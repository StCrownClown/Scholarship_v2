
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

if ($model->project_id != NULL) {
    $detailHide = '';
}

if (in_array($mode, array('add', 'edit'))) {
    $btnSubmitName = $mode;
    $btnSubmitLabel = 'บันทึก / Save';
    $btnSubmitLabelBack = 'ปิด / Close';
    $urlBack = 'stem/primaryproject';
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
    'title' => 'ข้อมูลโครงการวิจัยหลัก / Primary Research Project ' . $title
));
?>


<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'primaryproject-form',
                    'action' => Yii::app()->createUrl($formAciton, array(
                        'mode' => Yii::app()->request->getQuery('mode'),
                        'prj_id' => Yii::app()->request->getQuery('prj_id'),
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
                                $addUrl = Yii::app()->createUrl('stem/primaryproject', array(
                                    'mode' => 'add'
                                ));
                                echo CHtml::link($addText, $addUrl, array(
                                    'class' => 'btn btn-default',
                                    'style' => 'display: inline;'
                                ));
                                echo '<br/><br/>';
                                echo $form->error($model, 'project_id', array(
                                    'style'=>'text-align: center;'
                                ));
                            }
                            ?>
                            <?php
                            echo $form->labelEx($model, 'project_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'StemPrimaryProjectForm[project_id]',
                                'data' => InitialData::Project(),
                                'value' => $model->project_id,
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                ),
                                'events' => array('change' => 'js:function(e) 
                                { showDetail(e.val);}'),
                            ));
                            ?>
                            <div id="loader">
                                <p>กรุณารอสักครู่ / Please wait a minute</p>
                            </div>

                        </div>
                    </div>
                    <hr/>
                    <div id="project_detail" style="<?= $detailHide ?>">
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
                                    <p>หากท่านต้องการแก้ไขโครงการหลักให้คลิ๊กที่ปุ่มนี้ / To edit, click on this button.
                                        <?php
                                        if (empty(Yii::app()->session['tmpReadOnly'])) {
                                            $addText = '<i class="icon-pencil"></i> แก้ไข / Edit';
                                            $addUrl = Yii::app()->createUrl('stem/primaryproject', array(
                                                'mode' => 'edit',
                                                'prj_id' => $model->project_id
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
                        $this->renderPartial('_view', array(
                            'form' => $form,
                            'model' => $model,
                            'mode' => $mode
                        ));
                        ?>
                    </div>
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
        var n = str.lastIndexOf("prj_id=");
        href = str.substring(0, n + '&prj_id='.length - 1);
    }
    $(document).ready(function () {
<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
        $("#loader").fadeOut(100);
        $('input[type=radio][name="StemPrimaryProjectForm[funding]"]').change(function () {
            $('#StemPrimaryProjectForm_funding_name, #StemPrimaryProjectForm_funding_code, #StemPrimaryProjectForm_funding_code_name, #StemPrimaryProjectForm_funding_etc').val('');
        });
    });

    function RadionButtonSelectedValueSet(name, SelectdValue) {
        $('input[name="StemPrimaryProjectForm[funding]"][value="' + data.funding + '"]').prop('checked', true);
    }

    function showDetail(prj_id) {
        $('#project_detail').hide();
        $("#loader").fadeIn(400);
        if (prj_id != '') {
            $.getJSON('<?php echo $this->createUrl('stem/getData'); ?>?prj_id=' + prj_id,
                    function (data) {
                        $('#StemPrimaryProjectForm_id').val(data.id);
                        $('#StemPrimaryProjectForm_name').val(data.name);
                        $('#StemPrimaryProjectForm_objective').val(data.objective);
                        $('#StemPrimaryProjectForm_scope').val(data.scope);
                        $('#StemPrimaryProjectForm_begin').val(data.begin);
                        $('#StemPrimaryProjectForm_end').val(data.end);
                        $('input[name="StemPrimaryProjectForm[funding]"][value="' + data.funding + '"]').prop('checked', true);
                        $("#StemPrimaryProjectForm_funding_name").hide();
                        $("#StemPrimaryProjectForm_funding_code, #StemPrimaryProjectForm_funding_code_name").hide();
                        $("#StemPrimaryProjectForm_funding_etc").hide();
                        if (data.funding == 'source') {
                            $("#StemPrimaryProjectForm_funding_name").show();
                        } else if (data.funding == 'nstda') {
                            $("#StemPrimaryProjectForm_funding_code, #StemPrimaryProjectForm_funding_code_name").show();
                        } else if (data.funding == 'other') {
                            $("#StemPrimaryProjectForm_funding_etc").show();
                        }
                        $('#StemPrimaryProjectForm_funding_name').val(data.funding_name);
                        $('#StemPrimaryProjectForm_funding_code').val(data.funding_code);
                        $('#StemPrimaryProjectForm_funding_code_name').val(data.funding_code_name);
                        $('#StemPrimaryProjectForm_funding_etc').val(data.funding_etc);
                        $('#StemPrimaryProjectForm_budget').val(data.budget);
                        $('#StemPrimaryProjectForm_func_period').val(data.func_period);
                        $("#btnEdit").attr('href', href + data.id)
                        $('#project_detail').show();
                        $("#loader").fadeOut(400);
                    });
        }
    }
</script>
