<?php
require 'includes/db.php';
require 'includes/config.php';
require 'includes/functions.php';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">

    <?php include 'includes/navbar.php'; ?>

    <header class="bg-success text-white text-center py-5">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Shop Green. Live Clean</h1>
            <p class="lead fs-5" style="color: white;">Save up to 40% on perfectly fresh surplus fruits & veggies.</p>
        </div>
    </header>

    <div class="container py-5">
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'added'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                Item added to cart successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Search and Filter Section -->
        <div class="filter-sort-container">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search me-1 text-success"></i>Search Products
                    </label>
                    <div class="search-container">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by name or description...">
                        <i class="bi bi-search search-icon"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-funnel me-1 text-success"></i>Filter
                    </label>
                    <select id="filterSelect" class="form-select">
                        <option value="all">All Products</option>
                        <option value="surplus">Surplus Deals Only</option>
                        <option value="regular">Regular Products</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-sort-down me-1 text-success"></i>Sort By
                    </label>
                    <select id="sortSelect" class="form-select">
                        <option value="newest">Newest First</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="name">Name: A to Z</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 fw-bold">Fresh Arrivals</h3>
            <span id="productCount" class="text-muted"></span>
        </div>
        
        <div id="productsContainer" class="row g-4">
            <?php foreach($products as $product): ?>
            <div class="col-md-6 col-lg-3 product-item" 
                 data-name="<?= strtolower(htmlspecialchars($product['name'])) ?>"
                 data-description="<?= strtolower(htmlspecialchars($product['description'])) ?>"
                 data-price="<?= $product['price'] ?>"
                 data-surplus="<?= $product['is_surplus'] ? 'true' : 'false' ?>"
                 data-id="<?= $product['id'] ?>">
                <div class="card h-100 shadow-sm border-0 product-card">
                    <?php if($product['is_surplus']): ?>
                        <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                            <i class="bi bi-tag-fill me-1"></i>Surplus Deal
                        </span>
                    <?php endif; ?>
                    
                    <?php $img = $product['image_url'] ? "uploads/products/" . $product['image_url'] : "https://dummyimage.com/600x400/198754/fff&text=Fresh+Produce"; ?>
                    <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 220px; object-fit: cover;">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-semibold"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= htmlspecialchars(substr($product['description'], 0, 70)) ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
                            <span class="h5 text-success mb-0 fw-bold"><?= format_price($product['price']) ?></span>
                            <form action="cart_action.php" method="POST" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="action" value="add">
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-cart-plus me-1"></i>Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div id="noResults" class="text-center py-5" style="display: none;">
            <i class="bi bi-search" style="font-size: 4rem; color: var(--text-muted);"></i>
            <h4 class="mt-3 text-muted">No products found</h4>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
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