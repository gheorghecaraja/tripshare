<?php
require 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$currentUser = intval($_SESSION['user_id']);
$ride_id     = intval($_GET['ride_id'] ?? 0);
$otherUser   = intval($_GET['other_id'] ?? 0);

if ($ride_id <= 0 || $otherUser <= 0) {
    echo json_encode([]);
    exit;
}

/*
   Conversație între EXACT 2 oameni:

   currentUser  → otherUser
   otherUser    → currentUser
*/

$sql = "
SELECT m.*, u.name AS sender_name
FROM messages m
JOIN users u ON m.sender_id = u.id
WHERE m.ride_id = ?
  AND (
        (m.sender_id = ? AND m.driver_id = ?) 
     OR (m.sender_id = ? AND m.driver_id = ?)
      )
ORDER BY m.created_at ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii",
    $ride_id,
    $currentUser, $otherUser,
    $otherUser, $currentUser
);

$stmt->execute();
$res = $stmt->get_result();

$messages = [];

while ($row = $res->fetch_assoc()) {
    $messages[] = [
        "id"          => (int)$row["id"],
        "sender_id"   => (int)$row["sender_id"],
        "sender_name" => $row["sender_name"],
        "message"     => $row["message"],
        "created_at"  => $row["created_at"],
        "is_me"       => ($row["sender_id"] == $currentUser)
    ];
}

echo json_encode($messages);
exit;
