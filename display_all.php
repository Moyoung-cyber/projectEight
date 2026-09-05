<?php
$dynamicTitle = "All product";
include ("header.php");
// include ('function/commonfunction.php');

$limit = 9; // Number of products per page (3x3 grid)

// Fetch total number of products
$sql = "SELECT COUNT(id) AS total FROM products";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_products = $row['total'];

// Calculate total pages
$total_pages = ceil($total_products / $limit);

// Get current page from URL, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_pages) $page = $total_pages;

$start = ($page - 1) * $limit;

?>


<section class="single-banner bg-light-white margin-top-header">
    <div class="container">
        <h1 class="heading shop-title-modern">All Product</h1>
        <div class="breadcrumb m-0">
            <a href="index.php">Home</a>
            <span>/</span>
            <span>All product</span>
        </div>
    </div>
</section>
<section class="pb-5 padding-top-section">
    <div class="container">
        <h3 class="heading underline center text-center">Display all product</h3>
        <div class="row g-xl-5 g-4 ">
            <?php
            // Open a row
            echo '<div class="row">';
            $product_count = 0;
            ob_start();
            allproduct($start, $limit);
            $products_html = ob_get_clean();
            $products = explode('<div class=\'col-lg-3 col-sm-6\'>', $products_html);
            foreach ($products as $product_html) {
                if (trim($product_html) === '') continue;
                if ($product_count > 0 && $product_count % 3 == 0) {
                    echo '</div><div class="row">';
                }
                echo '<div class="col-4">' . $product_html;
                $product_count++;
            }
            echo '</div>';
            ?>
        </div>
        
        <!-- Pagination -->
        <ul class="pagination">
            <li>
                <a href="?page=<?php echo max(1, $page - 1); ?>" class="page-numbers prev"><</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li>
                    <?php if ($i == $page): ?>
                        <span class="page-numbers current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>" class="page-numbers"><?php echo $i; ?></a>
                    <?php endif; ?>
                </li>
            <?php endfor; ?>
            <li>
                <a href="?page=<?php echo min($total_pages, $page + 1); ?>" class="page-numbers next">></a>
            </li>
        </ul>
    </div>
</section>

<?php include ("footer.php"); ?>

<style>
.shop-title-modern {
  font-size: 2.5rem;
  font-weight: 700;
  color: #23272f;
  display: inline-block;
  position: relative;
  margin-bottom: 0;
  margin-top: 0;
  text-align: center;
}
.shop-title-modern::after {
  content: '';
  display: block;
  width: 70px;
  height: 5px;
  background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
  border-radius: 2px;
  margin: 0 auto 0 auto;
}
.breadcrumb.m-0 {
  margin-top: 0 !important;
  margin-bottom: 0 !important;
}
</style>
<style>
.product-price {
  display: inline-flex;
  align-items: center;
  background: #f3f4fa;
  border-radius: 999px;
  padding: 0.15em 0.9em;
  font-size: 0.95em;
  box-shadow: 0 2px 8px rgba(99,102,241,0.07);
  margin-top: 0.5em;
}
.product-price .currency {
  color: #000;
  font-weight: 700;
  margin-right: 0.3em;
  font-size: 0.95em;
}
.product-price .amount {
  color: #000;
  font-weight: 700;
  font-size: 1em;
  letter-spacing: 0.5px;
}
</style>
