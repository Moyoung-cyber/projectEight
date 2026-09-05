<?php
@session_start();
include('../header.php');
include("./include/connect_database.php");
// include('../function/commonfunction.php');

// use PHPMailer\PHPMailer\PHPMailer;

// Include PHPMailer Autoload file
// Include PHPMailer classes
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Function to generate a random verification code
function generateVerificationCode()
{
    return rand(100000, 999999); // Generates a 6-digit code
}

// Create a new instance of the PHPMailer class
$mail = new PHPMailer();

// Set up SMTP configuration
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'ajayalama939@gmail.com';
$mail->Password = 'gnuotwhelvwbkjnf';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$errors = []; // Define an empty array to store errors

if (isset($_POST['user_register'])) {
    $user_name = $_POST['user_name'];
    $user_lname = $_POST['user_lname'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $conform_user_password = isset($_POST['conform_user_password']) ? $_POST['conform_user_password'] : '';
    $user_address = $_POST['user_address'];
    $user_phone = $_POST['user_contact'];

    $existing_email_query = "SELECT * FROM user_table WHERE user_email = '$user_email'";
    $existing_email_result = mysqli_query($conn, $existing_email_query);
    if (mysqli_num_rows($existing_email_result) > 0) {
        // Email already exists, display error message
        echo "<script>alert('Email is already registered. Please use a different email address.')</script>";
    } else {
        // Generate verification code
        $verification_code = generateVerificationCode();

        // Handle file upload
        if (!empty($_FILES['user_image']['name'])) {
            $user_image = $_FILES['user_image']['name'];
            $user_image_temp = $_FILES['user_image']['tmp_name'];

            // Move uploaded file to the appropriate directory
            $target_path = "./user_image/$user_image";
            move_uploaded_file($user_image_temp, $target_path);
        } else {
            $user_image = ""; // Set default value or handle case accordingly
        }

        // Construct and execute the SQL query
        $insert_query = "INSERT INTO `user_table`(`user_name`, `user_lname`, `user_email`, `user_password`, `user_image`, `user_address`, `user_mobile`, `verification_code`) VALUES ('$user_name', '$user_lname','$user_email','$user_password','$user_image','$user_address','$user_phone', '$verification_code')";
        $result = mysqli_query($conn, $insert_query);

        if ($result) {
            // Send verification email
            $mail->setFrom('ajayalama939@gmail.com', 'Gaming boy');
            $mail->addAddress($user_email, $user_name);
            $mail->Subject = 'Verify your email address for registration';
            $mail->isHTML(true);
            $mail->Body = "Please use the following verification code to verify your email address: <strong>$verification_code</strong><br>Enter the code on the following page: <a href='" . BASE_URL . "user_area/verify_email_click.php?code=$verification_code'>Verify Email</a>";

            if (!$mail->send()) {
                // Display error message if email sending fails
                echo "<script>alert('Failed to send verification email.')</script>";
            } else {
                // Display success message if email sending succeeds
                echo "<script>alert('Registration successful. Please check your email to verify your account.')</script>";
                echo "<script>window.open('verify_email.php', '_self')</script>";
            }
        } else {
            echo "<script>alert('Failed to insert user.')</script>";
        }
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
    <style>
    body {
        min-height: 100vh;
        background: linear-gradient(120deg, #6366f1 0%, #a5b4fc 100%);
        background-attachment: fixed;
        background-repeat: no-repeat;
    }
    .register-center-wrapper {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .register-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.12), 0 1.5px 8px rgba(35,39,47,0.08);
        padding: 2.5rem 2rem;
        max-width: 500px;
        width: 100%;
        margin: 0 auto;
    }
    .register-card .form-input, .register-card .form-control {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.75rem 1rem;
        margin-bottom: 1.2rem;
        font-size: 1rem;
        background: #f9fafb;
        transition: border 0.2s;
    }
    .register-card .form-input:focus, .register-card .form-control:focus {
        border-color: #6366f1;
        outline: none;
        background: #fff;
    }
    .register-card .btn.read-more {
        background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        transition: background 0.2s;
    }
    .register-card .btn.read-more:hover {
        background: linear-gradient(90deg, #4f46e5 60%, #818cf8 100%);
        color: #fff;
    }
    .register-card label {
        font-weight: 500;
        color: #23272f;
    }
    .register-card .required {
        color: #e11d48;
    }
    .register-card .optional {
        color: #6366f1;
        font-size: 0.9em;
    }
    .register-card .error {
        color: #e11d48;
        font-size: 0.95em;
        margin-bottom: 0.5rem;
        display: block;
    }
    </style>
    <div class="container register-center-wrapper">
        <form id="registrationForm" class="register-card" action="" method="post" enctype="multipart/form-data">
            <h4 class="heading text-center mb-4">New Registration Form</h4>
            <div class="form-group">
                <label for="username">Username <span class="required">*</span></label>
                <input type="text" id="user_name" name="user_name" class="form-input">
                <span id="usernameError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="user_lname">Last name</label>
                <input type="text" id="user_lname" name="user_lname" class="form-input">
            </div>
            <div class="form-group">
                <label for="user_email">Email <span class="required">*</span></label>
                <input type="text" id="user_email" name="user_email" class="form-input">
                <span id="emailError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="User_image">Image <span class="optional">(optional)</span></label>
                <input type="file" id="user_image" name="user_image" class="form-input form-control">
            </div>
            <div class="form-group" style="position: relative;">
                <label for="user_password">Password <span class="required">*</span></label>
                <input type="password" id="user_password" name="user_password" class="form-input password" style="padding-right: 2.5rem;">
                <span class="toggle-password" data-target="user_password" style="position: absolute; top: 55%; right: 18px; transform: translateY(-50%); cursor: pointer;">
                    <svg class="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/></svg>
                </span>
                <span id="passwordError" class="error"></span>
            </div>
            <div class="form-group" style="position: relative;">
                <label for="conform_user_password">Confirm Password <span class="required">*</span></label>
                <input type="password" id="conform_user_password" name="conform_user_password" class="form-input password" style="padding-right: 2.5rem;">
                <span class="toggle-password" data-target="conform_user_password" style="position: absolute; top: 55%; right: 18px; transform: translateY(-50%); cursor: pointer;">
                    <svg class="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24"><path stroke="#6366f1" stroke-width="2" d="M1.5 12S5.5 5.5 12 5.5 22.5 12 22.5 12 18.5 18.5 12 18.5 1.5 12 1.5 12Z"/><circle cx="12" cy="12" r="3.5" stroke="#6366f1" stroke-width="2"/></svg>
                </span>
                <span id="confirmPasswordError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="user_address">Address <span class="required">*</span></label>
                <input type="text" id="user_address" name="user_address" class="form-input">
                <span id="addressError" class="error"></span>
            </div>
            <div class="form-group">
                <label for="user_contact">Contact <span class="required">*</span></label>
                <input type="tel" id="user_contact" name="user_contact" class="form-input">
                <span id="contactError" class="error"></span>
            </div>
            <div class="text-center">
                <button type="submit" value="Register" class="btn read-more" name="user_register" onclick="console.log('Register buatton clicked')">Register</button>
                <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account? <a href="login-user.php" class="text-danger">Login</a></p>
            </div>
        </form>
    </div>
</section>

<script>
    function validateUsername() {
        const usernameInput = document.getElementById("user_name");
        const usernameError = document.getElementById("usernameError");
        const username = usernameInput.value.trim();

        if (username.length < 3) {
            usernameError.textContent = "Username must be at least 3 characters long.";
            return false;
        } else if (/\d/.test(username)) {
            usernameError.textContent = "Username must not contain numbers.";
            return false;
        } else {
            usernameError.textContent = "";
            return true;
        }
    }

    // Function to validate email
    function validateEmail() {
        const emailInput = document.getElementById("user_email");
        const emailError = document.getElementById("emailError");
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
            emailError.textContent = "Enter a valid email address.";
            return false;
        } else {
            emailError.textContent = "";
            return true;
        }
    }

    // Function to validate password
    function validatePassword() {
        const passwordInput = document.getElementById("user_password");
        const passwordError = document.getElementById("passwordError");
        if (passwordInput.value.trim().length < 6 || !/[A-Z]/.test(passwordInput.value) || !/[a-z]/.test(passwordInput.value) || !/\d/.test(passwordInput.value)) {
            passwordError.textContent = "Password must contain at least 1 lowercase, 1 uppercase, and 1 number, and be 6 characters long.";
            return false;
        } else {
            passwordError.textContent = "";
            return true;
        }
    }


    // Function to validate confirm password
    function validateConfirmPassword() {
        const passwordInput = document.getElementById("user_password");
        const confirmPasswordInput = document.getElementById("conform_user_password");
        const confirmPasswordError = document.getElementById("confirmPasswordError");
        if (passwordInput.value.trim() !== confirmPasswordInput.value.trim()) {
            confirmPasswordError.textContent = "Passwords do not match.";
            return false;
        } else {
            confirmPasswordError.textContent = "";
            return true;
        }
    }
    
    function validateAddress() {
        const addressInput = document.getElementById("user_address");
        const addressError = document.getElementById("addressError");
        const addressValue = addressInput.value.trim();

        // Split the address by spaces
        const addressParts = addressValue.split(/\s+/); // Split by one or more spaces

        // Check if there are at least two words
        if (addressParts.length < 2) {
            addressError.textContent = "Address must consist of at least two words.";
            return false;
        } else {
            addressError.textContent = "";
            return true;
        }
    }

    function validateContact() {
        const contactInput = document.getElementById("user_contact");
        const contactError = document.getElementById("contactError");
        const contactValue = contactInput.value.trim();

        // Regular expression to match the desired pattern
        const contactPattern = /^9[678]\d{8}$/; // Starts with 9, second digit is 6, 7, or 8, followed by 8 digits

        if (!contactPattern.test(contactValue)) {
            contactError.textContent = "Enter a valid contact number starting with 9 and second digit as 6, 7, or 8.";
            return false;
        } else {
            contactError.textContent = "";
            return true;
        }
    }

    // Add event listeners for input events
    document.getElementById("user_name").addEventListener("input", validateUsername);
    document.getElementById("user_email").addEventListener("input", validateEmail);
    document.getElementById("user_password").addEventListener("input", validatePassword);
    document.getElementById("conform_user_password").addEventListener("input", validateConfirmPassword);
    document.getElementById("user_address").addEventListener("input", validateAddress);
    document.getElementById("user_contact").addEventListener("input", validateContact);

    // Add event listener for form submission
    document.getElementById("registrationForm").addEventListener("submit", function (event) {
        // Prevent form submission if any of the validations fail
        if (!validateUsername() || !validateEmail() || !validatePassword() || !validateConfirmPassword() || !validateAddress() || !validateContact()) {
            event.preventDefault();
        }
    });

</script>

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

<?php include('../Footer.php'); ?>