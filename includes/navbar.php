<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand text-success fw-bold d-flex align-items-center" href="index.php">
            <i class="bi bi-basket3-fill me-2"></i> GreenCart
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="index.php">
                        <i class="bi bi-shop me-1"></i>Shop Surplus
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium" href="mission.php">
                        <i class="bi bi-heart me-1"></i>Our Mission
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-outline-success position-relative fw-medium" href="cart.php">
                        <i class="bi bi-cart3 me-1"></i>Cart
                        <?php if($cart_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cart_count ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item ms-2 dropdown">
                    <button class="btn btn-sm btn-outline-success dropdown-toggle fw-medium" type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuBtn">
                        <li><a class="dropdown-item" href="#">My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item ms-2">
                    <a class="btn btn-sm btn-outline-success fw-medium me-2" href="login.php?redirect=checkout.php">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    <a class="btn btn-sm btn-success fw-medium" href="register.php?redirect=checkout.php">
                        <i class="bi bi-person-plus me-1"></i>Sign Up
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item ms-2">
                    <a class="btn btn-sm btn-dark fw-medium" href="admin/index.php">
                        <i class="bi bi-person-circle me-1"></i>Farmer Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>