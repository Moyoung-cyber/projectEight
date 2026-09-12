<?php
include('include/connect_database.php');

echo "<h2>Migration: Hashing Existing Passwords</h2>";

// Hash passwords in user_table
$user_query = "SELECT user_id, user_password FROM user_table";
$user_result = mysqli_query($conn, $user_query);
$user_count = 0;

while ($row = mysqli_fetch_assoc($user_result)) {
    $password = $row['user_password'];
    // Skip if already hashed (password_hash produces a 60-char string starting with $2y$)
    if (strlen($password) === 60 && substr($password, 0, 4) === '$2y$') {
        continue;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_id = $row['user_id'];
    $escaped_password = mysqli_real_escape_string($conn, $hashed_password);
    mysqli_query($conn, "UPDATE user_table SET user_password = '$escaped_password' WHERE user_id = $user_id");
    $user_count++;
}

// Hash passwords in admin_table
$admin_query = "SELECT admin_id, admin_password FROM admin_table";
$admin_result = mysqli_query($conn, $admin_query);
$admin_count = 0;

while ($row = mysqli_fetch_assoc($admin_result)) {
    $password = $row['admin_password'];
    if (strlen($password) === 60 && substr($password, 0, 4) === '$2y$') {
        continue;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $admin_id = $row['admin_id'];
    $escaped_password = mysqli_real_escape_string($conn, $hashed_password);
    mysqli_query($conn, "UPDATE admin_table SET admin_password = '$escaped_password' WHERE admin_id = $admin_id");
    $admin_count++;
}

echo "<p>Done. Updated $user_count user(s) and $admin_count admin(s).</p>";
echo "<p><strong>You can delete this file after running it.</strong></p>";
?>
