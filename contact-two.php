<?php
$dynamicTitle = "Contact";
include ("header.php");
include './include/connect_database.php';
// include ("function/commonfunction.php");

if (isset($_POST['submit'])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];


    $insert_query = "INSERT INTO contact_message (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    $result = mysqli_query($conn, $insert_query);
    if ($result) {
        echo "<script>alert('Message sent successfully.')</script>";
    } else {
        echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
    }

}
?>



<section class="contact-two margin-top-header" style="background: #f8f9fb;">
    <div class="container py-5">
        <!-- Contact Form and Info Section -->
        <div class="row align-items-start mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="content p-4 shadow rounded bg-white h-100">
                    <span style="color:#6366f1; font-weight:600; text-transform:uppercase; letter-spacing:1px; font-size:0.95rem;">Say hi to the team</span>
                    <h1 class="heading mb-3" style="font-size:2.2rem; font-weight:700;">Contact Us</h1>
                    <p class="mb-4" style="color:#555;">Feel free to contact us and we will get back to you as soon as we can.</p>
                    <form action="" method="post">
                        <div class="mb-3">
                            <input class="form-control form-control-lg" name="name" type="text" placeholder="Name" required>
                        </div>
                        <div class="mb-3">
                            <input class="form-control form-control-lg" name="email" type="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input class="form-control form-control-lg" name="subject" type="text" placeholder="Subject" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control form-control-lg" name="message" placeholder="Message Us" rows="4" required></textarea>
                        </div>
                        <input type="submit" class="btn btn-primary px-4 py-2" name="submit" value="Send">
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content p-4 shadow rounded bg-white h-100">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <h6 class="heading mb-0" style="color: #1f2937; font-weight: 500; font-size: 1.05rem;">Address</h6>
                        </div>
                        <p class="mb-0 ms-5" style="color: #6b7280; font-size: 1.1rem;">Bhaktapur, Nepal</p>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <h6 class="heading mb-0" style="color: #1f2937; font-weight: 500; font-size: 1.05rem;">Support</h6>
                        </div>
                        <a href="mailto:gamerbox@gmail.com" class="text-decoration-none ms-5" style="color: #6366f1; font-size: 1.1rem; transition: color 0.3s ease;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#6366f1'">
                            gamerbox@gmail.com
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <div class="me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <h6 class="heading mb-0" style="color: #1f2937; font-weight: 500; font-size: 1.05rem;">Call us</h6>
                        </div>
                        <a href="tel:+9779861744430" class="text-decoration-none ms-5" style="color: #6366f1; font-size: 1.1rem; transition: color 0.3s ease;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#6366f1'">
                            +977 9861744430
                        </a>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="mb-3" style="color: #374151; font-weight: 600;">Follow us</h6>
                        <div class="d-flex gap-3">
                            <a href="https://www.instagram.com/" target="_blank" class="social-link" style="width: 45px; height: 45px; background: linear-gradient(135deg, #e91e63, #c2185b); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(233, 30, 99, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fab fa-instagram text-white fs-5"></i>
                            </a>
                            <a href="https://www.facebook.com/" target="_blank" class="social-link" style="width: 45px; height: 45px; background: linear-gradient(135deg, #1877f2, #0d6efd); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(24, 119, 242, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fab fa-facebook-f text-white fs-5"></i>
                            </a>
                            <a href="https://www.linkedin.com/" target="_blank" class="social-link" style="width: 45px; height: 45px; background: linear-gradient(135deg, #0077b5, #005885); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(0, 119, 181, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fab fa-linkedin-in text-white fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section - Full Width Landscape -->
        <div class="row">
            <div class="col-12">
                <div class="content shadow rounded bg-white overflow-hidden">
                    <div class="p-4 border-bottom">
                        <h5 class="heading mb-0" style="font-size:2rem; font-weight:700; letter-spacing:1px; color:#4f46e5; background:rgba(99,102,241,0.07); border-left:6px solid #6366f1; padding:0.5rem 1rem; border-radius:0 12px 12px 0; box-shadow:0 2px 8px rgba(99,102,241,0.07); display:inline-block;">
    <i class="fas fa-map-marker-alt me-2" style="color:#6366f1;"></i>Our Location
</h5>
                    </div>
                    <div class="w-100" style="height:400px;">
                        <iframe id="userMap" src="https://www.google.com/maps?q=Bhaktapur,Nepal&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        var map = document.getElementById('userMap');
        map.src = "https://www.google.com/maps?q=" + lat + "," + lng + "&output=embed";
    });
}
</script>
<?php include ("footer.php"); ?>