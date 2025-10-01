<?php
require_once('Models/UserCheck.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userID = $_GET['id'];

    $userCheck = new UserCheck();

    // Delete user information
    $deleted = $userCheck->deleteUserInfo($userID);

    if ($deleted) {
        // Redirect to the deliverer page on success
        header("Location: deliverers.php");
        exit();
    } else {
        // Handle deletion failure (optional)
        echo "Failed to delete user.";
    }
} else {
    // Handle invalid request (optional)
    echo "Invalid request.";
}
?>
