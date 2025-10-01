<?php
// Include necessary files
require_once('Models/Database.php');

// Get the search term from the AJAX request
$searchTerm = $_GET['term'];

// Initialize the database connection
$dbInstance = Database::getInstance();
$dbHandle = $dbInstance->getdbConnection();

// SQL query to search for suggestions
$sqlQuery = 'SELECT delivery_point.id, full_name, address_1, username, postcode, deliverer, lat, longitude, status_text, photo_name 
              FROM delivery_point
              INNER JOIN delivery_users ON delivery_point.deliverer = delivery_users.userid
              INNER JOIN delivery_status ON delivery_point.status_num = delivery_status.idstatus
              WHERE full_name LIKE :searchTerm
              ORDER BY delivery_point.id
              LIMIT 10';

// Prepare a PDO statement
$statement = $dbHandle->prepare($sqlQuery);

// Sanitize the search term
$searchTerm = '%' . $searchTerm . '%';

// Bind the sanitized search term to the parameter in the SQL query
$statement->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);

// Executes the PDO statement
$statement->execute();

// Fetches data and creates an array of suggestions
$suggestions = [];
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $suggestions[] = $row;
}

// Returns the array of suggestions as JSON
echo json_encode($suggestions);
?>
