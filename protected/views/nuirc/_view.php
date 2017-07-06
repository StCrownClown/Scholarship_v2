<?php
$person_type = ConfigWeb::getActivePersonType();
$readonly = 'readonly';
$disabled = 'disabled';
if (in_array($mode, array('add', 'edit'))) {
    $readonly = '';
    $disabled = '';
}
?>
<?php echo $form->hiddenField($model, 'id', array('type' => "hidden")); ?>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->textFieldGroup($model, 'name', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'readonly' => $readonly,
                    'placeholder' => '',
                )
            ),
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->textFieldGroup($model, 'cluster', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'readonly' => $readonly,
                    'placeholder' => '',
                )
            ),
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
                    'readonly' => $readonly,
                    'placeholder' => '',
                    'width' => '100%'),
            )
        ));
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
                    'readonly' => $readonly,
                    'placeholder' => '',
                    'width' => '100%'),
            )
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php echo $form->labelEx($model, 'begin'); ?>
        <br/>
        <?php
        $this->widget('booster.widgets.TbDatePicker', array(
            'model' => $model,
            'attribute' => 'begin',
            'options' => array(
                'language' => 'en'
            ),
            'htmlOptions' => array(
                'disabled' => $disabled,
                    'placeholder' => '',
                    'readonly' => $readonly,
            ),
        ));
        echo $form->error($model, 'begin');
        ?>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php echo $form->labelEx($model, 'end'); ?>
        <br/>
        <?php
        $this->widget('booster.widgets.TbDatePicker', array(
            'model' => $model,
            'attribute' => 'end',
            'options' => array(
                'language' => 'en'
            ),
            'htmlOptions' => array(
                'disabled' => $disabled,
                    'placeholder' => '',
                    'readonly' => $readonly,
            ),
        ));
        echo $form->error($model, 'end');
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
                    'disabled' => TRUE,
                    'placeholder' => '',
                    'readonly' => true,
                )
            )
        ));
        ?>
    </div>
</div>

<div class="form-group">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php echo $form->labelEx($model, 'funding'); ?>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-xs-12">
    <?php echo $form->error($model, 'funding'); ?>
    <?php
    $source = '';
    $code = '';
    $etc = '';
    $source = '<br/>' . $form->labelEx($model, 'funding_name', array('style'=> (($model->funding == 'source') ? '' : 'display:none;')))
            . $form->textFieldGroup($model, 'funding_name', array(
        'label' => false,
        'widgetOptions' => array(
            'htmlOptions' => array(
                'placeholder' => '',
                'readonly' => $readonly,
                'style'=> (($model->funding == 'source') ? '' : 'display:none;'),
            )
        ),
        'class' => 'span-2'));
    $code = '<br/>' . $form->labelEx($model, 'funding_code', array('style'=> (($model->funding == 'nstda') ? '' : 'display:none;')))
            . $form->textFieldGroup($model, 'funding_code', array(
        'label' => false,
        'widgetOptions' => array(
            'htmlOptions' => array(
                'placeholder' => '',
                'readonly' => $readonly,
                'style'=> (($model->funding == 'nstda') ? '' : 'display:none;'),
            )
        ),
        'class' => 'span-2'));
    
    $code .= $form->labelEx($model, 'funding_code_name', array('style'=> (($model->funding == 'nstda') ? '' : 'display:none;')))
            . $form->textFieldGroup($model, 'funding_code_name', array(
        'label' => false,
        'widgetOptions' => array(
            'htmlOptions' => array(
                'placeholder' => '',
                'readonly' => $readonly,
                'style'=> (($model->funding == 'nstda') ? '' : 'display:none;'),
            )
        ),
        'class' => 'span-2'));
    
    $etc = '<br/>' . $form->labelEx($model, 'funding_etc', array('style'=> (($model->funding == 'other') ? '' : 'display:none;')))
            . $form->textFieldGroup($model, 'funding_etc', array(
        'label' => false,
        'widgetOptions' => array(
            'htmlOptions' => array(
                'placeholder' => '',
                'readonly' => $readonly,
                'style'=> (($model->funding == 'other') ? '' : 'display:none;'),
            )
        ),
        'class' => 'span-2'));
    
    $data = array(
        $model->CHECKBOX_FUNDING_SOURCE => InitialData::CHECKBOX_FUNDING($model->CHECKBOX_FUNDING_SOURCE) . $source,
        $model->CHECKBOX_FUNDING_NSTDA => InitialData::CHECKBOX_FUNDING($model->CHECKBOX_FUNDING_NSTDA) . $code,
        $model->CHECKBOX_FUNDING_OTHER => InitialData::CHECKBOX_FUNDING($model->CHECKBOX_FUNDING_OTHER) . $etc,
        $model->CHECKBOX_FUNDING_NONE => InitialData::CHECKBOX_FUNDING($model->CHECKBOX_FUNDING_NONE),
    );
    if ($person_type == 'mentor') {
       $data = array(
            $model->CHECKBOX_FUNDING_NSTDA => InitialData::CHECKBOX_FUNDING($model->CHECKBOX_FUNDING_NSTDA) . $code,
        ); 
    }
    

    echo $form->radioButtonList($model, 'funding', $data, array(
        'labelOptions' => array(
            'style' => 'display:inline',
        ),
        'disabled' => $disabled,
        'separator' => ''));
    ?>
