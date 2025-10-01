<?php
require_once('Models/DelivererDataSet.php');
$view = new stdClass();
$view->pageTitle = 'Manager Page';

// Set the default values for pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rowsPerPage = 51; // Default number of rows per page

// Retrieve the sort order from the query string or set default as 'asc'
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc';

// Check if the search form has been submitted
if (isset($_POST['search'])) {
    // Retrieve the search term from the form
    $searchTerm = $_POST['search'];

    // Create a new instance of the DelivererDataSet class
    $delivererDataSet = new DelivererDataSet();
    // Perform a search using the provided term
    $view->delivererDataSet = $delivererDataSet->searchDeliveryInfo($searchTerm, $page, $rowsPerPage, $sortOrder);
} else {
    // Creates a new instance of the DelivererDataSet class
    $delivererDataSet = new DelivererDataSet();

    // If the search term is not set and no status filter is selected, fetch all delivery information with pagination
    if (!isset($_GET['status']) || empty($_GET['status'])) {
        $view->delivererDataSet = $delivererDataSet->fetchDeliveryInfo($page, $rowsPerPage, $sortOrder);
    } else {
        // Fetch delivery information filtered by status
        $status = (int)$_GET['status'];
        $view->delivererDataSet = $delivererDataSet->fetchDeliveryInfoByStatus($status, $page, $rowsPerPage, $sortOrder);
    }
}

// Get the total number of rows for pagination
$view->totalRows = $delivererDataSet->getTotalRows();

// Calculate total pages
$view->totalPages = ceil($view->totalRows / $rowsPerPage);

// Pass the current page to the view
$view->currentPage = $page;

require_once('Views/manager_page.phtml');
?>
