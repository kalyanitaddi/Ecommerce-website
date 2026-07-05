<?php
session_start();
include 'database/db.php';

$logged_in = isset($_SESSION['user_id']);

// Fetch products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div>
                <h1>My Shop</h1>
                <?php if ($logged_in): ?>
                    <p class="subtitle">Welcome back!</p>
                <?php endif; ?>
            </div>

            <nav>
                <?php if ($logged_in): ?>
                    <a href="pages/profile.php">Profile</a>
                    <a href="pages/cart.php">Cart</a>
                    <a href="pages/logout.php" class="logout-button">Logout</a>
                <?php else: ?>
                    <a href="pages/login.php">Login</a>
                    <a href="pages/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main-container">
        <div class="product-list">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product">
                
                <!-- Product Image -->
                <img src="images/<?= $product['image']; ?>">

                <!-- Product Name -->
                <h3><?= htmlspecialchars($product['name']); ?></h3>

                <!-- Price -->
                <p>₹<?= $product['price']; ?></p>

                <!-- Add to Cart -->
                <form method="POST" action="pages/add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                    <button type="submit">Add to Cart</button>
                </form>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products available</p>
    <?php endif; ?>
</div>

</body>
</html>