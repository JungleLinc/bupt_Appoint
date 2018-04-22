<?php
$adminController = mav_encrypt("admin");
$addAdvisorAction = mav_encrypt("addAdvisor");

$loginController = mav_encrypt("login");
$addDepartmentAction = mav_encrypt("addDepartment");
$addMajorAction = mav_encrypt("addMajor");
$deleteAdvisorAction = mav_encrypt("deleteAdvisor");
$setTemporaryPasswordAction = mav_encrypt("setTemporaryPassword");
$changePasswordDefaultAction = mav_encrypt("changePasswordDefault");
$logoutAction = mav_encrypt("logout");
$showDepartmentScheduleAction = mav_encrypt("showDepartmentSchedule");
$deleteTimeSlotAction = mav_encrypt("deleteTimeSlot");
$showAdvisorAssignmentAction = mav_encrypt("showAdvisorAssignment");

$feedbackController = mav_encrypt("feedback");
$getFeedbackAction = mav_encrypt("getFeedback");
?>

<div class="navbar-header">
    <a class="navbar-brand" href="<?php getUrlWithoutParameters()?>">
        <font style="font-weight:bold; color: #e67e22" size="6"> BUPT-Appointment </font>
        <font style="color: #e67e22; margin-left: 10px;" size="3">当前登录角色: 管理员</font>
    </a>
</div>

<div id="navbar">

    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <font style="color: #e67e22" size="3">学院</font>
                <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="?c=<?php echo $adminController?>&a=<?php echo $addMajorAction?>"><font size="3">添加专业</font></a></li>
                <li><a href="?c=<?php echo $adminController?>&a=<?php echo $addDepartmentAction?>"><font size="3">添加学院</font></a></li>
                <li><a href="?c=<?php echo $adminController?>&a=<?php echo $showDepartmentScheduleAction?>"><font size="3">查看学院预约信息</font></a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <font style="color: #e67e22" size="3">教师</font>
                <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="?c=<?php echo $adminController?>&a=<?php echo $addAdvisorAction?>"><font size="3">添加教师信息</font></a></li>
                <li><a href="?c=<?php echo $adminController?>&a=<?php echo $deleteAdvisorAction?>"><font size="3">删除教师信息</font></a></li>
<!--                <li><a href="?c=--><?php //echo $adminController?><!--&a=--><?php //echo $showAdvisorAssignmentAction?><!--"><font size="3">安排学生至指定教师</font></a></li>-->
            </ul>
        </li>

        <li><a href="?c=<?php echo $feedbackController?>&a=<?php echo $getFeedbackAction?>"><font style="color: #e67e22" size="3">反馈</font></a></li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <font style="color: #e67e22" size="3">设置</font>
                <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="?c=<?php echo $loginController?>&a=<?php echo $changePasswordDefaultAction?>"><font size="3">修改密码</font></a></li>
                <li class="divider"></li>
                <li><a href="?c=<?php echo $adminController?>&a=<?php echo $setTemporaryPasswordAction?>"><font size="3">临时密码</font></a></li>
            </ul>
        </li>
        <li><a href="?c=<?php echo $loginController?>&a=<?php echo $logoutAction?>"><font style="color:#e67e22" size="3">退出</font></a></li>
    </ul>

</div>
</div>
</nav>