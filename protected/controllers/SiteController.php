<?php

class SiteController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/login';

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (Yii::app()->user->isGuest) {
            $model = new LoginForm;
            $this->redirect(Yii::app()->createUrl('site/login'));
        }
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionPreRegister() {
        $type_person = Yii::app()->request->getQuery('type');
        if (isset($type_person)) {
            Yii::app()->session['person_type'] = Yii::app()->request->getQuery('type');
            $this->redirect(Yii::app()->createUrl('site/register'));
        }

        $this->render('preregister');
    }

    public function validateRegister($model = null) {
        $error = false;
        if ($model) {
            if (strlen($model->nationality_id) == NULL) {
                Yii::app()->user->setFlash('nationality_id', 'โปรดระบบ สัญชาติ / Please specify nationality.');
                $error = true;
            }
            if (strlen($model->nationality_id) == '17') {
                if (strlen($model->id_card) != 13) {
                    Yii::app()->user->setFlash('id_card', 'เลขบัตรประชาชนไม่ครบ 13 หลัก');
                    $error = true;
                } else {
                    $sum = 0;
                    for ($i = 0; $i < 12; $i++) {
                        $sum += intval($model->id_card[$i]) * (13 - $i);
                    }
                    if ((11 - $sum % 11) % 10 != intval($model->id_card[12])) {
                        Yii::app()->user->setFlash('id_card', 'เลขบัตรประชาชนไม่ถูกต้อง');
                        $error = true;
                    }
                }
            }
        }
        return $error;
    }

    public function actionRegister() {
        $model = new RegisterForm;
        $error = false;
        if (!isset(Yii::app()->session['person_type'])) {
            $this->redirect(Yii::app()->createUrl('site/preregister'));
        }
        $model->type = Yii::app()->session['person_type'];

        if (!empty(Yii::app()->session['person_id'])) {
            $criteria = new CDbCriteria;
            $criteria->condition = "id = " . Yii::app()->session['person_id'];
            $criteria->limit = 1;
            $records = Person::model()->find($criteria);
            $model->attributes = $records->attributes;
            $model->id = $records->id;
            $model->password = Yii::app()->session['tmpPass'];
        }

        if (empty($model->nationality_id)) {
            $model->nationality_id = '17';
        }
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->input->post('RegisterForm');
            if ($model->validate()) {
                if ($model->nationality_id == '0') {
                    $model->nationality_id = NULL;
                }
                $model->id = NULL;
                $model->type = Yii::app()->session['person_type'];
                $model->nationality_id = intval($model->nationality_id);
                $model->first_created = date("Y-m-d H:i:s");
                $model->last_updated = date("Y-m-d H:i:s");
                $tmpPass = $model->password;
                $model->password = md5($model->password . Yii::app()->params['PrivateKeyPWD']);
                $model->confirmPassword = md5($model->confirmPassword . Yii::app()->params['PrivateKeyPWD']);
//----------------- GEN TOKEN ------------------------
                $getTokenRand = rand(0, 99999);
                $getTime = date("H:i:s");
                $email = $model->email;
                $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);

                $error = $this->validateRegister($model);
                
                if (!$error) {
                    if ($model->validate()) {
                        if ($model->save()) {
                            if (!@mkdir(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $model->id, 0, true)) {
                            }
                            $model->password = $tmpPass;
                            $model->confirmPassword = $tmpPass;
                            Yii::app()->session['person_id'] = $model->id;
                            Yii::app()->session['tmpPass'] = $tmpPass;
                            $this->redirect(Yii::app()->createUrl('site/register'));
                        } else {
                            $model->password = $tmpPass;
                            $model->confirmPassword = $tmpPass;
                            $this->redirect(Yii::app()->createUrl('site/register'));
                        }
                    }
                }
            }
        }

        $model->password = NULL;
        $model->confirmPassword = NULL;
        $this->render('register', array('model' => $model, 'type' => Yii::app()->session['person_type']));
    }

    public function getTokenRand($token) {
        $model = Person::model()->findByAttributes(array('token' => $token));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
    public function getTokenAdminRand($token, $type) {
        $model = Account::model()->findByAttributes(array('token' => $token, 'type' => $type, 'active' => 1));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
    
    public function actionVerifyAdminToken() {
        $token = Yii::app()->request->getQuery('token');
        $scholar_type = strtolower(Yii::app()->request->getQuery('scholartype'));
        if (empty($scholar_type)) {
            $scholar_type = ConfigWeb::getActiveScholarType();
        }else{
            Yii::app()->session['scholar_type'] = $scholar_type;
        }
        
        if ($token === NULL || $scholar_type === NULL) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $user = $this->getTokenAdminRand($token, $scholar_type);
            $model = new LoginForm;

            $model->username = $user->username;
            $model->password = $token;
            if ($model->validate() && $model->loginAdmin()) {
                Yii::app()->session['user_type'] = 'admin';
                Yii::app()->session['admin_name'] = $user->username;
                Yii::app()->session['admin_scholar_type'] = $user->type;
                Yii::app()->session['admin_group'] = $user->group;
                
                $getTokenRand = rand(0, 99999);
                $getTime = date("H:i:s");
                $username = $user->username;
                $user->token = RegisterForm::hashPassword($getTokenRand . $getTime . $username);
                $user->setIsNewRecord(FALSE);
                if ($user->update()) {}
            }
        }
        $this->redirect(Yii::app()->createUrl(WorkflowData::$home, array(
                'scholartype' => $scholar_type
            ))
        );
    }
    
    public function actionVerifyToken() {
        $token = Yii::app()->request->getQuery('token');
        $scholar_type = strtolower(Yii::app()->request->getQuery('scholartype'));
        $pass = Yii::app()->request->getQuery('pass');
        if (empty($scholar_type)) {
            $scholar_type = ConfigWeb::getActiveScholarType();
        }else{
            Yii::app()->session['scholar_type'] = $scholar_type;
        }
        
        if ($token === NULL || $scholar_type === NULL) {
            throw new CHttpException(404, 'The requested page does not exist.');
        } else {
            $user = $this->getTokenRand($token);
            $model = new LoginForm;

            $model->username = $user->email;
            $model->password = $token;
            if ($model->login()) {
                Yii::app()->session['user_type'] = 'user';
                Yii::app()->session['person_id'] = $user->id;
                Yii::app()->session['person_type'] = $user->type;
                Yii::app()->session['token'] = $user->token;
                if($pass == '1')
                    Yii::app()->session['LoginByToken'] = FALSE;
                else
                    Yii::app()->session['LoginByToken'] = TRUE;
            }
        }
        $this->redirect(Yii::app()->createUrl(WorkflowData::$home, array(
                'scholartype' => $scholar_type
            ))
        );
    }

    public function actionResetEmail() {
        if (!empty(Yii::app()->session['LoginByToken']) || empty(Yii::app()->session['person_id'])) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $model = new ResetEmailForm;
        $user = Person::model()->findByAttributes(array('id' => Yii::app()->session['person_id']));
        $model->id = $user->id;
        $model->email_old = $user->email;
        
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['ResetEmailForm'])) {
                $model->attributes = Yii::app()->input->post('ResetEmailForm');

                $tmpEmail = $model->email;
                $tmpConPass = $model->confirmEmail;

                if ($model->validate()) {
                    $model->attributes = $user->attributes;

                    $getTokenRand = rand(0, 99999);
                    $getTime = date("H:i:s");
                    $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $tmpEmail);

                    $model->id = $user->id;
                    $model->first_created = $user->first_created;
                    $model->email = $tmpEmail;
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        Yii::app()->user->setFlash('result', '<p style="color:green;">Email ได้ถูกเปลี่ยนเรียบร้อยแล้ว'
                                . '<br/>Email has been successfully changed!</p>');
                        Yii::app()->session['tmpEmail'] = $tmpEmail;
                        $this->redirect(Yii::app()->createUrl('site/resetemail'));
                    }
                } else {
                    $model->email = $tmpEmail;
                    $model->confirmEmail = $tmpConPass;
                }
            }
        }

        if (!empty(Yii::app()->session['tmpEmail'])) {
            $model->email = Yii::app()->session['tmpEmail'];
            $model->confirmEmail = Yii::app()->session['tmpEmail'];
        }

        $this->render('resetemail', array(
            'model' => $model
        ));
    }

    public function actionResetPassword() {
        if (!empty(Yii::app()->session['LoginByToken']) || empty(Yii::app()->session['person_id'])) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $model = new ResetPasswordForm;
        $user = Person::model()->findByAttributes(array('id' => Yii::app()->session['person_id']));
        $model->id = $user->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['ResetPasswordForm'])) {
                $model->attributes = Yii::app()->input->post('ResetPasswordForm');

                $tmpPass = $model->password;
                $tmpConPass = $model->confirmPassword;

                if ($model->validate()) {
                    $model->attributes = $user->attributes;
                    $model->id = $user->id;
                    $model->first_created = $user->first_created;
                    $model->password = RegisterForm::hashPassword($tmpPass);
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        Yii::app()->user->setFlash('result', '<p style="color:green;">รหัสผ่านได้ถูกเปลี่ยนเรียบร้อยแล้ว'
                                . '<br/>Password has been successfully changed!</p>');
                        Yii::app()->session['tmpPass'] = $tmpPass;
                        $this->redirect(Yii::app()->createUrl('site/resetpassword'));
                    }
                } else {
                    $model->password = $tmpPass;
                    $model->confirmPassword = $tmpConPass;
                }
            }
        }

        if (!empty(Yii::app()->session['tmpPass'])) {
            $model->password = Yii::app()->session['tmpPass'];
            $model->confirmPassword = Yii::app()->session['tmpPass'];
        }

        $this->render('resetpassword', array(
            'model' => $model
        ));
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function actionForgetPassword() {
        $model = new ForgetPasswordForm;
        $serverDomain = Yii::app()->params['serverDomain'];

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->input->post('ForgetPasswordForm');
            if ($model->validate()) {
                $criteria = new CDbCriteria;
                $criteria->condition = "email = '$model->email' and id_card = '$model->id_card' and nationality_id = '$model->nationality_id'";
                $criteria->limit = 1;
                $records = Person::model()->find($criteria);

                if ($records == NULL) {
                    Yii::app()->user->setFlash('error', 'กรอกข้อมูลทั้งหมดไม่ตรงกัน');
                } else {
//                    $getTokenRand = rand(0, 99999);
//                    $getTime = date("H:i:s");
//                    $email = $records->email;
//                    $records->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                    $tmpPass = $this->randomPassword();
                    $records->password = RegisterForm::hashPassword($tmpPass);
                    if ($records->validate()) {
                        if ($records->save()) {
                            Yii::app()->user->setFlash('error', 'การรีเซ็ตรหัสผ่านของคุณได้ถูกส่งไปยังอีเมลของคุณ'
                                    . '<br/>Link to reset your password has been sent to your email');

                            $SendMail = new SendMail;
                            $SendMail->subject = Yii::app()->params['EmailTemplateResetPasswordSubject'];
                            $message = Yii::app()->params['EmailTemplateResetPasswordBody'];
                            $message = str_replace("##EMAIL##", $records->email, $message);
                            $message = str_replace("##PERSONNAME##", $records->email, $message);
                            $message = str_replace("##USERNAME##", $records->email, $message);
                            $message = str_replace("##PASSWORD##", $tmpPass, $message);
                            $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                            $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                'token' => $records->token,
                                'scholartype' => ConfigWeb::getActiveScholarType()
                            ));
                            //$URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
							$URL = 'http://www.biotec.or.th/nstdaScholarship_v2/index.php/site/logout';
                            $message = str_replace("##URL##", $URL, $message);
                                            
                            $SendMail->body = $message;
                            $SendMail->to = $records->email;
                            $SendMail->from = "noreply@nstda.or.th";
                            $SendMail->send();
//                            $this->refresh();
                            $UrlNext = WorkflowData::$home;
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    }
                }
            }
        }

        $this->render('forgetpassword', array('model' => $model));
    }

    public function actionDownload($typedw = NULL, $scholar = NULL) {
        if ($typedw !== NULL) {
            $person_id = ConfigWeb::getActivePersonId();
            $person_type = ConfigWeb::getActivePersonType();

            $criteria = new CDbCriteria;
            if ($scholar == NULL) {
                $criteria->condition = "id = $person_id";
                $criteria->limit = '1';
                $user = Person::model()->find($criteria);

                $path = Yii::app()->basePath . Yii::app()->params['pathUploads'] . $user[$typedw];
                if (file_exists($path)) {
                    return Yii::app()->getRequest()->sendFile($user[$typedw], @file_get_contents($path));
                } else {
                    throw new CHttpException(404, 'The specified post cannot be found.');
                }
            }else {
                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId() . " and " . $person_type . "_id = " . $person_id;
                $criteria->limit = 1;
                $Scholar = Scholar::model()->find($criteria);
                
                $path = Yii::app()->basePath . Yii::app()->params['pathUploads'] . $Scholar->scholarStem[$typedw];
                if (file_exists($path)) {
                    return Yii::app()->getRequest()->sendFile($Scholar->scholarStem[$typedw], @file_get_contents($path));
                } else {
                    throw new CHttpException(404, 'The specified post cannot be found.');
                }
            }
        }
    }

    public function actionMiniAttachment() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        $modelStemRec = NULL;
        if($person_type == 'student'){
            $modelStemRec = new StemRecommendationForm ();
            $sql = "SELECT 
                    CONCAT(s.fname,'  ',s.lname) AS student_name,
                    CONCAT(p.fname,'  ',p.lname) AS professor_name,
                    CONCAT(m.fname,'  ',m.lname) AS mentor_name,
                    CONCAT(i.fname,'  ',i.lname) AS industrial_name,
                        i.industrial AS industrial_full,
                        project.name AS project_name,
                        project.objective AS project_objective,
                        project.scope AS project_scope,
                        scholar_stem.project_name AS project_student_name,
                        scholar_stem.project_begin AS project_student_begin,
                        scholar_stem.project_end AS project_student_end,
                        scholar_stem.objective AS project_student_objective,
                        scholar_stem.expect AS project_student_expect,
                        scholar_stem.id AS scholar_stem_id
                    FROM
                    scholar
                    LEFT JOIN scholar_stem ON scholar.scholar_stem_id = scholar_stem.id
                    LEFT JOIN project ON scholar_stem.project_id = project.id
                    LEFT JOIN person s ON scholar.student_id = s.id 
                    LEFT JOIN person p ON scholar.professor_id = p.id 
                    LEFT JOIN person m ON scholar.mentor_id = m.id 
                    LEFT JOIN person i ON scholar.industrial_id = i.id 
                    WHERE scholar.id=" . ConfigWeb::getActiveScholarId();
            $RecommendationDisplay = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($RecommendationDisplay [0] as $key => $value) {
                if ($key != 'scholar_stem_id')
                    $modelStemRec->$key = $value;
            }
            
            if ($modelStemRec->project_student_begin !== NULL && $modelStemRec->project_student_end !== NULL) {
                $diff = ConfigWeb::GetPeriodDate($modelStemRec->project_student_begin, $modelStemRec->project_student_end);
                if ($diff) {
                    $modelStemRec->project_student_func = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
                } else {
                    $modelStemRec->project_student_func = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
                }
            }
        }
        $model = new UploadAttachmentForm();
        $criteria = new CDbCriteria;
        $criteria->condition = "id = $person_id";
        $criteria->limit = 1;
        $user = Person::model()->find($criteria);
        
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId() . " and " . $person_type . "_id = " . $person_id;
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
                                
        $model->attributes = $Scholar->scholarStem->attributes;
        $model->id = $Scholar->scholarStem->id;
        
        if($person_type == 'professor' || $person_type == 'mentor'){
            $model->cv_path = $user->cv_path;
            $model->professor_mentor_attachment_project_path = $Scholar->scholarStem->professor_mentor_attachment_project_path;
            $model->professor_mentor_attachment_other_path = $Scholar->scholarStem->professor_mentor_attachment_other_path;
            $model->industrial_certificate_path = $Scholar->scholarStem->industrial_certificate_path;
            $model->industrial_join_path = $Scholar->scholarStem->industrial_join_path;
        }else if($person_type == 'student'){
            $model->student_card_path = $Scholar->scholarStem->student_card_path;
            $model->student_transcript_path = $Scholar->scholarStem->student_transcript_path;
            $model->copy_id_card_path = $user->copy_id_card_path;
            $model->student_portfolio_path = $Scholar->scholarStem->student_portfolio_path;
            $model->student_attachment_other_path = $Scholar->scholarStem->student_attachment_other_path;
            $model->student_attachment_other2_path = $Scholar->scholarStem->student_attachment_other2_path;
        }else if($person_type == 'industrial'){
            $model->industrial_certificate_path = $Scholar->scholarStem->industrial_certificate_path;
            $model->industrial_join_path = $Scholar->scholarStem->industrial_join_path;
            $model->industrial_attachment_other_path = $Scholar->scholarStem->industrial_attachment_other_path;
        }
        
        $criteria->condition = ""
                . "scholar_id = " . ConfigWeb::getActiveScholarId()
                . " and person_id = " . $person_id;
        $criteria->limit = 1;
        $Comment = Comment::model()->find($criteria);
        if(!empty($Comment))
            $model->status = $Comment->status;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['UploadAttachmentForm'])) {
                $model->attributes = Yii::app()->input->post('UploadAttachmentForm');
                if (isset($_POST['upload'])) {
                    if($person_type == 'professor' || $person_type == 'mentor'){
                        $FileUpload = CUploadedFile::getInstance($model, 'cv');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' . "cv.$ext";
                            $user->cv_path =  $fileName;
                            $model->cv_path =  $fileName;
                            $user->first_created = $user->first_created;
                            $user->last_updated = date("Y-m-d H:i:s");
                            $user->setIsNewRecord(FALSE);
                            if($model->validate('cv')){
                                if ($user->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'professor_mentor_attachment_project');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_professor_mentor_attachment_project.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->professor_mentor_attachment_project_path = $fileName;
                            $model->professor_mentor_attachment_project_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('professor_mentor_attachment_project')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_certificate');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $Scholar->industrial_id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_certificate.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->industrial_certificate_path = $fileName;
                            $model->industrial_certificate_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('industrial_certificate')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_join');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $Scholar->industrial_id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_join.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->industrial_join_path = $fileName;
                            $model->industrial_join_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('industrial_join')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'professor_mentor_attachment_other');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_professor_mentor_attachment_other.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->professor_mentor_attachment_other_path = $fileName;
                            $model->professor_mentor_attachment_other_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('professor_mentor_attachment_other')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] .  $fileName);
                                }
                            }
                        }
                    }else if($person_type == 'student'){
                        $FileUpload = CUploadedFile::getInstance($model, 'student_card');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_card.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->student_card_path = $fileName;
                            $model->student_card_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('student_card')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_transcript');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_transcript.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->student_transcript_path = $fileName;
                            $model->student_transcript_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('student_transcript')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'copy_id_card');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' ."copy_id_card.$ext";
                            $user->copy_id_card_path = $fileName;
                            $model->copy_id_card_path = $fileName;
                            $user->first_created = $user->first_created;
                            $user->last_updated = date("Y-m-d H:i:s");
                            $user->setIsNewRecord(FALSE);
                            if($model->validate('copy_id_card')){
                                if ($user->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_portfolio');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_portfolio.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->student_portfolio_path = $fileName;
                            $model->student_portfolio_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('student_portfolio')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_attachment_other');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' . ConfigWeb::getActiveScholarId()."_student_attachment_other.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->student_attachment_other_path = $fileName;
                            $model->student_attachment_other_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('student_attachment_other')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_attachment_other2');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_attachment_other2.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->student_attachment_other2_path = $fileName;
                            $model->student_attachment_other2_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('student_attachment_other2')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                    }else if($person_type == 'industrial'){
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_certificate');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_certificate.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->industrial_certificate_path = $fileName;
                            $model->industrial_certificate_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('industrial_certificate')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_join');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_join.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->industrial_join_path = $fileName;
                            $model->industrial_join_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('industrial_join')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_attachment_other');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_attachment_other.$ext";
                            $Scholar->scholarStem->id = $Scholar->scholarStem->id;
                            $Scholar->scholarStem->industrial_attachment_other_path = $fileName;
                            $model->industrial_attachment_other_path = $fileName;
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if($model->validate('industrial_attachment_other')){
                                if ($Scholar->scholarStem->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                    }
                    $errores = $model->getErrors();
                    if(!empty($errores)){
                        foreach ($errores as $key=>$value) {
                            Yii::app()->user->setFlash('error_'.$key, $model->attributeLabels()[$key] ." - ". $value[0]);
                        }
                    }
                }
                
                $error = '';
                if (isset($_POST['next']) || isset($_POST['savesend']) || isset($_POST['draft']) || isset($_POST['save'])) {
                    if ($person_type == 'professor' || $person_type == 'mentor') {
                        if (empty($user->cv_path)){
                            $error = 'error';
                            Yii::app()->user->setFlash('error1', $model->attributeLabels()['cv_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                        if (empty($Scholar->scholarStem->professor_mentor_attachment_project_path)){
                            $error = 'error';
                            Yii::app()->user->setFlash('error2', $model->attributeLabels()['professor_mentor_attachment_project_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                    } else if ($person_type == 'student') {
                        if (empty($Scholar->scholarStem->student_transcript_path)) {
                            $error = 'error';
                            Yii::app()->user->setFlash('error2', $model->attributeLabels()['student_transcript_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                        if (empty($user->copy_id_card_path)) {
                            $error = 'error';
                            Yii::app()->user->setFlash('error3', $model->attributeLabels()['copy_id_card_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                        if (empty($Scholar->scholarStem->student_card_path)) {
                            $error = 'error';
                            Yii::app()->user->setFlash('error1', $model->attributeLabels()['student_card_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                    } else if ($person_type == 'industrial') {
                        if (empty($Scholar->scholarStem->industrial_certificate_path)) {
                            $error = 'error';
                            Yii::app()->user->setFlash('error1', $model->attributeLabels()['industrial_certificate_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                        if (empty($Scholar->scholarStem->industrial_join_path)) {
                            $error = 'error';
                            Yii::app()->user->setFlash('error2', $model->attributeLabels()['industrial_join_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                    }
                    
                    if ((isset($_POST['draft']) || isset($_POST['savesend']) || isset($_POST['save'])) && $error == '') {
                        if (isset($_POST['draft'])) {
                            $Comment->status = 'draft';
                        } else if (isset($_POST['savesend'])) {
                            $Comment->status = 'sent';
                        }
                        
                        $Comment->first_created = $Comment->first_created;
                        $Comment->last_updated = date("Y-m-d H:i:s");

                        $Comment->setIsNewRecord(FALSE);
                        if ($Comment->update()) {
                            if (isset($_POST['savesend'])) {
                                $sql = "SELECT COUNT(*) AS total,
                                            IFNULL(SUM(CASE WHEN status='draft' THEN 1 ELSE 0 END),0) AS draft,
                                            IFNULL(SUM(CASE WHEN status='sent' THEN 1 ELSE 0 END),0) AS sent
                                        FROM comment WHERE scholar_id=" . ConfigWeb::getActiveScholarId();
                                $ReCheckConfirm = Yii::app()->db->createCommand($sql)->queryAll();
                                if ($ReCheckConfirm[0]['total'] == $ReCheckConfirm[0]['sent']) {
                                    $criteria = new CDbCriteria;
                                    $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                    $criteria->limit = 1;
                                    $Scholar = Scholar::model()->find($criteria);
                                    $Scholar->status = 'confirm';
                                    $Scholar->first_created = $Scholar->first_created;
                                    $Scholar->last_updated = date("Y-m-d H:i:s");
                                    $Scholar->setIsNewRecord(FALSE);
                                    if ($Scholar->update()) {
                                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                        $this->redirect(Yii::app()->createUrl($UrlNext));
                                    }
                                } else {
                                    $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                    $this->redirect(Yii::app()->createUrl($UrlNext), TRUE);
                                }
                            }
                        }
                    }

                    if ($error == '') {
                        $user->industrial_type_manufacture = $user->industrial_type_manufacture;
                        $user->industrial_type_export = $user->industrial_type_export;
                        $user->industrial_type_service = $user->industrial_type_service;
                        $user->first_created = $user->first_created;
                        $user->last_updated = date("Y-m-d H:i:s");
                        $user->setIsNewRecord(FALSE);
                        if ($user->update()) {
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    }
                }
            }
        }
        if (isset($_POST['upload'])) {
//            $this->redirect(Yii::app()->createUrl(Yii::app()->urlManager->parseUrl(Yii::app()->request)));
            $model = new UploadAttachmentForm();
            $criteria = new CDbCriteria;
            $criteria->condition = "id = $person_id";
            $criteria->limit = 1;
            $user = Person::model()->find($criteria);

            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId() . " and " . $person_type . "_id = " . $person_id;
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);

            $model->attributes = $Scholar->scholarStem->attributes;
            if($person_type == 'professor' || $person_type == 'mentor'){
                $model->cv_path = $user->cv_path;
                $model->professor_mentor_attachment_project_path = $Scholar->scholarStem->professor_mentor_attachment_project_path;
                $model->professor_mentor_attachment_other_path = $Scholar->scholarStem->professor_mentor_attachment_other_path;
                $model->industrial_certificate_path = $Scholar->scholarStem->industrial_certificate_path;
                $model->industrial_join_path = $Scholar->scholarStem->industrial_join_path;
            }else if($person_type == 'student'){
                $model->student_transcript_path = $Scholar->scholarStem->student_transcript_path;
                $model->copy_id_card_path = $user->copy_id_card_path;
                $model->student_portfolio_path = $Scholar->scholarStem->student_portfolio_path;
                $model->student_attachment_other_path = $Scholar->scholarStem->student_attachment_other_path;
                $model->student_attachment_other2_path = $Scholar->scholarStem->student_attachment_other2_path;
            }else if($person_type == 'industrial'){
                $model->industrial_certificate_path = $Scholar->scholarStem->industrial_certificate_path;
                $model->industrial_join_path = $Scholar->scholarStem->industrial_join_path;
                $model->industrial_attachment_other_path = $Scholar->scholarStem->industrial_attachment_other_path;
            }
            
            $criteria->condition = ""
                . "scholar_id = " . ConfigWeb::getActiveScholarId()
                . " and person_id = " . $person_id;
            $criteria->limit = 1;
            $Comment = Comment::model()->find($criteria);
            if(!empty($Comment))
                $model->status = $Comment->status;
            
        }
        
        $this->render('miniattachment', array(
            'model' => $model,
            'modelStemRec' => $modelStemRec
        ));
    }

    public function actionMiniUploadPhotoProfile() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();

        $model = new UploadImageForm;
        $criteria = new CDbCriteria;
        $criteria->condition = "id = $person_id";
        $criteria->limit = 1;
        $user = Person::model()->find($criteria);

        $model->attributes = $user->attributes;
        $model->id = $user->id;
        $model->image_path = $user->image_path;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['UploadImageForm'])) {
                $model->attributes = Yii::app()->input->post('UploadImageForm');
                if (isset($_POST['upload_photo'])) {
                    $uploadedFile = CUploadedFile::getInstance($model, 'image');
                    if (!empty($uploadedFile)) {
                        $ext = pathinfo($uploadedFile, PATHINFO_EXTENSION);
                        $fileName = $user->id . '\\' ."ProfilePic.$ext";  // random number + file name

                        $model->id = $user->id;
                        $model->image_path = $fileName;
                        $model->first_created = $user->first_created;
                        $model->last_updated = date("Y-m-d H:i:s");
                        $model->setIsNewRecord(FALSE);
                        if ($model->validate()) {
                            if ($model->update()) {
                                if (!empty($uploadedFile)) {  // check if uploaded file is set or not
                                    $uploadedFile->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);  // image will uplode to rootDirectory/banner/
                                }
                            }
                        } else {
                            $model->image_path = NULL;
                        }
                    } else {
                        Yii::app()->user->setFlash('error', 'กรุณาอัพโหลดภาพ / Please upload a photo.');
                    }
                }
            }

            if (isset($_POST['next'])) {
                if (!empty($user->image_path)) {
                    $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                    $this->redirect(Yii::app()->createUrl($UrlNext));
                } else {
                    Yii::app()->user->setFlash('error', 'กรุณาอัพโหลดภาพ / Please upload a photo.');
                }
            }
        }
        $this->render('miniuploadphotoprofile', array('model' => $model));
    }

    public function actionMiniProfile() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();

        $model = new ProfileForm();
        $criteria = new CDbCriteria;
        $criteria->condition = "id = '$person_id'";
        $criteria->limit = 1;
        $user = Person::model()->find($criteria);

        if ($user !== NULL) {
            $model->attributes = $user->attributes;
            if ($model->institute_id == NULL && !empty($model->institute_other))
                $model->institute_id = 0;
            if ($model->faculty_id == NULL && !empty($model->faculty_other))
                $model->faculty_id = 0;
            if ($model->major_id == NULL && !empty($model->major_other))
                $model->major_id = 0;
        }
//        $model->attributes = $user->attributes;
//        if ($model->institute_id == NULL)
//            $model->institute_id = 0;
//        if ($model->faculty_id == NULL)
//            $model->faculty_id = 0;
//        if ($model->major_id == NULL)
//            $model->major_id = 0;
        if(ConfigWeb::getActiveScholarType() == 'stem'){
            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            $model->is_taist = $Scholar->scholarStem->is_taist;
        }
        
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['ProfileForm'])) {
                $model->attributes = Yii::app()->input->post('ProfileForm');
                $model->id = $user->id;
                $model->first_created = $user->first_created;
                $model->last_updated = date("Y-m-d H:i:s");
                $model->institute_id = ($model->institute_id == NULL) ? NULL : intval($model->institute_id);
                $model->faculty_id = ($model->faculty_id == NULL) ? NULL : intval($model->faculty_id);
                $model->major_id = ($model->major_id == NULL) ? NULL : intval($model->major_id);
                $model->setIsNewRecord(FALSE);
                if ($model->validate()) {
                    if ($model->institute_id == 0)
                        $model->institute_id = NULL;
                    if ($model->faculty_id == 0)
                        $model->faculty_id = NULL;
                    if ($model->major_id == 0)
                        $model->major_id = NULL;
                    
                    $model->birthday = $this->formatDataViewToDB($model->birthday);
                    if(ConfigWeb::getActiveScholarType() == 'stem' && ConfigWeb::getActivePersonType() == "student"){
                        $model->id_card_created = $this->formatDataViewToDB($model->id_card_created);
                        $model->id_card_expired = $this->formatDataViewToDB($model->id_card_expired);
                    }
                    if ($model->update()) {
                        if(ConfigWeb::getActiveScholarType() == 'stem' && ConfigWeb::getActivePersonType() == "student"){
                            if(ConfigWeb::getActiveScholarType() == 'stem'){
                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);

                                $criteria->condition = "id = " . $Scholar->scholarStem->id;
                                $criteria->limit = 1;
                                $Stem = ScholarStem::model()->find($criteria);

                                $Stem->is_taist = $model->is_taist;
                                $Stem->setIsNewRecord(FALSE);

                                if ($Stem->update()) {
                                    $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                    $this->redirect(Yii::app()->createUrl($UrlNext));
                                }
                            }else{
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        }else{
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    }
                }
            }
        }
        
        if(ConfigWeb::getActiveScholarType() == 'stem' && ConfigWeb::getActivePersonType() == "student"){
            if (!empty($model->id_card_created))
                $model->id_card_created = date("d/m/Y", strtotime($this->formatDataViewToDB($model->id_card_created)));
            if (!empty($model->id_card_expired))
                $model->id_card_expired = date("d/m/Y", strtotime($this->formatDataViewToDB($model->id_card_expired)));
        }
        if ($model->birthday !== NULL) {
            $datetime1 = new DateTime($this->formatDataViewToDB($model->birthday));
            $datetime2 = new DateTime();
            if ($datetime1 < $datetime2) {
                $diff = $datetime1->diff($datetime2);
                $model->age = $diff->y . '  ปี  ' .
                        $diff->m . '  เดือน  ' .
                        ($diff->d + 1) . '  วัน  ';
            } else {
                $model->age = '0 วัน';
            }
            if (!empty($model->birthday))
                $model->birthday = date("d/m/Y", strtotime($this->formatDataViewToDB($model->birthday)));
        }

        $this->render('miniprofile', array('model' => $model));
    }

    public function formatDataViewToDB($date) {
        if (!empty($date)) {
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date))
                return $date;
            else {
                $myDateTime = DateTime::createFromFormat('d/m/Y', $date);
                return $myDateTime->format('Y-m-d');
            }
        } else {
            return NULL;
        }
    }

    public function actionget_age($birthday = NULL) {
        if (!empty($birthday)) {
            $birthday = $this->formatDataViewToDB($birthday);
        }

        $datetime1 = new DateTime($birthday);
        $datetime2 = new DateTime();
        $data = '0 วัน';
        if ($datetime1 <= $datetime2) {
            $diff = $datetime1->diff($datetime2);
            $data = $diff->y . '  ปี  ' .
                    $diff->m . '  เดือน  ' .
                    ($diff->d + 1) . '  วัน';
        } else {
            $data = 'วันเกิดไม่ถูกต้อง \ Birthday invalid.';
        }
        echo $data;
    }

    public function actiongetDataEducation() {
        $person_id = ConfigWeb::getActivePersonId();
        $id = Yii::app()->request->getQuery('edu_id');
        $model = new EducationForm('add');
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id and person_id = $person_id";
        $criteria->limit = 1;
        $education = Education::model()->find($criteria);
        if ($education !== NULL) {
            $model->attributes = $education->attributes;
            $model->id = $education->id;
            $model->status = $education->status;
        }
        if ($model->institute_id == NULL && !empty($model->institute_other))
            $model->institute_id = 0;
        if ($model->faculty_id == NULL && !empty($model->faculty_other))
            $model->faculty_id = 0;
        if ($model->major_id == NULL && !empty($model->major_other))
            $model->major_id = 0;

        echo json_encode($model);
    }

    public function actionMiniEducation() {
        $mode = Yii::app()->request->getQuery('mode');
        $edu_id = Yii::app()->request->getQuery('edu_id');
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        $model = new EducationForm($mode);
        $modelForm = new EducationForm ();
        $Education = NULL;
        $error = NULL;
        $Scholar = NULL;
        $criteria = new CDbCriteria ();

        if ($mode != 'add') {
            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
        }

        if (!empty($Scholar->education_id) && $person_type == 'student') {
            $criteria->condition = "id = " . $Scholar->education_id . " and person_id = $person_id";
            $criteria->limit = 1;
            $Education = Education::model()->find($criteria);
            if(!empty($Education)){
                $model->attributes = $Education->attributes;
                $model->education_id = $Education->id;
            }
            
            if (ConfigWeb::getActiveScholarType() == 'stem') {
                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId() . " and " . $person_type . "_id = " . $person_id;
                $criteria->limit = 1;
                $Scholar = Scholar::model()->find($criteria);
                $model->student_before_gpa = $Scholar->scholarStem->student_before_gpa;
            }
        }
        
        if($person_type != 'student' && $mode != 'add'){
            $criteria->condition = "is_highest = 1 and person_id = $person_id";
            $criteria->limit = 1;
            $Education = Education::model()->find($criteria);
            if(!empty($Education)){
                $model->attributes = $Education->attributes;
                $model->education_id = $Education->id;
            }
        }

        if (!empty($edu_id) && $mode == 'edit') {
            $criteria->condition = "id = $edu_id and person_id = $person_id";
            $criteria->limit = 1;
            $Education = Education::model()->find($criteria);
            if ($Education !== NULL) {
                $model->attributes = $Education->attributes;
            }
        }

        if ($Education !== NULL) {
            if ($model->institute_id == NULL && !empty($model->institute_other))
                $model->institute_id = 0;
            if ($model->faculty_id == NULL && !empty($model->faculty_other))
                $model->faculty_id = 0;
            if ($model->major_id == NULL && !empty($model->major_other))
                $model->major_id = 0;
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['EducationForm'])) {
                $model->attributes = Yii::app()->input->post('EducationForm');
                $modelForm->attributes = Yii::app()->input->post('EducationForm');
                if (isset($_POST ['add'])) {
                    $model = new EducationForm('add');
                    $model->attributes = Yii::app()->input->post('EducationForm');
                    $model->id = NULL;

                    if (!is_numeric($model->avg_gpa) || empty($model->avg_gpa)) {
                        $model->avg_gpa = 0;
                    }
                    $model->institute_id = ($model->institute_id == NULL) ? NULL : intval($model->institute_id);
                    $model->faculty_id = ($model->faculty_id == NULL) ? NULL : intval($model->faculty_id);
                    $model->major_id = ($model->major_id == NULL) ? NULL : intval($model->major_id);
                    $model->person_id = $person_id;
                    $model->is_highest = TRUE;
                    $model->first_created = date("Y-m-d H:i:s");
                    $model->last_updated = date("Y-m-d H:i:s");
                    if ($model->validate()) {
                        $model->institute_id = ($model->institute_id == NULL || $model->institute_id == 0) ? NULL : intval($model->institute_id);
                        $model->faculty_id = ($model->faculty_id == NULL || $model->faculty_id == 0) ? NULL : intval($model->faculty_id);
                        $model->major_id = ($model->major_id == NULL || $model->major_id == 0) ? NULL : intval($model->major_id);
                        $model->setIsNewRecord(TRUE);
                        if ($model->save()) {
                            if ($person_type == 'student') {
                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->education_id = $model->id;
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    
                                }
                            }

                            $sql = "UPDATE education SET is_highest = 0 WHERE person_id = $person_id";
                            $ObjectList = Yii::app()->db->createCommand($sql)->execute();
                            $sql = "UPDATE education SET is_highest = 1 WHERE id = " . $model->id . " and person_id = $person_id";
                            $ObjectList = Yii::app()->db->createCommand($sql)->execute();

                            $criteria->condition = "id = " . $model->id . " and person_id = $person_id and is_highest = 1";
                            $criteria->limit = 1;
                            $Edu = Education::model()->find($criteria);
                            if (!empty($Edu)) {
                                $this->redirect(Yii::app()->createUrl('site/minieducation'));
                            }
                        }
                    }else {
                        $error = TRUE;
                        $model->attributes = $modelForm->attributes;
                    }
                } else if (isset($_POST ['edit'])) {
                    $model->person_id = $Education->person_id;
                    $model->id = $Education->id;
                    if (!is_numeric($model->avg_gpa) || empty($model->avg_gpa)) {
                        $model->avg_gpa = 0;
                    }
                    $model->institute_id = ($model->institute_id == NULL) ? NULL : intval($model->institute_id);
                    $model->faculty_id = ($model->faculty_id == NULL) ? NULL : intval($model->faculty_id);
                    $model->major_id = ($model->major_id == NULL) ? NULL : intval($model->major_id);
                    $model->first_created = $Education->first_created;
                    $model->last_updated = date("Y-m-d H:i:s");
                    if ($model->validate()) {
                        $model->institute_id = ($model->institute_id == NULL || $model->institute_id == 0) ? NULL : intval($model->institute_id);
                        $model->faculty_id = ($model->faculty_id == NULL || $model->faculty_id == 0) ? NULL : intval($model->faculty_id);
                        $model->major_id = ($model->major_id == NULL || $model->major_id == 0) ? NULL : intval($model->major_id);
                        $model->setIsNewRecord(FALSE);
                        if ($model->update()) {
                            if ($person_type == 'student') {
                                $Scholar->education_id = $model->id;
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    
                                }
                            }
                            $sql = "UPDATE education SET is_highest = 0 WHERE person_id = $person_id";
                            $ObjectList = Yii::app()->db->createCommand($sql)->execute();
                            $sql = "UPDATE education SET is_highest = 1 WHERE id = " . $model->id . " and person_id = $person_id";
                            $ObjectList = Yii::app()->db->createCommand($sql)->execute();

                            $criteria->condition = "id = " . $model->id . " and person_id = $person_id and is_highest = 1";
                            $criteria->limit = 1;
                            $Edu = Education::model()->find($criteria);
                            if (!empty($Edu)) {
                                $this->redirect(Yii::app()->createUrl('site/minieducation'));
                            }
                        }
                    } else {
                        $error = TRUE;
                        $model->attributes = $modelForm->attributes;
                    }
                } else if (isset($_POST ['next'])) {
                    $model = new EducationForm('next');
                    $model->attributes = Yii::app()->input->post('EducationForm');
//                    if (!empty($model->education_id) && ) {
                    if ($model->validate()) {
                        if ($person_type == 'student') {
                            $Scholar->education_id = $model->education_id;
                            $Scholar->first_created = $Scholar->first_created;
                            $Scholar->last_updated = date("Y-m-d H:i:s");
                            $Scholar->setIsNewRecord(FALSE);
                            if ($Scholar->update()) {

                                if (ConfigWeb::getActiveScholarType() == 'stem') {
                                    $Scholar->scholarStem->student_before_gpa = $model->student_before_gpa;
                                    $Scholar->scholarStem->setIsNewRecord(FALSE);
                                    if ($Scholar->scholarStem->update()) {}
                                }
                            }
                        }

                        $sql = "UPDATE education SET is_highest = 0 WHERE person_id = $person_id";
                        $ObjectList = Yii::app()->db->createCommand($sql)->execute();
                        $sql = "UPDATE education SET is_highest = 1 WHERE id = " . $model->education_id . " and person_id = $person_id";
                        $ObjectList = Yii::app()->db->createCommand($sql)->execute();

                        $criteria->condition = "id = " . $model->education_id . " and person_id = $person_id and is_highest = 1";
                        $criteria->limit = 1;
                        $Edu = Education::model()->find($criteria);
                        if (!empty($Edu)) {
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->controller->id . "/" . Yii::app()->controller->action->id, TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    } else {
                        $error = TRUE;
                        $modelForm->education_id = $model->education_id;
                        $modelForm->student_before_gpa = $model->student_before_gpa;
                        if(!empty($model->education_id)){
                            $criteria->condition = "id = ".$model->education_id." and person_id = $person_id";
                            $criteria->limit = 1;
                            $Education = Education::model()->find($criteria);
                            if ($Education !== NULL) {
                                $model->attributes = $Education->attributes;
                            }
                        }
                        $model->education_id = $modelForm->education_id;
                        $model->student_before_gpa = $modelForm->student_before_gpa;
//                        $model->validate();
                    }
                }
            }
        }

        if ($model->institute_id == NULL && !empty($model->institute_other))
            $model->institute_id = 0;
        if ($model->faculty_id == NULL && !empty($model->faculty_other))
            $model->faculty_id = 0;
        if ($model->major_id == NULL && !empty($model->major_other))
            $model->major_id = 0;

        $this->render('minieducation', array(
            'model' => $model,
            'mode' => $mode
        ));
    }

    public function actionMiniEducation2() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();

        $model = new EducationForm();
        $criteria = new CDbCriteria;
        $criteria->condition = "person_id = $person_id and is_highest = 1";
        $criteria->limit = 1;
        $Education = Education::model()->find($criteria);
        if ($Education == NULL) {
            $edu = new Education;
            $edu->person_id = $person_id;
            $edu->is_highest = TRUE;
            $edu->active = TRUE;
            $edu->first_created = date("Y-m-d H:i:s");
            $edu->setIsNewRecord(TRUE);
            if ($edu->save()) {
                $criteria->condition = "person_id = $person_id and is_highest = 1";
                $criteria->limit = 1;
                $Education = Education::model()->find($criteria);
            }
        }

        if ($Education !== NULL) {
            $model->attributes = $Education->attributes;
            if ($model->institute_id == NULL && !empty($model->institute_other))
                $model->institute_id = 0;
            if ($model->faculty_id == NULL && !empty($model->faculty_other))
                $model->faculty_id = 0;
            if ($model->major_id == NULL && !empty($model->major_other))
                $model->major_id = 0;
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['EducationForm'])) {
                $model->attributes = Yii::app()->input->post('EducationForm');
                if (!is_numeric($model->avg_gpa) || empty($model->avg_gpa)) {
                    $model->avg_gpa = 0;
                }

                $model->institute_id = ($model->institute_id == NULL) ? NULL : intval($model->institute_id);
                $model->faculty_id = ($model->faculty_id == NULL) ? NULL : intval($model->faculty_id);
                $model->major_id = ($model->major_id == NULL) ? NULL : intval($model->major_id);
                $model->person_id = $person_id;

                if ($model->validate()) {
                    $model->institute_id = ($model->institute_id == NULL || $model->institute_id == 0) ? NULL : intval($model->institute_id);
                    $model->faculty_id = ($model->faculty_id == NULL || $model->faculty_id == 0) ? NULL : intval($model->faculty_id);
                    $model->major_id = ($model->major_id == NULL || $model->major_id == 0) ? NULL : intval($model->major_id);
                    if ($Education !== NULL) {
                        $model->id = $Education->id;
                        $model->first_created = $Education->first_created;
                        $model->last_updated = date("Y-m-d H:i:s");
                        $model->setIsNewRecord(FALSE);
                        if ($model->update()) {
                            $criteria->condition = "id = $person_id";
                            $criteria->limit = 1;
                            $user = Person::model()->find($criteria);
                            $user->first_created = $user->first_created;
                            $user->last_updated = date("Y-m-d H:i:s");
                            if ($user->update()) {
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        }
                    } else {
                        $model->is_highest = TRUE;
                        $model->active = TRUE;
                        $model->first_created = date("Y-m-d H:i:s");
                        $model->setIsNewRecord(TRUE);
                        if ($model->save()) {
                            $criteria->condition = "id = $person_id";
                            $criteria->limit = 1;
                            $user = Person::model()->find($criteria);
                            $user->first_created = $user->first_created;
                            $user->last_updated = date("Y-m-d H:i:s");
                            if ($user->update()) {
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        }
                    }
                }
            }
        }

        if ($model->institute_id == NULL && !empty($model->institute_other))
            $model->institute_id = 0;
        if ($model->faculty_id == NULL && !empty($model->faculty_other))
            $model->faculty_id = 0;
        if ($model->major_id == NULL && !empty($model->major_other))
            $model->major_id = 0;

        $this->render('minieducation', array('model' => $model));
    }

    public function actionCompany() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();

        $Industrial = NULL;
        $model = new CompanyForm();
        $criteria = new CDbCriteria;
        $criteria->condition = "id = $person_id";
        $criteria->limit = 1;
        $Industrial = Person::model()->find($criteria);
        $model->attributes = $Industrial->attributes;
        $model->id = $Industrial->id;
        
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['CompanyForm'])) {
                $model->attributes = Yii::app()->input->post('CompanyForm');
                if (isset($_POST['next'])) {
                    if ($model->validate()) {
                        if (!empty($model->id)) {
//                        EDIT________________________________
                            $model->first_created = $Industrial->first_created;
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->setIsNewRecord(FALSE);
                            if ($model->update()) {
                                $criteria->condition = "person_id = $person_id";
                                $criteria->limit = 1;
                                $Address = Address::model()->find($criteria);
                                if ($Address == NULL) {
                                    $Address = new Address;
                                    $Address->person_id = $person_id;
                                    $Address->first_created = date("Y-m-d H:i:s");
                                    $Address->last_updated = date("Y-m-d H:i:s");
                                    $Address->save();
                                }
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        } else {
//                        ADD_________________________________
                            $model->type = 'industrial';
                            $model->first_created = date("Y-m-d H:i:s");
                            $model->last_updated = date("Y-m-d H:i:s");
                            if ($model->save()) {
                                $criteria->condition = "person_id = $person_id";
                                $criteria->limit = 1;
                                $Address = Address::model()->find($criteria);
                                if (empty($Address)) {
                                    $Address->person_id = $person_id;
                                    $Address->first_created = date("Y-m-d H:i:s");
                                    $Address->last_updated = date("Y-m-d H:i:s");
                                    $Address->save();
                                }
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        }
                    }
                }
                if (isset($_POST['confirmemail'])) {
                    $criteria->condition = "email = '" . $model->email . "' and type = 'industrial' ";
                    $criteria->limit = 1;
                    $Industrial = Person::model()->find($criteria);
                    $model->attributes = $Industrial->attributes;
                    $model->id = $Industrial->id;
                    Yii::app()->session['person_id'] = $Industrial->id;
                }
            }
        }
        $this->render('company', array('model' => $model));
    }

    public function actionGetDepartmentByOrg() {
        $sql = "SELECT
                    id,
                    concat(dpm_name,'/',dpm_name_en) AS name
                FROM nstdamas_department
                WHERE org_id=" . $_POST['org_id'] . "
                ORDER BY dpm_name ASC";
        $ObjectList = Yii::app()->db->createCommand($sql)->queryAll();

        $data = CHtml::listData($ObjectList, 'id', 'name');
        echo CHtml::tag('option', array('value' => ""), CHtml::encode(""));
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode(str_replace("/NULL", "", $name)));
        }
    }

    public function actionGetInstituteByCountry() {
        $sql = "SELECT
                    id,
                    concat(ist_name,'/',ist_name_en) AS name
                FROM
                    nstdamas_institute
                WHERE
                    ist_type_name='มหาวิทยาลัย'
                AND ist_name NOT LIKE '%(เก่า)%'
                AND ist_name NOT LIKE '%(ไม่ใช้งาน)%'
                AND ist_name NOT LIKE '%โรงเรียน%'
                AND ist_cnt_id=" . $_POST['country_id'] . "
                ORDER BY ist_name";
        $ObjectList = Yii::app()->db->createCommand($sql)->queryAll();

        $data = CHtml::listData($ObjectList, 'id', 'name');
        echo CHtml::tag('option', array('value' => ""), CHtml::encode(""));
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode(str_replace("/NULL", "", $name)));
        }
        echo CHtml::tag('option', array('value' => "0"), CHtml::encode("อื่นๆ / Other"));
    }

    public function actionGetDistrictByProvince() {
        $data = NstdamasZipcode::model()->findAll(
                'zpc_prv_id = :match', array(':match' => $_POST['province_id'])
        );
        $data = CHtml::listData($data, 'zpc_district', 'zpc_district');
        echo CHtml::tag('option', array('value' => ""), CHtml::encode(""));
        foreach ($data as $value => $prv_name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($prv_name), true);
        }
    }

    public function actionGetZipcodeByDistrict() {
        $data = NstdamasZipcode::model()->findAll(
                'zpc_district = :match', array(':match' => $_POST['district_id'])
        );
        $data = CHtml::listData($data, 'zpc_code', 'zpc_code');
        echo CHtml::tag('option', array('value' => ""), CHtml::encode(""));
        foreach ($data as $value => $zpc_code) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($zpc_code), true);
        }
    }

    public function actionAddress() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();

        $model = new AddressForm;
        $criteria = new CDbCriteria;
        $criteria->condition = "person_id = $person_id";
        $criteria->limit = 1;
        $Address = Address::model()->find($criteria);
        if (!empty($Address)) {
            $model->attributes = $Address->attributes;
            $model->id = $Address->id;
        }
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['AddressForm'])) {
                $model->attributes = Yii::app()->input->post('AddressForm');
                if (isset($_POST['next'])) {
                    if ($model->validate()) {
                        if (!empty($model->id)) {
//                        EDIT________________________________
                            $model->first_created = $Address->first_created;
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->type = 'contact';
                            $model->setIsNewRecord(FALSE);
                            if ($model->update()) {
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        } else {
//                        ADD_________________________________
                            $model->person_id = $person_id;
                            $model->first_created = date("Y-m-d H:i:s");
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->type = 'contact';
                            if ($model->save()) {
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        }
                    }
                }
            }
        }
        $this->render('address', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }
        
        $model = new LoginForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $scholar_type = strtolower(Yii::app()->request->getQuery('scholartype'));
        if (empty($scholar_type)) {
            $scholar_type = ConfigWeb::getActiveScholarType();
        }

        if (!in_array($scholar_type, Yii::app()->params['ScholarTypeList'])) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login() && strpos($model->username, "@stem") == NULL) {
                $criteria = new CDbCriteria;
                $criteria->condition = "email = '$model->username' or id_card = '$model->username'";
                $criteria->limit = 1;
                $records = Person::model()->find($criteria);
                Yii::app()->session['user_type'] = 'user';
                Yii::app()->session['person_id'] = $records->id;
                Yii::app()->session['person_type'] = $records->type;
                Yii::app()->session['token'] = $records->token;
                Yii::app()->session['LoginByToken'] = FALSE;
                $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
            }

            if ($model->validate() && $model->loginAdmin() && strpos($model->username, "@stem") > -1) {
                $criteria = new CDbCriteria;
                $criteria->condition = "username = '$model->username' ";
                $criteria->limit = 1;
                $records = Account::model()->find($criteria);
                Yii::app()->session['user_type'] = 'admin';
                Yii::app()->session['admin_name'] = $records->username;
                Yii::app()->session['admin_scholar_type'] = $records->type;
                Yii::app()->session['admin_group'] = $records->group;
                Yii::app()->session['LoginByToken'] = FALSE;
                $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
            } else {
                Yii::app()->user->setFlash('error', 'Incorrect username or password.');
            }
        }
        // display the login form
        $this->render('login', array('model' => $model, 'scholartype' => $scholar_type));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        $sch_type = ConfigWeb::getActiveScholarType();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->createUrl($sch_type));
    }

}
