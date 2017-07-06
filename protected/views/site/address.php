
<?php
$person_id = ConfigWeb::getActivePersonId();
$person_type = ConfigWeb::getActivePersonType();

$model->country_id = (($model->country_id == NULL) ? '187' : $model->country_id);
$styleThai = "style='display:none;'";
$styleNoThai = "";
$labelAddress = "ที่อยู่ / Address";
if ($model->country_id == '187') {
    $styleThai = "";
    $styleNoThai = "style='display:none;'";
    $labelAddress = "เลขที่";
}

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}
?>

<?php
$tt = 'ข้อมูลที่อยู่ / Address';
if (ConfigWeb::getActiveScholarType() == 'stem')
{
	if($person_type == 'student')
		$tt = 'ข้อมูลที่อยู่ตามบัตรประชาชน / Address';
	if($person_type == 'industrial')
		$tt = 'ข้อมูลที่อยู่บริษัท / Address';
}
    
	
$this->renderPartial('_x_title', array(
    'title' => $tt
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <br>
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'address-form',
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
                        echo $form->labelEx($model, 'country_id') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'AddressForm[country_id]',
                            'data' => InitialData::NstdamasCountry(),
                            'value' => (($model->country_id == NULL) ? '187' : $model->country_id),
                            'events' => array('change' => 'js:function(e) { 
                                    if(e.val==187){
                                        $("#number").show();
                                        $("#address").hide();
                                        $(".box_thai").show();
                                        $(".box_nothai").hide();
                                        $("#AddressForm_province_id").select2("val", "");
                                        $("#AddressForm_district").select2("val", "");
                                        $("#AddressForm_zipcode").select2("val", "");
                                        $("input[name=\'AddressForm[zipcode]\']").val("");
                                        $("#AddressForm_subdistrict").val("");
                                        $("#AddressForm_address").val("");
                                        $("#AddressForm_number").val("");
                                        $("#AddressForm_building").val("");
                                        $("#AddressForm_floor").val("");
                                        $("#AddressForm_room").val("");
                                        $("#AddressForm_village").val("");
                                        $("#AddressForm_alley").val("");
                                        $("#AddressForm_road").val("");
                                        $("#AddressForm_moo").val("");
                                    }else{
                                        $("#number").hide();
                                        $("#address").show();
                                        $(".box_thai").hide();
                                        $(".box_nothai").show();
                                        $("#AddressForm_province_id").select2("val", "");
                                        $("#AddressForm_district").select2("val", "");
                                        $("#AddressForm_zipcode").select2("val", "");
                                        $("input[name=\'AddressForm[zipcode]\']").val("");
                                        $("#AddressForm_subdistrict").val("");
                                        $("#AddressForm_address").val("");
                                        $("#AddressForm_number").val("");
                                        $("#AddressForm_building").val("");
                                        $("#AddressForm_floor").val("");
                                        $("#AddressForm_room").val("");
                                        $("#AddressForm_village").val("");
                                        $("#AddressForm_alley").val("");
                                        $("#AddressForm_road").val("");
                                        $("#AddressForm_moo").val("");
                                    }
                                    $("#AddressForm_province_id").select2("val", "");
                                    $("#AddressForm_district").select2("val", "");
                                    $("#AddressForm_zipcode").select2("val", "");
                                    $("#AddressForm_zipcode option").each(function() {
                                        $(this).remove();
                                    });
                                    $("#AddressForm_district option").each(function() {
                                        $(this).remove();
                                    });
                                }'),
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                        ));
                        echo $form->error($model, 'country_id');
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->labelEx($model, 'province_id') . ' <span class="required">*</span><br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'AddressForm[province_id]',
                            'attribute' => 'province_id',
                            'data' => InitialData::NstdamasProvince(),
