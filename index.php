<?php
require 'includes/db.php';
// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenCart | Rescue Fresh Food</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons for better visual elements -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts - Poppins for headings, Inter for body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section with enhanced styling -->
    <header class="bg-success text-white text-center py-5 fade-in">
        <div class="container position-relative">
            <!-- Decorative icons -->
            <i class="bi bi-flower1" style="position: absolute; top: 20px; left: 10%; font-size: 3rem; opacity: 0.2;"></i>
            <i class="bi bi-flower2" style="position: absolute; top: 20px; right: 10%; font-size: 3rem; opacity: 0.2;"></i>
            
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-heart-fill text-warning"></i> Rescue "Ugly" Produce
            </h1>
            <p class="lead mb-4">Save up to 40% on perfectly fresh surplus fruits & veggies.</p>
            
            <!-- Feature highlights -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-truck fs-1 mb-2"></i>
                        <small>Fast Delivery</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-shield-check fs-1 mb-2"></i>
                        <small>Fresh Guaranteed</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-recycle fs-1 mb-2"></i>
                        <small>Eco-Friendly</small>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-piggy-bank fs-1 mb-2"></i>
                        <small>Save Money</small>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container py-5">
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'added'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Item added to cart successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Section heading with icon -->
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-basket3-fill text-success me-2" style="font-size: 1.75rem;"></i>
            <h3 class="mb-0 text-secondary border-bottom pb-2 flex-grow-1">Fresh Arrivals</h3>
        </div>
        
        <!-- Product grid with enhanced cards -->
        <div class="row g-4">
            <?php foreach($products as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 product-card fade-in">
                    <!-- Surplus badge with icon -->
                    <?php if($product['is_surplus']): ?>
                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 d-flex align-items-center gap-1">
                            <i class="bi bi-star-fill"></i> Surplus Deal
                        </span>
                    <?php endif; ?>
                    
                    <!-- Product image -->
                    <?php $img = $product['image_url'] ? "uploads/products/" . $product['image_url'] : "https://dummyimage.com/600x400/198754/fff&text=Fresh+Produce"; ?>
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        <img src="<?= htmlspecialchars($img) ?>" class="card-img-top w-100 h-100" alt="<?= htmlspecialchars($product['name']) ?>" style="object-fit: cover;">
                    </div>
                    
                    <!-- Card body with enhanced styling -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-apple text-success me-1"></i>
                            <?= htmlspecialchars($product['name']) ?>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1" style="min-height: 40px;">
                            <?= htmlspecialchars(substr($product['description'], 0, 60)) ?>...
                        </p>
                        
                        <!-- Price and add to cart section -->
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <div>
                                <span class="h5 text-success mb-0 fw-bold">
                                    <i class="bi bi-currency-dollar"></i><?= number_format($product['price'], 2) ?>
                                </span>
                                <?php if($product['is_surplus']): ?>
                                    <small class="d-block text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-tag-fill text-warning"></i> Best Value
                                    </small>
                                <?php endif; ?>
                            </div>
                            <form action="cart_action.php" method="POST" class="mb-0">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-cart-plus"></i> Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript for enhanced interactivity -->
    <script src="assets/js/main.js"></script>
</body>
</html>