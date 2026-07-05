
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

/* ✅ REMOVE ITEM */
if (isset($_POST['remove_from_cart'])) {
    $cart_id = $_POST['cart_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);

    header("Location: cart.php");
    exit();
}

/* ✅ UPDATE QUANTITY */
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) $quantity = 1;

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$quantity, $cart_id, $user_id]);

    header("Location: cart.php");
    exit();
}

/* ✅ PLACE ORDER */
if (isset($_POST['place_order'])) {
    $stmt = $conn->prepare("SELECT cart.product_id, cart.quantity, products.price FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
    $stmt->execute([$user_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($order_items) > 0) {
        $total_cost = 0;
        foreach ($order_items as $item) {
            $total_cost += $item['price'] * $item['quantity'];
        }

        $conn->beginTransaction();
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_cost]);
        $order_id = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($order_items as $item) {
            $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
        }

        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $conn->commit();

        $success_message = "Order placed successfully!";
        $order_total_param = urlencode(number_format($total_cost, 2));
        header("Location: cart.php?success=1&order_total={$order_total_param}");
        exit();
    }
}

/* ✅ FETCH CART ITEMS */
$stmt = $conn->prepare("
    SELECT cart.id AS cart_id, products.name, products.price, products.image, cart.quantity 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;
$display_cost = null;
if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['order_total'])) {
    $display_cost = htmlspecialchars($_GET['order_total']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial;
        }
        .cart-container {
            width: 80%;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        img {
            width: 80px;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
        input[type="number"] {
            width: 60px;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Your Cart</h2>

    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>

        <?php if (count($cart_items) > 0): ?>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td>
                    <img src="../images/<?= $item['image']; ?>">
                </td>

                <td><?= htmlspecialchars($item['name']); ?></td>

                <td>$<?= number_format($item['price'], 2); ?></td>

                <td>
                    <form method="POST">
                        <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                        <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1">
                        <button type="submit" name="update_quantity">Update</button>
                    </form>
                </td>

                <td>
                    $<?= number_format($item['price'] * $item['quantity'], 2); ?>
                </td>

                <td>
                    <form method="POST">
                        <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                        <button type="submit" name="remove_from_cart">Remove</button>
                    </form>
                </td>
            </tr>

            <?php 
            $total_cost += $item['price'] * $item['quantity']; 
            endforeach; 
            ?>

        <?php else: ?>
            <tr>
                <td colspan="6">Your cart is empty</td>
            </tr>
        <?php endif; ?>

    </table>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color: green; font-weight: bold;">Order placed successfully!</p>
        <?php if (isset($_GET['order_total'])): ?>
            <p style="font-weight: bold;">Order Total: ₹<?= htmlspecialchars($_GET['order_total']); ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <h3>Total Cost: ₹<?= $display_cost !== null ? $display_cost : number_format($total_cost, 2); ?></h3>

    <?php if (count($cart_items) > 0): ?>
        <form method="POST">
            <button type="submit" name="place_order">Place Order</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>


