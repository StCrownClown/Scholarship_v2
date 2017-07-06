
<?php
$person_type = ConfigWeb::getActivePersonType();

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
?>

<?php

$title = 'ข้อมูลส่วนตัว / Profile';
if ($person_type == 'professor') {
    $title = 'ข้อมูลสังกัดสถาบันของอาจารย์ (ปัจจุบัน)';
}
$this->renderPartial('_x_title', array(
    'title' => $title
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'miniprofile-form',
                    'action' => Yii::app()->createUrl($formAciton),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'nationality_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'ProfileForm[nationality_id]',
                            'data' => InitialData::NstdamasNationality(),
                            'value' => (($model->nationality_id == NULL) ? '17' : $model->nationality_id),
                            'options' => array(
                                'placeholder' => '',
                                'width' => '100%',
                            )
                        ));
                        echo $form->error($model, 'nationality_id');
                        ?>
                    </div>

                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'id_card', array(
                            'wrapperHtmlOptions' => array(
                                'maxlength' => 30,
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                
                <?php if ($person_type == 'student') { ?>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php echo $form->labelEx($model, 'id_card_created'); ?>
                            <br/>
                            <?php
                            $this->widget('booster.widgets.TbDatePicker', array(
                                'model' => $model,
                                'attribute' => 'id_card_created',
                                'options' => array(
                                    'language' => 'en',
                                    
                                ),
                                'htmlOptions' => array(
                                    'placeholder' => 'DD/MM/YYYY',
                                )
                            ));
                            echo $form->error($model, 'id_card_created');
                            ?>
                        </div>
                        
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php echo $form->labelEx($model, 'id_card_expired'); ?>
                            <br/>
                            <?php
                            $this->widget('booster.widgets.TbDatePicker', array(
                                'model' => $model,
                                'attribute' => 'id_card_expired',
                                'options' => array(
                                    'language' => 'en',
                                    
                                ),
                                'htmlOptions' => array(
                                    'placeholder' => 'DD/MM/YYYY',
                                )
                            ));
                            echo $form->error($model, 'id_card_expired');
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'prefix_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'ProfileForm[prefix_id]',
                            'data' => InitialData::NstdamasPrefix(),
                            'value' => $model->prefix_id,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            )
                        ));
                        echo $form->error($model, 'prefix_id');
                        ?>
                    </div>
                    
                    <?php if ($person_type == 'professor') { ?>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'academic_position') .  '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'ProfileForm[academic_position]',
                            'data' => InitialData::ACADEMIC_POSITION(),
                            'value' => $model->academic_position,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            )
                        ));
                        echo $form->error($model, 'academic_position');
                        ?>
                    </div>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'fname', array(
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
                        echo $form->textFieldGroup($model, 'lname', array(
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
                        echo $form->textFieldGroup($model, 'fname_en', array(
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
                        echo $form->textFieldGroup($model, 'lname_en', array(
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
                
                <?php if ($person_type == 'student') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'nickname', array(
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
                <?php } ?>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->textFieldGroup($model, 'mobile', array(
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
                        echo $form->textFieldGroup($model, 'email', array(
                            'readonly' => true,
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'disabled' => TRUE,
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>

                <?php if ($person_type == 'professor' || $person_type == 'mentor' || $person_type == 'industrial') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'phone', array(
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
                            echo $form->textFieldGroup($model, 'fax', array(
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
                <?php } ?>

                <?php if ($person_type == 'student') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php echo $form->labelEx($model, 'birthday'); ?>
                            <br/>
                            <?php
                            $this->widget('booster.widgets.TbDatePicker', array(
                                'model' => $model,
                                'attribute' => 'birthday',
                                'options' => array(
                                    'language' => 'en',
                                    
                                ),
                                'htmlOptions' => array(
                                    'placeholder' => 'DD/MM/YYYY',
                                )
                            ));
                            echo $form->error($model, 'birthday');
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php echo $form->labelEx($model, 'age'); ?>
                            <br/>
                            <span id="ProfileForm_age"><?= $model->age ?></span>
                        </div>
                    </div>
                <?php } ?>
                
                <?php if ($person_type == 'mentor') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'position') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'ProfileForm[position]',
                                'data' => array(
                                                'ผู้บริหาร' => ' ผู้บริหาร',
                                                'นักวิจัย' => ' นักวิจัย',
                                                'ผู้ช่วยนักวิจัย' => ' ผู้ช่วยนักวิจัย',
                                                'ผู้เชี่ยวชาญ' => ' ผู้เชี่ยวชาญ',
                                                'วิศวกร' => ' วิศวกร',),
                                'value' => $model->position,
                                'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                                ),
                            ));
                            echo $form->error($model, 'position');
                            ?>
                            
                        </div>
                    </div>
                <?php } ?>
                
                <?php if ($person_type == 'industrial') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'position', array(
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
                <?php } ?>

                <?php if ($person_type != 'student') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'management_position', array(
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
                <?php } ?>
                
                <?php if ($person_type == 'professor' || $person_type == 'industrial') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textFieldGroup($model, 'industrial', array(
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
                <?php } ?>
                
                <?php if ($person_type == 'mentor') { ?>
                    <!-- FOR RESEARCHER -->
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'org_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'ProfileForm[org_id]',
                                'data' => InitialData::NstdamasOrg(),
                                'value' => $model->org_id,
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                ),
                                'events' => array('change' => 'js:function(e) {
                                        $("#ProfileForm_department_id option").each(function() {
                                            $(this).remove();
                                        });
                                        $("#ProfileForm_department_id").select2("val", "");
                                        $("#ProfileForm_department_id").val($("#ProfileForm_department_id option:first").val());
                                }'),
                                'htmlOptions' => array(
                                    'ajax' => array(
                                        'type' => 'POST',
                                        'url' => Yii::app()->createUrl("site/GetDepartmentByOrg"),
                                        'delay' => 250,
                                        'data' => array('org_id' => 'js:this.value'),
                                        'results' => 'js:function(data) { return {results: data}; }',
                                        'update' => '#ProfileForm_department_id',
                                    ),
                                ),
                            ));
                            echo $form->error($model, 'org_id');
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'department_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'ProfileForm[department_id]',
                                'data' => (($model->org_id != NULL) ? InitialData::NstdamasDepartmentByOrg($model->org_id) : array()),
                                'value' => $model->department_id,
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                ),
                                
                            ));
                            echo $form->error($model, 'department_id');
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($person_type == 'professor') { ?>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            // Institute in thailand only
                            $datasInstitute = InitialData::NstdamasInstituteByCountry(187);
                            
                            echo $form->labelEx($model, 'institute_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'ProfileForm[institute_id]',
                                'data' => $datasInstitute,
                                'value' => $model->institute_id,
                                'events' => array('change' => 'js:function(e) { 
                                        if(e.val==0){
                                            $("#ProfileForm_institute_other").val("");
                                            $("#box_institute_other").show();
                                        }else{
                                            $("#ProfileForm_institute_other").val("");
                                            $("#box_institute_other").hide(); 
                                        }
                                    }'),
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                )
                            ));
                            echo $form->error($model, 'institute_id');
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12" id="box_institute_other" <?= ConfigWeb::setDefaultDisplay($model->institute_id) ?>>
                            <?php
                            echo $form->textFieldGroup($model, 'institute_other', array(
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
                            echo $form->labelEx($model, 'faculty_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'ProfileForm[faculty_id]',
                                'data' => InitialData::NstdamasFaculty(),
                                'value' => $model->faculty_id,
                                'events' => array('change' => 'js:function(e) { 
                                        if(e.val==0){
                                            $("#ProfileForm_faculty_other").val("");
                                            $("#box_faculty_other").show();
                                        }else{
                                            $("#ProfileForm_faculty_other").val("");
                                            $("#box_faculty_other").hide(); 
                                        }
                                    }'),
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                )
                            ));
                            echo $form->error($model, 'faculty_id');
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12" id="box_faculty_other" <?= ConfigWeb::setDefaultDisplay($model->faculty_id) ?>>
                            <?php
                            echo $form->textFieldGroup($model, 'faculty_other', array(
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
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <?php
                            echo $form->labelEx($model, 'major_id') . '<br/>';
                            $this->widget('booster.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'model' => $model,
                                'name' => 'ProfileForm[major_id]',
                                'data' => InitialData::NstdamasMarjor(),
                                'value' => $model->major_id,
                                'events' => array('change' => 'js:function(e) { 
                                        if(e.val==0){
                                            $("#ProfileForm_major_other").val("");
                                            $("#box_major_other").show();
                                        }else{
                                            $("#ProfileForm_major_other").val("");
                                            $("#box_major_other").hide();
                                        }
                                    }'),
                                'options' => array(
                                    'placeholder' => '--- เลือก ---',
                                    'width' => '100%',
                                )
                            ));
                            echo $form->error($model, 'major_id');
                            ?>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12" id="box_major_other" <?= ConfigWeb::setDefaultDisplay($model->major_id) ?>>
                            <?php
                            echo $form->textFieldGroup($model, 'major_other', array(
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
                <?php } ?>
                
                <?php
                if ($person_type == 'mentor' || $person_type == 'professor' || $person_type == 'industrial') {
                    ?>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            echo $form->textAreaGroup($model, 'expert', array(
                                'wrapperHtmlOptions' => array(
                                    'class' => 'form-control',
                                ),
                                'widgetOptions' => array(
                                    'htmlOptions' => array(
                                        'rows' => 5,
                                        'placeholder' => '',
                                    )
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
        var formname = "ProfileForm";
        $("#" + formname + "_fname_en, #" + formname + "_lname_en").keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z._ -]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $("#" + formname + "_fname, #" + formname + "_lname").keypress(function (e) {
            var regex = new RegExp("^[ก-๙._ -]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        
        $("#ProfileForm_mobile, #ProfileForm_parent_mobile, #ProfileForm_parent2_mobile").keypress(function(event){
            if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
                event.preventDefault(); //stop character from entering input
            }
        });
<?php if ($person_type == 'student') { ?>
            var d = new Date();
            var year = d.getFullYear() - 18;
            d.setFullYear(year);
            $('#ProfileForm_birthday').datepicker({
                'autoclose': true,
                'orientation': "top",
                'format': 'dd/mm/yyyy',
                'viewformat': 'dd/mm/yyyy',
                'datepicker': {
                    'language': 'en'
                },
            }).on("change", function () {
                setFuncAge();
            });
            
            $('#ProfileForm_id_card_created').datepicker({
                'autoclose': true,
                'orientation': "top",
                'format': 'dd/mm/yyyy',
                'viewformat': 'dd/mm/yyyy',
                'datepicker': {
                    'language': 'en'
                },
            });
            $('#ProfileForm_id_card_expired').datepicker({
                'autoclose': true,
                'orientation': "top",
                'format': 'dd/mm/yyyy',
                'viewformat': 'dd/mm/yyyy',
                'datepicker': {
                    'language': 'en'
                },
            });
            
<?php } ?>
    
<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });
<?php if ($person_type == 'student') { ?>
        function setFuncAge() {
            var birthday = $('#ProfileForm_birthday').val();
            if (birthday != '') {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl('site/get_age') ?>',
                    data: {birthday: birthday},
                    type: "GET",
                    success: function (data) {
                        $('#ProfileForm_age').text(data);
                    },
                    error: function () {
                        $('#ProfileForm_age').text('-');
                    }
                });
            }
        }
<?php } ?>
</script>
