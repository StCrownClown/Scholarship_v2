<?php
$person_type = ConfigWeb::getActivePersonType();
$person_id = ConfigWeb::getActivePersonId();

$isComplate = "style='display:none;'";
if ($model->status == 'complete' || $person_type != 'student') {
    $isComplate = '';
}

$readonly = 'readonly';
$disabled = 'disabled';
if (in_array($mode, array('add', 'edit'))) {
    $readonly = '';
    $disabled = '';
}
?>
<?php echo $form->hiddenField($model, 'id', array('type' => "hidden")); ?>
<?php echo $form->hiddenField($model, 'person_id', array('type' => "hidden")); ?>
<?php if ($person_type == 'student') { ?>
    <div class="form-group">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <?php
            echo $form->labelEx($model, 'status') . ' <span class="required">*</span><br/>';
            echo $form->radioButtonList($model, 'status', array(
                'studying' => ' กำลังศึกษา',
                'complete' => ' จบการศึกษา'), array(
                'labelOptions' => array('style' => 'display:inline'),
                'separator' => '<br/>',
                'disabled' => $disabled,
            ));
            echo '<br/>';
            echo '<br/>';
            echo $form->error($model, 'status');
            echo '<br/>';
            ?>
        </div>
    </div>
<?php } ?>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->labelEx($model, 'educationlevel_id') . '<br/>';
        $this->widget('booster.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'model' => $model,
            'name' => 'EducationForm[educationlevel_id]',
            'data' => InitialData::NstdamasEducationLevel(),
            'value' => $model->educationlevel_id,
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
            )
        ));
        ?>
        <?php echo $form->error($model, 'educationlevel_id'); ?>
    </div>
