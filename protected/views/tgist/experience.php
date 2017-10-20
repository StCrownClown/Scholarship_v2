
<?php
$person_type = ConfigWeb::getActivePersonType();

$isExperience = "style='display:none;'";
if ($model->is_experience == '1') {
    $isExperience = '';
}

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
?>

<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ประสบการณ์และผลงานที่เคยได้รับ (ถ้ามี)'
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'company-form',
                    'action' => Yii::app()->createUrl($formAciton),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'portfolio_thesis', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '100%',
                                    'placeholder' => '',),
                            )
                        ));
                        ?>
                    </div>
                </div>
                
<!--                <div class="form-group">-->
<!--                    <div class="col-md-12 col-sm-12 col-xs-12">-->
<!--                        <label><span class="required">**ผลงานที่เคยได้รับ (โปรดแนบไฟล์ ที่หน้า 8 Attachment)**</span></label>-->
<!--                    </div>-->
<!--                </div>-->
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'is_experience') . ' <br/>';
                        echo $form->radioButtonList($model, 'is_experience', array(
                            '0' => ' ไม่มีประสบการณ์และผลงาน',
                            '1' => ' มีประสบการณ์และผลงาน'), array(
                            'labelOptions' => array('style' => 'display:inline'), // add this code
                            'separator' => '<br/>',
                        ));
                        echo $form->error($model, 'is_experience');
                        echo '<br/>';
                        echo '<br/>';
                        ?>
                    </div>
                </div>
                
                <div id="experience_box" <?php echo $isExperience; ?>>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label for="portfolio_journal_international">ผลงานที่เคยได้รับ</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_journal_international'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_journal_international'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_journal_international'); ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_journal_international_amount" style="<?php echo (empty($model->portfolio_journal_international) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_journal_international_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_journal_international) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_journal_international_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_journal_incountry'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_journal_incountry'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_journal_incountry'); ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_journal_incountry_amount" style="<?php echo (empty($model->portfolio_journal_incountry) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_journal_incountry_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_journal_incountry) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_journal_incountry_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_patent'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_patent'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_patent'); ?>
                            
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_patent_amount" style="<?php echo (empty($model->portfolio_patent) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_patent_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_patent) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_patent_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_prototype'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_prototype'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_prototype'); ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_prototype_amount" style="<?php echo (empty($model->portfolio_prototype) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_prototype_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_prototype) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_prototype_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_conference_international'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_conference_international'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_conference_international'); ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_conference_international_amount" style="<?php echo (empty($model->portfolio_conference_international) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_conference_international_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_conference_international) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_conference_international_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_conference_incountry'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_conference_incountry'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_conference_incountry'); ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_conference_incountry_amount" style="<?php echo (empty($model->portfolio_conference_incountry) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_conference_incountry_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_conference_incountry) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_conference_incountry_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_award'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_award'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_award'); ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_award_amount" style="<?php echo (empty($model->portfolio_award) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_award_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_award) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_award_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
                                        'width' => '100%',
                                        'placeholder' => '',
                                    ),
                                )
                            ));
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-1">
                            <?php // echo $form->checkBoxGroup($model, 'portfolio_other'); ?>
                            <?php echo $form->checkBox($model, 'portfolio_other'); ?>
                            <?php echo $form->labelEx($model, 'portfolio_other'); ?>
                            <?php
                            echo $form->textField($model, 'portfolio_other_text', array(
                                'placeholder' => ' อื่นๆ โปรดระบุ',
                                'style' => (empty($model->portfolio_other) ? 'display:none;' : 'display: inline;'),
                                'class' => 'col-md-offset-0'
                            ));
                            echo $form->error($model, 'effect_other_text');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1"  id="portfolio_other_amount" style="<?php echo (empty($model->portfolio_other) ? 'display:none;' : ''); ?>">
                        <div class="col-md-2 col-sm-3 col-xs-12">
                            <?php 
                            echo $form->textFieldGroup($model, 'portfolio_other_amount', array(
                                'class' => 'form-control',
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'placeholder' => '',
                                        'maxlength'=>5,
                                        'style' => (empty($model->portfolio_other) ? 'display:none;' : ''),
                                    )
                                )
                            ));
                            ?>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'portfolio_other_desc', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 3,
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
                
                
            </div>
        </div>    
        <div class="actionBar">
            <?php
            $UrlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
            $addUrl = Yii::app()->createUrl($UrlBack);
            echo CHtml::link('← ย้อนกลับ / Back', $addUrl, array(
                'class' => 'btn btn-default',
                'style' => 'float: left;',
            ));

            $this->widget('booster.widgets.TbButton', array(
                'label' => 'ถัดไป / Next →',
                'buttonType' => 'submit',
                'htmlOptions' => array(
                    'name' => 'next',
                    'class' => 'btn btn-success',
                    'style' => 'float: right;',
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
<script>
    $(document).ready(function () {
        $("input[type=checkbox]").change(function () {
            var id = $(this).attr('id') + "_amount";
            var label = id.replace("TgistExperienceForm_", "");
            
            if($(this).attr('id').replace("TgistExperienceForm_", "") == "portfolio_other"){
                if ($(this).is(':checked')) {
                    $("#TgistExperienceForm_portfolio_other_text").show();
                } else {
                    $("#TgistExperienceForm_portfolio_other_text").attr('style','display:inline;').hide().val('');
                }
            }
            if ($(this).is(':checked')) {
                $("#" + id).show();
                $("#" + label).show();
                $("#" + id).val('');
            } else {
                $("#" + id).hide().val('0');
                $("#" + label).hide();
                $("#" + id).val('');
            }
        });
        
        $('input[name="TgistExperienceForm[is_experience]"]').change(function () {
            is_experience = $('input[name="TgistExperienceForm[is_experience]"]:checked').val();
            if (is_experience == '0') {
                $('#experience_box').hide();
                $('#TgistExperienceForm_portfolio_journal_international').val('');
                $('#TgistExperienceForm_portfolio_journal_incountry').val('');
                $('#TgistExperienceForm_portfolio_patent').val('');
                $('#TgistExperienceForm_portfolio_prototype').val('');
                $('#TgistExperienceForm_portfolio_conference_international').val('');
                $('#TgistExperienceForm_portfolio_conference_incountry').val('');
                $('#TgistExperienceForm_portfolio_award').val('');
                $('#TgistExperienceForm_portfolio_other').val('');
                $('#TgistExperienceForm_portfolio_journal_international_amount').val('');
                $('#TgistExperienceForm_portfolio_journal_incountry_amount').val('');
                $('#TgistExperienceForm_portfolio_patent_amount').val('');
                $('#TgistExperienceForm_portfolio_prototype_amount').val('');
                $('#TgistExperienceForm_portfolio_conference_international_amount').val('');
                $('#TgistExperienceForm_portfolio_conference_incountry_amount').val('');
                $('#TgistExperienceForm_portfolio_award_amount').val('');
                $('#TgistExperienceForm_portfolio_other_text').val('');
                $('#TgistExperienceForm_portfolio_journal_international_desc').val('');
                $('#TgistExperienceForm_portfolio_journal_incountry_desc').val('');
                $('#TgistExperienceForm_portfolio_patent_desc').val('');
                $('#TgistExperienceForm_portfolio_prototype_desc').val('');
                $('#TgistExperienceForm_portfolio_conference_international_desc').val('');
                $('#TgistExperienceForm_portfolio_conference_incountry_desc').val('');
                $('#TgistExperienceForm_portfolio_award_desc').val('');
                $('#TgistExperienceForm_portfolio_other_desc').val('');
                $('#TgistExperienceForm_portfolio_other_amount').val('');
            } else if (is_experience == '1') {
                $('#experience_box').show();
                $('#TgistExperienceForm_is_experience').val() == $('input[name="TgistExperienceForm[is_experience]"]:checked').val();
            }
        });

<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });
</script>
