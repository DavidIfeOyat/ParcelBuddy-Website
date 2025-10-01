<?php
require_once('Models/UserCheck.php');

$view = new stdClass();
$view->pageTitle = 'Student Information System';

$UserCheck = new UserCheck();

// Check if a search query is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    // Use the search query to fetch filtered user data
    $searchQuery = $_POST['search'];
    $view->UserCheck = $UserCheck->searchUsers($searchQuery);
} else {
    // Fetch all users if no search query
    $view->UserCheck = $UserCheck->fetchAllUsers();
}

require_once('Views/deliverers.phtml');
