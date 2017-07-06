
<div id="wrapper" style="max-width: 600px">
    <div class="login_content">
        <h1>เปลี่ยนรหัสผ่าน / Change Password</h1>
    </div>

    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'register-form',
        'action' => Yii::app()->createUrl('site/resetpassword'),
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-horizontal'
        )
    ));
    ?>
    <div class="login_content">
        <?php
        echo '<ul class="flashes" style="color:#b94a48;text-align: center;list-style-type: none;">';
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
        }
        echo '</ul>';
        ?>
    </div>
    
    <?php if(empty(Yii::app()->session['tmpPass'])) { ?>
    <div class="form-group col-md-offset-3">
        <?php
            echo $form->passwordFieldGroup($model, 'password', array(
                'wrapperHtmlOptions' => array(
                    'maxlength' => 10,
                ),
                'widgetOptions' => array(
                    'htmlOptions' => array(
                        'maxlength' => 10,)
                )
            ));
        ?>
        <p>กรอกได้เฉพาะ a-z A-Z 0-9 @#$% ความยาว 6-10 ตัวอักษร</p>
    </div>
    <div class="form-group col-md-offset-3">
            <?php
            echo $form->passwordFieldGroup($model, 'confirmPassword', array(
                'wrapperHtmlOptions' => array(
                    'maxlength' => 10,
                ),
                'widgetOptions' => array(
                    'htmlOptions' => array(
                        'maxlength' => 10,)
                )
            ));
            ?>
    </div>
    <div class="login_content" style="text-align: center;text-shadow: 0 0px 0 #fff;">
    <?php 
    $style = 'float: left;';
    } else { 
        $style = '';
        ?>
        <div class="form-group" style="text-align: center;text-shadow: 0 0px 0 #fff;">
            <?php echo "<h3>New Password</h3><br/><h1>".Yii::app()->session['tmpPass']."</h1><br/><br/>"; ?>
        </div>
        <div class="login_content" style="text-align: center;text-shadow: 0 0px 0 #fff;">
    <?php }  ?>
        <?php
        $addUrl = Yii::app()->createUrl(WorkflowData::$home);
        echo CHtml::link('ปิด / Close', $addUrl, array(
            'class' => 'btn btn-danger',
            'style' => $style,
        ));
        if(empty(Yii::app()->session['tmpPass'])){ 
            $this->widget('booster.widgets.TbButton', array(
                'label' => 'ยืนยัน / Confirm',
                'buttonType' => 'submit',
                'htmlOptions' => array(
                    'name' => 'confirm',
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
<script>

</script>

