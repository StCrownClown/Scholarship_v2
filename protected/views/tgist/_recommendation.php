<?php
$person_type = ConfigWeb::getActivePersonType();
?>

<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <?php echo '<h3>ผู้ขอรับทุน / Information</h3>'; ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'student_name'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->student_name . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<?php if(!empty($model->professor_name)){ ?>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'professor_name'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->professor_name . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<?php } ?>
<?php if(!empty($model->mentor_name)){ ?>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'mentor_name'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->mentor_name . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<?php } ?>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'industrial_name'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->industrial_full . " - ". $model->industrial_name . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_name'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_name . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_objective'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_objective . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_scope'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_scope . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_student_name'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_student_name . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-3 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_student_begin'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_student_begin . '</u>'; ?>
        </div>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-12">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_student_end'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_student_end . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_student_func'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_student_func . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_student_objective'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_student_objective . '</u>'; ?>
        </div>
    </div>
</div>
<br/>
<div class="form-group">
    <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'project_student_expect'); ?><br/>
            <?php echo '<u style="color:green;">' . $model->project_student_expect . '</u>'; ?>
        </div>
    </div>
</div>
<br/>