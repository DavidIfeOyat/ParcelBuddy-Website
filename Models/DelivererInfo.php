<?php

class DelivererInfo {

    // Properties to store deliverer data
    protected $_userID, $_name, $_address, $_postCode, $_deliveryDet, $_latitude, $_longitude, $_statusText, $_photoID;
    // Constructor to initialize the object with database row data
    public function __construct($dbRow) {
        $this->_userID = $dbRow['id'];
        $this->_name = $dbRow['full_name'];
        $this->_address = $dbRow['address_1'];
        $this->_postCode = $dbRow['postcode'];
        $this->_latitude = $dbRow['lat'];
        $this->_longitude = $dbRow['longitude'];
        $this->_photoID = $dbRow['photo_name'];
        $this->_deliveryDet = $dbRow['username'];
        $this->_statusText = $dbRow['status_text'];
    }

    // Getter method to retrieve the user ID
    public function getUserID() {
        return $this->_userID;
    }
    // Getter method to retrieve the name
    public function getName() {
        return $this->_name;
    }
    // Getter method to retrieve the Address
    public function getAddress() {
        return $this->_address;
    }
    // Getter method to retrieve the postcode
    public function getPostCode() {
        return $this->_postCode;
    }

    // Getter method to retrieve the Deliverer
    public function getDeliveryDet() {
        return $this->_deliveryDet;
    }
    // Getter method to retrieve the latitude
    public function getLatitude() {
        return $this->_latitude;
    }
    // Getter method to retrieve the longitude
    public function getLongitude() {
        return $this->_longitude;
    }
    // Getter method to retrieve the photoID
    public function getPhotoID() {
        return $this->_photoID;
    }
    // Getter method to retrieve the statusText

    public function getStatusText() {
        return $this->_statusText;
    }
}



