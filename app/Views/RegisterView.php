<?php
include("template/header.php");
include("template/visitor_navigation.php");
$mavAppointUrl = $_SESSION['mavAppointUrl'];
$registerController = mav_encrypt("register");
$getMajorsAction = mav_encrypt("getMajors");
$registerStudentAction = mav_encrypt("registerStudent");

$content = json_decode($content, true);
$departments = $content['data']['departments'];
$majors = $content['data']['majors'];
$degrees = $content['data']['degrees'];
$url = $content['data']['url'];
$successAction = mav_encrypt("success");
?>

<style>
.resize {
    width: 60%;
}
.resize-body {
    width: 80%;
}
</style>


<input type="hidden" id="registerController" value="<?php echo $registerController?>" />
<input type="hidden" id="getMajorsAction" value="<?php echo $getMajorsAction?>" />
<input type="hidden" id="registerStudentAction" value="<?php echo $registerStudentAction?>" />
<input type="hidden" id="loginUrl" value="<?php echo $url?>" />
<input type="hidden" id="successAction" value="<?php echo $successAction?>" />
<input class="mavAppointUrl" type="hidden" value="<?php echo $mavAppointUrl?>"/>

<div class="container block">
    <!-- Panel -->
    <div class="panel panel-default resize center-block">
        <!-- Default panel contents -->
        <div class="panel-heading text-center"><h1>学生注册</h1></div>
        <form method="post" name="register_form">
            <div class="panel-body center-block resize-body">

                <label id="registerErrorMessage" for="message"></label>
                <div class="">
                    <div class="form-group">

                        <div>
                            <div style="float:left">
                                <label for="drp_department"><font color="#0" size="4">学院</font></label>
                                <br>
                                <select id="drp_department_register" name="drp_department_register" class="btn btn-default btn-lg dropdown-toggle">

                                    <?php
                                    foreach ($departments as $department) {
                                        echo "<option>$department</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                            <div style="margin-left:10px; float:left">
                                <label for="drp_major"><font color="#0" size="4">专业</font></label>
                                <br>
                                <select id="drp_major" name="drp_major" class="btn btn-default btn-lg dropdown-toggle">

                                    <?php
                                    foreach ($majors as $major) {
                                        echo "<option>$major</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div style="clear:both; padding-top:10px">
                            <label for="drp_degreeType"><font color="#0" size="4">学位类型</font></label>
                            <br>
                            <select id="drp_degreeType" name="drp_degreeType" class="btn btn-default btn-lg dropdown-toggle">

                                <?php
                                foreach ($degrees as $degree) {
                                    echo "<option>$degree</option>";
                                }
                                ?>

                            </select>
                        </div>

                        <div style="clear:both; padding-top:10px">
                            <div style="float:left">
                                <label for="last_name"><font color="#0" size="4">姓</font></label>
                                <br>
                                <input type="text" id="lastName" class="form-control" name=last_name>
                            </div>
                            <div style="margin-left:10px; float:left">
                                <label for="first_name"><font color="#0" size="4">名</font></label>
                                <br>
                                <input type="text" id="firstName" class="form-control" name=first_name>
                            </div>
                        </div>

                        <div style="clear:both; padding-top:10px">
                            <label for="student_Id"><font color="#0" size="4">学号</font></label>
                            <br>
                            <input type="text" id="studentId" class="form-control" name=student_Id placeholder="20xxxxxxxx">
                        </div>

                        <div style="clear:both; padding-top:10px">
                            <label for="phone_num"><font color="#0" size="4">手机号</font></label>
                            <br>
                            <input type="text" id="phoneNumber" class="form-control" name=phone_num placeholder="1xxxxxxxxxx">
                        </div>

                        <div style="clear:both; padding-top:10px">
                            <label for="emailAddress"><font color="#0" size="4">邮箱</font></label>
                            <br>
                            <input type="text" id="email" class="form-control" name=emailAddress placeholder="name@bupt.edu.cn">
                            <br>
                        </div>
                    </div>
                </div>

            </div>
            <div  class="panel-footer text-center">
                <input type="submit" id="registerSubmit" class="btn-lg" value="提交">
            </div>
        </form>
    </div>
</div>


<?php include("template/footer.php"); ?>