<?php
/**
 * Created by PhpStorm.
 * User: ren xiaolei
 * Date: 4/6/16
 * Time: 17:56
 */

//namespace App\Controllers;


//use Models\Bean\AppointmentType;
//use Models\Db\DatabaseManager;
//use Models\Login\AdvisorUser;

include_once dirname(dirname(__FILE__))."/Models/db/DatabaseManager.php";
include_once dirname(dirname(__FILE__))."/Models/bean/AppointmentType.php";
include_once dirname(dirname(__FILE__))."/Models/login/AdvisorUser.php";
class CustomizeSettingController
{

    private $email;
    private $uid;
    private $role;

    function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->email = isset($_SESSION['email']) ? $_SESSION['email']: null;
        $this->uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
        $this->role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }

    function showAppointmentTypeAction(){
        $typeAndDuration = "";
        $getAdvisorNotificationState = "";
        $cutOffTime = "";
        if($this->role =="advisor" && $this->email!=null){
            $dbm = new DatabaseManager();
            $advisor = $dbm->getAdvisor($this->email);
            $appointmentTypeObjectArr = $dbm->getAppointmentTypes($advisor->getPName());
            $typeAndDuration = array();
            if (sizeof($appointmentTypeObjectArr) != 0) {
                foreach ($appointmentTypeObjectArr as $appointmentType){
                    array_push($typeAndDuration,
                        array(
                            "type" => $appointmentType->getType(),
                            "duration" => $appointmentType->getDuration()
                        )
                    );
                }
            }
            $getAdvisorNotificationState = $advisor->getNotification();
            $cutOffTime = $advisor->getCutOffPreference();
        }else if($this->role =="student" && $this->email!=null){
            $dbm = new DatabaseManager();
            $student = $dbm->getStudent($this->email);
            $getAdvisorNotificationState = $student->getNotification();
        }else
            return array(
                "error" => 1,
                "data" => array(
                    "typeAndDuration" =>$typeAndDuration,
                    "advisorNotificationState" =>$getAdvisorNotificationState
                )
            );

        return array(
            "error" => 0,
            "data" => array(
                "typeAndDuration" =>$typeAndDuration,
                "advisorNotificationState" =>$getAdvisorNotificationState,
                "cutOffTime" => $cutOffTime
            )
        );
    }

    function cutOffTimeAction(){
        $cutOffTime = isset($_REQUEST['cutOffTime']) ? $_REQUEST['cutOffTime'] : "";

        if($cutOffTime == "" || $cutOffTime < 0)
            return array(
                "error" => 1,
                "dispatch" => "failure"
            );

        $dbm = new DatabaseManager();
        $uid = $dbm->getUserIdByEmail($_SESSION['email']);
        $dbm->setCutOffTime($uid, $cutOffTime);

        return array(
            "error" => 0,
            "dispatch" => "success"
        );
    }

    function setEmailNotificationsAction(){
        $radioValue = isset($_REQUEST['notify']) ? $_REQUEST['notify'] : "";
        $dbm = new DatabaseManager();
        $uid = $dbm->getUserIdByEmail($_SESSION['email']);
        $user = new LoginUser();
        $user->setUserId($uid);
        $user->setRole($this->role);

        if (!$dbm->updateUserNotification($user, $radioValue)) {
            return array(
                "error" => 1
            );
        }

        return array(
            "error" => 0,
            "dispatch" => "success",
        );
    }

    function addTypeAndDurationAction(){
        $at = new AppointmentType();
        $type = $_REQUEST['apptypes'];
        $duration = $_REQUEST['minutes'];
        if($type == null || $type == ""){
            return array(
                    "error" => 1,
                    "data" => array("errorMsg" => "Advising Task cannot be empty.")
                    );
        }

         if($duration == null || $duration == ""){
            return array(
                    "error" => 1,
                    "data" => array("errorMsg" => "Duration cannot be empty.")
                    );
         }

         if ($duration < 0) {
             return array(
                 "error" => 1,
                 "data" => array("errorMsg" => "Duration must be positive.")
             );
         }

         $restAfterMod5 = $duration % 5;
         if($restAfterMod5 != 0){
                        return array(
                                "error" => 1,
                                "data" => array("errorMsg" => "Duration must be a multiple of 5.")
                                );
          }

        $at->setType($type);
        $at->setDuration($duration);
        $dbm = new DatabaseManager();
        $uid = $dbm->getUserIdByEmail($_SESSION['email']);
        $dbm->addAppointmentType($uid,$at);

        return array(
            "error" => 0
        );
    }

    function changeTypeAndDurationAction(){
        $at = new AppointmentType();
        $type = isset($_REQUEST['apptypes']) ? $_REQUEST['apptypes'] : "";
        $duration = isset($_REQUEST['minutes']) ? $_REQUEST['minutes'] : "";
        if (!$duration){
            $duration = "5";
        }

        $at->setType($type);
        $at->setDuration($duration);
        $dbm = new DatabaseManager();
        $uid = $dbm->getUserIdByEmail($_SESSION['email']);
        $dbm->updateAppointmentType($uid,$at);

        return array(
            "error" => 0,
            "dispatch" => "success",
            "uid"=>$uid
        );
    }

    function deleteTypeAndDurationAction(){
        $at = new AppointmentType();
        $type = isset($_REQUEST['apptypes']) ? $_REQUEST['apptypes'] : "";
        $duration = isset($_REQUEST['minutes']) ? $_REQUEST['minutes'] : "";
        $at->setType($type);
        $at->setDuration($duration);
        $dbm = new DatabaseManager();
        $uid = $dbm->getUserIdByEmail($_SESSION['email']);
        $dbm->deleteAppointmentType($uid,$at);//

        return array(
            "error" => 0,
            "dispatch" => "success",
        );
    }

    public function successAction(){
        return array(
            "error" => 0,
            "data" => getUrlWithoutParameters() . "?c=" . mav_encrypt("customizeSetting") . "&a=" . mav_encrypt("showAppointmentType")
        );
    }
}