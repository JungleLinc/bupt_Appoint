<script type="text/javascript" src="./app/Views/js/allInPage.js"></script>

<footer>
    <?php
    $feedbackController = mav_encrypt("feedback");
    $getFeedbackAdvisorAction = mav_encrypt("getFeedbackAdvisor");
    $addFeedbackAction = mav_encrypt("addFeedback");
    if(isset($role) && $role == "student"){
        ?>

        <input id="feedbackController" type="hidden" value="<?php echo $feedbackController?>"/>
        <input id="getFeedbackAdvisorAction" type="hidden" value="<?php echo $getFeedbackAdvisorAction?>"/>
        <input id="addFeedbackAction" type="hidden" value="<?php echo $addFeedbackAction?>"/>

        <div id="feedback" style="position:fixed; bottom:0; right:0;z-index:100;">
            <button id="btn_feedback" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#feedbackModel">
                信息反馈
            </button>
        </div>
    <?php } ?>

    <div class="modal fade" id="feedbackModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        提交反馈
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="btn-group">
                        <div style="float:left;">
                            <label for="drp_feedback_type"><font color="#0" size="2">选择反馈类型</font></label>
                            <br>
                            <select id="drp_feedback_type" name="drp_feedback_type" class="btn btn-default btn-group-sm dropdown-toggle">
                                <option>system</option>
                                <option>Advising</option>
                            </select>
                        </div>

                        <div id="drp_feedback_advisor_section" style="margin-left:10px; float:left; display: none;">
                            <label for="drp_feedback_type"><font color="#0" size="2">选择教师</font></label>
                            <br>
                            <select id="drp_feedback_advisor" name="drp_feedback_advisor" class="btn btn-default btn-group-sm dropdown-toggle">
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px">
                        <label for="feedback_title">标题</label>
                        <input type="text" class="form-control" id="feedback_title">
                    </div>

                    <div class="form-group">
                        <label for="feedback_comment">内容</label>
                        <textarea style="z-index:0" class="form-control" rows="5" id="feedback_comment"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="feedback_loading_section" style="display:none; float:left; margin: 5px;">
                        <img id="feedback_loading_img" style="margin-bottom:5px; width:15px; height:15px;">
                        <font id="feedback_loading_text" size="3"></font>
                    </div>

                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        关闭
                    </button>
                    <button id="button_feedback_submit" type="button" class="btn btn-primary">
                        提交
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
</footer>
</body>

</html>

