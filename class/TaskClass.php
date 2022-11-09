<?php
include_once "DbConfigClass.php";


/**
 * undocumented class
 */
class Task extends DbConfigClass {
    public function __construct() {
        parent::__construct();
    }


    /**
     * Query executer
     *
     * Function for executing any query and returning its status.
     *
     * @param String $query Query to be executed.
     * @param Array $keyValue Associative array containing the values
     * @return Boolean Return status of query execution.
     **/
    public function queryExecute($query, $keyValue) {
        $pdoStatement = $this->pdoConnection->prepare($query);
        $result = $pdoStatement->execute($keyValue);
        if ($result == false) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Get task data function
     *
     * The function will execute select queries and returns data
     *
     * @param String $selectQuery Select query for fetching task data.
     * @param Array $keyValue Associative array containing the values
     * @return Array $taskRow Rows of user data.
     **/
    public function getTaskData($selectQuery, $keyValue) {

        $pdoStatement = $this->pdoConnection->prepare($selectQuery);
        $pdoStatement->execute($keyValue);
        $taskRow = array();

        while ($row = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            $taskRow[] = $row;
        }
        return $taskRow;
    }
}
