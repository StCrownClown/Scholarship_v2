<?php
$disabled = false;
$title = 'ลงทะเบียน / Register';
$name_submit = 'สร้าง / Create';
$action = 'site/register';

if (!empty($model->id)) {
    $disabled = true;
    $title = 'ลงทะเบียนสำเร็จ / Register success';
    $name_submit = 'เข้าสู่ระบบ / Login →';
    $action = WorkflowData::$home;
    if ($model->login()) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id = " . $model->id
                . " and email = '$model->email' "
                . " and id_card = '$model->id_card' ";
        $criteria->limit = 1;
        $records = Person::model()->find($criteria);
        Yii::app()->session['user_type'] = 'user';
        Yii::app()->session['person_id'] = $records->id;
        Yii::app()->session['person_type'] = $records->type;
        Yii::app()->session['token'] = $records->token;
        Yii::app()->session['LoginByToken'] = FALSE;
    }
}
?>

<?php
$this->renderPartial('_x_title', array(
    'title' => $title
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">

            <div class="wizard_content" style="display: block;">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'register-form',
                    'action' => Yii::app()->createUrl($action),
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
                        echo $form->hiddenField($model, 'id', array('type' => "hidden"));
                        if (empty($model->id)) {
                            echo $form->labelEx($model, 'type') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'RegisterForm[type]',
                                'data' => InitialData::PERSON_TYPE(),
                                'value' => $model->type,
                                'disabled' => TRUE,
                                'options' => array(
                                    'placeholder' => '',
                                    'width' => '100%',
                                )
                            ));
                            echo $form->error($model, 'type');
                        } else {
                            echo $form->textFieldGroup($model, 'type', array(
                                'wrapperHtmlOptions' => array(),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'disabled' => TRUE,
                                        'value' => InitialData::PERSON_TYPE($model->type))
                                )
                            ));
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        if (empty($model->id)) {
                            echo $form->labelEx($model, 'nationality_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'RegisterForm[nationality_id]',
                                'data' => InitialData::NstdamasNationality(),
                                'value' => $model->nationality_id,
                                'options' => array(
                                    'placeholder' => '',
                                    'width' => '100%',
                                )
                            ));
                            echo $form->error($model, 'nationality_id');
                        } else {
                            echo $form->textFieldGroup($model, 'nationality_id', array(
                                'wrapperHtmlOptions' => array(),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'disabled' => TRUE,
                                        'value' => InitialData::NstdamasNationality($model->nationality_id),)
                                )
                            ));
                        }

//                        echo $form->select2Group($model, 'nationality_id', array(
//                            'wrapperHtmlOptions' => array(
//                                'name' => 'ForgetPasswordForm[nationality_id]',
//                                'width' => '100%',
//                            ),
//                            'widgetOptions' => array(
//                                'asDropDownList' => TRUE,
//                                'data' => InitialData::NstdamasNationality(),
//                                'value' => ($model->nationality_id != NULL) ? $model->nationality_id : '17',
//                                'disabled' => ((empty($model->id)) ? FALSE : TRUE),
//                            )
//                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'id_card', array(
                            'wrapperHtmlOptions' => array(
                                'maxlength' => 30,
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array('disabled' => ((empty($model->id)) ? FALSE : TRUE),)
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        if (empty($model->id)) {
                            echo $form->passwordFieldGroup($model, 'password', array(
                                'wrapperHtmlOptions' => array(
                                    'maxlength' => 10,
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'disabled' => ((empty($model->id)) ? FALSE : TRUE),
                                        'maxlength' => 10,)
                                )
                            ));
                            echo '<p>กรอกได้เฉพาะ a-z A-Z 0-9 @#$% ความยาว 6-10 ตัวอักษร</p>';
                        } else {
                            echo $form->textFieldGroup($model, 'password', array(
                                'wrapperHtmlOptions' => array(
                                    'maxlength' => 10,
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'disabled' => ((empty($model->id)) ? FALSE : TRUE),
                                        'value' => Yii::app()->session['tmpPass'],
                                        'maxlength' => 10,)
                                )
                            ));
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php if (empty($model->id)) { ?>
                            <?php
                            echo $form->passwordFieldGroup($model, 'confirmPassword', array(
                                'wrapperHtmlOptions' => array(
                                    'maxlength' => 10,
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'disabled' => ((empty($model->id)) ? FALSE : TRUE),
                                        'value' => Yii::app()->session['tmpPass'],
                                        'maxlength' => 10,)
                                )
                            ));
                            ?>
                            <?php
                        } else {
                            echo $form->hiddenField($model, 'confirmPassword', array('type' => "hidden"));
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'email', array(
                            'wrapperHtmlOptions' => array(
                                'maxlength' => 100,
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array('disabled' => ((empty($model->id)) ? FALSE : TRUE),)
                            )
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="actionBar">
                <?php
                $addUrl = Yii::app()->createUrl('site/preregister');
                echo CHtml::link('← ย้อนกลับ / Back', $addUrl, array(
                    'class' => 'btn btn-default',
                    'style' => 'float: left;',
                ));



                if (!empty($model->id) && !empty($model->id)) {
                    $addUrl = Yii::app()->createUrl('site/verifytoken', array(
                        'token' => $model->token,
                        'scholartype' => Yii::app()->session['scholar_type'],
                        'pass' => '1',
                    ));
                    echo CHtml::link('เข้าสู่ระบบ / Login →', $addUrl, array(
                        'class' => 'btn btn-success',
                        'style' => 'float: right;',
                    ));
                } else {
                    $this->widget('booster.widgets.TbButton', array(
                        'label' => $name_submit,
                        'buttonType' => 'submit',
                        'htmlOptions' => array(
                            'name' => 'next',
                            'class' => 'btn btn-success',
                            'style' => 'float: right;',
                        ),
                    ));
                }
                ?>
            </div>
            <?php
            $this->endWidget();
            unset($form);
            ?>
        </div>
    </div>
</div>
