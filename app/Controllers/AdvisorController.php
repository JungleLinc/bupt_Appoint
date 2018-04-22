<?php
//namespace App\Controllers;
/**
 * Created by PhpStorm.
 * User: gaolin
 * Date: 2/20/17
 * Time: 12:58 AM
 */
//use Models\Db\DatabaseManager;
//use Models\Bean\AllocateTime;
//use Models\Helper\TimeSlotHelper;
//use Models\Bean\Appointment;
//use Models\Login\AdvisorUser;

include_once dirname(dirname(__FILE__))."/Models/db/DatabaseManager.php";
include_once dirname(dirname(__FILE__))."/Models/bean/AllocateTime.php";
class advisorController
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

    function showScheduleAction(){;
        if (!isset($_SESSION['role'])) {
            header("Location:" . getUrlWithoutParameters() . "?c=" .mav_encrypt("login"));
        }

        if($this->role =="advisor" && $this->email!=null){
            $dbm = new DatabaseManager();
            $advisor = $dbm->getAdvisor($this->email);

            $scheduleObjectArr = $dbm->getAdvisorSchedule($advisor->getPName());
            $tempSchedules = array();
            if (sizeof($scheduleObjectArr) != 0) {
                foreach ($scheduleObjectArr as $schedule){
                    array_push($tempSchedules,
                        array(
                        "name" => $schedule->getName(),
                        "date" => $schedule->getDate(),
                        "startTime" => $schedule->getStartTime(),
                        "endTime" => $schedule->getEndTime(),
                        )
                    );

                }
            }


            $appointments = $dbm->getAppointments($advisor);
            $tempAppointments = array();
            foreach ($appointments as $appointment){
                if($appointment->getStatus()==0){
                    array_push($tempAppointments,
                        array(
                            "advisingDate" => $appointment->getAdvisingDate(),
                            "advisingStartTime" => $appointment->getAdvisingStartTime(),
                            "advisingEndTime" => $appointment->getAdvisingEndTime(),
                            "appointmentType" => $appointment->getAppointmentType()
                        )
                    );

                }


            }


        }

        return array(
            "error" => 0,
            "data" => array(
                "email" =>$this->email,
                "advisorName" => $advisor->getPName(),
                "role" => $this->role,
                "schedules" =>$tempSchedules,
                "appointments" => $tempAppointments
            )
        );


    }

