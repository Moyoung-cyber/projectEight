<?php
include ("./include/connect_database.php");
// test_secret_key_e78b6608052b4d388f6455ae0df8d9b7
// test_public_key_3349c4f953df407591d450fb1a890d90
// https://admin.khalti.com/



$error_message = "";
$khalti_public_key = "test_public_key_3349c4f953df407591d450fb1a890d90";


// Check if the order_id is set in the GET request
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order and product details
    $select_product = "SELECT user_order.*, products.product_name, products.product_price 
                       FROM `user_order` 
                       INNER JOIN `products` ON user_order.product_id = products.id 
                       WHERE user_order.order_id = $order_id";
    $result = mysqli_query($conn, $select_product);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $product_id = $row['product_id'];
        $product_price = $row['product_price'];
        $product_name = $row['product_name'];
    } else {
        echo "No order found with the given ID.";
        exit;
    }
} else {
    echo "Order ID not specified.";
    exit;
}

$amount = $product_price * 100; // Amount in paisa
$uniqueProductId = $product_id;
$uniqueUrl = "http://localhost/projectSixth/user_area/product/$product_id";
$uniqueProductName = $product_name;
$successRedirect = "http://localhost/projectSixth/user_area/profile.php?user_order";

function checkValid($data)
{
    global $amount;
    return (float) $data["amount"] == $amount;
}

// Handle payment initiation
if (isset($_POST["mobile"]) && isset($_POST["mpin"])) {
    try {
        $mobile = $_POST["mobile"];
        $mpin = $_POST["mpin"];
        $price = (float) $amount;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://khalti.com/api/v2/payment/initiate/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "public_key" => $khalti_public_key,
                "mobile" => $mobile,
                "transaction_pin" => $mpin,
                "amount" => $amount,
                "product_identity" => $uniqueProductId,
                "product_name" => $uniqueProductName,
                "product_url" => $uniqueUrl
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $parsed = json_decode($response, true);

        if (isset($parsed["token"])) {
            $token = $parsed["token"];
        } else {
            $error_message = "Incorrect mobile or mpin";
        }
    } catch (Exception $e) {
        $error_message = "Incorrect mobile or mpin";
    }
}

// Handle OTP verification
if (isset($_POST["otp"]) && isset($_POST["token"]) && isset($_POST["mpin"])) {
    try {
        $otp = $_POST["otp"];
        $token = $_POST["token"];
        $mpin = $_POST["mpin"];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://khalti.com/api/v2/payment/confirm/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "public_key" => $khalti_public_key,
                "transaction_pin" => $mpin,
                "confirmation_code" => $otp,
                "token" => $token
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $parsed = json_decode($response, true);

        if (isset($parsed["token"]) && checkValid($parsed)) {
            // Payment is valid
            $update_order = "UPDATE `user_order` SET `order_status` = 'complete' WHERE `order_id` = $order_id";
            if (mysqli_query($conn, $update_order)) {
                $error_message = "<span style='color:green'>Payment success</span> <script> window.location='" . $successRedirect . "'; </script>";
            } else {
                $error_message = "Error updating order status: " . mysqli_error($conn);
            }
        } else {
            $error_message = "Could not process the transaction at the moment.";
            if (isset($parsed["detail"])) {
                $error_message = $parsed["detail"];
            }
        }
    } catch (Exception $e) {
        $error_message = "Could not process the transaction at the moment.";
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
    margin: 0;
    padding: 20px;
}

.khalticontainer {
    width: 400px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(99,102,241,0.12), 0 1.5px 8px rgba(35,39,47,0.08);
    margin: 50px auto;
    padding: 2.5rem 2rem;
    text-align: center;
}

.khalticontainer img {
    margin-bottom: 1.5rem;
    border-radius: 12px;
}

.khalticontainer h2 {
    color: #23272f;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.khalticontainer small {
    display: block;
    text-align: left;
    font-weight: 600;
    color: #23272f;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.khalticontainer input {
    display: block;
    width: 100%;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    background: #f9fafb;
    transition: border 0.2s;
    box-sizing: border-box;
}

.khalticontainer input:focus {
    border-color: #6366f1;
    outline: none;
    background: #fff;
}

.khalticontainer input[type="password"] {
    letter-spacing: 0.3em;
}

.khalticontainer button {
    display: block;
    background: linear-gradient(90deg, #5C2D91 60%, #7c3aed 100%);
    border: none;
    color: white;
    cursor: pointer;
    width: 100%;
    padding: 0.75rem 1rem;
    margin: 1rem 0;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    transition: background 0.2s;
}

.khalticontainer button:hover {
    background: linear-gradient(90deg, #4c1d95 60%, #6d28d9 100%);
}

.khalticontainer .error-message {
    display: block;
    color: #e11d48;
    font-size: 0.9rem;
    margin: 0.5rem 0;
    padding: 0.5rem;
    background: #fef2f2;
    border-radius: 6px;
    border-left: 3px solid #e11d48;
}

.khalticontainer .success-message {
    display: block;
    color: #059669;
    font-size: 0.9rem;
    margin: 0.5rem 0;
    padding: 0.5rem;
    background: #f0fdf4;
    border-radius: 6px;
    border-left: 3px solid #059669;
}

.khalticontainer .info-text {
    color: #6b7280;
    font-size: 0.8rem;
    margin-top: 1rem;
    line-height: 1.4;
}

.price-display {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #23272f;
    font-size: 1.1rem;
}
</style>

<div class="khalticontainer">
    <center>
        <div><img src="khalti.png" alt="khalti" width="200"></div>
    </center>
    <h2>Complete Your Payment</h2>
    <?php if (!isset($token) || $token == "") { ?>
    <form action="khalti_payment.php?order_id=<?php echo $order_id; ?>" method="post">
        <small>Mobile Number:</small>
        <input type="number" class="number" minlength="10" maxlength="10" name="mobile" placeholder="98xxxxxxxx" required>
        
        <small>Khalti Mpin:</small>
        <input type="password" class="mpin" name="mpin" minlength="4" maxlength="6" placeholder="xxxx" required>
        
        <small>Price:</small>
        <div class="price-display">Rs. <?php echo $product_price; ?></div>
        <input type="hidden" class="price" name="amount" value="<?php echo $product_price; ?>">
        
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <button type="submit">Pay Rs. <?php echo $product_price; ?></button>
        
        <div class="info-text">
            We don't store your credentials for security reasons. You will have to re-enter your details every time.
        </div>
    </form>
    <?php } else { ?>
    <form action="khalti_payment.php?order_id=<?php echo $order_id; ?>" method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="hidden" name="mpin" value="<?php echo $mpin; ?>">
        
        <small>OTP:</small>
        <input type="number" value="" name="otp" placeholder="xxxx" required>
        
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <button type="submit">Pay Rs. <?php echo $product_price; ?></button>
    </form>
    <?php } ?>
</div>
