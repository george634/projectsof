<?php
session_start();
include 'db_connection.php';
include 'navbar.footer.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Open database connection
$conn = OpenCon();

// Retrieve user's username from session
$username = $_SESSION['Username'];

// Fetch all orders for the current user, including product details
$fetchOrdersSql = "
    SELECT o.id, o.typeofshipping, o.totaleprice, p.pname, p.price, p.img, sc.quantity
    FROM orders o
    JOIN shoppingcart sc ON o.id = sc.orderid
    JOIN products p ON sc.productid = p.id
    WHERE o.username = '$username' AND o.pay = 0
    ORDER BY o.id DESC
";
$ordersResult = mysqli_query($conn, $fetchOrdersSql);

if (mysqli_num_rows($ordersResult) > 0) {
    echo "<h2>Your Orders</h2>";

    // Start the form
    echo "<form action='CheckCancel.php' method='POST' id='ordersForm'>";

    $currentOrderId = null;

    while ($row = mysqli_fetch_assoc($ordersResult)) {
        if ($currentOrderId !== $row['id']) {
            if ($currentOrderId !== null) {
                // Add a check order button and cancel button for the previous order and close the container
                echo "<div class='order-actions'>";
                echo "<button type='submit' name='order_check[]' value='{$currentOrderId}' class='check-btn' onclick='return confirmCheckOrder()'>Check Order</button>";
                echo "<button type='submit' name='cancel_order[]' value='{$currentOrderId}' class='cancel-btn' onclick='return confirmCancelOrder()'>Cancel Order</button>";
                echo "</div>";
                echo "</div>"; // Close the previous order container
            }
            // Start a new order container
            echo "<div class='order-container'>";
            echo "<h3>Order ID: " . $row['id'] . "</h3>";
            echo "<p><strong>Shipping Option:</strong> " . $row['typeofshipping'] . "</p>";
            echo "<p><strong>Total Price:</strong> $" . $row['totaleprice'] . "</p>";
            echo "<hr>";
            $currentOrderId = $row['id'];
        }

        // Display the product details with image and price
        echo "<div class='product-details'>";
        echo "<img src='{$row['img']}' alt='{$row['pname']}'>";
        echo "<div>";
        echo "<p><strong>Product:</strong> " . $row['pname'] . "</p>";
        echo "<p><strong>Price:</strong> $" . $row['price'] . "</p>";
        echo "<p><strong>Quantity:</strong> " . $row['quantity'] . "</p>";
        echo "</div>";
        echo "</div>";
    }

    // Ensure the actions for the last order are added
    if ($currentOrderId !== null) {
        echo "<div class='order-actions'>";
        echo "<button type='submit' name='order_check[]' value='{$currentOrderId}' class='check-btn' onclick='return confirmCheckOrder()'>Check Order</button>";
        echo "<button type='submit' name='cancel_order[]' value='{$currentOrderId}' class='cancel-btn' onclick='return confirmCancelOrder()'>Cancel Order</button>";
        echo "</div>";
        echo "</div>"; // Close the last order container
    }

    echo "</form>"; // Close the form

} else {
    echo "<script>
    alert('You have no orders.');
    window.location.href = 'userdata.php'; // Redirect to user data page
</script>";
}

// Close database connection
CloseCon($conn);
?>

<script>
function confirmCheckOrder() {
    return confirm('Are you sure you have received this order?');
}

function confirmCancelOrder() {
    return confirm('Are you sure you want to cancel this order? This action cannot be undone.');
}
</script>

<style>
/* Order container styling */
.order-container {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.order-container h3 {
    margin-top: 0;
    font-size: 18px;
    color: #333;
}

.order-container p {
    margin: 5px 0;
    color: #555;
}

.order-container img {
    max-width: 100px;
    border-radius: 5px;
    margin-right: 10px;
}

.product-details {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.product-details div {
    margin-left: 10px;
}

.order-container hr {
    border: 0;
    border-top: 1px solid #eee;
    margin: 10px 0;
}

/* Order actions container */
.order-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

/* Check Order button styling */
.check-btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.check-btn:hover {
    background-color: #45a049;
}

/* Cancel button styling */
.cancel-btn {
    background-color: #f44336;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cancel-btn:hover {
    background-color: #e53935;
}
</style>
