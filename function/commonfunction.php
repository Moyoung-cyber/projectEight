<?php

// include './include/connect_database.php';


function displayProducts($limit, $show_cart_icon = true)
{
    global $conn;
    $sql_query = "SELECT p.*, c.category_name, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS review_count FROM `products` p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN reviews r ON p.id = r.product_id GROUP BY p.id ORDER BY p.date DESC LIMIT {$limit}";
    $result = mysqli_query($conn, $sql_query);

    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['id'];
        $product_name = $row['product_name'];
        $product_price = $row['product_price'];
        $product_image = $row['product_image_1'];
        $product_in_store = $row['product_in_store'];
        $category = $row['category_name'];
        $avg_rating = $row['avg_rating'];
        $review_count = $row['review_count'];
        // Build dynamic star rating (updated to match modern style)
        if ($review_count > 0) {
        $full_stars = floor($avg_rating);
        $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
        $empty_stars = 5 - $full_stars - $half_star;
        $star_html = '';
        for ($i = 0; $i < $full_stars; $i++) {
                $star_html .= "<span class='star-filled'>★</span>";
        }
        if ($half_star) {
                $star_html .= "<span class='star-filled'>½</span>";
        }
        for ($i = 0; $i < $empty_stars; $i++) {
                $star_html .= "<span class='star-empty'>☆</span>";
        }
            $score_html = " ".number_format($avg_rating, 1)."/5 ($review_count Review".($review_count == 1 ? '' : 's').")";
        } else {
            $star_html = "<span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span>";
            $score_html = " (No reviews yet)";
        }
        // Output HTML code to display product
        echo "<div class='col-lg-3 col-sm-6'>
            <div class='new-arrival-box'>
                <a href='shop-single.php?id=$product_id'>
                <div class='image'>
                    <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
        if ($product_in_store <= 0 || $product_in_store == 1) {
            echo "<div class='sale-btn'>
                            <span class='btn read-more'>out of stock</span>
                        </div>";
        }
        echo "
                </div>
                <div class='content'>
                        <div class='category'>".htmlspecialchars($category)."</div>
                        <h4 class='heading'>".htmlspecialchars($product_name)."</h4>
                        <div class='rating'>$star_html$score_html</div>
                        <div class='price-tag'>
                            Rs. ".number_format($product_price, 2)."
                        </div>";
        if ($show_cart_icon) {
            echo "<div class='cart-btn'>
                            <a href='cart.php?add=$product_id'><i class='fa fa-shopping-cart'></i></a>
                        </div>";
        }
        echo    "</div>
                </a>
            </div>
        </div>";
    }
}

