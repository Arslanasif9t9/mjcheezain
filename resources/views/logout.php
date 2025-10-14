<?php
session_start();

// Debugging - check if we received the POST data
error_log("Logout request received. POST data: " . print_r($_POST, true));

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Clear session data
    $_SESSION = array();
    
    // If it's desired to kill the session, also delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    
    // Verify session is destroyed
    error_log("Session after destruction: " . print_r($_SESSION, true));
    
    // Redirect to login page (ensure this is the correct path)
    header("Location: ./index.php");
    exit();
// } else {
    // If someone accesses this page directly without POST data
    // header("Location: ./index.php");
    // exit();
// }
?>
<?php
// session_start();
echo "<pre>Session data: ";
print_r($_SESSION);
echo "</pre>";
?>