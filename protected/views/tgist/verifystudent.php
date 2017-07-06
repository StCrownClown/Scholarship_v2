<?php

$person_type = ConfigWeb::getActivePersonType();
$person_id = ConfigWeb::getActivePersonId();
$title = '';
$btnSubmitName = 'next';
$btnSubmitLabel = 'ถัดไป / Next →';
$btnSubmitLabelBack = '← ย้อนกลับ / Back';
$urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
$btnSubmitColorBack = 'default';
$detailHide = 'display:none;';
$mode = Yii::app()->request->getQuery('mode');

if ($model->project_id != NULL) {
	$detailHide = '';
}

if (in_array($mode, array('add', 'edit'))) {
	$btnSubmitName = $mode;
	$btnSubmitLabel = 'บันทึก / Save';
	$btnSubmitLabelBack = 'ปิด / Close';
	$urlBack = 'tgist/primaryproject';
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
			'confirm' => "ยืนยันการบันทึกข้อมูลโครงการวิจัยหลัก กรุณายืนยัน ?"
			. "\nConfirm to save the primary research project ?",
	);
} else if ($mode == 'edit') {
	$title = '(แก้ไข / Edit)';
	$htmlOptions = array(
			'name' => $btnSubmitName,
			'style' => 'float: right;',
			'class' => 'btn btn-success',
			'confirm' => "ยืนยันการบันทึกข้อมูลโครงการวิจัยหลัก กรุณายืนยัน ?"
			. "\nConfirm to save the primary research project ?",
	);
}

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
?>

<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'รับรอง นักเรียน/นักศึกษา '
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
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
                
                <?php if ($person_type == 'professor') { ?>

		<div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'professor_accord_nstda') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'NuircStudentProjectForm[professor_accord_nstda]',
                            'data' => array(
                                            'คลัสเตอร์เกษตรและอาหาร' => ' คลัสเตอร์เกษตรและอาหาร',
                                            'คลัสเตอร์พลังงานและสิ่งแวดล้อม' => ' คลัสเตอร์พลังงานและสิ่งแวดล้อม',
                                            'คลัสเตอร์สุขภาพและการแพทย์' => ' คลัสเตอร์สุขภาพและการแพทย์',
                                            'คลัสเตอร์ทรัพยากร ชุมชน และผู้ด้อยโอกาส' => ' คลัสเตอร์ทรัพยากร ชุมชน และผู้ด้อยโอกาส',
                                            'คลัสเตอร์อุตสาหกรรมการผลิตและบริการ' => ' คลัสเตอร์อุตสาหกรรมการผลิตและบริการ',
                                            'Cross Cutting Technology Program' => ' Cross Cutting Technology Program'),
                            'value' => $model->professor_accord_nstda,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),

                        ));
                        echo $form->error($model, 'professor_accord_nstda');
                        ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'professor_nstda_cooperation', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '50%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>
                
		<?php } ?>
			
		<?php if ($person_type == 'mentor') { ?>

		<div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'mentor_accord_nstda') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'TgistStudentProjectForm[mentor_accord_nstda]',
                            'data' => array(
                                            'คลัสเตอร์เกษตรและอาหาร' => ' คลัสเตอร์เกษตรและอาหาร',
                                            'คลัสเตอร์พลังงานและสิ่งแวดล้อม' => ' คลัสเตอร์พลังงานและสิ่งแวดล้อม',
                                            'คลัสเตอร์สุขภาพและการแพทย์' => ' คลัสเตอร์สุขภาพและการแพทย์',
                                            'คลัสเตอร์ทรัพยากร ชุมชน และผู้ด้อยโอกาส' => ' คลัสเตอร์ทรัพยากร ชุมชน และผู้ด้อยโอกาส',
                                            'คลัสเตอร์อุตสาหกรรมการผลิตและบริการ' => ' คลัสเตอร์อุตสาหกรรมการผลิตและบริการ',
                                            'Cross Cutting Technology Program' => ' Cross Cutting Technology Program'),
                            'value' => $model->mentor_accord_nstda,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),

                        ));
                        echo $form->error($model, 'mentor_accord_nstda');
                        ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'mentor_nstda_cooperation', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '50%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>
			
		<?php } ?>
			
            </div>    

            <div class="actionBar">
                <?php
                    $addUrl = Yii::app()->createUrl($urlBack);
                    echo CHtml::link($btnSubmitLabelBack, $addUrl, array(
                        'class' => 'btn btn-' . $btnSubmitColorBack,
                        'style' => 'float: left;',
                    ));

                    $this->widget('booster.widgets.TbButton', array(
                        'label' => $btnSubmitLabel,
                        'buttonType' => 'submit',
                        'htmlOptions' => $htmlOptions,
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
    $(document).ready(function () {
        
        var formname = "TgistStudentProjectForm";
        
        $("#" + formname + "_project_name_en").keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z._ -]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        
        $("#" + formname + "_project_name_th").keypress(function (e) {
            var regex = new RegExp("^[ก-๙._ -]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });

        $("#" + formname + "_knowledge, #" + formname + "_new_process, #" + formname + "_new_technology, #" + formname + "_project_prototype_ind, #" + formname + "_project_prototype_gnd, #" + formname + "_project_prototype_lab").keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        
        $("#" + formname + "_academic_article_nat, #" + formname + "_academic_article_int, #" + formname + "_project_conference_nat, #" + formname + "_project_conference_int, #" + formname + "_final_report, #" + formname + "_technical_paper").keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        
        $('#project_commercial').hide();
        $('input[name="TgistStudentProjectForm[project_commercial]"]').change(function () {
            project_commercial = $('input[name="TgistStudentProjectForm[project_commercial]"]:checked').val();
            if (project_commercial == '0') {
                $('#project_commercial').hide();
                $('#TgistStudentProjectForm_project_commercial').val('');
            } else if (project_commercial == '1') {
                $('#project_commercial').show();
                $('#TgistStudentProjectForm_project_commercial').val('');
            }
        });
        
        $('#project_public').hide();
        $('input[name="TgistStudentProjectForm[project_public]"]').change(function () {
            project_public = $('input[name="TgistStudentProjectForm[project_public]"]:checked').val();
            if (project_public == '0') {
                $('#project_public').hide();
                $('#TgistStudentProjectForm_project_public').val('');
            } else if (project_public == '1') {
                $('#project_public').show();
                $('#TgistStudentProjectForm_project_public').val('');
            }
        });

        var $focused = $(':focus');
    });
    
    

</script>
