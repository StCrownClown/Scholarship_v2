
<?php
$person_type = ConfigWeb::getActivePersonType();
$scholar_id = Yii::app()->request->getQuery('id');
$utype = Yii::app()->request->getQuery('utype');
        
$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;

$disabled = '';
$readonly = '';
if(!empty($model->email)){
    $disabled = 'disabled';
    $readonly = 'readonly';
}
?>

<?php
$title = '';
if($model->type == 'student'){
    $title = 'เปลี่ยน Email นักเรียน/นักศึกษา / Change Email for Student ';
}else if($model->type == 'professor'){
    $title = 'เปลี่ยน Email อาจารย์ที่ปรึกษา / Change Email for Professor ';
}else if($model->type == 'mentor'){
    $title = 'เปลี่ยน Email นักวิจัยสวทช. / Change Email for Mentor ';
}else if($model->type == 'industrial'){
    $title = 'เปลี่ยน Email บริษัท/ภาคอุตสาหกรรม / Change Email for Company/Industry ';
}
$this->renderPartial('../site/_x_title', array(
    'title' => $title
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'changeemail-form',
                    'action' => Yii::app()->createUrl($formAciton, array('id'=>$model->scholar_id ,'utype'=>$model->type)),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                <input type="hidden" name="oldemail" id="oldemail" value="<?php echo $model->email; ?>">
                <input type="hidden" name="oldprefix_id" id="oldprefix_id" value="<?php echo $model->prefix_id; ?>">
                <input type="hidden" name="oldfname" id="oldfname" value="<?php echo $model->fname; ?>">
                <input type="hidden" name="oldlname" id="oldlname" value="<?php echo $model->lname; ?>">
                <input type="hidden" name="oldmobile" id="oldmobile" value="<?php echo $model->mobile; ?>">
                <?php echo $form->hiddenField($model, 'scholar_id', array('type' => "hidden")); ?>
                <?php echo $form->hiddenField($model, 'id', array('type' => "hidden")); ?>
                <?php echo $form->hiddenField($model, 'type', array('type' => "hidden")); ?>
                
                <?php if($model->type != 'student'){ ?>
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <h3>ผู้ที่สามารถติดต่อประสานงานได้หรือ วิศวกรพี่เลี้ยง</h3>
                    </div>
                </div>
                <?php } ?>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'email', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'disabled' => FALSE,
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
                        $datas = array();
                        if($model->type == 'student'){
                            $datas = InitialData::NstdamasPrefix();
                        }else if($model->type == 'professor'){
                            $datas = InitialData::NstdamasPrefix();
                        }else if($model->type == 'mentor'){
                            $datas = InitialData::NstdamasPrefix();
                        }else if($model->type == 'industrial'){
                            $datas = InitialData::NstdamasPrefix();
                        }
                        
                        
                        echo $form->labelEx($model, 'prefix_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'ChangeEmailForm[prefix_id]',
                            'data' => $datas,
                            'value' => $model->prefix_id,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                            'htmlOptions' => array(
				'readonly'=> ((!empty( $error ) && (empty( $model->prefix_id )))?"":(empty($readonly)?'':True)),
                            )
                        ));
                        echo $form->error($model, 'prefix_id');
                        ?>
                    </div>
                </div>

                <div class="form-group"  id='name'>
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
                $addUrl = Yii::app()->createUrl(WorkflowData::$home);
                echo CHtml::link('ปิด / Close', $addUrl, array(
                    'class' => 'btn btn-danger',
                    'style' => 'float: left;',
                ));

                $this->widget('booster.widgets.TbButton', array(
                    'label' => 'บันทึก / Save',
                    'buttonType' => 'submit',
                    'htmlOptions' => array(
                        'name' => 'next',
                        'class' => 'btn btn-success',
                        'style' => 'float: right;',
                        'confirm' => "ยืนยันการเปลี่ยน Email กรุณายืนยัน ?"
                            . "\nConfirm to save the change email ?",
                    ),
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
        var formname = "ChangeEmailForm";
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
        var formname = "ChangeEmailForm";
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
        if($("#s2id_ChangeEmailForm_prefix_id").hasClass( "select2-container-disabled" )){
            $("#s2id_ChangeEmailForm_prefix_id").attr('class', 'select2-container');
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
        
        var formname = "ChangeEmailForm";
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
                    disableForm();
                    $.post("<?php echo Yii::app()->createUrl("tgist/isAlreadyEmail") ?>",
                        {
                            type: '<?php echo $utype; ?>',
                            email: $(this).val(),
                        },
                        function (data, status) {
                            if (data == '1') {
                                var r = confirm("อีเมล์นี้ได้ถูกลงทะเบียนไว้แล้ว หากต้องการใช้ข้อมูลเดิมนี้ กรุณายืนยัน\nThis email is already registered. To use the same information, please confirm");
                                if (r == true) {
                                    // ไป get ข้อมูลมาใส่
                                    $.post("<?php echo Yii::app()->createUrl("tgist/getPersonDetail") ?>",
                                    {
                                        type: '<?php echo $utype; ?>',
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
                                alert("สามารถใช้อีเมล์นี้ได้!!\nThis email is available!!");
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
