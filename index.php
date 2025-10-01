<?php
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$view = new stdClass();
$view->pageTitle = 'Homepage';
require_once('Views/index.phtml');

require_once('Models/Database.php');
require_once('Models/UserCheck.php');

$userCheck = new UserCheck();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $_POST["username"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    if (!empty($username) && !empty($password)) {
        $userData = $userCheck->userChecker($username, $password);

        if ($userData !== false) {
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['username'] = $userData['username'];

            header("Location: deliverer_page.php");
            exit();
        } else {
            $errorMessage = "Incorrect username or password!! Please try again.";
        }
    }
}
?>
