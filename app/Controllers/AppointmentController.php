<?php
include_once ROOT . "/app/Models/db/DatabaseManager.php";

class AppointmentController
{
    public function makeAppointmentAction(){
        include_once ROOT . "/app/Models/bean/Appointment.php";

        $appointment = new Appointment();
        $appointment->setStudentPhoneNumber($_REQUEST['phoneNumber']);
        $appointment->setStudentId($_REQUEST['studentId']);
        $appointment->setDescription($_REQUEST['description']);
        $appointment->setAppointmentType($_REQUEST['appointmentType']);
        $appointment->setPname($_REQUEST['pName']);
        $appointment->setStatus(0);

        $start = $_REQUEST['start'];
        $dateTime = explode(" ", $start);
        $date = $dateTime[3] . "-" . convertMonth($dateTime[1]) . "-" . $dateTime[2];
        $startTime = $dateTime[4];
        $duration = $_REQUEST['duration'];
        $endTime = $this->addTime($startTime, $duration);

        $appointment->setAdvisingDate($date);
        $appointment->setAdvisingStartTime($startTime);
        $appointment->setAdvisingEndTime($endTime);

        $dbManager = new DatabaseManager();
        $result = $dbManager->createAppointment($appointment, $_REQUEST['email']);
        if (!isset($result['response']) || !$result['response']) {
            return array(
                "error" => 1
            );
        }

        if ($result['student_notify'] == 'yes') {
            mav_mail("BUPT-Appoint: 预约成功 ",
                "您与教师 " . $appointment->getPname()." 的预约咨询主题为: " . $appointment->getAppointmentType() . ", <br>时间: " . $appointment->getAdvisingDate() . ", " .
                $appointment->getAdvisingStartTime() . " - " . $appointment->getAdvisingEndTime() . ".<br>谢谢!",
                array($_REQUEST['email']));
        }

        if ($result['advisor_notify'] == 'yes') {
            mav_mail("BUPT-Appoint: 您有新的预约 " ,
                "学号为 " .  $appointment->getStudentId(). "的学生已经与您预约成功, <br>时间为: " . $appointment->getAdvisingDate() . ",  " .
                $appointment->getAdvisingStartTime() . " - " . $appointment->getAdvisingEndTime() . "<br>预约咨询主题为: ". $appointment->getAppointmentType() ,
                array($result['advisor_email']));
        }

        return array(
            "error" => 0,
        );
    }

    public function showAppointmentAction() {
        if($_SESSION['role'] ==null || $_SESSION['email'] == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "会话失效,请登录重试."
                )
            );
        $dbManager = new DatabaseManager();
        $user = null;
        switch ($_SESSION['role']){
            case 'student':
                $user = $dbManager->getStudent($_SESSION['email']);
                break;
            case 'advisor':
                $user = $dbManager->getAdvisor($_SESSION['email']);
                break;
            default:
                return array(
                    "error" => 1,
                    "data" => array(
                        "errorMsg" => "用户角色信息错误,请重新登录."
                    )
                );
                break;
        }

        if($user->getUserId() == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "系统获取用户信息失败,请重新登录."
                )
            );

        return array(
            "error" => 0,
            "data" => array(
                "appointments" => $this->getAppointments($user, $dbManager)
            )
        );
    }

    public function showCanceledAppointmentAction(){
        if($_SESSION['role'] ==null || $_SESSION['email'] == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "会话失效,请登录重试."
                )
            );
        $dbManager = new DatabaseManager();
        switch ($_SESSION['role']){
            case 'student':
                $user = $dbManager->getStudent($_SESSION['email']);
                break;
            case 'advisor':
                $user = $dbManager->getAdvisor($_SESSION['email']);
                break;
            default:
                return array(
                    "error" => 1,
                    "data" => array(
                        "errorMsg" => "用户角色信息错误,请重新登录."
                    )
                );
                break;
        }

        if($user->getUserId() == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "系统获取用户信息失败,请重新登录."
                )
            );

        return array(
            "error" => 0,
            "data" => array(
                "appointments" => $this->getCanceledAppointments($user, $dbManager)
            )
        );
    }

    public function cancelAppointmentAction() {
        $appointmentId = $_REQUEST['appointmentId'];
        if($appointmentId == null || $appointmentId=='')
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "系统获取预约信息失败,请刷新重试.",
                )
            );
        $reason = $_REQUEST['cancellationReason'];
        if($reason == null || $reason == "")
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "取消原因不能为空!",
                )
            );
        $isCanceledBy = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        if($isCanceledBy == null)
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "获取用户信息失败,请重新登录并重试.",
                )
            );
        $dbManager = new DatabaseManager();
        $appointment = $dbManager->getAppointmentById($appointmentId);
