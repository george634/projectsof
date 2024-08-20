<?php
include 'navbar.footer.php';
include 'db_connection.php';

// Start session

// Redirect user to login page if not logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Include database connection
$conn = OpenCon(); // Open database connection

$username = $_SESSION['Username'];

// Check if product removal request is sent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removeProductId'])) {
    $removeProductId = $_POST['removeProductId'];
    
    // Initialize removed quantity variable
    $removedQuantity = 0;

    // Fetch the quantity of the specified product from the shopping cart
    $fetchQuantitySql = "SELECT quantity FROM shoppingcart WHERE username = '$username' AND productid = $removeProductId LIMIT 1";
    $quantityResult = mysqli_query($conn, $fetchQuantitySql);
    $quantityRow = mysqli_fetch_assoc($quantityResult);

    // Check if quantity row exists
    if ($quantityRow) {
        $removedQuantity = $quantityRow['quantity'];

        // Update shopping cart to remove all quantity of the specified product
        $removeSql = "DELETE FROM shoppingcart WHERE username = '$username' AND productid = $removeProductId";
        mysqli_query($conn, $removeSql);
    }
}

// Query to fetch products from shopping cart for the logged-in user
$sql = "SELECT sc.productid, SUM(sc.quantity) AS total_quantity, p.pname, p.price, p.color, p.weight, p.inventory, p.img
        FROM shoppingcart sc
        INNER JOIN products p ON sc.productid = p.id
        WHERE sc.username = '$username' AND sc.checked = 0
        GROUP BY sc.productid";

$result = mysqli_query($conn, $sql);

// Check if any products found in the shopping cart
$totalPrice = 0; // Initialize total price variable
$inventoryIssue = false; // Flag to check if any inventory issue exists
$inventoryIssues = []; // Array to store issues

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if the total quantity in the cart exceeds the available inventory
        if ($row['total_quantity'] > $row['inventory']) {
            $inventoryIssue = true;
            $inventoryIssues[] = "Product: {$row['pname']} (Requested: {$row['total_quantity']}, Available: {$row['inventory']})";
        } 
            $totalPrice += $row['price'] * $row['total_quantity']; // Calculate total price
            echo "<div class='product'>";
            echo "<img src='{$row['img']}' alt='{$row['pname']}'>";
            echo "<div class='product-details'>";
            echo "<p><strong>Name:</strong> {$row['pname']}</p>";
            echo "<p><strong>Price:</strong> {$row['price']}</p>";
            echo "<p><strong>Color:</strong> {$row['color']}</p>";
            echo "<p><strong>Weight:</strong> {$row['weight']}</p>";
            echo "<p><strong>Inventory:</strong> {$row['inventory']}</p>";
            echo "<p><strong>Total Quantity:</strong> {$row['total_quantity']}</p>";
            // Add remove button with form to handle removal
            echo "<form method='post'>";
            echo "<input type='hidden' name='removeProductId' value='{$row['productid']}'>";
            echo "<input type='submit' value='Remove'>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
       
    }

    if ($inventoryIssue) {
        // Convert the issues array to a string
        $issuesMessage = implode("\\n", $inventoryIssues);
        
        // Show an alert with the inventory issues
        echo "<script>alert('Inventory issues detected:\\n$issuesMessage\\nPlease adjust your cart to proceed.'); </script>";
       
    } else{
        echo "<div class='total-price-container'>";
        echo "<div class='total-price'>Total Price: $$totalPrice</div>";
        echo "<div class='shipping-options'>";
        echo "<form action='payment.php' method='post'>";
        echo '<div class="shipping-option">';
        echo '<input type="radio" id="shipping" name="shipping_option" value="Shipping" onclick="showPaymentModal()" required>';
        echo '<label for="shipping">Shipping</label>';
        echo '</div>';
        echo '<div class="shipping-option">';
        echo '<input type="radio" id="pickup" name="shipping_option" value="Pickup" onclick="hidePaymentModal()" required>';
        echo '<label for="pickup">Pickup</label>';
        echo '</div>';
        echo "<br>";
        echo "<input type='submit' name='pay_now' value='Pay Now' class='pay-now-btn'>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
        // Display total price and payment form if no issues
      
    
} else {
    echo "<script>alert('Your shopping cart is empty.'); window.location.href = 'products.php';</script>";
    exit; 
}

