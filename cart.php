<?php
require 'includes/db.php';
require 'includes/config.php';
require 'includes/functions.php';
// Ensure session is started for cart
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get cart product IDs
$cart_ids = isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : [];
$products = [];

if (!empty($cart_ids)) {
    // Create placeholder string for SQL IN clause (?,?,?)
    $in  = str_repeat('?,', count($cart_ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($cart_ids);
    $products = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | GreenCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <?php include 'includes/navbar.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4 fw-bold">
            <i class="bi bi-cart3 me-2 text-success"></i>Shopping Cart
        </h2>
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'removed'): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                Item removed from cart successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-8">
                <?php if (empty($products)): ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-cart-x" style="font-size: 4rem; color: var(--text-muted);"></i>
                            <h4 class="mt-3 text-muted">Your cart is empty</h4>
                            <p class="text-muted mb-4">Start adding some fresh produce to your cart!</p>
                            <a href="index.php" class="btn btn-success btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $grand_total = 0;
                                        foreach($products as $product): 
                                            $qty = $_SESSION['cart'][$product['id']];
                                            $subtotal = $product['price'] * $qty;
                                            $grand_total += $subtotal;
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <?php 
                                                    $img = $product['image_url'] ? "uploads/products/" . $product['image_url'] : "https://dummyimage.com/100x100/198754/fff&text=Product";
                                                    ?>
                                                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['name']) ?>" 
                                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($product['name']) ?></h6>
                                                        <?php if($product['is_surplus']): ?>
                                                            <small class="badge bg-warning text-white">
                                                                <i class="bi bi-tag-fill me-1"></i>Surplus Deal
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold"><?= format_price($product['price']) ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <form action="cart_action.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                        <input type="hidden" name="action" value="decrease">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary" <?= $qty <= 1 ? 'disabled' : '' ?>>
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                    </form>
                                                    <span class="mx-3 fw-bold" style="min-width: 30px; text-align: center;"><?= $qty ?></span>
                                                    <form action="cart_action.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                        <input type="hidden" name="action" value="add">
                                                        <input type="hidden" name="redirect" value="cart.php">
                                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-success"><?= format_price($subtotal) ?></td>
                                            <td class="text-center">
                                                <form action="cart_action.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                    <input type="hidden" name="action" value="remove">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Remove item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4 mt-4 mt-md-0">
                <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-receipt me-2 text-success"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold fs-5"><?= isset($grand_total) ? format_price($grand_total) : format_price(0) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Shipping</span>
                            <span class="fw-semibold text-success">Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold fs-4 text-success"><?= isset($grand_total) ? format_price($grand_total) : format_price(0) ?></span>
                        </div>
                        <?php if (!empty($products)): ?>
                            <a href="checkout.php" class="btn btn-success w-100 btn-lg mb-2">
                                <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                            </a>
                            <a href="index.php" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" title="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>