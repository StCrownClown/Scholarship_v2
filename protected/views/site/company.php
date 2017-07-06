
<?php
$person_type = ConfigWeb::getActivePersonType();

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}

?>
<?php
$this->renderPartial('_x_title', array(
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

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'industrial', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'industrial_en', array(
                            'placeholder' => '',
                            'class' => 'form-control'));
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
                        echo $form->labelEx($model, 'prefix_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'CompanyForm[prefix_id]',
                            'data' => InitialData::NstdamasPrefix(),
                            'value' => $model->prefix_id,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            )
                        ));
                        echo $form->error($model, 'prefix_id');
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'fname', array(
                            'placeholder' => '',
                            'class' => 'form-control'));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'lname', array(
                            'placeholder' => '',
                            'class' => 'form-control'));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'mobile', array(
                            'placeholder' => '',
                            'class' => 'form-control'));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'email', array(
                            'readonly' => true,
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

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'phone', array(
                            'placeholder' => '',
                            'class' => 'form-control'));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'fax', array(
                            'placeholder' => '',
                            'class' => 'form-control'));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <label for="industrial_type_manufacture">ประเภทของการประกอบกิจการ / Type. <span class="required">*</span></label>
                    </div>
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php echo $form->checkBoxGroup($model, 'industrial_type_manufacture'); ?>
                            <?php echo $form->checkBoxGroup($model, 'industrial_type_export'); ?>
                            <?php echo $form->checkBoxGroup($model, 'industrial_type_service'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'industrial_type_description', array(
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

            <div class="actionBar">
                <?php
                $UrlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
                $addUrl = Yii::app()->createUrl($UrlBack);
                echo CHtml::link('← ย้อนกลับ / Back', $addUrl, array(
                    'class' => 'btn btn-default',
                    'style' => 'float: left;',
                ));

                $this->widget('booster.widgets.TbButton', array(
                    'label' => 'ถัดไป / Next →',
                    'buttonType' => 'submit',
                    'htmlOptions' => array(
                        'name' => 'next',
                        'style' => 'float: right;',
                        'class' => 'btn btn-success',
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

<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
        
    });
</script>