function search_product()
{
    global $conn;

    // Check if the search keyword is set
    if (isset($_GET['search_keyword']) && !empty($_GET['search_keyword'])) {
        $user_search_data_value = $_GET['search_keyword'];

        // Escape the search keyword to prevent SQL injection
        $search_keyword = '%' . mysqli_real_escape_string($conn, $user_search_data_value) . '%';

        // Construct the SQL query
        $search_product_query = "
            SELECT p.* 
            FROM products p
            INNER JOIN tags t ON p.tag_id = t.id
            INNER JOIN categories c ON p.category_id = c.id
            WHERE c.category_name LIKE ? OR t.tag_name LIKE ? OR p.product_name LIKE ?";

        // Prepare the statement
        $stmt = mysqli_prepare($conn, $search_product_query);
        mysqli_stmt_bind_param($stmt, 'sss', $search_keyword, $search_keyword, $search_keyword);

        // Execute the query
        mysqli_stmt_execute($stmt);
        $result_query = mysqli_stmt_get_result($stmt);

        // Check if there are any search results
        if (mysqli_num_rows($result_query) == 0) {
            echo "<section class='search-result-section pb-5 mb-sm-5'>
                    <div class='container'>
                        <div class='content'>
                            <h4 class='heading'>No search Results</h4>
                            <p>There are no products matching your query</p>
                            <div class='search-product search-result'>
                                <form action='search.php' method='get'>
                                    <div class='position-relative d-flex gap-3'>
                                        <input type='search' name='search_keyword' placeholder='Search...'>
                                        <input type='submit' name='search_product' class='read-more btn' value='Search'>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>";
        } else {
            while ($row = mysqli_fetch_assoc($result_query)) {
                $product_id = $row['id'];
                $product_name = $row['product_name'];
                $product_price = $row['product_price'];
                $product_image = $row['product_image_1'];
                $product_in_store = $row['product_in_store'];

                // Output HTML code to display product
                echo "<div class='col-lg-3 col-sm-6'>
                        <div class='new-arrival-box'>
                            <a href='shop-single.php?id=$product_id'>
                                <div class='image'>
                                    <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
                if ($product_in_store <= 0) {
                    echo "<div class='sale-btn'>
                                            <span class='btn read-more'>out of stock</span>
                                        </div>";
                }
                echo "
                                </div>
                                <div class='content'>
                                    <h4 class='heading'>$product_name</h4>
                                    <div class='product-price mt-2'>
                                      <span class='currency'>Rs.</span>
                                      <span class='amount'>$product_price</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>";
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<section class='search-result-section pb-5 mb-sm-5'>
                <div class='container'>
                    <div class='content'>
                        <h4 class='heading'>No search Results</h4>
                        <p>Please enter a search keyword.</p>
                        <div class='search-product search-result'>
                            <form action='search.php' method='get'>
                                <div class='position-relative d-flex gap-3'>
                                    <input type='search' name='search_keyword' placeholder='Search...'>
                                    <input type='submit' name='search_product' class='read-more btn' value='Search'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>";
    }
}

function allproduct($start, $limit)
{
    global $conn;
    $sql_query = "SELECT p.*, c.category_name, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS review_count FROM `products` p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN reviews r ON p.id = r.product_id GROUP BY p.id LIMIT $start, $limit";
    $result = mysqli_query($conn, $sql_query);

    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['id'];
        $product_name = $row['product_name'];
        $product_price = $row['product_price'];
        $product_image = $row['product_image_1'];
        $product_in_store = $row['product_in_store'];
        $category = $row['category_name'];
        $avg_rating = $row['avg_rating'];
        $review_count = $row['review_count'];
        // Build dynamic star rating (updated to match modern style)
        if ($review_count > 0) {
        $full_stars = floor($avg_rating);
        $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
        $empty_stars = 5 - $full_stars - $half_star;
        $star_html = '';
        for ($i = 0; $i < $full_stars; $i++) {
                $star_html .= "<span class='star-filled'>★</span>";
        }
        if ($half_star) {
                $star_html .= "<span class='star-filled'>½</span>";
        }
        for ($i = 0; $i < $empty_stars; $i++) {
                $star_html .= "<span class='star-empty'>☆</span>";
        }
            $score_html = " ".number_format($avg_rating, 1)."/5 ($review_count Review".($review_count == 1 ? '' : 's').")";
        } else {
            $star_html = "<span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span>";
            $score_html = " (No reviews yet)";
        }
        echo "<div class='col-lg-3 col-sm-6'>
        <div class='new-arrival-box'>
            <a href='shop-single.php?id=$product_id'>
            <div class='image'>
                <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
        if ($product_in_store <= 0) {
            echo "<div class='sale-btn'>
                        <span class='btn read-more'>out of stock</span>
                    </div>";
        }
        echo "
            </div>
            <div class='content'>
                    <div class='category'>".htmlspecialchars($category)."</div>
                    <h4 class='heading'>".htmlspecialchars($product_name)."</h4>
                    <div class='rating'>$star_html$score_html</div>
                    <div class='product-price mt-2'>
                      <span class='currency'>Rs.</span>
                      <span class='amount'>".number_format($product_price, 2)."</span>
                    </div>
                </div>
            </a>
                </div>
            </div>";
    }
}


function productdetail($review_count = null, $average_rating = null)
{
    global $conn;
    $quantity = 1;

    if (isset($_POST["shop_single_add_to_cart"])) {
        if (!isset($_SESSION["userid"])) {
            echo "<script>alert('Please log in to add items to the cart');</script>";
            echo "<script>window.open('./user_area/login-user.php','_self');</script>";
            return;
        }

        $get_product_id = $_GET['id'];
        $quantity = $_POST['quantity'];
        $userid = $_SESSION["userid"];

        $sql = "SELECT * FROM `cart_details` WHERE userid = '$userid' AND product_id = $get_product_id";
        $result = mysqli_query($conn, $sql);
        $num_of_rows = mysqli_num_rows($result);

        // Retrieve product_in_store from the database
        $product_in_store = 0;
        $query_store = "SELECT product_in_store FROM products WHERE id = '$get_product_id'";
        $result_store = mysqli_query($conn, $query_store);
        if ($row_store = mysqli_fetch_assoc($result_store)) {
            $product_in_store = $row_store['product_in_store'];
        }

        if ($num_of_rows > 0) {
            echo "<script>alert('Item already in cart');</script>";
        } else {
            if ($quantity > $product_in_store) {
                echo "<script>alert('You have exceeded the quantity in store');</script>";
            } else {
                $insert_query = "INSERT INTO `cart_details` (product_id, userid, quantity) VALUES ($get_product_id, '$userid', $quantity)";
                $result_query = mysqli_query($conn, $insert_query);

                if ($result_query) {
                    echo "<script>alert('Item added to cart successfully');</script>";
                } else {
                    echo "<script>alert('Error adding item to cart');</script>";
                }
            }
        }
    }

    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];

        $sql_query = "SELECT products.*, categories.category_name 
                      FROM products 
                      INNER JOIN categories ON products.category_id = categories.id
                      WHERE products.id = '$product_id'";

        $result = mysqli_query($conn, $sql_query);
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['id'];
            $product_name = $row['product_name'];
            $product_description = $row['product_description'];
            $product_price = $row['product_price'];
            $product_image_1 = $row['product_image_1'];
            $product_image_2 = $row['product_image_2'];
            $tag_ids = $row['tag_id'];
            $category = $row["category_name"];
            $product_in_store = $row['product_in_store'];
            $category_id = $row['category_id'];
            
            // Get tag names for display
            $tag_names = [];
            $tag_links = [];
            if (!empty($tag_ids)) {
                $tag_id_array = explode(',', $tag_ids);
                foreach ($tag_id_array as $single_tag_id) {
                    $single_tag_id = trim($single_tag_id); // Remove any whitespace
                    if (!empty($single_tag_id)) {
                        $tag_query = "SELECT tag_name FROM tags WHERE id = '$single_tag_id'";
                        $tag_result = mysqli_query($conn, $tag_query);
                        if ($tag_result && $tag_row = mysqli_fetch_assoc($tag_result)) {
                            $tag_names[] = $tag_row['tag_name'];
                            $tag_links[] = "<a href='tag.php?tag_id={$single_tag_id}'>{$tag_row['tag_name']}</a>";
                        }
                    }
                }
            }

            // Build 5-star rating HTML based on average rating
            if ($review_count && $average_rating !== null) {
                $full_stars = (int) floor($average_rating);
                $half_star = (($average_rating - $full_stars) >= 0.5) ? 1 : 0;
                $empty_stars = 5 - $full_stars - $half_star;

                $star_html = '';
                for ($s = 0; $s < $full_stars; $s++) {
                    $star_html .= "<span class='star-filled'>★</span>";
                }
                if ($half_star) {
                    $star_html .= "<span class='star-filled'>★</span>";
                }
                for ($s = 0; $s < $empty_stars; $s++) {
                    $star_html .= "<span class='star-empty'>☆</span>";
                }
                $score_html = "<span class='ms-1' style='font-size:0.9rem;color:#666;'>(" . number_format($average_rating, 1) . "/5)</span>";
            } else {
                $star_html = "<span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span>";
                $score_html = "<span class='ms-1' style='font-size:0.9rem;color:#666;'>(No reviews yet)</span>";
            }

            $review_label = ($review_count == 1) ? 'Review' : 'Reviews';

            echo "<section class='single-banner bg-light-white margin-top-header'>
                    <div class='container'>
                        <div class='content'>
                            <h1 class='heading'>Shop</h1>
                            <div class='breadcrumb m-0'>
                                <a href='index.php'>Home</a>
                                <span>/</span>
                                <span>{$category}</span>
                                <span>/</span>
                                <span>{$product_name}</span>
                            </div>
                        </div>
                    </div>
                </section>
                <section class='pb-5 padding-top-section'>
                    <div class='container'>
                        <div class='row g-sm-4 gy-5 mb-5'>
                            <div class='col-sm-6'>
                                <div class=''>
                                    <div class='zoom image image-change position-relative overflow-hidden' id='zoom1'>
                                        <img class='zoomable-image' src='./admin_area/product_images/{$product_image_1}' alt='{$product_name}'>
                                        <div class='image-change-full-width'>
                                            <i class='fas fa-magnifying-glass' onclick='openFancybox(this)'></i>
                                        </div>
                                    </div>
                                    <div class='for_change-image d-flex gap-2 pt-2'>
                                        <a href='./admin_area/product_images/{$product_image_1}' data-fancybox='images' data-caption='{$product_name}'>
                                            <img src='./admin_area/product_images/{$product_image_1}' alt='{$product_name}'>
                                        </a>
                                        <a href='./admin_area/product_images/{$product_image_2}' data-fancybox='images' data-caption='{$product_name}'>
                                            <img src='./admin_area/product_images/{$product_image_2}' alt='{$product_name}'>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='product-price-detail ps-sm-5'>
                                    <div class='content product-box-list'>
                                        <div class='title'>
                                            <h4 class='heading'>{$product_name}</h4>
                                            <div class='d-flex align-items-center mb-2 gap-2'>
                                                <span class='badge bg-info' style='font-size:0.9rem;'>{$review_count} {$review_label}</span>
                                                $star_html $score_html
                                            </div>
                                        </div>
                                        <div class='price-tag'>
                                            <ins><span class='price-symbol'>Rs.</span><span>{$product_price}</span></ins>
                                        </div>
                                    </div>
                                    <div class='product-price-description product-box-list'>
                                        <p>{$product_description}</p>
                                    </div>
                                    <div class='product-price-input product-box-list'>
                                        <form action='' method='post'>";

            if ($product_in_store <= 0) {
                echo "<span class='out-of-stock'>Out of stock</span>";
            } else {
                echo "<div class='product-number position-relative d-flex'>
                    <input type='number' name='quantity' min='1' max='{$product_in_store}' value='1' class='quantity-product'>
                </div>
                <input type='submit' name='shop_single_add_to_cart' value='ADD TO CART' class='read-more'>";
            }

            echo "</form>
                                        </div>
                    <div class='product-price-detail-category'>
                        <div class='d-flex gap-2 align-items-center'>
                            <h5 class='heading'>Category:</h5>
                            <a href='category.php?cat_id={$category_id}'>{$category}</a>
                        </div>
                        <div class='d-flex gap-2 align-items-center'>
                            <h5 class='heading'>Tags:</h5>
                            <div class='d-flex gap-1 flex-wrap'>";
                            if (!empty($tag_links)) {
                                echo implode(' ', $tag_links);
                            } else {
                                echo "<span>No tags assigned</span>";
                            }
                            echo "</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>";
        }
    }
}




