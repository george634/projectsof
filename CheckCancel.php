<?php
session_start();
include 'db_connection.php';

// Redirect to login page if user is not logged in

// Open database connection
$conn = OpenCon();

// Retrieve user's username from session
$username = $_SESSION['Username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_order'])) {
        foreach ($_POST['cancel_order'] as $orderId) {
            // Retrieve the products and their quantities for the order
            $getOrderItemsSql = "
                SELECT sc.productid, sc.quantity 
                FROM shoppingcart sc 
                WHERE sc.orderid = ? AND sc.username = ?";
            $stmt = $conn->prepare($getOrderItemsSql);
            $stmt->bind_param('is', $orderId, $username);
            $stmt->execute();
            $orderItemsResult = $stmt->get_result();
            $stmt->close();

            // Update the inventory for each product in the canceled order
            while ($item = $orderItemsResult->fetch_assoc()) {
                $updateInventorySql = "UPDATE products SET inventory = inventory + ? WHERE id = ?";
                $stmt = $conn->prepare($updateInventorySql);
                $stmt->bind_param('ii', $item['quantity'], $item['productid']);
                $stmt->execute();
                $stmt->close();
            }

            // Delete the items related to the order from shoppingcart
            $deleteCartItemsSql = "DELETE FROM shoppingcart WHERE orderid = ? AND username = ?";
            $stmt = $conn->prepare($deleteCartItemsSql);
            $stmt->bind_param('is', $orderId, $username);
            $stmt->execute();
            $stmt->close();

            // Delete the order from the orders table
            $deleteOrderSql = "DELETE FROM orders WHERE id = ? AND username = ? AND pay = 0";
            $stmt = $conn->prepare($deleteOrderSql);
            $stmt->bind_param('is', $orderId, $username);
            $stmt->execute();
            $stmt->close();
        }

        // Redirect to the orders page after deletion
        echo "<script>alert('Order has been canceled.'); window.location.href='view_orders.php';</script>";
        exit;
    }
    if (isset($_POST['order_check'])) {
        foreach ($_POST['order_check'] as $orderId) {
            // Update the 'pay' field to 1 for the selected order
            $updatePaySql = "UPDATE orders SET pay = 1 WHERE id = ? AND username = ?";
            $stmt = $conn->prepare($updatePaySql);
            $stmt->bind_param('is', $orderId, $username);
            $stmt->execute();
            $stmt->close();
        }

        // Redirect to the orders page after updating
        echo "<script>alert('Order has been marked as received.'); window.location.href='view_orders.php';</script>";
        exit;
    }
}


// Close database connection
CloseCon($conn);
?>
