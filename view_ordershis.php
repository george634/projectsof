<?php
session_start();
include 'db_connection.php';
include 'navbar.footer.php';

// Redirect to login page if the user is not logged in


// Open database connection
$conn = OpenCon();

// Retrieve user's username from session
$username = $_SESSION['Username'];

// Fetch all completed (paid) orders for the current user
$fetchOrdersSql = "
    SELECT o.id, o.typeofshipping, o.totaleprice, p.pname, p.price, p.img, sc.quantity
    FROM orders o
    JOIN shoppingcart sc ON o.id = sc.orderid
    JOIN products p ON sc.productid = p.id
    WHERE o.username = '$username' AND o.pay = 1
    ORDER BY o.id DESC
";
$ordersResult = mysqli_query($conn, $fetchOrdersSql);

if (mysqli_num_rows($ordersResult) > 0) {
    echo "<h2>Your Order History</h2>";

    while ($row = mysqli_fetch_assoc($ordersResult)) {
        echo "<div class='order-container'>";
        echo "<h3>Order ID: " . $row['id'] . "</h3>";
        echo "<p><strong>Shipping Option:</strong> " . $row['typeofshipping'] . "</p>";
        echo "<p><strong>Total Price:</strong> $" . $row['totaleprice'] . "</p>";
        echo "<hr>";
        echo "<div class='product-details'>";
        echo "<img src='{$row['img']}' alt='{$row['pname']}'>";
        echo "<div>";
        echo "<p><strong>Product:</strong> " . $row['pname'] . "</p>";
        echo "<p><strong>Price:</strong> $" . $row['price'] . "</p>";
        echo "<p><strong>Quantity:</strong> " . $row['quantity'] . "</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>"; // Close the order container
    }
} else {
    // If there are no completed orders, show an alert and redirect
    echo "<script>
        alert('You have no completed orders.');
        window.location.href = 'userdata.php'; // Redirect to user data page
    </script>";
}

// Close database connection
CloseCon($conn);
?>
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
</style>