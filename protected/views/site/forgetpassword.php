
<?php
$this->renderPartial('_x_title', array(
    'title' => 'ลืมรหัสผ่าน / Fotget Password'
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'login-form',
                    'action' => Yii::app()->createUrl('site/forgetpassword'),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        echo '<ul class="flashes" style="color:#b94a48;text-align: left;">';
                        foreach (Yii::app()->user->getFlashes() as $key => $message) {
                            echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
                        }
                        echo '</ul>';
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'nationality_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'value' => ($model->nationality_id != NULL) ? $model->nationality_id : '17',
                            'name' => 'ForgetPasswordForm[nationality_id]',
                            'data' => InitialData::NstdamasNationality(),
                            'options' => array(
                                'placeholder' => '',
                                'width' => '100%',
                            )
                        ));
                        echo $form->error($model, 'nationality_id');
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'id_card', array(
                            'placeholder' => ''
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'email', array(
                            'placeholder' => ''
                        ));
                        ?>
                    </div>
                </div>

                <div class="actionBar">
                    <?php
                    $addUrl = Yii::app()->createUrl('site/login', array('scholartype' => Yii::app()->session['scholar_type']));
                    echo CHtml::link('← เข้าสู่ระบบ / Login', $addUrl, array(
                        'class' => 'btn btn-default',
                        'style' => 'float: left;',
                    ));

                    $this->widget('booster.widgets.TbButton', array(
                        'label' => 'ยืนยัน / Confirm →',
                        'buttonType' => 'submit',
                        'htmlOptions' => array(
                            'name' => 'next',
                            'class' => 'btn btn-success',
                            'style' => 'float: right;',
                            'confirm' => "คุณต้องการบันทึกและดำเนินการยังหน้าถัดไป หรือไม่?"
                            . "\nDo you want to Save & Next?",
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
</div>
