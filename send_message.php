<?php
require 'config.php';

// User trebuie să fie logat
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$sender_id = intval($_SESSION['user_id']);
$driver_id = intval($_POST["driver_id"] ?? 0);
$ride_id   = intval($_POST["ride_id"] ?? 0);
$message   = trim($_POST["message"] ?? "");

// Validare
if ($driver_id <= 0 || $ride_id <= 0 || $message === "") {

    // Dacă cere AJAX → JSON
    if (isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
        echo json_encode(["success" => false, "error" => "Invalid data"]);
        exit;
    }

    // Form normal
    die("Invalid data");
}

$sql = "INSERT INTO messages (ride_id, driver_id, sender_id, message, created_at)
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $ride_id, $driver_id, $sender_id, $message);
$stmt->execute();

// Dacă vine prin AJAX (popup sau mini-chat)
if (isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
    echo json_encode(["success" => true]);
    exit;
}

// Dacă vine normal (formular simplu）→ redirecționăm la messages
header("Location: messages.php");
exit;
