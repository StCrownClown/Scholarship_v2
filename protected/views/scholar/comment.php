<?php $scholar_type = Yii::app()->session['scholar_type']; ?>

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
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>

<!--[if lt IE 9]>
      <script src="<?php // echo Yii::app()->request->baseUrl; ?>/../assets/js/ie8-responsive-file-warning.js"></script>
      <![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
        <script src="<?php // echo Yii::app()->request->baseUrl; ?>/https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="<?php // echo Yii::app()->request->baseUrl; ?>/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ข้อมูลทุน / Scholarship'
));
?>
<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">

                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="width: 1%;text-align: center;">#</th>
                            <th class="column-title" style="text-align: center;white-space: nowrap;">วันที่ส่งมา<br/>Incoming Date</th>
                            <!--<th class="column-title" style="width: 10%;text-align: center;white-space: nowrap;">ประเภททุน<br/>Type</th>-->
                            <th class="column-title" style="text-align: center;">ชื่อนักเรียน/นักศึกษา<br/>Student</th>
                            <th class="column-title" style="width: 1%;text-align: center;white-space: nowrap;">สถานะใบสมัคร<br/>Status</th>
                            <th class="column-title" style="text-align: center;white-space: nowrap;">วันที่แก้ไขล่าสุด<br/>Last Updated</th>
                            <th class="column-title no-link last" style="width: auto;text-align: center;"><span class="nobr">Action</span></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $this->widget('zii.widgets.CListView', array(
                            'dataProvider' => $dataProvider,
                            'enablePagination' => false,
                            'itemView' => '_comment',
                            'template' => '{sorter}{items}{pager}',
                            'htmlOptions' => array('class' => 'list-view-listings'),
                        ));
                        ?>
                    </tbody>
                </table>

            </div>
            <div class="actionBar">
                <?php
//                $addUrl = Yii::app()->createUrl('site/logout');
//                echo CHtml::link('← ออกจากระบบ / Logout', $addUrl, array(
//                    'class' => 'btn btn-danger',
//                    'style' => 'float: left;',
//                    'confirm' => "คุณต้องการออกจากระบบ หรือไม่?"
//                    . "\nWill you want to logout?",
//                ));
                ?>
            </div>
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
<!-- <script src="<?php // echo Yii::app()->request->baseUrl; ?>/js/datatables/js/jquery.dataTables.js"></script>
<script src="<?php // echo Yii::app()->request->baseUrl; ?>/js/datatables/tools/js/dataTables.tableTools.js"></script> -->

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
    });
</script>