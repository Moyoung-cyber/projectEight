<?php include ('../header.php'); ?>
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
        <form class="login-card" action="login.php" method="post">
            <h4 class="heading text-center mb-4">Login</h4>
            <div class="form-group">
                <label for="username">Email <span class="required">*</span></label>
                <input type="text/email" name="user_email" class="form-input" required>
            </div>
            <div class="form-group" style="position: relative;">
                <label for="password">Password</label>
                <input type="password" name="user_password" class="form-input password" id="passwordInput" required style="padding-right: 2.5rem;">
                <span class="toggle-password" style="position: absolute; top: 55%; right: 18px; transform: translateY(-50%); cursor: pointer;">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/></svg>
                </span>
            </div>
            <script>
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            const togglePassword = document.querySelector('.toggle-password');
            let passwordVisible = false;
            togglePassword.addEventListener('click', function() {
                passwordVisible = !passwordVisible;
                passwordInput.type = passwordVisible ? 'text' : 'password';
                eyeIcon.innerHTML = passwordVisible
                  ? '<circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><line x1="6" y1="18" x2="18" y2="6" stroke="#6366f1" stroke-width="2"/>'
                  : '<path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/>';
            });
            </script>
            <div class="text-center mb-3">
                <a href="lost-password.php" class="small text-danger">Forgot your password?</a>
            </div>
            <div class="form-row d-flex gap-2 align-items-center mb-3">
                <input type="submit" class="btn white-btn checkout-btn" value="login" name="user_login">
                <a href="verify_email.php" class="btn read-more">Verify Email</a>
            </div>
            <div class="text-center">
                <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="user_registration.php" class="text-danger">Register</a></p>
            </div>
        </form>
    </div>
</section>

<?php include ("../Footer.php"); ?>