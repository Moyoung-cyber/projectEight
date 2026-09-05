<!------------------------------  Header section  ------------------------------>
<?php
$dynamicTitle = "GameBox";
include("header.php");
// include("function/commonfunction.php");
cart();
?>
<!------------------------------ End  header section  ------------------------------>

<style>
.new-arrival-box .rating {
  color: #FFD700;
}
</style>

<section class="main-banner secondary-bg">
    <div class="splide banner-slide">
        <div class="splide__track">
            <ul class="splide__list">
                <li class="splide__slide">
                    <div class="banner"
                        style="background-image: url('image/hero.png'); background-position: bottom center; background-size: contain;">
                        <div class="container">
                            <div class="content d-flex align-items-center justify-content-start h-100">
                                <div class="col-xl-6">
                                    <div class="slide-animation">
                                        <div style="font-size: 1rem; color: #fff; background: #6366f1; display: inline-block; padding: 0.25em 0.75em; border-radius: 20px; font-weight: 700; margin-bottom: 1.2em; letter-spacing: 1px;">LIMITED TIME ONLY!</div>
                                        <h1 class="heading underline animated">
                                          <span style="font-weight: 300; letter-spacing: 2px; margin-bottom: 0.2em; display: inline-block; font-size: 2.2rem;">EPIC GAMER DEALS</span><br>
                                          <span style="font-weight: 800; color: #23272f; margin-top: 0; display: inline-block; font-size: 2.8rem;">ON TOP GAMES & GEAR</span>
                                        </h1>
                                        <p class="animated" style="font-size: 1.2rem; margin-top: 1rem;">
                                          Power up your play with exclusive offers, hot new releases, and up to <span style="color:#6366f1;font-weight:600;">30% OFF</span>!<br>
                                          <span style="color:#23272f;font-weight:500;">Only at <b>GameBox</b>.</span>
                                        </p>
                                        <a href="#other_section" class="btn animated read-more mt-1" style="font-size:1.1rem;letter-spacing:1px;">UNLOCK DEALS</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>
<section id="other_section" class="shop-categorie section-gap">
    <div class="container">
        <div class="row g-sm-4 g-2">
            <div class="col-sm-4 d-flex">
                <div class="shop-small ">
                    <div class="row gy-sm-4 gy-2">
                        <div class="col-12">
                            <div class="image overflow-hidden wow animate__animated animate__fadeInLeft">
                                <img src="image/b3.jpeg" alt="GameBox">
                                <div class="content">
                                    <h3 class="title"><small>Crazy Deals</small><br>
                                        <p style = "color: red;">Buy 3 get 1 free</p>
                                        <small>The best Games are on sale at GameBox</small></h3>
                                    <a href="http://localhost/projectSixth/tag.php?tag_id=2"
                                        class="btn read-more mt-1">Shop Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="image overflow-hidden wow animate__animated animate__fadeInLeft">
                                <img src="image/b4.jpg" alt="GameBox">
                                <div class="content">
                                    <h3 class="title">NEW GAMES SALE<br>
                                        <small style = "color: red;">Spring / Summer 2023</small></h3>
                                    <a href="http://localhost/projectSixth/tag.php?tag_id=3"
                                        class="btn read-more mt-1">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 d-flex">
                <div class="shop-big w-100 wow animate__animated animate__fadeInRight">
                    <div class="image overflow-hidden">
                        <img src="image/b1.jpg" alt="Game Collection">
                        <div class="content">
                            <h3 class="title">Spring/Summer
                                        <p style = "color: red;">Upcoming Games</p><br>
                                        The best Games are on sale at GameBox</h3>
                            <a href="http://localhost/projectSixth/tag.php?tag_id=2" class="btn read-more mt-1">Shop
                                Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="deal-section section-gap">
    <div class="container">
        <div class="d-flex justify-content-sm-end justify-content-center">
            <div class="col-sm-7">
                <div class="d-flex justify-content-center wow animate__animated animate__zoomIn">
                    <div class="text-center">
                        <div class="title">
                            <h3 class="heading">Unleash Your Inner Gamer!</h3>
                        </div>
                        <p>Welcome to <strong>GameBox</strong> – your ultimate destination for the hottest games, next-gen consoles, and must-have accessories. Power up your collection with exclusive deals and limited-time offers you won’t find anywhere else!</p>
                        <p class="mt-2"><span style="color:#6366f1;font-weight:600;">Ready. Set. Play!</span></p>
                        <a href="display_all.php" class="white-btn btn">Shop the Collection</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="shop-collection section-gap wow animate__animated animate__backInUp">
    <div class="container">
        <div class="content text-center">
            <div class="title kaushan">
                <h5 class="heading">Shop Collection</h5>
            </div>
            <h3 class="heading underline">🔥 New Arrivals</h3>
            <p>Be the first to grab the latest releases, trending titles, and exclusive GameBox gear. Don’t miss out—our new arrivals are flying off the shelves!</p>
        </div>

        <div class="row g-xl-5 g-4">
            <?php displayProducts(8, false); ?>
        </div>
    </div>
</section>
<section class="section-gap main-shop">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-8">
                <div class="content text-center wow animate__animated animate__zoomIn">
                    <div class="title">
                        <h4 class="heading">Why Shop at GameBox?</h4>
                    </div>
                    <p>Score unbeatable prices, lightning-fast shipping, and a massive selection of games and gear. Whether you’re a casual player or a hardcore pro, GameBox has everything you need to level up your play.</p>
                    <a href="display_all.php" class="white-btn btn">Browse All Games</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="main-top-rate main-product-box section-gap wow animate__animated animate__backInUp">
    <div class="container">
        <div class="title">
            <h5 class="heading">Top Rated Products</h5>
        </div>
        <div class="row g-xl-5 g-4">
            <?php displayTopRatedProducts(4); ?>
        </div>
        <div id="cartBody"></div>
    </div>
</section>

<!------------------------------  Footer section  ------------------------------>
<?php include("footer.php"); ?>
<!------------------------------ End  Footer section  ------------------------------>