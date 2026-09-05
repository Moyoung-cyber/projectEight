<?php

$dynamicTitle = "tag";
include("header.php");
// include("function/commonfunction.php");
include('include/connect_database.php');

if (isset($_GET['tag_id'])) {
    $tag_id = intval($_GET['tag_id']); // Ensure tag_id is an integer for security

    // Fetch the tag name from the database
    $select = "SELECT tag_name FROM tags WHERE id = $tag_id";
    $result = mysqli_query($conn, $select);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $tag_name = htmlspecialchars($row['tag_name']); // Sanitize the output
    } else {
        // Handle case where the tag is not found
        $tag_name = "Unknown Tag";
    }
} else {
    // Handle case where no tag_id is provided
    $tag_id = null;
    $tag_name = "No Tag Selected";
}
ob_start();
$active_filter = isotop_tag($tag_id);
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
            <h1 class="heading">Tag List</h1>
            <div class="breadcrumb m-0">
                <a href="index.php">Home</a>
            
                <?php if ($active_filter) { ?>
                    <span>/</span>
                    <span><?php echo htmlspecialchars($active_filter); ?></span>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<section class="tag-list section-gap">
    <div class="container">
        <div class="tag-filter-bar mb-4">
            <?php echo $isotope_html; ?>
        </div>
    </div>
</section>

<?php

include('footer.php');

?>
