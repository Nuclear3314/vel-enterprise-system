<?php
require_once dirname(__DIR__) . '/includes/class-vel-autoloader.php';
VEL_Autoloader::register();

// 測試載入類別
try {
    $core = new VEL\Includes\VEL_Core();
    echo "自動加載器運作正常\n";
} catch (Exception $e) {
    echo "錯誤: " . $e->getMessage() . "\n";
}