<?php
require_once('Models/UserCheck.php');

$view = new stdClass();
$view->pageTitle = 'Add Deliverer';
$errors = []; // Array to store error messages

$userCheck = new UserCheck(); // Assuming you have an instance of UserCheck

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate that the password is numerical or int
    if (!is_numeric($password)) {
        $errors[] = "Password must be a numerical or integer value.";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Other form data collection and validation can be added here

    if (empty($errors)) {
        // No errors, proceed with adding deliverer information to the database
        $userType = 2; // Assuming 'Deliverer' has a user type of 2

        // Add user to the database using the addUser method
        $success = $userCheck->addUser($username, $hashedPassword, $userType);

        if ($success) {
            // Redirect to a success page upon successful addition
            header("Location: deliverers.php");
            exit(); // this ensures that no further code is executed after the header redirection
        } else {
            $errors[] = "Failed to add user. Please try again.";
        }
    }
}

require_once('Views/add_deliverer.phtml');
?>
