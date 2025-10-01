<?php
// Include necessary files
require_once('Models/Database.php');
require_once('Models/DelivererInfo.php');

class DelivererDataSet {
    protected $_dbHandle, $_dbInstance;

    // Constructor to initialize the database connection
    public function __construct() {
        // Initialize the database connection
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Fetch all delivery information from the database
     * @return array
     */

    /**
     * Fetch all delivery information from the database with pagination and sorting.
     * @param int $page Starting page number for result set
     * @param int $rowsPerPage Number of records per page
     * @param string $sortOrder Sort order ('asc' or 'desc')
     * @return array Array of DelivererInfo objects containing delivery information
     */
    public function fetchDeliveryInfo($page = 1, $rowsPerPage = 51, $sortOrder = 'asc') {
        $offset = ($page - 1) * $rowsPerPage;  // Calculate offset for SQL query

        // Determine sort order for SQL query
        $orderClause = $sortOrder === 'desc' ? 'DESC' : 'ASC';

        // SQL query to fetch delivery info with dynamic ordering and pagination
        $sqlQuery = 'SELECT delivery_point.id, full_name, address_1, username, postcode, deliverer, lat, longitude, status_text, photo_name 
                 FROM delivery_point, delivery_users, delivery_status 
                 WHERE (delivery_point.deliverer = delivery_users.userid AND delivery_point.status_num = delivery_status.idstatus) 
                 ORDER BY delivery_point.id ' . $orderClause . ' 
                 LIMIT :offset, :rowsPerPage';

        $statement = $this->_dbHandle->prepare($sqlQuery);  // Prepare SQL statement
        // Bind parameters for pagination
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->bindParam(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
        $statement->execute();  // Execute the SQL query

        $dataSet = [];  // Initialize an empty array to hold data
        // Fetch data row by row and instantiate DelivererInfo objects
        while ($row = $statement->fetch()) {
            $dataSet[] = new DelivererInfo($row);
        }
        return $dataSet;  // Return the array of DelivererInfo objects
    }

    /**
     * Search for delivery information based on a search term
     * @param string $searchTerm
     * @return array
     */
    public function searchDeliveryInfo(string $searchTerm): array
    {
        // SQL query to search for delivery information based on various fields
        $sqlQuery = 'SELECT dp.id, dp.full_name, dp.address_1, du.username, dp.postcode, dp.deliverer, dp.lat, dp.longitude, ds.status_text, dp.photo_name 
            FROM delivery_point dp
            INNER JOIN delivery_users du ON dp.deliverer = du.userid
            INNER JOIN delivery_status ds ON dp.status_num = ds.idstatus
            WHERE 
                dp.id LIKE :searchTerm OR
                dp.full_name LIKE :searchTerm OR
                dp.address_1 LIKE :searchTerm OR
                du.username LIKE :searchTerm OR
                dp.postcode LIKE :searchTerm OR
                dp.deliverer LIKE :searchTerm OR
                dp.lat LIKE :searchTerm OR
                dp.longitude LIKE :searchTerm OR
                ds.status_text LIKE :searchTerm OR
                dp.photo_name LIKE :searchTerm
            ORDER BY dp.id';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Sanitize the search term
        $searchTerm = '%' . $searchTerm . '%';

        // Bind the sanitized search term to the parameter in the SQL query
        $statement->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);

        // Executes the PDO statement
        $statement->execute();

        // Initializes an empty array to store the fetched data
        $dataSet = [];

        // Fetches data and creates DelivererInfo objects
        while ($row = $statement->fetch()) {
            $dataSet[] = new DelivererInfo($row);
        }

        // Returns the array of DelivererInfo objects
        return $dataSet;
    }

    /**
     * Search for delivery information based on a search term
     * @param string $searchTerm
     * @return array
     */
    public function searchDeliveryUserInfo(string $searchTerm, $loggedInUserId): array
    {
        // SQL query to search for delivery information based on various fields and user ID
        $sqlQuery = 'SELECT dp.id, dp.full_name, dp.address_1, du.username, dp.postcode, dp.deliverer, dp.lat, dp.longitude, ds.status_text, dp.photo_name 
        FROM delivery_point dp
        INNER JOIN delivery_users du ON dp.deliverer = du.userid
        INNER JOIN delivery_status ds ON dp.status_num = ds.idstatus
        WHERE 
            (du.userid = :loggedInUserId) AND
            (dp.id LIKE :searchTerm OR
            dp.full_name LIKE :searchTerm OR
            dp.address_1 LIKE :searchTerm OR
            du.username LIKE :searchTerm OR
            dp.postcode LIKE :searchTerm OR
            dp.deliverer LIKE :searchTerm OR
            dp.lat LIKE :searchTerm OR
            dp.longitude LIKE :searchTerm OR
            ds.status_text LIKE :searchTerm OR
            dp.photo_name LIKE :searchTerm)
        ORDER BY dp.id';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Sanitize the search term
        $searchTerm = '%' . $searchTerm . '%';

        // Bind the parameters in the SQL query
        $statement->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $statement->bindValue(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);

        // Execute the PDO statement
        $statement->execute();

        // Initialize an empty array to store the fetched data
        $dataSet = [];

        // Fetch data and create DelivererInfo objects
        while ($row = $statement->fetch()) {
            $dataSet[] = new DelivererInfo($row);
        }

        // Return the array of DelivererInfo objects
        return $dataSet;
    }

    /**
     * Add new delivery information to the database
     * @param $name
     * @param $address
     * @param $postCode
     * @param $latitude
     * @param $longitude
     * @param $deliveryDet
     * @param $statusText
     * @param $photoID
     * @return bool
     */
    public function addDeliveryInfo($name, $address, $postCode, $latitude, $longitude, $deliveryDet, $statusText) {

        // SQL query to insert new delivery information
        $sqlQuery = 'INSERT INTO delivery_point (full_name, address_1, postcode, lat, longitude, deliverer, status_num) 
                 VALUES (:name, :address, :postCode, :latitude, :longitude, :deliveryDet, :statusText)';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind values to parameters
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':address', $address, PDO::PARAM_STR);
        $statement->bindValue(':postCode', $postCode, PDO::PARAM_STR);
        $statement->bindValue(':latitude', $latitude, PDO::PARAM_STR);
        $statement->bindValue(':longitude', $longitude, PDO::PARAM_STR);
        $statement->bindValue(':deliveryDet', $deliveryDet, PDO::PARAM_STR);
        $statement->bindValue(':statusText', $statusText, PDO::PARAM_STR);

        // Execute the PDO statement and return the result
        return $statement->execute();
    }