</div>
<div class="form-group">    
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->labelEx($model, 'country_id') . '<br/>';
        $this->widget('booster.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'model' => $model,
            'name' => 'EducationForm[country_id]',
            'data' => InitialData::NstdamasCountry(),
            'value' => $model->country_id,
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'events' => array('change' => 'js:function(e) {
                    $("#box_institute_other").hide();
                    $("#EducationForm_institute_id option").each(function() {
                        $(this).remove();
                    });
                    $("#EducationForm_institute_other").val("");
                    $("#EducationForm_institute_id").select2("val", "");
                    $("#EducationForm_institute_id").val($("#EducationForm_institute_id option:first").val());
            }'),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
                'ajax' => array(
                    'type' => 'POST',
                    'url' => Yii::app()->createUrl("site/GetInstituteByCountry"),
                    'delay' => 250,
                    'data' => array('country_id' => 'js:this.value'),
                    'results' => 'js:function(data) { return {results: data}; }',
                    'update' => '#EducationForm_institute_id',
                ),
            ),
        ));
        ?>
        <?php echo $form->error($model, 'country_id'); ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        $dataInstitute = array();
        if($model->country_id != NULL && in_array($mode, array('edit'))){
            $dataInstitute  = InitialData::NstdamasInstituteByCountry($model->country_id);
        } else if(!in_array($mode, array('add', 'edit'))){
            $dataInstitute  = InitialData::NstdamasInstitute();
        }else{
            $dataInstitute  = InitialData::NstdamasInstitute();
        }
        
        echo $form->labelEx($model, 'institute_id') . '<br/>';
        $this->widget('booster.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'model' => $model,
            'name' => 'EducationForm[institute_id]',
            'data' => $dataInstitute,
            'value' => $model->institute_id,
            'events' => array(
                'change' => 'js:function(e) {
                    if(e.val==0){
                        $("#EducationForm_institute_other").val(""); 
                        $("#box_institute_other").show();
                    }else{
                        $("#EducationForm_institute_other").val(""); 
                        $("#box_institute_other").hide();
                    }
                }',
                'select2-selecting' => 'js:function(e) { 
                    if(e.val==0){
                        $("#EducationForm_institute_other").prev().removeClass( "error" );
                        $("#EducationForm_institute_other").removeClass( "error" );

                        $("#box_institute_other").show();
                        $("#EducationForm_institute_other").val("");
                    }
                }'),
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
            )
        ));
        ?>
        <?php echo $form->error($model, 'institute_id'); ?>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12" id="box_institute_other" <?= ConfigWeb::setDefaultDisplay($model->institute_id) ?>>
        <?php
        echo $form->textFieldGroup($model, 'institute_other', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'readonly' => $readonly,
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
            'name' => 'EducationForm[faculty_id]',
            'data' => InitialData::NstdamasFaculty(),
            'value' => $model->faculty_id,
            'events' => array('change' => 'js:function(e) { 
                            if(e.val==0){
                                $("#EducationForm_faculty_other").val("");
                                $("#box_faculty_other").show();
                            }else{
                                $("#EducationForm_faculty_other").val("");
                                $("#box_faculty_other").hide();
                            }
                        }'),
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
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
                    'readonly' => $readonly,
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
            'name' => 'EducationForm[major_id]',
            'data' => InitialData::NstdamasMarjor(),
            'value' => $model->major_id,
            'events' => array('change' => 'js:function(e) { 
                            if(e.val==0){
                                $("#EducationForm_major_other").val("");
                                $("#box_major_other").show();
                            }else{
                                $("#EducationForm_major_other").val("");
                                $("#box_major_other").hide();
                            }
                        }'),
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
            )
        ));
        ?>
        <?php echo $form->error($model, 'major_id'); ?>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12" id="box_major_other" <?= ConfigWeb::setDefaultDisplay($model->major_id) ?>>
        <?php
        echo $form->textFieldGroup($model, 'major_other', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'readonly' => $readonly,
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
        echo $form->labelEx($model, 'month_enrolled') . '<br/>';
        $this->widget('booster.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'model' => $model,
            'name' => 'EducationForm[month_enrolled]',
            'data' => InitialData::MonthList(),
            'value' => $model->month_enrolled,
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
            )
        ));
        ?>
        <?php echo $form->error($model, 'month_enrolled'); ?>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->labelEx($model, 'year_enrolled') . '<br/>';
        $this->widget('booster.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'model' => $model,
            'name' => 'EducationForm[year_enrolled]',
            'data' => InitialData::YearList(),
            'value' => $model->year_enrolled,
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
            )
        ));
        ?>
        <?php echo $form->error($model, 'year_enrolled'); ?>
    </div>
</div>

<div class="form-group" id="graduated" <?= $isComplate ?> >
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        $req = "";
        if ($person_type == 'student')
            $req = '  <span class="required">*</span><br/>';
        echo $form->labelEx($model, 'month_graduated') . $req;
        $this->widget('booster.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'model' => $model,
            'name' => 'EducationForm[month_graduated]',
            'data' => InitialData::MonthList(),
            'value' => $model->month_graduated,
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
            )
        ));
        ?>
        <?php echo $form->error($model, 'month_graduated'); ?>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->labelEx($model, 'year_graduated') . $req;
        $this->widget('booster.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'model' => $model,
            'name' => 'EducationForm[year_graduated]',
            'data' => InitialData::YearList(),
            'value' => $model->year_graduated,
            'options' => array(
                'placeholder' => '--- เลือก ---',
                'width' => '100%',
            ),
            'htmlOptions' => array(
                'readonly' => $readonly,
                'placeholder' => '',
            )
        ));
        ?>
        <?php echo $form->error($model, 'year_graduated'); ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->textFieldGroup($model, 'avg_gpa', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'readonly' => $readonly,
                    'width' => '100%',
                    'placeholder' => '',
                )
            )
        ));
        if ($person_type == 'student') {
            echo "<span class='required'>หากนักศึกษาเพิ่งเข้าเรียน และยังไม่มีเกรดเฉลี่ยรวมในปัจจุบันให้นักศึกษากรอกเกรดเฉลี่ยเป็น  0.01 แทน</span>";
        }
        ?>
    </div>
</div>


<script>
    $(document).ready(function () {

    });
</script>