function cart()
{
    global $conn;

    if (isset($_POST['add_to_cart'])) {
        $userid = $_SESSION["userid"];
        $product_id = $_POST['add_to_cart'];

        // Check if the item is already in the cart
        $check_query = "SELECT * FROM `cart_details` WHERE userid = '$userid' AND product_id = $product_id";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Item already in cart
            echo "Item already in cart";
        } else {
            // Insert the item into the cart
            $insert_query = "INSERT INTO `cart_details` (product_id, userid, quantity) VALUES ($product_id, '$userid', 1)";
            $insert_result = mysqli_query($conn, $insert_query);

            if ($insert_result) {
                // Item successfully added to cart
                echo "Item added to cart";
            } else {
                // Error adding item to cart
                echo "Failed to add item to cart";
            }
        }
    }
}
cart();

function total_product_cart()
{
    if (isset($_SESSION["userid"])) {
        global $conn;
        $userid = $_SESSION["userid"];

        $sql = "SELECT * FROM `cart_details` WHERE userid = '$userid'";
        $result = mysqli_query($conn, $sql);
        $num_of_sqli = mysqli_num_rows($result);

        echo $num_of_sqli;
    } else {
        echo "0"; // If userid is not set, return 0
    }
}



function displayCart()
{
    global $conn;

    $userid = $_SESSION["userid"];
    $cart_query = "SELECT cd.product_id, cd.quantity, p.product_image_1, p.product_price, p.product_name, p.product_in_store FROM cart_details cd JOIN products p ON cd.product_id = p.id WHERE cd.userid='$userid'";
    $run_cart = mysqli_query($conn, $cart_query);

    $total = 0;

    if (isset($_GET['remove_product'])) {
        $product_id_to_remove = $_GET['remove_product'];
        if (isset($_GET['confirm_delete'])) {
            $sql = "DELETE FROM cart_details WHERE product_id = $product_id_to_remove AND userid = '$userid'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "<script>alert('Product with ID $product_id_to_remove removed successfully')</script>";
                // header("Location: {$_SERVER['PHP_SELF']}");
                echo "<script>window.open('cart.php','_self');</script>";
                exit();
            } else {
                echo "<script>alert('Failed to delete product.')</script>";
            }
        }
    }

    if (isset($_POST['update_cart'])) {
        $quantities = $_POST['qty'];
        $update_success = false;
    
        foreach ($quantities as $product_id => $quantity) {
            $update_cart_query = "UPDATE cart_details SET quantity = $quantity WHERE product_id = $product_id AND userid = '$userid'";
            $update_result = mysqli_query($conn, $update_cart_query);
    
            if ($update_result) {
                $update_success = true;
            } else {
                echo "<script>alert('Failed to update quantity for product ID $product_id.');</script>";
            }
        }
    
        if ($update_success) {
            echo "<script>
                    alert('Cart updated successfully!');
                    window.location.href = window.location.href; // Refresh the page
                  </script>";
        }
    }
    

    if (mysqli_num_rows($run_cart) > 0) {
        echo "<section class='cart-section padding-top-section'>
                <div class='container'>
                    <div class='notice'>
                        <div class='update-message'>
                            <i class='fa-solid fa-circle-check'></i>
                            <span>Cart updated.</span>
                        </div>
                    </div>
                    <form action='' method='post'>
                        <table class='cart-list margin-bottom-cart'>
                            <thead>
                                <tr>
                                    <th class='product-remove'></th>
                                    <th class='product-thumbnail'>Image</th>
                                    <th class='product-name'>Product Name</th>
                                    <th class='product-price'>Price</th>
                                    <th class='product-quantity'>Quantity</th>
                                    <th class='product-subtotal'>Total</th>
                                </tr>
                            </thead>
                            <tbody>";

        while ($row_cart = mysqli_fetch_array($run_cart)) {
            $pro_id = $row_cart['product_id'];
            $quantity = $row_cart['quantity'];
            $image = $row_cart['product_image_1'];
            $price = $row_cart['product_price'];
            $product_name = $row_cart['product_name'];
            $product_in_store = $row_cart['product_in_store'];

            echo "<tr>
                    <td class='product-remove'>
                        <a href='{$_SERVER['PHP_SELF']}?remove_product=$pro_id&confirm_delete' onclick='return confirm(\"Are you sure you want to delete this product?\")'>x</a>
                    </td>
                    <td class='product-thumbnail'>
                        <img src='./admin_area/product_images/$image' alt='$product_name'>
                    </td>
                    <td class='product-name'>
                        $product_name
                    </td>
                    <td class='product-price'>
                        <span class='price-symbol'>Rs.</span> $price
                    </td>
                    <td class='product-quantity'>
                        <input type='number' min='1' max='$product_in_store' value='$quantity' class='quantity-product' name='qty[$pro_id]' onchange='validateQuantity(this, $product_in_store)'>
                    </td>
                    <td class='product-subtotal'>
                        <span class='price-symbol'>Rs.</span> " . ($price * $quantity) . "
                    </td>
                </tr>";
            $total += ($price * $quantity);
        }

        echo "
        <tr>
            <td class='text-end pt-5' colspan='6'>
            <input type='submit' name='update_cart' class='btn read-more checkout-btn' value='Update Cart'>
            </td> 
        </tr> 
        </tbody></table>
              <div class='cart-collaterals margin-bottom-cart'>
                <div class='row justify-content-end'>
                    <div class='col-sm-6'>
                        <h2 class='heading underline'>Cart totals</h2>
                        <table>
                            <tbody>
                                <tr class='cart-subtotal'>
                                    <th>Subtotal</th>
                                    <td><span class='price-symbol'>Rs.</span> $total</td>
                                </tr>
                                <tr class='order-total'>
                                    <th>Total</th>
                                    <td><strong><span class='price-symbol'>Rs.</span> $total</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class='proceed-to-checkout'>
                            <a href='./user_area/order.php?user_id=$userid' class='btn read-more checkout-btn'>Proceed to checkout</a>
                        </div>
                    </div>
                </div>
              </div>
              </form>
              </div>
              </section>";
    } else {
        echo "<section class='section-gap'>
                <div class='container'>
                    <h2 class='heading underline center text-center'>Your shopping cart is empty.</h2>
                    <p class='lead text-center'>Add some products to your cart before proceeding. You can also browse our collection of items or visit our shop page for more options.</p>
                </div>
            </section>";
    }

    // JavaScript code for quantity validation
    echo "<script>
        function validateQuantity(input, maxQty) {
            if (parseInt(input.value) > maxQty) {
                alert('Cannot exceed available stock (' + maxQty + ')');
                input.value = maxQty; // Set the quantity to maximum allowed
            }
        }
        </script>";
}

