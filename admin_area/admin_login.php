<?php

include ('header.php');

?>

<section class="admin_login padding-top-section">
    <style>
    body {
        min-height: 100vh;
        background: linear-gradient(120deg, #6366f1 0%, #a5b4fc 100%);
        background-attachment: fixed;
        background-repeat: no-repeat;
    }
    </style>
    <div class="container">
        <div class="content">
            <h4 class="heading">Login</h4>
            <form action="login.php" method="post">
                <div class="row pb-5">
                    <div class="col">
                        <div class="form-group">
                            <label for="admin_name">Username <span class="required">*</span></label>
                            <input type="text/email" name="admin_name" class="form-input" required>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <label for="password">Password</label>
                            <input type="password" name="admin_password" class="form-input password" id="adminPasswordInput" required style="padding-right: 2.5rem;">
                            <span class="toggle-password" style="position: absolute; top: 55%; right: 18px; transform: translateY(-50%); cursor: pointer;">
                                <svg id="eyeIconAdmin" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/></svg>
                            </span>
                        </div>
                        <script>
                        const adminPasswordInput = document.getElementById('adminPasswordInput');
                        const eyeIconAdmin = document.getElementById('eyeIconAdmin');
                        const togglePasswordAdmin = document.querySelector('.toggle-password');
                        let adminPasswordVisible = false;
                        togglePasswordAdmin.addEventListener('click', function() {
                            adminPasswordVisible = !adminPasswordVisible;
                            adminPasswordInput.type = adminPasswordVisible ? 'text' : 'password';
                            eyeIconAdmin.innerHTML = adminPasswordVisible
                              ? '<circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><line x1="6" y1="18" x2="18" y2="6" stroke="#6366f1" stroke-width="2"/>'
                              : '<path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/>';
                        });
                        </script>
                        <div class="form-row d-flex gap-2 align-items-center">
                            <input type="submit" class="btn white-btn checkout-btn" value="login" name="admin_login">
                        </div>
                        <div class="d-none">
                            <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a
                                    href="admin_registration.php" class="text-danger">Register</a></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php

include ('footer.php');

?>