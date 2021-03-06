$(function(){

    $("#signIn").on("click", function (e) {

        e.preventDefault();

        var passhash = md5($("#password").val());
        console.log(passhash);
        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#loginController").val(),
                a : $("#checkAction").val(),
                email : $("#email").val(),
                password : passhash
            },
            success: function(data){
                // alert(data);
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if(data.data.validated == 0 && data.data.daysBeforetempPasswordExpired<0){
                        $("#message3").css("visibility", "visible");
                        $("#message2").css("visibility", "hidden");
                        $("#message").css("visibility", "hidden");
                    }
                    else if (data.data.validated == 0) {
                        if (data.data.lastModDate == null) alert("新用户第一次登录,请修改您的密码");
                        else alert("请修改您的临时密码");
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#loginController").val() + "&a=" + $("#changePasswordDefaultAction").val();
                    }

                    else if(data.data.daysBeforeExpired !=null && data.data.daysBeforeExpired<=14 && data.data.daysBeforeExpired>=1){
                        alert("您的密码将在 " + data.data.daysBeforeExpired + " 天后失效.\n请及时更换您的密码.");
                            // alert("The password for your account will expire in " + data.data.daysBeforeExpired + " days.\nPlease change your password.");
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#loginController").val() + "&a=" + $("#changePasswordDefaultAction").val();
                    }else if(data.data.daysBeforeExpired <=0){
                        $("#message2").css("visibility", "visible");
                        $("#message").css("visibility", "hidden");
                        $("#message3").css("visibility", "hidden");
                    } else {
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#indexController").val();
                    }
                }else{
                    $("#message").css("visibility", "visible");
                    $("#message2").css("visibility", "hidden");
                    $("#message3").css("visibility", "hidden");
                }
            }
        });
    });

    $("#forgotPassword").click(
        function(e)
        {
            e.preventDefault();
            this.disabled=true;
            $.ajax(
                {
                    url: $(".mavAppointUrl").val(),
                    type: "post",
                    data:
                        {
                            c : $("#loginController").val(),
                            a : $("#forgotPasswordAction").val(),
                            emailAddress : $("#emailAddress").val()
                        },
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.error == 1){
                            $("#forgotPasswordMessage").text(data.description).css({'color' : '#e67e22', 'font-size' : '16px'});
                            document.getElementById("forgotPassword").disabled=false;
                        }else{
                            alert(data.description);
                            window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#loginController").val();
                        }


                    }
                }
            );
        }
    );

    $('#loginForm').submit(function (event) {
        event.preventDefault();
        window.history.back();
    });

    $("#createAdvisorSubmit").on("click", function(){
        var reg = /^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$/;
        var email = $("#emailAdvisor").val();
        var pname = $("#pname").val();
        var drp_department = $("#drp_department").val();

        var emaliValue = email.replace(/(^\s*)|(\s*$)/g, "");
        var pnammeValue = pname.replace(/(^\s*)|(\s*$)/g, "");
        if(emaliValue==null || emaliValue=="" || pnammeValue==null || pnammeValue=="") {
            alert("请输入教师邮箱和教师姓名!");
        }
        else if(!reg.test(emaliValue)){
            alert("请输入有效的邮箱地址!")
        }else{
            $.ajax({
                url: $(".mavAppointUrl").val(),
                type: "post",
                data: {
                    c : $("#adminController").val(),
                    a : $("#createNewAdvisorAction").val(),
                    email : email,
                    pname : pname,
                    drp_department : drp_department
                },
                success: function(data){
                    var data1 = JSON.parse(data);
                    if (data1.error == 0) {
                        // window.location.href = "http://www.google.com";
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#successAction").val()
                            + "&nc=admin&na=addAdvisor&nt=yes";
                        // $("#addAdvisorResult").text(data1.data.message);
                    }else{
                        alert(data1.data.message);
                        $("#addAdvisorResult").text(data1.data.message);
                    }
                }
            });
        }

    });

    $(".advisorButton").on("click", function (e) {
        var advisorName = $(this).attr("value");
        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#advisingController").val() + "&a=" + $("#getAdvisingInfoAction").val() + "&pName=" + advisorName;
    });

    $("#makeAppointment").on("click", function (e) {
        e.preventDefault();

        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#appointmentController").val(),
                a : $("#makeAppointmentAction").val(),
                appointmentId : $("#appointmentId").val(),
                appointmentType : $("#appointmentType").val(),
                duration : $("#duration").val(),
                pName : $("#pName").val(),
                start : $("#start").val(),
                email : $("#email").val(),
                studentId : $("#studentId").val(),
                phoneNumber : $("#phoneNumber").val(),
                description : $("#description").val(),

            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    // alert(data.info);
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#appointmentController").val() + "&a=" + $("#successAction").val()
                        + "&nc=advising&na=getAdvisingInfo";
                }else{
                    //TODO redirect to failure page
                    alert("预约出错");
                }
            }
        });
    });


    $("#assignStudentSubmit").on("click", function (e) {
        e.preventDefault();

        var length = $("#length").val();
        var advisors = [];

        for(var i = 0; i < length; i++){
            var advisor = {};

            var degType = 0;
            $("#degree"+i+" :selected").each(function(i, selected){
                if($(selected).text() == 'Bachelors')
                    degType += 1;
                if($(selected).text() == 'Masters')
                    degType += 2;
                if($(selected).text() == 'Doctorate')
                    degType += 4;
            });

            advisor.userId = $("#userId"+i).text();
            advisor.pName = $("#pName"+i).text();
            advisor.nameLow = $("#lowRange"+i+" option:selected").val();
            advisor.nameHigh = $("#highRange"+i+" option:selected").val();
            advisor.degreeType = degType;

            var majors = "";
            $("#majors"+i+" :selected").each(function(i, selected){
                majors = majors + "," +$(selected).text();
            });

            advisor.majors = majors;
            advisors.push(advisor);
        }
        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#adminController").val(),
                a : $("#assignStudentToAdvisorAction").val(),
                advisors : JSON.stringify(advisors)
            },
            success: function(data){
                data = JSON.parse(data);
                if (data.error == 0) {
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#successAction").val()
                        + "&nc=admin&na=showAdvisorAssignment";
                }else{
                    alert("advising error");
                }
            }
        });
    });

    $("#deleteAdvisorSubmit").on("click", function (e) {
        e.preventDefault();
        if ($("input[name='advisor[]']:checked").length === 0) {
            alert('请选择要删除的导师.');
        }else {
            var id_array = new Array();
            $('input[name="advisor[]"]:checked').each(function(){
                id_array.push($(this).val());
            });
            var idstr=id_array.join(',');

            $.ajax({
                url: $(".mavAppointUrl").val(),
                type: "post",
                data: {
                    c : $("#adminController").val(),
                    a : $("#deleteSelectAdvisorAction").val(),
                    advisors : idstr
                },
                success: function(data){
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#successAction").val()
                            + "&nc=admin&na=deleteAdvisor&nt=yes";
                    }else{
                        //TODO redirect to failure page
                        alert("Delete advisor error");
                    }

                }
            });
        }

    });


    $("#addDepartmentSubmit").on("click", function (e) {
        e.preventDefault();
        var text = $("#enterDepartment").val();
        var textValue = text.replace(/(^\s*)|(\s*$)/g, "");
        if(textValue==null || textValue==""){
            alert("请输入添加学院名称!");
        }else {
            $.ajax({
                url: $(".mavAppointUrl").val(),
                type: "post",
                data: {
                    c : $("#adminController").val(),
                    a : $("#createNewDepartmentAction").val(),
                    department : text
                },
                success: function(data){
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#successAction").val()
                            + "&nc=admin&na=addDepartment&nt=yes";
                    }else{
                        //TODO redirect to failure page
                        alert("删除教师信息出错");
                    }

                }
            });

        }
    });

    $("#addMajorSubmit").on("click", function (e) {
        e.preventDefault();
        var department = $("#drp_department").val();
        var major = $("#createMajorInput").val();

        if(major==null || major==""){
            alert("请输入添加专业名称!");
        }else {
            $.ajax({
                url: $(".mavAppointUrl").val(),
                type: "post",
                data: {
                    c : $("#adminController").val(),
                    a : $("#createNewMajorAction").val(),
                    department : department,
                    major : major
                },
                success: function(data){
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#successAction").val()
                            + "&nc=admin&na=addMajor";
                    }else{
                        //TODO redirect to failure page
                        alert(data.description);
                    }
                }
            });

        }
    });

    $("#setTemporaryPasswordIntervalSubmit").on("click", function (e) {
        e.preventDefault();
        var time = $("#entertemporaryPasswordInterval").val();
        if(time==null || time==""){
            alert("输入不能为空.");
        }else if(time <=0){
            alert("非法输入.");
        }else {
            $.ajax({
                url: $(".mavAppointUrl").val(),
                type: "post",
                data: {
                    c : $("#adminController").val(),
                    a : $("#setTemporaryPasswordInterval").val(),
                    temporaryPasswordInterval : time
                },
                success: function(data){
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#successAction").val()
                            + "&nc=admin&na=setTemporaryPassword";
                    }else{
                        //TODO redirect to failure page
                        alert("Set Temporary Password Expiration Time error");
                    }

                }
            });

        }
    });


    // $(".cancelButton").click(function(){
    //     var confirmMessage = 'Are you sure you want to delete this appointment?';
    //     if (confirm(confirmMessage)) {
    //         var appointmentId = $(this).attr("value");
    //
    //         $.ajax({
    //             url: $(".mavAppointUrl").val(),
    //             type: "post",
    //             data: {
    //                 c : $("#appointmentController").val(),
    //                 a : $("#cancelAppointmentAction").val(),
    //                 appointmentId : appointmentId
    //             },
    //             success: function(data){
    //                 var data = JSON.parse(data);
    //                 if (data.error == 0) {
    //                     window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#appointmentController").val() + "&a=" + $("#successAction").val()
    //                     + "&nc=appointment&na=showAppointment";
    //                 }else{
    //                     //TODO redirect to failure page
    //                     alert("Cancel appointment error");
    //                 }
    //             }
    //         });
    //     }
    //
    // });

    $("#cancellationCloseButton1").click(function(e){
        e.preventDefault();
        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#appointmentController").val() + "&a=" + $("#showAppointmentAction").val() ;
    });
    $("#cancellationCloseButton2").click(function(e){
        e.preventDefault();
        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#appointmentController").val() + "&a=" + $("#showAppointmentAction").val() ;
    });

    $("#cancelSubmitButton").click(function(e){
        e.preventDefault();

        $("#cancellation_loading_img").attr("src", "app/Views/img/loader.gif");
        $("#cancellation_loading_text").text('Sending...');
        $("#cancellation_loading_section").show();

        $.ajax(
            {
                url: $(".mavAppointUrl").val(),
                type: "post",
                data: {
                    c: $("#appointmentController").val(),
                    a: $("#cancelAppointmentAction").val(),
                    appointmentId : $("#appointmentId").val(),
                    cancellationReason : $("#cancellationReason").val()
                    },
                    success: function (data) {
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#appointmentController").val() + "&a=" + $("#successAction").val() + "&nc=appointment&na=showAppointment";
                        }else{
                            $("#cancellation_loading_img").attr("src", "app/Views/img/wrong.png");
                            $("#cancellation_loading_text").text(data.data.errorMsg);
                        }
                    }
            }
        );

    });
            // $.ajax({
            //     url: $(".mavAppointUrl").val(),
            //     type: "post",
            //     data: {
            //         c : $("#appointmentController").val(),
            //         a : $("#cancelAppointmentAction").val(),
            //         appointmentId : appointmentId
            //     },
            //     success: function(data){
            //         var data = JSON.parse(data);
            //         if (data.error == 0) {
            //             window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#appointmentController").val() + "&a=" + $("#successAction").val()
            //                 + "&nc=appointment&na=showAppointment";
            //         }else{
            //             //TODO redirect to failure page
            //             alert("Cancel appointment error");
            //         }
            //     }
            // });


    // });




    $("#drp_department_register").change(function(){
        var department = $("#drp_department_register option:selected").text();
        //alert(department);
        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#registerController").val(),
                a : $("#getMajorsAction").val(),
                department : department
            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#drp_major').empty();
                    $.each(data.data.majors, function(key, value) {
                        $('#drp_major')
                            .append($("<option></option>")
                                //.attr("value",key)
                                .text(value));
                    });
                }else{
                    alert("Error when getting majors from department");
                }
            }
        });
    });

    $("#registerSubmit").click(function(e){
        e.preventDefault();

        var email = $("#email").val();
        var studentId = $("#studentId").val();
        var phoneNumber = $("#phoneNumber").val();
        var firstName = $("#firstName").val();
        var lastName = $("#lastName").val();

        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#registerController").val(),
                a : $("#registerStudentAction").val(),
                email : email,
                studentId : studentId,
                phoneNumber : phoneNumber,
                department : $('#drp_department_register').find(":selected").text(),
                major : $('#drp_major').find(":selected").text(),
                degree : $('#drp_degreeType').find(":selected").text(),
                firstName : firstName,
                lastName : lastName
            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#registerController").val() + "&a=" + $("#successAction").val()
                        + "&nc=login&na=default";
                }else{
                    $("#registerErrorMessage").text(data.description).css({'color' : '#e67e22', 'font-size' : '16px'});
                }
            }
        });
    });

    $("#addToWaitListSubmit").click(function(e){
        e.preventDefault();

        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#advisingController").val(),
                a : $("#addToWaitListAction").val(),
                email : $("#email").val(),
                studentId : $("#studentId").val(),
                appointmentId : $("#appointmentId").val(),
                appointmentType : $("#appType").val(),
                phoneNumber : $("#phoneNumber").val(),
                description : $("#description").val()
            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#advisingController").val() + "&a=" + $("#successAction").val()
                        + "&nc=advising&na=getAdvisingInfo";
                }else{
                    alert(data.description);
                }
            }
        });
    });

    $("#cutOffTimeSubmit").click(function(e){
        e.preventDefault();

        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#customizeSettingController").val(),
                a : $("#cutOffTimeAction").val(),
                cutOffTime : $("#cutOffTimeText").val()
            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#customizeSettingController").val() + "&a=" + $("#successAction").val();
                }else{
                    alert("无效输入.")
                }
            }
        });
    });


    $("#setEmailNotificationsSubmit").click(function(e){
        e.preventDefault();

        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#customizeSettingController").val(),
                a : $("#setEmailNotificationsAction").val(),
                notify : $("input[name=notify]:checked").val()
            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#customizeSettingController").val() + "&a=" + $("#successAction").val();
                }
            }
        });
    });

    $("#addTypeAndDurationSubmit").click(function(e){
        e.preventDefault();
        $("#addTypeAndDuration_loading_img").attr("src", "app/Views/img/loader.gif");
        $("#addTypeAndDuration_loading_text").text('Sending...');
        $("#addTypeAndDuration_loading_section").show();

        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#customizeSettingController").val(),
                a : $("#addTypeAndDurationAction").val(),
                apptypes : $("#apptypes").val(),
                minutes : $("#minutes").val(),
            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#customizeSettingController").val() + "&a=" + $("#successAction").val();
                }else{
                    $("#addTypeAndDuration_loading_img").attr("src", "app/Views/img/wrong.png");
                    $("#addTypeAndDuration_loading_text").text(data.data.errorMsg);
                }
            }
        });
    });
    $("#cancellationCloseButton1").click(function(e){
        e.preventDefault();
        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#appointmentController").val() + "&a=" + $("#showAppointmentAction").val() ;
    });

    $(".deleteTypeAndDurationSubmit").click(function(e){
        e.preventDefault();
        var confirmMessage = '你确定要删除这个预约类型吗?';
        if (confirm(confirmMessage)){
            var apptypes = $(this).parent().parent().children(":first").text();
            var minutes = $(this).parent().parent().children(":nth-child(2)").text();

            $.ajax({
                url: $(".mavAppointUrl").val(),
                type: "post",
                data: {
                    c : $("#customizeSettingController").val(),
                    a : $("#deleteTypeAndDurationAction").val(),
                    apptypes : apptypes,
                    minutes : minutes
                },
                success: function(data){
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#customizeSettingController").val() + "&a=" + $("#successAction").val();
                    }
                }
            });
        }
    });

    $("#button_default_duration_submit").click(function(e){
        e.preventDefault();
        var apptypes = "Other";
        var minutes = $("#duration_default").val();
        $.ajax({
            url: $(".mavAppointUrl").val(),
            type: "post",
            data: {
                c : $("#customizeSettingController").val(),
                a : $("#changeTypeAndDurationAction").val(),
                apptypes : apptypes,
                minutes : minutes
            },
            success: function(data){
                var data = JSON.parse(data);
                if (data.error == 0) {
                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#customizeSettingController").val() + "&a=" + $("#successAction").val();
                }else{
                    alert("Error while updating duration")
                }
            }
        });
    });

    $("#changePasswordSubmit").click(
        function(e)
        {
            e.preventDefault();
            $.ajax(
                {
                    url: $(".mavAppointUrl").val(),
                    type: "post",
                    data:
                        {
                            c : $("#loginController").val(),
                            a : $("#changePasswordAction").val(),
                            currentPassword : $("#currentPassword").val(),
                            newPassword : $("#newPassword").val(),
                            repeatPassword : $("#repeatPassword").val()
                        },
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#loginController").val() + "&a=" + $("#successAction").val() + "&nc=index&na=default";
                        }
                        else{
                            $("#changePasswordErrorMessage").text(data.description).css({'color' : '#e67e22', 'font-size' : '16px'});
                        }
                    }
                }
            );
        }
    );

    $("#advisorAddTimeSlot").click(
        function(e)
        {
            e.preventDefault();
            // alert(">>>opendata:" +$("#opendate").val()+" >>>starttime:"+$("#starttime").val()+" >>>endtime"+$("#endtime").val() +" >>>repeat:"+$("#repeat").val());
            $("#advisorAddTimeSlot_loading_img").attr("src", "app/Views/img/loader.gif");
            $("#advisorAddTimeSlot_loading_text").text('Sending...');
            $("#advisorAddTimeSlot_loading_section").show();
            $.ajax(
                {
                    url: $(".mavAppointUrl").val(),
                    type: "post",
                    data:
                        {
                            c : $("#advisorController").val(),
                            a : $("#addTimeSlotAction").val(),
                            opendate : $("#opendate").val(),
                            starttime : $("#starttime").val(),
                            endtime : $("#endtime").val(),
                            repeat : $("#repeat").val()
                        },
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#advisorController").val() + "&a=" + $("#successAction").val() + "&nc=advisor&na=showSchedule";
                        }
                        else{
                            $("#advisorAddTimeSlot_loading_img").attr("src", "app/Views/img/wrong.png");
                            $("#advisorAddTimeSlot_loading_text").text(data.data.errorMsg);
                        }
                    }
                }
            );
        }
    );
    $("#advisorAddTimeSlotCloseButton").click(function(e){
        e.preventDefault();
        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#advisorController").val() + "&a=" + $("#showScheduleAction").val() ;
    });

    $("#advisorDeleteTimeSlot").click(
        function(e)
        {
            e.preventDefault();
            $("#deleteTimeSlot_loading_img").attr("src", "app/Views/img/loader.gif");
            $("#deleteTimeSlot_loading_text").text('Sending...');
            $("#deleteTimeSlot_loading_section").show();
            $.ajax(
                {
                    url: $(".mavAppointUrl").val(),
                    type: "post",
                    data:
                        {
                            c : $("#advisorController").val(),
                            a : $("#deleteTimeSlotAction").val(),
                            StartTime2 : $("#StartTime2").val(),
                            EndTime2 : $("#EndTime2").val(),
                            Date : $("#Date").val(),
                            delete_repeat : $("#delete_repeat").val(),
                            delete_reason : $("#delete_reason").val()
                        },
                        success: function(data) {
                            var data = JSON.parse(data);
                            if (data.error == 0) {
                                window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#advisorController").val() + "&a=" + $("#successAction").val() + "&nc=advisor&na=showSchedule";
                            }
                            else{
                                $("#deleteTimeSlot_loading_img").attr("src", "app/Views/img/wrong.png");
                                $("#deleteTimeSlot_loading_text").text(data.data.errorMsg);
                                $("#deleteTimeSlot_loading_section").show();
                            }
                        }
                    }
                );



        }
    );
    $("#advisorDeleteTimeSlotCloseButton").click(function(e){
        e.preventDefault();
        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#advisorController").val() + "&a=" + $("#showScheduleAction").val() ;
    });

    $("#adminDeleteTimeSlot").click(
        function(e)
        {
            e.preventDefault();
            // alert(">>start:" + $("#StartTime2").val() + ">>end:" + $("#EndTime2").val() +">>Date:"+$("#Date").val()
            // +">>title:" +$("#title").val());
            $("#deleteTimeSlot_loading_img").attr("src", "app/Views/img/loader.gif");
            $("#deleteTimeSlot_loading_text").text('Sending...');
            $("#deleteTimeSlot_loading_section").show();
            $.ajax(
                {
                    url: $(".mavAppointUrl").val(),
                    type: "post",
                    data:
                        {
                            c : $("#adminController").val(),
                            a : $("#deleteTimeSlotAction").val(),
                            StartTime2 : $("#StartTime2").val(),
                            EndTime2 : $("#EndTime2").val(),
                            Date : $("#Date").val(),
                            delete_repeat : $("#delete_repeat").val(),
                            delete_reason : $("#delete_reason").val(),
                            title : $("#title").val(),
                            currentDept : $("#currentDept").val()
                        },
                        success: function(data) {
                            var data = JSON.parse(data);
                            if (data.error == 0) {
                                window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#successAction").val() + "&nc=admin&na=showDepartmentSchedule";
                            }
                            else{
                                $("#deleteTimeSlot_loading_img").attr("src", "app/Views/img/wrong.png");
                                $("#deleteTimeSlot_loading_text").text(data.data.errorMsg);
                                $("#deleteTimeSlot_loading_section").show();
                            }
                        }
                }
            );



        }
    );
    $("#adminDeleteTimeSlotCloseButton").click(function(e){
        e.preventDefault();
        window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#adminController").val() + "&a=" + $("#showDepartmentScheduleAction").val() ;
    });


    $("#btn_feedback").click(function(e) {
            e.preventDefault();
            $("#feedback_loading_section").hide();
            $("#feedback_title").val("");
            $("#feedback_comment").val("");
        });

    $("#drp_feedback_type").change(function(){
            var type = $("#drp_feedback_type option:selected").text();

            if(type == 'System')
                $("#drp_feedback_advisor_section").hide();
            else {

                $.ajax(
                    {
                        url: $(".mavAppointUrl").val(),
                        type: "post",
                        data:
                            {
                                c : $("#feedbackController").val(),
                                a : $("#getFeedbackAdvisorAction").val()
                            },
                        success: function(data) {
                            var data = JSON.parse(data);
                            if (data.error == 0) {
                                $("#drp_feedback_advisor").empty();

                                var pNames = data.data.advisors.pName;
                                var userIds = data.data.advisors.userId;

                                for(var i = 0; i < pNames.length; i++) {
                                    $('#drp_feedback_advisor')
                                        .append($("<option></option>")
                                            .text(pNames[i])
                                            .val(userIds[i])
                                        );
                                }
                                $("#drp_feedback_advisor_section").show();
                            }
                        }
                    }
                );

            }
        }
    );

    $("#button_feedback_submit").click(function(e){
        e.preventDefault();

        var type = $("#drp_feedback_type option:selected").text();
        var targetId;
        if(type == 'System')
            targetId = '0';
        else
            targetId = $("#drp_feedback_advisor option:selected").val();

        var title =  $("#feedback_title").val();
        var content =  $("#feedback_comment").val();

        if(title == '' || content == ''){
            $("#feedback_loading_img").attr("src", "app/Views/img/wrong.png");
            $("#feedback_loading_text").text('标题或内容不能为空');
            $("#feedback_loading_section").show();
        }else {
            $("#feedback_loading_img").attr("src", "app/Views/img/loader.gif");
            $("#feedback_loading_text").text('Processing');
            $("#feedback_loading_section").show();

            $.ajax(
                {
                    url: $(".mavAppointUrl").val(),
                    type: "post",
                    data: {
                        c: $("#feedbackController").val(),
                        a: $("#addFeedbackAction").val(),
                        type: type,
                        tid: targetId,
                        title: title,
                        content: content
                    },
                    success: function (data) {
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            setTimeout(
                                function(){
                                    $("#feedback_loading_img").attr("src", "app/Views/img/correct.png");
                                    $("#feedback_loading_text").text('Success');
                                }
                                ,1000);
                        }else{
                            $("#feedback_loading_img").attr("src", "app/Views/img/wrong.png");
                            $("#feedback_loading_text").text(data.description);
                        }
                    }
                }
            );
        }
    });

    $(".feedbackReplyBtn").click(function(e){
        var position = $(this).attr("value");
        $("#feedbackReplyPosition").val(position);
        var title = $("#feedback_title"+position).text();
        $("#feedback_reply_comment_label").text("Re : " + title);
        $("#feedback_reply_loading_section").hide();
        $("#feedback_reply_comment").val("");
    });

    $("#button_feedback_reply_submit").click(function(e){
        e.preventDefault();
        var position =  $("#feedbackReplyPosition").val();

        var uid = $("#feedbackUid"+position).val();
        var fid = $("#feedbackFid"+position).val();
        var title =  $("#feedback_reply_comment_label").text();
        var content = $("#feedback_reply_comment").val();

        if(content == ''){
            $("#feedback_reply_loading_img").attr("src", "app/Views/img/wrong.png");
            $("#feedback_reply_loading_text").text('Title or content cannot be null');
            $("#feedback_reply_loading_section").show();
        }else {
            content =
                "Following is the reply to your feedback : <br><i>" +
                $("#feedback_content"+position).text() + "</i><br><br>" +
                content;

            $("#feedback_reply_loading_img").attr("src", "app/Views/img/loader.gif");
            $("#feedback_reply_loading_text").text('Processing');
            $("#feedback_reply_loading_section").show();

            $.ajax(
                {
                    url: $(".mavAppointUrl").val(),
                    type: "post",
                    data: {
                        c: $("#feedbackController").val(),
                        a: $("#replyFeedbackAction").val(),
                        uid: uid,
                        title: title,
                        fid:fid,
                        content: content
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            setTimeout(
                                function(){
                                    $("#feedback_reply_loading_img").attr("src", "app/Views/img/correct.png");
                                    $("#feedback_reply_loading_text").text('Success');
                                }
                                ,1000);

                            $("#feedback_reply_button" + position).attr("disabled","disabled").text("Handled");
                        }else{
                            $("#feedback_reply_loading_img").attr("src", "app/Views/img/wrong.png");
                            $("#feedback_reply_loading_text").text(data.description);
                        }
                    }
                }
            );
        }
    });
});