// function totalcart

function total_price_cart()
{
    global $conn;
    $total = 0;
    $userid = $_SESSION["userid"];
    $cart_query = "SELECT * FROM `cart_details` WHERE userid='$userid'";
    $run_cart = mysqli_query($conn, $cart_query);

    while ($row = mysqli_fetch_array($run_cart)) {
        $product_id = $row["product_id"];
        $select_products = "SELECT * FROM `products` WHERE id ='$product_id'";
        $result_products = mysqli_query($conn, $select_products);
        while ($row_products_price = mysqli_fetch_array($result_products)) {
            $product_price = array($row_products_price['product_price']);
            $product_value = array_sum($product_price);
            $total += $product_value;
        }
    }

    echo $total;
}


function user_order()
{
    global $conn;
    $username = $_SESSION["username"];
    $get_details = "SELECT * FROM `user_table` WHERE user_name = '$username'";
    $result_detail = mysqli_query($conn, $get_details);

    // Initialize a variable to keep track of whether the pending order message has been displayed
    $pending_order_displayed = false;

    while ($row_query = mysqli_fetch_array($result_detail)) {
        $user_id = $row_query["user_id"];

        if (!isset($_GET['edit_account']) && !isset($_GET['user_order']) && !isset($_GET['delete_account'])) {
            $get_order = "SELECT * FROM `user_order` WHERE user_id = $user_id AND order_status = 'pending'";
            $result_order_query = mysqli_query($conn, $get_order);
            $row_count = mysqli_num_rows($result_order_query);

            if ($row_count > 0 && !$pending_order_displayed) {
                echo "<div class='pending-order-fn'>
                    <h3 class='heading text-center '>You have <span>$row_count</span> pending orders</h3>
                    <p class='text-center'><a href='../user_area/profile.php?user_order'>Order Details</a></p>
                </div>";
                // Set the flag to true to indicate that the pending order message has been displayed
                $pending_order_displayed = true;
            } elseif (!$pending_order_displayed) {
                echo "<div class='pending-order-fn'>
                    <h3 class='heading text-center '>You have <span>$row_count</span> pending orders</h3>
                    <p class='text-center'><a href='../index.php'>Explore products  </a></p>
                </div>";
                // Set the flag to true to indicate that the pending order message has been displayed
                $pending_order_displayed = true;
            }
        }
    }
}

