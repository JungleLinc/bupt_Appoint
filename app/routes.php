<?php
//if the value is "view", return view, else return json format
return array(
    "register" => array(
        "default" => "RegisterView",
        "getMajors" => "getMajors",
        "registerStudent" => "registerStudent",
        "success" => "SuccessView"
    ),
    "login" => array(
        "default" => "LoginView",
        "test" => "testView",
        "check" => "check",
        "logout" => "IndexView",
        "changePasswordDefault" => "ChangePasswordView",
        "changePassword" => "changePassword",
        "forgotPasswordDefault" => "ForgotPasswordView",
        "forgotPassword" => "forgotPassword",
        "success" => "SuccessView"
    ),
    "index" => array(
        "default" => "IndexView"
    ),
    "admin" => array(
        "deleteAdvisor" => "DeleteAdvisorView",
        "deleteSelectAdvisor" => "deleteSelectAdvisor",
        "addMajor" => "AddMajorView",
        "addDepartment" => "AddDepartmentView",
        "createNewMajor" => "createNewMajor",
        "createNewDepartment" => "createNewDepartment",
        "setTemporaryPassword" => "SetTemporaryPasswordView",
        "setTemporaryPasswordInterval" => "setTemporaryPasswordInterval",
        "addAdvisor" => "CreateAdvisorView",
        "createNewAdvisor" => "createNewAdvisor",
        "showDepartmentSchedule" => "DepartmentScheduleView",
        "deleteTimeSlot" => "deleteTimeSlot",
        "showAdvisorAssignment" => "AssignStudentView",
        "assignStudentToAdvisor" => "assignStudentToAdvisor",
        "getStartAndEndTimeOfOriginalTimeSlot" => "getStartAndEndTimeOfOriginalTimeSlot",
        "success" => "SuccessView"
    ),
    "advisor" => array(
        "showSchedule" => "AdvisorScheduleView",
        "showAppointment" => "AppointmentView",
        "addTimeSlot" => "addTimeSlot",
        "deleteTimeSlot" => "deleteTimeSlot",
        "getStartAndEndTimeOfOriginalTimeSlot" => "getStartAndEndTimeOfOriginalTimeSlot",
        "success" => "SuccessView"

    ),
    "advising" => array(
        "getAdvisingInfo" => "AdvisingView",
        "schedule" => "ScheduleView",
        "getWaitListInfo" => "getWaitListInfo",
        "addToWaitList" => "addToWaitList",
        "success" => "SuccessView"
    ),
    "appointment" => array(
        "makeAppointment" => "makeAppointment",
        "success" => "SuccessView",
        "showAppointment" => "AppointmentView",
        "cancelAppointment" => "cancelAppointment",
        "showCanceledAppointment" => "CancellationHistoryView"
    ),
    "customizeSetting" => array(
        "showAppointmentType" => "CustomizeSettingView",
        "cutOffTime" => "cutOffTime",
        "setEmailNotifications" => "setEmailNotifications",
        "success" => "SuccessView",
        "addTypeAndDuration" => "addTypeAndDuration",
        "deleteTypeAndDuration" => "deleteTypeAndDuration",
        "changeTypeAndDuration" => "changeTypeAndDuration"
    ),
    "feedback" => array(
        "getFeedback" => "FeedbackView",
        "getFeedbackAdvisor" => "getFeedbackAdvisor",
        "addFeedback" => "addFeedback",
        "replyFeedback" => "replyFeedback"
    ),
);