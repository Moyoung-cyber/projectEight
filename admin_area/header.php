<?php
@session_start();
include ('../include/connect_database.php');
include ('../config.php');

$user_search_data_value = "";
if (isset($_GET['search_keyword'])) {
    $user_search_data_value = $_GET['search_keyword'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>new-website</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/slick.css">
    <link rel="stylesheet" type="text/css" href="../css/animate.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery.fancybox.css">
    <link rel="stylesheet" type="text/css" href="../css/all.css">
    <!-- <link rel="stylesheet" href="css/jquery-ui.css"> -->
    <link rel="stylesheet" href="../css/splide.min.css">
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
    <link rel="stylesheet" type="text/css" href="./style.css">
    <link rel="stylesheet" type="text/css" href="../css/responsive.css">
</head>

<body>
<?php
// Check if admin is logged in
$admin_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Pages that don't need sidebar (login & registration)
$current_page = basename($_SERVER['PHP_SELF']);
$is_auth_page = ($current_page === 'admin_login.php' || $current_page === 'admin_registration.php');
?>

<?php if ($admin_logged_in && !$is_auth_page): ?>
    <header>
        <nav>
            <div class="logo-name">
                <span class="logo_name">ADMIN DASHBOARD</span>
            </div>
            <div class="menu-items">
                <ul class="primary-menu">
                    <li>
                        <a href="<?php echo BASE_URL; ?>">
                            <i class="fa-solid fa-house-chimney"></i>
                            <span class="text">View site</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && empty($_GET)) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-house"></i>
                            <span class="text">Dashdoard</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?view_products" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['view_products'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-eye"></i>
                            <span class="text">View product</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?add_stock" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['add_stock'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            <span class="text">Add stock</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?history" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['history'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span class="text">History</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-download"></i>
                            <span class="text">Insert</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="index.php?insert_product" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['insert_product'])) ? 'active' : ''; ?>">Insert product</a></li>
                            <li><a href="index.php?insert_categories" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['insert_categories'])) ? 'active' : ''; ?>">Insert Categories</a></li>
                            <li><a href="index.php?insert_tags" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['insert_tags'])) ? 'active' : ''; ?>">Insert tags</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-list"></i>
                            <span class="text">List</span>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="index.php?list_order" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['list_order'])) ? 'active' : ''; ?>">All order</a></li>
                            <li><a href="index.php?list_payment" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['list_payment'])) ? 'active' : ''; ?>">All payment</a></li>
                            <li><a href="index.php?list_report" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['list_report'])) ? 'active' : ''; ?>">List Report</a></li>
                            <li><a href="index.php?list_user" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['list_user'])) ? 'active' : ''; ?>">List user</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="index.php?feedback" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['feedback'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-comments"></i>
                            <span class="text">Feedback</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?review" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && isset($_GET['review'])) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-star-half-stroke"></i>
                            <span class="text">Reviews</span>
                        </a>
                    </li>
                </ul>
                <div class="logout-btn">
                    <a href="logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout</a>
                </div>
            </div>
        </nav>
    </header>
<?php endif; ?>