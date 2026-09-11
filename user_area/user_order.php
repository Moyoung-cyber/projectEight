<?php
?>

<section class="order_user">
    <style>
    .order_user {
        background: #f4f6fb;
        min-height: 70vh;
        padding: 3rem 0;
    }
    .order-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.10), 0 1.5px 8px rgba(35,39,47,0.08);
        padding: 2.5rem 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }
    .order-card .heading {
        font-size: 2rem;
        font-weight: 700;
        color: #6366f1;
        margin-bottom: 2rem;
        text-align: center;
    }
    .order-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(99,102,241,0.06);
    }
    .order-table th, .order-table td {
        padding: 1rem 0.7rem;
        text-align: center;
        vertical-align: middle;
    }
    .order-table th {
        background: #6366f1;
        color: #fff;
        font-weight: 600;
        border: none;
    }
    .order-table tr {
        border-bottom: 1px solid #e5e7eb;
    }
    .order-table tr:last-child {
        border-bottom: none;
    }
    .order-table td {
        background: #fff;
        color: #23272f;
        font-size: 1.05rem;
    }
    .order-table img {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(99,102,241,0.10);
        background: #f3f4f6;
        width: 70px;
        height: 70px;
        object-fit: cover;
    }
    .order-table a.confirm-btn {
        background: linear-gradient(90deg, #6366f1 60%, #a5b4fc 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        display: inline-block;
    }
    .order-table a.confirm-btn:hover {
        background: linear-gradient(90deg, #4f46e5 60%, #818cf8 100%);
        color: #fff;
    }
    .order-table td.Paid {
        color: #22c55e;
        font-weight: 600;
    }
    .order-table td.Incomplete {
        color: #eab308;
        font-weight: 600;
    }
    .order-table td.Complete {
        color: #22c55e;
        font-weight: 600;
    }
    @media (max-width: 991px) {
        .order-card {
            padding: 1.5rem 0.5rem;
        }
        .order-table th, .order-table td {
            padding: 0.7rem 0.3rem;
            font-size: 0.98rem;
        }
    }
    </style>
    <div class="container order-card">
        <?php
        $user_name = $_SESSION['username'];
        $get_user = "SELECT * FROM `user_table` WHERE user_name = '$user_name'";
        $result_query = mysqli_query($conn, $get_user);
        $row = mysqli_fetch_assoc($result_query);
        $user = $row['user_id'];
        ?>

        <h1 class="heading">All Orders</h1>
        <table class="order-table mt-4">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Product Image</th>
                    <th>Amount Due</th>
                    <th>Total Products</th>
                    <th>Invoice Number</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $number = 1;
                $get_order_details = "SELECT uo.*, p.product_image_1 
                FROM `user_order` uo 
                JOIN `products` p ON uo.product_id = p.id
                WHERE uo.user_id = '$user'";
                $result_order = mysqli_query($conn, $get_order_details);
                while ($row_order = mysqli_fetch_assoc($result_order)) {
                    $order_id = $row_order['order_id']; // Retrieve order_id
                    $amount_due = $row_order['amount_due'];
                    $total_product = $row_order['total_products'];
                    $invoice_number = $row_order['invoice_number'];
                    $order_status = $row_order['order_status'];
                    $image = $row_order['product_image_1'];

                    $status_class = '';
                    if ($order_status == 'pending') {
                        $order_status_display = 'Incomplete';
                        $status_class = 'Incomplete';
                    } else {
                        $order_status_display = 'Complete';
                        $status_class = 'Complete';
                    }

                    $order_date = $row_order['order_date'];

                    echo "<tr>
                            <td>$number</td>
                            <td><img src='../admin_area/product_images/$image' height='80' width='80'/></td>
                            <td>₨ $amount_due</td>
                            <td>$total_product</td>
                            <td>$invoice_number</td>
                            <td>$order_date</td>
                            <td class='$status_class'>$order_status_display</td>";

                    if ($order_status_display == 'Complete') {
                        echo "<td class='Paid'>Paid</td>";
                    } else {
                        echo "<td><a href='confirm_payment.php?order_id=$order_id' class='confirm-btn'>Confirm</a></td>";
                    }

                    echo "</tr>";
                    $number++;
                }
                ?>
            </tbody>
        </table>
    </div>
</section>