<?php
$person_type = ConfigWeb::getActivePersonType();

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])
        || $model->status == 'confirm'
        || $model->status == 'pending_scholarships'
        || $model->status == 'pending_recommendations') {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}

$disabled = '';
$readonly = '';

$readonly_by_status = '';
if(!empty($model->email)){
    $disabled = 'disabled';
    $readonly = 'readonly';
}

if($model->status == 'confirm' 
        || $model->status == 'pending_recommendations'){
    $readonly_by_status = 'readonly';
}
?>

<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ข้อมูลเบื้องต้นบริษัท/ภาคอุตสาหกรรม / Company/Industry '
));
?>

<div class="x_content">

</div>
<script>

<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
    });

</script>
