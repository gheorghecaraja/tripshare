<?php
require "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$email = trim($_POST["newsletter_email"] ?? "");

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<h2>Invalid email address.</h2><a href='index.php'>Back</a>");
}

$stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    echo "<h2>Thank you for subscribing!</h2>
          <p>You have been added to our newsletter.</p>
          <a href='index.php'>Go back</a>";
} else {
    echo "<h2>You are already subscribed or an error occurred.</h2>
          <p>" . htmlspecialchars($conn->error) . "</p>
          <a href='index.php'>Go back</a>";
}
