<?php
// Include necessary model files for database operations
require_once('Models/Database.php');
require_once('Models/UserInfo.php');
require_once('Models/UserCheck.php');

// Check if the form has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data from the POST request
    $editUserName = $_POST['editUserName']; // User's new or edited username
    $editPassword = password_hash($_POST['editPassword'], PASSWORD_DEFAULT); // Hash the new or edited password for security
    $editUserType = $_POST['editUserType']; // User's type or role (e.g., admin, user)
    $editUserID = $_POST['editUserID']; // The unique ID of the user to be edited

    // Instantiate the UserCheck class to access its methods for user operations
    $userCheck = new UserCheck();

    // Call the updateUserInfo method to update the user's details in the database
    $userCheck->updateUserInfo($editUserID, $editUserName, $editPassword, $editUserType);

    // Redirect the user back to the 'deliverers.php' page after successful update
    // This prevents the form from being resubmitted on refresh and helps user see the updated result
    header("Location: deliverers.php");
    exit(); // Ensure that no further script execution happens after the redirect
}
