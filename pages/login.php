<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../includes/db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stored_password = $user['password'];
        if (password_verify($password, $stored_password)) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php");
            exit();
        }

        // Legacy support for plain-text passwords; upgrade to hashed password on login
        if ($password === $stored_password) {
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$new_hash, $user['id']]);
            $_SESSION['user_id'] = $user['id'];
            header("Location: profile.php");
            exit();
        }
    }

    $error_message = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main class="main-container">
        <div class="form-container">
            <h2>Login</h2>
            <form method="POST">
                <div class="form-field">
                    <label>Email:</label>
                    <input class="input-field" type="email" name="email" required>
                </div>
                <div class="form-field">
                    <label>Password:</label>
                    <input class="input-field" type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="primary-button">Login</button>
            </form>
            <div class="link-row">
                Don't have an account? <a href="register.php">Register</a>
            </div>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>