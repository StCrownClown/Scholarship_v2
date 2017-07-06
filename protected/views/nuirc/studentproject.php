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
                </div>
                
                <div class="form-group">
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
        var formname = "NuircStudentProjectForm_mentor_has_professor";
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
        $('input[name="NuircStudentProjectForm[mentor_has_professor]"]').change(function () {
            mentor_has_professor = $('input[name="NuircStudentProjectForm[mentor_has_professor]"]:checked').val();
            if (mentor_has_professor == '0') {
                $('#mentor_has_professor_box').hide();
                resetFormMentorHasProfessor();
            } else if (mentor_has_professor == '1') {
                $('#mentor_has_professor_box').show();
                resetFormMentorHasProfessor();
            }
        });
        <?php } ?>
            
        $('#NuircStudentProjectForm_project_begin').datepicker({
            'autoclose': true,
            'orientation': "top",
            'format': 'dd/mm/yyyy',
            'viewformat': 'dd/mm/yyyy',
            'datepicker': {'language': 'en'},
            'language': 'en'
        }).on("change", function () {
            setFuncPeriod();
        });

        $('#NuircStudentProjectForm_project_end').datepicker({
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
        
        $("#NuircStudentProjectForm_effect_other").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircStudentProjectForm_effect_other_text").show().css('display','inline');
            } else {
                $("#NuircStudentProjectForm_effect_other_text").hide().val('');
            }
        });
        
        $("#NuircStudentProjectForm_incash_fee").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircStudentProjectForm_incash_fee_cost").show();
                $("[id^='incash_fee']").show();
            } else {
                $("#NuircStudentProjectForm_incash_fee_cost").hide().val('0.00');
                $("[id^='incash_fee']").hide();
                $("#NuircStudentProjectForm_incash_fee_source").val('');
            }
            calIncash();
        });

        $("#NuircStudentProjectForm_incash_monthly").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircStudentProjectForm_incash_monthly_cost").show();
                $("[id^='incash_monthly']").show();
            } else {
                $("#NuircStudentProjectForm_incash_monthly_cost").hide().val('0.00');
                $("[id^='incash_monthly']").hide();
                $("#NuircStudentProjectForm_incash_monthly_source").val('');
            }
            calIncash();
        });

        $("#NuircStudentProjectForm_incash_other").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircStudentProjectForm_incash_other_cost").show();
                $("#NuircStudentProjectForm_incash_other_text").show().css('display','inline');;
                $("[id^='incash_other_']").show();
            } else {
                $("#NuircStudentProjectForm_incash_other_cost").hide().val('0.00');
                $("#NuircStudentProjectForm_incash_other_text").hide().val('');
                $("[id^='incash_other_']").hide();
                $("#NuircStudentProjectForm_incash_other_source").val('');
            }
            calIncash();
        });
        $("#NuircStudentProjectForm_incash_other2").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircStudentProjectForm_incash_other2_cost").show();
                $("#NuircStudentProjectForm_incash_other2_text").show().css('display','inline');;
                $("[id^='incash_other2']").show();
            } else {
                $("#NuircStudentProjectForm_incash_other2_cost").hide().val('0.00');
                $("#NuircStudentProjectForm_incash_other2_text").hide().val('');
                $("[id^='incash_other2']").hide();
                $("#NuircStudentProjectForm_incash_other2_source").val('');
            }
            calIncash();
        });
        $("#NuircStudentProjectForm_incash_fee_cost,#NuircStudentProjectForm_incash_monthly_cost,#NuircStudentProjectForm_incash_other_cost,#NuircStudentProjectForm_incash_other2_cost").change(function () {
            if ($.isNumeric($(this).val())) {
                calIncash();
            } else {
                $(this).val('0.00');
                calIncash();
            }
        });


        $("#NuircStudentProjectForm_inkind_other").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircStudentProjectForm_inkind_other_cost").show();
                $("#NuircStudentProjectForm_inkind_other_text").show().css('display','inline');;
                $("[id^='inkind_other_']").show();
            } else {
                $("#NuircStudentProjectForm_inkind_other_cost").hide();
                $("#NuircStudentProjectForm_inkind_other_text").hide();
                $("#NuircStudentProjectForm_inkind_other_cost").val('0.00');
                $("#NuircStudentProjectForm_inkind_other_text").val('');
                $("[id^='inkind_other_']").hide();
                $("#NuircStudentProjectForm_inkind_other_source").val('');
            }
            calInkind();
        });
        $("#NuircStudentProjectForm_inkind_other2").change(function () {
            if ($(this).is(':checked')) {
                $("#NuircStudentProjectForm_inkind_other2_cost").show();
                $("#NuircStudentProjectForm_inkind_other2_text").show().css('display','inline');
                $("[id^='inkind_other2']").show();
            } else {
                $("#NuircStudentProjectForm_inkind_other2_cost").hide();
                $("#NuircStudentProjectForm_inkind_other2_text").hide();
                $("#NuircStudentProjectForm_inkind_other2_cost").val('0.00');
                $("#NuircStudentProjectForm_inkind_other2_text").val('');
                $("[id^='inkind_other2']").hide();
                $("#NuircStudentProjectForm_inkind_other2_source").val('');
            }
            calInkind();
        });
        $("#NuircStudentProjectForm_inkind_other_cost,#NuircStudentProjectForm_inkind_other2_cost").change(function () {
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
        if ($('#NuircStudentProjectForm_incash_fee:checked').length == 1) {
            if ($.isNumeric($("#NuircStudentProjectForm_incash_fee_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircStudentProjectForm_incash_fee_cost").val());
            }
        }
        if ($('#NuircStudentProjectForm_incash_monthly:checked').length == 1) {
            if ($.isNumeric($("#NuircStudentProjectForm_incash_monthly_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircStudentProjectForm_incash_monthly_cost").val());
            }
        }
        if ($('#NuircStudentProjectForm_incash_other:checked').length == 1) {
            if ($.isNumeric($("#NuircStudentProjectForm_incash_other_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircStudentProjectForm_incash_other_cost").val());
            }
        }
        if ($('#NuircStudentProjectForm_incash_other2:checked').length == 1) {
            if ($.isNumeric($("#NuircStudentProjectForm_incash_other2_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircStudentProjectForm_incash_other2_cost").val());
            }
        }
        $('#total_incash').text(addCommas(parseFloat(total).toFixed(2)));
        calSum();
    }

    function calInkind() {
        total = 0.00;
        if ($('#NuircStudentProjectForm_inkind_other:checked').length == 1) {
            if ($.isNumeric($("#NuircStudentProjectForm_inkind_other_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircStudentProjectForm_inkind_other_cost").val());
            }
        }
        if ($('#NuircStudentProjectForm_inkind_other2:checked').length == 1) {
            if ($.isNumeric($("#NuircStudentProjectForm_inkind_other2_cost").val())) {
                total = parseFloat(total) + parseFloat($("#NuircStudentProjectForm_inkind_other2_cost").val());
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
        var begin = $('#NuircStudentProjectForm_project_begin').val();
        var end = $('#NuircStudentProjectForm_project_end').val();
        if (begin != '' && end != '') {
            $.ajax({
                url: '<?php echo Yii::app()->createUrl('nuirc/get_func_period') ?>',
                data: {begin: begin, end: end, mode: 'nonprimary'},
                type: "GET",
                success: function (data) {
                    $('#NuircStudentProjectForm_func_period').val(data);
                },
                error: function (data) {
                    $('#NuircStudentProjectForm_func_period').val(data);
                }
            });
        }
    }
</script>
