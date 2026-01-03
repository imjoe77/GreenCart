<?php
require 'includes/db.php';
require 'includes/config.php';
require 'includes/functions.php';
session_start();

// Ensure cart has items (allow viewing confirmation after placing order)
if (empty($_SESSION['cart']) && !isset($_GET['success'])) {
    header('Location: cart.php');
    exit();
}

// Require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php');
    exit();
}

// Fetch cart products (handle empty cart or show last_order items after success)
$cart_ids = [];
$items_source = [];

if (isset($_GET['success']) && !empty($_SESSION['last_order']['items'])) {
    // When showing order confirmation, use the items from last_order
    $items_source = $_SESSION['last_order']['items'];
    $cart_ids = array_keys($items_source);
} else {
    $items_source = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $cart_ids = array_keys($items_source);
}

$products = [];
if (!empty($cart_ids)) {
    $in  = str_repeat('?,', count($cart_ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($cart_ids);
    $products = $stmt->fetchAll();
}

$grand_total = 0;
foreach ($products as $product) {
    $qty = isset($items_source[$product['id']]) ? $items_source[$product['id']] : 0;
    $grand_total += $product['price'] * $qty;
}

$errors = [];
success:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (!$full_name) $errors[] = 'Full name is required.';
    if (!$address) $errors[] = 'Address is required.';
    if (!preg_match('/^[0-9\-\+\s]{7,20}$/', $phone)) $errors[] = 'Enter a valid phone number.';

    if (empty($errors)) {
        // For now, store the order in session (you can later persist to DB)
        $_SESSION['last_order'] = [
            'user_id' => $_SESSION['user_id'],
            'items' => $_SESSION['cart'],
            'shipping' => ['name' => $full_name, 'address' => $address, 'phone' => $phone],
            'total' => $grand_total,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Clear cart
        unset($_SESSION['cart']);

        header('Location: checkout.php?success=1');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | GreenCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4 fw-bold"><i class="bi bi-credit-card me-2 text-success"></i>Checkout</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show p-4">
                <h4 class="mb-1"><i class="bi bi-check-circle-fill me-2"></i>Order Placed Successfully!</h4>
                <p class="mb-0">Thank you — your order has been placed. We've emailed you a confirmation and will update you about delivery.</p>
            </div>
            <?php if (isset($_SESSION['last_order'])): $order = $_SESSION['last_order']; ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Order Confirmation</h5>
                        <p><strong>Order total:</strong> <?= format_price($order['total']) ?></p>
                        <p><strong>Shipping to:</strong> <?= htmlspecialchars($order['shipping']['name']) ?>, <?= nl2br(htmlspecialchars($order['shipping']['address'])) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($order['shipping']['phone']) ?></p>
                        <p><small class="text-muted">Order placed at <?= $order['created_at'] ?></small></p>
                    </div>
                </div>
                <a href="index.php" class="btn btn-success"><i class="bi bi-arrow-left me-1"></i>Continue Shopping</a>
            <?php endif; ?>
        <?php else: ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-7">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Shipping Details</h5>
                        <form method="POST" action="checkout.php" id="checkoutForm">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <button type="submit" name="place_order" class="btn btn-success btn-lg">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Order Summary</h5>
                        <?php foreach ($products as $product): $qty = isset($items_source[$product['id']]) ? $items_source[$product['id']] : 0; $subtotal = $product['price'] * $qty; ?>
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong><?= htmlspecialchars($product['name']) ?></strong>
                                    <div class="text-muted small">x <?= $qty ?></div>
                                </div>
                                <div><?= format_price($subtotal) ?></div>
                            </div>
                        <?php endforeach; ?>

                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <div>Total</div>
                            <div><?= format_price($grand_total) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
