<?php
session_start();
include 'db_connection.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Open database connection
$conn = OpenCon(); 
$today = date('Y-m-d');
// Retrieve user's username from session
$username = $_SESSION['Username'];

// Get the user's email from the database (assuming you have an 'email' field in your 'users' table)
$emailQuery = "SELECT email FROM users WHERE username = '$username'";
$emailResult = mysqli_query($conn, $emailQuery);
$emailRow = mysqli_fetch_assoc($emailResult);
$userEmail = $emailRow['email'];

// Check if pay now button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_now'])) {
    // Check if the shipping option is selected
    if (isset($_POST['shipping_option'])) {
        // Retrieve selected shipping option
        $shippingOption = $_POST['shipping_option'];
        
        // Retrieve product IDs and quantities from the shopping cart
        $productQuantities = [];
        $fetchCartItemsSql = "SELECT productid, quantity FROM shoppingcart WHERE username = '$username' AND checked = 0";
        $cartItemsResult = mysqli_query($conn, $fetchCartItemsSql);
        while ($row = mysqli_fetch_assoc($cartItemsResult)) {
            $productQuantities[$row['productid']] = $row['quantity'];
        }
        
        // Calculate the total price
        $totalPrice = 0;
        foreach ($productQuantities as $productId => $quantity) {
            $fetchProductPriceSql = "SELECT price FROM products WHERE id = $productId";
            $productPriceResult = mysqli_query($conn, $fetchProductPriceSql);
            $productPrice = mysqli_fetch_assoc($productPriceResult)['price'];
            $totalPrice += $productPrice * $quantity;
        }
        
        // Add shipping cost if shipping option is selected
        if ($shippingOption === 'Shipping') {
            $totalPrice += 50;
        }
        
        // Update inventory in the products table
        foreach ($productQuantities as $productId => $quantity) {
            $updateInventorySql = "UPDATE products SET inventory = inventory - $quantity WHERE id = $productId";
            mysqli_query($conn, $updateInventorySql);
        }
        
        // Get the current max id from the orders table
        $getMaxIdSql = "SELECT MAX(id) as max_id FROM orders";
        $maxIdResult = mysqli_query($conn, $getMaxIdSql);
        $maxIdRow = mysqli_fetch_assoc($maxIdResult);
        $c = $maxIdRow['max_id'] + 1; // Increment the max id by 1
        

        // Insert the order into the database with the incremented id
        $insertOrderSql = "INSERT INTO orders (username, typeofshipping, totaleprice, id, pay, date) VALUES (?, ?, ?, ?, 0, ?)";
        $stmt = $conn->prepare($insertOrderSql);
        $stmt->bind_param('ssdss', $username, $shippingOption, $totalPrice, $c, $today);
        $stmt->execute();
        $stmt->close();
        
        // Update the shopping cart to link the products with the new order id and mark them as checked
        $updateCheckedSql = "UPDATE shoppingcart SET orderid = $c, checked = 1 WHERE username = '$username' AND checked = 0";
        mysqli_query($conn, $updateCheckedSql);
        
        // Retrieve order details for display
        $orderDetailsSql = "SELECT * FROM orders WHERE id = '$c' AND username = '$username'";
        $orderDetailsResult = mysqli_query($conn, $orderDetailsSql);
        $orderDetails = mysqli_fetch_assoc($orderDetailsResult);
        
        // Display order details
        if ($orderDetails) {
           
            // Prepare the email content
            $subject = "Your Order Confirmation - Order ID: " . $orderDetails['id'];
            $message = "Dear " . $orderDetails['username'] . ",\n\n";
            $message .= "Thank you for your order. Here are your order details:\n";
            $message .= "Order ID: " . $orderDetails['id'] . "\n";
            $message .= "Shipping Option: " . $orderDetails['typeofshipping'] . "\n";
            $message .= "Total Price: $" . $orderDetails['totaleprice'] . "\n\n";
            $message .= "We will notify you once your order has been shipped.\n";
            $message .= "Thank you for shopping with us!\n";
            $message .= "Best regards,\n";
            $message .= "Your Company Name";
            
            // Send the email
            mail($userEmail, $subject, $message, "From: no-reply@yourcompany.com");
            
            // Display confirmation message on the web page
            echo "<div class='order-details-container'>";
            echo "<h2>Your Order Details:</h2>";
            echo "<p><strong>Order ID:</strong> " . $orderDetails['id'] . "</p>";
            echo "<p><strong>Username:</strong> " . $orderDetails['username'] . "</p>";
            echo "<p><strong>Shipping Option:</strong> " . $orderDetails['typeofshipping'] . "</p>";
            echo "<p><strong>Total Price:</strong> $" . $orderDetails['totaleprice'] . "</p>";
            echo "<p>A confirmation email has been sent to your email address: " . $userEmail . "</p>";
            echo "<button class='go-back-btn' onclick=\"redirectToProducts()\">Go to Products</button>";
            echo "</div>";
        } else {
            echo "<p>Failed to retrieve order details.</p>";
        }
    } else {
        echo "<p>Please select a shipping option.</p>";
    }
}

CloseCon($conn);
?>

<script>
    function redirectToProducts() {
        window.location.href = 'products.php';
    }
</script>

<script>
    function redirectToProducts() {
        window.location.href = 'products.php';
    }
</script>

<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.order-details-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.order-details-container h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

.order-details-container p {
    font-size: 18px;
    margin: 10px 0;
    color: #555;
}

.order-details-container p strong {
    color: #333;
}

.go-back-btn {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s;
}

.go-back-btn:hover {
    background-color: #45a049;
}
</style>
