<?php
require_once('Models/DelivererDataSet.php');
$view = new stdClass();
$view->pageTitle = 'Add Delivery';

$errors = []; // Array to store error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivererDataSet = new DelivererDataSet();

    // Collect form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $postCode = $_POST['postCode'];

    // Use FILTER_VALIDATE_FLOAT for latitude and longitude
    $latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
    $longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);

    // Check if latitude and longitude are valid floats
    if ($latitude === false || $longitude === false) {
        $errors[] = "Latitude and Longitude must be valid floats.";
    }

    $deliveryDet = $_POST['deliveryDet'];
    $statusText = $_POST['statusText'];

    if (empty($errors)) {
        // No errors, proceed with adding delivery information
        $success = $delivererDataSet->addDeliveryInfo($name, $address, $postCode, $latitude, $longitude, $deliveryDet, $statusText);

        if ($success) {
            // Redirect to manager_page.php upon success
            header("Location: manager_page.php");
            exit(); // this ensures that no further code is executed after the header redirection
        } else {
            $errors[] = "Error adding delivery information.";
        }
    }
}
require_once('Views/add_delivery.phtml');
?>
