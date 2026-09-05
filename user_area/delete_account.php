<section class="delete_account text-center">
    <style>
    .delete_account {
        background: #f4f6fb;
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }
    .delete-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(239,68,68,0.10), 0 1.5px 8px rgba(35,39,47,0.08);
        padding: 2.5rem 2rem;
        max-width: 400px;
        width: 100%;
        margin: 0 auto;
        border: 2px solid #f87171;
    }
    .delete-card .heading {
        font-size: 1.7rem;
        font-weight: 700;
        color: #ef4444;
        margin-bottom: 1.5rem;
    }
    .delete-card .warning-text {
        color: #b91c1c;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        font-weight: 500;
    }
    .delete-card .btn-delete {
        background: linear-gradient(90deg, #ef4444 60%, #f87171 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        transition: background 0.2s;
        width: 100%;
        margin-bottom: 1rem;
    }
    .delete-card .btn-delete:hover {
        background: linear-gradient(90deg, #b91c1c 60%, #f87171 100%);
        color: #fff;
    }
    .delete-card .btn-cancel {
        background: #f3f4f6;
        color: #23272f;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        transition: background 0.2s, color 0.2s;
        width: 100%;
    }
    .delete-card .btn-cancel:hover {
        background: #e0e7ef;
        color: #ef4444;
    }
    </style>
    <div class="container d-flex justify-content-center align-items-center" style="min-height:60vh;">
        <div class="delete-card">
            <h3 class="heading">Delete Account</h3>
            <div class="warning-text">Are you sure you want to delete your account? This action cannot be undone.</div>
            <form action="" method="post">
                <div class="form-outline mb-3">
                    <input type="submit" class="btn-delete" name="delete" value="Delete Account">
                </div>
                <div class="form-outline mb-2">
                    <input type="submit" class="btn-cancel" name="dont_delete" value="Don't Delete Account">
                </div>
            </form>
        </div>
    </div>
</section>

<?php

$username = $_SESSION["username"];

if (isset($_POST['delete'])) {
    $delete_user = "DELETE FROM user_table WHERE user_name= '$username'";
    $result = mysqli_query($conn, $delete_user);

    if ($result) {
        session_destroy();
        echo "<script>alert('Account deleted')</script>";
        echo "<script>window.open('../index.php','_self')</script>";
    }
}
if (isset($_POST['delete'])) {
    echo "<script>widow.open('profile.php','_self')</script>";
}

?>