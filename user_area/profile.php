<?php
include ('../header.php');
include ("../include/connect_database.php");
// include("../function/commonfunction.php");
@session_start();

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
<section class="section-gap profile-section">
    <style>
    .profile-section {
        background: #f4f6fb;
        min-height: 80vh;
        padding: 3rem 0;
    }
    .profile-sidebar {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.10), 0 1.5px 8px rgba(35,39,47,0.08);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        min-height: 420px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .profile-sidebar .user-image {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        overflow: hidden;
        margin: 1.2rem 0 1.5rem 0;
        box-shadow: 0 4px 16px rgba(99,102,241,0.10);
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .profile-sidebar .user-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    .profile-sidebar .heading {
        font-size: 1.3rem;
        font-weight: 700;
        color: #6366f1;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .profile-sidebar .nav-link {
        color: #23272f;
        font-size: 1.08rem;
        font-weight: 500;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        padding: 0.7rem 1rem;
        transition: background 0.2s, color 0.2s;
        display: flex;
        align-items: center;
    }
    .profile-sidebar .nav-link:hover, .profile-sidebar .nav-link.active {
        background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
        color: #fff !important;
        text-decoration: none;
    }
    .profile-main-content {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.10), 0 1.5px 8px rgba(35,39,47,0.08);
        padding: 2.5rem 2rem;
        min-height: 420px;
    }
    @media (max-width: 991px) {
        .profile-sidebar, .profile-main-content {
            min-height: unset;
            padding: 1.5rem 1rem;
        }
    }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 mb-4 mb-lg-0">
                <div class="profile-sidebar">
                    <h4 class="heading">Your Profile</h4>
                    <?php
                    $username = $_SESSION["username"];
                    $user_image = "select * from `user_table` where user_name = '$username'";
                    $result_image = mysqli_query($conn, $user_image);
                    $row_image = mysqli_fetch_array($result_image);
                    $user_image = $row_image['user_image'];
                    echo "<div class='image user-image'><img src='./user_image/$user_image' alt='$username'></div>";
                    ?>
                    <ul class="navbar-nav w-100 mt-2">
                        <li class="nav-item"><a class="nav-link" href="profile.php"><span>Pending order</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php?edit_account"><span>Edit account</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php?user_order"><span>My order</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php?delete_account"><span>Delete account</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><span>Log out</span></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="profile-main-content">
                <?php
                user_order();
                if (isset($_GET['edit_account'])) {
                    include ('edit_account.php');
                }
                if (isset($_GET['user_order'])) {
                    include ('user_order.php');
                }
                if (isset($_GET['delete_account'])) {
                    include ('delete_account.php');
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include ("../Footer.php"); ?>