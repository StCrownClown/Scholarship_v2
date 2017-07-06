
<div id="wrapper" style="max-width: 600px">
    <div class="login_content">
        <h1>เปลี่ยนอีเมล์ / Change Email</h1>
    </div>

    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'register-form',
        'action' => Yii::app()->createUrl('site/resetemail'),
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
    
    <?php if(empty(Yii::app()->session['tmpEmail'])) { ?>
    <div class="form-group col-md-offset-3">
        <?php
            echo $form->textFieldGroup($model, 'email_old', array(
                'wrapperHtmlOptions' => array(
                    'maxlength' => 100,
                ),
                'widgetOptions' => array(
                    'htmlOptions' => array(
                        'readonly' => 'readonly',
                    )
                )
            ));
        ?>
    </div>
    <div class="form-group col-md-offset-3">
        <?php
            echo $form->textFieldGroup($model, 'email', array(
                'wrapperHtmlOptions' => array(
                    'maxlength' => 100,
                ),
            ));
        ?>
    </div>
    <div class="form-group col-md-offset-3">
            <?php
            echo $form->textFieldGroup($model, 'confirmEmail', array(
                'wrapperHtmlOptions' => array(
                    'maxlength' => 100,
                ),
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
            <?php echo "<h3>New Email</h3><br/><h1>".Yii::app()->session['tmpEmail']."</h1><br/><br/>"; ?>
        </div>
        <div class="login_content" style="text-align: center;text-shadow: 0 0px 0 #fff;">
    <?php }  ?>
        <?php
        $addUrl = Yii::app()->createUrl(WorkflowData::$home);
        echo CHtml::link('ปิด / Close', $addUrl, array(
            'class' => 'btn btn-danger',
            'style' => $style,
        ));
        if(empty(Yii::app()->session['tmpEmail'])){ 
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

