<?php
include '../includes/db.php';

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $error_message = "Email already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $hashed_password]);

        header("Location: login.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main class="main-container">
        <div class="form-container">
            <h2>Register</h2>
            <form method="POST">
                <div class="form-field">
                    <label>Email:</label>
                    <input class="input-field" type="email" name="email" required>
                </div>
                <div class="form-field">
                    <label>Password:</label>
                    <input class="input-field" type="password" name="password" required>
                </div>
                <button type="submit" name="register" class="primary-button">Register</button>
            </form>

            <div class="link-row">
                Already have an account? <a href="login.php">Login</a>
            </div>

            <?php if (isset($error_message)): ?>
                <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>