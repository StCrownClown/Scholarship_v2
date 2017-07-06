<?php
$person_type = ConfigWeb::getActivePersonType();

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])
        || $model->status == 'confirm'
        || $model->status == 'pending_scholarships'
        || $model->status == 'pending_recommendations') {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}

$disabled = '';
$readonly = '';

$readonly_by_status = '';
if(!empty($model->email)){
    $disabled = 'disabled';
    $readonly = 'readonly';
}

if($model->status == 'confirm' 
        || $model->status == 'pending_recommendations'){
    $readonly_by_status = 'readonly';
}
?>

<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'เลือก นักวิจัย อุตสาหกรรม'
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'student-form',
                    'action' => Yii::app()->createUrl($formAciton),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                <?php echo $form->hiddenField($model, 'id', array('type' => "hidden")); ?>
           
                <?php
                if(empty($model->status) || $model->status == 'draft'){ ?>

                <?php } ?>

                <div class="form-group" id='industrial_name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'industrial_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'NuircStudentForm[industrial_id]',
                            'data' => NuircStudentForm::FindIndustrial(),
                            'value' => $model->industrial_id,
                            'options' => array(
                                'placeholder' => '--- เลือก นักวิจัยอุตสหกรรม ---',
                                'width' => '100%',
                            ),
                        ));
                        echo '<br/>';
                        ?>
                    </div>
                    
                </div>
                
                <div class="form-group ">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label><span class="required">**กรณีที่ยังไม่มีข้อมูล ให้กรอกอีเมลล์ของนักวิจัย อุตสาหกรรม</span></label>
                    </div>
                </div>
                
                <div class="form-group" id='industrial_email' >
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'email', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'readonly'=>$readonly_by_status,
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1">
                        <br/>
                        <?php // echo CHtml::button('ตรวจสอบ', array('style'=>'opacity:1;')); 
                        echo CHtml::link('ตรวจสอบ', '#', array(
                            'class' => 'btn btn-warning',
                            'style' => 'float: left;display: none;',
                            'id' => 'check',
                        ));
                        ?>
                        <img id="loading" style="display:none" src="<?php echo Yii::app()->request->baseUrl; ?>/images/loader.gif">
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label>
                            <span class="required">**ระบบจะส่ง e-mail ไปที่นักวิจัย อุตสาหกรรม เพื่อกรอกข้อมูล</span>
                        </label>
                    </div>
                </div>
                <hr>
               
            <div class="actionBar">
                <?php
                $urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
                WorkflowData::getActionBar($this, $urlBack);

                echo CHtml::submitButton('ยืนยัน / Confirm', array(
                    'name' => 'confirmemail',
                    'id' => 'confirmemail',
                    'class' => 'btn btn-warning',
                    'style' => 'display:none;'
                ));
                ?>
            </div>
            <?php
            $this->endWidget();
            unset($form);
            ?>
            </div> 
        </div>
    </div>
