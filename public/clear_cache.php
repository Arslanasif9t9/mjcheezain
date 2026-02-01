<?php
// clear_cache.php
echo "Clearing cache...<br>";

// Clear application cache
$cachePath = __DIR__ . '/../storage/framework/cache/data/';
$viewsPath = __DIR__ . '/../storage/framework/views/';

if (is_dir($cachePath)) {
    $files = glob($cachePath . '*');
    foreach ($files as $file) {
        if (is_file($file)) unlink($file);
    }
    echo "Cache cleared<br>";
}

if (is_dir($viewsPath)) {
    $files = glob($viewsPath . '*.php');
    foreach ($files as $file) {
        if (is_file($file)) unlink($file);
    }
    echo "Views cleared<br>";
}

echo "Done!";
?>