<?php

include_once dirname(__FILE__) . "/SQLCmd.php";
class CreateMajor extends SQLCmd
{
    private $major;
    private $department;

    function __construct($major, $department) {
        $this->major = $major;
        $this->department = $department;
    }

    function queryDB(){
        if($this->major != null)
            $query = "INSERT INTO ma_major (name, depName) VALUES('$this->major', '$this->department')";
        $this->conn->query($query);
        if (mysqli_affected_rows($this->conn) > 0)
            $this->result = true;
        else
            $this->result = false;
    }

    function processResult(){

        return $this->result;
    }
}