function category_list()
{
    global $conn;

    if (isset($_GET['cat_id'])) {
        $cat_id = $_GET['cat_id'];

        $sql_query = "SELECT * FROM `products` where category_id = $cat_id ";
        $result = mysqli_query($conn, $sql_query);

        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['id'];
            $product_name = $row['product_name'];
            $product_price = $row['product_price'];
            $product_image = $row['product_image_1'];
            $product_in_store = $row['product_in_store'];

            // $filled_stars = floor($product_rating); 

            // Output HTML code to display product
            echo "<div class='col-lg-3 col-sm-6'>
            <div class='new-arrival-box'>
                <a href='shop-single.php?id=$product_id'>
                <div class='image'>
                    <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
            if ($product_in_store <= 0 || $product_in_store == 1) {
                echo "<div class='sale-btn'>
                            <span class='btn read-more'>out of stock</span>
                        </div>";
            }
            echo "
                </div>
                <div class='content'>
                        <h4 class='heading'>$product_name</h4>
                        <div class='price-tag'>";

            echo "<ins><span class='price-symbol'>Rs.</span><span>$product_price</span></ins>
                            </div>
                        </div>
                </a>
                    </div>
                </div>";
        }
    }
}
function tag_list()
{
    global $conn;

    if (isset($_GET['tag_id'])) {
        $tag_id = $_GET['tag_id'];

        $sql_query = "SELECT * FROM `products` where tag_id = $tag_id ";
        $result = mysqli_query($conn, $sql_query);

        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['id'];
            $product_name = $row['product_name'];
            $product_price = $row['product_price'];
            $product_image = $row['product_image_1'];
            $product_in_store = $row['product_in_store'];

            // $filled_stars = floor($product_rating); 

            // Output HTML code to display product
            echo "<div class='col-lg-3 col-sm-6'>
            <div class='new-arrival-box'>
                <a href='shop-single.php?id=$product_id'>
                <div class='image'>
                    <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
            if ($product_in_store <= 0 || $product_in_store == 1) {
                echo "<div class='sale-btn'>
                            <span class='btn read-more'>out of stock</span>
                        </div>";
            }
            echo "
                </div>
                <div class='content'>
                        <h4 class='heading'>$product_name</h4>
                        <div class='price-tag'>";

            echo "<ins><span class='price-symbol'>Rs.</span><span>$product_price</span></ins>
                            </div>
                        </div>
                </a>
                    </div>
                </div>";
        }
    }
}

