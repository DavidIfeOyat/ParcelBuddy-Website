<?php
require_once('Models/DelivererDataSet.php');
require_once('Models/UserCheck.php');

$view = new stdClass();
$view->pageTitle = 'Delivery Users Page';

// Create an instance of UserCheck
$userCheck = new UserCheck();

// Check if the user is not logged in
if (!$userCheck->isLoggedIn()) {
    // Redirect to the index page or login page
    header("Location: index.php");
    exit();
}

// Get the authenticated user's username
$loggedInUsername = $_SESSION['username'];
$loggedInUserId = $_SESSION['user_id'];  // Assuming user ID is stored in 'user_id' session variable

// Pagination settings
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rowsPerPage = 21;

// Create an instance of DelivererDataSet
$delivererDataSet = new DelivererDataSet();

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $view->delivererDataSet = $delivererDataSet->searchDeliveryUserInfo($searchTerm, $loggedInUserId);
} else if (isset($_GET['status'])) {
    $status = (int)$_GET['status'];

    if ($status == 0) {
        $view->delivererDataSet = $delivererDataSet->fetchDeliveryInfoForUsername($loggedInUsername, $page, $rowsPerPage);
        $view->totalRows = $delivererDataSet->getTotalRows();
    } else {
        $view->delivererDataSet = $delivererDataSet->fetchDelivererIDByStatus($status, $page, $rowsPerPage, $loggedInUserId);
        // Get the total number of rows matching the specific status and logged-in user ID
        $view->totalRows = $delivererDataSet->getTotalRowsFiltered($status, $loggedInUserId);  // Assuming this method is implemented to count filtered results
    }
} else {
    $view->delivererDataSet = $delivererDataSet->fetchDeliveryInfoForUsername($loggedInUsername, $page, $rowsPerPage);
    $view->totalRows = $delivererDataSet->getTotalRows();  // Get total rows without any filters
}

$view->totalPages = ceil($view->totalRows / $rowsPerPage);
$view->currentPage = max(1, min($page, $view->totalPages));  // Ensure current page is within range

require_once('Views/deliverer_page.phtml');
?>
