<?php
/**
 * Created by PhpStorm.
 * User: Jarvis
 * Date: 2017/2/14
 * Time: 16:09
 */
include_once dirname(__FILE__) . "/SQLCmd.php";
class GetAdvisorsOfDepartment extends SQLCmd{
    private $dep;

    function __construct($dep) {
        $this->dep = $dep;
    }

    function queryDB(){
        $query = "SELECT ma_user_advisor.*,ma_user.*,ma_department_user.name as department
                  FROM ma_user_advisor,ma_department_user,ma_user
                  WHERE ma_department_user.userId=ma_user_advisor.userId 
                  AND ma_user.userId=ma_user_advisor.userId ";

        if($this->dep != "")
            $query .= "AND ma_department_user.name = '$this->dep'";

        $this->result = $this->conn->query($query);
    }

    function processResult(){
        include_once dirname(dirname(__FILE__)) . "/login/AdvisorUser.php";
        $arr = array();
        while($rs = mysqli_fetch_assoc($this->result)){
            $user = new AdvisorUser();
            $user->setUserId($rs['userId']);
            $user->setPName($rs['pName']);
            $user->setNotification($rs['notification']);
            $user->setNameLow($rs['nameLow']);
            $user->setNameHigh($rs['nameHigh']);
            $user->setDegType($rs['degreeTypes']);
            $user->setCutOffPreference($rs['cutOffTime']);
            $user->setEmail($rs['email']);
            $user->setPassword($rs['password']);
            $user->setRole($rs['role']);
            $user->setValidated($rs['validated']);
            $user->setDept($rs['department']);
            array_push($arr, $user);
        }

        return $arr;
    }
}