function isotop_category($active_cat_id = null, $active_tag_id = null) {
    global $conn;
    // Retrieve all categories
    $sql_query = "SELECT * FROM `categories`";
    $result = mysqli_query($conn, $sql_query);
    echo "<div id='isotope-filters' class='isotope-filters'>";
    echo "<a href='category.php' class='" . (is_null($active_cat_id) ? 'active' : '') . "'>All</a>";
    while ($row = mysqli_fetch_assoc($result)) {
        $cat_id = $row['id'];
        $cat_name = $row['category_name'];
        $active = ($active_cat_id == $cat_id) ? 'active' : '';
        echo "<a href='category.php?cat_id=$cat_id' class='$active'>$cat_name</a>";
    }
    echo "</div>"; // End of isotope filters
    // Retrieve and display products based on category
    if ($active_cat_id) {
        $sql_query = "SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS review_count FROM `products` p LEFT JOIN reviews r ON p.id = r.product_id WHERE p.category_id = $active_cat_id GROUP BY p.id";
    } else {
        $sql_query = "SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS review_count FROM `products` p LEFT JOIN reviews r ON p.id = r.product_id GROUP BY p.id";
    }
    $result = mysqli_query($conn, $sql_query);
    echo "<div id='isotope-container' class='row'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['id'];
        $product_name = $row['product_name'];
        $product_price = $row['product_price'];
        $product_image = $row['product_image_1'];
        $product_in_store = $row['product_in_store'];
        $cat_id = $row['category_id'];
        $avg_rating = $row['avg_rating'];
        $review_count = $row['review_count'];
        
        // Build dynamic star rating
        if ($review_count > 0) {
            $full_stars = floor($avg_rating);
            $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
            $empty_stars = 5 - $full_stars - $half_star;
            $star_html = '';
            for ($i = 0; $i < $full_stars; $i++) {
                $star_html .= "<span class='star-filled'>★</span>";
            }
            if ($half_star) {
                $star_html .= "<span class='star-filled'>½</span>";
            }
            for ($i = 0; $i < $empty_stars; $i++) {
                $star_html .= "<span class='star-empty'>☆</span>";
            }
            $score_html = " ".number_format($avg_rating, 1)."/5 ($review_count Review".($review_count == 1 ? '' : 's').")";
        } else {
            $star_html = "<span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span>";
            $score_html = " (No reviews yet)";
        }
        
        // Output product HTML
        echo "<div class='col-lg-3 col-sm-6 isotope-item cat-$cat_id'>
                <div class='new-arrival-box'>
                    <a href='shop-single.php?id=$product_id'>
                        <div class='image'>
                            <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
        if ($product_in_store <= 0 || $product_in_store == 1) {
            echo "<div class='sale-btn'>
                      <span class='btn read-more'>Out of Stock</span>
                  </div>";
        }
        echo        "</div>
                    <div class='content'>
                        <h4 class='heading'>$product_name</h4>
                        <div class='rating'>$star_html$score_html</div>
                        <div class='price-tag'>
                            Rs. ".number_format($product_price, 2)."
                        </div>
                    </div>
                    </a>
                </div>
            </div>";
    }
    echo "</div>"; // End of isotope container
    // Return the active filter name for breadcrumb
    if ($active_cat_id) {
        $cat_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT category_name FROM categories WHERE id = $active_cat_id"));
        return $cat_name ? $cat_name['category_name'] : null;
    }
    if ($active_tag_id) {
        $tag_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tag_name FROM tags WHERE id = $active_tag_id"));
        return $tag_name ? $tag_name['tag_name'] : null;
    }
    return null;
}

function isotop_tag($active_tag_id = null) {
    global $conn;
    // Retrieve all tags
    $sql_query = "SELECT * FROM `tags`";
    $result = mysqli_query($conn, $sql_query);
    echo "<div id='isotope-filters' class='isotope-filters'>";
    echo "<a href='tag.php' class='" . (is_null($active_tag_id) ? 'active' : '') . "'>All</a>";
    while ($row = mysqli_fetch_assoc($result)) {
        $tag_id = $row['id'];
        $tag_name = $row['tag_name'];
        $active = ($active_tag_id == $tag_id) ? 'active' : '';
        echo "<a href='tag.php?tag_id=$tag_id' class='$active'>$tag_name</a>";
    }
    echo "</div>"; // End of isotope filters
    // Retrieve and display products based on tag
    if ($active_tag_id) {
        $sql_query = "SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS review_count FROM `products` p LEFT JOIN reviews r ON p.id = r.product_id WHERE p.tag_id = $active_tag_id GROUP BY p.id";
    } else {
        $sql_query = "SELECT p.*, COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(r.id) AS review_count FROM `products` p LEFT JOIN reviews r ON p.id = r.product_id GROUP BY p.id";
    }
    $result = mysqli_query($conn, $sql_query);
    echo "<div id='isotope-container' class='row'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['id'];
        $product_name = $row['product_name'];
        $product_price = $row['product_price'];
        $product_image = $row['product_image_1'];
        $product_in_store = $row['product_in_store'];
        $tag_id = $row['tag_id'];
        $avg_rating = $row['avg_rating'];
        $review_count = $row['review_count'];
        
        // Build dynamic star rating
        if ($review_count > 0) {
            $full_stars = floor($avg_rating);
            $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
            $empty_stars = 5 - $full_stars - $half_star;
            $star_html = '';
            for ($i = 0; $i < $full_stars; $i++) {
                $star_html .= "<span class='star-filled'>★</span>";
            }
            if ($half_star) {
                $star_html .= "<span class='star-filled'>½</span>";
            }
            for ($i = 0; $i < $empty_stars; $i++) {
                $star_html .= "<span class='star-empty'>☆</span>";
            }
            $score_html = " ".number_format($avg_rating, 1)."/5 ($review_count Review".($review_count == 1 ? '' : 's').")";
        } else {
            $star_html = "<span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span>";
            $score_html = " (No reviews yet)";
        }
        
        // Output product HTML
        echo "<div class='col-lg-3 col-sm-6 isotope-item tag-$tag_id'>
                <div class='new-arrival-box'>
                    <a href='shop-single.php?id=$product_id'>
                        <div class='image'>
                            <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
        if ($product_in_store <= 0 || $product_in_store == 1) {
            echo "<div class='sale-btn'>
                      <span class='btn read-more'>Out of Stock</span>
                  </div>";
        }
        echo        "</div>
                    <div class='content'>
                        <h4 class='heading'>$product_name</h4>
                        <div class='rating'>$star_html$score_html</div>
                        <div class='price-tag'>
                            Rs. ".number_format($product_price, 2)."
                        </div>
                    </div>
                    </a>
                </div>
            </div>";
    }
    echo "</div>"; // End of isotope container
    // Return the active filter name for breadcrumb
    if ($active_tag_id) {
        $tag_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT tag_name FROM tags WHERE id = $active_tag_id"));
        return $tag_name ? $tag_name['tag_name'] : null;
    }
    return null;
}