//        $waitList = $dbManager->getFirstWaitList($appointmentId);
//        if ($waitList != null) {
//
//            $appointment->setStudentUserId($waitList->getStudentUserId());
//            $appointment->setStudentId($waitList->getStudentId());
//            $appointment->setStudentEmail($waitList->getStudentEmail());
//            $appointment->setStudentPhoneNumber($waitList->getStudentPhone());
//            $appointment->setAppointmentType($waitList->getType());
//            $appointment->setDescription($waitList->getDescription());
//
//            if ($dbManager->updateAppointment($appointment) && $dbManager->deleteWaitListSchedule($waitList->getId())) {
//
//                mav_mail("MavAppoint: Advising appointment with " . $appointment->getPname(),
//                    "\nAn appointment has been set for " . $appointment->getAppointmentType() . " on " . $appointment->getAdvisingDate() . " at " .
//                    $appointment->getAdvisingStartTime() . " - " . $appointment->getAdvisingEndTime() . "\nThanks!",
//                    [$appointment->getStudentEmail()]);
//
//                mav_mail("MavAppoint: Advising appointment with " . $appointment->getStudentId(),
//                    "\nAn appointment has been updated for " . $appointment->getAppointmentType() . " on " . $appointment->getAdvisingDate() . " at " .
//                    $appointment->getAdvisingStartTime() . " - " . $appointment->getAdvisingEndTime() .
//                    "\nSome student cancelled the appointment and the first one in the wait list is scheduled\nThanks!",
//                    [$appointment->getAdvisorEmail()]);
//
//            } else {
//                $result = 1;
//            }
//
//        } else {

        if ($dbManager->cancelAppointment($appointmentId, $isCanceledBy,$reason)) {

            mav_mail("您与 " . $appointment->getPname() . " 的预约已被取消",
                "您在 " . $appointment->getAdvisingDate() . ", " . $appointment->getAdvisingStartTime() .
                " 至 " . $appointment->getAdvisingEndTime() . " 的预约已被取消.",
                array($appointment->getStudentEmail()));

            mav_mail("学号为: " . $appointment->getStudentId() . "的学生与您的预约已被取消",
                "您在 " . $appointment->getAdvisingDate() . ", " . $appointment->getAdvisingStartTime() .
                " 至 " . $appointment->getAdvisingEndTime() . "的预约已被取消.",
                array($appointment->getAdvisorEmail()));

        } else {
            return array(
                "error" => 1,
                "data" => array(
                    "errorMsg" => "错误,请关闭当前窗口重试.",
                )
            );
        }
//        }

        return array(
            "error" => 0,
            "data" => array(
                "successMsg" => "预约取消成功",
                )
            );
    }

    public function successAction(){
        $controller = mav_encrypt($_REQUEST['nc']);
        $action = mav_encrypt($_REQUEST['na']);
        return array(
            "error" => 0,
            "data" => getUrlWithoutParameters() . "?c=$controller&a=$action"
        );
    }

   private function getAppointments(LoginUser $user, DatabaseManager $dbManager) {
        $appointments = $dbManager->getAppointments($user);

        $tempAppointments = array();
        foreach ($appointments as $appointment) {
            $statusName = ($appointment->getStatus()==0) ? "Reserved" : "Canceled" ;
            array_push($tempAppointments, array(
                "pName" => $appointment->getPname(),
                "advisingDate" => $appointment->getAdvisingDate(),
                "advisingStartTime" => $appointment->getAdvisingStartTime(),
                "advisingEndTime" => $appointment->getAdvisingEndTime(),
                "appointmentType" => $appointment->getAppointmentType(),
                "appointmentId" => $appointment->getAppointmentId(),
                "advisorEmail" => $appointment->getAdvisorEmail(),
                "description" => $appointment->getDescription(),
                "studentId" => $appointment->getStudentId(),
                "studentEmail" => $appointment->getStudentEmail(),
                "studentPhoneNumber" => $appointment->getStudentPhoneNumber(),
                "status" => $appointment->getStatus(),
                "statusName" => $statusName
            ));
        }

        return $tempAppointments;
    }


    private function getCanceledAppointments(LoginUser $user, DatabaseManager $dbManager) {
        $appointments = $dbManager->getAppointments($user);

        $tempAppointments = array();
        foreach ($appointments as $appointment) {
            if($appointment->getStatus() == -1){
                array_push($tempAppointments, array(
                    "pName" => $appointment->getPname(),
                    "advisingDate" => $appointment->getAdvisingDate(),
                    "advisingStartTime" => $appointment->getAdvisingStartTime(),
                    "advisingEndTime" => $appointment->getAdvisingEndTime(),
                    "appointmentType" => $appointment->getAppointmentType(),
                    "appointmentId" => $appointment->getAppointmentId(),
                    "advisorEmail" => $appointment->getAdvisorEmail(),
                    "description" => $appointment->getDescription(),
                    "studentId" => $appointment->getStudentId(),
                    "studentEmail" => $appointment->getStudentEmail(),
                    "studentPhoneNumber" => $appointment->getStudentPhoneNumber(),
                    "isCanceledBy" => $appointment->getIsCanceledBy(),
                    "remark" => $appointment->getRemark()
                ));

            }

        }

        return $tempAppointments;
    }


    private function addTime($startTime, $duration) {
        $tmp = explode(":", $startTime);
        $hours = $tmp[0];
        $minutes = $tmp[1] + $duration;
        if ($minutes >= 60) {
            $minutes -= 60;
            $hours += 1;

            if ($hours < 10) {
                $hours = "0" . $hours;
            }
        }

        if ($minutes < 10) {
            $minutes = "0" . $minutes;
        }
        $tmp[1] = $minutes;
        $tmp[0] = $hours;

        return join(":", $tmp);
    }
}