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
    'title' => 'ข้อมูลเบื้องต้นบริษัท/ภาคอุตสาหกรรม / Company/Industry '
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'company-form',
                    'action' => Yii::app()->createUrl($formAciton),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                <?php echo $form->hiddenField($model, 'id', array('type' => "hidden")); ?>
                
                <?php if($readonly_by_status == 'readonly'){ ?>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label>
                                <span class="required">**หากต้องการเปลี่ยน Email ให้กลับไปที่หน้า Home และคลิ๊กที่ปุ่ม 
                                    <button class="btn btn-default btn btn-default" type="button">
                                            <span class="glyphicon glyphicon-cog"></span>
                                    </button>**
                                </span>
                            </label>
                        </div>
                    </div>
                <?php }  
                if(empty($model->status) || $model->status == 'draft'){ ?>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label>
                                <span class="required">**ระบบจะส่ง e-mail ไปที่นักศึกษาและบริษัท หลังจากที่อาจารย์กรอกข้อมูลครบทุกส่วนและ คลิก “บันทึกและส่ง"*</span>
                            </label>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'industrial', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                    'readonly'=>$readonly_by_status,
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'industrial_en', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                    'readonly'=>$readonly_by_status,
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <h3>ผู้ที่สามารถติดต่อประสานงานได้หรือ วิศวกรพี่เลี้ยง</h3>
                    </div>
                </div>
                <div class="form-group">
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
                </div>
                
                <div class="form-group" id='prefix_id'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'prefix_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'NuircCompanyForm[prefix_id]',
                            'data' => InitialData::NstdamasPrefix(),
                            'value' => $model->prefix_id,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                            'htmlOptions' => array(
//                                'disabled'=>$disabled,
//                                'readonly'=> (empty($readonly)?'':True)
				'readonly'=> ((!empty( $error ) && (empty( $model->prefix_id )))?"":(empty($readonly)?'':True)),
                            )
                        ));
                        echo $form->error($model, 'prefix_id');
                        ?>
                    </div>
                </div>

                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'fname', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                    'readonly'=>$readonly
                                )
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'lname', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
//                                    'disabled'=>$disabled,
                                    'readonly'=>$readonly
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group" id='mobile'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'mobile', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
//                                    'disabled'=>$disabled,
                                    'readonly'=>$readonly
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>

            </div>    

            <div class="actionBar">
                <?php
                $urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
                WorkflowData::getActionBar($this, $urlBack);
                
                echo CHtml::submitButton('ยืนยัน / Confirm', array(
                        'name' => 'confirmemail',
                        'id' => 'confirmemail',
                        'class' => 'btn btn-warning',
                        'style'=> 'display:none;'
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
<script>
    
    function ValidateEmail(email) {
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return expr.test(email);
    };
    
    function resetForm(){
        var formname = "NuircCompanyForm";
//        $("#" + formname + "_email").val('');
        $("#" + formname + "_prefix_id").select2("val", "");
        $("#s2id_" + formname + "_prefix_id").attr("class","select2-container");
        $("#" + formname + "_prefix_id").removeAttr("disabled").val('');
        $("#" + formname + "_prefix_id").removeAttr("readonly").val('');
        $("#" + formname + "_fname").removeAttr( "readonly").val('');
        $("#" + formname + "_lname").removeAttr( "readonly").val('');
        $("#" + formname + "_mobile").removeAttr( "readonly").val('');
        $("label, input").removeClass('error').removeClass('required');
        $("div").removeClass('has-error');
        $(".help-block").remove();
    }
    
    function disableForm(){
        var formname = "NuircCompanyForm";
        $("#s2id_" + formname + "_id").addClass("select2-container-disabled");
        $("#" + formname + "_prefix_id").attr("disabled","disabled");
        $("#" + formname + "_prefix_id").attr("readonly","readonly");
        $("#" + formname + "_fname").attr("readonly","readonly");
        $("#" + formname + "_lname").attr("readonly","readonly");
        $("#" + formname + "_mobile").attr("readonly","readonly");
    }
    
    (function($){
       $.isBlank = function(obj){
         return(!obj || $.trim(obj) === "");
       };
     })(jQuery);
     
    $(document).ready(function () {
        
        if($("#s2id_nuircStudentForm_prefix_id").hasClass( "select2-container-disabled" )){
            $("#s2id_nuircStudentForm_prefix_id").attr('class', 'select2-container');
        }
        $('input').each(function(){
            if($( this ).hasClass( "error" )){
                $( this ).removeAttr( "readonly");
            }
        });
        
        $('#company-form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) { 
                e.preventDefault();
                return false;
            }
        });
        
        var formname = "NuircCompanyForm";
        $("#" + formname + "_mobile").keypress(function(event){
            if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
                event.preventDefault(); //stop character from entering input
            }
        });
        
        $("#" + formname + "_email").focus(function() {
            disableForm();
            $('#check').show();
            $("button[name='next']").hide();
        });
        
        $("#" + formname + "_email" ).focusout(function() {
           $('#check').hide();
           $("button[name='next']").show();
           prefix_id = $("#" + formname + "_prefix_id").val();
           fname = $("#" + formname + "_fname").val();
           lname = $("#" + formname + "_lname").val();
           mobile = $("#" + formname + "_mobile").val();

           if($.isBlank(prefix_id)){
               $("#s2id_" + formname + "_prefix_id").attr("class","select2-container");
               $("#" + formname + "_prefix_id").removeAttr("disabled");
               $("#" + formname + "_prefix_id").removeAttr("readonly");
           }
           if($.isBlank(fname))
               $("#" + formname + "_fname").removeAttr( "readonly");
           if($.isBlank(lname))
               $("#" + formname + "_lname").removeAttr( "readonly");
           if($.isBlank(mobile))
               $("#" + formname + "_mobile").removeAttr( "readonly");
         });
        
        $("#" + formname + "_email").change(function () {
            disableForm();
            $("#prefix_id, #name, #mobile").hide();
            if (ValidateEmail($(this).val())) {
                    $("#loading").show();
                    $.post("isAlreadyEmail",
                        {
                            type: 'industrial',
                            email: $(this).val(),
                        },
                        function (data, status) {
                            if (data == '1') {
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
                                        $("#s2id_" + formname + "_prefix_id").addClass("select2-container-disabled");
                                        $("#" + formname + "_prefix_id").select2("val", data.prefix_id);
                                        $("#" + formname + "_prefix_id").val(data.prefix_id).attr("readonly","readonly").attr("disabled","readonly");
                                        $("#" + formname + "_fname").val(data.fname).attr('value',data.fname).attr("readonly","readonly");
                                        $("#" + formname + "_lname").val(data.lname).attr('value',data.lname).attr("readonly","readonly");
                                        $("#" + formname + "_mobile").val(data.mobile).attr('value',data.mobile).attr("readonly","readonly");
                                        $("label, input").removeClass('error').removeClass('required');
                                        $("div").removeClass('has-error');
                                        $(".help-block").remove();
                                        $("#prefix_id, #name, #mobile").show();
                                    });
                                } else {
                                    $("#" + formname + "_email").val('');
                                    resetForm();
                                    $("#prefix_id, #name, #mobile").show();
                                }
                            } else if (data != '0') {
                                alert("อีเมล์นี้ได้ถูกลงทะเบียนไว้แล้ว ในประเภท " + data + "!!\nThis email is already registered in " + data + "!!");
                                $("#" + formname + "_email").val('');
                                resetForm();
                                $("#prefix_id, #name, #mobile").show();
                            }else{
                                resetForm();
                                $("#prefix_id, #name, #mobile").show();
                            }
                            $("#loading").hide();
                    });
            }
            if (!ValidateEmail($(this).val())) {
                alert("Email ไม่ถูกต้อง / Invalid email address.");
                $(this).val("");
                resetForm();
                $("#prefix_id, #name, #mobile").show();
            }
        });
        
<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });
</script>
