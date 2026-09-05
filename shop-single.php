<?php
include ('./include/connect_database.php');
$select_query = "SELECT * FROM products WHERE id='" . $_GET['id'] . "' ";
$result_query = mysqli_query($conn, $select_query);
$row = mysqli_fetch_assoc($result_query);
$product_name = $row["product_name"];
$dynamicTitle = "$product_name";
include ("header.php");
// include ('function/commonfunction.php');

// --- Review System Start ---
@session_start();
$product_id = $_GET['id'];
$user_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Handle review form submission (add/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_submit'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $rating = (int)$_POST['rating'];
    if ($user_id && $username) {
        // Check if user already reviewed
        $check = mysqli_query($conn, "SELECT * FROM reviews WHERE product_id='$product_id' AND user_id='$user_id'");
        if (mysqli_num_rows($check) > 0) {
            // Update existing review
            mysqli_query($conn, "UPDATE reviews SET comment='$comment', rating='$rating', updated_at=NOW() WHERE product_id='$product_id' AND user_id='$user_id'");
        } else {
            // Insert new review
            mysqli_query($conn, "INSERT INTO reviews (product_id, user_id, username, comment, rating, created_at, updated_at) VALUES ('$product_id', '$user_id', '$username', '$comment', '$rating', NOW(), NOW())");
        }
    }
    // Refresh to avoid resubmission
    echo "<script>window.location.href='shop-single.php?id=$product_id';</script>";
    exit();
}
// Handle review delete
if (isset($_GET['delete_review']) && $user_id) {
    $delete_id = (int)$_GET['delete_review'];
    mysqli_query($conn, "DELETE FROM reviews WHERE id='$delete_id' AND user_id='$user_id'");
    echo "<script>window.location.href='shop-single.php?id=$product_id';</script>";
    exit();
}
// Fetch all reviews for this product
$reviews = mysqli_query($conn, "SELECT * FROM reviews WHERE product_id='$product_id' ORDER BY updated_at DESC");
// Fetch current user's review (if any)
$user_review = null;
if ($user_id) {
    $user_review_result = mysqli_query($conn, "SELECT * FROM reviews WHERE product_id='$product_id' AND user_id='$user_id'");
    $user_review = mysqli_fetch_assoc($user_review_result);
}
// Get review count
$review_count = mysqli_num_rows($reviews);
// Calculate average rating
$average_rating = null;
if ($review_count > 0) {
    $sum = 0;
    mysqli_data_seek($reviews, 0); // Reset pointer
    while ($r = mysqli_fetch_assoc($reviews)) {
        $sum += $r['rating'];
    }
    $average_rating = $sum / $review_count;
    mysqli_data_seek($reviews, 0); // Reset pointer again for later use
}
// --- Review System End ---
?>
<?php
// Display breadcrumb and product details
?>
<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <h2 class="d-inline-block align-middle mb-0">
                <?php echo htmlspecialchars($product_name); ?>
                <span class="badge bg-info align-middle ms-2" style="font-size:1.1rem; vertical-align:middle;"> <?php echo $review_count; ?> Review<?php echo $review_count == 1 ? '' : 's'; ?> </span>
            </h2>
        </div>
    </div>
</div>
<?php
productdetail($review_count, $average_rating);
?>

<?php
// --- Recommendation System Start ---
// 1. Get the tag_ids for the current product
$current_tag_ids = $row['tag_id'];
$product_id = $row['id'];

