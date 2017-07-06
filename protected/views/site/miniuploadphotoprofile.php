<?php
$this->renderPartial('_x_title', array(
    'title' => 'ข้อมูลรูปถ่าย / Photo'
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <?php
                $CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'miniuploadphotoprofile-form',
                    'action' => Yii::app()->createUrl($CurrentUrl),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'name' => 'UploadImageForm',
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                    )
                ));
                ?>
                <div class="form-group login_content">
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

                <div class="form-group login_content">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php
                        if (!empty($model->image_path)) {
                            $path = Yii::app()->baseUrl . Yii::app()->params['pathUploadsView'] . $model->image_path;
                            ?> 
                            <div class="avatar-view" style="margin: 0 auto;" data-original-title="Change the avatar">
                                <img src="<?= $path . "?" . time() ?>" alt="Avatar" >
                            </div>
                        <?php } else { ?>
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user.png<?= "?" . time() ?>" width="220px" height="220px" class="thumbnail login_content">
                        <?php } ?>
                    </div>
                </div>
                <?php if(empty(Yii::app()->session['tmpReadOnly'])) { ?>
                <div class="form-group login_content">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <br/>
                        <?php
                        echo $form->fileFieldGroup($model, 'image', array(
                            'label' => false,
                            'wrapperHtmlOptions' => array(
                                'width' => '50%'
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group login_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label><span class="required">**รองรับนามสกุล jpg, jpeg ขนาดต้องไม่เกิน 1MB**</span></label>
                    </div>
                </div>
                
                <div class="form-group login_content">
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <br/>
                        <?php
                        $this->widget('booster.widgets.TbButton', array(
                            'buttonType' => 'submit',
                            'label' => 'อัพโหลด / Upload',
                            'htmlOptions' => array(
                                'name' => 'upload_photo',
                                'class' => 'btn',
                            )
                        ));
                        ?>
                    </div>
                </div>
                <?php } ?>
                <div class="actionBar">
                    <?php
                    $urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
                    WorkflowData::getActionBar($this, $urlBack);
                    ?>
                </div>
                <?php
                $this->endWidget();
                unset($form);
                ?>
            </div>
        </div>
    </div>

