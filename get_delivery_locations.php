<?php
// Include necessary files
require_once('Models/Database.php');
require_once('Models/UserCheck.php');

// Create an instance of UserCheck
$userCheck = new UserCheck();

// Check if the user is not logged in
if (!$userCheck->isLoggedIn()) {
    // Redirect to the index page or login page
    header("Location: index.php"); // Replace with the actual path to your index page
    exit();
}

// Get the authenticated user's user ID
$loggedInUserId = $_SESSION['user_id'];

// Create an instance of Database
$dbInstance = Database::getInstance();
$dbHandle = $dbInstance->getdbConnection();

// Prepare and execute the query with the user ID condition
$query = 'SELECT dp.lat, dp.longitude, dp.full_name as name, dp.address_1, dp.postcode, ds.status_text, du.username 
          FROM delivery_point dp 
          JOIN delivery_status ds ON dp.status_num = ds.idstatus 
          JOIN delivery_users du ON dp.deliverer = du.userid
          WHERE dp.status_num IN (8, 9, 10) AND dp.deliverer = :userId'; // This line filters deliveries with status code 8, 9, 10 (That is Delivered, Out for Delivery and local courier received parcels in the delivery_status Table)

$statement = $dbHandle->prepare($query);
$statement->bindParam(':userId', $loggedInUserId, PDO::PARAM_INT);
$statement->execute();

// Fetch the data
$deliveryLocations = $statement->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
$dbHandle = null;

// Format the data into an array of objects
$formattedLocations = [];
foreach ($deliveryLocations as $location) {
    $formattedLocations[] = [
        'lat' => $location['lat'],
        'lng' => $location['longitude'],
        'name' => $location['name'],
        'address_1' => $location['address_1'],
        'postcode' => $location['postcode'],
        'status_text' => $location['status_text'],
        'username' => $location['username']
    ];
}

// Encode the formatted data into JSON format
echo json_encode($formattedLocations);
?>
