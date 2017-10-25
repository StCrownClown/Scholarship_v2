
<?php
$person_type = ConfigWeb::getActivePersonType();

$isHistory = "style='display:none;'";
$model->is_history = 1;
if ($model->is_history == '1') {
    $isHistory = '';
}

$CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
$formAciton = $CurrentUrl;
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    $formAciton = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request));
}


?>
<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ประวัติการรับทุนอื่น'
));
?>

<!-- Bootstrap core CSS -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">

<link href="<?php echo Yii::app()->request->baseUrl; ?>/fonts/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/animate.min.css" rel="stylesheet">

<!-- Custom styling plus plugins -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom.css" rel="stylesheet">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/icheck/flat/green.css" rel="stylesheet">

<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
<style>
    .table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th{
        font-weight: normal;
    }
    .table>thead>tr>th{
        vertical-align: middle;
    }
    table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before{
        text-indent: 3px;
        font-weight: bold;
    }
    table{
        font-size: small;
    }
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <?php
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'minieducation-form',
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
                        echo $form->labelEx($model, 'is_history') . ' <br/>';
                        echo $form->radioButtonList($model, 'is_history', array(
                                '0' => ' ไม่เคย',
                                '1' => ' เคย'
                            ),
                             array(
                                'labelOptions' => array('style' => 'display:inline'), // add this code
                                'separator' => '<br/>',
                            )
                        );
                        echo $form->error($model, 'is_history');
                        echo '<br/>';
                        ?>
                    </div>
                </div>
                <br/>
                <div class="form-group" id="history_box" <?php echo $isHistory; ?>>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                        <p>หากท่านต้องการเพิ่มให้คลิ๊กที่ปุ่มนี้ / To add, click on this button.
                            <?php
                            $addText = '<i class="fa fa-plus"></i> '
                                    . '<b>Add</b>';
                            $addUrl = Yii::app()->createUrl('tgist/addhistory');
                            echo CHtml::link($addText, $addUrl, array(
                                'class' => 'btn btn-success'
                            ));
                            ?>
                        </p>
                        <?php } ?>
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="headings">
                                    <th class="column-title" style="width: 1%;text-align: center;">#</th>
                                    <th class="column-title" style="width: auto;text-align: center;">ระดับการศึกษา<br/>Degree</th>
                                    <th class="column-title" style="width: auto;text-align: center;white-space: nowrap;">ขื่อทุนที่ได้รับ<br/>Name</th>
                                    <th class="column-title" style="width: 1%;text-align: center;white-space: nowrap;">วันที่เริ่มรับทุน<br/>Begin</th>
                                    <?php if (empty(Yii::app()->session['tmpReadOnly'])) { ?>
                                    <th class="column-title no-link last" style="width: 1%;text-align: center;"><span class="nobr"></span></th>
                                    <?php } ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $this->widget('zii.widgets.CListView', array(
                                    'dataProvider' => $dataProvider,
                                    'enablePagination' => false,
                                    'itemView' => '_history',
                                    'template' => '{sorter}{items}{pager}',
                                    'htmlOptions' => array('class' => 'list-view-listings'),
                                ));
                                ?>
                            </tbody>
                        </table>
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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>

<!-- bootstrap progress js -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/progressbar/bootstrap-progressbar.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/nicescroll/jquery.nicescroll.min.js"></script>
<!-- icheck -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/icheck/icheck.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/custom.js"></script>

<!-- Datatables -->
<!-- <script src="<?php // echo Yii::app()->request->baseUrl;    ?>/js/datatables/js/jquery.dataTables.js"></script>
<script src="<?php // echo Yii::app()->request->baseUrl;    ?>/js/datatables/tools/js/dataTables.tableTools.js"></script> -->

<!-- Datatables-->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/buttons.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/jszip.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/pdfmake.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/vfs_fonts.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/buttons.html5.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/buttons.print.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/dataTables.keyTable.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/responsive.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datatables/dataTables.scroller.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable-responsive').DataTable();
        $('input[name="TgistHistoryForm[is_history]"]').change(function () {
            is_history = $('input[name="TgistHistoryForm[is_history]"]:checked').val();
            if (is_history == '0') {
                $('#history_box').hide();
            } else if (is_history == '1') {
                $('#history_box').show();
            }
        });
        
        
<?php
if (!empty(Yii::app()->session['tmpReadOnly'])) {
    echo "$('form select,form input, form textarea').attr('disabled', 'disabled').attr('readonly', 'readonly');";
}
echo "$('.x_panel').show();";
?>
        
    });
</script>