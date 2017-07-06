<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$params = require(dirname(__FILE__) . '/params.php');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Scholarship',
    // preloading 'log' component
    'preload' => array(
        'log',
        'booster',
//        'bootstrap',
        'input'),
    'language' => 'th',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.data.*',
        'application.models.table.*',
        'ext.yii-mail.YiiMailMessage',
    // 'ext.EDataTables.*',
    ),
    'controllerMap' => array(
        'BibTex' => 'ext.StructuresBibTex.Controller.BibTexController'
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '1234',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                'bootstrap.gii',
                'ext.modelI18N.gii',
            // 'ext.speedUpOci.gii',
            ),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('site' => 'login'),
        ),
        'booster' => array(
            'class' => 'ext.yiibooster4.components.Booster',
            'responsiveCss' => true,
        ),
//        'bootstrap' => array(
//            'class' => 'ext.yiibooster.components.Bootstrap',
//            'responsiveCss' => true,
//        ),
        'input' => array(
            'class' => 'CmsInput',
            'cleanPost' => true,
            'cleanGet' => true,
        ),
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php',
            //'transportOptions' => array(
                //'host' => 'ismtp.nstda.or.th', not work 9.5.2017
				//'host' => '10.226.202.13',
			//	'host' => 'osmtp.nstda.or.th',
            //    'port' => 25,
            //),
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => require(dirname(__FILE__) . '/database.php'),
        // uncomment the following to use a MySQL database
        /*
          'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=testdrive',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          ),
         */
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    'class' => 'CPHPMailerLogRoute',
                    'levels' => 'error',
                    'subject' => 'Error',
                    'emails' => array('jakkrich.changgon@nstda.or.th'),
                ),
            ),
        ),
//        'log' => array(
//            'class' => 'CLogRouter',
//            'routes' => array(
//                array(
//                    'class' => 'CFileLogRoute',
//                    'levels' => 'error, warning',
//                ),
//            // uncomment the following to show log messages on web pages
//            /*
//              array(
//              'class'=>'CWebLogRoute',
//              ),
//             */
//            ),
//        ),
    ),
    
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => $params,
);
