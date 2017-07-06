
<?php
$person_type = ConfigWeb::getActivePersonType();

$isWork = "style='display:none;'";
if ($model->is_work == '1') {
    $isWork = '';
}

$isWorkwithProject = "style='display:none;'";
if ($model->is_workwithproject == '1') {
    $isWorkwithProject = '';
}

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
?>

<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ข้อมูลการทำงาน (ถ้ามี) '
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'working-form',
                    'action' => Yii::app()->createUrl($formAciton),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'is_work') . ' <br/>';
                        echo $form->radioButtonList($model, 'is_work', array(
                            '0' => ' ไม่ได้ทำงาน',
                            '1' => ' ทำงานโดยได้รับค่าตอบแทนเป็นเงินเดือนประจำ'), array(
                            'labelOptions' => array('style' => 'display:inline'), // add this code
                            'separator' => '<br/>',
                        ));
                        echo $form->error($model, 'is_work');
                        echo '<br/>';
                        ?>
                    </div>
                </div>
                <br/>

                <div id="work_box" <?php echo $isWork; ?>>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'work_company', array(
                                'readonly' => true,
                                'placeholder' => '',
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                    )
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'work_position', array(
                                'readonly' => true,
                                'placeholder' => '',
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                    )
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'work_location', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => ''
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'work_phone', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => ''
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'work_fax', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => ''
                                    )
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'is_workwithproject') . ' <br/>';
                        echo $form->radioButtonList($model, 'is_workwithproject', array(
                            '0' => ' ไม่มี',
                            '1' => ' มี'), array(
                            'labelOptions' => array('style' => 'display:inline'), // add this code
                            'separator' => '<br/>',
                        ));
                        echo $form->error($model, 'is_workwithproject');
                        echo '<br/>';
                        ?>
                    </div>
                </div>
                <br/>

                <div  id="workwithproject_box" <?php echo $isWorkwithProject; ?>>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'workwithproject_text1', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => ''
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'workwithproject_text2', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => ''
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'workwithproject_text3', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => ''
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <div class="actionBar">
            <?php
            $UrlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
            $addUrl = Yii::app()->createUrl($UrlBack);
            echo CHtml::link('← ย้อนกลับ / Back', $addUrl, array(
                'class' => 'btn btn-default',
                'style' => 'float: left;'
            ));

            $this->widget('booster.widgets.TbButton', array(
                'label' => 'ถัดไป / Next →',
                'buttonType' => 'submit',
                'htmlOptions' => array(
                    'name' => 'next',
                    'class' => 'btn btn-success',
                    'style' => 'float: right;'
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
        /*$("#StemWorkingForm_work_phone").keypress(function(event){
            if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
                event.preventDefault(); //stop character from entering input
            }
        });*/
        
        $('input[name="StemWorkingForm[is_work]"]').change(function () {
            is_work = $('input[name="StemWorkingForm[is_work]"]:checked').val();
            if (is_work == '0') {
                $('#work_box').hide();
                $('#StemWorkingForm_work_company').val('');
                $('#StemWorkingForm_work_location').val('');
                $('#StemWorkingForm_work_phone').val('');
                $('#StemWorkingForm_work_fax').val('');
            } else if (is_work == '1') {
                $('#work_box').show();
                $('#StemWorkingForm_work_company').val('');
                $('#StemWorkingForm_work_location').val('');
                $('#StemWorkingForm_work_phone').val('');
                $('#StemWorkingForm_work_fax').val('');
            }
        });
        $('input[name="StemWorkingForm[is_workwithproject]"]').change(function () {
            is_workwithproject = $('input[name="StemWorkingForm[is_workwithproject]"]:checked').val();
            if (is_workwithproject == '0') {
                $('#workwithproject_box').hide();
                $('#StemWorkingForm_workwithproject_text1').val('');
                $('#StemWorkingForm_workwithproject_text2').val('');
                $('#StemWorkingForm_workwithproject_text3').val('');
            } else if (is_workwithproject == '1') {
                $('#workwithproject_box').show();
                $('#StemWorkingForm_workwithproject_text1').val('');
                $('#StemWorkingForm_workwithproject_text2').val('');
                $('#StemWorkingForm_workwithproject_text3').val('');
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
