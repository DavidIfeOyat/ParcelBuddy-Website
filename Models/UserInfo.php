<?php

class UserInfo {

    protected $_idUser, $_userName, $_passWrd, $_userTypeName;

    // Constructor to initialize the object with database row data
    public function __construct($dbRow) {
        $this->_idUser = $dbRow['userid'];
        $this->_userName = $dbRow['username'];
        $this->_passWrd = $dbRow['password'];
        $this->_userTypeName = $dbRow['usertype_name'];
    }

    public function getUserID() {
        return $this->_idUser;
    }

    public function getUserName() {
        return $this->_userName;
    }

    public function getPassWrd() {
        return $this->_passWrd;
    }

    public function getUserType() {
        return $this->_userTypeName;
    }
}


