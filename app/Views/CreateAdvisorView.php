<?php
include ("template/header.php");
$role = isset($_SESSION['role']) ? $_SESSION['role'] : "visitor";
include ("template/" . $role . "_navigation.php");
$mavAppointUrl = $_SESSION['mavAppointUrl'];
$content = json_decode($content, true);
$departments = $content['departments'];
$message = $content['message'];
$adminController = mav_encrypt("admin");
$createNewAdvisorAction = mav_encrypt("createNewAdvisor");
$successAction=mav_encrypt("success");
?>

    <style>
        .resize {
            width: 60%;
        }
        .resize-body {
            width: 80%;
        }
    </style>


    <input id="adminController" type="hidden" value="<?php echo $adminController?>"/>
    <input id="createNewAdvisorAction" type="hidden" value="<?php echo $createNewAdvisorAction?>"/>
    <input type="hidden" id="successAction" value="<?php echo $successAction?>">
    <input class="mavAppointUrl" type="hidden" value="<?php echo $mavAppointUrl?>"/>
    <div class="container">
        <!-- Panel -->
        <div class="panel panel-default resize center-block">
            <!-- Default panel contents -->
            <form action="create_advisor" method="post" name="advisor_form" onsubmit="return false;">
                <div class="panel-heading text-center"><h1>添加新教师信息</h1></div>
                <div class="panel-body resize-body center-block">

                    <div class="form-group">

                        <label for="drp_department"><font color="#0" size="4">选择学院</font></label>
                        <br>
                        <select id="drp_department" name="drp_department" class="btn btn-default btn-lg dropdown-toggle">
                            <?php
                            for($i=0; $i<count($departments);$i++){?>
                                <option value=<?php echo $departments[$i]?> >
                                    <?php echo $departments[$i]?>
                                </option>
                            <?php } ?>
                        </select>
                        <br>

                        <label for="emailAddress"><font color="#0" size="4">教师邮箱</font></label><br> <input type="text" style="width: 350px;"
                                                                  class="form-control" id="emailAdvisor" placeholder="">
                        <label for="pname"><font color="#0" size="4">教师姓名</font></label><br> <input type="text" style="width: 350px;"
                                                               class="form-control" id="pname" placeholder="">
                        <br>
                    </div>

                </div>

                <div class= "panel-footer text-center">
                    <input id="createAdvisorSubmit" type="submit" class="btn-lg" value="Submit">
                </div>
            </form>

        </div>
    </div>


<?php include("template/footer.php");?>