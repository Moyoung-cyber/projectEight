<?php
session_start();
include ("../include/connect_database.php");
include ("../config.php");

$message = '';
$valid_token = false;

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);

    $check_query = "SELECT * FROM user_table WHERE reset_token = '$token' AND reset_expiry > NOW()";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $valid_token = true;
    } else {
        $message = '<div class="alert alert-danger">Invalid or expired reset link. Please <a href="lost-password.php">request a new one</a>.</div>';
    }
} else {
    header("Location: lost-password.php");
    exit();
}

if (isset($_POST['reset_password']) && $valid_token) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $message = '<div class="alert alert-danger">Passwords do not match.</div>';
    } elseif (strlen($new_password) < 6) {
        $message = '<div class="alert alert-danger">Password must be at least 6 characters long.</div>';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $escaped_password = mysqli_real_escape_string($conn, $hashed_password);
        $escaped_token = mysqli_real_escape_string($conn, $token);

        $update_query = "UPDATE user_table SET user_password = '$escaped_password', reset_token = NULL, reset_expiry = NULL WHERE reset_token = '$escaped_token'";
        $result = mysqli_query($conn, $update_query);

        if ($result) {
            $message = '<div class="alert alert-success">Password has been reset successfully. <a href="login-user.php">Login here</a>.</div>';
            $valid_token = false;
        } else {
            $message = '<div class="alert alert-danger">Failed to reset password. Please try again.</div>';
        }
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
            <h4 class="heading text-center mb-4">Set New Password</h4>
            <?php echo $message; ?>
            <?php if ($valid_token): ?>
                <div class="form-group" style="position: relative;">
                    <label for="new_password">New Password <span class="required">*</span></label>
                    <input type="password" name="new_password" class="form-input" id="new_password" required minlength="6" style="padding-right: 2.5rem;">
                    <span class="toggle-password" data-target="new_password" style="position: absolute; top: 55%; right: 18px; transform: translateY(-50%); cursor: pointer;">
                        <svg class="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/></svg>
                    </span>
                </div>
                <div class="form-group" style="position: relative;">
                    <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                    <input type="password" name="confirm_password" class="form-input" id="confirm_password" required minlength="6" style="padding-right: 2.5rem;">
                    <span class="toggle-password" data-target="confirm_password" style="position: absolute; top: 55%; right: 18px; transform: translateY(-50%); cursor: pointer;">
                        <svg class="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/></svg>
                    </span>
                </div>
                <div class="form-row d-flex gap-2 align-items-center mb-3">
                    <input type="submit" class="btn white-btn checkout-btn" value="RESET PASSWORD" name="reset_password">
                    <a href="login-user.php" class="btn read-more">Back to Login</a>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <a href="lost-password.php" class="btn read-more">Request New Reset Link</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</section>

<script>
document.querySelectorAll('.toggle-password').forEach(function(toggle) {
    toggle.addEventListener('click', function() {
        var targetId = toggle.getAttribute('data-target');
        var input = document.getElementById(targetId);
        var eyeIcon = toggle.querySelector('.eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.innerHTML = '<circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><line x1="6" y1="18" x2="18" y2="6" stroke="#6366f1" stroke-width="2"/>';
        } else {
            input.type = 'password';
            eyeIcon.innerHTML = '<path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/>';
        }
    });
});
</script>

<?php include ("../Footer.php"); ?>
