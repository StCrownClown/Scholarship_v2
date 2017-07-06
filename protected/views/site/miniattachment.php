<?php
$person_id = ConfigWeb::getActivePersonId();
$person_type = ConfigWeb::getActivePersonType();
$scholar_type = Yii::app()->session['scholar_type'];

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
?>

<?php
$this->renderPartial('_x_title', array(
    'title' => 'ข้อมูลเอกสารแนบอื่นๆ / Other Attachment'
));
?>
<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <?php
                //$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'miniattachment-form',
                    'action' => Yii::app()->createUrl($formAciton),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                    )
                ));
                ?>
                <?php
                if ($person_type == 'student') {
                    $this->renderPartial('../stem/_recommendation', array(
                        'form' => $form,
                        'model' => $modelStemRec
                    ));
                }
                ?>

                <div class="form-group ">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label><span class="required">**รองรับนามสกุล pdf, doc, docx, ppt, pptx, xls, xlxs, zip, rar ขนาดต้องไม่เกิน 5MB**</span></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo '<ul class="flashes" style="color:#b94a48;text-align: left;">';
                        foreach (Yii::app()->user->getFlashes() as $key => $message) {
                            echo '<li><div class="flash-' . $key . '">' . $message . "</div></li>\n";
                        }
                        echo '</ul>';
                        ?>
                    </div>
                </div>

                <?php if ($person_type == 'professor' || $person_type == 'mentor') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'cv_path') . " <span class='required'>*</span>";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'cv', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'cv', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->cv_path)) {
                                $link = '<i class="fa fa-download"></i>  Download CV';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'cv_path',
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'professor_mentor_attachment_project_path') . " <span class='required'>*</span>";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'professor_mentor_attachment_project', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'professor_mentor_attachment_project', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->professor_mentor_attachment_project_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Plan project';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'professor_mentor_attachment_project_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo str_replace("1.", "3.", $form->labelEx($model, 'industrial_certificate_path'))." (หากมีสามารถแนบแทนบริษัทได้)";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'industrial_certificate', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'industrial_certificate', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->industrial_certificate_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Certificate';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'industrial_certificate_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo str_replace("2.", "4.", $form->labelEx($model, 'industrial_join_path'))." (หากมีสามารถแนบแทนบริษัทได้)";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'industrial_join', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'industrial_join', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->industrial_join_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Industrial Join';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'industrial_join_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'professor_mentor_attachment_other_path');
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'professor_mentor_attachment_other', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'professor_mentor_attachment_other', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->professor_mentor_attachment_other_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Additional';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'professor_mentor_attachment_other_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                <?php } else if ($person_type == 'student') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'student_transcript_path') . " <span class='required'>*</span>";
                            echo '<br/>'
                            . '<p style="color:#b94a48;">'
                            . 'กรณีที่สมัครในระดับปริญญาโท โปรดแนบ Transcript ปริญญาโท และปริญญาตรี (ฉบับระบุวันจบการศึกษา)<br/>'
                            . 'กรณีที่สมัครในระดับปริญญาเอก โปรดแนบ Transcript ปริญญาเอก และปริญญาโท (ฉบับระบุวันจบการศึกษา)'
                            . '</p>';
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'student_transcript', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'student_transcript', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->student_transcript_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Transcript';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'student_transcript_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'copy_id_card_path') . " <span class='required'>*</span>";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'copy_id_card', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'copy_id_card', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->copy_id_card_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Copy Id Card';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'copy_id_card_path',
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'student_card_path') . " <span class='required'>*</span>";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'student_card', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'student_card', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->student_card_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Student Card';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'student_card_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'student_portfolio_path');
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'student_portfolio', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'student_portfolio', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->student_portfolio_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Portfolio';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'student_portfolio_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'student_attachment_other_path');
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'student_attachment_other', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'student_attachment_other', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->student_attachment_other_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Additional';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'student_attachment_other_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'student_attachment_other2_path');
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'student_attachment_other2', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'student_attachment_other2', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->student_attachment_other2_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Additional 2';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'student_attachment_other2_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                <?php } else if ($person_type == 'industrial') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'industrial_certificate_path') . " <span class='required'>*</span>";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'industrial_certificate', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'industrial_certificate', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->industrial_certificate_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Certificate';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'industrial_certificate_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'industrial_join_path') . " <span class='required'>*</span>";
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'industrial_join', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'industrial_join', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->industrial_join_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Industrial Join';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'industrial_join_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'industrial_attachment_other_path');
                            if (empty(Yii::app()->session['tmpReadOnly']))
                                echo CHtml::activeFileField($model, 'industrial_attachment_other', array());
                            ?>
                        </div>
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php
                                echo "<br/>";
                                echo $form->error($model, 'industrial_attachment_other', array(
                                    'class' => '',
                                    'style' => 'color:#b94a48;',
                                ));
                                ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <?php
                            if (!empty($model->industrial_attachment_other_path)) {
                                $link = '<i class="fa fa-download"></i>  Download Additional';
                                $addUrl = Yii::app()->createUrl('site/download', array(
                                    'typedw' => 'industrial_attachment_other_path',
                                    'scholar' => $scholar_type
                                ));
                                echo CHtml::link($link, $addUrl, array(
                                    'class' => 'btn btn-info',
                                    'style' => 'float: left;'
                                ));
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label><span class="required">**กรุณาตรวจสอบไฟล์เอกสารดังกล่าว หากไม่ถูกต้องกรุณาแนบเอกสารใหม่**</span></label>
                        </div>
                    </div>
                <?php } ?>
                <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                    <div class="form-group login_content">
                        <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $this->widget('booster.widgets.TbButton', array(
                                'buttonType' => 'submit',
                                'label' => 'อัพโหลด / Upload',
                                'htmlOptions' => array(
                                    'name' => 'upload',
                                    'class' => 'btn',
                                )
                            ));
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="actionBar">
                <?php
                $urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
                WorkflowData::getActionBar($this, $urlBack, FALSE);

                if (empty(Yii::app()->session['tmpReadOnly'])) {
                    if ($scholar_type == 'stem') {
                        if ($person_type == 'student') {
                            if ($model->status == Yii::app()->params['Status']['Draft']) {
                                $this->widget('booster.widgets.TbButton', array(
                                    'label' => 'บันทึกและส่ง / Save&Send',
                                    'buttonType' => 'submit',
                                    'htmlOptions' => array(
                                        'name' => 'savesend',
                                        'style' => 'float: right;',
                                        'class' => 'btn btn-success',
                                        'confirm' => "ยืนยันการบันทึกและส่ง กรุณายืนยัน ?"
                                        . "\nDo you want to Save & Send?",
                                    ),
                                ));
                                $this->widget('booster.widgets.TbButton', array(
                                    'label' => 'บันทึกร่าง / Save Draft',
                                    'buttonType' => 'submit',
                                    'htmlOptions' => array(
                                        'name' => 'draft',
                                        'style' => 'float: right;',
                                        'class' => 'btn btn-warning',
                                    ),
                                ));
                            } else if ($model->status == Yii::app()->params['Status']['Send']) {
                                $this->widget('booster.widgets.TbButton', array(
                                    'label' => 'บันทึก / Save',
                                    'buttonType' => 'submit',
                                    'htmlOptions' => array(
                                        'name' => 'save',
                                        'style' => 'float: right;',
                                        'class' => 'btn btn-warning',
                                    ),
                                ));
                            }
                        } else {
                            $this->widget('booster.widgets.TbButton', array(
                                'label' => 'ถัดไป / Next →',
                                'buttonType' => 'submit',
                                'htmlOptions' => array(
                                    'name' => 'next',
                                    'class' => 'btn btn-success',
                                    'style' => 'float: right;',
                                ),
                            ));
                        }
                    }
                } else {
                    $this->widget('booster.widgets.TbButton', array(
                        'label' => 'ถัดไป / Next →',
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
<script>
    $(document).ready(function () {

    });
</script>
