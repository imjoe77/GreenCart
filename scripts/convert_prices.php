<?php
// Simple script to convert product prices by a multiplier (e.g., USD -> INR)
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rate = floatval($_POST['rate']);
    if ($rate <= 0) {
        $error = 'Please provide a valid conversion rate.';
    } else {
        $stmt = $pdo->query("SELECT id, price FROM products");
        $products = $stmt->fetchAll();
        $updated = 0;
        foreach ($products as $p) {
            $newPrice = round($p['price'] * $rate, 2);
            $u = $pdo->prepare("UPDATE products SET price = ? WHERE id = ?");
            if ($u->execute([$newPrice, $p['id']])) $updated++;
        }
        $message = "Updated prices for $updated products by a rate of $rate.";
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Convert Product Prices</title></head>
<body>
    <h2>Convert product prices (dangerous: make a DB backup first)</h2>
    <?php if (!empty($error)): ?><div style="color:red"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($message)): ?><div style="color:green"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <form method="post">
        <label>Conversion rate (multiply current prices by):</label>
        <input type="text" name="rate" value="83">
        <button type="submit">Convert</button>
    </form>
    <p><em>Make a DB backup before running this.</em></p>
</body>
</html>