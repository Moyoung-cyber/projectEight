<?php
// Handle review deletion BEFORE any output
if (isset($_GET['delete_review'])) {
    $review_id = intval($_GET['delete_review']);
    mysqli_query($conn, "DELETE FROM reviews WHERE id = $review_id");
    header('Location: index.php?review');
    exit();
}

// Fetch all reviews with product info and username directly from reviews
$sql = "SELECT r.id, r.rating, r.comment, r.created_at, p.product_name, r.username
        FROM reviews r
        LEFT JOIN products p ON r.product_id = p.id
        ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-5">
    <h2 class="mb-4">User Reviews</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo str_repeat('★', (int)$row['rating']) . str_repeat('☆', 5-(int)$row['rating']); ?></td>
                <td><?php echo htmlspecialchars($row['comment']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="review.php?delete_review=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
