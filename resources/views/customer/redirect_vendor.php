<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
}
elseif (isset($_SESSION['type']) && $_SESSION['type'] == "vendor") {
    header("Location: ../vendor/dashboard.php");
}
?>