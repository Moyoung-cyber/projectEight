<?php
if (!isset($conn) || !$conn) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "shop";
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

if (!function_exists('completeOrderStockUpdate')) {
function completeOrderStockUpdate($conn, $order_id) {
    $order_id = (int) $order_id;

    // Get invoice_number from user_order (this is the reliable link to order_status)
    $get_invoice = "SELECT invoice_number FROM `user_order` WHERE `order_id` = $order_id";
    $inv_result = mysqli_query($conn, $get_invoice);
    if (!$inv_result || mysqli_num_rows($inv_result) == 0) return;
    $inv_row = mysqli_fetch_assoc($inv_result);
    $invoice_number = (int) $inv_row['invoice_number'];

    // Update order_status using invoice_number (not order_id, since order_status.order_id is auto-incremented independently)
    $update_order_status = "UPDATE `order_status` SET `order_status` = 'complete' WHERE `invoice_number` = $invoice_number";
    mysqli_query($conn, $update_order_status);

    // Fetch ordered products and quantities
    $get_details = "SELECT product_id, quantity FROM `order_status` WHERE `invoice_number` = $invoice_number";
    $result = mysqli_query($conn, $get_details);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = (int) $row['product_id'];
            $quantity = (int) $row['quantity'];
            if ($product_id > 0 && $quantity > 0) {
                $update_stock = "UPDATE `products` SET `product_in_store` = `product_in_store` - $quantity WHERE `id` = $product_id";
                mysqli_query($conn, $update_stock);
            }
        }
    }
}
}
?>