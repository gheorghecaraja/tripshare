<?php require 'config.php'; ?>

<?php
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$success = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $leaving     = trim($_POST["leaving-from"] ?? "");
    $going       = trim($_POST["going-to"] ?? "");
    $passengers  = intval($_POST["passenger"] ?? 0);
    $ride_date   = trim($_POST["date"] ?? "");
    $ride_time   = trim($_POST["time"] ?? "");
    $price       = 0.00; // ai coloană price în DB, deci setăm default

    if ($leaving === "") $errors[] = "Leaving from is required.";
    if ($going === "") $errors[] = "Going to is required.";
    if ($passengers < 1) $errors[] = "Passengers must be at least 1.";
    if ($ride_date === "") $errors[] = "Date is required.";
    if ($ride_time === "") $errors[] = "Time is required.";

    if (!$errors) {

        $sql = "INSERT INTO rides (user_id, leaving_from, going_to, ride_date, ride_time, passengers, price) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // ❗ FIX DOI: tipuri corecte → "issssid"
        $stmt->bind_param(
            "issssid",
            $_SESSION["user_id"],
            $leaving,
            $going,
            $ride_date,
            $ride_time,
            $passengers,
            $price
        );

        if ($stmt->execute()) {
            $success = "Your ride has been published successfully!";
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>TripShare - Publish a Ride</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="style.css" />

    <style>
      .publish-a-ride a {
        text-decoration: none !important;
      }
      .publish-a-ride a:hover {
        text-decoration: none !important;
      }
      a {
        cursor: pointer;
      }
      .form-field {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .form-field img {
        position: absolute;
        left: 10px;
        width: 22px;
        height: 22px;
        pointer-events: none;
        opacity: 0.9;
      }
      .form-field input {
        padding-left: 40px;
        z-index: 2;
      }
    </style>
  </head>
  <body>
    <div class="publish-a-ride">

   <nav class="navbar">
    <div class="navbar-container">

        <a href="index.php" class="navbar-logo">
            <img src="images/logo.png" alt="TripShare logo">
        </a>

        <ul class="navbar-links">
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="ride.php" class="publish-link active">Publish a ride</a></li>

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


      <div class="rectangle"></div>

      <main>

        <section class="hero-section">

          <h1 class="text-wrapper-16">
            Become a TripShare driver.
          </h1>

          <aside class="ride-publish">

            <?php if ($success): ?>
              <p style="padding:12px;background:#d5ffd5;border-radius:8px;color:#0b6b0b;">
                <?= $success ?>
              </p>
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

            <form class="ride-publish-form" id="rideForm" action="ride.php" method="POST">

              <div class="form-field">
                <img class="lsicon-location"
                  src="https://c.animaapp.com/VbWKg8Bm/img/lsicon-location-filled-1.svg" />
                <label for="leaving-from" class="text-wrapper-11">Leaving from</label>
                <input type="text" id="leaving-from" name="leaving-from" required />
              </div>


              <div class="form-field">
                <img class="lsicon-location-2"
                  src="https://c.animaapp.com/VbWKg8Bm/img/lsicon-location-filled-2.svg" />
                <label for="going-to" class="text-wrapper-12">Going to</label>
                <input type="text" id="going-to" name="going-to" required />
              </div>


              <div class="form-field">
                <img class="material-symbols"
                  src="https://c.animaapp.com/VbWKg8Bm/img/material-symbols-person-outline-rounded-2.svg" />
                <label for="passenger" class="text-wrapper-13">Passenger</label>
                <input type="number" id="passenger" name="passenger" min="1" required />
              </div>

              <div class="form-field">
                <img class="material-symbols-date"
                  src="https://www.pngall.com/wp-content/uploads/2016/10/Calendar-Download-PNG.png" />
                <label for="date" class="text-wrapper-13">Date</label>
                <input type="date" id="date" name="date" required />
              </div>

              <div class="form-field">
                <img class="material-symbols-time"
                  src="https://www.pngall.com/wp-content/uploads/2016/10/Calendar-Download-PNG.png" />
                <label for="time" class="text-wrapper-13">Time</label>
                <input type="time" id="time" name="time" required />
              </div>

              <p class="text-wrapper-14">Save money on your first trip with friends.</p>

              <button type="submit" class="frame-5">
                <span class="text-wrapper-15">Publish a ride</span>
              </button>

            </form>
          </aside>

          <div class="design">
            <img class="ellipse" src="https://c.animaapp.com/VbWKg8Bm/img/ellipse-4.svg" />
            <img class="ellipse-2" src="https://c.animaapp.com/VbWKg8Bm/img/ellipse-5.svg" />
            <img class="happy-couple-young"
              src="https://c.animaapp.com/VbWKg8Bm/img/happy-couple-young-people-rides-600nw-1619620189-removebg-previe@2x.png" />
          </div>

          <br>

        </section>

        <section class="frame-6">
          <h2 class="text-wrapper-17">Drive. Connect. Save.</h2>
          <div class="frame-7">

            <article class="frame-8">
              <div class="frame-9"><h3 class="text-wrapper-18">Drive</h3></div>
              <p class="text-wrapper-19">
                Stay in control of your plans, hit the road your way and make the most of every empty seat.
              </p>
            </article>

            <article class="frame-10">
              <div class="frame-9"><h3 class="text-wrapper-18">Connect</h3></div>
              <p class="text-wrapper-19">
                Meet great people, share stories, and make every journey a memorable experience.
              </p>
            </article>

            <article class="frame-11">
              <div class="frame-9"><h3 class="text-wrapper-18">Save</h3></div>
              <p class="text-wrapper-19">
                Split fuel, tolls, and parking costs easily while helping reduce your carbon footprint.
              </p>
            </article>

          </div>
        </section>

        <section class="benefits">
          <div class="frame-12">

            <div class="frame-13">
              <div class="text-wrapper-20">25M+</div>
              <div class="text-wrapper-21">drivers saving with RideLink</div>
            </div>

            <div class="frame-14">
              <div class="text-wrapper-20">110M+</div>
              <p class="text-wrapper-21">travelers sharing the road together</p>
            </div>

            <div class="frame-15">
              <div class="text-wrapper-20">45M+</div>
              <p class="text-wrapper-21">rides made simple every year</p>
            </div>

          </div>
        </section>

      </main>

     
      <footer class="site-footer">
        <div class="footer-hero">
          <img src="images/footer-porsche.png" class="footer-car" />
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
                <input type="email" name="newsletter_email" placeholder="Enter your email" required />
                <button type="submit">Subscribe</button>
              </form>
      
              <p class="footer-small">
                By subscribing you agree to with our Privacy Policy and provide
                consent to receive updates from our company.
              </p>
            </div>
      
            <nav class="footer-nav">
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
                <li><a href="https://www.tiktok.com/"><img src="https://c.animaapp.com/3GpNtRxg/img/tiktio.svg"><span>TikTok</span></a></li>
                <li><a href="#"><img src="https://c.animaapp.com/3GpNtRxg/img/facebook.svg"><span>Facebook</span></a></li>
                <li><a href="#"><img src="https://c.animaapp.com/3GpNtRxg/img/instagram.svg"><span>Instagram</span></a></li>
                <li><a href="#"><img src="https://c.animaapp.com/3GpNtRxg/img/x.svg"><span>X</span></a></li>
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
