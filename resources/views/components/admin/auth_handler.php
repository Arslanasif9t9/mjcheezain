<?php
header('Content-Type: application/json');
require_once '../mydatabase/conn.php';

// Initialize response
$response = ['success' => false, 'message' => ''];

try {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'login':
            handleLogin($conn);
            break;
        case 'forgot_password':
            handleForgotPassword($conn);
            break;
        default:
            $response['message'] = 'Invalid action';
            break;
    }
} catch (Exception $e) {
    $response['message'] = 'Server error: ' . $e->getMessage();
}

echo json_encode($response);
$conn->close();
exit;

function handleLogin($conn) {
    global $response;

    $username = $conn->real_escape_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $response['message'] = 'Username is required';
        return;
    }

    if (empty($password)) {
        $response['message'] = 'Password is required';
        return;
    }

    // Fetch user from database
    $sql = "SELECT id, username, password_hash FROM admin_users WHERE username = '$username' OR email = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        $response['message'] = 'Invalid username or password';
        return;
    }

    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password_hash'])) {
        $response['success'] = true;
        $response['message'] = 'Login successful';
        // In a real application, you would start a session here
        session_start();
        $_SESSION['admin_id'] = $user['id'];
        // $_SESSION['username'] = $user['username'];
    } else {
        $response['message'] = 'Invalid username or password';
    }
}

function handleForgotPassword($conn) {
    global $response;

    $email = $conn->real_escape_string($_POST['email'] ?? '');

    if (empty($email)) {
        $response['message'] = 'Email is required';
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format';
        return;
    }

    // Check if email exists
    $sql = "SELECT id FROM admin_users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $sql = "UPDATE admin_users SET reset_token = '$token', token_expires_at = '$expires' WHERE id = {$user['id']}";
        if ($conn->query($sql)) {
            // In a real application, you would send an email here
            // $resetLink = "https://yourdomain.com/reset_password.php?token=$token";
            // sendResetEmail($email, $resetLink);
        } else {
            $response['message'] = 'Failed to generate reset token';
            return;
        }
    }

    // Always return success to prevent email enumeration
    $response['success'] = true;
    $response['message'] = 'If this email is registered, you will receive a password reset link shortly.';
}
?>