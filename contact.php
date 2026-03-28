<?php require 'config.php'; ?>
<?php
$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first  = trim($_POST["first_name"] ?? "");
    $last   = trim($_POST["last_name"] ?? "");
    $email  = trim($_POST["email"] ?? "");
    $phone  = trim($_POST["phone"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($first === "") $errors[] = "First name is required.";
    if ($last === "") $errors[] = "Last name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
    if ($phone === "") $errors[] = "Phone number is required.";
    if ($message === "") $errors[] = "Message is required.";

    if (!$errors) {

        // INSERT CORECT în tabelul contact_messages
        $sql = "INSERT INTO contact_messages 
                (first_name, last_name, email, phone, message, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first, $last, $email, $phone, $message);
        $stmt->execute();

        $success = "Your message has been sent successfully!";
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>TripShare - Contact Us</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="contact-us-page">

  <nav class="navbar">
    <div class="navbar-container">

        <a href="index.php" class="navbar-logo">
            <img src="images/logo.png" alt="TripShare logo">
        </a>

        <ul class="navbar-links">
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
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
                    <a href="logout.php" class="icon-link">Logout</a>
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


      <div class="header-wrapper">
        <img class="mask-group" src="https://img.freepik.com/premium-photo/landscape-road-winter-forest-with-snow-covered-wilderness_494741-15385.jpg?semt=ais_incoming&w=740&q=80" alt="Header background" />
        <div class="rectangle"></div>
        <img class="group" src="https://c.animaapp.com/tbov1HX2/img/group@2x.png" alt="Header decoration" />
        <div class="text-wrapper-4">Contact Us</div>
      </div>

      <div class="frame-4">
        <div class="group-wrapper">
          <div class="frame-wrapper">
            <div class="frame-5">

              <div class="frame-6">
                <div class="let-s-talk-with-us">Let's talk with us</div>
                <p class="p">
                  Questions, comments, or suggestions? Simply fill in the form and we'll be in touch shortly.
                </p>
              </div>

              <div class="frame-7">
                <div class="frame-8">
                  <img class="img" src="https://c.animaapp.com/tbov1HX2/img/frame-3879.svg" alt="Location icon" />
                  <p class="text-wrapper-5">Strada Sarmizegetusa 48, MD-3032, Chișinău</p>
                </div>

                <div class="frame-7">
                  <div class="frame-9">
                    <img class="noun-phone" src="https://c.animaapp.com/tbov1HX2/img/noun-phone-3612570-1.svg" alt="Phone icon" />
                    <div class="text-wrapper-6">+373 227 32 221</div>
                  </div>
                  <div class="frame-10">
                    <img class="noun-email" src="https://c.animaapp.com/tbov1HX2/img/noun-email-247564-1.svg" alt="Email icon" />
                    <div class="text-wrapper-7">TripShare@info.com</div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <section class="contact-section">
          <?php if ($success): ?>
            <p style="padding:12px;background:#d5ffd5;border-radius:8px;color:#0b6b0b;"><?= $success ?></p>
          <?php endif; ?>

          <?php if ($errors): ?>
            <div style="padding:12px;background:#ffd5d5;border-radius:8px;color:#8a0000;">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
          <?php endif; ?>

          <form id="contactForm" class="contact-form" action="contact.php" method="POST">
            <h2>Send us a Message</h2>
            <input type="text" name="first_name" placeholder="First Name" required />
            <input type="text" name="last_name" placeholder="Last Name" required />
            <input type="email" name="email" placeholder="Your Email" required />
            <input type="tel" name="phone" placeholder="Phone number" required />
            <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
            <button type="submit">Send Message</button>
          </form>
        </section>
      </div>

 <footer class="site-footer">
        <div class="footer-hero">
          <img
            src="images/footer-porsche.png"
            alt="TripShare car silhouette"
            class="footer-car"
          />
        </div>
      
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

            <nav class="footer-nav" aria-label="Footer navigation">
              <h3>Navigation</h3>
              <ul>
                <li><a href="ride.php">Offer a ride</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="about.php">About</a></li>
              </ul>
            </nav>
      
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
  </body>
</html>



     