<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="style.css" />
    <title>TripShare - Home</title>
  </head>

  <body>
    <div class="trip-share">

      <!-- ================= NAVBAR SUS ================= -->
      <nav class="navbar">
        <div class="navbar-container">
    
            <!-- LOGO -->
            <a href="index.php" class="navbar-logo">
                <img src="images/logo.png" alt="TripShare logo">
            </a>
    
            <!-- LINKS -->
            <ul class="navbar-links">
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="ride.php" class="publish-link">Publish a ride</a></li>
    
                <li>
                    <a href="search.php" class="icon-link">
                        <img src="https://c.animaapp.com/3GpNtRxg/img/material-symbols-light-search.svg" />
                        Search
                    </a>
                </li>
                <li><a href="messages.php" class="icon-link active">Messages</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="navbar-user">
                        <span class="user-name">Hello, <?=
                            htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8')
                        ?></span>
                    </li>
                    <li>
                        <a href="logout.php" class="icon-link">
                            Logout
                        </a>
                    </li>
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
    





      <!-- ================= HERO SECTION (SEPARAT) ================= -->
      <header class="hero-section">

        <img class="hero-bg"
             src="https://c.animaapp.com/3GpNtRxg/img/z1200x675-1.png"
             alt="Highway background">
      
        <div class="hero-text">
          <h1>We travel together,<br>we go further.</h1>
        </div>
      
      
        <!-- ================= SEARCH BAR MUTAT ÎN HERO ================= -->
        <div class="search-bar-wrapper">
          <form action="search.php" method="get" class="hero-search">
      
            <div class="search-field">
              <img src="https://c.animaapp.com/PiJpJLzd/img/lsicon-location-filled.svg" />
              <input type="text" placeholder="Leaving from" name="from" />
            </div>
      
            <div class="divider"></div>
      
            <div class="search-field">
              <img src="https://c.animaapp.com/PiJpJLzd/img/lsicon-location-filled-1.svg" />
              <input type="text" placeholder="Going to" name="to" />
            </div>
      
            <div class="divider"></div>
      
            <div class="search-field">
              <img src="https://c.animaapp.com/PiJpJLzd/img/solar-calendar-outline.svg" />
              <input type="date" name="date" />
            </div>
      
            <div class="divider"></div>
      
            <div class="search-field">
              <img src="https://c.animaapp.com/PiJpJLzd/img/material-symbols-person-outline-rounded.svg" />
              <input type="number" min="1" placeholder="Passenger" name="passengers" />
            </div>
      
            <button type="submit" class="search-btn">Search</button>
          </form>
        </div>
      
      </header>
    


      <!-- ================= MAIN CONTENT ================= -->
      <main>

        <!-- Info Section -->
        <section class="frame-7">
          <article class="frame-8">
            <div class="frame-9">
              <img src="https://c.animaapp.com/3GpNtRxg/img/humbleicons-car.svg" />
              <h2>You save money</h2>
            </div>
            <p>You share fuel costs and tolls with other passengers.</p>
          </article>

          <article class="frame-8">
            <div class="frame-9">
              <img src="https://c.animaapp.com/3GpNtRxg/img/solar-earth-linear.svg" />
              <h2>Cleaner environment</h2>
            </div>
            <p>Fewer cars = less pollution and cleaner air.</p>
          </article>

          <article class="frame-8">
            <div class="frame-9">
              <img src="https://c.animaapp.com/3GpNtRxg/img/mdi-people-outline.svg" />
              <h2>You meet new people</h2>
            </div>
            <p>Turn daily commutes into opportunities to connect.</p>
          </article>

          <article class="frame-8">
            <div class="frame-9">
              <img src="https://c.animaapp.com/3GpNtRxg/img/iconamoon-clock-light.svg" />
              <h2>You save time</h2>
            </div>
            <p>Shared routes get you there faster.</p>
          </article>
        </section>






        <!-- Offer a Ride -->
        <section class="frame-11">
          <img class="image" src="https://c.animaapp.com/3GpNtRxg/img/image-28.png">
          <div class="frame-12">
            <h2>Where do you want to travel today?</h2>
            <p>TripShare helps drivers fill empty seats and save money.</p>
            <a class="frame-14" href="ride.php"><span class="text-wrapper-10">Publish a ride</span></a>

        </section>






        <!-- FAQ Section -->
        <!-- FAQ Section -->
        <section class="faq-section">
  
          <div class="faq-container">
            <h2 class="faq-title">Frequently asked questions</h2>
            <p class="faq-subtitle">Learn more about TripShare</p>
        
            <div class="faq-list">
        
              <div class="faq-item">
                <button class="faq-button">
                  <span>Cum funcționează platforma de carpooling?</span>
                  <span class="plus">+</span>
                </button>
                <div class="faq-answer">
                  Este simplu: șoferii publică cursele disponibile, iar pasagerii pot rezerva un loc către aceeași destinație. Totul se face online, rapid și sigur.
                </div>
              </div>
        
              <div class="faq-item">
                <button class="faq-button">
                  <span>Trebuie să plătesc pentru a folosi platforma?</span>
                  <span class="plus">+</span>
                </button>
                <div class="faq-answer">
                  Înregistrarea și căutarea curselor sunt gratuite. Plata se face doar între șofer și pasager, în funcție de distanță și de costurile stabilite.
                </div>
              </div>
        
              <div class="faq-item">
                <button class="faq-button">
                  <span>Cum pot oferi o cursă?</span>
                  <span class="plus">+</span>
                </button>
                <div class="faq-answer">
                  După ce îți creezi un cont, completezi detaliile cursei tale (punct de plecare, destinație, dată, oră, locuri disponibile) și o publici.
                </div>
              </div>
        
              <div class="faq-item">
                <button class="faq-button">
                  <span>Cum pot găsi o cursă potrivită?</span>
                  <span class="plus">+</span>
                </button>
                <div class="faq-answer">
                  Introdu punctul de plecare și destinația dorită. Platforma îți va arăta cursele disponibile, iar tu poți contacta șoferul direct pentru rezervare.
                </div>
              </div>
        
              <div class="faq-item">
                <button class="faq-button">
                  <span>Este sigur să călătoresc cu persoane necunoscute?</span>
                  <span class="plus">+</span>
                </button>
                <div class="faq-answer">
                  Da. Fiecare utilizator are un profil verificat și recenzii de la alți membri ai comunității, pentru a garanta o experiență sigură și plăcută.
                </div>
              </div>
        
            </div>
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
            <!-- Coloană stânga: logo + newsletter -->
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
                  <a href="#" aria-label="Instagram">
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
      
          <!-- Bara de jos: copyright + linkuri legale -->
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

    </div>

    <script src="script.js"></script>


  </body>
</html>