CloseCon($conn);
?>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePaymentModal()">&times;</span>
        <h3>Payment Details</h3>
        <form action="payment.php" method="post">
            <label for="amount">Amount to Pay</label>
            <input type="text" id="amount" name="amount" value="<?php echo $totalPrice; ?>" readonly>

            <!-- Payment Logos -->
            <div class="payment-logos">
                <img src="visa.png" alt="Visa" class="payment-logo">
                <!-- Add other logos similarly -->
            </div>

            <label for="cardNumber">Card Number</label>
            <input type="text" id="cardNumber" name="cardNumber" placeholder="Enter your card number" required>
            <label for="expiryDate">Expiry Date</label>
            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" required>
            <label for="cvc">CVC</label>
            <input type="text" id="cvc" name="cvc" placeholder="CVC" required>

            <!-- Hidden input to ensure 'Shipping' is set -->
            <input type="hidden" name="shipping_option" value="Shipping">

            <input type="submit" name="pay_now" value="Pay Now" class="pay-now-btn">
        </form>
    </div>
</div>

<script>
function showPaymentModal() {
    document.getElementById('paymentModal').style.display = 'block';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    document.querySelector('input[name="shipping_option"][value="Shipping"]').checked = false;
}

function hidePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}
</script>

<style>
/* Include your styling here as previously defined */
</style>

<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.product-details form {
    margin-top: 10px;
}

.product-details form input[type="submit"] {
    background-color: #ff6347; /* Red color for remove button */
    color: #fff; /* White text color */
    padding: 5px 10px; /* Padding around the button text */
    border: none; /* Remove border */
    border-radius: 3px; /* Rounded corners */
    cursor: pointer; /* Cursor style */
    transition: background-color 0.3s; /* Smooth transition for background color */
}

.product-details form input[type="submit"]:hover {
    background-color: #d6341d; /* Darker red color on hover */
}

.product {
    width: 300px;
    margin: 20px auto;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #fff;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product img {
    width: 70%;
    height: auto;
    border-radius: 10px;
}

.product-details {
    padding: 15px;
    width: 100%;
    text-align: left;
}

.product-details p {
    margin: 10px 0;
    color: #333;
}

.product-details p strong {
    font-weight: bold;
}

.total-price {
    font-weight: bold;
    font-size: 24px;
    color: #333;
    margin-top: 20px;
    text-align: center;
}

.inventory-issues {
    margin-top: 20px;
    text-align: center;
    color: red;
}

/* Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
    padding-top: 100px;
}

.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.5s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-content h3 {
    margin-top: 0;
    font-size: 22px;
    color: #333;
    text-align: center;
}

.modal-content label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: bold;
}

/* Payment Logos */
.payment-logos {
    text-align: center;
    margin: 15px 0;
}

.payment-logo {
    max-height: 30px;
    margin: 0 10px;
}

.modal-content input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

.pay-now-btn {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s;
}

.pay-now-btn:hover {
    background-color: #45a049;
}
.total-price-container {
    text-align: center;
    margin-top: 20px;
}

.total-price {
    font-weight: bold;
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

.shipping-options {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 20px;
}

.shipping-option {
    display: flex;
    align-items: center;
    gap: 10px;
}

.shipping-option input[type="radio"] {
    margin: 0;
}

.shipping-option label {
    font-size: 18px;
    color: #555;
    cursor: pointer;
}

.pay-now-btn {
    width: 200px;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s;
}

.pay-now-btn:hover {
    background-color: #45a049;
}
</style>
