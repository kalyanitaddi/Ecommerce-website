<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT email FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main class="main-container">
        <div class="profile-container">
            <h2>Your Profile</h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p>Welcome to your account page.</p>
            <a href="../index.php" class="secondary-button">Go to shop</a>
        </div>
    </main>
</body>
</html>
