
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
    'title' => 'ข้อมูลความคิดเห็น / Recommendation '
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
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
                <?php echo $form->hiddenField($model, 'id', array('type' => "hidden")); ?>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'comment', array(
                            'placeholder' => '',
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '100%',
                                    'placeholder' => ''
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>

            </div>    
            <div class="actionBar">
                <div class="modal animate fadeIn" style="text-align: left;padding-left: 17px;background-color: rgba(0,0,0,.5);">
                  <div class="modal-dialog modal-lg" style="margin: 10% auto;">
                    <div class="modal-content">
                      <div class="modal-header" style="height: 50px;">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                        <h2 class="modal-title" id="myModalLabel"><i class="fa fa-warning"></i> ข้อควรทราบ</h2>
                      </div>
                      <div class="modal-body">
                          <h3 class="well">ข้าพเจ้าขอรับรองว่าข้อความดังกล่าวข้างต้นเป็นความจริงทุกประการ หากตรวจสอบพบว่าข้อมูลและหลักฐานต่างๆ ไม่เป็นความจริง ข้าพเจ้ายินยอมให้ตัดสิทธิ์การรับสมัครได้ และผลการตัดสินของโครงการถือเป็นข้อยุติ</h3>
                      </div>
                      <div class="actionBar">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด / Close</button>
                        <?php 
                        $this->widget('booster.widgets.TbButton', array(
                            'label' => 'ยินยอม / Agree',
                            'buttonType' => 'submit',
                            'htmlOptions' => array(
                                'name' => 'saveapply',
                                'style' => 'float: right;',
                                'class' => 'btn btn-success',
                                'confirm' => "ยืนยันการบันทึกและสมัคร กรุณายืนยัน ?"
                                . "\nDo you want to Save & Apply?",
                            ),
                        ));
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                
                <?php
                $urlBack = WorkflowData::WorkflowUrlBack(Yii::app()->urlManager->parseUrl(Yii::app()->request));
                WorkflowData::getActionBar($this, $urlBack, FALSE);

                if (empty(Yii::app()->session['tmpReadOnly'])) {
                    if ($model->scholar_status == 'pending_recommendations' && $model->status == 'draft') {
                        $this->widget('booster.widgets.TbButton', array(
                            'label' => 'บันทึกและส่ง / Save&Send',
                            'buttonType' => 'submit',
                            'htmlOptions' => array(
                                'name' => 'savesend',
                                'style' => 'float: right;',
                                'class' => 'btn btn-success',
                                'confirm' => "ยืนยันการบันทึกและส่ง Email แจ้งนักเรียน/นักศึกษา กรุณายืนยัน ?"
                                . "\nDo you want to Save & Send?",
                            ),
                        ));
                        $this->widget('booster.widgets.TbButton', array(
                            'label' => 'บันทึกร่าง / Save Draft',
                            'buttonType' => 'submit',
                            'htmlOptions' => array(
                                'name' => 'savedraft',
                                'style' => 'float: right;',
                                'class' => 'btn btn-warning',
                            ),
                        ));
                    }
                    else if ($model->scholar_status == 'pending_recommendations' && $model->status == 'sent') {
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
                    else if ($model->scholar_status == 'confirm') {
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
                        
//                        $this->widget('booster.widgets.TbButton', array(
//                            'label' => 'บันทึกและสมัคร / Save&Apply',
//                            'buttonType' => 'submit',
//                            'htmlOptions' => array(
//                                'name' => 'saveapply',
//                                'style' => 'float: right;',
//                                'class' => 'btn btn-success',
//                                'confirm' => "ยืนยันการบันทึกและสมัคร กรุณายืนยัน ?"
//                                . "\nDo you want to Save & Apply?",
//                            ),
//                        ));

//                        $this->widget('booster.widgets.TbButton', array(
//                            'label' => 'บันทึก / Save',
//                            'buttonType' => 'submit',
//                            'htmlOptions' => array(
//                                'name' => 'save',
//                                'style' => 'float: right;',
//                                'class' => 'btn btn-warning',
//                            ),
//                        ));
//                    }
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
    function ToggleModal(){
        $(".modal").modal("show");
        $(".modal-backdrop").remove();
    }
    
    $(document).ready(function () {
<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });
</script>
