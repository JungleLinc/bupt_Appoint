<?php
$loginController = mav_encrypt("login");
$logoutAction = mav_encrypt("logout");
$advisorController = mav_encrypt("advisor");
$showScheduleAction = mav_encrypt("showSchedule");
$addTimeSlotAction = mav_encrypt("addTimeSlot");
$deleteTimeSlotAction = mav_encrypt("deleteTimeSlot");
$customizeSettingController = mav_encrypt("customizeSetting");
$showAppointmentTypeAction = mav_encrypt("showAppointmentType");
$changePasswordDefaultAction = mav_encrypt("changePasswordDefault");
$showAppointmentAction = mav_encrypt("showAppointment");
$feedbackController = mav_encrypt("feedback");
$getFeedbackAction = mav_encrypt("getFeedback");
$appointmentController = mav_encrypt("appointment");
$showCanceledAppointmentAction = mav_encrypt("showCanceledAppointment");
?>

<div class="navbar-header">
    <a class="navbar-brand" href="<?php getUrlWithoutParameters()?>">
        <font style="font-weight:bold; color: #e67e22" size="6"> BUPT-Appointment </font>
        <font style="color: #e67e22; margin-left: 10px;" size="3">当前登录角色: 教师</font>
    </a>
</div>

<div>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="?c=<?php echo $advisorController?>&a=<?php echo $showScheduleAction?>"><font style="color: #e67e22" size="3">
                    设置预约时间</font> </a></li>
        <li><a href="?c=<?php echo $appointmentController?>&a=<?php echo $showAppointmentAction?>"><font style="color: #e67e22" size="3">
                    查看预约信息</font> </a></li>

        <li><a href="?c=<?php echo $feedbackController?>&a=<?php echo $getFeedbackAction?>"><font style="color: #e67e22" size="3">
                    反馈</font></a></li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <font style="color: #e67e22" size="3">设置</font>
                <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="?c=<?php echo $loginController?>&a=<?php echo $changePasswordDefaultAction?>"><font size="3">修改密码</font></a></li>
                <li><a href="?c=<?php echo $customizeSettingController?>&a=<?php echo $showAppointmentTypeAction?>"><font size="3">个人设置</font></a></li>
            </ul>
        </li>

        <li><a href="?c=<?php echo $loginController?>&a=<?php echo $logoutAction?>"><font style="color:#e67e22" size="3">退出</font></a></li>
    </ul>

</div>
</div>
</nav>