//                            'value' => $model->province_id,
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                                'allowClear' => TRUE,
                            ),
                            'events' => array('change' => 'js:function(e) { 
                                    $("#AddressForm_district").select2("val", "");
                                    $("#AddressForm_zipcode").select2("val", "");
                                    $("input[name=\'AddressForm[zipcode]\']").val("");
                                    $("#AddressForm_zipcode option").each(function() {
                                        $(this).remove();
                                    });
                                    $("#AddressForm_district option").each(function() {
                                        $(this).remove();
                                    });
                            }'),
                            'htmlOptions' => array(
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => Yii::app()->createUrl("site/GetDistrictByProvince"),
                                    'delay' => 250,
                                    'data' => array('province_id' => 'js:this.value'),
                                    'results' => 'js:function(data) { return {results: data}; }',
                                    'update' => '#AddressForm_district',
                                ),
                            ),
                        ));
                        echo $form->error($model, 'province_id');
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->labelEx($model, 'district') . ' <span class="required">*</span><br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'AddressForm[district]',
                            'attribute' => 'district',
                            'data' => (($model->province_id != NULL) ? InitialData::NstdamasDistrict($model->province_id) : array()),
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                            'events' => array('change' => 'js:function(e) { 
                                    $("#AddressForm_zipcode").select2("val", "");
                                    $("input[name=\'AddressForm[zipcode]\']").val("");
                                    $("#AddressForm_zipcode option").each(function() {
                                        $(this).remove();
                                    });
                            }'),
                            'htmlOptions' => array(
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => Yii::app()->createUrl("site/GetZipcodeByDistrict"),
                                    'data' => array('district_id' => 'js:this.value'),
                                    'results' => 'js:function(data) { return {results: data}; }',
                                    'update' => '#AddressForm_zipcode',
                                ),
                            ),
                        ));
                        echo $form->error($model, 'district');
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'subdistrict', array(
                            'label' => 'แขวง / ตำบล <span class="required">*</span>',
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
                    <div class="col-md-6 col-sm-12 col-xs-12" id="number" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'number', array(
                            'label' => '<span id="labelAddress">' . $labelAddress . ' <span class="required">*</span></sapn>',
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'size' => 5,
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12" id="address" <?= $styleNoThai ?>>
                        <?php
                        echo $form->textAreaGroup($model, 'address', array(
                            'label' => '<span id="labelAddress">' . $labelAddress . ' <span class="required">*</span></sapn>',
                            'wrapperHtmlOptions' => array(
                                'class' => 'form-control',
                            ),
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'rows' => 5,
                                    'placeholder' => '',
                                ),
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'building', array(
                            'placeholder' => '',
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'size' => 100,
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'floor', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'room', array(
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
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'village', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'moo', array(
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
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'alley', array(
                            'class' => 'form-control',
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'placeholder' => '',
                                )
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->textFieldGroup($model, 'road', array(
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
                    <div class="col-md-6 col-sm-12 col-xs-12 box_thai" <?= $styleThai ?>>
                        <?php
                        echo $form->labelEx($model, 'zipcode') . '<br/>';
                        $this->widget('booster.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'model' => $model,
                            'name' => 'AddressForm[zipcode]',
                            'data' => (($model->district != NULL) ? InitialData::NstdamasZipcode($model->district) : array()),
                            'value' => $model->zipcode,
                            'events' => array('change' => 'js:function(e) { 
                                $("input[name=\'AddressForm[zipcode]\']").val(e.val);
                            }'),
                            'options' => array(
                                'placeholder' => '--- เลือก ---',
                                'width' => '100%',
                            ),
                        ));
                        echo $form->error($model, 'zipcode');
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12 box_nothai" <?= $styleNoThai ?>>
<?php
echo $form->textFieldGroup($model, 'zipcode', array(
    'class' => 'form-control',
    'events' => array('change' => 'js:function(e) { 
                                $("input[name=\'AddressForm[zipcode]\']").val(e.val);
                            }'),
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
    $(document).ready(function () {


<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });
</script>
