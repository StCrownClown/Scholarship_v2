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
    table{
        font-size: small;
    }
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>

<!--[if lt IE 9]>
      <script src="<?php // echo Yii::app()->request->baseUrl;                    ?>/../assets/js/ie8-responsive-file-warning.js"></script>
      <![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
        <script src="<?php // echo Yii::app()->request->baseUrl;                    ?>/https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="<?php // echo Yii::app()->request->baseUrl;                    ?>/https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
<?php
$this->renderPartial('../site/_x_title', array(
    'title' => 'ข้อมูลการรับสมัครนักเรียนทุน / Scholarship'
));
?>

<div class="x_content">
    <div class="row">
        <div id="wizard" class="form_wizard wizard_horizontal">
            <div class="wizard_content" style="display: block;">
                <?php 
                $status = StatusData::DisplayStatusBar(); 
                if(!empty($status)){
                ?>
                    <div class="col-md-12 col-sm-12 col-xs-12 hidden-xs" style="float:right">
                        <div style="float:right" class="badge bg-green">&nbsp;&nbsp;
                            <?php for($i=0;$i<sizeof($status);$i++){
                                if($i == sizeof($status)-1){
                                    echo "<span class=''>".InitialData::STATUS($status[$i])."</span>&nbsp;&nbsp;";
                                }else {
                                    echo "<span class=''>".InitialData::STATUS($status[$i])."</span>&nbsp;&nbsp;<i class='fa fa-arrow-right'></i>&nbsp;&nbsp;";
                                }
                            } ?>
                        </div>
                        <div style="float:right">
                            สถานะทุน:&nbsp;&nbsp;
                        </div>
                    </div>
                    <br/>
                <?php } ?>
                <div>
                    <?php
                    $CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request);
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'company-form',
                        'action' => Yii::app()->createUrl($CurrentUrl),
                        'enableAjaxValidation' => false,
                        'htmlOptions' => array(
                            'class' => 'form-horizontal'
                        )
                    ));
                    ?>

                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom: 0px;height: 60px;">
                            <?php
                            echo $form->labelEx($model, 'year') . '<br/>';
                            echo $form->dropDownList($model,'year', 
                                    InitialData::YearList(NULL, 10),
                                    array('options' => array($model->year=>array('selected'=>true))));

//                            $this->widget('booster.widgets.TbSelect2', array(
//                                'asDropDownList' => TRUE,
//                                'model' => $model,
//                                'name' => 'AdminSearchForm[year]',
//                                'data' => InitialData::YearList(NULL, 10),
//                                'value' => $model->year,
//                                'options' => array(
//                                    'width' => '100%',
//                                    'style' => 'display:inline'
//                                )
//                            ));
//                            echo $form->error($model, 'year');

                            $this->widget('booster.widgets.TbButton', array(
                                'label' => "Search",
                                'icon' => 'search',
                                'buttonType' => 'submit',
                                'htmlOptions' => array(
                                    'id' => 'next',
                                    'name' => 'next',
                                    'class' => 'btn btn-info',
                                    'style' => 'display:none'
                                ),
                            ));
                            
                            $this->widget('booster.widgets.TbButton', array(
                                'label' => "Export Zip",
                                'icon' => 'fa fa-download',
                                'buttonType' => 'submit',
                                'htmlOptions' => array(
                                    'name' => 'export_all_zip',
                                    'style' => 'float:right',
                                    'class' => 'btn btn-info',
                                ),
                            ));
                                 
                            $this->widget('booster.widgets.TbButton', array(
                                'label' => "Export Excel",
                                'icon' => 'fa fa-download',
                                'buttonType' => 'submit',
                                'htmlOptions' => array(
                                    'name' => 'export_excel',
                                    'style' => 'float:right',
                                    'class' => 'btn btn-success',
                                ),
                            ));
                            
                            $this->widget('booster.widgets.TbButton', array(
                                'label' => "Export STEM",
                                'icon' => 'fa fa-download',
                                'buttonType' => 'submit',
                                'htmlOptions' => array(
                                    'name' => 'export_excel_stem',
                                    'style' => 'float:right',
                                    'class' => 'btn btn-success',
                                ),
                            ));
                            
                            $this->widget('booster.widgets.TbButton', array(
                                'label' => "Export QuickReport(All)",
                                'icon' => 'fa fa-download',
                                'buttonType' => 'submit',
                                'htmlOptions' => array(
                                    'name' => 'export_excel_qr',
                                    'style' => 'float:right',
                                    'class' => 'btn btn-success',
                                ),
                            ));
                            ?>
                        </div>
                        
                        <div class="col-md-4 col-sm-3 col-xs-12"  style="padding-bottom: 0px;height: 60px;display:none">
