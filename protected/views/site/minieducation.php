
<?php
$person_type = ConfigWeb::getActivePersonType();
$person_id  = ConfigWeb::getActivePersonId();
$title = '';
$btnSubmitName = 'next';
$btnSubmitLabel = 'ถัดไป / Next →';
$btnSubmitLabelBack = '← ย้อนกลับ / Back';
$urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
$btnSubmitColorBack = 'default';
$detailHide = 'display:none;';

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
if ($model->education_id != NULL) {
    $detailHide = '';
}

if (in_array($mode, array('add', 'edit'))) {
    $btnSubmitName = $mode;
    $btnSubmitLabel = 'บันทึก / Save';
    $btnSubmitLabelBack = 'ปิด / Close';
    $urlBack = $CurrentUrl;
    $btnSubmitColorBack = 'danger';
}


$htmlOptions = array(
    'name' => $btnSubmitName,
    'style' => 'float: right;',
    'class' => 'btn btn-success',
);

if ($mode == 'add') {
    $title = '(สร้าง / Create)';
    $htmlOptions = array(
        'name' => $btnSubmitName,
        'style' => 'float: right;',
        'class' => 'btn btn-success',
        'confirm' => "ยืนยันการบันทึกข้อมูลการศึกษา กรุณายืนยัน ?"
        . "\nConfirm to save the education ?",
    );
} else if ($mode == 'edit') {
    $title = '(แก้ไข / Edit)';
    $htmlOptions = array(
        'name' => $btnSubmitName,
        'style' => 'float: right;',
        'class' => 'btn btn-success',
        'confirm' => "ยืนยันการบันทึกข้อมูลการศึกษา กรุณายืนยัน ?"
        . "\nConfirm to save the education ?",
    );
}
?>
<?php
$title_msg = 'ข้อมูลการศึกษาสูงสุด / Highest Education';
if ($person_type == 'student') {
    $title_msg = 'ข้อมูลการศึกษาที่ต้องการสมัคร / Education';
}
$this->renderPartial('_x_title', array(
    'title' => $title_msg
));
?>

