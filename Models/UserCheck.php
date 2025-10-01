<?php
// Include necessary classes
require_once('Models/Database.php');
require_once('Models/UserInfo.php');

class UserCheck
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        // Initialize the database connection
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    // Method to set the database handle, assuming it's not set in the constructor
    public function setDbHandle($dbHandle) {
        $this->_dbHandle = $dbHandle;
    }
    /**
     * Check user credentials and redirect based on user type
     * @param $userName
     * @param $passWrd
     * @return bool|array
     */
    public function userChecker($userName, $passWrd)
    {
        // SQL query to fetch user data based on the provided username
        $sqlQuery = 'SELECT * FROM delivery_users WHERE username = :username';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':username', $userName, PDO::PARAM_STR);
        $statement->execute();

        // Fetch the user data
        $userData = $statement->fetch(PDO::FETCH_ASSOC);

        // If user data is found
        if ($userData) {
            // Verify the entered password with the hashed password from the database
            if (password_verify($passWrd, $userData['password'])) {
                // Store user information in the session
                session_start();
                $_SESSION['user_id'] = $userData['userid'];
                $_SESSION['username'] = $userData['username'];
                $_SESSION['user_type'] = $userData['usertype'];

                // Check the user type
                $userType = $userData['usertype'];

                // Redirect based on user type
                if ($userType == 1) {
                    // This redirects Manager to manager page
                    header("Location: manager_page.php");
                    exit();
                } elseif ($userType == 2) {
                    // Redirects Deliverer to deliverer page
                    header("Location: deliverer_page.php");
                    exit();
                }
            }
        }
        // If user data is not found or password is incorrect, return false
        return false;
    }

    /**
     * Add a new user to the delivery_users table
     *
     * @param string $username
     * @param string $hashedPassword
     * @param int $userType
     * @return bool Whether the user addition was successful
     */
    public function addUser($username, $hashedPassword, $userType)
    {
        // SQL query to insert a new user into the delivery_users table
        $sqlQuery = 'INSERT INTO delivery_users (username, password, usertype) VALUES (:username, :password, :usertype)';

        // Prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery);

        // Bind values to parameters
        $statement->bindParam(':username', $username, PDO::PARAM_STR);
        $statement->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $statement->bindParam(':usertype', $userType, PDO::PARAM_INT);

        // Execute the PDO statement and return the result
        return $statement->execute();
    }

    /**
     * Searches for users based on a given search query
     * It excludes users with a user type of 1 and sorts the results by user ID.
     *
     * @param string $searchQuery The search term input by the user.
     * @return array An array of UserInfo objects that match the search criteria.
     */
    public function searchUsers($searchQuery): array
    {
        $searchQuery = '%' . $searchQuery . '%';  // Modify the search term to use with SQL LIKE operation.

        // Prepare an SQL query that joins delivery_users with delivery_usertype and filters based on the search criteria.
        $sqlQuery = 'SELECT delivery_users.userid, username, password, usertype_name 
                 FROM delivery_users, delivery_usertype 
                 WHERE (delivery_users.usertype = delivery_usertype.id) 
                 AND delivery_users.usertype <> 1
                 AND (username LIKE :searchQuery OR usertype_name LIKE :searchQuery OR CAST(delivery_users.userid AS CHAR) LIKE :searchQuery)
                 ORDER BY delivery_users.userid';

        $statement = $this->_dbHandle->prepare($sqlQuery);  // Prepare the SQL statement
        $statement->bindParam(':searchQuery', $searchQuery, PDO::PARAM_STR);  // Bind the modified search term
        $statement->execute();  // Execute the query

        $dataSet = [];  // Initialize an empty array to collect the results
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserInfo($row);  // Create UserInfo objects for each row and add to the result set
        }

        return $dataSet;  // Return the array of UserInfo objects
    }

    /**
     * Fetches all users excluding those with a user type of 1.
     * Results are sorted by user ID.
     *
     * @return array An array of UserInfo objects for all users.
     */
    public function fetchAllUsers(): array
    {
        // SQL query to select all users, joining with user types, excluding type 1
        $sqlQuery = 'SELECT delivery_users.userid, username, password, usertype_name 
                 FROM delivery_users, delivery_usertype 
                 WHERE (delivery_users.usertype = delivery_usertype.id) 
                 AND delivery_users.usertype <> 1
                 ORDER BY delivery_users.userid';

        $statement = $this->_dbHandle->prepare($sqlQuery);  // Prepare and execute the SQL statement
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserInfo($row);  // Populate results with UserInfo objects
        }
        return $dataSet;
    }

    /**
     * Deletes a user and any related delivery points from the database.
     *
     * @param int $userID The unique identifier of the user to be deleted.
     * @return bool Returns true if the operation was successful, false otherwise.
     */
    public function deleteUserInfo($userID)
    {
        $this->deleteRelatedDeliveryPoints($userID);  // Delete related records in delivery_point table first

        $sqlQuery = 'DELETE FROM delivery_users WHERE userid = :userID';  // SQL to delete the user
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);  // Bind user ID to the prepared statement

        return $statement->execute();  // Execute the deletion and return the result
    }

    /**
     * Deletes delivery points associated with a specific user.
     *
     * @param int $userID The user ID whose delivery points are to be deleted.
     */
    private function deleteRelatedDeliveryPoints($userID)
    {
        $sqlQuery = 'DELETE FROM delivery_point WHERE deliverer = :userID';  // SQL to delete related delivery points
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);  // Bind user ID to the statement
        $statement->execute();  // Execute the deletion
    }

    /**
     * Updates user information in the database.
     *
     * @param int $userID The user ID for which the information is updated.
     * @param string $newUserName The new username.
     * @param string $newPassword The new password.
     * @param string $newUserType The new user type.
     * @return void
     */
    public function updateUserInfo($userID, $newUserName, $newPassword, $newUserType)
    {
        $sqlQuery = 'UPDATE delivery_users 
                 SET username = :newUserName, password = :newPassword, usertype = :newUserType 
                 WHERE userid = :userID';  // SQL to update user info

        $statement = $this->_dbHandle->prepare($sqlQuery);  // Prepare the SQL statement
        // Bind new values and the user ID to the statement
        $statement->bindParam(':newUserName', $newUserName, PDO::PARAM_STR);
        $statement->bindParam(':newPassword', $newPassword, PDO::PARAM_STR);
        $statement->bindParam(':newUserType', $newUserType, PDO::PARAM_STR);
        $statement->bindParam(':userID', $userID, PDO::PARAM_INT);

        $statement->execute();  // Execute the update
    }

    /**
     * Logs out the current user by destroying the session.
     */
    public function logoutUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();  // Start the session if it hasn't been started
        }

        $_SESSION = [];  // Clear all session variables
        session_destroy();  // Destroy the session
    }

    /**
     * Checks if the user is currently logged in.
     *
     * @return bool Returns true if the user is logged in, false otherwise.
     */
    public function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();  // Start the session if it hasn't been started
        }

        return isset($_SESSION['user_id']);  // Check if 'user_id' is set in the session
    }
}

