<?php

$dynamicTitle = "category";
include("header.php");
// include("function/commonfunction.php");
include('include/connect_database.php');

if (isset($_GET['cat_id'])) {
    $category_id = intval($_GET['cat_id']); // Ensure category_id is an integer for security

    // Fetch the category name from the database
    $select = "SELECT category_name FROM categories WHERE id = $category_id";
    $result = mysqli_query($conn, $select);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $category_name = htmlspecialchars($row['category_name']); // Sanitize the output
    } else {
        // Handle case where the category is not found
        $category_name = "Unknown category";
    }
} else {
    // Handle case where no category_id is provided
    $category_id = null;
    $category_name = "No category Selected";
}
ob_start();
$active_filter = isotop_category($category_id, null);
$isotope_html = ob_get_clean();
?>

<style>
#isotope-filters {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}
#isotope-filters a {
  font-size: 1.1rem;
  font-weight: 600;
  color: #6366f1;
  padding: 0.75rem 1.5rem;
  border-radius: 50px;
  background: #f3f4fa;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  display: inline-block;
  box-shadow: 0 2px 8px rgba(99,102,241,0.08);
}
#isotope-filters a:hover {
  background: linear-gradient(135deg, #6366f1 0%, #a5b4fc 100%);
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(99,102,241,0.2);
  border-color: #6366f1;
}
#isotope-filters a.active {
  background: linear-gradient(135deg, #6366f1 0%, #a5b4fc 100%);
  color: #fff;
  box-shadow: 0 4px 16px rgba(99,102,241,0.2);
  border-color: #6366f1;
  transform: translateY(-1px);
}
.new-arrival-box .rating {
  color: #ffc107;
  font-size: 15px;
  margin-bottom: 8px;
}
.new-arrival-box .price-tag {
  font-size: 18px;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 8px;
  background: #f3f4fa;
  padding: 8px 16px;
  border-radius: 12px;
  display: inline-block;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  border: 1px solid #e2e8f0;
}
</style>

<section class="single-banner bg-light-white margin-top-header">
    <div class="container">
        <div class="content">
            <h1 class="heading">Category List</h1>
            <div class="breadcrumb m-0">
                <a href="index.php">Home</a>
                <span>/</span>
                <span><?php echo $category_name; ?></span>
                <?php if ($active_filter) { ?>
                    <span>/</span>
                    <span><?php echo htmlspecialchars($active_filter); ?></span>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<section class="category-list section-gap">
    <div class="container">
        <div class="category-filter-bar mb-4">
            <?php echo $isotope_html; ?>
        </div>
    </div>
</section>
<?php
    include ('footer.php');
?>
