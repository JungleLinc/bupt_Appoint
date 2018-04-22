<?php

class LoginController
{
    public function defaultAction()
    {
        $_SESSION['mavAppointUrl'] = getUrlWithoutParameters();
    }

    public function checkAction()
    {

        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];

        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $manager = new DatabaseManager();

        include_once ROOT . "/app/Models/bean/GetSet.php";
        $set = new GetSet();
        $set->setEmail($email);
        $set->setPassword($password);
        $res = $manager->checkUser($set);

        if (!$res) {
            return array(
                "error" => 1
            );
        }

        $_SESSION['email'] = $email;
        $_SESSION['role'] = $res['role'];
        $_SESSION['uid'] = $manager->getUserIdByEmail($email);
        $time = $manager->getTemporaryPasswordInterval();
//        $time = 80;

        if($res['sendTemPWDate']!=null){
            $today1 = strtotime(date("Y-m-d", time()));
            $lastDate1 = strtotime($res['sendTemPWDate']);
            $daysBeforetempPasswordExpired = $time - ($today1-$lastDate1)/(3600*24);
        }
        if($res['lastModDate'] !=null) {
            $today = strtotime(date("Y-m-d", time()));
            $lastDate = strtotime($res['lastModDate']);
            $daysBeforePasswordExpired = 90 - ($today-$lastDate)/(3600*24);
        }
        return array(
            "error" => 0,
            "data" => array(
                "role" => $res['role'],
                "validated" => $res['validated'],
                "lastModDate" => $res['lastModDate'],
                "daysBeforeExpired" => isset($daysBeforePasswordExpired) ? $daysBeforePasswordExpired : null,
                "daysBeforetempPasswordExpired" => isset($daysBeforetempPasswordExpired) ? $daysBeforetempPasswordExpired : null

            )
        );

    }

    public function changePasswordDefaultAction(){

    }

    public function changePasswordAction() {
        $currentPassword = $_REQUEST['currentPassword'];
        $newPassword = $_REQUEST['newPassword'];
        $repeatPassword = $_REQUEST['repeatPassword'];

        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $dbManager = new DatabaseManager();
        if ($_SESSION['role'] == "admin") {
            $user = $dbManager->getAdmin($_SESSION['email']);
        } else if ($_SESSION['role'] == "advisor") {
            $user = $dbManager->getAdvisor($_SESSION['email']);
        } else {
            $user = $dbManager->getStudent($_SESSION['email']);
        }
        if (md5($currentPassword) != $user->getPassword()) {
            return array(
                "error" => 1,
                "description" => "原密码错误"
            );
        }

        if (strlen($newPassword) < 8) {
            return array(
                "error" => 1,
                "description" => "新密码长度至少为8位"
            );
        }
        if ($currentPassword == $newPassword) {
            return array(
                "error" => 1,
                "description" => "新密码不能与旧密码相同"
            );
        }

        if ($newPassword != $repeatPassword) {
            return array(
                "error" => 1,
                "description" => "输入的两次新密码不一致,请重新输入"
            );
        }


        $user->setPassword($newPassword);
        $user->setValidated(1);
        $user->setLastModDate( date("Y-m-d", time()     ) );
        if (!$dbManager->updateUser($user)) {
            return array(
                "error" => 1,
                "description" => "更改密码出错."
            );
        }

        return array(
            "error" => 0
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

    public function logoutAction()
    {
        session_unset();
        session_destroy();
    }

    public function testAction()
    {
        return array(
            "error" => 0
        );
    }

    public function forgotPasswordAction(){
        $emailAddress = $_REQUEST['emailAddress'];
        if($emailAddress=="" || $emailAddress==null){
            return array(
                "error" =>1,
                "description" => "请输入您的邮箱地址!"
            );
        }

        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $manager = new DatabaseManager();
        $uid = $manager->getUserIdByEmail($emailAddress);
        $password = generateRandomPassword();
        $time = date("Y-m-d", time());
        if($uid==null){
            return array(
                "error" =>1,
                "description" => "此邮箱尚未注册"
            );
        }
        if(!$manager->updatePassword($emailAddress,$password,$time)){
            return array(
                "error" =>1,
                "description" => "重置密码失败,请重试"
            );
        }

        mav_mail("BUPT-Appointment - 密码重置",
            "<p>您的临时密码为:  ". $password . "</p>"
            . "<br><br>请点击  <a href='" . getUrlWithoutParameters() . "?c=" . mav_encrypt("login") . "'>这里</a> 使用此临时密码登录系统修改密码.", array($emailAddress));


        return array(
            "error" => 0,
            "description" => "Reset password successfully! Please check your email for further details!"
        );
    }

    public function forgotPasswordDefaultAction(){
        return array(
            "error" => 0
        );
    }

}