function displayTopRatedProducts($limit)
{
    global $conn;
    // Get products with their average rating and review count
    $sql = "SELECT p.*, 
                COALESCE(AVG(r.rating), 0) AS avg_rating, 
                COUNT(r.id) AS review_count
            FROM products p
            LEFT JOIN reviews r ON p.id = r.product_id
            GROUP BY p.id
            ORDER BY review_count DESC, avg_rating DESC
            LIMIT $limit";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['id'];
        $product_name = $row['product_name'];
        $product_price = $row['product_price'];
        $product_image = $row['product_image_1'];
        $product_in_store = $row['product_in_store'];
        $review_count = $row['review_count'];
        $avg_rating = $row['avg_rating'];
        // Star and review count display
        if ($review_count > 0) {
            $full_stars = floor($avg_rating);
            $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
            $empty_stars = 5 - $full_stars - $half_star;
            $star_html = '';
            for ($i = 0; $i < $full_stars; $i++) {
                $star_html .= "<span class='star-filled'>★</span>";
            }
            if ($half_star) {
                $star_html .= "<span class='star-filled'>½</span>";
            }
            for ($i = 0; $i < $empty_stars; $i++) {
                $star_html .= "<span class='star-empty'>☆</span>";
            }
            $score_html = number_format($avg_rating, 1) . "/5 ($review_count Review" . ($review_count == 1 ? '' : 's') . ")";
        } else {
            $star_html = "<span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span>";
            $score_html = "(No reviews yet)";
        }
        echo "<div class='col-lg-3 col-sm-6'>
            <div class='new-arrival-box'>
                <a href='shop-single.php?id=$product_id'>
                <div class='image'>
                    <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
        if ($product_in_store <= 0 || $product_in_store == 1) {
            echo "<div class='sale-btn'>
                            <span class='btn read-more'>out of stock</span>
                        </div>";
        }
        echo "
                </div>
                <div class='content'>
                        <h4 class='heading'>$product_name</h4>
                        <div class='d-flex align-items-center mb-1'>$star_html <span class='ms-2'>$score_html</span></div>
                        <div class='price-tag'>
                            <ins><span class='price-symbol'>Rs.</span><span>$product_price</span></ins>
                        </div>
                    </div>
                </a>
            </div>
        </div>";
    }
}

