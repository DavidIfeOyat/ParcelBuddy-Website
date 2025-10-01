<?php
require_once('Models/DelivererDataSet.php');

// this checks if the request method is GET and the 'id' parameter is set in the query string
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Creates an instance of the DelivererDataSet class
    $delivererDataSet = new DelivererDataSet();

    // Get the delivery ID from the query parameters
    $deliveryId = $_GET['id'];

    // Calls for a method to delete the delivery information
    $success = $delivererDataSet->deleteDeliveryInfo($deliveryId);

    // Checks if the deletion was successful
    if ($success) {
        // Redirects back to the Manager main page to displaying delivery information
        header("Location: manager_page.php");
        exit();
    } else {
        echo "Error deleting delivery information.";
    }
} else {
    // if the request is invalid, redirect to the main page
    header("Location: manager_page.php");
    exit();
}