</div>
<script>


    function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };
    
    function disableForm(){
        var formname = "NuircStudentForm";
        $("#s2id_" + formname + "_id").addClass("select2-container-disabled");
        $("#" + formname + "_industrial_id").attr("disabled","disabled");
        $("#" + formname + "_industrial_id").attr("readonly","readonly");
    }
    
       function disableFormEmail(){
        var formname = "NuircStudentForm";
        $("#s2id_" + formname + "_id").addClass("select2-container-disabled");
        $("#" + formname + "_email").attr("disabled","disabled");
        $("#" + formname + "_email").attr("readonly","readonly");
    }
    
    function resetForm(){
        var formname = "NuircStudentForm";
    }

    (function($){
        $.isBlank = function(obj){
          return(!obj || $.trim(obj) === "");
        };
      })(jQuery);

    $(document).ready(function () {
        
    var formname = "NuircStudentForm";
        
        $("#" + formname + "_email").focus(function() {
            disableForm();
            $('#check').show();
            $("button[name='next']").hide();
        });
        
        $("#" + formname + "_email" ).focusout(function() {
           $('#check').hide();
           $("button[name='next']").show();
           email = $("#" + formname + "_email").val();

           if($.isBlank(email)){
               $("#s2id_" + formname + "_industrial_id").attr("class","select2-container");
               $("#" + formname + "_industrial_id").removeAttr("disabled");
               $("#" + formname + "_industrial_id").removeAttr("readonly");
           }
         });
  
        $("#" + formname + "_email").change(function () {
            disableForm();
            if (ValidateEmail($(this).val())) {
                    $("#loading").show();
                    disableForm();
                    $.post("isAlreadyEmail",
                        {
                            type: 'industrial',
                            email: $(this).val(),
                        },
                        function (data, status) {
                            if (data == '1') {
                                disableForm();
                                var r = confirm("อีเมล์นี้ได้ถูกลงทะเบียนไว้แล้ว หากต้องการใช้ข้อมูลเดิมนี้ กรุณายืนยัน\nThis email is already registered. To use the same information, please confirm");
                                if (r == true) {
//                                  $("#confirmemail").click();
                                    // ไป get ข้อมูลมาใส่
                                    $.post("getPersonDetail",
                                    {
                                        type: 'industrial',
                                        email: $("#" + formname + "_email").val(),
                                    }, function (data, status) {
                                        data = $.parseJSON(data);
                                        $("#s2id_" + formname + "_industrial_id").addClass("select2-container-disabled");
                                        $("#" + formname + "_industrial_id").select2("val", data.id);
                                        $("#" + formname + "_industrial_id").val(data.id).attr("readonly","readonly").attr("disabled","readonly");
                                        $("label, input").removeClass('error').removeClass('required');
                                        $("div").removeClass('has-error');
                                        $(".help-block").remove();
                                        $("#industrial_id").show();
                                    });
                                } else {
                                    $("#" + formname + "_email").val('');
                                    resetForm();
                                    $("#industrial_id").show();
                                }
                            } else if (data != '0') {
                                alert("อีเมล์นี้ได้ถูกลงทะเบียนไว้แล้ว ในประเภท " + data + "!!\nThis email is already registered in " + data + "!!");
                                $("#" + formname + "_email").val('');
                                resetForm();
                                $("#industrial_id").show();
                            }else{
                                resetForm();
                                $("#industrial_id").show();
                            }
                            $("#loading").hide();
                    });
            }
            if (!ValidateEmail($(this).val())) {
                alert("Email ไม่ถูกต้อง / Invalid email address.");
                $(this).val("");
                resetForm();
                $("#industrial_id").show();
            }
        });
        

        $("#" + formname + "_industrial_id").change(function () {
//            disableFormEmail();
                $("#loading").show();
//                disableFormEmail();
                $.post("isAlreadyID",
                    {
                        type: 'industrial',
                        industrial_id: $(this).val(),
                    },
                    function (data, status) {
                        if (data == '1') {
//                            disableFormEmail();
                            var r = confirm("หากต้องการใช้ข้อมูลเดิมนี้ กรุณายืนยัน\n To use the same information, please confirm");
                            if (r == true) {
//                                  $("#confirmemail").click();
                                // ไป get ข้อมูลมาใส่
                                $.post("getPersonEmail",
                                {
                                    type: 'industrial',
                                    industrial_id: $("#" + formname + "_industrial_id").val(),
                                }, function (data, status) {
                                    data = $.parseJSON(data);
                                    $("#s2id_" + formname + "_email").addClass("select2-container");
                                    $("#" + formname + "_email").select2("val", data.email);
                                    $("#" + formname + "_email").val(data.email).attr("readonly","readonly").attr("readonly");
                                    $("label, input").removeClass('error').removeClass('required');
                                    $("div").removeClass('has-error');
                                    $(".help-block").remove();
                                    $("#email").show();
                                });
                            } else {
                                $("#" + formname + "_industrial_id").val('');
                                resetForm();
                                $("#industrial_id").show();
                            }
                        }
                        else{
                            resetForm();
                            $("#industrial_id").show();
                        }
                        $("#loading").hide();
                });
        });

<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });
    
    
</script>
