<?php
include ("../include/connect_database.php");
include ('../header.php');


if (isset($_POST['verify'])) {
$user_email = $_POST['email'];
$verification_code = $_POST['verification_code'];

// Verify the code against the database
$query = "SELECT * FROM user_table WHERE user_email = '$user_email' And verification_code = '$verification_code'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // Update user's email verification status
    $update_query = "UPDATE `user_table` SET email_verified = 1 WHERE user_email = '$user_email'";
    mysqli_query($conn, $update_query);
    echo "<script>alert('Your email has been successfully verified.');</script>";
    echo "<script>window.open('login-user.php', '_self')</script>";
} else {
    echo "<script>alert('Invalid verification code. Please try again.');</script>";
}
}
?>
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
    <div class="container verify-center-wrapper">
        <form class="verify-card" action="verify_email.php" method="post">
            <h4 class="heading">Verify Your Email</h4>
            <div class="form-group">
                <label for="username">Email <span class="required">*</span></label>
                <input type="text/email" name="email" class="form-input" required>
            </div>
            <div class="form-group" style="position: relative;">
                <label class="textlabel" for="verification_code">Verification Code</label>
                <input type="password" class="form-input" id="verification_code" name="verification_code" autocomplete="off" required style="padding-right:2.5rem;" />
                <span class="toggle-password" style="position: absolute; top: 55%; right: 18px; transform: translateY(-50%); cursor: pointer;">
                    <svg id="eyeIconVerify" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/></svg>
                </span>
            </div>
            <script>
const verificationInput = document.getElementById('verification_code');
const eyeIconVerify = document.getElementById('eyeIconVerify');
const togglePasswordVerify = document.querySelector('.toggle-password');
let codeVisible = false;
togglePasswordVerify.addEventListener('click', function() {
codeVisible = !codeVisible;
verificationInput.type = codeVisible ? 'text' : 'password';
eyeIconVerify.innerHTML = codeVisible
    ? '<circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><line x1="6" y1="18" x2="18" y2="6" stroke="#6366f1" stroke-width="2"/>'
    : '<path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/>';
});
</script>
            <button type="submit" class="btn verify-btn" name="verify">Verify</button>
            <a href="login-user.php" class="btn read-more">Login</a>
        </form>
    </div>
</section>

<?php include ("../Footer.php"); ?>