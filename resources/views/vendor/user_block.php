<?php
$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT status FROM users WHERE user_id = $user_id");
$arr = $result->fetch_assoc();
$status = $arr['status'];
if ($status == "blocked") {
  echo "User has been blocked from cheezain.";
  exit();
}
?>