<?php
require 'includes/db.php';
session_start();

$message = "";
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    // Basic Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (strlen($pass) < 6) {
        $message = "Password must be at least 6 characters.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $message = "Email already registered.";
        } else {
            // Hash password and Insert
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'customer')";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$name, $email, $hashed_pass])) {
                $_SESSION['success'] = "Account created! Please login.";
                $loginRedirect = 'login.php';
                if (!empty($redirect) && strpos($redirect, 'http') === false) {
                    $loginRedirect .= '?redirect=' . urlencode($redirect);
                }
                header("Location: " . $loginRedirect);
                exit();
            } else {
                $message = "Error creating account.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | GreenCart</title>
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

    <!-- Registration Section with enhanced styling -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 fade-in">
                    <!-- Card header with icon -->
                    <div class="card-header bg-white text-center border-0 pt-4">
                        <i class="bi bi-person-plus-fill text-success" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-0 fw-bold">Create Account</h3>
                        <p class="text-muted small mb-0">Join us in reducing food waste</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Error message -->
                        <?php if($message): ?>
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= $message ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Registration form -->
                        <form method="POST" action="register.php" id="registerForm" onsubmit="console.log('DEBUG: Form submitting'); return true;">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person me-1 text-success"></i>Full Name
                                </label>
                                <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-envelope me-1 text-success"></i>Email Address
                                </label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-lock me-1 text-success"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required id="registerPassword">
                                    <button type="button" class="btn btn-outline-secondary password-toggle" data-controls="registerPassword" aria-label="Toggle password visibility">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Password must be at least 6 characters long
                                </small>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Sign Up
                            </button>
                        </form>
                        
                        <!-- Login link -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted mb-0">
                                Already have an account? 
                                <a href="login.php" class="text-success fw-bold text-decoration-none">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Login here
                                </a>
                            </p>
                        </div>
                    </div>
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