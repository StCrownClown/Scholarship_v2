<?php
$caption = array(
    'student' => array(
        'th' => 'นักเรียน/นักศึกษา',
        'en' => 'Student',
        'background' => '#24bb1a;',
    ),
    'professor' => array(
        'th' => 'อาจารย์สถาบัน',
        'en' => 'Professor',
        'background' => '#1a78bb;',
    ),
    'mentor' => array(
        'th' => 'นักวิจัย สวทช.',
        'en' => 'Mentor',
        'background' => '#c43c35;',
    ),
    'industrial' => array(
        'th' => 'ภาคอุตสาหการ',
        'en' => 'Industrial',
        'background' => '#c435b2;',
    ),
);
Yii::app()->session['person_id'] = NULL;
$scholar_type = Yii::app()->session['scholar_type'];
$grpPreregister = Yii::app()->params['GroupPreRegister'][$scholar_type];
$col = 12 / sizeof($grpPreregister);
?>

<?php
$this->renderPartial('_x_title', array(
    'title' => 'ลงทะเบียนใหม่ / Pre Register'
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
                
                <!-- element -->
                <div class="form-grou" style="text-align: center;">
                    <?php foreach ($grpPreregister as $value) { ?>
                        <div class="col-md-<?= $col ?> col-sm-6 col-xs-12 animated fadeIn">
                            <div class="x_panel ui-ribbon-container">
                                <div class="x_content">
                                    <div style="text-align: center; margin-bottom: 17px"></div>
                                    <h3 class="name_title"><?= $caption[$value]['en'] ?></h3>
                                    <p><?= $caption[$value]['th'] ?></p>
                                    <div class="pricing_footer">
                                        <?php
                                        $addUrl = Yii::app()->createUrl('site/preregister', array(
                                            'type' => $value));
                                        echo CHtml::link('ลงทะเบียน / Register →', $addUrl, array(
                                            'class' => 'btn btn-primary btn-block',
                                            'role' => 'button',
                                            'style' => 'background:' . $caption[$value]['background'] . 'border-color:' . $caption[$value]['background'] . ''));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- end element -->
                </div>
            </div>
            <div class="actionBar">
                <?php
                $addUrl = Yii::app()->createUrl('site/login', array('scholartype' => Yii::app()->session['scholar_type']));
                echo CHtml::link('← เข้าสู่ระบบ / Login', $addUrl, array(
                    'class' => 'btn btn-default',
                    'style' => 'float: left;',
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