/*
|--------------------------------------------------------------------------
| Best Selling Algorithm - Pure PHP Step-by-Step Implementation
|--------------------------------------------------------------------------
| All algorithmic logic (filtering, grouping, sorting, selecting) is done
| in PHP. SQL is ONLY used to fetch raw data. No SQL aggregation, no
| SQL sorting, no SQL GROUP BY for the algorithm itself.
|--------------------------------------------------------------------------
*/
function displayBestSellingProducts($limit = 8)
{
    global $conn;

    // ================================================================
    // STEP 1: Fetch ALL raw order records from database
    // ================================================================
    // We fetch every row from order_status. No filtering, no grouping,
    // no sorting in SQL. Just raw data retrieval.
    $sql = "SELECT order_id, product_id, quantity, order_status FROM order_status";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<div class='col-12 text-center'><p>No order records found.</p></div>";
        return;
    }

    // Store all raw records in a PHP array
    $all_orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $all_orders[] = [
            'order_id'    => (int) $row['order_id'],
            'product_id'  => (int) $row['product_id'],
            'quantity'    => (int) $row['quantity'],
            'order_status' => $row['order_status']
        ];
    }

    // ================================================================
    // STEP 2: Filter completed orders in PHP
    // ================================================================
    // Loop through all orders and keep only those with status 'complete'.
    // This is filtering logic done entirely in PHP, not in SQL WHERE.
    $completed_orders = [];
    $total_orders = count($all_orders);

    for ($i = 0; $i < $total_orders; $i++) {
        if ($all_orders[$i]['order_status'] === 'complete') {
            $completed_orders[] = $all_orders[$i];
        }
    }

    if (empty($completed_orders)) {
        echo "<div class='col-12 text-center'><p>No completed orders yet. Check back later!</p></div>";
        return;
    }

    // ================================================================
    // STEP 3: Group by product_id and calculate total quantity (in PHP)
    // ================================================================
    // We manually build a sales map: product_id => total_quantity_sold.
    // No SQL GROUP BY used. All aggregation is done in PHP.
    $sales_map = [];  // key = product_id, value = total quantity
    $total_completed = count($completed_orders);

    for ($i = 0; $i < $total_completed; $i++) {
        $pid = $completed_orders[$i]['product_id'];
        $qty = $completed_orders[$i]['quantity'];

        // Check if this product already exists in our map
        $found = false;
        foreach ($sales_map as $key => $value) {
            if ($key === $pid) {
                $sales_map[$pid] += $qty;
                $found = true;
                break;
            }
        }

        // If product not seen before, add it to the map
        if (!$found) {
            $sales_map[$pid] = $qty;
        }
    }

    // ================================================================
    // STEP 4: Convert sales_map to indexed array for sorting
    // ================================================================
    // We create an array of [product_id, total_sold] pairs so we can
    // apply a sorting algorithm on it.
    $product_sales = [];
    foreach ($sales_map as $pid => $total) {
        $product_sales[] = [
            'product_id' => $pid,
            'total_sold' => $total
        ];
    }

    // ================================================================
    // STEP 5: Sort using Bubble Sort Algorithm (descending by total_sold)
    // ================================================================
    // Classic Bubble Sort implemented in PHP to sort products by
    // total quantity sold in descending order. This makes the sorting
    // logic visible and explicit instead of using built-in functions.
    $n = count($product_sales);

    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            // Compare adjacent elements
            if ($product_sales[$j]['total_sold'] < $product_sales[$j + 1]['total_sold']) {
                // Swap if current is less than next (descending order)
                $temp = $product_sales[$j];
                $product_sales[$j] = $product_sales[$j + 1];
                $product_sales[$j + 1] = $temp;
            }
        }
    }

    // ================================================================
    // STEP 6: Select top N products from sorted array
    // ================================================================
    // We manually pick the first $limit elements from the sorted array.
    // No array_slice used. Simple loop to select top performers.
    $top_products = [];
    $select_count = ($limit > count($product_sales)) ? count($product_sales) : $limit;

    for ($i = 0; $i < $select_count; $i++) {
        $top_products[] = $product_sales[$i];
    }

    // ================================================================
    // STEP 7: Fetch product details for each best selling product
    // ================================================================
    // For each product in our top list, fetch full details from products
    // table, category name from categories table, and compute rating
    // from reviews table. All done individually per product.
    for ($i = 0; $i < count($top_products); $i++) {
        $pid = $top_products[$i]['product_id'];
        $total_sold = $top_products[$i]['total_sold'];

        // 7a: Fetch product basic info
        $prod_query = "SELECT * FROM products WHERE id = $pid";
        $prod_result = mysqli_query($conn, $prod_query);

        if (!$prod_result || mysqli_num_rows($prod_result) == 0) {
            continue; // Skip if product not found
        }
        $product = mysqli_fetch_assoc($prod_result);

        // 7b: Fetch category name
        $cat_id = $product['category_id'];
        $cat_query = "SELECT category_name FROM categories WHERE id = $cat_id";
        $cat_result = mysqli_query($conn, $cat_query);
        $category_name = "Unknown";
        if ($cat_result && mysqli_num_rows($cat_result) > 0) {
            $cat_row = mysqli_fetch_assoc($cat_result);
            $category_name = $cat_row['category_name'];
        }

        // 7c: Fetch all reviews for this product
        $rev_query = "SELECT rating FROM reviews WHERE product_id = $pid";
        $rev_result = mysqli_query($conn, $rev_query);

        // 7d: Calculate average rating manually in PHP (no SQL AVG)
        $total_rating = 0;
        $review_count = 0;
        if ($rev_result) {
            while ($rev_row = mysqli_fetch_assoc($rev_result)) {
                $total_rating += (int) $rev_row['rating'];
                $review_count++;
            }
        }
        $avg_rating = ($review_count > 0) ? ($total_rating / $review_count) : 0;

        // ================================================================
        // STEP 8: Build star rating HTML in PHP
        // ================================================================
        if ($review_count > 0) {
            $full_stars = (int) floor($avg_rating);
            $half_star = (($avg_rating - $full_stars) >= 0.5) ? 1 : 0;
            $empty_stars = 5 - $full_stars - $half_star;

            $star_html = '';
            for ($s = 0; $s < $full_stars; $s++) {
                $star_html .= "<span class='star-filled'>★</span>";
            }
            if ($half_star) {
                $star_html .= "<span class='star-filled'>½</span>";
            }
            for ($s = 0; $s < $empty_stars; $s++) {
                $star_html .= "<span class='star-empty'>☆</span>";
            }
            $score_html = " " . number_format($avg_rating, 1) . "/5 ($review_count Review" . ($review_count == 1 ? '' : 's') . ")";
        } else {
            $star_html = "<span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span><span class='star-empty'>☆</span>";
            $score_html = " (No reviews yet)";
        }

        // ================================================================
        // STEP 9: Display the product card
        // ================================================================
        $product_id = $product['id'];
        $product_name = $product['product_name'];
        $product_price = $product['product_price'];
        $product_image = $product['product_image_1'];
        $product_in_store = $product['product_in_store'];

        echo "<div class='col-lg-3 col-sm-6'>
            <div class='new-arrival-box'>
                <a href='shop-single.php?id=$product_id'>
                <div class='image'>
                    <img src='./admin_area/product_images/$product_image' alt='$product_name'>";
        if ($product_in_store <= 0 || $product_in_store == 1) {
            echo "<div class='sale-btn'>
                            <span class='btn read-more'>out of stock</span>
                        </div>";
        }
        echo "
                </div>
                <div class='content'>
                        <div class='category'>" . htmlspecialchars($category_name) . "</div>
                        <h4 class='heading'>" . htmlspecialchars($product_name) . "</h4>
                        <div class='rating'>$star_html$score_html</div>
                        <div class='price-tag'>
                            Rs. " . number_format($product_price, 2) . "
                        </div>
                        <div class='sold-count' style='color:#6366f1;font-size:0.85rem;font-weight:600;margin-top:0.3rem;'>
                            <i class='fa fa-fire'></i> $total_sold sold
                        </div>

                    </div>
                </a>
            </div>
        </div>";
    }
}

?>