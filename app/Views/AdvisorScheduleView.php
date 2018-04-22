<?php
include ("template/header.php");
if (!isset($_SESSION)) {
    session_start();
}
$role = isset($_SESSION['role']) ? $_SESSION['role'] : "visitor";
include ("template/" . $role . "_navigation.php");
$mavAppointUrl = $_SESSION['mavAppointUrl'];
$content = json_decode($content, true);
$schedules = isset($content['data']['schedules']) ? $content['data']['schedules'] : null;
$appointments = isset($content['data']['appointments']) ? $content['data']['appointments'] : null;
$successAction = mav_encrypt("success");
$getStartAndEndTimeOfOriginalTimeSlotAction = mav_encrypt("getStartAndEndTimeOfOriginalTimeSlot");
//$dispatch = isset($content['dispatch']) ? $content['dispatch'] : null;
?>

<input type="hidden" id="advisorController" value="<?php echo $advisorController?>">
<input type="hidden" id="showScheduleAction" value="<?php echo $showScheduleAction?>">
<input type="hidden" id="addTimeSlotAction" value="<?php echo $addTimeSlotAction?>">
<input type="hidden" id="deleteTimeSlotAction" value="<?php echo $deleteTimeSlotAction?>">
<input type="hidden" id="getStartAndEndTimeOfOriginalTimeSlotAction" value="<?php echo $getStartAndEndTimeOfOriginalTimeSlotAction?>">
<input type="hidden" id="successAction" value="<?php echo $successAction?>">
<input class="mavAppointUrl" type="hidden" value="<?php echo $mavAppointUrl?>"/>

<div id='calendar'></div>

