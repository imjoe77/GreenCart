<?php
// Utility functions for the site
if (!function_exists('format_price')) {
    function format_price($amount) {
        // Load config if present
        $symbol = '₹';
        $rate = 1;
        if (file_exists(__DIR__ . '/config.php')) {
            include_once __DIR__ . '/config.php';
            if (isset($currency_symbol)) $symbol = $currency_symbol;
            if (isset($currency_rate)) $rate = $currency_rate;
        }
        $amount = floatval($amount) * floatval($rate);
        return $symbol . number_format($amount, 2);
    }
}
?>
