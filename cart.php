<?php
require 'includes/db.php';
include 'includes/navbar.php';

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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <!-- Page heading with icon -->
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-cart3-fill text-success me-2" style="font-size: 2rem;"></i>
        <h2 class="mb-0">Shopping Cart</h2>
    </div>
    
    <div class="row">
        <!-- Cart items section -->
        <div class="col-md-8">
            <?php if (empty($products)): ?>
                <!-- Empty cart message -->
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Your cart is empty</h4>
                        <p class="text-muted mb-4">Start adding some fresh produce to your cart!</p>
                        <a href="index.php" class="btn btn-success">
                            <i class="bi bi-arrow-left me-2"></i>Go Shopping
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Cart items table -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-list-ul text-success me-2"></i>
                            Cart Items (<?= count($products) ?>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">
                                            <i class="bi bi-box-seam me-1"></i>Product
                                        </th>
                                        <th>
                                            <i class="bi bi-currency-dollar me-1"></i>Price
                                        </th>
                                        <th>
                                            <i class="bi bi-123 me-1"></i>Quantity
                                        </th>
                                        <th>
                                            <i class="bi bi-calculator me-1"></i>Total
                                        </th>
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
                                                <i class="bi bi-apple text-success me-2"></i>
                                                <span class="fw-bold"><?= htmlspecialchars($product['name']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold">$<?= number_format($product['price'], 2) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark"><?= $qty ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">$<?= number_format($subtotal, 2) ?></span>
                                        </td>
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

        <!-- Order summary sidebar -->
        <div class="col-md-4 mt-4 mt-md-0">
            <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-receipt-cutoff text-success me-2"></i>
                        Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-muted">
                            <i class="bi bi-cart-check me-1"></i>Subtotal
                        </span>
                        <span class="fw-bold fs-5 text-success">
                            $<?= isset($grand_total) ? number_format($grand_total, 2) : '0.00' ?>
                        </span>
                    </div>
                    
                    <!-- Estimated savings (if applicable) -->
                    <?php if(isset($grand_total) && $grand_total > 0): ?>
                    <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-piggy-bank me-2"></i>
                        <small>You're saving food from waste!</small>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Checkout button -->
                    <?php if(!empty($products)): ?>
                        <a href="checkout.php" class="btn btn-success w-100 btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                        </a>
                    <?php else: ?>
                        <button class="btn btn-success w-100 btn-lg" disabled>
                            <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                        </button>
                    <?php endif; ?>
                    
                    <!-- Continue shopping link -->
                    <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Custom JavaScript -->
<script src="assets/js/main.js"></script>
</body>
</html>