<!--  begin processing schedules -->
<div class="container-fluid">
    <script>

        $(document).ready(function(){

            $('#calendar').fullCalendar({
                header:
                    {
                        left:'month,basicWeek,basicDay',
                        right: 'today, prev,next',
                        center: 'title'
                    },
                displayEventEnd :
                    {
                        month: true,
                        basicWeek: true,
                        'default' : true,
                    },
                eventMouseover: function(event,jsEvent,view){
                    $('.fc-event-inner', this).append('<div id=\"'+event.id+'\" class=\"hover-end\">'+$.fullCalendar.formatDate(event.end, 'h:mmt')+'</div>');
                },
                eventMouseout: function(event, jsEvent, view) {
                    $('#'+event.id).remove();
                },
                dayClick: function(date,jsEvent,view){
                    document.getElementById("opendate").value = date.format('YYYY-MM-DD');
                    $("#addTimeSlotModal").modal();
                },
                eventClick: function(event,element){
                    if (event.id != null){
                        $.ajax({
                            url: $(".mavAppointUrl").val(),
                            type: "post",
                            data: {
                                c : $("#advisorController").val(),
                                a : $("#getStartAndEndTimeOfOriginalTimeSlotAction").val(),
                                date : event.start.format('YYYY-MM-DD'),
                                subStartTime : event.start.format('HH:mm:ss'),
                                subEndTime : event.end.format('HH:mm:ss')
                            },
                            success: function(data){
                                var data = JSON.parse(data);

                                if (data.error == 0) {
                                    document.getElementById("StartTime2").value = data.data.originalStartTime;
                                    document.getElementById("EndTime2").value = data.data.originalEndTime;
                                    document.getElementById("pname").value = event.title;
                                    document.getElementById("Date").value = event.start.format('YYYY-MM-DD');
                                    $("#deleteTimeSlotModal").modal();
                                }else{
                                    alert("Error while fetching time slot's info. Please try again.");
                                    window.location.href = $(".mavAppointUrl").val() + "?c=" + $("#advisorController").val() + "&a=" + $("#showScheduleAction").val();
                                }
                            }
                        });
                    }
                    else{
                        updateAppt.submit();
                    }
                },
                events: [
                    <?php
                    if(count($schedules) != 0)
                    {
                    $i = 0;
                    foreach ($schedules as $schedule)
                    {?>
                    {
                        title: '<?php echo $schedule['name']?>',
                        start: '<?php echo $schedule['date'] . "T" . $schedule['startTime']?>',
                        end: '<?php echo $schedule['date'] . "T" . $schedule['endTime']?>',
                        id:<?php echo $i?>,
                        backgroundColor: 'blue'
                    }
                    <?php
                    if ($i != count($schedules) - 1 || count($appointments) != 0) {
                        echo ",";
                    }
                    $i++;
                    }
                    }

                    if (count($appointments) != 0) {
                    $i = 1;
                    foreach ($appointments as $appointment) {
                    ?>
                    {
                        title:'<?php echo $appointment['appointmentType']?>',
                        start:'<?php echo $appointment['advisingDate'] . "T" . $appointment['advisingStartTime']?>',
                        end:'<?php echo $appointment['advisingDate'] . "T" . $appointment['advisingEndTime']?>',
                        id:<?php echo -$i?>,
                        backgroundColor: 'orange'
                    }
                    <?php
                    if ($i != count($appointments)) {echo ",";}
                    }
                    }

                    ?>
                ]








            });
        });
    </script>

        <div class="modal fade" id="addTimeSlotModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addTimeSlotLabel">添加预约时间段</h4>
                    </div>
                    <div class="modal-body">
                        <label for="starttime">起始时间:</label>
                        <input type="time"
                                                                          class="form-control" name=starttime id="starttime" step="300">
                        <label for="tndtime">结束时间:</label> <input type="time"
                                                                      class="form-control" name=endtime id="endtime" step="300">
                        <label for="opendate">日期:</label> <input type="text"
                                                                   class="form-control" name=opendate id="opendate">
                        <label for="repeat">按周重复设置(次):</label>
                        <input type="text" class="form-control" name=repeat id="repeat" value="0">
                        <label
                                id="result"><font style="color: #e67e22" size="4"></label>
                    </div>
                    <div class="modal-footer">
                        <div id="advisorAddTimeSlot_loading_section" style="display:none; float:left; margin: 5px;">
                            <img id="advisorAddTimeSlot_loading_img" style="margin-bottom:5px; width:15px; height:15px;">
                            <font id="advisorAddTimeSlot_loading_text" size="3" color="black"></font>
                        </div>

                        <button id="advisorAddTimeSlotCloseButton" type="button" class="btn btn-default" data-dismiss="modal">
                            关闭
                        </button>
                        <button id="advisorAddTimeSlot" type="button" class="btn btn-primary">
                            提交
                        </button>
                    </div>

                </div>
            </div>
        </div>

    <form name=deleteTimeSlot id="delete_time_slot" action="?c=<?php echo $advisorController?>&a=<?php echo $deleteTimeSlotAction?>" method="post">
        <div class="modal fade" id="deleteTimeSlotModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="deleteTimeSlotTitle">删除预约时间段</h4>
                    </div>
                    <div class="modal-body">
                        <label for="StartTime">起始时间:</label>
                            <input type="time" class="form-control" name=StartTime2 id="StartTime2" step="300" disabled>
                        <label for="EndTime">终止时间:</label>
                            <input type="time" class="form-control" name=EndTime2 id="EndTime2" step="300" disabled>
                        <label for="Date">日期:</label>
                            <input type="date" class="form-control" name=Date id="Date" disabled>
                            <input type="hidden" name=pname id="pname">
                        <label id="result2"><font style="color: #e67e22" size="4"></font></label>
                        <label for="delete_repeat">按周重复删除(次):</label>
                            <input type="text" class="form-control" name=delete_repeat id="delete_repeat" value="0">
                        <label for="delete_reason">原因:</label>
                            <input type="text" class="form-control" name=delete_reason id="delete_reason" placeholder="删除此预约时间段的原因">
                    </div>
                    <div class="modal-footer">
                        <div id="deleteTimeSlot_loading_section" style="display:none; float:left; margin: 5px;">
                            <img id="deleteTimeSlot_loading_img" style="margin-bottom:5px; width:15px; height:15px;">
                            <font id="deleteTimeSlot_loading_text" size="3" color="black"></font>
                        </div>
                        <button id="advisorDeleteTimeSlotCloseButton" type="button" class="btn btn-default" data-dismiss="modal">
                            关闭</button>
                        <button type="button" id="advisorDeleteTimeSlot" value="submit" class="btn btn-primary"
                               >提交</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script> function FormSubmit(){
            var starttime = document.getElementById("starttime").value;
            var endtime = document.getElementById("endtime").value;
            var date = document.getElementById("opendate").value;
            var repeat = document.getElementById("repeat").value;
            var params = ('starttime='+starttime+'&endtime='+endtime+'&opendate='+date+'&repeat='+repeat);
            document.getElementById('add_time_slot').submit();
        }

    </script>
    <script>
        function validate(){
            var valid = confirm('Are you sure you want to delete?');
            if (valid == true){
                document.getElementById('delete_time_slot').submit();
            }
            else {
                return false;
            }
        }
    </script>
</div>
<style>
    #calendar {
        background-color: white;
    }
</style>
