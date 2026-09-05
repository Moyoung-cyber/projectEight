<?php
if (!isset($conn) || !$conn) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "shop";
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
?>