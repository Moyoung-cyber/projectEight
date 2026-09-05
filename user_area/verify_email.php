<?php
include ("./include/connect_database.php");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="loginstyle.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #6366f1 0%, #a5b4fc 100%);
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
        .verify-center-wrapper {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(99,102,241,0.12), 0 1.5px 8px rgba(35,39,47,0.08);
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }
        .verify-card .heading {
            font-size: 1.4rem;
            font-weight: 700;
            color: #6366f1;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .verify-card .form-input {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
            margin-bottom: 1.2rem;
            font-size: 1rem;
            background: #f9fafb;
            transition: border 0.2s;
        }
        .verify-card .form-input:focus {
            border-color: #6366f1;
            outline: none;
            background: #fff;
        }
        .verify-card label {
            font-weight: 500;
            color: #23272f;
            margin-bottom: 0.3rem;
            display: block;
        }
        .verify-card .btn.verify-btn {
            background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            transition: background 0.2s;
            width: 100%;
            margin-bottom: 0.7rem;
        }
        .verify-card .btn.verify-btn:hover {
            background: linear-gradient(90deg, #4f46e5 60%, #818cf8 100%);
            color: #fff;
        }
        .verify-card .btn.read-more {
            color: #6366f1;
            font-weight: 500;
            text-decoration: underline;
            background: none;
            border: none;
            width: 100%;
            padding: 0.7rem 1.5rem;
        }
        .verify-card .btn.read-more:hover {
            color: #4f46e5;
            text-decoration: underline;
        }
    </style>
</head>

<body>
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