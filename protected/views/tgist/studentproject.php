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
                        echo $form->labelEx($model, 'scholar_education') . ' <br/>';
                        echo $form->radioButtonList($model, 'scholar_education', array(
                            'master' => ' ปริญญาโท',
                            'doctor' => ' ปริญญาเอก'), array(
                            'labelOptions' => array('style' => 'display:inline'), // add this code
                            'separator' => '<br/>',
                        ));
//                        echo $form->error($model, 'scholar_education');
                        echo '<br/>';
                        ?>
                    </div>
                </div>
                <br/>
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'project_name_th', array(
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
                        echo $form->textFieldGroup($model, 'project_name_en', array(
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
                        echo $form->labelEx($model, 'professor_accord_nstda') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'TgistStudentProjectForm[professor_accord_nstda]',
                            'data' => array(
                                            'คลัสเตอร์เกษตรและอาหาร' => ' คลัสเตอร์เกษตรและอาหาร',
                                            'คลัสเตอร์พลังงานและสิ่งแวดล้อม' => ' คลัสเตอร์พลังงานและสิ่งแวดล้อม',
                                            'คลัสเตอร์สุขภาพและการแพทย์' => ' คลัสเตอร์สุขภาพและการแพทย์',
                                            'คลัสเตอร์ทรัพยากร ชุมชน และผู้ด้อยโอกาส' => ' คลัสเตอร์ทรัพยากร ชุมชน และผู้ด้อยโอกาส',
                                            'คลัสเตอร์อุตสาหกรรมการผลิตและบริการ' => ' คลัสเตอร์อุตสาหกรรมการผลิตและบริการ',
                                            'Cross Cutting Technology Program' => ' Cross Cutting Technology Program'),
                            'value' => $model->professor_accord_nstda,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                        ));
//                        echo $form->error($model, 'professor_accord_nstda');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'scope', array(
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
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'project_period') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'TgistStudentProjectForm[project_period]',
                            'data' => array(
                                            '1' => ' 1 ปี',
                                            '2' => ' 2 ปี',
                                            '3' => ' 3 ปี',),
                            'value' => $model->project_period,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                        ));
//                        echo $form->error($model, 'project_period');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'cooperation_nstda') . '<br/>';
                        $percent = array(
                                            '0' => ' 0%',
                                            '10' => ' 10%',
                                            '20' => ' 20%',
                                            '30' => ' 30%',
                                            '40' => ' 40%',
                                            '50' => ' 50%',
                                            '60' => ' 60%',
                                            '70' => ' 70%',
                                            '80' => ' 80%',
                                            '90' => ' 90%',
                                            '100' => ' 100%',);
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'TgistStudentProjectForm[cooperation_nstda]',
                            'data' => $percent,
                            'value' => $model->cooperation_nstda,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                        ));
                        echo $form->error($model, 'cooperation_nstda');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'cooperation_university') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'TgistStudentProjectForm[cooperation_university]',
                            'data' => $percent,
                            'value' => $model->cooperation_university,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                        ));
//                        echo $form->error($model, 'cooperation_university');
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'cooperation_industrial') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'TgistStudentProjectForm[cooperation_industrial]',
                            'data' => $percent,
                            'value' => $model->cooperation_industrial,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                        ));
//                        echo $form->error($model, 'cooperation_industrial');
                        ?>
                    </div>
                </div>
                
                <div class="form-group ">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label><span class="required">**ลักษณะความร่วมมือในการทำวิทยานิพนธ์ของนิสิต/นักศึกษา สวทช. มหาวิทยาลัย หรืออื่นๆ เช่น หน่วยงานเอกชน หรือหน่วยงานร่วมมืออื่นๆ แบ่งเป็นสัดส่วน โดยรวมกันแล้วได้ 100%</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'project_profit', array(
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
                        echo $form->textAreaGroup($model, 'project_other_connect', array(
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
                        echo $form->textAreaGroup($model, 'expect', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'knowledge', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'new_process', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'new_technology', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'project_prototype_ind', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'project_prototype_gnd', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'project_prototype_lab', array(
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
                
                <div class="form-group" >
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'project_commercial', array(
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
                    
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'project_public', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'academic_article_nat', array(
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
                        echo $form->textFieldGroup($model, 'academic_article_int', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'project_conference_nat', array(
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
                        echo $form->textFieldGroup($model, 'project_conference_int', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'final_report', array(
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
                        echo $form->textFieldGroup($model, 'technical_paper', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'textbook', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'thesis', array(
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
                
                <div class="form-group" id='name'>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'others', array(
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
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'course_should_study', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 2,
                                    'width' => '50%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'professor_support') . ' <br/>';
                        echo $form->radioButtonList($model, 'professor_support', array(
                            'sometime' => ' ไม่สามารถดูแลนักศึกษาเพื่อทำวิจัย(วิทยานิพนธ์) ได้เต็มเวลา',
                            'fulltime' => ' สามารถดูแลนักศึกษาเพื่อทำวิจัย(วิทยานิพนธ์) ได้เต็มเวลา'), array(
                            'labelOptions' => array('style' => 'display:inline'), // add this code
                            'separator' => '<br/>',
                        ));
                        echo $form->error($model, 'professor_support');
                        echo '<br/>';
                        ?>
                    </div>
                </div>
                <br/>
                
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textAreaGroup($model, 'professor_nstda_cooperation', array(
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'width' => '50%',
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
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

$(document).ready(function () {
        
        var formname = "TgistStudentProjectForm";
        
        $("#" + formname + "_project_name_en").keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z._ -]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        
        $("#" + formname + "_project_name_th").keypress(function (e) {
            var regex = new RegExp("^[ก-๙._ -]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });

        $("#" + formname + "_knowledge, #" + formname + "_new_process, #" + formname + "_new_technology, #" + formname + "_project_prototype_ind, #" + formname + "_project_prototype_gnd, #" + formname + "_project_prototype_lab").keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        
        $("#" + formname + "_academic_article_nat, #" + formname + "_academic_article_int, #" + formname + "_project_conference_nat, #" + formname + "_project_conference_int, #" + formname + "_final_report, #" + formname + "_technical_paper").keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        
        $('#project_commercial').hide();
        $('input[name="TgistStudentProjectForm[project_commercial]"]').change(function () {
            project_commercial = $('input[name="TgistStudentProjectForm[project_commercial]"]:checked').val();
            if (project_commercial == '0') {
                $('#project_commercial').hide();
                $('#TgistStudentProjectForm_project_commercial').val('');
            } else if (project_commercial == '1') {
                $('#project_commercial').show();
                $('#TgistStudentProjectForm_project_commercial').val('');
            }
        });
        
        $('#project_public').hide();
        $('input[name="TgistStudentProjectForm[project_public]"]').change(function () {
            project_public = $('input[name="TgistStudentProjectForm[project_public]"]:checked').val();
            if (project_public == '0') {
                $('#project_public').hide();
                $('#TgistStudentProjectForm_project_public').val('');
            } else if (project_public == '1') {
                $('#project_public').show();
                $('#TgistStudentProjectForm_project_public').val('');
            }
        });

        var $focused = $(':focus');
    });
    
    
</script>
