<?php
include ('../header.php');
include ("../include/connect_database.php");
@session_start();

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $select_data = "SELECT * FROM `user_order` WHERE order_id = $order_id";
    $query_select = mysqli_query($conn, $select_data);
    $row_fetch = mysqli_fetch_assoc($query_select);
    $invoice_number = $row_fetch['invoice_number'];
    $amount_due = $row_fetch['amount_due'];
}

if (isset($_POST["conform_payment"])) {
    $invoice_number = $_POST['invoice_number'];
    $amount = $_POST['amount'];
    $payment_mode = $_POST['payment_mode'];

    // Insert payment details
    $insert_query = "INSERT INTO `user_payments` (order_id, invoice_number, amount, payment_mode) 
                     VALUES ('$order_id', '$invoice_number', '$amount', '$payment_mode')";
    $result = mysqli_query($conn, $insert_query);

    if (!$result) {
        echo "<h1 class='heading'>Error occurred while processing your payment</h1>";
        echo "<p>Please try again later.</p>";
        echo "<p>Error message: " . mysqli_error($conn) . "</p>";
    } else {
        echo "<script>alert('Successfully completed the payment')</script>";

        if ($payment_mode == "Cash on delivery") {
            // Update order status for Cash on Delivery
            $update_order = "UPDATE `user_order` SET order_status = 'complete' WHERE order_id = $order_id";
            $result_order = mysqli_query($conn, $update_order);

            if ($result_order) {
                echo "<script>window.location.href='profile.php?user_order';</script>";
            } else {
                echo "<h1 class='heading'>Error updating order status</h1>";
                echo "<p>Error message: " . mysqli_error($conn) . "</p>";
            }
        } else if ($payment_mode == "Khalti") {
            // Redirect to Khalti payment page after insertion
            echo "<script>window.location.href='khalti_payment.php?order_id=$order_id';</script>";
            exit;
        } else if ($payment_mode == "Stripe") {
            // Redirect to Stripe payment page after insertion
            echo "<script>window.location.href='stripe_payment.php?order_id=$order_id';</script>";
            exit;
        }
    }
}
?>

<style>
body {
    min-height: 100vh;
    background: linear-gradient(120deg, #6366f1 0%, #a5b4fc 100%);
    background-attachment: fixed;
    background-repeat: no-repeat;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.payment-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5rem 0;
    margin-top: 2rem;
}

.payment-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(99,102,241,0.12), 0 1.5px 8px rgba(35,39,47,0.08);
    padding: 2.5rem 2rem;
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
}

.payment-card .heading {
    font-size: 2rem;
    font-weight: 700;
    color: #6366f1;
    margin-bottom: 2rem;
    text-align: center;
}

.payment-card .form-outline {
    margin-bottom: 1.5rem;
}

.payment-card .form-control, .payment-card .form-select {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    background: #f9fafb;
    transition: border 0.2s;
    width: 100%;
    box-sizing: border-box;
}

.payment-card .form-control:focus, .payment-card .form-select:focus {
    border-color: #6366f1;
    outline: none;
    background: #fff;
}

.payment-card .form-control[readonly] {
    background: #f3f4f6;
    color: #6b7280;
    font-weight: 600;
}

.payment-card label {
    font-weight: 500;
    color: #23272f;
    margin-bottom: 0.5rem;
    display: block;
}

.payment-card .btn.read-more {
    background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    transition: background 0.2s;
    width: 100%;
    margin-top: 1rem;
}

.payment-card .btn.read-more:hover {
    background: linear-gradient(90deg, #4f46e5 60%, #818cf8 100%);
    color: #fff;
}

.payment-info {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.payment-info .amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #6366f1;
}

.payment-info .invoice {
    font-size: 1rem;
    color: #6b7280;
    margin-top: 0.5rem;
}

.payment-methods {
    margin-bottom: 1.5rem;
}

.payment-methods .form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}
</style>

<section class="payment-container">
    <div class="payment-card">
        <h1 class="heading">Confirm Payment</h1>
        
        <div class="payment-info">
            <div class="amount">Rs. <?php echo $amount_due; ?></div>
            <div class="invoice">Invoice: <?php echo $invoice_number; ?></div>
        </div>
        
        <form id="paymentForm" action="" method="post">
            <input type="hidden" value="<?php echo $invoice_number ?>" name="invoice_number">
            <input type="hidden" value="<?php echo $amount_due ?>" name="amount">
            
            <div class="payment-methods">
                <label for="paymentMode">Select Payment Method</label>
                <select name="payment_mode" class="form-select" id="paymentMode">
                    <option value="select a option" disabled selected>Choose your payment method</option>
                    <option value="Cash on delivery">💵 Cash on Delivery</option>
                    <option value="Khalti">💜 Khalti Digital Wallet</option>
                    <option value="Stripe">💳 Credit/Debit Card</option>
                </select>
            </div>
            
            <input type="submit" class="btn read-more" value="Confirm Payment" name="conform_payment">
        </form>
    </div>
</section>

<?php include ("../Footer.php"); ?>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(event) {
    var paymentMode = document.getElementById('paymentMode').value;

    if (paymentMode === 'select a option') {
        event.preventDefault();
        alert('Please select a valid payment method.');
    } else if (paymentMode === 'Khalti') {
        event.preventDefault(); // Prevent form submission
        window.location.href = 'khalti_payment.php?order_id=<?php echo $order_id; ?>';
    } else if (paymentMode === 'Stripe') {
        event.preventDefault(); // Prevent form submission
        window.location.href = 'stripe_payment.php?order_id=<?php echo $order_id; ?>';
    }
});
</script>