<?php
// -------------------------------
// SESSION
// -------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------
// DATABASE CONFIG
// -------------------------------
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tripshare"; // asigură-te că numele DB este corect

$conn = new mysqli($host, $user, $pass, $db);

// CHECK CONNECTION
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// -------------------------------
// CHARSET FIX
// -------------------------------
$conn->set_charset("utf8mb4");

// -------------------------------
// UPDATE ONLINE STATUS
// (pentru utilizatorii logați)
// -------------------------------
if (isset($_SESSION['user_id'])) {
    $uid = intval($_SESSION['user_id']);
    $conn->query("UPDATE users SET last_seen = NOW() WHERE id = $uid");
}

function clean($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}
?>
