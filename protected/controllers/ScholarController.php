<?php

class ScholarController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/login';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users
                'actions' => array('Home'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'index' actions
                'actions' => array('Index', 'Goto', 'Delete', 'Edit', 'View', 'Comment', 'Act', 'Admin', 'Word', 'ReadOnly'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    public function actionHome() {
        $this->render('home', array());
    }
    
    public function actionAdmin() {
        ConfigWeb::PageAdminOnly();
        ConfigWeb::ClearSessionTemp();
        $model = new AdminSearchForm;
        $model->year = date('Y', strtotime("+3 months", strtotime(date("Y-m-d"))));

        $model->status = '0';
        $model->student = NULL;

        $whereStu = ' ';
        $whereStatus = ' ';
        $whereOrg = " AND men.org_id=" . Yii::app()->session['admin_group'] . " ";
        if (Yii::app()->session['admin_group'] == 'all')
            $whereOrg = ' ';
        $whereYear = $model->year;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['AdminSearchForm'])) {
                $model->attributes = Yii::app()->input->post('AdminSearchForm');
                if(ConfigWeb::getActiveScholarType() == 'stem'){
                    if (isset($_POST['next'])) {
                        if ($model->status != '0') {
                            $whereStatus = " AND s.status = '" . $model->status . "' ";
                        }
                        if (!empty($model->student)) {
                            $whereStu = " AND CONCAT(std.fname,' ',std.lname) COLLATE utf8_unicode_ci LIKE '% " . $model->student . "%'";
                        }
                        $model->year = $model->year - 543;
                        $whereYear = $model->year;
                    } else if (isset($_POST['export_all_zip'])) {
                        $this->redirect(Yii::app()->createUrl(ConfigWeb::getActiveScholarType() . '/ExportAllZip', array('year' => $model->year)));
                    } else if (isset($_POST['export_excel'])) {
                        $this->redirect(Yii::app()->createUrl(ConfigWeb::getActiveScholarType() . '/ExportExcel', array('year' => $model->year)));
                    } else if (isset($_POST['export_excel_qr'])) {
                        $this->redirect(Yii::app()->createUrl(ConfigWeb::getActiveScholarType() . '/ExportExcelQuickReport', array('year' => $model->year)));
                    } else if (isset($_POST['export_excel_stem'])) {
                        $this->redirect(Yii::app()->createUrl(ConfigWeb::getActiveScholarType() . '/ExportExcelStem', array('year' => $model->year)));
                    }
                }
            }
        }

        $sql_count = "
                    SELECT
                        count(s.id)
                    FROM scholar s LEFT JOIN person std ON s.student_id = std.id
                      LEFT JOIN person men ON s.mentor_id=men.id
                      LEFT JOIN person pro ON s.professor_id=pro.id
                    WHERE s.type='" . Yii::app()->session['admin_scholar_type'] . "' 
                    AND YEAR(DATE_ADD(s.first_created, INTERVAL 3 MONTH))='" . $whereYear . "'
                    " . $whereStatus . "
                    " . $whereStu . "
                    " . $whereOrg . "
                    ORDER BY s.last_updated DESC";
        $count = Yii::app()->db->createCommand($sql_count)->queryScalar();
        $sql = "SELECT
                    s.id,
                    s.type AS type,
                    IF(s.professor_id IS NOT NULL,CONCAT(pro.fname,' ',pro.lname),IF(s.mentor_id IS NOT NULL,CONCAT(men.fname,' ',men.lname),'')) AS professor_mentor,
                    CONCAT(std.fname,' ',std.lname) AS student,
                    s.status AS status,
                    s.professor_id AS professor_id,
                    s.mentor_id AS mentor_id,
                    s.student_id AS student_id,
                    s.industrial_id AS industrial_id,
                    s.last_updated AS lastupdated,
					pro.token AS pro_token,
					men.token AS men_token,
					ind.token AS ind_token,
					std.token AS std_token
                FROM scholar s
				  LEFT JOIN person std ON s.student_id = std.id
                  LEFT JOIN person men ON s.mentor_id=men.id
                  LEFT JOIN person pro ON s.professor_id=pro.id
				  LEFT JOIN person ind ON s.industrial_id=ind.id
                WHERE s.type='" . Yii::app()->session['admin_scholar_type'] . "' 
                AND YEAR(DATE_ADD(s.first_created, INTERVAL 3 MONTH))='" . $whereYear . "'
                " . $whereStatus . "
                " . $whereStu . "
                " . $whereOrg . "
                ORDER BY s.last_updated DESC";
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 1000,
            ),
        ));

        $model->year = $model->year + 543;
        $this->render('admin', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
        
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        ConfigWeb::ClearSessionTemp();

        $sql = '';
        $sql_count = '';
        $where = '';
        $count = 0;
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = Yii::app()->session['person_id'];
        $person_type = Yii::app()->session['person_type'];
        $utype = Yii::app()->session['user_type'];

        $wf = new WorkflowData;
        $UrlName = Yii::app()->urlManager->parseUrl(Yii::app()->request);
        if ($utype == 'user') {
            if ($scholar_type == 'stem') {
                if ($person_type == 'professor' || $person_type == 'mentor') {
                    if ($person_type == 'professor') {
                        $where = ' WHERE s.professor_id=' . $person_id . " and s.type='".$scholar_type."'";
                    } else if ($person_type == 'mentor') {
                        $where = ' WHERE s.mentor_id=' . $person_id . " and s.type='".$scholar_type."'";
                    }
                    $sql_count = 'SELECT COUNT(*)
                        FROM scholar s LEFT JOIN person std ON s.student_id = std.id '
                            . $where
                            . ' ORDER BY s.last_updated DESC';
                    $count = Yii::app()->db->createCommand($sql_count)->queryScalar();
                    $sql = 'SELECT
                        s.id,
                        s.type AS type,
                        concat(std.fname,"  ",std.lname) AS student,
                        s.status AS status,
                        s.last_updated AS lastupdated
                    FROM scholar s LEFT JOIN person std ON s.student_id = std.id  '
                            . $where
                            . ' ORDER BY s.last_updated DESC ';
                } else if ($person_type == 'student' || $person_type == 'industrial') {
                    $this->redirect(Yii::app()->createUrl('scholar/comment'));
                }
                
            } 
            
            else if ($scholar_type == 'nuirc' || $scholar_type == 'tgist') {
                if ($person_type == 'student') {
                    $where = ' WHERE s.student_id=' . $person_id . " and s.type='".$scholar_type."'";
                }
                else if ($person_type != 'student') {
                    $where = ' WHERE s.' . $person_type . '_id=' . $person_id . " and s.type='".$scholar_type."'";
                    $this->redirect(Yii::app()->createUrl('scholar/comment'));
                }
                $sql_count = 'SELECT COUNT(*)
                    FROM scholar s LEFT JOIN person std ON s.student_id = std.id '
                        . $where
                        . ' ORDER BY s.last_updated DESC';
                $count = Yii::app()->db->createCommand($sql_count)->queryScalar();
//                $sql = 'SELECT
//                    s.id,
//                    s.type AS type,
//                    concat(std.fname,"  ",std.lname) AS student,
//                    s.status AS status,
//                    s.last_updated AS lastupdated
//                FROM scholar s LEFT JOIN person std ON s.student_id = std.id  '
//                        . $where
//                        . ' ORDER BY s.last_updated DESC ';
                $sql = 'SELECT
                    s.id,
                    s.type AS type,
                    concat(std.fname, "  ", std.lname) AS student,
                    concat(pp.fname, "  ", pp.lname) AS pro_men_ind,
                    s.status AS status,
                    c.status AS status_comment,
                    s.last_updated AS lastupdated
                FROM scholar s LEFT JOIN person std ON s.student_id = std.id 
                LEFT JOIN comment c ON s.id = c.scholar_id 
                LEFT JOIN person pp ON pp.id = c.person_id '
                        . $where
                        . ' ORDER BY s.last_updated DESC ';
            }
        } else if ($utype == 'admin') {
            $this->redirect(Yii::app()->createUrl('scholar/admin'));
        }
        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 1000,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionComment() {
        ConfigWeb::ClearSessionTemp();

        $sql = '';
        $sql_count = '';
        $where = '';
        $count = 0;
        $person_id = Yii::app()->session['person_id'];
        $person_type = Yii::app()->session['person_type'];

        $sql_count = "SELECT
                            COUNT(*)
                        FROM comment c LEFT JOIN scholar s ON c.scholar_id = s.id 
                        LEFT JOIN person std ON s.student_id = std.id
                        WHERE c.person_id=$person_id
                        ORDER BY c.first_created DESC, c.last_updated DESC";
        $count = Yii::app()->db->createCommand($sql_count)->queryScalar();
        $sql = "SELECT
                        s.id,
                        c.first_created AS firstcreated,
                        s.type AS type,
                        concat(std.fname,' ',std.lname) AS student,
                        s.status AS status,
                        c.status AS status_comment,
                        c.last_updated AS lastupdated
                    FROM comment c LEFT JOIN scholar s ON c.scholar_id = s.id 
                    LEFT JOIN person std ON s.student_id = std.id
                    WHERE c.person_id=$person_id
                    ORDER BY c.first_created DESC, c.last_updated DESC ";

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 1000,
            ),
        ));

        $this->render('comment', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionWord() {
        $id = Yii::app()->request->getQuery('id');
        $scholar_type = Yii::app()->request->getQuery('scholartype');
        if (empty($id) || empty($scholar_type))
            throw new CHttpException(404, 'The requested page does not exist.');

        Yii::app()->session['tmpActiveScholarId'] = $id;
        Yii::app()->session['scholar_type'] = $scholar_type;
        $this->redirect(Yii::app()->createUrl($scholar_type . '/ExportAttachmentZip'));
    }

    public function actionView() {
        $id = Yii::app()->request->getQuery('id');
        $scholar_type = Yii::app()->request->getQuery('scholartype');
        if (empty($id) || empty($scholar_type))
            throw new CHttpException(404, 'The requested page does not exist.');

        Yii::app()->session['tmpActiveScholarId'] = $id;
        Yii::app()->session['scholar_type'] = $scholar_type;
        Yii::app()->session['tmpReadOnly'] = TRUE;
        $this->redirect(Yii::app()->createUrl($scholar_type . '/index'));
    }

    public function actionEdit() {
        $id = Yii::app()->request->getQuery('id');
        $scholar_type = Yii::app()->request->getQuery('scholartype');
        if (empty($id) || empty($scholar_type))
            throw new CHttpException(404, 'The requested page does not exist.');

        Yii::app()->session['tmpActiveScholarId'] = $id;
        Yii::app()->session['scholar_type'] = $scholar_type;

        $url = $scholar_type . '/index';
        $wf = WorkflowData::getWorkflow();
        $max = sizeof($wf) - 1;
        if (WorkflowData::getMaxPage() > 0) {
            if (WorkflowData::getMaxPage() == $max) {
                $url = $wf[strval(WorkflowData::getMaxPage())];
            } else {
                if(!WorkflowData::getUseSkipWorkflow())
                    $url = $wf[strval(WorkflowData::getMaxPage() + 1)];
                else{
                    $steppage = WorkflowData::getMaxPage();
                    $max = count($wf);
                    for($i=0;$i<$max-1;$i++){
                        if($steppage & pow(2, $i))
                            $url = '';
                        else{
                            $url = $wf[strval($i+1)];
                            $i = $max;
                        }
                    }
                    if(empty($url))
                        $url = $wf[strval($max-1)];
                }
            }
        }
        $this->redirect(Yii::app()->createUrl($url));
    }

    public function actionDelete() {
        $id = Yii::app()->request->getQuery('id');
        $scholar_type = Yii::app()->request->getQuery('scholartype');
        $person_type = ConfigWeb::getActivePersonType();
        $person_id = ConfigWeb::getActivePersonId();
        
        $criteria = new CDbCriteria;
        $criteria->condition = "id = " . $id . " and " . strtolower($person_type) . '_id = ' .$person_id;
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);

        $ScholarXId = $Scholar['scholar' . ucfirst($scholar_type)]->id;
        $Scholar->delete();
        Yii::app()->db->createCommand('DELETE FROM scholar_' . $scholar_type . ' WHERE id = ' . $ScholarXId)->query();
//        Yii::app()->db->createCommand('DELETE FROM comment WHERE scholar_id = ' . $ScholarXId)->query();

        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
    }

    public function actionAct() {
        $id = Yii::app()->request->getQuery('id');
        $scholar_type = Yii::app()->request->getQuery('scholartype');
        $action = Yii::app()->request->getQuery('action');
        $utype = Yii::app()->request->getQuery('utype');

        $this->redirect(Yii::app()->createUrl($scholar_type . "/" . $action, array('id' => $id, 'utype' => $utype)));
    }

    public function actionReadOnly() {
        $id = Yii::app()->request->getQuery('id');
        $scholar_type = Yii::app()->request->getQuery('scholartype');
        $utype = Yii::app()->request->getQuery('utype');
        $pid = Yii::app()->request->getQuery('pid');
        
        if (empty($id) || empty($scholar_type) || empty($utype)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $criteria = new CDbCriteria;
        if(Yii::app()->session['user_type'] == 'admin'){
            $criteria->condition = "id = " . $id . " and " . $utype . "_id = " . $pid;
        }else{
            $criteria->condition = "id = " . $id . " and " . Yii::app()->session['person_type'] . "_id = " . Yii::app()->session['person_id'];
        }
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);

        if (!empty($Scholar)) {
            $targetId = $Scholar[$utype . "_id"];
            Yii::app()->session['tmpActiveScholarId'] = $Scholar->id;
            Yii::app()->session['tmpReadOnly'] = TRUE;
            Yii::app()->session['tmpActivePersonId'] = $targetId;
            Yii::app()->session['tmpActivePersonType'] = $utype;
            $this->redirect(Yii::app()->createUrl($scholar_type . '/index'));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    public function actionGoto() {
        $person_id = Yii::app()->session['person_id'];
        $person_type = Yii::app()->session['person_type'];
        $scholar_type = ConfigWeb::getActiveScholarType();

        if ($scholar_type == 'nuirc' || $scholar_type == 'tgist') {
            $fiscal_year = date("Y-m-d H:i:s", strtotime('-1 years'));
            $criteria = new CDbCriteria;
            $criteria->condition = "student_id = " . $person_id . " and type = '" . $scholar_type . "' and first_created >= '" . $fiscal_year . "'";
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            if (is_null($Scholar)) {
                $this->redirect(Yii::app()->createUrl($scholar_type . "/goto"));
            }
            else {
                Yii::app()->user->setFlash('error','คุณได้ลงทะเบียนสมัครทุน ' . strtoupper($scholar_type) . ' ในปีนี้แล้ว </br></br>');
                $this->redirect(Yii::app()->createUrl("scholar/index"));
            }
        }
        else {
            $this->redirect(Yii::app()->createUrl($scholar_type . "/goto"));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Scholar::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'scholar-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
