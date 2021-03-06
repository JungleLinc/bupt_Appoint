<?php

/**
 * Created by PhpStorm.
 * User: Jarvis
 * Date: 2017/2/14
 * Time: 15:11
 */
include_once dirname(__FILE__) . "/SQLCmd.php";
class GetAppointmentTypes extends SQLCmd{
    private $pName;

    function __construct($pName) {
        $this->pName = $pName;
    }

    function queryDB(){
        $query = "SELECT type,duration,ma_user.email 
                  FROM  ma_appointment_types,ma_user_advisor,ma_user 
                  WHERE ma_appointment_types.userId=ma_user_advisor.userId 
                  AND ma_user_advisor.userId=ma_user.userId 
                  AND ma_user_advisor.pName='$this->pName'";
        $this->result = $this->conn->query($query);
    }

    function processResult(){
        include_once dirname(dirname(__FILE__)) . "/bean/AppointmentType.php";
        $arr = array();

        $otherSet = null;

        while($rs = mysqli_fetch_assoc($this->result)){
            $set = new AppointmentType();
            $set->setType($rs["type"]);
            $set->setDuration($rs["duration"]);
            $set->setEmail($rs["email"]);

            if($rs["type"] == "Other")
                $otherSet = $set;
            else
                array_push($arr, ($set));
        }

        if($otherSet != null){
            array_push($arr, ($otherSet));
        }

        return $arr;
    }
}