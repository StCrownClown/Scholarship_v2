<?php
$person_type = ConfigWeb::getActivePersonType();

$formAciton = 'tgist/recommendation';
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
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
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
    
//    function setFuncPeriod() {
//        var begin = $('#tgistStudentProjectForm_project_begin').val();
//        var end = $('#tgistStudentProjectForm_project_end').val();
//        if (begin != '' && end != '') {
//            $.ajax({
//                url: '<?php // echo Yii::app()->createUrl('tgist/get_func_period') ?>',
//                data: {begin: begin, end: end},
//                type: "GET",
//                success: function (data) {
//                    $('#tgistStudentProjectForm_func_period').val(data);
//                },
//                error: function () {
//                    $('#tgistStudentProjectForm_func_period').val('-');
//                }
//            });
//        }
//    }
</script>