    /**
     * Delete delivery information from the database
     * @param $deliveryId
     * @return bool
     */
    public function deleteDeliveryInfo($deliveryId) {
        // SQL query to delete delivery information by ID
        $sqlQuery = 'DELETE FROM delivery_point WHERE id = :deliveryId';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':deliveryId', $deliveryId, PDO::PARAM_INT);

        // Execute the PDO statement and return the result
        return $statement->execute();
    }

    /**
     * Get delivery information by ID
     * @param $deliveryId
     * @return mixed
     */
    public function getDeliveryInfoById($deliveryId) {
        // SQL query to retrieve delivery information by ID
        $sqlQuery = 'SELECT * FROM delivery_point WHERE id = :deliveryId';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':deliveryId', $deliveryId, PDO::PARAM_INT);

        // Execute the PDO statement
        $statement->execute();

        // Return the fetched data as an associative array
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update delivery information in the database
     * @param $id
     * @param $name
     * @param $address
     * @param $postCode
     * @param $latitude
     * @param $longitude
     * @param $deliveryDet
     * @param $statusText
     * @param $photoID
     * @return bool
     */
    public function updateDeliveryInfo($id, $name, $address, $postCode, $latitude, $longitude, $deliveryDet, $statusText, $photoID) {

        // SQL query to update delivery information by ID
        $sqlQuery = 'UPDATE delivery_point 
                 SET full_name = :name, 
                     address_1 = :address, 
                     postcode = :postCode, 
                     lat = :latitude, 
                     longitude = :longitude, 
                     deliverer = :deliveryDet, 
                     status_num = :statusText, 
                     photo_name = :photoID 
                 WHERE id = :id';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':address', $address, PDO::PARAM_STR);
        $statement->bindValue(':postCode', $postCode, PDO::PARAM_STR);
        $statement->bindValue(':latitude', $latitude, PDO::PARAM_STR); // Change PDO::PARAM_STR to PDO::PARAM_INT
        $statement->bindValue(':longitude', $longitude, PDO::PARAM_STR); // Change PDO::PARAM_STR to PDO::PARAM_INT
        $statement->bindValue(':deliveryDet', $deliveryDet, PDO::PARAM_INT); // Assuming deliverer is an integer
        $statement->bindValue(':statusText', $statusText, PDO::PARAM_INT); // Assuming status_num is an integer
        $statement->bindValue(':photoID', $photoID, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Retrieves the total number of rows from the delivery_point table.
     * This method specifically counts entries where delivery points are linked with valid delivery users and statuses.
     * It's useful for pagination or understanding the scale of the dataset.
     *
     * @return int Returns the total number of linked rows as an integer.
     */
    public function getTotalRows() {
        // SQL query to count all entries in delivery_point where there is a valid deliverer and status.
        // It joins delivery_point with delivery_users and delivery_status on their respective relationships.
        $sqlQuery = 'SELECT COUNT(*) FROM delivery_point, delivery_users, delivery_status 
                 WHERE (delivery_point.deliverer = delivery_users.userid AND delivery_point.status_num = delivery_status.idstatus)';

        // Execute the query directly without preparing since there are no external parameters
        $statement = $this->_dbHandle->query($sqlQuery);

        // fetchColumn() fetches the first column from the result set which in this case is COUNT(*),
        // and returns the count of total linked rows.
        return $statement->fetchColumn();
    }

    /**
     * Fetch deliverer usernames from the database, excluding the first row
     * @return array
     */
    public function fetchDelivererUsernames(): array
    {
        $sqlQuery = 'SELECT userid, username FROM delivery_users';
        $statement = $this->_dbHandle->query($sqlQuery);

        // Fetch all rows, skipping the first one
        $deliverers = $statement->fetchAll(PDO::FETCH_ASSOC);
        array_shift($deliverers);

        return $deliverers;
    }

    /**
     * Fetch status texts from the database
     * @return array
     */
    public function fetchStatusTexts(): array
    {
        $sqlQuery = 'SELECT idstatus, status_text FROM delivery_status';
        $statement = $this->_dbHandle->query($sqlQuery);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the total number of deliveries in the delivery_point table
     * @return int
     */
    public function getTotalDeliveries(): int
    {
        $sqlQuery = 'SELECT COUNT(*) FROM delivery_point';
        $statement = $this->_dbHandle->query($sqlQuery);
        return (int)$statement->fetchColumn();
    }

    /**
     * Fetches delivery information specific to a given username, with pagination support.
     * This method retrieves all related data for deliveries handled by a specific user and manages pagination of the results.
     *
     * @param string $username The username for which delivery info is being retrieved.
     * @param int $page Current page number requested by the user.
     * @param int $rowsPerPage Number of records to be displayed per page.
     * @return array An array of DelivererInfo objects containing the fetched data.
     */
    public function fetchDeliveryInfoForUsername($username, $page, $rowsPerPage) {
        $offset = ($page - 1) * $rowsPerPage; // Calculate the starting point for the results to fetch.

        // SQL query to retrieve delivery information for a specific username with necessary joins and pagination.
        // This query fetches all pertinent details from the delivery_point table and related user and status information.
        $sqlQuery = 'SELECT dp.id, dp.full_name, dp.address_1, du.username, dp.postcode, dp.deliverer, dp.lat, dp.longitude, ds.status_text, dp.photo_name 
                 FROM delivery_point dp
                 INNER JOIN delivery_users du ON dp.deliverer = du.userid
                 INNER JOIN delivery_status ds ON dp.status_num = ds.idstatus
                 WHERE du.username = :username
                 ORDER BY dp.id LIMIT :offset, :rowsPerPage';

        // Another SQL query to count the total number of rows for the given username to manage pagination effectively.
        $countQuery = 'SELECT COUNT(*) FROM delivery_point dp
                   INNER JOIN delivery_users du ON dp.deliverer = du.userid
                   WHERE du.username = :username';

        // Prepare and execute the statement to get total row count.
        $countStatement = $this->_dbHandle->prepare($countQuery);
        $countStatement->bindParam(':username', $username, PDO::PARAM_STR);
        $countStatement->execute();
        $totalRows = $countStatement->fetchColumn(); // Retrieve the total number of rows that match the query.

        // Adjust the pagination if the offset exceeds the total number of rows available.
        if ($offset >= $totalRows) {
            $page = ceil($totalRows / $rowsPerPage);
            $offset = ($page - 1) * $rowsPerPage; // Recalculate offset for the last available page
        }

        // Prepare the main SQL query for execution.
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->bindParam(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
        $statement->execute(); // Execute the query to retrieve the data.

        $dataSet = []; // Initialize an array to hold the data.
        // Loop through the fetched rows and create DelivererInfo objects to be returned.
        while ($row = $statement->fetch()) {
            $dataSet[] = new DelivererInfo($row);
        }

        return $dataSet; // Return the array of DelivererInfo objects containing delivery information.
    }

    /**
     * Update delivery information in the database
     * @param $id
     * @param $name
     * @param $address
     * @param $postCode
     * @param $latitude
     * @param $longitude
     * @param $deliveryDet
     * @param $statusText
     * @param $photoID
     * @return bool
     */
    public function updateDeliveryStatus($id, $statusText) {
        // SQL query to update delivery information by ID
        $sqlQuery = 'UPDATE delivery_point 
                 SET status_num = :statusText 
                 WHERE id = :id';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':statusText', $statusText, PDO::PARAM_INT); // Assuming status_num is an integer

        return $statement->execute();
    }

    /**
     * Fetch delivery information from the database filtered by status
     * @param int $status
     * @param int $page
     * @param int $rowsPerPage
     * @return array
     */
    public function fetchDeliveryInfoByStatus(int $status, int $page, int $rowsPerPage): array {
        $offset = ($page - 1) * $rowsPerPage;

        // SQL query to retrieve delivery information filtered by status
        $sqlQuery = 'SELECT dp.id, dp.full_name, dp.address_1, du.username, dp.postcode, dp.deliverer, dp.lat, dp.longitude, ds.status_text, dp.photo_name 
            FROM delivery_point dp
            INNER JOIN delivery_users du ON dp.deliverer = du.userid
            INNER JOIN delivery_status ds ON dp.status_num = ds.idstatus
            WHERE dp.status_num = :status
            ORDER BY dp.id LIMIT :offset, :rowsPerPage';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind parameters
        $statement->bindValue(':status', $status, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->bindValue(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);

        // Execute the PDO statement
        $statement->execute();

        $dataSet = [];

        // Fetch data and create DelivererInfo objects
        while ($row = $statement->fetch()) {
            $dataSet[] = new DelivererInfo($row);
        }

        return $dataSet;
    }

    /**
     * Fetch delivery information from the database filtered by status
     * @param int $status
     * @param int $page
     * @param int $rowsPerPage
     * @param int $loggedInUserId
     * @return array
     */
    public function fetchDelivererIDByStatus(int $status, int $page, int $rowsPerPage, int $loggedInUserId): array {
        $offset = ($page - 1) * $rowsPerPage;

        // SQL query to retrieve delivery information filtered by status and user ID
        $sqlQuery = 'SELECT dp.id, dp.full_name, dp.address_1, du.username, dp.postcode, dp.deliverer, dp.lat, dp.longitude, ds.status_text, dp.photo_name 
        FROM delivery_point dp
        INNER JOIN delivery_users du ON dp.deliverer = du.userid
        INNER JOIN delivery_status ds ON dp.status_num = ds.idstatus
        WHERE dp.status_num = :status AND du.userid = :loggedInUserId
        ORDER BY dp.id LIMIT :offset, :rowsPerPage';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind parameters
        $statement->bindValue(':status', $status, PDO::PARAM_INT);
        $statement->bindValue(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->bindValue(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);

        // Execute the PDO statement
        $statement->execute();

        $dataSet = [];

        // Fetch data and create DelivererInfo objects
        while ($row = $statement->fetch()) {
            $dataSet[] = new DelivererInfo($row);
        }

        return $dataSet;
    }

    /**
     * Calculates the total number of delivery entries that match a specific status and are associated with a specific user.
     * This is useful for pagination when filtering delivery records on the deliverer page.
     *
     * @param int $status The status number to filter deliveries.
     * @param int $loggedInUserId The ID of the user whose deliveries are to be counted.
     * @return int The count of delivery entries matching the specified status and user.
     */
    public function getTotalRowsFiltered($status, $loggedInUserId) {
        // SQL query to count the number of entries where the delivery status and user ID match the specified criteria.
        // The query performs an inner join between the delivery_point and delivery_users table to ensure that only the deliveries associated with the specified user are counted.
        $sqlQuery = 'SELECT COUNT(*) FROM delivery_point dp
                 INNER JOIN delivery_users du ON dp.deliverer = du.userid
                 WHERE dp.status_num = :status AND du.userid = :loggedInUserId';

        // Prepare the SQL statement for execution to prevent SQL injection and optimize performance.
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind the 'status' parameter to the prepared statement as an integer.
        $statement->bindValue(':status', $status, PDO::PARAM_INT);

        // Bind the 'loggedInUserId' parameter to the prepared statement as an integer.
        $statement->bindValue(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);

        // Execute the prepared statement.
        $statement->execute();

        // Fetch the first column of the result set which contains the count of the matching rows
        // and return this count as an integer.
        return $statement->fetchColumn();
    }

}
