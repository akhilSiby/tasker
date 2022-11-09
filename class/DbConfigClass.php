<?php

/**
 * Database config class
 */
class DbConfigClass {
    // DB configuration
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "tasker";

    protected $pdoConnection;


    public function __construct() {
        if (!isset($this->dbConnection)) {
            $this->pdoConnection = new PDO("mysql:host={$this->hostname};dbname={$this->database}", $this->username, $this->password);
        }
        return $this->pdoConnection;
    }
}
