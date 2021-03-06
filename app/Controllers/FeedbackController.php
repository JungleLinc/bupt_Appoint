<?php

class FeedbackController
{
    public function getFeedbackAdvisorAction(){
        $_SESSION['mavAppointUrl'] = getUrlWithoutParameters();

        if (isset($_SESSION['email'])) {
            include_once ROOT . "/app/Models/db/DatabaseManager.php";
            $dbManager = new DatabaseManager();
            $advisors = $dbManager->getAdvisors();

            return array(
                "error" => 0,
                "data" => array(
                    "advisors" => $advisors
                )
            );
        }else
            return array("error" => 1);
    }

    public function addFeedbackAction() {
        if($_REQUEST['title'] == '' || $_REQUEST['content'] == '')
            return array(
                "error" => 1,
                "description" => "反馈提交失败-1"
            );

        include_once ROOT . "/app/Models/bean/Feedback.php";
        $feedback = new Feedback();
        $feedback->setUid($_SESSION['uid']);
        $feedback->setTargetId($_REQUEST['tid']);
        $feedback->setType($_REQUEST['type']);
        $feedback->setTitle($_REQUEST['title']);
        $feedback->setContent($_REQUEST['content']);
        $feedback->setIsHandle(0);

        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $dbManager = new DatabaseManager();
        if (!$dbManager->addFeedback($feedback)) {
            return array(
                "error" => 1,
                "description" => "反馈提交失败-2"
            );
        }

        return array(
            "error" => 0
        );
    }


    public function getFeedbackAction() {
        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $dbManager = new DatabaseManager();

        $user = null;
        if ($_SESSION['role'] == 'advisor') {
            $user = $dbManager->getAdvisor($_SESSION['email']);
        } else if ($_SESSION['role'] == 'admin') {
            $user = $dbManager->getAdmin($_SESSION['email']);
        } else
            return array("error" => 1);

        $feedback = $dbManager->getFeedback($user->getUserId(), $user->getRole());

        $tempFeedback = array();
        foreach ($feedback as $f) {
            /** @var Feedback $f */
            array_push($tempFeedback, array(
                "fid" => $f->getFid(),
                "title" => $f->getTitle(),
                "content" => $f->getContent(),
                "isHandled" => $f->getIsHandle(),
                "uid" => $f->getUid()
//                "type" => $appointment->getAdvisingEndTime(),
            ));
        }

        return array(
            "error" => 0,
            "data" => array(
                "feedback" => $tempFeedback
            )
        );
    }

    public function replyFeedbackAction() {
        $fid = $_REQUEST['fid'];
        $uid = $_REQUEST['uid'];
        $title = $_REQUEST['title'];
        $content = $_REQUEST['content'];

        if($title == '' || $content == '' || $fid == '' || $uid == '')
            return array(
                "error" => 1,
                "description" => "内容不能为空"
            );

        include_once ROOT . "/app/Models/db/DatabaseManager.php";
        $dbManager = new DatabaseManager();

        $role = $_SESSION['role'];
        $replier = null;
        if ($role == 'advisor') {
            $replier = $dbManager->getAdvisor($_SESSION['email'])->getPName();
        } else if ($role == 'admin') {
            $replier = 'Admin';
        } else
            return array('error' => 1);
        $replyee = $dbManager->getUserById($uid);

        $res = mav_mail(
            $title,
            $content . "<br><br>" . "Best,<br>" .  $replier,
            array($replyee->getEmail()));

        if ($res) {
            return array(
                "error" => 1,
                "description" => "回复出错,请重试."
            );
        }

        if (!$dbManager->updateFeedBack($fid,1)) {
            return array(
                "error" => 1,
                "description" => "更新数据库失败,请重试"
            );
        }

        return array(
            "error" => 0
        );
    }
}