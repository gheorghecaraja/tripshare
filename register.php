<?php
require "config.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $phone    = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";

    // VALIDARI
    if ($name === "")  $errors[] = "Your name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (!preg_match("/^[0-9+\- ]{6,20}$/", $phone)) $errors[] = "Invalid phone number.";
    if (strlen($password) < 6) $errors[] = "Password must have at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (!$errors) {

        // Check if email/phone already exist
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
        $check->bind_param("ss", $email, $phone);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "An account with this email or phone already exists.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hash);

            if ($stmt->execute()) {

                $_SESSION["user_id"] = $stmt->insert_id;
                $_SESSION["user_name"] = $name;

                header("Location: index.php");
                exit;

            } else {
                $errors[] = "Registration failed: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripShare - Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-container">

    <div class="login-image">
        <img src="images/login-bg.png" alt="Road">
    </div>

    <div class="login-form-section">

        <a href="index.php"><img src="images/logo.png" class="login-logo"></a>

        <h2 class="login-title">Create your account</h2>

        <!-- ERROR BOX -->
        <?php if ($errors): ?>
        <div class="error-box" style="background:#ffd4d4;padding:15px;border-radius:10px;margin-bottom:15px;color:#7a0000;">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="register.php">

            <label class="label">Full Name</label>
            <input type="text" name="name" placeholder="Your Name" required>

            <label class="label">Email</label>
            <input type="email" name="email" placeholder="Your Email" required>

            <label class="label">Phone Number</label>
            <input type="text" name="phone" placeholder="Phone Number" required>

            <label class="label">Password</label>
            <input type="password" name="password" placeholder="Enter password" required>

            <label class="label">Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>

            <button type="submit" class="btn-primary btn-link-button">Create Account</button>

            <div class="divider"></div>

            <p class="signup-text">
                Already have an account?
                <a href="login.php" class="signup-link">Sign in</a>
            </p>

        </form>

    </div>

</div>

</body>
</html>
