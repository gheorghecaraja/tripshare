<?php require 'config.php'; ?>

<?php
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login = trim($_POST["login"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($login === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {

        $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];

                header("Location: index.php");
                exit;

            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Account not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripShare - Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-container">

    <div class="login-image">
        <img src="images/login-bg.png" alt="TripShare Road">
    </div>

    <div class="login-form-section">
        
       <a href="index.php">
            <img src="images/logo.png" class="login-logo">
       </a>

        <h2 class="login-title">Nice to see you again</h2>

        <?php if ($error): ?>
            <p style="color:red; margin-bottom:10px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form class="login-form" action="login.php" method="POST">

            <label class="label">Login</label>
            <input type="text" name="login" placeholder="Email or phone number" required>

            <label class="label">Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="login-options">
                <a href="404.html" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="btn-primary">Sign in</button>

            <div class="divider"></div>

<a href="404.html" class="btn-google">
    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg">
    Or sign in with Google
</a>


            <p class="signup-text">
                Don’t have an account?
                <a href="register.php" class="signup-link">Sign up now</a>
            </p>

        </form>
    </div>
</div>

</body>
</html>
