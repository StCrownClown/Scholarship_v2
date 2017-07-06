<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <!-- Bootstrap core CSS -->

        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">

        <link href="<?php echo Yii::app()->request->baseUrl; ?>/fonts/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/animate.min.css" rel="stylesheet">

        <!-- Custom styling plus plugins -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/icheck/flat/green.css" rel="stylesheet">

        <!--<script src="js/jquery.min.js"></script>-->

        <!--[if lt IE 9]>
              <script src="../assets/js/ie8-responsive-file-warning.js"></script>
              <![endif]-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
              <![endif]-->

        <style id="style-1-cropbar-clipper">/* Copyright 2014 Evernote Corporation. All rights reserved. */
            .en-markup-crop-options {
                top: 18px !important;
                left: 50% !important;
                margin-left: -100px !important;
                width: 200px !important;
                border: 2px rgba(255,255,255,.38) solid !important;
                border-radius: 4px !important;
            }

            .en-markup-crop-options div div:first-of-type {
                margin-left: 0px !important;
            }
        </style></head>


    <body class="pace-done nav-sm" view_id="" name="" no_dhx="1">
        <div class="pace  pace-inactive">
            <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
                <div class="pace-progress-inner"></div>
            </div>
            <div class="pace-activity"></div>   
        </div>

        <div class="container body">
            <div class="main_container">
                <!-- top navigation -->
                <?php if (!empty(Yii::app()->session['person_id']) || Yii::app()->session['user_type'] == 'admin') { ?>
                    <div class="top_nav">
                        <div class="nav_menu" style="position: fixed;z-index: 1000;">
                            <nav class="" role="navigation">
                                <?php $CurrentUrl = Yii::app()->urlManager->parseUrl(Yii::app()->request); ?>
                                <?php if ($CurrentUrl != WorkflowData::$home) { ?>  
                                    <div class="nav toggle visible-xs" style="width: 40px;">
                                        <a href="<?php echo Yii::app()->createUrl(WorkflowData::$home); ?>"><i class="fa fa-home"></i></a>
                                    </div>
                                <?php } ?>
                                <div class="nav_title animated flipInX" style="border:0;background:none;width:auto;" >
                                    <b>
                                        <span class="site_title" style="color: #5A738E;font-weight: bolder;" >
                                            <?php echo InitialData::FullNameScholar(Yii::app()->session['scholar_type']); ?>
                                            <span style="<?php echo (($_SERVER['SERVER_NAME']=='localhost')?'':'color:#ededed;') ?>">
                                                <?php echo Yii::app()->session['tmpActiveScholarId']; ?><?php echo ConfigWeb::getActiveScholarIdType(); ?><?php echo ConfigWeb::getActiveScholarIdComment(); ?>
                                            </span>
                                        </span>
                                    </b>
                                </div>
                                
                                <ul class="nav navbar-nav navbar-right" style="margin-right: 20px;">
                                    <?php if ($CurrentUrl == WorkflowData::$home || $CurrentUrl == 'scholar/comment') { ?>  
                                    <li class="visible-xs">
                                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            &nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;
                                        </a>
                                        <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                            <?php if (empty(Yii::app()->session['LoginByToken'])) { ?>  
                                            <li>
                                                <a href="<?php echo Yii::app()->createUrl('site/resetemail'); ?>" >
                                                    <i class="fa fa-envelope-o pull-left"></i>  Change Email
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo Yii::app()->createUrl('site/resetpassword'); ?>" >
                                                    <i class="fa fa-key pull-left"></i>  Change Password
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <li>
                                                <a onclick='return confirm("คุณต้องการออกจากระบบ หรือไม่\nWill you want to logout?");' href="<?php echo Yii::app()->createUrl('site/logout'); ?>">
                                                    <i class="fa fa-sign-out pull-right"></i>  Log Out
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <?php } ?>
                                    
                                    <?php if (Yii::app()->session['user_type'] == 'admin') { ?> 
                                        <li  class="visible-xs">
                                            <a onclick='return confirm("คุณต้องการออกจากระบบ หรือไม่\nWill you want to logout?");' href="<?php echo Yii::app()->createUrl('site/logout'); ?>">
                                                &nbsp;<i class="fa fa-sign-out"></i>&nbsp;
                                            </a>
                                        </li>
                                    <?php } ?> 
                                    
                                    <?php if ($CurrentUrl == WorkflowData::$home || $CurrentUrl == 'scholar/comment' || $CurrentUrl == 'scholar/admin') { ?>  
                                        <li role="presentation" class="dropdown hidden-xs">
                                            <a onclick='return confirm("คุณต้องการออกจากระบบ หรือไม่\nWill you want to logout?");' href="<?php echo Yii::app()->createUrl('site/logout'); ?>">
                                                <!--<i class="fa fa-power-off"></i>&nbsp;<div class="hidden-xs" style="display:inline;">Logout</div>-->
                                                Log out  <i class="fa fa-sign-out"></i>
                                            </a>
                                        </li>
                                    <?php } ?>
                                        
                                    <li class="">
                                        <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <?php
                                            if (!empty(Yii::app()->session['person_id'])) {
                                                $person_id = Yii::app()->session['person_id'];
                                                $criteria = new CDbCriteria;
                                                $criteria->condition = "id = $person_id";
                                                $criteria->limit = '1';
                                                $user = Person::model()->find($criteria);
                                                if (!empty($user->image_path)) {
                                                    $path = Yii::app()->baseUrl . Yii::app()->params['pathUploadsView'] . $user->image_path;
                                                    ?> 
                                                    <img src="<?= $path . "?" . time() ?>">
                                                <?php } else { ?>
                                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user.png<?= "?" . time() ?>">
                                                <?php } ?>
                                                <?php echo $user->fname; ?> <div class="hidden-xs" style="display:inline;"><?php echo $user->lname; ?></div>(<?php echo InitialData::PERSON_TYPE(Yii::app()->session['person_type']); ?>)
                                            <?php } else { ?>
                                                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/user.png<?= "?" . time() ?>">
                                                <?php if (Yii::app()->session['user_type'] == 'admin') {
                                                    echo Yii::app()->session['admin_name']." <div class='hidden-xs' style='display:inline;'>(".Yii::app()->session['user_type'].")</div>";
                                                } ?> 
                                            <?php } ?> 
                                            <span class=" fa fa-angle-down"></span>
                                        </a>
                                    </li>
                                    
                                    <?php if (($CurrentUrl == WorkflowData::$home || $CurrentUrl == 'scholar/comment') && empty(Yii::app()->session['LoginByToken'])) { ?>
                                        <li role="presentation" class="dropdown hidden-xs">
                                            <a href="<?php echo Yii::app()->createUrl('site/resetpassword'); ?>" >
                                                <i class="fa fa-key"></i>&nbsp;<div style="display: inline;">Change Password</div>
                                            </a>
                                        </li>
                                        <li role="presentation" class="dropdown hidden-xs">
                                            <a href="<?php echo Yii::app()->createUrl('site/resetemail'); ?>" >
                                                <i class="fa fa-envelope-o"></i>&nbsp;<div style="display: inline;">Change Email</div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                <?php } ?>
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="" style="padding: 60px 20px;">
                        <div class="clearfix"></div>

                        <?php // echo WorkflowData::isLastStep(Yii::app()->urlManager->parseUrl(Yii::app()->request)); ?>
                        <?php if (Yii::app()->session['user_type'] == 'user' || Yii::app()->session['user_type'] == 'admin') { ?>
                            <div class="hidden-xs">
                                <div class="form_wizard wizard_horizonta animated fadeIn">
                                    <?php $flowline = WorkflowData::ShowBreadcrumb(Yii::app()->urlManager->parseUrl(Yii::app()->request)); 
                                        if($flowline == WorkflowData::$home){
                                            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                        }else{
                                            echo $flowline;
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="visible-xs">
                                <div class="form_wizard wizard_horizontal animated fadeIn">
                                    <h1 style="float: left;padding-left:10px;color:#1ABB9C;font-weight: 900;font-size: 28pt;">
                                        <?php echo WorkflowData::ShowBreadcrumbName(Yii::app()->urlManager->parseUrl(Yii::app()->request)); ?>
                                    </h1>
                                    <h1 style="float: right;padding-right:10px;color:#1ABB9C;font-weight: 900;font-size: 28pt;">
                                        <?php echo WorkflowData::ShowBreadcrumbShort(Yii::app()->urlManager->parseUrl(Yii::app()->request)); ?>
                                    </h1>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="row animated fadeIn">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <?php echo $content; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- footer content -->
                    <!--                    <footer>
                                            <div class="copyright-info">
                    <?php // echo $this->renderPartial('_login_footer'); ?>
                                            </div>
                                            <div class="clearfix"></div>
                                        </footer>-->
                    <!-- /footer content -->

                </div>
                <!-- /page content -->
            </div>

        </div>

        <div id="custom_notifications" class="custom-notifications dsp_none">
            <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
            </ul>
            <div class="clearfix"></div>
            <div id="notif-group" class="tabbed_notifications"></div>
        </div>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>

        <!-- bootstrap progress js -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/progressbar/bootstrap-progressbar.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- icheck -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/icheck/icheck.min.js"></script>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/custom.js"></script>

        <!-- pace -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/pace/pace.min.js"></script>
        
        <div id="ascrail2000" class="nicescroll-rails" style="width: 5px; z-index: auto; cursor: -webkit-grab; position: absolute; top: 0px; left: 65px; height: 579px; display: none; opacity: 0;">
            <div style="position: relative; top: 0px; float: right; width: 5px; height: 0px; background-color: rgba(42, 63, 84, 0.34902); border: 0px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px;"></div>
        </div>
        <div id="ascrail2000-hr" class="nicescroll-rails" style="height: 5px; z-index: auto; top: 574px; left: 0px; position: absolute; display: none; width: 65px; opacity: 0;">
            <div style="position: relative; top: 0px; height: 5px; width: 0px; background-color: rgba(42, 63, 84, 0.34902); border: 0px solid rgb(255, 255, 255); background-clip: padding-box; border-radius: 5px; left: 0px;"></div>
        </div>
        <script type="text/javascript">

        </script>
    </body>
</html>