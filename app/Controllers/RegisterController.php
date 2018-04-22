<?php

class RegisterController
{
    public function defaultAction() {
        $_SESSION['mavAppointUrl'] = getUrlWithoutParameters();
        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $dbManager = new DatabaseManager();
        $departments = $dbManager->getDepartment();
        $majors = $dbManager->getMajorsOfDepartment($departments[0]);
        return array(
            "error" => 0,
            "data" => array(
                "degrees" => $this->getDegrees(),
                "departments" => $departments,
                "majors" => $majors,
                "url" => getUrlWithoutParameters() . "?c=" . mav_encrypt("login")
            )
        );
    }

    public function getMajorsAction() {
//        $department = isset($_REQUEST['department']) ? $_REQUEST['department'] : "CSE";
        $department = $_REQUEST['department'];
        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $dbManager = new DatabaseManager();
        $majors = $dbManager->getMajorsOfDepartment($department);
        return array(
            "error" => 0,
            "data" => array(
                "majors" => $majors
            )
        );
    }

    public function registerStudentAction() {
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : "";
        $studentId = isset($_REQUEST['studentId']) ? $_REQUEST['studentId'] : "";
        $phoneNumber = isset($_REQUEST['phoneNumber']) ? $_REQUEST['phoneNumber'] : "";
        $lastName = isset($_REQUEST['lastName']) ? $_REQUEST['lastName'] : "";
        $firstName = isset($_REQUEST['firstName']) ? $_REQUEST['firstName'] : "";

        if (!validateStudentId($studentId)) {
            return array(
                "error" => 1,
                "description" => "请输入有效学号"
            );
        }

        if (!validatePhoneNumber($phoneNumber)) {
            return array(
                "error" => 1,
                "description" => "请输入有效手机号"
            );
        }

        if (!validateEmail($email)) {
            return array(
                "error" => 1,
                "description" => "请输入有效邮箱"
            );
        }
        include_once ROOT . "/app/Models/login/StudentUser.php";
        $studentUser = new StudentUser();
        $studentUser->setEmail($email);
        $studentUser->setPhoneNumber($phoneNumber);
        $studentUser->setStudentId($studentId);
        $studentUser->setRole("student");
        $studentUser->setLastName($_REQUEST['lastName']);
        $studentUser->setFirstName($_REQUEST['firstName']);
        $studentUser->setDepartments(array($_REQUEST['department']));
        $studentUser->setMajors(array($_REQUEST['major']));
        $studentUser->setDegreeTypeFromString($_REQUEST['degree']);
        $studentUser->setNotification("yes");
        $studentUser->setSendTemPWDate(date("Y-m-d", time()));
        $password = generateRandomPassword();
        $studentUser->setPassword($password);

        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $dbManager = new DatabaseManager();

        $uid = $dbManager->createUser($studentUser);
        if ($uid) {
            $studentUser->setUserId($uid);
        } else {
            return array(
                "error" => 1,
                "description" => "Errors while creating user"
            );
        }

        if (!$dbManager->createStudent($studentUser)) {
            return array(
                "error" => 1,
                "description" => "Errors while creating student"
            );
        }

        mav_mail("BUPT-Appointment账户创建成功",
            "<p>您的账户信息如下:</p>"
            . "<p>角色: 学生 </p>"
            . "<p>账号: " . $studentUser->getEmail() . " </p>"
            . "<p>密码: " . $password . "</p>"
            . "<br><br>点击 <a href='" . getUrlWithoutParameters() . "?c=" . mav_encrypt("login") . "'>这里</a>, 登录成功后修改临时密码.", array($studentUser->getEmail()));

        return array(
            "error" => 0,
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

    private function getDegrees() {
        return array("Bachelor", "Master", "Doctorate");
    }

}