<?php require 'config.php'; ?>

<?php
$results = [];
$errors = [];

$from = trim($_GET["from"] ?? "");
$to   = trim($_GET["to"] ?? "");
$date = trim($_GET["date"] ?? "");
$passengers = intval($_GET["passengers"] ?? 1);


/* ============================
      LOGICA DE CĂUTARE
   ============================ */

if ($from === "" && $to === "") {

    // Dacă nu ai introdus nimic → arătăm TOATE cursele
    $sql = "SELECT rides.*, 
                   users.name  AS driver_name,
                   users.email AS driver_email,
                   users.id    AS driver_id
            FROM rides
            JOIN users ON rides.user_id = users.id
            ORDER BY ride_date ASC, ride_time ASC";

    $results = $conn->query($sql);

} else {

    // Căutare după filtre
    $sql = "SELECT rides.*, 
                   users.name  AS driver_name,
                   users.email AS driver_email,
                   users.id    AS driver_id
            FROM rides
            JOIN users ON rides.user_id = users.id
            WHERE leaving_from LIKE ?
              AND going_to LIKE ?";

    if ($date !== "") {
        $sql .= " AND ride_date = ?";
    }

    $sql .= " ORDER BY ride_date ASC, ride_time ASC";

    $stmt = $conn->prepare($sql);

    $like_from = "%{$from}%";
    $like_to   = "%{$to}%";

    if ($date !== "") {
        $stmt->bind_param("sss", $like_from, $like_to, $date);
    } else {
        $stmt->bind_param("ss", $like_from, $like_to);
    }

    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>TripShare - Search for a Ride</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="style.css" />

<style>
/* DOAR GRID — nu schimbăm designul cardului */
.results-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media(max-width: 900px){
    .results-grid { grid-template-columns: repeat(2, 1fr); }
}

@media(max-width: 600px){
    .results-grid { grid-template-columns: 1fr; }
}

/* POPUP simplu, nu atinge cardurile existente */
.popup-bg {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.popup-box {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 340px;
}

.popup-box h2 {
    margin-top: 0;
    margin-bottom: 10px;
}

.popup-box input,
.popup-box textarea {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 14px;
}

.popup-box button {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    background: #457b9d;
    color: #fff;
    font-size: 15px;
}

.popup-close {
    float: right;
    font-size: 22px;
    cursor: pointer;
}
</style>
</head>

<body>

<div class="SEARCH-FOR-a-RIDE">

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
    <div class="navbar-container">

        <a href="index.php" class="navbar-logo">
            <img src="images/logo.png" alt="TripShare logo">
        </a>

        <ul class="navbar-links">
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="ride.php" class="publish-link">Publish a ride</a></li>

            <li>
                <a href="search.php" class="icon-link active">
                    <img src="https://c.animaapp.com/3GpNtRxg/img/material-symbols-light-search.svg" />
                    Search
                </a>
            </li>
            <li><a href="messages.php">Messages</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="navbar-user">
                    <span class="user-name">Hello, 
                        <?= htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>
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


<!-- SEARCH BAR -->
<p class="p">Where do you want to travel?</p>

<div class="search-bar">
    <form class="search-form" action="search.php" method="GET">

        <div class="search-field">
            <img src="https://c.animaapp.com/PiJpJLzd/img/lsicon-location-filled.svg" class="icon">
            <!-- OBLIGATORIU -->
            <input type="text" name="from" placeholder="Leaving from"
                   value="<?= htmlspecialchars($from) ?>" required>
        </div>

        <div class="divider"></div>

        <div class="search-field">
            <img src="https://c.animaapp.com/PiJpJLzd/img/lsicon-location-filled-1.svg" class="icon">
            <!-- OBLIGATORIU -->
            <input type="text" name="to" placeholder="Going to"
                   value="<?= htmlspecialchars($to) ?>" required>
        </div>

        <div class="divider"></div>

        <div class="search-field">
            <img src="https://c.animaapp.com/PiJpJLzd/img/solar-calendar-outline.svg" class="icon">
            <!-- OPTIONAL -->
            <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
        </div>

        <div class="divider"></div>

        <div class="search-field">
            <img src="https://c.animaapp.com/PiJpJLzd/img/material-symbols-person-outline-rounded.svg" class="icon">
            <!-- OPTIONAL -->
            <input type="number" name="passengers" min="1"
                   value="<?= htmlspecialchars($passengers) ?>">
        </div>

        <button type="submit" class="search-btn">Search</button>

    </form>
</div>


<!-- RESULTS SECTION -->

<div class="ticket-section">

<div class="text-wrapper-7" style="font-size:22px;margin-bottom:15px;color:#000;">
    <?= $results ? $results->num_rows : 0 ?> ride(s) found
</div>

<?php if ($results && $results->num_rows > 0): ?>

<div class="results-grid">

<?php while ($ride = $results->fetch_assoc()): ?>

    <div class="ticket-card" style="padding:20px;border:1px solid #ddd;border-radius:10px;">

        <div class="profile">
            <img class="img"
             src="https://c.animaapp.com/PiJpJLzd/img/material-symbols-person-outline-rounded-1.svg" />
            <div class="text-wrapper-4"><?= htmlspecialchars($ride['driver_name']) ?></div>
        </div>

        <!-- AICI am schimbat: ora + DATA, nu ora de 2 ori -->
        <div class="time">
            <div class="text-wrapper-12"><?= htmlspecialchars($ride['ride_time']) ?></div>
            <img class="line-3" src="https://c.animaapp.com/PiJpJLzd/img/line-9.svg" />
            <div class="text-wrapper-14"><?= htmlspecialchars($ride['ride_date']) ?></div>
        </div>

        <div class="frame-15">
            <div class="text-wrapper-15"><?= htmlspecialchars($ride['leaving_from']) ?></div>
            <div class="text-wrapper-15"><?= htmlspecialchars($ride['going_to']) ?></div>
        </div>

        <div class="text-wrapper-16"><?= number_format($ride['price'], 2) ?> Lei</div>

        <!-- AICI doar adaug data-*, restul designului rămâne la fel -->
        <a href="#" class="frame-14 contact-btn"
           data-driver="<?= htmlspecialchars($ride['driver_name']) ?>"
           data-driverid="<?= (int)$ride['driver_id'] ?>"
           data-rideid="<?= (int)$ride['id'] ?>">
            <div class="text-wrapper-11">Contact the driver</div>
        </a>

    </div>

<?php endwhile; ?>

</div>

<?php else: ?>

<p>No rides found. Try different filters.</p>

<?php endif; ?>

</div>


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

<!-- POPUP pentru mesaje -->
<div class="popup-bg" id="popup">
    <div class="popup-box">
        <span class="popup-close" id="popupClose">&times;</span>
        <h2>Contact the driver</h2>

        <form action="send_message.php" method="POST">
            <label>Driver</label>
            <input type="text" id="driverName" readonly>

            <label>Your message</label>
            <textarea name="message" rows="4" required></textarea>

            <input type="hidden" name="driver_id" id="driverId">
            <input type="hidden" name="ride_id" id="rideId">

            <button type="submit">Send message</button>
        </form>
    </div>
</div>

<script>
// logica popup-ului
const popup = document.getElementById('popup');
const popupClose = document.getElementById('popupClose');

document.querySelectorAll('.contact-btn').forEach(btn => {
    btn.addEventListener('click', function(e){
        e.preventDefault();
        document.getElementById('driverName').value = this.dataset.driver;
        document.getElementById('driverId').value   = this.dataset.driverid;
        document.getElementById('rideId').value     = this.dataset.rideid;
        popup.style.display = 'flex';
    });
});

popupClose.addEventListener('click', () => {
    popup.style.display = 'none';
});

popup.addEventListener('click', (e) => {
    if (e.target === popup) {
        popup.style.display = 'none';
    }
});
</script>

</body>
</html>
