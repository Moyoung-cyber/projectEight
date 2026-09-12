<?php
session_start();
include ("../include/connect_database.php");
include ("../function/commonfunction.php");
$_SESSION['admin_logged_in'] = true;

if (isset($_POST['admin_login'])) {
    $admin_name = $_POST['admin_name'];
    $admin_password = $_POST['admin_password'];
    $_SESSION['admin_name'] = $admin_name;

    $select_query = "SELECT * FROM `admin_table` WHERE admin_name = '$admin_name'";
    $result = mysqli_query($conn, $select_query);
    $row = mysqli_num_rows($result);
    $row_data = mysqli_fetch_array($result);

    if ($row > 0) {
        if (password_verify($admin_password, $row_data['admin_password'])) {
            if ($row_data['email_verified']) { // Check if email is verified
                $_SESSION["admin_name"] = $admin_name;
                echo "<script>alert('Login successfully')</script>";
                echo "<script>window.open('index.php','_self')</script>";
            } else {
                echo "<script>alert('Please verify your email before logging in')</script>";
                echo "<script>window.open('admin_login.php','_self')</script>";
            }
        } else {
            echo "<script>alert('Invalid Credentials')</script>";
            echo "<script>window.open('admin_login.php','_self')</script>";
        }
    } else {
        echo "<script>alert('Invalid Credentials')</script>";
        echo "<script>window.open('admin_login.php','_self')</script>";

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameBox Admin Login</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-bg">
  <div class="login-card">
    <div class="login-logo">
      <svg width="48" height="48" fill="none" viewBox="0 0 24 24"><path fill="#6366f1" d="M7 17l-2 2m12-2l2 2M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <span>GameBox Admin</span>
    </div>
    <form method="post" autocomplete="off">
      <div class="form-group mb-3">
        <label for="admin_name" class="form-label">Admin Name <span class="required">*</span></label>
        <input type="text" class="form-input form-control" id="admin_name" name="admin_name" placeholder="Enter admin name" required>
      </div>
      <div class="form-group mb-3" style="position:relative;">
        <label for="admin_password" class="form-label">Password</label>
        <input type="password" class="form-input form-control" id="admin_password" name="admin_password" placeholder="Enter password" required>
        <span class="show-password" onclick="togglePassword()" title="Show/Hide Password">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#888" viewBox="0 0 16 16">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/>
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
          </svg>
        </span>
      </div>
      <button type="submit" name="admin_login" class="btn read-more w-100">LOGIN</button>
    </form>
  </div>
</div>
<script>
function togglePassword() {
  var pwd = document.getElementById('admin_password');
  if (pwd.type === 'password') {
    pwd.type = 'text';
  } else {
    pwd.type = 'password';
  }
}
</script>
</body>
</html>