//    function showAppointmentAction(){
//        if (!isset($_SESSION['role'])) {
//            header("Location:" . getUrlWithoutParameters() . "?c=" .mav_encrypt("login"));
//        }
//
//        if($this->role =="advisor" && $this->email!=null) {
//            $dbm = new DatabaseManager();
//            $advisor = $dbm->getAdvisor($this->email);
//
//            $appointments = $dbm->getAppointments($advisor);
//
//            $tempAppointments = array();
//            foreach ($appointments as $appointment) {
//                $statusName = ($appointment->getStatus()==0) ? "Scheduled" : "Completed" ;
//                array_push($tempAppointments, array(
//                    "pName" => $appointment->getPname(),
//                    "advisingDate" => $appointment->getAdvisingDate(),
//                    "advisingStartTime" => $appointment->getAdvisingStartTime(),
//                    "advisingEndTime" => $appointment->getAdvisingEndTime(),
//                    "appointmentType" => $appointment->getAppointmentType(),
//                    "appointmentId" => $appointment->getAppointmentId(),
//                    "advisorEmail" => $appointment->getAdvisorEmail(),
//                    "description" => $appointment->getDescription(),
//                    "studentId" => $appointment->getStudentId(),
//                    "studentEmail" => $appointment->getStudentEmail(),
//                    "studentPhoneNumber" => $appointment->getStudentPhoneNumber(),
//                    "status" => $appointment->getStatus(),
//                    "statusName" => $statusName
//                ));
//            }
//
//            return array(
//                "error" => 0,
//                "data" => array(
//                    "appointments" => $tempAppointments
//                )
//            );
//        }
//
//    }

    function addTimeSlotAction(){
        $openDate = $_POST['opendate'];
        $startTime = $_POST['starttime'];
        $endTime = $_POST['endtime'];
        $repeat = $_POST['repeat'];

        if ($startTime == "" || $startTime == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Please enter Start Time correctly."
                )
            );
        if ($endTime == "" || $endTime == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Please enter End Time correctly."
                )
            );
        if ($startTime >= $endTime)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "EndTime must after StartTime."
                )
            );
        if ($openDate == "" || $openDate == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Please enter Date correctly."
                )
            );
        try{
            $rep = intval($repeat);
        }catch (Exception $e){
            $rep = 0;
        }
        if ($rep >7) {
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Repeat times cannot larger than 7."
                )
            );
        }
        $dbm = new DatabaseManager();
        $time = new AllocateTime();
        $time->setDate($openDate);
        $time->setStartTime($startTime);
        $time->setEndTime($endTime);



        date_default_timezone_set('America/Chicago');
        $todayDate = date("Y-m-d");
        if($time->getDate()<=$todayDate){
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Error. The date must after today (" . $todayDate . ")."
                )
            );
        }
        $flag = $dbm->addTimeSlot($time, $this->uid);
        for($i=0;$i<$rep;$i++){
            $time->setDate(TimeSlotHelper::addDate($time->getDate(),1) );
            $flag = $dbm->addTimeSlot($time, $this->uid);
        }

        if($flag == false){
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Error occurs, Please try again."
                )
            );
        }
        else
            return array(
                "error" => 0,

            );





    }


    function deleteTimeSlotAction(){
        $requestStartTime = isset($_POST['StartTime2']) ? $_POST['StartTime2'] : null;
        $requestEndTime = isset($_POST['EndTime2']) ? $_POST['EndTime2'] : null;
        $requestDate = isset($_POST['Date']) ? date('Y-m-d',strtotime($_POST['Date'])) : null;
        $repeat = isset($_POST['delete_repeat']) ? intval($_POST['delete_repeat']) : 0;
        $reason = isset($_POST['delete_reason']) ? $_POST['delete_reason'] : null;
        if ($requestStartTime == null || $requestEndTime == null || $requestDate == null){
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Error while getting this time slot's info. Please close this window and try again."
                )
            );
        }
        if($reason == null || $reason == "")
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "Reason for deleting time slot cannot be empty."
                )
            );
        $dbm = new DatabaseManager();
        $advisor = $dbm->getAdvisor($this->email);
        $date = $requestDate;
        $this->cancelAppointments($dbm,$advisor,$date,$requestStartTime,$requestEndTime,$reason);
        if($repeat > 0){
            for($i = 0 ; $i<$repeat; $i++){
                include_once dirname(dirname(__FILE__))."/Models/helper/TimeSlotHelper.php";
                $date = date('Y-m-d',strtotime(TimeSlotHelper::addDate($date,1)));
                $this->cancelAppointments($dbm,$advisor,$date,$requestStartTime,$requestEndTime,$reason);
            }
        }
        include_once dirname(__FILE__)."/DeleteTimeSlotController.php";
        DeleteTimeSlotController::deleteTimeSlot($requestDate,$requestStartTime,$requestEndTime,$advisor->getPName(),$repeat,$reason);
        return array(
            "error" => 0
        );
    }

    function cancelAppointments(DatabaseManager $dbm, AdvisorUser $advisor, $date , $originalStartTime, $originalEndTime, $reason){
        $appointments = $dbm->getAppointments($advisor);
        $studentEmailAndMsgArr = array();
        foreach ($appointments as $appointment){
            if(($appointment->getAdvisingDate() === $date)
                && ($appointment->getAdvisingStartTime() >= $originalStartTime)
                && ($appointment->getAdvisingEndTime() <= $originalEndTime)
                && ($appointment->getStatus() == 0) ){

                $dbm->cancelAppointment($appointment->getAppointmentId(),$this->role,$reason);

                array_push($studentEmailAndMsgArr,
                    array(
                        "studentEmail" => $appointment->getStudentEmail(),
                        "msg"=> "您在" . $date. "， ". $appointment->getAdvisingStartTime()
                            . "-" .$appointment->getAdvisingEndTime()." 的预约已被教师 " .$advisor->getPName()." 取消。 "
                            ."<br>" ."原因: ". $reason,
                    )
                );


            }


        }
        if(sizeof($studentEmailAndMsgArr)!=0){
            $emailSubject = 'BUPT-Appoint: 教师预约时间段已被取消！';
            foreach ($studentEmailAndMsgArr as $keyValue){
                mav_mail($emailSubject,$keyValue['msg'],array($keyValue['studentEmail']));
            }
        }

    }

    public function getStartAndEndTimeOfOriginalTimeSlotAction(){
        $dbm = new DatabaseManager();
        $advisor = $dbm->getAdvisor($this->email);
        $date = $_POST['date'];
        $originalStartTime = $_POST['subStartTime'];
        $originalEndTime = $_POST['subEndTime'];


        $originalTimeSlots = $dbm->getAdvisorSchedule($advisor->getPName(),true,$date);
        foreach ($originalTimeSlots as $timeSlot){
            if($originalStartTime>=$timeSlot->getStartTime() && $originalEndTime<=$timeSlot->getEndTime())
            {
                $originalStartTime = $timeSlot->getStartTime();
                $originalEndTime = $timeSlot->getEndTime();
                break;
            }
        }
        return array(
            "error" => 0,
            "data" => array(
                "originalStartTime" => $originalStartTime,
                "originalEndTime" => $originalEndTime
            )

        );


    }

    public function successAction() {
        $controller = mav_encrypt($_REQUEST['nc']);
        $action = mav_encrypt($_REQUEST['na']);

        return array(
            "error" => 0,
            "data" => getUrlWithoutParameters() . "?c=$controller&a=$action"
        );
    }




}