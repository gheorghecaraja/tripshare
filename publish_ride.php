<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leaving_from = trim($_POST['leaving-from'] ?? '');
    $going_to     = trim($_POST['going-to'] ?? '');
    $passengers   = (int)($_POST['passenger'] ?? 0);
    $date         = $_POST['date'] ?? '';
    $time         = $_POST['time'] ?? '';
    $price        = (float)($_POST['price'] ?? 0);
    $user_id      = $_SESSION['user_id'];

    if ($leaving_from === '' || $going_to === '' || !$passengers || !$date || !$time) {
        $errors[] = "All fields are required.";
    }

    if (!$errors) {
        $stmt = $conn->prepare("
            INSERT INTO rides (user_id, leaving_from, going_to, passengers, ride_date, ride_time, price)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ississd", $user_id, $leaving_from, $going_to, $passengers, $date, $time, $price);

        if ($stmt->execute()) {
            $success = "Ride published successfully!";
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}

// vom afișa un răspuns simplu
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Publish ride</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="centered-box">
    <?php if ($errors): ?>
        <h2>Something went wrong</h2>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="ride.php">Back to form</a>
    <?php else: ?>
        <h2><?= e($success) ?></h2>
        <a href="search.php">Search rides</a> |
        <a href="ride.php">Publish another ride</a>
    <?php endif; ?>
</div>
</body>
</html>
