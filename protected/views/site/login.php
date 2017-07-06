<?php
$this->pageTitle = Yii::app()->name . ' - Login';
$scholar_type = Yii::app()->session['scholar_type'];
if (empty($scholar_type))
    $scholar_type = strtolower(Yii::app()->request->getQuery('scholartype'));
Yii::app()->session['scholar_type'] = strtolower($scholar_type);
?>

<div id="wrapper" class="animated fadeIn" style="margin-top:1%;margin-bottom:1%;">
    
    <div class="login_content animated flipInX" style="border:0;background:none;width:auto;" >
        <b>
            <span class="site_title" style="font-size: 30pt;color: #5A738E;font-weight: bolder;" >
                <?php echo InitialData::FullNameScholar(Yii::app()->session['scholar_type']); ?>
            </span>
        </b>
    </div>
    
    <div class="login_content">
        <h1>เข้าสู่ระบบ / Login</h1>
    </div>

    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'login-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'class' => 'form-vertical'
        )
    ));
    ?>
    <div class="col-md-offset-2">
        <?php
        echo '<ul class="flashes" text-align: left;">';
        foreach (Yii::app()->user->getFlashes() as $key => $message) {
            echo '<li><div style="color:#b94a48; class="flash-' . $key . '">' . $message . "</div></li>\n";
        }
        echo '</ul>';
        ?>

    </div>

    <div class="form-group">
        <?php
        echo $form->textFieldGroup($model, 'username', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'width' => '100%',
                    'placeholder' => '',
                )
            )
        ));
        ?>
    </div>

    <div class="form-group">
        <?php
        echo $form->passwordFieldGroup($model, 'password', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'width' => '100%',
                    'placeholder' => '',
                )
            )
        ));
        ?>
    </div>

    <div class="login_content">
        <?php
        $this->widget('booster.widgets.TbButton', array(
            'buttonType' => 'submit',
            'label' => 'เข้าระบบ / Login',
            'htmlOptions' => array(
                'class' => 'btn btn-default submit'
            )
        ));
        
        echo '<br/>';                
        ?>
        <?php echo CHtml::link('ผู้ดูแลระบบ / Administrator', Yii::app()->params['adminUrlSSO']."?scholartype=".$scholar_type, array('class' => 'reset_pass')); ?>&nbsp;&nbsp;|&nbsp;&nbsp;
        <?php echo CHtml::link('ลืมรหัสผ่าน / Forget Password', array('site/forgetpassword'), array('class' => 'reset_pass')); ?>
    </div>
    <?php
    $this->endWidget();
    unset($form);
    ?>
    <div class="login_content">
        <div class="clearfix"></div>
        <div class="separator">
            <p class="change_link">
                <?php echo CHtml::link('ลงทะเบียนใหม่ / Register', array('site/preregister')); ?>
            </p>
        </div>
        <!-- form -->
    </div>
    <!-- content -->

</div>