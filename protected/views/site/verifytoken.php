
<div id="wrapper" style="max-width: 600px">
    <div class="login_content">
        <h1>เปลี่ยนรหัสผ่าน / Change Password</h1>
    </div>

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'verifytoken-form',
        'action' => Yii::app()->createUrl('site/verifytoken', array(
            'token' => $model->token,
            'scholarType' => Yii::app()->session['scholarType'],
        )),
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-vertical',
        )
    ));
    ?>
    <div class="col-md-offset-2">
        <?php
        echo '<ul class="flashes" style="color:#b94a48;text-align: left;">';
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
        }
        echo '</ul>';
        ?>
    </div>

    <div class="form-group col-md-offset-3">
        <?php
        echo $form->passwordFieldRow($model, 'password', array(
            'placeholder' => '',
            'class' => 'span3'));
        ?>
    </div>

    <div class="form-group col-md-offset-3">
        <?php
        echo $form->passwordFieldRow($model, 'confirmPassword', array(
            'placeholder' => '',
            'class' => 'span3'));
        ?>
    </div>

    <div class="login_content">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'submit',
            'label' => 'ยืนยัน / Confirm',
            'htmlOptions' => array(
                'class' => 'btn btn-success bt_create'
            )
        ));
        ?>
    </div>
    <?php
    $this->endWidget();
    unset($form);
    ?>
    <div class="login_content">
        <div class="clearfix"></div>
        <div class="separator">
            <p class="change_link">
                <?php $addUrl = Yii::app()->createUrl('site/login', array('scholarType' => Yii::app()->session['scholarType'])); ?>
                <?php echo CHtml::link('เข้าสู่ระบบ / Login', $addUrl); ?>
            </p>
            <?= $this->renderPartial('../site/_login_footer'); ?>
        </div>
        <!-- form -->
    </div>
</div>
<script>
    $(function () {

    });
</script>

