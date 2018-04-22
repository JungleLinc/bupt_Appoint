<form method="post">
    <div class="panel-heading text-center"><h3>预约管理</h3></div>
    <input type=hidden name=cancel_button id="cancel_button">
    <input type=hidden name=edit_button id="edit_button">
    <table class="table table-striped custab">
        <thead>
        <tr>
            <th><font style="color: #000000" size="4">预约类型名称</font></th>
            <th><font style="color: #000000" size="4">时长(分钟)</font></th>
        </tr>
        </thead>

        <!-- begin processing appointments  -->
        <?php $otherDuration = 10;
        if (sizeof($typeAndDuration) != 0) {
            for ($i = 0; $i < sizeof($typeAndDuration); $i++) {
                if ($typeAndDuration[$i]['type'] == 'Other') {
                    $otherDuration = $typeAndDuration[$i]['duration'];
                } else {
                    ?>

                    <tr>
                        <td style="color: #000000; size: 10px"><?php echo $typeAndDuration[$i]['type']; ?></td>
                        <td style="color: #000000; size: 10px"><?php echo $typeAndDuration[$i]['duration']; ?></td>
                        <td>
                            <button class="btn deleteTypeAndDurationSubmit" type="button"><span
                                    class="glyphicon glyphicon-remove"></span></button>
                        </td>
                    </tr>

                    <script> function deleteapptype<?php echo $i?>() {
                            var apptype = "<?php echo $typeAndDuration[$i]['type']?>";
                            var minutes = "<?php echo $typeAndDuration[$i]['duration']?>";
                            if (validate(apptype) == true) {

                                var params = ('minutes=' + minutes + '&apptypes=' + apptype);
                                var xmlhttp;
                                xmlhttp = new XMLHttpRequest();
                                xmlhttp.onreadystatechange = function () {
                                    if (xmlhttp.readyState == 4) {
                                        if (xmlhttp.status == 200) {
                                            document.getElementById("result").innerHTML = xmlhttp.responseText;
                                        }
                                    } else {
                                        //alert(xmlhttp.readyState+" "+xmlhttp.status);
                                    }
                                }

                                xmlhttp.open("POST", "appointment_type", true);
                                xmlhttp.setRequestHeader("Content-type",
                                    "application/x-www-form-urlencoded");
                                xmlhttp.setRequestHeader("Content-length", params.length);
                                xmlhttp.setRequestHeader("Connection", "close");
                                xmlhttp.send(params);
                                //window.location.reload();
                                window.location.href = window.location;
                            }
                        }</script>
                <?php }
            }
        } ?>

        <tr>
            <td>设置默认时长(分钟)</td>
            <td><input id="duration_default" type="text" value="<?php echo $otherDuration; ?>"></td>
            <td>
                <button id="button_default_duration_submit" class="btn" type="button"><span
                        class="glyphicon glyphicon-ok"></span></button>
            </td>
        </tr>
        <!-- end processing advisors -->
    </table>
</form>

</div>
<div class="panel-footer text-center">
    <input id="addAppointmentTypeSubmit" type="submit" class="btn-lg" value="添加预约类型" href="#"
           data-toggle="modal" data-target="#addApptType">
</div>

<form method="post">
    <div class="modal fade" id="addApptType" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"></button>
                    <h4 class="modal-title" id="addApptTypeLabel">新预约类型</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="apptypes">预约类型名称:</label>
                        <input type="typeText" class="form-control" id="apptypes" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="minutes">时长 (分钟)</label> <input type="number" class="form-control"
                                                                               id="minutes"
                                                                               value="<?php echo $otherDuration; ?>"
                                                                               step="5" placeholder="">
                    </div>
                    <div>
                        <label id="result"><font style="color: #000000" size="4"></font></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="addTypeAndDuration_loading_section" style="display:none; float:left; margin: 5px;">
                        <img id="addTypeAndDuration_loading_img" style="margin-bottom:5px; width:15px; height:15px;">
                        <font id="addTypeAndDuration_loading_text" size="3"></font>
                    </div>
                    <button id="addTypeAndDurationCloseButton" type="button" class="btn btn-default"
                            data-dismiss="modal">关闭
                    </button>
                    <input id="addTypeAndDurationSubmit" class="btn btn-primary" type="submit" value="提交"
                    >
                </div>
            </div>
        </div>
    </div>
</form>

<div class="panel-body resize-body center-block">
    <form method="post">
        <div class="panel-heading text-center"><h3>设置预约截止时间(学生至少提前几小时预约)</h3></div>

        <div class="form-group">
            <label for="cutOffTimeText">截止时间 (小时):<br></label>
            <input type="text" name="cutOffTimeText" id="cutOffTimeText" value="<?php echo $cutOffTime; ?>"
                   placeholder="">
        </div>

        <div class="panel-footer text-center">
            <input id="cutOffTimeSubmit" type="submit" class="btn-lg" value="提交"/>
        </div>
    </form>
</div>