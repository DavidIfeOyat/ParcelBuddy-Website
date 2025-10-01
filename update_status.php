<?php
// Include the DelivererDataSet model for accessing delivery data operations
require_once('Models/DelivererDataSet.php');

// Checks if the request method is GET and if there is an 'id' parameter in the query string
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Create an instance of the DelivererDataSet class to utilize its methods
    $delivererDataSet = new DelivererDataSet();

    // Retrieve the delivery ID from the query parameters
    $deliveryId = $_GET['id'];

    // Fetch delivery information using the delivery ID
    $deliveryInfo = $delivererDataSet->getDeliveryInfoById($deliveryId);

    // Check if the delivery information is successfully retrieved
    if ($deliveryInfo) {
        // If data is found, load the update status view which contains the form for editing
        require('Views/update_status.phtml');
    } else {
        // If no data is found, output an error message
        echo "Delivery information not found.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // This block handles POST requests, typically form submissions for updating delivery information
    $delivererDataSet = new DelivererDataSet();

    // Collect form data from POST request
    $deliveryId = $_POST['id'];
    $statusText = $_POST['statusText'];

    // Attempt to update the delivery status using the provided form data
    $success = $delivererDataSet->updateDeliveryStatus($deliveryId, $statusText);

    // Check if the update operation was successful
    if ($success) {
        // If successful, redirect to the deliverer page to view the updated delivery status
        header("Location: deliverer_page.php");
        exit();
    } else {
        // If the update fails, output an error message
        echo "Error updating delivery information.";
    }
} else {
    // If neither GET nor POST, or if the GET request does not include an 'id', redirect to the manager page
    header("Location: deliverer_page.php");
    exit();
}
?>
