<?php
require_once('Models/DelivererDataSet.php');

// Checks if the request method is GET and the id parameter is set in the query string
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Creates an instance of the DelivererDataSet class
    $delivererDataSet = new DelivererDataSet();

    // Get the delivery ID from the query parameters
    $deliveryId = $_GET['id'];

    // Calls for a method to fetch the delivery information by ID
    $deliveryInfo = $delivererDataSet->getDeliveryInfoById($deliveryId);

    // Check if the delivery information is found
    if ($deliveryInfo) {
        // then render a form for editing with the fetched data
        require('Views/edit_delivery.phtml');
    } else {
        echo "Delivery information not found.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission for updating the delivery information
    $delivererDataSet = new DelivererDataSet();

    // Collect form data
    $deliveryId = $_POST['id'];
    $name = $_POST['full_name'];
    $address = $_POST['address'];
    $postCode = $_POST['postCode'];

    // Use FILTER_VALIDATE_FLOAT for latitude and longitude
    $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
    $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);

    // Check if latitude and longitude are valid floats
    if ($latitude === false || $longitude === false) {
        echo "Latitude and Longitude must be valid floats.";
        exit();
    }

    $deliveryDet = $_POST['deliveryDet'];
    $statusText = $_POST['statusText'];
    $photoID = $_POST['photoID'];

    // Call the updateDeliveryInfo function
    $success = $delivererDataSet->updateDeliveryInfo($deliveryId, $name, $address, $postCode, $latitude, $longitude, $deliveryDet, $statusText, $photoID);

    // Check if the update was successful
    if ($success) {
        // Redirect back to the page displaying delivery information
        header("Location: manager_page.php");
        exit();
    } else {
        echo "Error updating delivery information.";
    }
} else {
    // if the request is invalid, redirect to the manager page
    header("Location: manager_page.php");
    exit();
}
?>
