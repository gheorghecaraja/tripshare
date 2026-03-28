<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About TripShare</title>

    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>

     <!-- ================= NAVBAR SUS ================= -->
     <nav class="navbar">
      <div class="navbar-container">
  
          <!-- LOGO -->
          <a href="index.php" class="navbar-logo">
              <img src="images/logo.png" alt="TripShare logo">
          </a>
  
          <!-- LINKS -->
          <ul class="navbar-links">
            <li><a href="about.php" class="active">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="ride.php" class="publish-link">Publish a ride</a></li>

            <li>
                <a href="search.php" class="icon-link">
                    <img src="https://c.animaapp.com/3GpNtRxg/img/material-symbols-light-search.svg" />
                    Search
                </a>
            </li>
            <li><a href="messages.php" class="icon-link active">Messages</a></li>
            <?php if(isset($_SESSION["user_id"])): ?>
                <li class="navbar-user">
                    <span class="user-name">Hello, 
                        <?= htmlspecialchars($_SESSION["user_name"], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </li>
                <li><a href="logout.php" class="icon-link">Logout</a></li>
            <?php else: ?>
                <li>
                    <a href="login.php" class="icon-link">
                        <img src="https://c.animaapp.com/3GpNtRxg/img/vector.svg" />
                    </a>
                </li>
            <?php endif; ?>

          </ul>
      </div>
  </nav>

    <main class="about-wrapper">

        <!-- ░░░ SECTION BLUE BOX ░░░ -->
        <section class="about-hero">
            <h1>About TripShare</h1>

            <p>
                At TripShare, we’re redefining the way people travel every day.<br>
                By connecting drivers and passengers heading in the same direction, we make commuting smarter,
                cheaper, and greener.<br>
                Together, we’re building a community-driven transport network that reduces traffic,
                emissions, and costs.
            </p>

            <a href="ride.php" class="about-btn">Offer your ride</a>
        </section>

        <!-- ░░░ 3 CARDS (Mission / Values / Vision) ░░░ -->
        <section class="about-cards">

            <!-- CARD 1 -->
            <div class="about-card">
                <div class="icon-circle">
                    <img src="https://c.animaapp.com/30qjjc1A/img/marketeq-eye-2.svg" />
                </div>

                <h2>Mission</h2>

                <p>
                    To create a sustainable and affordable mobility alternative that connects people,
                    reduces congestion, and promotes eco-friendly travel habits.
                </p>
            </div>

            <!-- CARD 2 (BLUE) -->
            <div class="about-card highlight">
                <div class="icon-circle">
                    <img src="https://c.animaapp.com/30qjjc1A/img/marketeq-eye-2.svg" />
                </div>

                <h2>Our Values</h2>

                <p>
                    Sustainability — every shared ride makes a difference for the planet.<br>
                    Community — we believe people travel better when they travel together.<br>
                    Trust — your safety and reliability are at the heart of every journey.
                </p>
            </div>

            <!-- CARD 3 -->
            <div class="about-card">
                <div class="icon-circle">
                    <img src="https://c.animaapp.com/30qjjc1A/img/marketeq-eye-2.svg" />
                </div>

                <h2>Vision</h2>

                <p>
                    To become the leading carpooling platform in the region, empowering communities
                    to move smarter and supporting a cleaner, more connected future.
                </p>
            </div>

        </section>

    </main>

    <!-- ================= FOOTER (NU MODIFIC NIMIC!) ================= -->
      <footer class="site-footer">
        <!-- partea de sus, cu mașina -->
        <div class="footer-hero">
          <img
            src="images/footer-porsche.png"
            alt="TripShare car silhouette"
            class="footer-car"
          />
        </div>
      
        <!-- dreptunghiul negru cu tot conținutul -->
        <div class="footer-main">
          <div class="footer-inner">

            <div class="footer-brand">
              <div class="footer-logo">
                <a href="index.php">
                  <span class="footer-logo-mark">TripShare</span>
                </a>
              </div>
      
              <p class="footer-lead">
                Join our newsletter to stay up to date on features
                and releases.
              </p>
      
              <form class="footer-newsletter" action="newsletter.php" method="post">
                <input
                  type="email"
                  name="newsletter_email"
                  placeholder="Enter your email"
                  required
                />
                <button type="submit">Subscribe</button>
              </form>
      
              <p class="footer-small">
                By subscribing you agree to with our Privacy Policy and provide
                consent to receive updates from our company.
              </p>
            </div>
      
            <!-- Coloană mijloc: Navigation -->
            <nav class="footer-nav" aria-label="Footer navigation">
              <h3>Navigation</h3>
              <ul>
                <li><a href="ride.php">Offer a ride</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="about.php">About</a></li>
              </ul>
            </nav>
      
            <!-- Coloană dreapta: Follow us -->
            <div class="footer-social">
              <h3>Follow us</h3>
              <ul>
                <li>
                  <a href="https://www.tiktok.com/" aria-label="TikTok">
                    <img src="https://c.animaapp.com/3GpNtRxg/img/tiktio.svg" alt="" />
                    <span>TikTok</span>
                  </a>
                </li>
                <li>
                  <a href="#" aria-label="Facebook">
                    <img src="https://c.animaapp.com/3GpNtRxg/img/facebook.svg" alt="" />
                    <span>Facebook</span>
                  </a>
                </li>
                <li>
                  <a href="https://www.instagram.com/" aria-label="Instagram">
                    <img src="https://c.animaapp.com/3GpNtRxg/img/instagram.svg" alt="" />
                    <span>Instagram</span>
                  </a>
                </li>
                <li>
                  <a href="#" aria-label="X">
                    <img src="https://c.animaapp.com/3GpNtRxg/img/x.svg" alt="" />
                    <span>X</span>
                  </a>
                </li>
              </ul>
            </div>

          </div>
      
          <hr class="footer-divider" />
      
          <div class="footer-bottom">
            <p class="footer-copy">© 2025 TripShare. All rights reserved.</p>
            <div class="footer-legal">
             <a href="404.html">Privacy Policy</a>
             <a href="404.html">Terms of Service</a>
             <a href="404.html">Cookies Settings</a>
            </div>
          </div>
        </div>
      </footer>
</body>
</html>