</div>
<div class="form-group">
    <div class="col-md-12 col-sm-6 col-xs-12"></div>
</div>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo $form->textFieldGroup($model, 'budget', array(
            'class' => 'form-control',
            'widgetOptions' => array(
                'htmlOptions' => array(
                    'readonly' => $readonly,
                    'placeholder' => '',
                    'maxlength'=>12
                )
            ),
        ));
        ?>
    </div>
</div>

<script>
    
    $(document).ready(function () {
        
//    	$('#NuircPrimaryProjectForm_budget').keypress(function() {
//    		var nchar = String.fromCharCode(event.keyCode);
//    	    if ((nchar < '0' || nchar > '9') && (nchar != '.')) return false;
//    		Num = nchar;
//    		});
        
        $('#NuircPrimaryProjectForm_begin').datepicker({
            'autoclose': true,
            'orientation': "top",
            'format': 'dd/mm/yyyy',
            'viewformat': 'dd/mm/yyyy',
            'datepicker': {'language': 'en'},
            'language': 'en',
        }).on("change", function () {
            setFuncPeriod();
        });

        $('#NuircPrimaryProjectForm_end').datepicker({
            'autoclose': true,
            'orientation': "top",
            'format': 'dd/mm/yyyy',
            'viewformat': 'dd/mm/yyyy',
            'datepicker': {'language': 'en'},
            'language': 'en'
        }).on("change", function () {
            setFuncPeriod();
        });
        
        $("input[name='NuircPrimaryProjectForm[funding]']").change(function () {
            $('div.error').hide();
            if($(this).val() == 'source'){
                $("label[for^='NuircPrimaryProjectForm_funding_name']").show();
                $('#NuircPrimaryProjectForm_funding_name').show();
                $("label[for^='NuircPrimaryProjectForm_funding_code']").hide();
                $('#NuircPrimaryProjectForm_funding_code, #NuircPrimaryProjectForm_funding_code_name').hide().val('');
                $("label[for^='NuircPrimaryProjectForm_funding_etc']").hide();
                $('#NuircPrimaryProjectForm_funding_etc').hide().val('');
            }else if($(this).val() == 'nstda'){
                $("label[for^='NuircPrimaryProjectForm_funding_name']").hide();
                $('#NuircPrimaryProjectForm_funding_name').hide().val('');
                $("label[for^='NuircPrimaryProjectForm_funding_code']").show();
                $('#NuircPrimaryProjectForm_funding_code, #NuircPrimaryProjectForm_funding_code_name').show();
                $("label[for^='NuircPrimaryProjectForm_funding_etc']").hide();
                $('#NuircPrimaryProjectForm_funding_etc').hide().val('');
            }else if($(this).val() == 'other'){
                $("label[for^='NuircPrimaryProjectForm_funding_name']").hide();
                $('#NuircPrimaryProjectForm_funding_name').hide().val('');
                $("label[for^='NuircPrimaryProjectForm_funding_code']").hide();
                $('#NuircPrimaryProjectForm_funding_code, #NuircPrimaryProjectForm_funding_code_name').hide().val('');
                $("label[for^='NuircPrimaryProjectForm_funding_etc']").show();
                $('#NuircPrimaryProjectForm_funding_etc').show();
            }else{
                $("label[for^='NuircPrimaryProjectForm_funding_name']").hide();
                $('#NuircPrimaryProjectForm_funding_name').hide().val('');
                $("label[for^='NuircPrimaryProjectForm_funding_code']").hide();
                $('#NuircPrimaryProjectForm_funding_code, #NuircPrimaryProjectForm_funding_code_name').hide().val('');
                $("label[for^='NuircPrimaryProjectForm_funding_etc']").hide();
                $('#NuircPrimaryProjectForm_funding_etc').hide().val('');
            }
        });
        
    });

    function setFuncPeriod() {
        var begin = $('#NuircPrimaryProjectForm_begin').val();
        var end = $('#NuircPrimaryProjectForm_end').val();
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('nuirc/get_func_period') ?>',
            data: {begin: begin, end: end, mode: 'primary'},
            type: "GET",
            success: function (data) {
                $('#NuircPrimaryProjectForm_func_period').val(data);
            },
            error: function (data) {
                $('#NuircPrimaryProjectForm_func_period').val(data);
            }
        });
    }
</script>