<div class="x_content">
    <div class="row">
        <div class="wizard_content" style="display: block;">
            <?php
            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id' => 'minieducation-form',
                'action' => Yii::app()->createUrl($formAciton, array(
                    'mode' => Yii::app()->request->getQuery('mode'),
                    'edu_id' => Yii::app()->request->getQuery('edu_id'),
                )),
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'class' => 'form-horizontal'
                )
            ));
            ?>

            <?php if (!in_array($mode, array('add', 'edit'))) { ?>
                <div class="form-group login_content">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        if (empty(Yii::app()->session['tmpReadOnly'])) {
                            $addText = '<i class="icon-plus"></i> สร้าง / Create';
                            $addUrl = Yii::app()->createUrl('site/minieducation', array(
                                'mode' => 'add'
                            ));
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn btn-default',
                                'style' => 'display: inline;text-align: center;'
                            ));
                            echo '<br/><br/>';
                            echo $form->error($model, 'education_id', array(
//                                'style'=>'text-align: center;'
                            ));
                        }
                        ?>
                        <?php
                        echo $form->labelEx($model, 'education_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'EducationForm[education_id]',
                            'data' => InitialData::Education(),
                            'value' => $model->education_id,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                            'events' => array('change' => 'js:function(e) 
                                { showDetail(e.val);}'),
                        ));
                        ?>
                    </div>
                </div>
            
            <?php 
            $style = 'display:none;';
            $label_student_before_gpa = '<b id="lbl_lv"></b> <span class="required">*</span>';
            
            if($model->educationlevel_id == 13 || $model->educationlevel_id == 14){
                $style = '';
                if($model->educationlevel_id == 13){
                    $label_student_before_gpa = '<b id="lbl_lv">ปริญญาตรี</b> <span class="required">*</span>';
                }else if($model->educationlevel_id == 14){
                    $label_student_before_gpa = '<b id="lbl_lv">ปริญญาโท</b> <span class="required">*</span>';
                }
            }
            ?>
            <?php if ($person_type == 'student') { ?>
                <div class="form-group login_content" id="student_before_gpa" style="<?php echo $style;?>">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-3 col-sm-12 col-xs-12" style="text-align: left;">
                        <?php
                        
                        echo $form->labelEx($model, 'student_before_gpa', array()) . $label_student_before_gpa. '<br/>';
                        echo $form->textField($model, 'student_before_gpa', array(
                            'class' => 'form-control',
                            'width' => '50%',
                            'placeholder' => '',
                        ));
                        echo $form->error($model, 'student_before_gpa', array(
//                                'style'=>'text-align: center;'
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group login_content" id="loader" style="display:none;">
                    <br/>
                    <p>กรุณารอสักครู่ / Please wait a minute</p>
                </div>
            <?php } ?>
                <hr/>
                <div id="education_detail" style="<?= $detailHide ?>">
                    <div class="form-group login_content">
                        <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h3>รายละเอียด / Information</h3>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group login_content">
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <p>หากท่านต้องการแก้ไขข้อมูลการศึกษาให้คลิ๊กที่ปุ่มนี้ / To edit, click on this button.
                                    <?php
                                    if (empty(Yii::app()->session['tmpReadOnly'])) {
                                        $addText = '<i class="icon-pencil"></i> แก้ไข / Edit';
                                        $addUrl = Yii::app()->createUrl($CurrentUrl, array(
                                            'mode' => 'edit',
                                            'edu_id' => $model->education_id
                                        ));
                                        echo CHtml::link($addText, $addUrl, array(
                                            'id' => 'btnEdit',
                                            'class' => 'btn btn-default'
                                        ));
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>

                    <?php
                    $this->renderPartial('_education', array(
                        'form' => $form,
                        'model' => $model,
                        'mode' => $mode
                    ));
                    ?>
                </div>
            </div>  
            <div class="actionBar">
                <?php
                WorkflowData::getActionBar($this, $urlBack, TRUE,
                    array(
                        'class' => 'btn btn-' . $btnSubmitColorBack,
                        'style' => 'float: left;',
                    ),
                    array(
                        'label' => $btnSubmitLabel,
                        'buttonType' => 'submit',
                        'htmlOptions' => $htmlOptions,
                    )
                );
                
//                $addUrl = Yii::app()->createUrl($urlBack);
//                echo CHtml::link($btnSubmitLabelBack, $addUrl, array(
//                    'class' => 'btn btn-' . $btnSubmitColorBack,
//                    'style' => 'float: left;',
//                ));
//
//                $this->widget('booster.widgets.TbButton', array(
//                    'label' => $btnSubmitLabel,
//                    'buttonType' => 'submit',
//                    'htmlOptions' => $htmlOptions,
//                ));
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
    var href = '#';
    var str = $("#btnEdit").attr('href');
    if (str != undefined) {
        var n = str.lastIndexOf("edu_id=");
        href = str.substring(0, n + '&edu_id='.length - 1);
    }

    $(document).ready(function () {
        $("#loader").fadeOut(100);
        var lbl_class = $("label[for='EducationForm_student_before_gpa']").attr('class');
        if(lbl_class == 'error'){
            $('#lbl_lv').attr('style','color:#b94a48;');
            strError = $('#EducationForm_student_before_gpa').next().text();
            strErrorNew = strError.replace("เกรดเฉลี่ย", "เกรดเฉลี่ย"+$('#lbl_lv').text());
            $('#EducationForm_student_before_gpa').next().text(strErrorNew);
        }
        
<?php if ($person_type == 'student') { ?>
            $('input[name="EducationForm[status]"]').change(function () {
                status = $('input[name="EducationForm[status]"]:checked').val();
                if (status == 'studying') {
                    $('#graduated').hide();
                    $('#EducationForm_month_graduated').val('');
                    $('#EducationForm_year_graduated').val('');
                    $("#EducationForm_month_graduated").select2("val", "");
                    $("#EducationForm_year_graduated").select2("val", "");
                } else if (status == 'complete') {
                    $('#graduated').show();
                    $('#EducationForm_month_graduated').val('');
                    $('#EducationForm_year_graduated').val('');
                    $("#EducationForm_month_graduated").select2("val", "");
                    $("#EducationForm_year_graduated").select2("val", "");
                }
            });
<?php } ?>

<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });

    function showDetail(edu_id) {
        $('#education_detail').hide();
        $("#loader").fadeIn(400);
        if (edu_id != '') {
            $.getJSON('<?php echo $this->createUrl('site/getDataEducation'); ?>?edu_id=' + edu_id,
                    function (data) {
                        $('#EducationForm_id').val(data.id);
                        $('#EducationForm_educationlevel_id').val(data.educationlevel_id);
                        $('#EducationForm_educationlevel_id').select2("val", data.educationlevel_id);
                        
                        lbl_class = $("label[for='EducationForm_student_before_gpa']").attr('class');
                        if(data.educationlevel_id == 13){
                            $('#lbl_lv').text('ปริญญาตรี');
                            $('#EducationForm_student_before_gpa').val('');
                            $('#student_before_gpa').show();
                            if(lbl_class == 'error'){
                                strError = $('#EducationForm_student_before_gpa').next().text();
                                strErrorNew = strError.replace("เกรดเฉลี่ยปริญญาโท", "เกรดเฉลี่ยปริญญาตรี");
                                $('#EducationForm_student_before_gpa').next().text(strErrorNew);
                            }
                        }else if(data.educationlevel_id == 14){
                            $('#lbl_lv').text('ปริญญาโท');
                            $('#EducationForm_student_before_gpa').val('');
                            $('#student_before_gpa').show();
                            if(lbl_class == 'error'){
                                strError = $('#EducationForm_student_before_gpa').next().text();
                                strErrorNew = strError.replace("เกรดเฉลี่ยปริญญาตรี", "เกรดเฉลี่ยปริญญาโท");
                                $('#EducationForm_student_before_gpa').next().text(strErrorNew);
                            }
                        }else{
                            $('#EducationForm_student_before_gpa').val('');
                            $('#student_before_gpa').hide();
                            if(lbl_class == 'error'){
                                $('#EducationForm_student_before_gpa').next().text('');
                            }
                        }
                        $('#EducationForm_country_id').val(data.country_id);
                        $('#EducationForm_country_id').select2("val", data.country_id);
                        
                        $('#EducationForm_institute_id').val(data.institute_id);
                        $('#EducationForm_institute_id').select2("val", data.institute_id);
                        $('#EducationForm_institute_other').val(data.institute_other);
                        if(data.institute_other != '')
                            $("#box_institute_other").show();
                        else
                            $("#box_institute_other").hide();
                        
                        $('#EducationForm_faculty_id').val(data.faculty_id);
                        $('#EducationForm_faculty_id').select2("val", data.faculty_id);
                        $('#EducationForm_faculty_other').val(data.faculty_other);
                        if(data.faculty_other != '')
                            $("#box_faculty_other").show();
                        else
                            $("#box_faculty_other").hide();
                        
                        $('#EducationForm_major_id').val(data.major_id);
                        $('#EducationForm_major_id').select2("val", data.major_id);
                        $('#EducationForm_major_other').val(data.major_other);
                        if(data.major_other != '')
                            $("#box_major_other").show();
                        else
                            $("#box_major_other").hide();
                        
                        $('#EducationForm_month_enrolled').select2("val", data.month_enrolled);
                        $('#EducationForm_year_enrolled').select2("val", data.year_enrolled);
                        $('#EducationForm_month_graduated').select2("val", data.month_graduated);
                        $('#EducationForm_year_year_graduated').select2("val", data.year_year_graduated);
                        $('#EducationForm_avg_gpa').val(data.avg_gpa);
                        console.log("!"+data.status+"!");
                        <?php if ($person_type == 'student') { ?>
                                var $radios = $('input:radio[name="EducationForm[status]"]');
                                    $radios.filter('[value='+data.status+']').prop('checked', true);
                                
                                if (data.status == 'studying') {
                                    $('#graduated').hide();
                                    $('#EducationForm_month_graduated').val('');
                                    $('#EducationForm_year_graduated').val('');
                                    $("#EducationForm_month_graduated").select2("val", "");
                                    $("#EducationForm_year_graduated").select2("val", "");
                                } else if (data.status == 'complete') {
                                    $('#graduated').show();
                                    $('#EducationForm_month_graduated').val('');
                                    $('#EducationForm_year_graduated').val('');
                                    $("#EducationForm_month_graduated").select2("val", "");
                                    $("#EducationForm_year_graduated").select2("val", "");
                                }
                        <?php } ?>
                        $("#btnEdit").attr('href', href + data.id)
                        $('#education_detail').show();
                        $("#loader").fadeOut(400);
                    });
        }
    }
</script>
