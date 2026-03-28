<?php
require 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['online' => false]);
    exit;
}

$other_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($other_id <= 0) {
    echo json_encode(['online' => false]);
    exit;
}

$stmt = $conn->prepare("SELECT last_seen FROM users WHERE id = ?");
$stmt->bind_param("i", $other_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['online' => false]);
    exit;
}

$row = $res->fetch_assoc();
$last_seen = $row['last_seen'];

if (!$last_seen) {
    echo json_encode(['online' => false, 'last_seen' => null]);
    exit;
}

// considerăm ONLINE dacă a fost activ în ultimele 2 minute
$last_ts = strtotime($last_seen);
$now     = time();

$online = ($now - $last_ts) <= 120;

echo json_encode([
    'online'    => $online,
    'last_seen' => $last_seen
]);
