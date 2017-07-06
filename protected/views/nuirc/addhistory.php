
<?php
$person_type = ConfigWeb::getActivePersonType();
?>
<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'การรับทุนอื่น / Other Scholarship'
));

$Current = Yii::app()->createUrl('nuirc/addhistory');
if(!empty($model->id)){
    $Current = Yii::app()->createUrl('nuirc/edithistory',array('id'=>$model->id));
}
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'addhistory-form',
                    'action' => $Current,
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'educationlevel_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'ScholarHistoryForm[educationlevel_id]',
                            'data' => InitialData::NstdamasEducationLevel(),
                            'value' => $model->educationlevel_id,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            )
                        ));
                        echo $form->error($model, 'educationlevel_id');
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'name', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => 'โปรดระบุ / Please specify.',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'source', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => 'โปรดระบุ / Please specify.',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php 
                        echo $form->textAreaGroup($model, 'description', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '100%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php echo $form->labelEx($model, 'begin'); ?>
                        <br/>
                        <?php
                        $this->widget('booster.widgets.TbDatePicker', array(
                            'model' => $model,
                            'attribute' => 'begin',
                            'options' => array(
                                'language' => 'en'
                            ),
                        ));
                        ?>
                        <?php echo $form->error($model, 'begin'); ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php echo $form->labelEx($model, 'end'); ?>
                        <br/>
                        <?php
                        $this->widget('booster.widgets.TbDatePicker', array(
                            'model' => $model,
                            'attribute' => 'end',
                            'options' => array(
                                'language' => 'en'
                            ),
                        ));
                        ?>
                        <?php echo $form->error($model, 'end'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'func_period', array(
                            'readonly' => true,
                            'placeholder' => '',
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'disabled' => TRUE,
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="actionBar">
                <?php
                $addUrl = Yii::app()->createUrl('nuirc/history');
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
                        'confirm' => "ยืนยันการบันทึกข้อมูลประวัติการรับทุน กรุณายืนยัน ?"
                            . "\nConfirm to save the primary research project ?",
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
    $(document).ready(function () {
        $('#ScholarHistoryForm_begin').datepicker({
            'autoclose': true,
            'orientation': "top",
            'format': 'dd/mm/yyyy',
            'viewformat': 'dd/mm/yyyy',
            'datepicker': {'language': 'en'},
            'language': 'en'
        }).on("change", function () {
            setFuncPeriod();
        });
        
        $('#ScholarHistoryForm_end').datepicker({
            'autoclose': true,
            'orientation': "top",
            'format': 'dd/mm/yyyy',
            'viewformat': 'dd/mm/yyyy',
            'datepicker': {'language': 'en'},
            'language': 'en'
        }).on("change", function () {
            setFuncPeriod();
        });
    });

    function setFuncPeriod() {
        var begin = $('#ScholarHistoryForm_begin').val();
        var end = $('#ScholarHistoryForm_end').val();
        if (begin != '' && end != '') {
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('nuirc/get_func_period') ?>',
                data: {begin: begin, end: end, mode: ''},
                type: "GET",
                success: function (data) {
                    $('#ScholarHistoryForm_func_period').val(data);
                },
                error: function () {
                    $('#ScholarHistoryForm_func_period').val('-');
                }
            });
        }
    }
</script>
