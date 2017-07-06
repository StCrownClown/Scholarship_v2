<?php
$person_type = ConfigWeb::getActivePersonType();

$formAciton = 'nuirc/recommendation';
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::$home;
}
?>

<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ข้อมูลความคิดเห็น / Recommendation '
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'recommendation-form',
                    'action' => Yii::app()->createUrl($formAciton),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                <?php echo $form->hiddenField($model, 'id', array('type' => "hidden")); ?>
                <?php
                    $this->renderPartial('_recommendation', array(
                        'form' => $form,
                        'model' => $model
                    ));
                ?>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h3>ค่าใช้จ่ายในการสนับสนุนโครงการวิจัยของนักเรียน/นักศึกษา (ประมาณการเงินสนับสนุนจนจบโครงการ)</h3>
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
                        <?php echo $form->checkBox($model, 'industrial_incash_salary'); ?>
                        <?php echo $form->labelEx($model, 'industrial_incash_salary_cost'); ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_incash_salary" style="<?php echo (empty($model->industrial_incash_salary) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo '<label id="NuircRecommendationForm_industrial_incash_salary_label"  style="'.(empty($model->industrial_incash_salary) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_incash_salary_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_incash_salary) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_incash_salary_cost');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php echo $form->checkBox($model, 'industrial_incash_rents'); ?>
                        <?php echo $form->labelEx($model, 'industrial_incash_rents_cost'); ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_incash_rents" style="<?php echo (empty($model->industrial_incash_rents) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo '<label id="NuircRecommendationForm_industrial_incash_rents_label"  style="'.(empty($model->industrial_incash_rents) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_incash_rents_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_incash_rents) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_incash_rents_cost');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php echo $form->checkBox($model, 'industrial_incash_traveling'); ?>
                        <?php echo $form->labelEx($model, 'industrial_incash_traveling_cost'); ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_incash_traveling" style="<?php echo (empty($model->industrial_incash_traveling) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo '<label id="NuircRecommendationForm_industrial_incash_traveling_label"  style="'.(empty($model->industrial_incash_traveling) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_incash_traveling_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_incash_traveling) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_incash_traveling_cost');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'industrial_incash_other') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'industrial_incash_other_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'industrial_incash_other_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_incash_other) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'industrial_incash_other_text');
                        ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_incash_other" style="<?php echo (empty($model->industrial_incash_other) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="NuircRecommendationForm_industrial_incash_other_label" style="'.(empty($model->industrial_incash_other) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_incash_other_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_incash_other) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_incash_other_cost');
                        ?>
                    </div>    
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'industrial_incash_other2') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'industrial_incash_other2_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'industrial_incash_other2_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_incash_other2) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'industrial_incash_other2_text');
                        ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_incash_other2" style="<?php echo (empty($model->industrial_incash_other2) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="NuircRecommendationForm_industrial_incash_other2_label" style="'.(empty($model->industrial_incash_other2) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_incash_other2_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_incash_other2) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_incash_other2_cost');
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
                    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php echo $form->checkBox($model, 'industrial_inkind_equipment'); ?>
                        <?php echo $form->labelEx($model, 'industrial_inkind_equipment_cost'); ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_inkind_equipment" style="<?php echo (empty($model->industrial_inkind_equipment) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo '<label id="NuircRecommendationForm_industrial_inkind_equipment_label"  style="'.(empty($model->industrial_inkind_equipment) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_inkind_equipment_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_inkind_equipment) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_inkind_equipment_cost');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'industrial_inkind_other') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'industrial_inkind_other_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'industrial_inkind_other_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_inkind_other) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'industrial_inkind_other_text');
                        ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_inkind_other" style="<?php echo (empty($model->industrial_inkind_other) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="NuircRecommendationForm_industrial_inkind_other_label" style="'.(empty($model->industrial_inkind_other) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_inkind_other_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_inkind_other) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_inkind_other_cost');
                        ?>
                    </div>    
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                        <?php
                        echo $form->checkBox($model, 'industrial_inkind_other2') . "&nbsp;&nbsp;";
                        echo $form->labelEx($model, 'industrial_inkind_other2_cost') . "&nbsp;&nbsp;";
                        echo $form->textField($model, 'industrial_inkind_other2_text', array(
                            'placeholder' => 'ระบุประเภทค่าใช้จ่าย',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_inkind_other2) ? 'display:none;width: 150px;' : 'display:inline;width: 150px;'),
                        ));
                        echo $form->error($model, 'industrial_inkind_other2_text');
                        ?>
                    </div>
                </div>
                <div class="form-group" id="industrial_inkind_other2" style="<?php echo (empty($model->industrial_inkind_other2) ? 'display:none;' : ''); ?>">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                         <?php
                        echo '<label id="NuircRecommendationForm_industrial_inkind_other2_label" style="'.(empty($model->industrial_inkind_other2) ? 'display:none;' : '').' ">จำนวน (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น</label>';
                        echo $form->textField($model, 'industrial_inkind_other2_cost', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'style' => (empty($model->industrial_inkind_other2) ? 'display:none;' : ''),
                            'maxlength'=>12
                        ));
                        echo $form->error($model, 'industrial_inkind_other2_cost');
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
                    <div class="col-md-10 col-sm-12 col-xs-12  col-md-offset-1">
                        <?php
                        echo $form->textAreaGroup($model, 'industrial_support_desc', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                    'rows' => 5,
                                    'width' => '100%'),
                            )
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10 col-sm-12 col-xs-12  col-md-offset-1">
                        <?php
                        echo $form->textAreaGroup($model, 'comment', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                    'rows' => 5,
                                    'width' => '100%'),
                            )
                        ));
                        ?>
                    </div>
                </div>
                
            </div>    

            <div class="actionBar">
                <?php
                $urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
                WorkflowData::getActionBar($this, $urlBack, FALSE);

                if (empty(Yii::app()->session['tmpReadOnly'])) {
                    if($model->status == Yii::app()->params['Status']['Draft']){
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
                    }else if($model->status == Yii::app()->params['Status']['Send']){
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
                            'style' => 'float: right;',
                            'class' => 'btn btn-success',
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
        $("[type='checkbox']").change(function () {
            var id = $(this).attr('id');
            if ($(this).is(':checked')) {
                $("#"+id+"_cost").show();;
                $("#"+id+"_label").show();
            } else {
                $("#"+id+"_cost").hide().val('');
                $("#"+id+"_label").hide();
            }
        });
        
        $("#NuircRecommendationForm_industrial_incash_salary").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_incash_salary_cost").show().val('0.00');
                $("[id^='industrial_incash_salary']").show();
            } else {
                $("#NuircRecommendationForm_industrial_incash_salary_cost").hide().val('0.00');
                $("[id^='industrial_incash_salary']").hide();
            }
            calIncash();
        });
        
        $("#NuircRecommendationForm_industrial_incash_rents").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_incash_rents_cost").show().val('0.00');
                $("[id^='industrial_incash_rents']").show();
            } else {
                $("#NuircRecommendationForm_industrial_incash_rents_cost").hide().val('0.00');
                $("[id^='industrial_incash_rents']").hide();
            }
            calIncash();
        });
        
        
        $("#NuircRecommendationForm_industrial_incash_traveling").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_incash_traveling_cost").show().val('0.00');
                $("[id^='industrial_incash_traveling']").show();
            } else {
                $("#NuircRecommendationForm_industrial_incash_traveling_cost").hide().val('0.00');
                $("[id^='industrial_incash_traveling']").hide();
            }
            calIncash();
        });
        
        $("#NuircRecommendationForm_industrial_incash_other").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_incash_other_cost").show().val('0.00');
                $("#NuircRecommendationForm_industrial_incash_other_text").show().val('');
                $("[id^='industrial_incash_other']").show();
            } else {
                $("#NuircRecommendationForm_industrial_incash_other_cost").hide().val('0.00');
                $("#NuircRecommendationForm_industrial_incash_other_text").hide().val('');
                $("[id^='industrial_incash_other']").hide();
            }
            calIncash();
        });
        
        $("#NuircRecommendationForm_industrial_incash_other2").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_incash_other2_cost").show().val('0.00');
                $("#NuircRecommendationForm_industrial_incash_other2_text").show().val('');
                $("[id^='industrial_incash_other2']").show();
            } else {
                $("#NuircRecommendationForm_industrial_incash_other2_cost").hide().val('0.00');
                $("#NuircRecommendationForm_industrial_incash_other2_text").hide().val('');
                $("[id^='industrial_incash_other2']").hide();
            }
            calIncash();
        });
        
        $("#NuircRecommendationForm_industrial_inkind_equipment").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_inkind_equipment_cost").show().val('0.00');
                $("[id^='industrial_inkind_equipment']").show();
            } else {
                $("#NuircRecommendationForm_industrial_inkind_equipment_cost").hide().val('0.00');
                $("[id^='industrial_inkind_equipment']").hide();
            }
            calInkind();
        });
        
        $("#NuircRecommendationForm_industrial_inkind_other").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_inkind_other_cost").show().val('0.00');
                $("#NuircRecommendationForm_industrial_inkind_other_text").show().val('');
                $("[id^='industrial_inkind_other']").show();
            } else {
                $("#NuircRecommendationForm_industrial_inkind_other_cost").hide().val('0.00');
                $("#NuircRecommendationForm_industrial_inkind_other_text").hide().val('');
                $("[id^='industrial_inkind_other']").hide();
            }
            calInkind();
        });
        
        $("#NuircRecommendationForm_industrial_inkind_other2").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircRecommendationForm_industrial_inkind_other2_cost").show().val('0.00');
                $("#NuircRecommendationForm_industrial_inkind_other2_text").show().val('');
                $("[id^='industrial_inkind_other2']").show();
            } else {
                $("#NuircRecommendationForm_industrial_inkind_other2_cost").hide().val('0.00');
                $("#NuircRecommendationForm_industrial_inkind_other2_text").hide().val('');
                $("[id^='industrial_inkind_other2']").hide();
            }
            calInkind();
        });
        
        $("#NuircRecommendationForm_industrial_incash_salary_cost, #NuircRecommendationForm_industrial_incash_rents_cost, #NuircRecommendationForm_industrial_incash_traveling_cost, #NuircRecommendationForm_industrial_incash_other_cost, #NuircRecommendationForm_industrial_incash_other2_cost").change(function () {
            if ($.isNumeric($(this).val())) {
                calIncash();
            } else {
                $(this).val('0.00');
                calIncash();
            }
        });
        
        $("#NuircRecommendationForm_industrial_inkind_equipment_cost,#NuircRecommendationForm_industrial_inkind_other_cost,#NuircRecommendationForm_industrial_inkind_other2_cost").change(function () {
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
    
    function calInkind() {
        total = 0.00;
        if ($('#NuircRecommendationForm_industrial_inkind_equipment:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_inkind_equipment_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_inkind_equipment_cost").val());
            }
        }
        if ($('#NuircRecommendationForm_industrial_inkind_other:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_inkind_other_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_inkind_other_cost").val());
            }
        }
        if ($('#NuircRecommendationForm_industrial_inkind_other2:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_inkind_other2_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_inkind_other2_cost").val());
            }
        }
        $('#total_inkind').text(addCommas(parseFloat(total).toFixed(2)));
        calSum();
    }
    
    function calIncash() {
        total = 0.00;
        if ($('#NuircRecommendationForm_industrial_incash_salary:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_incash_salary_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_incash_salary_cost").val());
            }
        }
        if ($('#NuircRecommendationForm_industrial_incash_rents:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_incash_rents_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_incash_rents_cost").val());
            }
        }
        if ($('#NuircRecommendationForm_industrial_incash_traveling:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_incash_traveling_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_incash_traveling_cost").val());
            }
        }
        if ($('#NuircRecommendationForm_industrial_incash_other:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_incash_other_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_incash_other_cost").val());
            }
        }
        if ($('#NuircRecommendationForm_industrial_incash_other2:checked').length == 1) {
            if ($.isNumeric($("#NuircRecommendationForm_industrial_incash_other2_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircRecommendationForm_industrial_incash_other2_cost").val());
            }
        }
        $('#total_incash').text(addCommas(parseFloat(total).toFixed(2)));
        calSum();
    }
    
    function calSum(){
        var total_incash = parseFloat($('#total_incash').text().replace(/,/g, ""));
        var total_inkind = parseFloat($('#total_inkind').text().replace(/,/g, ""));
        var sum = total_incash + total_inkind;
        
        $('#sum').text(addCommas(parseFloat(sum).toFixed(2)));
    }   
    
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
    
//    function setFuncPeriod() {
//        var begin = $('#nuircStudentProjectForm_project_begin').val();
//        var end = $('#nuircStudentProjectForm_project_end').val();
//        if (begin != '' && end != '') {
//            $.ajax({
//                url: '<?php // echo Yii::app()->createUrl('nuirc/get_func_period') ?>',
//                data: {begin: begin, end: end},
//                type: "GET",
//                success: function (data) {
//                    $('#nuircStudentProjectForm_func_period').val(data);
//                },
//                error: function () {
//                    $('#nuircStudentProjectForm_func_period').val('-');
//                }
//            });
//        }
//    }
</script>
