<?php
// Our Mission page - Explains GreenCart's mission and values
require 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Mission | GreenCart</title>
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
    <?php include 'includes/navbar.php'; ?>

    <!-- Mission Hero Section -->
    <header class="bg-success text-white text-center py-5 fade-in">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-heart-fill text-warning"></i> Our Mission
            </h1>
            <p class="lead">Reducing food waste, one vegetable at a time</p>
        </div>
    </header>

    <!-- Mission Content -->
    <div class="container py-5">
        <!-- Main Mission Statement -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <div class="card shadow-lg border-0 p-5 mb-4">
                    <i class="bi bi-bullseye text-success" style="font-size: 4rem;"></i>
                    <h2 class="mt-4 mb-3">What We Stand For</h2>
                    <p class="lead text-muted">
                        At GreenCart, we believe that "ugly" produce deserves a second chance. 
                        Our mission is to rescue perfectly fresh fruits and vegetables that would 
                        otherwise go to waste, making them available to you at affordable prices 
                        while helping the environment.
                    </p>
                </div>
            </div>
        </div>

        <!-- Mission Values -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 text-center p-4">
                    <i class="bi bi-recycle text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mb-3">Reduce Waste</h4>
                    <p class="text-muted">
                        We rescue surplus and "ugly" produce that doesn't meet 
                        supermarket beauty standards but is perfectly fresh and nutritious.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 text-center p-4">
                    <i class="bi bi-piggy-bank text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mb-3">Save Money</h4>
                    <p class="text-muted">
                        By purchasing directly from farmers and reducing waste, 
                        we pass the savings to you - up to 40% off regular prices.
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 text-center p-4">
                    <i class="bi bi-globe text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mb-3">Protect Earth</h4>
                    <p class="text-muted">
                        Every purchase helps reduce food waste, lower carbon footprint, 
                        and support sustainable farming practices.
                    </p>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0 p-5">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-gear-fill text-success me-2"></i>How It Works
                    </h2>
                    <div class="row g-4">
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <span class="badge bg-success rounded-circle p-3" style="font-size: 1.5rem; width: 60px; height: 60px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                            </div>
                            <h5 class="fw-bold">Farmers Partner</h5>
                            <p class="text-muted small">Local farmers provide surplus produce</p>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <span class="badge bg-success rounded-circle p-3" style="font-size: 1.5rem; width: 60px; height: 60px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                            </div>
                            <h5 class="fw-bold">We Rescue</h5>
                            <p class="text-muted small">We collect and sort fresh produce</p>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <span class="badge bg-success rounded-circle p-3" style="font-size: 1.5rem; width: 60px; height: 60px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                            </div>
                            <h5 class="fw-bold">You Save</h5>
                            <p class="text-muted small">You get fresh produce at great prices</p>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <span class="badge bg-success rounded-circle p-3" style="font-size: 1.5rem; width: 60px; height: 60px; display: inline-flex; align-items: center; justify-content: center;">4</span>
                            </div>
                            <h5 class="fw-bold">Earth Wins</h5>
                            <p class="text-muted small">Together we reduce food waste</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Statistics -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0 p-5 bg-success text-white">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-graph-up-arrow me-2"></i>Our Impact
                    </h2>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h3 class="display-4 fw-bold">1000+</h3>
                            <p class="lead">Pounds of Produce Rescued</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="display-4 fw-bold">500+</h3>
                            <p class="lead">Happy Customers</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="display-4 fw-bold">40%</h3>
                            <p class="lead">Average Savings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row">
            <div class="col-12 text-center">
                <div class="card shadow-lg border-0 p-5">
                    <h3 class="mb-4">Join Us in Making a Difference</h3>
                    <p class="lead text-muted mb-4">
                        Start shopping and help reduce food waste while saving money!
                    </p>
                    <a href="index.php" class="btn btn-success btn-lg">
                        <i class="bi bi-basket3 me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>