$recommended_products = [];
if (!empty($current_tag_ids)) {
    // Split tag IDs and create a condition to match any of them
    $tag_id_array = explode(',', $current_tag_ids);
    $tag_conditions = [];
    foreach ($tag_id_array as $tag_id) {
        $tag_conditions[] = "tag_id LIKE '%$tag_id%'";
    }
    $tag_condition = implode(' OR ', $tag_conditions);
    
    $rec_query = "
        SELECT * FROM products
        WHERE ($tag_condition)
        AND id != '$product_id'
        LIMIT 4
    ";
    $rec_result = mysqli_query($conn, $rec_query);
    while ($rec_row = mysqli_fetch_assoc($rec_result)) {
        $recommended_products[] = $rec_row;
    }
}
?>
<?php if (!empty($recommended_products)): ?>
<div class="container mt-4 recommend-no-gap">
    <h4 class="mb-3 recommend-title-modern">You Might Also Like</h4>
    <div class="row">
        <?php foreach (
    $recommended_products as $rec): ?>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card h-100">
            <a href="shop-single.php?id=<?php echo $rec['id']; ?>">
                <div class="recommend-img-wrapper">
                    <img src="admin_area/product_images/<?php echo htmlspecialchars($rec['product_image_1']); ?>" alt="<?php echo htmlspecialchars($rec['product_name']); ?>">
                </div>
            </a>
            <?php
            // Fetch average rating and review count for this product
            $rec_id = $rec['id'];
            $rec_reviews = mysqli_query($conn, "SELECT rating FROM reviews WHERE product_id = '$rec_id'");
            $rec_review_count = mysqli_num_rows($rec_reviews);
            $rec_avg_rating = 0;
            if ($rec_review_count > 0) {
                $sum = 0;
                while ($r = mysqli_fetch_assoc($rec_reviews)) {
                    $sum += $r['rating'];
                }
                $rec_avg_rating = $sum / $rec_review_count;
            }
            ?>
            <div class="recommend-rating mb-1" style="font-size:1.1rem; text-align:center;">
                <style>
                .star-filled { color: #FFD700 !important; }
                .star-empty { color: #ccc !important; }
                </style>
                <?php
                $fullStars = floor($rec_avg_rating);
                $halfStar = ($rec_avg_rating - $fullStars) >= 0.5;
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $fullStars) {
                        echo '<span class="star-filled">&#9733;</span>';
                    } elseif ($halfStar && $i == $fullStars + 1) {
                        echo '<span class="star-filled">&#189;</span>';
                    } else {
                        echo '<span class="star-empty">&#9733;</span>';
                    }
                }
                ?>
                <span style="font-size:0.95rem; color:#888;">
                    <?php echo $rec_review_count > 0 ? number_format($rec_avg_rating, 1) : '0.0'; ?>/5 (<?php echo $rec_review_count; ?> Review<?php echo $rec_review_count == 1 ? '' : 's'; ?>)
                </span>
            </div>
            <div class="card-body">
                <h6 class="card-title mb-1">
                    <a href="shop-single.php?id=<?php echo $rec['id']; ?>">
                        <?php echo htmlspecialchars($rec['product_name']); ?>
                    </a>
                </h6>
                <p class="mb-0" style="font-size:1rem; color:#6366f1;">
                    <?php echo htmlspecialchars($rec['product_price']); ?> 
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<!-- Review Section Start -->
<div class="container mt-5">
    <h3>Product Reviews</h3>
    <?php if ($user_id): ?>
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" action="">
                    <div class="mb-2">
                        <label for="rating">Rating:</label>
                        <div class="star-rating" style="font-size:2rem; direction: rtl; display: inline-flex;">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" style="display:none;" <?php if (isset($user_review['rating']) && $user_review['rating'] == $i) echo 'checked'; ?> required>
                                <label for="star<?php echo $i; ?>" style="color:<?php echo (isset($user_review['rating']) && $user_review['rating'] >= $i) ? '#ffc107' : '#ccc'; ?>; cursor:pointer;">&#9733;</label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="comment">Comment:</label>
                        <textarea name="comment" id="comment" class="form-control" required><?php echo isset($user_review['comment']) ? htmlspecialchars($user_review['comment']) : ''; ?></textarea>
                    </div>
                    <button type="submit" name="review_submit" class="btn btn-primary">
                        <?php echo $user_review ? 'Update Review' : 'Submit Review'; ?>
                    </button>
                    <?php if ($user_review): ?>
                        <a href="shop-single.php?id=<?php echo $product_id; ?>&delete_review=<?php echo $user_review['id']; ?>" class="btn btn-danger ms-2" onclick="return confirm('Delete your review?');">Delete</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <style>
        .star-rating label {
            font-size: 2rem;
            color: #ccc;
            transition: color 0.2s;
        }
        .star-rating input:checked ~ label {
            color: #ffc107 !important;
        }
        .star-rating label:hover {
            color: #ffc107 !important;
        }
        
        /* Tag styling for product detail page */
        .product-price-detail-category a {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 2px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(99,102,241,0.2);
        }
        .product-price-detail-category a:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
            color: white;
            text-decoration: none;
        }

        .recommend-img-wrapper {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(99,102,241,0.08), 0 1.5px 8px rgba(35,39,47,0.08);
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 180px;
            max-height: 200px;
            margin-bottom: 10px;
        }
        .recommend-img-wrapper img {
            max-width: 100%;
            max-height: 140px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: none;
            background: transparent;
        }
        .recommend-title-modern {
  font-size: 1.7rem;
  font-weight: 700;
  color: #23272f;
  display: inline-block;
  position: relative;
  margin-bottom: 0;
  margin-top: 0;
}
.recommend-title-modern::after {
  content: '';
  display: block;
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
  border-radius: 2px;
  margin-top: 0;
  margin-left: 0;
}
.recommend-no-gap {
  margin-top: 0 !important;
  padding-top: 0 !important;
}
        </style>
        <script>
        // Only highlight the hovered star, not all previous
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating label');
            const radios = document.querySelectorAll('.star-rating input[type="radio"]');
            stars.forEach(function(star, idx) {
                star.addEventListener('mouseenter', function() {
                    stars.forEach(function(s, i) {
                        s.style.color = (i === idx) ? '#ffc107' : '#ccc';
                    });
                });
                star.addEventListener('mouseleave', function() {
                    let found = false;
                    radios.forEach(function(radio, i) {
                        if (radio.checked && !found) {
                            stars.forEach(function(s, j) {
                                s.style.color = (5 - radio.value === j) ? '#ffc107' : '#ccc';
                            });
                            found = true;
                        }
                    });
                    if (!found) {
                        stars.forEach(function(s) { s.style.color = '#ccc'; });
                    }
                });
                star.addEventListener('click', function() {
                    stars.forEach(function(s, i) {
                        s.style.color = (i === idx) ? '#ffc107' : '#ccc';
                    });
                });
            });
        });
        </script>
    <?php else: ?>
        <div class="alert alert-info">Please <a href="user_area/login-user.php">login</a> to leave a review.</div>
    <?php endif; ?>
    <div class="reviews-list mt-4">
        <?php if (mysqli_num_rows($reviews) > 0): ?>
            <?php while ($review = mysqli_fetch_assoc($reviews)): ?>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                            <span style="color:#ffc107; font-size:1.2rem;">
                                <?php for ($i = 1; $i <= $review['rating']; $i++): ?>&#9733;<?php endfor; ?>
                            </span>
                        </div>
                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        <small class="text-muted">Reviewed on <?php echo date('Y-m-d H:i', strtotime($review['updated_at'])); ?></small>
                        <?php if ($user_id && $review['user_id'] == $user_id): ?>
                            <span class="badge bg-success ms-2">Your Review</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-secondary">No reviews yet. Be the first to review this product!</div>
        <?php endif; ?>
    </div>
</div>
<!-- Review Section End -->

<?php include ("footer.php"); ?>