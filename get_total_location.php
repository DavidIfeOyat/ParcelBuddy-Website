<?php
// Include necessary classes for database interaction and user checks
require_once('Models/Database.php');
require_once('Models/UserCheck.php');

// Instantiate UserCheck to utilize its methods for user session checks
$userCheck = new UserCheck();

// Verify if the user is currently logged in
if (!$userCheck->isLoggedIn()) {
    // If user is not logged in, redirect to the login page
    header("Location: index.php"); // Ensure the path is correctly specified
    exit(); // Terminate script execution after redirection
}

// Get a singleton instance of Database class
$dbInstance = Database::getInstance();

// Obtain a database connection from the instance
$dbHandle = $dbInstance->getdbConnection();

// SQL query to select data from multiple tables via JOINs
$query = 'SELECT dp.lat, dp.longitude, dp.full_name as name, dp.address_1, dp.postcode, ds.status_text, du.username 
          FROM delivery_point dp 
          JOIN delivery_status ds ON dp.status_num = ds.idstatus 
          JOIN delivery_users du ON dp.deliverer = du.userid
          WHERE dp.status_num = 10'; // This line filters deliveries with status code 10 (That is Delivered parcels in the delivery_status Table)

// Prepare the SQL statement for execution to prevent SQL injection
$statement = $dbHandle->prepare($query);

// Execute the prepared statement
$statement->execute();

// Fetch all the resulting rows as an associative array
$deliveryLocations = $statement->fetchAll(PDO::FETCH_ASSOC);

// Clean up the database connection by closing it
$dbHandle = null;

// Initialize an array to hold formatted location data
$formattedLocations = [];

// Process each delivery location to reformat data
foreach ($deliveryLocations as $location) {
    $formattedLocations[] = [
        'lat' => $location['lat'], // Latitude
        'lng' => $location['longitude'], // Longitude
        'name' => $location['name'], // Full name of the delivery point
        'address_1' => $location['address_1'], // Address
        'postcode' => $location['postcode'], // Post code
        'status_text' => $location['status_text'], // status of the delivery
        'username' => $location['username'] // Username of the deliverer
    ];
}

// Encode the array of formatted data into JSON format for web applications
echo json_encode($formattedLocations);
?>
