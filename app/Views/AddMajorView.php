<?php
include ("template/header.php");
$role = isset($_SESSION['role']) ? $_SESSION['role'] : "visitor";
include ("template/" . $role . "_navigation.php");
$mavAppointUrl = $_SESSION['mavAppointUrl'];
$content = json_decode($content, true);
$departments = $content['data']['departments'];

$adminController = mav_encrypt("admin");
$createNewMajor = mav_encrypt("createNewMajor");
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
    <input id="createNewMajorAction" type="hidden" value="<?php echo $createNewMajor?>"/>
    <input type="hidden" id="successAction" value="<?php echo $successAction?>">
    <input class="mavAppointUrl" type="hidden" value="<?php echo $mavAppointUrl?>"/>
    <div class="container">

        <!-- Panel -->
        <div class="panel panel-default resize center-block">
            <div class="panel-heading text-center"><h1>添加专业</h1></div>
            <!-- Default panel contents -->
            <form method="post" name="major_form">
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
                        <div style="height: 20px"></div>
                        <label for="createMajor"><font color="#0" size="4">专业名称</font></label><br>
                        <input type="text" style="width: 350px;" class="form-control" id="createMajorInput" placeholder="">
                        <br>
                    </div>

                </div>

                <div class= "panel-footer text-center">
                    <input id="addMajorSubmit" type="submit" class="btn-lg" value="提交">
                </div>
            </form>

        </div>
    </div>

    <script>
        function FormSubmit(){
            var text = document.department_form.department.value;
            var textValue = text.replace(/(^\s*)|(\s*$)/g, "");
            if(textValue==null || textValue==""){
                alert("Need to enter a department");
            }else {
                document.getElementById("department_form").submit();
            }

        }
    </script>
<?php include("template/footer.php");?>