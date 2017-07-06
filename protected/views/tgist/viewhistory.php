
<?php
$person_type = ConfigWeb::getActivePersonType();
?>
<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'การรับทุนอื่น / Other Scholarship'
));
?>
<style>
    .form-horizontal .form-group {
        margin-bottom: 10px;
    }
    .view_value{
        color: green;
        text-decoration: underline;
    }
</style>
<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'addhistory-form',
                    'action' => Yii::app()->createUrl('#'),
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal'
                    )
                ));
                ?>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'educationlevel_id') . '<br/>';
                        if (!empty($model->educationlevel_id))
                            echo "<p class='view_value'>" . InitialData::NstdamasEducationLevel($model->educationlevel_id) . "</p>";
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'name') . '<br/>';
                        echo "<p class='view_value'>" . $model->name . "</p>";
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'source') . '<br/>';
                        echo "<p class='view_value'>" . $model->source . "</p>";
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'description') . '<br/>';
                        echo "<p class='view_value'>" . $model->description . "</p>";
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'begin') . '<br/>';
                        echo "<p class='view_value'>" . date("d/m/Y", strtotime($model->begin)) . "</p>";
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'end') . '<br/>';
                        echo "<p class='view_value'>" . date("d/m/Y", strtotime($model->end)) . "</p>";
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        echo $form->labelEx($model, 'func_period') . '<br/>';
                        echo "<p class='view_value'>" . $model->func_period . "</p>";
                        ?>
                    </div>
                </div>

            </div>

            <div class="actionBar">
                <?php
                $addUrl = Yii::app()->createUrl('tgist/history');
                echo CHtml::link('ปิด / Close', $addUrl, array(
                    'class' => 'btn btn-danger',
                    'style' => 'float: left;',
                ));
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

</script>
