<?php
require 'includes/db.php';
session_start();

$error = "";
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

// Show success message from registration if exists
if (isset($_SESSION['success'])) {
    $success_msg = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // #region agent log
    $logDir = __DIR__ . '/.cursor';
    if (!is_dir($logDir)) { @mkdir($logDir, 0777, true); }
    @file_put_contents($logDir . '/debug.log', json_encode(['location' => 'login.php:13', 'message' => 'Login POST received', 'data' => ['hasEmail' => isset($_POST['email']), 'hasPassword' => isset($_POST['password'])], 'timestamp' => time() * 1000, 'sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'C']) . "\n", FILE_APPEND);
    // #endregion
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        // Login Success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        
        // #region agent log
        $logDir = __DIR__ . '/.cursor';
        if (!is_dir($logDir)) { @mkdir($logDir, 0777, true); }
        @file_put_contents($logDir . '/debug.log', json_encode(['location' => 'login.php:25', 'message' => 'Login successful', 'data' => ['userId' => $user['id'], 'role' => $user['role']], 'timestamp' => time() * 1000, 'sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'C']) . "\n", FILE_APPEND);
        // #endregion
        
        // Redirect based on role or requested page
        $post_redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : '';
        if (!empty($post_redirect) && strpos($post_redirect, 'http') === false) {
            header("Location: " . $post_redirect);
            exit();
        }

        if ($user['role'] === 'admin') {
            header("Location: admin/index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        // #region agent log
        $logDir = __DIR__ . '/.cursor';
        if (!is_dir($logDir)) { @mkdir($logDir, 0777, true); }
        @file_put_contents($logDir . '/debug.log', json_encode(['location' => 'login.php:36', 'message' => 'Login failed', 'data' => ['userFound' => !empty($user), 'passwordMatch' => $user ? password_verify($pass, $user['password']) : false], 'timestamp' => time() * 1000, 'sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'C']) . "\n", FILE_APPEND);
        // #endregion
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GreenCart</title>
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

    <!-- Login Section with enhanced styling -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 fade-in">
                    <!-- Card header with icon -->
                    <div class="card-header bg-white text-center border-0 pt-4">
                        <i class="bi bi-box-arrow-in-right text-success" style="font-size: 3rem;"></i>
                        <h3 class="mt-3 mb-0 fw-bold">Welcome Back</h3>
                        <p class="text-muted small mb-0">Sign in to your account</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Success message -->
                        <?php if(isset($success_msg)): ?>
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div><?= $success_msg ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Error message -->
                        <?php if($error): ?>
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= $error ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Login form -->
                        <form method="POST" action="login.php" id="loginForm" onsubmit="console.log('DEBUG: Form submitting'); return true;">
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
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
                                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required id="loginPassword">
                                    <button type="button" class="btn btn-outline-secondary password-toggle" data-controls="loginPassword" aria-label="Toggle password visibility">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </form>
                        
                        <!-- Register link -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted mb-0">
                                New user? 
                                <a href="register.php" class="text-success fw-bold text-decoration-none">
                                    <i class="bi bi-person-plus me-1"></i>Create an account
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