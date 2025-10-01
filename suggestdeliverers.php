<?php
// Include necessary files
require_once('Models/Database.php');

// Get the search term from the AJAX request
$searchTerm = $_GET['term'];

// Initialize the database connection
$dbInstance = Database::getInstance();
$dbHandle = $dbInstance->getdbConnection();

// SQL query to search for suggestions (only for usernames)
$sqlQuery = 'SELECT username FROM delivery_users WHERE username LIKE :searchTerm LIMIT 10';

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
while ($row = $statement->fetch()) {
    $suggestions[] = $row['username'];
}

// Returns the array of suggestions as JSON
echo json_encode($suggestions);
?>
