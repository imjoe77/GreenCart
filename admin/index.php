<?php
// Start session and check admin access
session_start();
require '../includes/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Simple Image Upload Logic
    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/products/";
        // Ensure directory exists
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
    }

    $sql = "INSERT INTO products (name, description, price, stock, image_url, is_surplus) VALUES (?, ?, ?, ?, ?, 1)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$name, $desc, $price, $stock, $image_name])) {
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard | GreenCart</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    
    <!-- Enhanced Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="../index.php">
                <i class="bi bi-speedometer2 me-2" style="font-size: 1.5rem;"></i>
                Farmer Dashboard
            </a>
            <div class="d-flex gap-2">
                <a href="add_product.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Product
                </a>
                <a href="orders.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-cart-check me-1"></i>View Orders
                </a>
                <a href="../index.php" class="btn btn-light btn-sm">
                    <i class="bi bi-shop me-1"></i>View Store
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <!-- Success/Error Messages -->
                <?php if($message): ?>
                    <div class="alert alert-success d-flex align-items-center fade-in" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= $message ?></div>
                    </div>
                <?php endif; ?>

                <!-- Add Product Card -->
                <div class="card shadow-lg border-0 fade-in">
                    <div class="card-header bg-white border-bottom">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-plus-circle-fill text-success me-2"></i>
                            <span>Add Surplus Harvest</span>
                        </h4>
                        <small class="text-muted">List your fresh produce to help reduce food waste</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data" id="addProductForm">
                            <!-- Product Name -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-tag me-1 text-success"></i>Product Name
                                </label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Misfit Carrots (5kg)">
                                <small class="text-muted">Give your product a clear, descriptive name</small>
                            </div>
                            
                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-file-text me-1 text-success"></i>Description
                                </label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Describe the item, its condition, and why it's surplus..."></textarea>
                                <small class="text-muted">Help customers understand what they're buying</small>
                            </div>

                            <!-- Price and Stock Row -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-currency-dollar me-1 text-success"></i>Price ($)
                                    </label>
                                    <input type="number" step="0.01" min="0" name="price" class="form-control" required placeholder="0.00">
                                    <small class="text-muted">Set a competitive price</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-box-seam me-1 text-success"></i>Stock Quantity
                                    </label>
                                    <input type="number" min="1" name="stock" class="form-control" required placeholder="0">
                                    <small class="text-muted">How many units available?</small>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-image me-1 text-success"></i>Product Image
                                </label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Upload a clear image of your product (optional but recommended)</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Publish Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="../assets/js/main.js"></script>
</body>
</html>