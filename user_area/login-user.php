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
    <style>
    body {
        min-height: 100vh;
        background: linear-gradient(120deg, #6366f1 0%, #a5b4fc 100%);
        background-attachment: fixed;
        background-repeat: no-repeat;
    }
    .login-center-wrapper {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.12), 0 1.5px 8px rgba(35,39,47,0.08);
        padding: 2.5rem 2rem;
        max-width: 400px;
        width: 100%;
        margin: 0 auto;
    }
    .login-card .form-input {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.75rem 1rem;
        margin-bottom: 1.2rem;
        font-size: 1rem;
        background: #f9fafb;
        transition: border 0.2s;
    }
    .login-card .form-input:focus {
        border-color: #6366f1;
        outline: none;
        background: #fff;
    }
    .login-card .btn.checkout-btn {
        background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        transition: background 0.2s;
    }
    .login-card .btn.checkout-btn:hover {
        background: linear-gradient(90deg, #4f46e5 60%, #818cf8 100%);
    }
    .login-card .read-more {
        color: #6366f1;
        font-weight: 500;
        text-decoration: underline;
    }
    .login-card label {
        font-weight: 500;
        color: #23272f;
    }
    .login-card .required {
        color: #e11d48;
    }
    </style>
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