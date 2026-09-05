<?php
include '../include/connect_database.php';

if (isset($_GET['edit_product'])) {
    $edit_id = $_GET['edit_product'];

    $get_data = "SELECT * FROM `products` WHERE id='$edit_id'";
    $result_data = mysqli_query($conn, $get_data);
    $row = mysqli_fetch_assoc($result_data);
    $product_name = $row['product_name'];
    $product_description = $row['product_description'];
    $tag_id = $row['tag_id'];
    $category_id = $row['category_id'];
    $product_image_1 = $row['product_image_1'];
    $product_image_2 = $row['product_image_2'];
    $product_price = $row['product_price'];
}

if (isset($_POST['update_product'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $tag_ids = $_POST['select_tags']; // This will be an array
    $category_id = $_POST['select_categories'];
    $product_price = $_POST['product_price'];

    // Convert array to comma-separated string for storage
    $tag_id_string = is_array($tag_ids) ? implode(',', $tag_ids) : $tag_ids;

    // Handling file uploads
    $product_image_1_name = $_FILES['product_image_1']['name'];
    $product_image_1_tmp = $_FILES['product_image_1']['tmp_name'];

    $product_image_2_name = $_FILES['product_image_2']['name'];
    $product_image_2_tmp = $_FILES['product_image_2']['tmp_name'];

    if ($product_image_1_name) {
        $product_image_1_path = "./product_images/" . $product_image_1_name;
        move_uploaded_file($product_image_1_tmp, $product_image_1_path);
    } else {
        $product_image_1_name = $product_image_1;
    }

    if ($product_image_2_name) {
        $product_image_2_path = "./product_images/" . $product_image_2_name;
        move_uploaded_file($product_image_2_tmp, $product_image_2_path);
    } else {
        $product_image_2_name = $product_image_2;
    }

    // Update query
    $update_product = "UPDATE `products` SET 
        product_name='$product_name', 
        product_description='$product_description', 
        tag_id='$tag_id_string', 
        category_id='$category_id', 
        product_price='$product_price', 
        product_image_1='$product_image_1_name', 
        product_image_2='$product_image_2_name' 
        WHERE id='$edit_id'";

    $result_update = mysqli_query($conn, $update_product);

    if ($result_update) {
        echo "<script>alert('Product updated successfully');</script>";
        echo "<script>window.location.href='index.php?view_products';</script>";
    } else {
        echo "<script>alert('Product update failed');</script>";
    }
}

// Convert current tag_id to array for multiple selection
$current_tag_ids = explode(',', $tag_id);
?>

<style>
#select_tags, #select_categories {
  font-size: 1.1em;
  font-weight: 500;
  color: #23272f;
  background: #f3f4fa;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  padding: 0.5em 1em;
  margin-bottom: 1em;
  min-height: 44px;
  box-shadow: 0 2px 8px rgba(99,102,241,0.07);
  transition: border 0.2s, box-shadow 0.2s;
}
#select_tags:focus, #select_categories:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 2px #a5b4fc44;
  outline: none;
}
</style>

<section class="edit-product w-50 m-auto">
    <div class="container">
        <div class="title text-center">
            <h1 class="heading">Edit Product</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product name</label>
                <input type="text" id="product_name" name="product_name" class="form-control"
                    value="<?php echo $product_name; ?>">
            </div>
            <div class="form-group">
                <label for="product_description">Product description</label>
                <textarea id="product_description" name="product_description" class="form-control" rows="4"><?php echo htmlspecialchars($product_description); ?></textarea>
            </div>
            <div class="form-group">
                <label for="select_tags">Tags (Hold Ctrl/Cmd to select multiple)</label>
                <select name="select_tags[]" id="select_tags" class="form-control" multiple size="4">
                    <?php
                    $sql = "SELECT * FROM `tags`";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<option value='" . $row['id'] . "'";
                        if (in_array($row['id'], $current_tag_ids)) {
                            echo " selected";
                        }
                        echo ">" . $row['tag_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="select_categories">Categories</label>
                <select name="select_categories" id="select_categories" class="form-control">
                    <option value="">Select categories</option>
                    <?php
                    $sql = "SELECT * FROM `categories`";
                    $res = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<option value='" . $row['id'] . "'";
                        if ($row['id'] == $category_id) {
                            echo " selected";
                        }
                        echo ">" . $row['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="product_price">Product price</label>
                <input type="text" id="product_price" name="product_price" class="form-control"
                    value="<?php echo $product_price; ?>">
            </div>
            <div class="form-group">
                <label for="product_image_1">Product image 1</label>
                <div class="d-flex">
                    <input type="file" name="product_image_1" id="product_image_1" class="form-control">
                    <img class="product-image" src="./product_images/<?php echo $product_image_1; ?>" alt="">
                </div>
            </div>
            <div class="form-group">
                <label for="product_image_2">Product image 2</label>
                <div class="d-flex">
                    <input type="file" name="product_image_2" id="product_image_2" class="form-control">
                    <img class="product-image" src="./product_images/<?php echo $product_image_2; ?>" alt="">
                </div>
            </div>

            <input type="submit" class="btn btn-primary" name="update_product" value="Update Product">
        </form>
    </div>
</section>