<?php
$person_type = ConfigWeb::getActivePersonType();

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
?>

<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ข้อมูลโครงการวิจัยย่อยของนักเรียน/นักศึกษา / Research Project for Student '
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'student-form',
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
                        echo $form->textFieldGroup($model, 'project_name', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php echo $form->labelEx($model, 'project_begin'); ?>
                        <br/>
                        <?php
                        $this->widget('booster.widgets.TbDatePicker', array(
                            'model' => $model,
                            'attribute' => 'project_begin',
                            'options' => array(
                                'language' => 'en',
                            ),
                            'htmlOptions' => array(
                                'placeholder' => '',
                            )
                        ));
                        echo $form->error($model, 'project_begin');
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php echo $form->labelEx($model, 'project_end'); ?>
                        <br/>
                        <?php
                        $this->widget('booster.widgets.TbDatePicker', array(
                            'model' => $model,
                            'attribute' => 'project_end',
                            'options' => array(
                                'language' => 'en',
                            ),
                            'htmlOptions' => array(
                                'placeholder' => '',
                            )
                        ));
                        echo $form->error($model, 'project_end');
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'func_period', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                    'disabled' => 'disabled',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'objective', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '100%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'expect', array(
                            'style' => 'text-align: left;'
                        ));
                        echo $form->textAreaGroup($model, 'expect', array(
                            'label' => false,
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '100%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label><span class="required">**แผนการดำเนินงานของโครงการย่อย (โปรดดาวโหลดแผนการดำเนินงานและแนบกลับมา ที่หน้า 8 Attachment)**</span></label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php 
                        echo $form->labelEx($model, 'cooperation', array(
                            'style' => 'text-align: left;'
                        ));
                        echo $form->textAreaGroup($model, 'cooperation', array(
                            'label' => false,
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '100%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label for="StemStudentProjectForm_effect_new">ผลกระทบต่อหน่วยงานอุตสาหกรรม/บริษัทในทางด้านเศรษฐกิจ สังคม หรืออื่นๆ    (ตอบได้มากกว่า 1 ข้อ) /The impact on the industrial units / companies in economic, social or other (more than 1). <span class="required">*</span></label>
                        <?php echo $form->error($model, 'effect_new'); ?>
                    </div>
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php echo $form->checkBox($model, 'effect_new'); ?>
                            <?php echo $form->labelEx($model, 'effect_new'); ?>
                            <?php echo $form->labelEx($model, 'effect_new_desc', array(
                                    'style'=> (($model->effect_new == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'effect_new_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->effect_new == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'effect_cost'); ?>
                            <?php echo $form->labelEx($model, 'effect_cost'); ?>
                            <?php echo $form->labelEx($model, 'effect_cost_desc', array(
                                    'style'=> (($model->effect_cost == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'effect_cost_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->effect_cost == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'effect_quality'); ?>
                            <?php echo $form->labelEx($model, 'effect_quality'); ?>
                            <?php echo $form->labelEx($model, 'effect_quality_desc', array(
                                    'style'=> (($model->effect_quality == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'effect_quality_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->effect_quality == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'effect_environment'); ?>
                            <?php echo $form->labelEx($model, 'effect_environment'); ?>
                            <?php echo $form->labelEx($model, 'effect_environment_desc', array(
                                    'style'=> (($model->effect_environment == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'effect_environment_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->effect_environment == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'effect_other'); ?>
                            <?php echo $form->labelEx($model, 'effect_other'); ?>
                            <?php
                            echo $form->textField($model, 'effect_other_text', array(
                                'placeholder' => ' อื่นๆ โปรดระบุ',
                                'style' => (empty($model->effect_other) ? 'display:none;' : 'display: inline;'),
                                'class' => 'col-md-offset-0'
                            ));
                            echo $form->error($model, 'effect_other_text');
                            ?>
                            <?php echo $form->labelEx($model, 'effect_other_desc', array(
                                    'style'=> (($model->effect_other == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'effect_other_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->effect_other == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label for="StemStudentProjectForm_relevance_automotive">ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม/Relevance of the research projects with industry. <span class="required">*</span></label>
                        <?php echo $form->error($model, 'relevance_automotive'); ?>
                    </div>
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php echo $form->checkBox($model, 'relevance_automotive'); ?>
                            <?php echo $form->labelEx($model, 'relevance_automotive'); ?>
                            <?php echo $form->labelEx($model, 'relevance_automotive_desc', array(
                                    'style'=> (($model->relevance_automotive == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_automotive_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_automotive == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_electronics'); ?>
                            <?php echo $form->labelEx($model, 'relevance_electronics'); ?>
                            <?php echo $form->labelEx($model, 'relevance_electronics_desc', array(
                                    'style'=> (($model->relevance_electronics == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_electronics_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_electronics == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_tourism'); ?>
                            <?php echo $form->labelEx($model, 'relevance_tourism'); ?>
                            <?php echo $form->labelEx($model, 'relevance_tourism_desc', array(
                                    'style'=> (($model->relevance_tourism == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_tourism_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_tourism == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_agriculture'); ?>
                            <?php echo $form->labelEx($model, 'relevance_agriculture'); ?>
                            <?php echo $form->labelEx($model, 'relevance_agriculture_desc', array(
                                    'style'=> (($model->relevance_agriculture == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_agriculture_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_agriculture == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_food'); ?>
                            <?php echo $form->labelEx($model, 'relevance_food'); ?>
                            <?php echo $form->labelEx($model, 'relevance_food_desc', array(
                                    'style'=> (($model->relevance_food == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_food_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_food == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_robotics'); ?>
                            <?php echo $form->labelEx($model, 'relevance_robotics'); ?>
                            <?php echo $form->labelEx($model, 'relevance_robotics_desc', array(
                                    'style'=> (($model->relevance_robotics == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_robotics_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_robotics == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_aviation'); ?>
                            <?php echo $form->labelEx($model, 'relevance_aviation'); ?>
                            <?php echo $form->labelEx($model, 'relevance_aviation_desc', array(
                                    'style'=> (($model->relevance_aviation == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_aviation_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_aviation == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_biofuels'); ?>
                            <?php echo $form->labelEx($model, 'relevance_biofuels'); ?>
                            <?php echo $form->labelEx($model, 'relevance_biofuels_desc', array(
                                    'style'=> (($model->relevance_biofuels == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_biofuels_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_biofuels == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_digital'); ?>
                            <?php echo $form->labelEx($model, 'relevance_digital'); ?>
                            <?php echo $form->labelEx($model, 'relevance_digital_desc', array(
                                    'style'=> (($model->relevance_digital == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_digital_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_digital == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                            <?php echo $form->checkBox($model, 'relevance_medical'); ?>
                            <?php echo $form->labelEx($model, 'relevance_medical'); ?>
                            <?php echo $form->labelEx($model, 'relevance_medical_desc', array(
                                    'style'=> (($model->relevance_medical == '1') ? '' : 'display:none;'),
                                  )); ?>
                            <?php 
                            echo $form->textAreaGroup($model, 'relevance_medical_desc', array(
                                'labelOptions' => array("label" => false),
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                        'style'=> (($model->relevance_medical == '1') ? '' : 'display:none;'),
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                <?php if ($person_type == 'professor') { ?>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="StemStudentProjectForm_itap">ขอทุนผ่านช่องทาง / Channel</label>
                        </div>
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1">
                                <?php echo $form->checkBox($model, 'itap'); ?>
                                <?php echo $form->labelEx($model, 'itap'); ?>
                            </div>
                        </div>
                    </div>
                <?php } else if ($person_type == 'mentor') { 
                    $sbox = '';
                    if(empty($model->mentor_has_professor) || $model->mentor_has_professor == '0')
                        $sbox = 'display:none;'
                    ?>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label for="StemStudentProjectForm_mentor_has_professor"><?php echo $form->labelEx($model, 'mentor_has_professor') . ' <br/>'; ?></label>
                        </div>
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php
                            echo $form->radioButtonList($model, 'mentor_has_professor', array(
                                '1' => ' มี',
                                '0' => ' ไม่มี'), array(
                                'labelOptions' => array('style' => 'display:inline'), // add this code
                                'separator' => '<br/>',
                            ));
                            echo $form->error($model, 'mentor_has_professor');
                            echo '<br/>';
                            ?>
                        </div>
                    </div>
                    <div id="mentor_has_professor_box" style="<?php echo $sbox; ?>">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <?php
                                echo $form->labelEx($model, 'mentor_has_professor_prefix_id') . '<br/>';
                                $this->widget('booster.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'model' => $model,
                                    'name' => 'StemStudentProjectForm[mentor_has_professor_prefix_id]',
                                    'data' => InitialData::NstdamasPrefix(),
                                    'value' => $model->mentor_has_professor_prefix_id,
                                    'options' => array(
                                        'placeholder' => '--- เลือก ---',
                                        'width' => '100%',
                                    )
                                ));
                                echo $form->error($model, 'mentor_has_professor_prefix_id');
                                ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'mentor_has_professor_fname', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'mentor_has_professor_lname', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                            
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'mentor_has_professor_mobile', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'mentor_has_professor_email', array(
                                'readonly' => true,
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
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
                            // Institute in thailand only
                            $datasInstitute = InitialData::NstdamasInstituteByCountry(187);
                            if(Yii::app()->session['scholar_type'] == 'stem'){
                                $datasInstitute = InitialData::MasterInstituteStem();
                            }
                            
                            echo $form->labelEx($model, 'mentor_has_professor_institute_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'StemStudentProjectForm[mentor_has_professor_institute_id]',
                                'data' => $datasInstitute,
                                'value' => $model->mentor_has_professor_institute_id,
                                'events' => array('change' => 'js:function(e) { 
                                        if(e.val==0){
                                            $("#StemStudentProjectForm_mentor_has_professor_institute_other").val("");
                                            $("#box_institute_other").show();
                                        }else{
                                            $("#StemStudentProjectForm_mentor_has_professor_institute_other").val("");
                                            $("#box_institute_other").hide(); 
                                        }
                                    }'),
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                )
                            ));
                            echo $form->error($model, 'mentor_has_professor_institute_id');
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12" id="box_institute_other" <?= ConfigWeb::setDefaultDisplay($model->mentor_has_professor_institute_id) ?>>
                            <?php
                            echo $form->textFieldGroup($model, 'mentor_has_professor_institute_other', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => 'โปรดระบุ / Please specify.',
                                    )
                                )
                            ));
                            ?>
                        </div>
                    </div>
                            
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'mentor_has_professor_faculty_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'StemStudentProjectForm[mentor_has_professor_faculty_id]',
                                'data' => InitialData::NstdamasFaculty(),
                                'value' => $model->mentor_has_professor_faculty_id,
                                'events' => array('change' => 'js:function(e) { 
                                        if(e.val==0){
                                            $("#StemStudentProjectForm_mentor_has_professor_faculty_other").val("");
                                            $("#box_faculty_other").show();
                                        }else{
                                            $("#StemStudentProjectForm_mentor_has_professor_faculty_other").val("");
                                            $("#box_faculty_other").hide(); 
                                        }
                                    }'),
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                )
                            ));
                            echo $form->error($model, 'mentor_has_professor_faculty_id');
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12" id="box_faculty_other" <?= ConfigWeb::setDefaultDisplay($model->mentor_has_professor_faculty_id) ?>>
                            <?php
                            echo $form->textFieldGroup($model, 'mentor_has_professor_faculty_other', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => 'โปรดระบุ / Please specify.',
                                        'width' => '100%',
                                    )
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'mentor_has_professor_relation', array(
                                'style' => 'text-align: left;'
                            ));
                            echo $form->textAreaGroup($model, 'mentor_has_professor_relation', array(
                                'label' => false,
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                    
                </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h3>ประมาณค่าใช้จ่ายตลอดโครงการย่อย / Estimated Project Cost</h3>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <br/>
                        <div class="animated fadeIn tile_stats_count">
                            <div class="right">
                                <span class="count_top"><i class="fa fa-money"></i> IN-CASH (บาท)</span>
                                <div class="count" id="total_incash"><?php echo number_format($model->incash_sum, 2, '.', ','); ?></div>
                            </div>
                        </div>
                        
                        <!--<h4 id="total_incash">IN-CASH ( <?php // echo number_format($model->incash_sum, 2, '.', ','); ?> บาท)</h4>-->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php echo $form->checkBox($model, 'incash_fee'); ?>
                        <?php echo $form->labelEx($model, 'incash_fee_cost'); ?>
                    </div>
                </div>
                
                <div class="form-group" id="incash_fee_source" style="<?php echo (empty($model->incash_fee) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo '<label id="incash_fee_cost_label"  style="'.(empty($model->incash_fee) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'incash_fee_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->incash_fee) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'incash_fee_cost');
                        ?>
                    </div>    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'incash_fee_source', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php echo $form->checkBox($model, 'incash_monthly'); ?>
                        <?php echo $form->labelEx($model, 'incash_monthly_cost'); ?>
                    </div>
                </div>
                <div class="form-group" id="incash_monthly_source" style="<?php echo (empty($model->incash_monthly) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo '<label id="incash_monthly_cost_label"  style="'.(empty($model->incash_monthly) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'incash_monthly_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->incash_monthly) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'incash_monthly_cost');
                        ?>
                    </div>    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'incash_monthly_source', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'incash_other') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'incash_other_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'incash_other_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->incash_other) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'incash_other_text');
                        ?>
                    </div>
                </div>
                <div class="form-group" id="incash_other_source" style="<?php echo (empty($model->incash_other) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="incash_other_cost_label" style="'.(empty($model->incash_other) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'incash_other_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->incash_other) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'incash_other_cost');
                        ?>
                    </div>    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'incash_other_source', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'incash_other2') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'incash_other2_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'incash_other2_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->incash_other2) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'incash_other2_text');
                        ?>
                    </div>
                </div>
                <div class="form-group" id="incash_other2_source" style="<?php echo (empty($model->incash_other2) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="incash_other2_cost_label" style="'.(empty($model->incash_other2) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'incash_other2_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->incash_other2) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'incash_other2_cost');
                        ?>
                    </div>    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'incash_other2_source', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <br/>
                        <div class="animated fadeIn tile_stats_count">
                            <div class="right">
                                <span class="count_top"><i class="fa fa-money"></i> IN-KIND (บาท)</span>
                                <div class="count" id="total_inkind"><?php echo number_format($model->inkind_sum, 2, '.', ','); ?></div>
                            </div>
                        </div>
                        <!--<h4 id="total_inkind">IN-KIND ( <?php // echo number_format($model->inkind_sum, 2, '.', ','); ?> บาท)</h4>-->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'inkind_other') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'inkind_other_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'inkind_other_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->inkind_other) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'inkind_other_text');
                        ?>
                    </div>
                </div>
                <div class="form-group"  id="inkind_other_source" style="<?php echo (empty($model->inkind_other) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="inkind_other_cost_label" style="'.(empty($model->inkind_other) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'inkind_other_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->inkind_other) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'inkind_other_cost');
                        ?>
                    </div>    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'inkind_other_source', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'inkind_other2') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'inkind_other2_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'inkind_other2_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->inkind_other2) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'inkind_other2_text');
                        ?>
                    </div>
                </div>
                <div class="form-group" id="inkind_other2_source"  style="<?php echo (empty($model->inkind_other2) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="inkind_other2_cost_label" style="'.(empty($model->inkind_other2) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'inkind_other2_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->inkind_other2) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        
                        echo $form->error($model, 'inkind_other2_cost');
                        ?>
                    </div>    
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'inkind_other2_source', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <br/>
                        <div class="animated fadeIn tile_stats_count">
                            <div class="right">
                                    <span class="count_top"><i class="fa fa-money"></i> <?php echo $form->labelEx($model, 'sum'); ?> (บาท)</span>
                                    <div class="count" id="sum"><?php echo number_format($model->sum, 2, '.', ','); ?></div>
                            </div>
                        </div>
                        <br/>
                        <?php // echo $form->labelEx($model, 'sum'); ?>
<!--                        <br/>
                        <h2 id="sum"><?php // echo number_format($model->sum, 2, '.', ','); ?> บาท</h2>-->
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12 login_content">
                        <span class="required">หมายเหตุ : ทุนโครงการ STEM Workforce ให้ทุนค่าใช้จ่ายรายเดือน โดยนักเรียน/นักศึกษา จะต้องไม่ได้รับทุนในหมวดค่าใช้จ่ายรายเดือนซ้ำซ้อนกับทุนอื่นที่ได้รับจากหน่วยงาน/องค์กรภาครัฐ</span>
                        <span class="required">ทั้งนี้ หากนักเรียน/นักศึกษา ได้รับการสนับสนุนจากภาคอุตสาหกรรมในหมวดค่าใช้จ่ายรายเดือนอยู่แล้ว นักเรียน/นักศึกษา สามารถขอรับทุนจากโครงการ STEM Workforce ได้</span>
                    </div>
                </div>
            </div>    

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
<script>
    function resetFormMentorHasProfessor(){
        var formname = "StemStudentProjectForm_mentor_has_professor";
        $("#" + formname + "_prefix_id").select2("val", "");
        $("#s2id_" + formname + "_prefix_id").attr("class","select2-container");
        $("#" + formname + "_prefix_id").val('');
        $("#" + formname + "_institute_id").select2("val", "");
        $("#s2id_" + formname + "_institute_id").attr("class","select2-container");
        $("#" + formname + "_institute_id").val('');
        $("#" + formname + "_faculty_id").select2("val", "");
        $("#s2id_" + formname + "_faculty_id").attr("class","select2-container");
        $("#" + formname + "_faculty_id").val('');
        $("#" + formname + "_fname").val('');
        $("#" + formname + "_lname").val('');
        $("#" + formname + "_mobile").val('');
        $("#" + formname + "_email").val('');
        $("#" + formname + "_institute_other").val('');
        $("#" + formname + "_faculty_other").val('');
        $("#" + formname + "_relation").val('');
        $("label, input").removeClass('error').removeClass('required');
        $("div").removeClass('has-error');
        $(".help-block").remove();
    }
    
    $(document).ready(function () {
        <?php if ($person_type == 'mentor') { ?>
        $('input[name="StemStudentProjectForm[mentor_has_professor]"]').change(function () {
            mentor_has_professor = $('input[name="StemStudentProjectForm[mentor_has_professor]"]:checked').val();
            if (mentor_has_professor == '0') {
                $('#mentor_has_professor_box').hide();
                resetFormMentorHasProfessor();
            } else if (mentor_has_professor == '1') {
                $('#mentor_has_professor_box').show();
                resetFormMentorHasProfessor();
            }
        });
        <?php } ?>
            
        $('#StemStudentProjectForm_project_begin').datepicker({
            'autoclose': true,
            'orientation': "top",
            'format': 'dd/mm/yyyy',
            'viewformat': 'dd/mm/yyyy',
            'datepicker': {'language': 'en'},
            'language': 'en'
        }).on("change", function () {
            setFuncPeriod();
        });

        $('#StemStudentProjectForm_project_end').datepicker({
            'autoclose': true,
            'orientation': "top",
            'format': 'dd/mm/yyyy',
            'viewformat': 'dd/mm/yyyy',
            'datepicker': {'language': 'en'},
            'language': 'en'
        }).on("change", function () {
            setFuncPeriod();
        });
        
        
        $("[type='checkbox']").change(function () {
            var id = $(this).attr('id');
            if ($(this).is(':checked')) {
                $("#"+id+"_desc").show();
                $("label[for='"+id+"_desc"+"']").show();
            } else {
                $("#"+id+"_desc").hide().val('');
                $("label[for='"+id+"_desc"+"']").hide();
            }
        });
        
        $("#StemStudentProjectForm_effect_other").change(function () {
            if ($(this).is(':checked')) {
                $("#StemStudentProjectForm_effect_other_text").show().css('display','inline');
            } else {
                $("#StemStudentProjectForm_effect_other_text").hide().val('');
            }
        });
        
        $("#StemStudentProjectForm_incash_fee").change(function () {
            if ($(this).is(':checked')) {
                $("#StemStudentProjectForm_incash_fee_cost").show();
                $("[id^='incash_fee']").show();
            } else {
                $("#StemStudentProjectForm_incash_fee_cost").hide().val('0.00');
                $("[id^='incash_fee']").hide();
                $("#StemStudentProjectForm_incash_fee_source").val('');
            }
            calIncash();
        });

        $("#StemStudentProjectForm_incash_monthly").change(function () {
            if ($(this).is(':checked')) {
                $("#StemStudentProjectForm_incash_monthly_cost").show();
                $("[id^='incash_monthly']").show();
            } else {
                $("#StemStudentProjectForm_incash_monthly_cost").hide().val('0.00');
                $("[id^='incash_monthly']").hide();
                $("#StemStudentProjectForm_incash_monthly_source").val('');
            }
            calIncash();
        });

        $("#StemStudentProjectForm_incash_other").change(function () {
            if ($(this).is(':checked')) {
                $("#StemStudentProjectForm_incash_other_cost").show();
                $("#StemStudentProjectForm_incash_other_text").show().css('display','inline');;
                $("[id^='incash_other_']").show();
            } else {
                $("#StemStudentProjectForm_incash_other_cost").hide().val('0.00');
                $("#StemStudentProjectForm_incash_other_text").hide().val('');
                $("[id^='incash_other_']").hide();
                $("#StemStudentProjectForm_incash_other_source").val('');
            }
            calIncash();
        });
        $("#StemStudentProjectForm_incash_other2").change(function () {
            if ($(this).is(':checked')) {
                $("#StemStudentProjectForm_incash_other2_cost").show();
                $("#StemStudentProjectForm_incash_other2_text").show().css('display','inline');;
                $("[id^='incash_other2']").show();
            } else {
                $("#StemStudentProjectForm_incash_other2_cost").hide().val('0.00');
                $("#StemStudentProjectForm_incash_other2_text").hide().val('');
                $("[id^='incash_other2']").hide();
                $("#StemStudentProjectForm_incash_other2_source").val('');
            }
            calIncash();
        });
        $("#StemStudentProjectForm_incash_fee_cost,#StemStudentProjectForm_incash_monthly_cost,#StemStudentProjectForm_incash_other_cost,#StemStudentProjectForm_incash_other2_cost").change(function () {
            if ($.isNumeric($(this).val())) {
                calIncash();
            } else {
                $(this).val('0.00');
                calIncash();
            }
        });


        $("#StemStudentProjectForm_inkind_other").change(function () {
            if ($(this).is(':checked')) {
                $("#StemStudentProjectForm_inkind_other_cost").show();
                $("#StemStudentProjectForm_inkind_other_text").show().css('display','inline');;
                $("[id^='inkind_other_']").show();
            } else {
                $("#StemStudentProjectForm_inkind_other_cost").hide();
                $("#StemStudentProjectForm_inkind_other_text").hide();
                $("#StemStudentProjectForm_inkind_other_cost").val('0.00');
                $("#StemStudentProjectForm_inkind_other_text").val('');
                $("[id^='inkind_other_']").hide();
                $("#StemStudentProjectForm_inkind_other_source").val('');
            }
            calInkind();
        });
        $("#StemStudentProjectForm_inkind_other2").change(function () {
            if ($(this).is(':checked')) {
                $("#StemStudentProjectForm_inkind_other2_cost").show();
                $("#StemStudentProjectForm_inkind_other2_text").show().css('display','inline');
                $("[id^='inkind_other2']").show();
            } else {
                $("#StemStudentProjectForm_inkind_other2_cost").hide();
                $("#StemStudentProjectForm_inkind_other2_text").hide();
                $("#StemStudentProjectForm_inkind_other2_cost").val('0.00');
                $("#StemStudentProjectForm_inkind_other2_text").val('');
                $("[id^='inkind_other2']").hide();
                $("#StemStudentProjectForm_inkind_other2_source").val('');
            }
            calInkind();
        });
        $("#StemStudentProjectForm_inkind_other_cost,#StemStudentProjectForm_inkind_other2_cost").change(function () {
            if ($.isNumeric($(this).val())) {
                calInkind();
            } else {
                $(this).val('0.00');
                calInkind();
            }
        });
<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function calIncash() {
        total = 0.00;
        if ($('#StemStudentProjectForm_incash_fee:checked').length == 1) {
            if ($.isNumeric($("#StemStudentProjectForm_incash_fee_cost").val())) {
                total = parseFloat(total) + parseFloat($("#StemStudentProjectForm_incash_fee_cost").val());
            }
        }
        if ($('#StemStudentProjectForm_incash_monthly:checked').length == 1) {
            if ($.isNumeric($("#StemStudentProjectForm_incash_monthly_cost").val())) {
                total = parseFloat(total) + parseFloat($("#StemStudentProjectForm_incash_monthly_cost").val());
            }
        }
        if ($('#StemStudentProjectForm_incash_other:checked').length == 1) {
            if ($.isNumeric($("#StemStudentProjectForm_incash_other_cost").val())) {
                total = parseFloat(total) + parseFloat($("#StemStudentProjectForm_incash_other_cost").val());
            }
        }
        if ($('#StemStudentProjectForm_incash_other2:checked').length == 1) {
            if ($.isNumeric($("#StemStudentProjectForm_incash_other2_cost").val())) {
                total = parseFloat(total) + parseFloat($("#StemStudentProjectForm_incash_other2_cost").val());
            }
        }
        $('#total_incash').text(addCommas(parseFloat(total).toFixed(2)));
        calSum();
    }

    function calInkind() {
        total = 0.00;
        if ($('#StemStudentProjectForm_inkind_other:checked').length == 1) {
            if ($.isNumeric($("#StemStudentProjectForm_inkind_other_cost").val())) {
                total = parseFloat(total) + parseFloat($("#StemStudentProjectForm_inkind_other_cost").val());
            }
        }
        if ($('#StemStudentProjectForm_inkind_other2:checked').length == 1) {
            if ($.isNumeric($("#StemStudentProjectForm_inkind_other2_cost").val())) {
                total = parseFloat(total) + parseFloat($("#StemStudentProjectForm_inkind_other2_cost").val());
            }
        }
        $('#total_inkind').text(addCommas(parseFloat(total).toFixed(2)));
        calSum();
    }
    
    function calSum(){
        var total_incash = parseFloat($('#total_incash').text().replace(/,/g, ""));
        var total_inkind = parseFloat($('#total_inkind').text().replace(/,/g, ""));
        var sum = total_incash + total_inkind;
        
        $('#sum').text(addCommas(parseFloat(sum).toFixed(2)));
    }    
    
    function setFuncPeriod() {
        var begin = $('#StemStudentProjectForm_project_begin').val();
        var end = $('#StemStudentProjectForm_project_end').val();
        if (begin != '' && end != '') {
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('stem/get_func_period') ?>',
                data: {begin: begin, end: end, mode: 'nonprimary'},
                type: "GET",
                success: function (data) {
                    $('#StemStudentProjectForm_func_period').val(data);
                },
                error: function (data) {
                    $('#StemStudentProjectForm_func_period').val(data);
                }
            });
        }
    }
</script>