<?php
//                            echo $form->labelEx($model, 'status') . '<br/>';
//                            $this->widget('booster.widgets.TbSelect2', array(
//                                'asDropDownList' => TRUE,
//                                'model' => $model,
//                                'name' => 'AdminSearchForm[status]',
//                                'data' => InitialData::STATUS_SEARCH(),
//                                'value' => $model->status,
//                                'options' => array(
//                                    'width' => '100%',
//                                )
//                            ));
//                            echo $form->error($model, 'status');
?>
                        </div>
                        <div class="col-md-4 col-sm-3 col-xs-12"  style="padding-bottom: 0px;height: 60px;display:none">
<?php
//                            echo $form->textFieldGroup($model, 'student', array(
//                                'class' => 'form-control',
//                                'widgetOptions' => array(
//                                    'htmlOptions' => array(
//                                        'placeholder' => '',
//                                    )
//                                ),
//                            ));
?>
                        </div>
                    </div>
                    <br/>
                    <!--<hr/>-->
                    <div class="login_content" style="display:none">
<?php
//                        $this->widget('booster.widgets.TbButton', array(
//                            'label' => "Search",
//                            'icon' => 'search',
//                            'buttonType' => 'submit',
//                            'htmlOptions' => array(
//                                'name' => 'next',
//                                'class' => 'btn btn-info',
//                            ),
//                        ));
?>
                    </div>
                        <?php
                        $this->endWidget();
                        unset($form);
                        ?>
                </div>
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="width: 1%;text-align: center;">#</th>
                            <!--<th class="column-title" style="width: 10%;text-align: center;white-space: nowrap;">ประเภททุน<br/>Type</th>-->
                            <th class="column-title" style="width: auto;text-align: center;">อาจารย์/นักวิจัย สวทช.<br/>Professor/Mentor</th>
                            <th class="column-title" style="width: auto;text-align: center;">ชื่อนักเรียน/นักศึกษา<br/>Student</th>
                            <th class="column-title" style="width: 100px;text-align: center;white-space: nowrap;">สถานะทุน<br/>Status</th>
                            <th class="column-title" style="width: 1%;text-align: center;white-space: nowrap;">วันที่แก้ไขล่าสุด<br/>Last Updated</th>
                            <th class="column-title no-link last" style="width: 1%;text-align: center;"><span class="nobr"></span></th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        $this->widget('zii.widgets.CListView', array(
                            'dataProvider' => $dataProvider,
                            'enablePagination' => false,
                            'itemView' => '_admin',
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
//        $addUrl = Yii::app()->createUrl('site/logout');
//        echo CHtml::link('ออกจากระบบ / Logout&nbsp;&nbsp;<i class="fa fa-sign-out"></i>', $addUrl, array(
//            'class' => 'btn btn-danger',
//            'icon'=>'sign-out',
//            'style' => 'float: left;',
//            'confirm' => "คุณต้องการออกจากระบบ หรือไม่?"
//            . "\nWill you want to logout?",
//        ));
?>
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
<!-- <script src="<?php // echo Yii::app()->request->baseUrl;                    ?>/js/datatables/js/jquery.dataTables.js"></script>
<script src="<?php // echo Yii::app()->request->baseUrl;                    ?>/js/datatables/tools/js/dataTables.tableTools.js"></script> -->

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

<script>
//    var handleDataTableButtons = function () {
//        0 !== $("#datatable-responsive").length && $("#datatable-responsive").DataTable({
//            dom: "Bfrtip",
//            buttons: [{
//                    extend: "excel",
//                    className: "btn btn-success",
//                    text: "<i class='fa fa-download'></i>&nbsp;&nbsp;Export Xls"
//                }, {
//                    id: "export_doc",
//                    className: "btn btn-info",
//                    text: "&nbsp;&nbsp;<i class='fa fa-download'></i>&nbsp;&nbsp;Export Doc"
//                }],
//            responsive: !0
//        })
//    }, TableManageButtons = function () {
//        return {
//            init: function () {
//                handleDataTableButtons()
//            }
//        }
//    }();
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable-responsive').DataTable({
            aLengthMenu: [
                [-1],
                ["All"]
            ],
            iDisplayLength: -1
        });
        
        $('#datatable-responsive_length, #datatable-responsive_info, #datatable-responsive_paginate').hide();
        $('#AdminSearchForm_year').change(function(){
            $("#next").click();
        });
        
//        $('#datatable-excel').DataTable();
//        TableManageButtons.init();
    });
</script>