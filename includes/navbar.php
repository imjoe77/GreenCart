<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Calculate cart count for badge display
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!-- Enhanced Navigation Bar with modern styling -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <!-- Brand logo with icon -->
        <a class="navbar-brand text-success fw-bold d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../index.php' : 'index.php'; ?>">
            <i class="bi bi-basket3-fill me-2" style="font-size: 1.75rem;"></i>
            <span>GreenCart</span>
            <span class="ms-1" style="font-size: 1.25rem;">🌿</span>
        </a>
        
        <!-- Mobile menu toggle button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Shop link -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../index.php' : 'index.php'; ?>">
                        <i class="bi bi-shop me-1"></i> Shop Surplus
                    </a>
                </li>
                
                <!-- Mission link -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../mission.php' : 'mission.php'; ?>">
                        <i class="bi bi-heart me-1"></i> Our Mission
                    </a>
                </li>
                
                <!-- Cart button with badge -->
                <li class="nav-item ms-2">
                    <a class="btn btn-outline-success position-relative d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../cart.php' : 'cart.php'; ?>">
                        <i class="bi bi-cart3 me-1"></i> Cart
                        <?php if($cart_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cart_count ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- User menu (if logged in) -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown ms-3">
                        <a class="nav-link dropdown-toggle btn btn-light border d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <span>Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                            <?php if($_SESSION['user_role'] == 'admin'): ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? 'index.php' : 'admin/index.php'; ?>">
                                        <i class="bi bi-speedometer2 me-2"></i> Farmer Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item text-danger d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../logout.php' : 'logout.php'; ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Login and Sign Up buttons (if not logged in) -->
                    <li class="nav-item ms-3 d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../login.php' : 'login.php'; ?>">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                        <a class="btn btn-sm btn-success d-flex align-items-center" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../register.php' : 'register.php'; ?>">
                            <i class="bi bi-person-plus me-1"></i> Sign Up
                        </a>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
</nav>