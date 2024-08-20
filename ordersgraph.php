<?php
include 'db_connection.php';
include 'navbar.footer.php';

$con = OpenCon(); // Open database connection

// Check if a specific year and month are selected
$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selected_month = isset($_GET['month']) ? $_GET['month'] : null;

// Query to get the number of paid orders for each product in the selected month and year
$query = "
    SELECT p.id, p.pname, p.img, COUNT(sc.productid) AS order_count 
    FROM orders o
    JOIN shoppingcart sc ON o.id = sc.orderid
    JOIN products p ON sc.productid = p.id
    WHERE YEAR(o.date) = ? AND o.pay = 1" . ($selected_month ? " AND MONTH(o.date) = ?" : "") . "
    GROUP BY sc.productid
";
$stmt = $con->prepare($query);
if ($selected_month) {
    $stmt->bind_param("ii", $selected_year, $selected_month);
} else {
    $stmt->bind_param("i", $selected_year);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
$order_counts = [];
$product_images = [];

while ($row = $result->fetch_assoc()) {
    $products[] = "ID: " . $row['id'] . " - " . $row['pname'];
    $order_counts[] = $row['order_count'];
    $product_images[] = $row['img']; // Store the product image URLs
}

$stmt->close();
CloseCon($con); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }
        select, button {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Product Orders Graph - Year: <?php echo $selected_year; ?> <?php if ($selected_month) { echo "Month: " . date('F', mktime(0, 0, 0, $selected_month, 10)); } ?></h1>
    <form method="GET">
        <label for="year">Choose a year:</label>
        <select name="year" id="year">
            <?php
            for ($i = date('Y'); $i >= 2000; $i--) {
                echo "<option value=\"$i\"" . ($selected_year == $i ? ' selected' : '') . ">$i</option>";
            }
            ?>
        </select>
        <label for="month">Choose a month:</label>
        <select name="month" id="month">
            <option value="">All</option>
            <option value="1" <?php echo $selected_month == 1 ? 'selected' : ''; ?>>January</option>
            <option value="2" <?php echo $selected_month == 2 ? 'selected' : ''; ?>>February</option>
            <option value="3" <?php echo $selected_month == 3 ? 'selected' : ''; ?>>March</option>
            <option value="4" <?php echo $selected_month == 4 ? 'selected' : ''; ?>>April</option>
            <option value="5" <?php echo $selected_month == 5 ? 'selected' : ''; ?>>May</option>
            <option value="6" <?php echo $selected_month == 6 ? 'selected' : ''; ?>>June</option>
            <option value="7" <?php echo $selected_month == 7 ? 'selected' : ''; ?>>July</option>
            <option value="8" <?php echo $selected_month == 8 ? 'selected' : ''; ?>>August</option>
            <option value="9" <?php echo $selected_month == 9 ? 'selected' : ''; ?>>September</option>
            <option value="10" <?php echo $selected_month == 10 ? 'selected' : ''; ?>>October</option>
            <option value="11" <?php echo $selected_month == 11 ? 'selected' : ''; ?>>November</option>
            <option value="12" <?php echo $selected_month == 12 ? 'selected' : ''; ?>>December</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <canvas id="ordersGraph"></canvas>
</div>

<script>
  const ctx = document.getElementById('ordersGraph').getContext('2d');
const products = <?php echo json_encode($products); ?>;
const productImages = <?php echo json_encode($product_images); ?>;
const orderCounts = <?php echo json_encode($order_counts); ?>;

const ordersGraph = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: products,
        datasets: [{
            label: 'Number of Orders',
            data: orderCounts,
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            tooltip: {
                enabled: false,
                external: function(context) {
                    let tooltipEl = document.getElementById('chartjs-tooltip');

                    // Create tooltip element if it doesn't exist
                    if (!tooltipEl) {
                        tooltipEl = document.createElement('div');
                        tooltipEl.id = 'chartjs-tooltip';
                        tooltipEl.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
                        tooltipEl.style.borderRadius = '3px';
                        tooltipEl.style.color = 'white';
                        tooltipEl.style.opacity = 1;
                        tooltipEl.style.position = 'absolute';
                        tooltipEl.style.transform = 'translate(-50%, 0)';
                        tooltipEl.style.transition = 'opacity 0.3s ease';
                        document.body.appendChild(tooltipEl);
                    }

                    const tooltipModel = context.tooltip;

                    if (tooltipModel.opacity === 0) {
                        tooltipEl.style.opacity = 0;
                        return;
                    }

                    if (tooltipModel.body) {
                        const index = tooltipModel.dataPoints[0].dataIndex;
                        const title = tooltipModel.title || [];
                        const imgUrl = productImages[index];
                        const productId = products[index].split(' - ')[0];
                        const orderCount = orderCounts[index];
                        const innerHtml = `
                            <div style="display:flex; flex-direction:column; align-items:center;">
                                <strong>ID: ${productId}</strong><br>
                                ${title}<br>
                                <p>Number of Orders: ${orderCount} orders</p>
                                <p>Image:</p>
                                <img src="${imgUrl}" alt="Product Image" style="width: 50px; height: 50px; margin-top: 5px;">
                            </div>`;

                        tooltipEl.innerHTML = innerHtml;
                    }

                    const position = context.chart.canvas.getBoundingClientRect();
                    tooltipEl.style.opacity = 1;
                    tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
                    tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
                }
            }
        }
    }
});
</script>

</body>
</html>
