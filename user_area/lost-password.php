<?php
session_start();
include ("../include/connect_database.php");
include ("../config.php");

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';

if (isset($_POST['reset_request'])) {
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);

    $check_query = "SELECT * FROM user_table WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(32));

        $update_query = "UPDATE user_table SET reset_token = '$token', reset_expiry = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE user_email = '$user_email'";
        mysqli_query($conn, $update_query);

        $reset_link = BASE_URL . "user_area/reset-password.php?token=$token";

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ajayalama939@gmail.com';
        $mail->Password = 'gnuotwhelvwbkjnf';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('ajayalama939@gmail.com', 'GameBox');
        $mail->addAddress($user_email);
        $mail->Subject = 'Reset Your Password - GameBox';
        $mail->isHTML(true);
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin:0; padding:0; background-color:#f4f4f7; font-family:Arial, Helvetica, sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f7; padding:40px 0;">
                <tr>
                    <td align="center">
                        <table width="560" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                            <!-- Header -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #6366f1, #8b5cf6); padding:35px 40px; text-align:center;">
                                    <h1 style="margin:0; color:#ffffff; font-size:26px; font-weight:700; letter-spacing:0.5px;">GameBox</h1>
                                </td>
                            </tr>
                            <!-- Body -->
                            <tr>
                                <td style="padding:40px 40px 20px 40px;">
                                    <h2 style="margin:0 0 15px 0; color:#1a1a2e; font-size:22px; font-weight:600;">Password Reset Request</h2>
                                    <p style="margin:0 0 25px 0; color:#555555; font-size:15px; line-height:1.7;">
                                        We received a request to reset the password for your GameBox account. Click the button below to set a new password:
                                    </p>
                                </td>
                            </tr>
                            <!-- Button -->
                            <tr>
                                <td style="padding:0 40px 30px 40px; text-align:center;">
                                    <a href="' . $reset_link . '" style="display:inline-block; background-color:#6366f1; color:#ffffff; text-decoration:none; padding:14px 40px; border-radius:8px; font-size:16px; font-weight:600; letter-spacing:0.3px;">Reset My Password</a>
                                </td>
                            </tr>
                            <!-- Divider -->
                            <tr>
                                <td style="padding:0 40px;">
                                    <hr style="border:none; border-top:1px solid #e8e8ef; margin:0;">
                                </td>
                            </tr>
                            <!-- Info -->
                            <tr>
                                <td style="padding:25px 40px;">
                                    <p style="margin:0 0 10px 0; color:#888888; font-size:13px; line-height:1.6;">
                                        This link will expire in <strong style="color:#555555;">15 minutes</strong>.
                                    </p>
                                    <p style="margin:0; color:#888888; font-size:13px; line-height:1.6;">
                                        If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <!-- Footer -->
                        <table width="560" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:25px 40px; text-align:center;">
                                    <p style="margin:0; color:#999999; font-size:12px;">&copy; ' . date("Y") . ' GameBox. All rights reserved.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';

        if ($mail->send()) {
            $message = '<div class="alert alert-success">A password reset link has been sent to your email.</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to send email. Please try again later.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">No account found with that email address.</div>';
    }
}
?>

<?php include ("../header.php"); ?>

<section class="single-banner bg-light-white margin-top-header">
    <div class="container">
        <div class="content">
            <h1 class="heading">My Account</h1>
            <div class="breadcrumb m-0">
                <a href="../index.php">Home</a>
                <span>/</span>
                <span>My Account</span>
            </div>
        </div>
    </div>
</section>

<section class="login-user padding-top-section">
    <div class="container login-center-wrapper">
        <form class="login-card" action="" method="post">
            <h4 class="heading text-center mb-4">Reset Password</h4>
            <?php echo $message; ?>
            <p class="mb-3">Lost your password? Please enter your email address. You will receive a link to create a new password via email.</p>
            <div class="form-group">
                <label for="user_email">Email <span class="required">*</span></label>
                <input type="email" name="user_email" class="form-input" required>
            </div>
            <div class="form-row d-flex gap-2 align-items-center mb-3">
                <input type="submit" class="btn white-btn checkout-btn" value="RESET PASSWORD" name="reset_request">
                <a href="login-user.php" class="btn read-more">Back to Login</a>
            </div>
        </form>
    </div>
</section>

<?php include ("../Footer.php"); ?>
