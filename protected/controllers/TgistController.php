<?php

class TgistController extends Controller {

    /**
     *
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     *      using two-column layout. See 'protected/views/layouts/column2.php'.
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
                'backColor' => 0xFFFFFF
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction'
            )
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        if (Yii::app()->user->isGuest) {
            $model = new LoginForm ();
            Yii::app()->session['scholar_type'] = 'tgist';
            $this->redirect(Yii::app()->createUrl('site/login', array(
                        'scholar_type' => ConfigWeb::getActiveScholarType())));
        }
        ConfigWeb::getActiveScholarId();
        $this->redirect(Yii::app()->createUrl(WorkflowData::WorkflowUrlStart()));
    }
    
    public function actionGoto() {
        $person_id = Yii::app()->session['person_id'];
        $person_type = Yii::app()->session['person_type'];
        $scholar_type = ConfigWeb::getActiveScholarType();
        
        $tgistModel = new ScholarTgist;
        if ($tgistModel->save()) {
                $scholarModel = new Scholar;
                $scholarModel['scholar_' . strtolower($scholar_type) . '_id'] = $tgistModel->id;
                $scholarModel[$person_type . '_id'] = $person_id;
                $scholarModel->type = strtolower($scholar_type);
                $scholarModel->first_created = date("Y-m-d H:i:s");
                $scholarModel->last_updated = date("Y-m-d H:i:s");
                $scholarModel->status = 'draft';
                if ($scholarModel->save()) {
                    $commentModel = new Comment;
                    $commentModel->scholar_id = $scholarModel->id;
                    $commentModel->status = 'draft';
                    $commentModel->first_created = date("Y-m-d H:i:s");
                    $commentModel->last_updated = date("Y-m-d H:i:s");
                    if ($commentModel->save()) {
                        Yii::app()->session['tmpActiveScholarId'] = $scholarModel->id;
                        $this->redirect(Yii::app()->createUrl(WorkflowData::WorkflowUrlStart()));
                    } else {
                        $scholarModel->delete();
                        $tgistModel->delete();
                        throw new CHttpException(500, 'HTTPInternalServerError.');
                    }
                }
            } else {
                $tgistModel->delete();
                throw new CHttpException(500, 'HTTPInternalServerError.');
            }
    }
    
    public function actionExportExcelTgist() {
        ConfigWeb::PageAdminOnly();
        $year = Yii::app()->request->getQuery('year');
        $whareOrg = '';
        $org_id = Yii::app()->session['admin_group'];
        if($org_id != 'all'){
            $whareOrg = " AND IF(sch.professor_id is NULL, men.org_id , 0) = " . $org_id;
        }
        $sql = Yii::app()->params['sqlExportTgist'] . " "
                . " AND YEAR(DATE_ADD(sch.first_created, INTERVAL 3 MONTH))='" . ($year-543) . "' "
                . $whareOrg
                . " ORDER BY int_running DESC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            $fields = array(
                'running'=>'Code',
                'stu_fullname'=>'ชื่อ-นามสกุล (นักศึกษา) ',
                'pro_men_fullname'=>'ชื่อ-นามสกุล (อาจารย์)',
                'stu_edu_institute'=>'มหาวิทยาลัย/สถาบัน',
                'stu_edu_faculty'=>'คณะ',
                'stu_edu_major'=>'ภาควิชา',
                'stu_edu_level'=>'ระดับการศึกษา',
                'stu_edu_gpa_before'=>'GPA ก่อนหน้า',
                'stu_edu_gpa'=>'GPA ปัจจุบัน',
                'prj_stu_name'=>'ชื่อโครงการย่อย',
                'prj_stu_range'=>'ระยะเวลาโครงการย่อย',
                'prj_stu_begin'=>'วันเริ่ม',
                'prj_stu_end'=>'วันสิ้นสุด',
                'prj_stu_sum'=>'สรุปงบประมาณ',
                'prj_source'=>'แหล่งทุนวิจัยโครงการหลัก - ได้รับอนุมัติทุนวิจัยแล้วจาก ',
                'prj_nstda'=>'แหล่งทุนวิจัยโครงการหลัก -  ได้รับอนุมัติโครงการแล้วจาก สวทช. ',
                'prj_other'=>'แหล่งทุนวิจัยโครงการหลัก -  อื่นๆ ',
                'prj_none'=>'แหล่งทุนวิจัยโครงการหลัก -  ไม่มี',
                'ind_name'=>'บริษัท/ภาคอุตสาหกรรม - ชื่อบริษัท',
                'ind_sum'=>'บริษัท/ภาคอุตสาหกรรม - สรุปประมาณการเงินสนับสนุนจนจบโครงการ',
                'ind_desc'=>'โปรดระบุสินค้าและบริการของบริษัท/ภาคอุตสาหกรรม ',
                'relevance_automotive'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมยานยนต์สมัยใหม่',
                'relevance_electronics'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมอิเล็กทรอนิกส์อัจฉริยะ',
                'relevance_tourism'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมการท่องเที่ยวกลุ่มรายได้ดีและการท่องเที่ยวเชิงสุขภาพ',
                'relevance_agriculture'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - การเกษตรและเทคโนโลยีชีวภาพ',
                'relevance_food'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมการแปรรูปอาหาร',
                'relevance_robotics'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมหุ่นยนต์',
                'relevance_aviation'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมการบินและโลจิสติกส์',
                'relevance_biofuels'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมเชื้อเพลิงชีวภาพและเคมีชีวภาพ',
                'relevance_digital'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมดิจิตอล',
                'relevance_medical'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม - อุตสาหกรรมการแพทย์ครบวงจร',
                'effect_new'=>'ผลกระทบต่อหน่วยงานอุตสาหกรรม/บริษัทในทางด้านเศรษฐกิจ สังคม หรืออื่นๆ - การสร้างผลิตภัณฑ์ใหม่',
                'effect_cost'=>'ผลกระทบต่อหน่วยงานอุตสาหกรรม/บริษัทในทางด้านเศรษฐกิจ สังคม หรืออื่นๆ - การลดต้นทุนการผลิต',
                'effect_quality'=>'ผลกระทบต่อหน่วยงานอุตสาหกรรม/บริษัทในทางด้านเศรษฐกิจ สังคม หรืออื่นๆ - การเพิ่มคุณภาพการผลิต',
                'effect_environment'=>'ผลกระทบต่อหน่วยงานอุตสาหกรรม/บริษัทในทางด้านเศรษฐกิจ สังคม หรืออื่นๆ - การแก้ปัญหาสิ่งแวดล้อม',
                'effect_other'=>'ผลกระทบต่อหน่วยงานอุตสาหกรรม/บริษัทในทางด้านเศรษฐกิจ สังคม หรืออื่นๆ - อื่นๆ',
                
                //'status'=>'สถานะ'
            );

            ExcelExporter::sendAsXLS('ExportExcelTgist', $data, false, true, $fields);
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }else{
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }
    }
    
    public function actionExportExcelQuickReport() {
        ConfigWeb::PageAdminOnly();
        $year = Yii::app()->request->getQuery('year');
        $whareOrg = '';
        $org_id = Yii::app()->session['admin_group'];
        if($org_id != 'all'){
            $whareOrg = " AND IF(sch.professor_id is NULL, men.org_id , 0) = " . $org_id;
        }
        $sql = Yii::app()->params['sqlExportQuickReport'] . " "
                . " AND YEAR(DATE_ADD(sch.first_created, INTERVAL 3 MONTH))='" . ($year-543) . "' "
                . $whareOrg
                . " ORDER BY sch.last_updated DESC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            $fields = array(
//                'x_id'=>'Id',
                'pro_men_fullname'=>'ชื่ออาจารย์/นักวิจัย',
                'pro_men_workarea'=>'สถานที่ทำงาน',
                'pro_men_email'=>'อีเมล์ อาจารย์/นักวิจัย',
                'pro_men_mobile'=>'เบอร์โทรศัพท์มือถือ',
                'stu_fullname'=>'ชื่อ นศ.',
                'stu_idcard'=>'เลขประจำตัวประชาชน นศ.',
                'stu_email'=>'อีเมล์ นศ.',
                'stu_mobile'=>'เบอร์โทรศัพท์มือถือ นศ.',
                'ind_name'=>'ชื่อภาคอุตสาหกรรม/บริษัท',
                'ind_fullname'=>'ชื่อผู้ประสานงาน',
                'ind_mobile'=>'เบอร์โทรศัพท์มือถือ ผู้ประสานงาน',
                'ind_email'=>'อีเมล์ ชื่อผู้ประสานงาน',
                'ind_type_manufacture'=>'ประเภทของกิจการ',
//                'ind_type_export'=>'ประเภทการส่งออก',
//                'ind_type_service'=>'ประเภทการบริการ',
                'ind_type_description'=>'สินค้าและบริการ',
                'status'=>'สถานะใบสมัคร',
                'last_updated'=>'อัพเดทล่าสุดเมื่อ'
            );

            ExcelExporter::sendAsXLS('ExcelQuickReport', $data, false, true, $fields);
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }else{
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }
    }
    
    public function actionExportExcel() {
        ConfigWeb::PageAdminOnly();
        $year = Yii::app()->request->getQuery('year');
        $whareOrg = '';
        $org_id = Yii::app()->session['admin_group'];
        if($org_id != 'all'){
            $whareOrg = " AND IF(sch.professor_id is NULL, men.org_id , 0) = " . $org_id;
        }
        $sql = Yii::app()->params['sqlExport'] . " "
                . " AND sch.id IS NOT NULL "
                . " AND YEAR(DATE_ADD(sch.first_created, INTERVAL 3 MONTH))='" . ($year-543) . "' "
                . $whareOrg
                . " ORDER BY sch.last_updated DESC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($data)){
            $fields = array(
                'running'=>'Code',
                'status'=>'สถานะใบสมัคร',
                'academic_position'=>'ตำแหน่งทางวิชาการ',
                'pro_men_fullname'=>'ชื่ออาจารย์/นักวิจัย',
                'pro_men_mobile'=>'เบอร์โทรศัพท์มือถือ',
                'pro_men_email'=>'อีเมล์ อาจารย์/นักวิจัย',
                'pro_men_position'=>'ตำแหน่งงาน',
    //            'สถาบัน',
    //            'คณะ',
    //            'ภาควิชา ',
                'pro_men_expert'=>'ความเชี่ยวชาญ',
    //            
                'pro_men_edu_level'=>'ระดับการศึกษา',
                'pro_men_edu_country'=>'ประเทศ',
                'pro_men_edu_institute'=>'สถาบัน',
                'pro_men_edu_faculty'=>'คณะ',
                'pro_men_edu_major'=>'ภาควิชา',
                'pro_men_edu_enrolled'=>'ปีที่เข้า',
                'pro_men_edu_graduated'=>'ปีที่จบ',
    //            'เกรดเฉลี่ยรวม',
    //            
                'stu_email'=>'อีเมล์นศ',
                'stu_fullname'=>'ชื่อนศ.',
                'stu_mobile'=>'เบอร์โทรศัพท์มือถือ',
                'stu_age'=>'อายุ',
    //            
                'prj_name'=>'ชื่อโครงการหลัก',
                'prj_objective'=>'วัตุประสงค์',
                'prj_scope'=>'ขอบเขต',
                'prj_begin_prj_end'=>'ระยะเวลาโครงการ',
                'prj_begin'=>'วันที่เริ่ม',
                'prj_end'=>'วันสิ้นสุด',
                'prj_funding'=>'แหล่งทุน',
                'prj_budget'=>'งบประมาณ',
    //            
                'prj_stu_name'=>'ชื่อโครงการย่อย',
                'prj_stu_begin_prj_end'=>'ระยะเวลา',
                'prj_stu_begin'=>'วันเริ่ม',
                'prj_stu_end'=>'วันสิ้นสุด',
                'prj_stu_objective'=>'วัตถุประสงค์',
                'prj_stu_expect'=>'ผลที่คาดหวัง',
                'prj_stu_cooperation'=>'ความร่วมมือวิชาการและงานวิจัยระหว่างอาจารย์และนักวิจัย',
                'prj_effect'=>'ผลกระทบต่อหน่วยงานด้านเศรษฐกิจและ สังคม ',
                'prj_relevance'=>'ความเกี่ยวข้องของโครงการวิจัยย่อยกับกลุ่มอุตสาหกรรม',
                'itap'=>'ITAP',
                'ind_incash_other'=>'ค่าใช้จ่าย IN-CASH',
                'ind_inkind_other'=>'ค่าใช้จ่าย IN-KIND',
    //            
                'ind_name'=>'ชื่อบริษัท',
                'ind_email'=>'อีเมล์',
                'ind_fullname'=>'ชื่อผู้ประสานงาน',
                'ind_mobile'=>'เบอร์โทรศัพท์มือถือ',
                'ind_comment'=>'ความคิดเห็นต่อผู้รับทุน',
                'ind_address'=>'ที่อยู่',

    //            
                'stu_edu_status'=>'สถานะ:กำลังศึกษา/จบการศึกษา',
                'stu_edu_level'=>'ระดับการศึกษา:โท/เอก',
                'stu_edu_institute'=>'สถาบัน/มหาวิทยาลัย',
                'stu_edu_faculty'=>'คณะ',
                'stu_edu_major'=>'ภาควิชา',
                'stu_edu_year_enrolled'=>'ปีที่เข้ารับการศึกษา',
                'stu_edu_gpa'=>'เกรดเฉลี่ยรวมปัจจุบัน',
    //            
                'is_history'=>'การรับทุนอื่น',
                'history_edu_level'=>'ระดับการศึกษาที่ได้รับทุน',
                'history_name'=>'ชื่อทุน',
                'history_source'=>'หน่วยงาน',
                'history_begin'=>'วันเริ่ม',
                'history_end'=>'วันสิ้นสุด',
                'history_begin_history_end'=>'ระยะเวลา',
    //            
                'portfolio_thesis'=>'ชื่อหัวข้อวิทยานิพนธ์',
                'portfolio_all'=>'ผลงานที่เคยได้รับ',
    //            
                'is_work'=>'สถานภาพการทำงาน (ทำ/ไม่ทำ)',
                'work_position'=>'ตำแหน่ง',
                'work_company'=>'บริษัท',
                'workwithproject_text1'=>'ประวัติการทำงาน/ประสบการณ์ทำงาน(1)',
                'workwithproject_text2'=>'ประวัติการทำงาน/ประสบการณ์ทำงาน(2)',
                'workwithproject_text3'=>'ประวัติการทำงาน/ประสบการณ์ทำงาน(3)',
    //            
                'ind_name2'=>'ชื่อบริษัท/สถานประกอบการ',
                'ind_type_manufacture'=>'ประเภทของกิจการ',
                'ind_type_description'=>'สินค้าและบริการ',

                'incash_other'=>'ค่าใช้จ่าย IN-CASH',
                'inkind_other'=>'ค่าใช้จ่าย IN-KIND',
                'ind_support_desc'=>'โครงการวิจัยช่วยสนับสนุนภาคอุสาหกรรมและเกี่ยวข้องกับ 10 อุตสาหกรรม ระบุ'
            );

            ExcelExporter::sendAsXLS('ExportExcel', $data, false, true, $fields);
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }else{
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }
    }
    
    public function actionExportAttachmentZip($id = NULL) {
        ConfigWeb::PageAdminOnly();
        
        $ssid = Yii::app()->getSession()->getSessionId();
        $download = False;
        if(empty($id)){
            $id = ConfigWeb::getActiveScholarId();
            $download = True;
        }
        $sql = Yii::app()->params['sqlExport'] . " "
                . " AND sch.id = " . $id . " "
                . " ORDER BY sch.last_updated DESC";
        $Scholar = Yii::app()->db->createCommand($sql)->queryAll();
        $path = Yii::app()->basePath . '/../uploads/';
        $ApplicationForm = Yii::app()->basePath . '/../template/tgist/ApplicationForm/';
        
        $pathTemp = Yii::app()->basePath . '/../temp/' . $ssid;
        if (!is_dir($pathTemp)) {
            @mkdir($pathTemp, 0, true);
         }
            
        if(!empty($Scholar)){
            $ZipName = 'TGIST_'.$Scholar[0]['running'].'.zip';
            $ZipPath = $pathTemp . '/' . $ZipName;

            $dZipPath = Yii::getPathOfAlias('ext.dZip');
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            include($dZipPath . DIRECTORY_SEPARATOR . 'dZip.inc.php');
            $zip = new dZip($ZipPath);
            spl_autoload_register(array('YiiBase', 'autoload'));
            
            
            foreach ($Scholar as $tgist) {
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $tgist['x_id'];
                $criteria->limit = 1;
                $Sch = Scholar::model()->find($criteria);
                
                $zip->addDir($tgist['running']);
                if (file_exists($ApplicationForm."ApplicationForm_".$tgist['running'].".docx")) {
                    $newFile = $tgist['running']."/"."ApplicationForm_".$tgist['running'].".docx";
                    $zip->addFile($ApplicationForm."ApplicationForm_".$tgist['running'].".docx", $newFile);
                }
                
                $zip->addDir($tgist['running']."/"."Student"); // Add Folder
                $fd_pro_men = (empty($tgist['pro_id'])?"Mentor":"Professor");
                $zip->addDir($tgist['running']."/".$fd_pro_men); // Add Folder
                $zip->addDir($tgist['running']."/"."Industrial"); // Add Folder
   
                if(is_dir($path.$tgist['stu_id'])){
                    $pathStu = "/"."Student/";
                    $file = $Sch->student->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->student_transcript_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->student_portfolio_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }        
                    $file = $Sch->scholarTgist->student_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    } 
                    $file = $Sch->scholarTgist->student_attachment_other2_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->student_attachment_other3_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
                if(is_dir($path.$tgist['pro_id'])){
                    $pathStu = "/"."Professor/";
                    $file = $Sch->professor->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->professor_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
//                $path_pro_men = (empty($tgist['pro_id'])?$tgist['men_id']:$tgist['pro_id']);
//                if(is_dir($path.$path_pro_men)){
//                    $pathProMen = "/".$fd_pro_men."/";
//                    $file = (empty($Sch->professor)?$Sch->mentor->image_path:$Sch->professor->image_path);
//                    if (is_file($path.$file)) {
//                        $newFile = $tgist['running'].$pathProMen.basename($file);
//                        $zip->addFile($path.$file, $newFile);
//                    }
//                    $file = (empty($Sch->professor)?$Sch->mentor->cv_path:$Sch->professor->cv_path);
//                    if (is_file($path.$file)) {
//                        $newFile = $tgist['running'].$pathProMen.basename($file);
//                        $zip->addFile($path.$file, $newFile);
//                    }
//                    $file = $Sch->scholarTgist->professor_attachment_other_path;
//                    if (is_file($path.$file)) {
//                        $newFile = $tgist['running'].$pathProMen.basename($file);
//                        $zip->addFile($path.$file, $newFile);
//                    }
//                }
                
                if(is_dir($path.$tgist['ind_id'])){
                    $pathInd = "/"."Industrial/";
                    $file = $Sch->industrial->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->industrial_certificate_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->industrial_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
            }
            
            $zip->save();
            if($download){
                $pathUrl = '/temp/' . $ssid . "/" . $ZipName;
                header('Location: ' . Yii::app()->baseUrl . $pathUrl);
            }
        }
    }
    
    public function actionExportAllZip() {
        ConfigWeb::PageAdminOnly();
        
        $ssid = Yii::app()->getSession()->getSessionId();
        $path = Yii::app()->basePath . '/../uploads/';
        $ApplicationForm = Yii::app()->basePath . '/../template/tgist/ApplicationForm/';
        
        $year = Yii::app()->request->getQuery('year');
        $whareOrg = '';
        $org_id = Yii::app()->session['admin_group'];
        if($org_id != 'all'){
            $whareOrg = " AND IF(sch.professor_id is NULL, men.org_id , 0) = " . $org_id;
        }
        $file_tgist_ids = array();
        $sql = Yii::app()->params['sqlExport'] . " "
                . " AND sch.id IS NOT NULL "
                . " AND YEAR(DATE_ADD(sch.first_created, INTERVAL 3 MONTH))='" . ($year-543) . "' "
                . $whareOrg
                . " ORDER BY sch.last_updated DESC";
        $Scholar = Yii::app()->db->createCommand($sql)->queryAll();
        
        $pathTemp = Yii::app()->basePath . '/../temp/' . $ssid;
        if (!is_dir($pathTemp)) {
            @mkdir($pathTemp, 0, true);
         }
        if(!empty($Scholar)){
            $ZipName = 'TGIST_ALL.zip';
            $ZipPath = $pathTemp . '/' . $ZipName;

            $dZipPath = Yii::getPathOfAlias('ext.dZip');
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            include($dZipPath . DIRECTORY_SEPARATOR . 'dZip.inc.php');
            $zip = new dZip($ZipPath);
            spl_autoload_register(array('YiiBase', 'autoload'));

            foreach ($Scholar as $tgist) {
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $tgist['x_id'];
                $criteria->limit = 1;
                $Sch = Scholar::model()->find($criteria);
//                TgistController::actionWord($tgist['x_id']);
                $zip->addDir($tgist['running']);
                if (file_exists($ApplicationForm."ApplicationForm_".$tgist['running'].".docx")) {
                    $newFile = $tgist['running']."/"."ApplicationForm_".$tgist['running'].".docx";
                    $zip->addFile($ApplicationForm."ApplicationForm_".$tgist['running'].".docx", $newFile);
                }else{
                    TgistController::actionWord($tgist['x_id']);
                    $newFile = $tgist['running']."/"."ApplicationForm_".$tgist['running'].".docx";
                    $zip->addFile($ApplicationForm."ApplicationForm_".$tgist['running'].".docx", $newFile);
                }
                $zip->addDir($tgist['running']."/"."Student"); // Add Folder
                $fd_pro_men = (empty($tgist['pro_id'])?"Mentor":"Professor");
                $zip->addDir($tgist['running']."/".$fd_pro_men); // Add Folder
                $zip->addDir($tgist['running']."/"."Industrial"); // Add Folder
                
                if(is_dir($path.$tgist['stu_id'])){
                    $pathStu = "/"."Student/";
                    $file = $Sch->student->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->student_transcript_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->student_portfolio_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }        
                    $file = $Sch->scholarTgist->student_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    } 
                    $file = $Sch->scholarTgist->student_attachment_other2_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
                $path_pro_men = (empty($tgist['pro_id'])?$tgist['men_id']:$tgist['pro_id']);
                if(is_dir($path.$path_pro_men)){
                    $pathProMen = "/".$fd_pro_men."/";
                    $file = (empty($Sch->professor)?$Sch->mentor->image_path:$Sch->professor->image_path);
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = (empty($Sch->professor)?$Sch->mentor->cv_path:$Sch->professor->cv_path);
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->professor_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
                if(is_dir($path.$tgist['ind_id'])){
                    $pathInd = "/"."Industrial/";
                    $file = $Sch->industrial->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->industrial_certificate_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->industrial_join_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarTgist->industrial_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $tgist['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
            }
            
            $zip->save();
            $pathUrl = '/temp/' . $ssid . "/" . $ZipName;
            header('Location: ' . Yii::app()->baseUrl . $pathUrl);
        }else{
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }
    }
    
    public function actionWord($id = NULL) {
//        ConfigWeb::PageAdminOnly();
//            $path = '/uploads/'.Yii::app()->session['person_id'].'/'.ConfigWeb::getActiveScholarId().'ApplicationForm.docx';
        $PHPWordPath = Yii::getPathOfAlias('ext.PHPWord');
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        require_once($PHPWordPath . DIRECTORY_SEPARATOR . 'PHPWord.php');
        $PHPWord = new PHPWord();
        spl_autoload_register(array('YiiBase', 'autoload'));
        
        $criteria = new CDbCriteria;
        $download = False;
        if(empty($id)){
            $id = ConfigWeb::getActiveScholarId();
            $download = True;
        }
        $criteria->condition = "id = " . $id;
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        
        $sql = Yii::app()->params['sqlExport'] . " "
                . "AND sch.id = " . $id . " "
                . "ORDER BY sch.last_updated DESC";
        $data = Yii::app()->db->createCommand($sql)->queryAll();

        if(!empty($data)){
            $document = $PHPWord->loadTemplate(Yii::app()->basePath . '/../template/tgist/TemplateApplicationForm.docx');
            $code = InitialData::FullNameScholar(ConfigWeb::getActiveScholarType()) . $Scholar->id;
//            ======    SetValue    ======
//            ======    TOP    ======
            $document->setValue('fyear', ConfigWeb::setWord($data[0]['fyear']));
            $document->setValue('x_id', ConfigWeb::setWord($data[0]['running']));
            $document->setValue('stu_edu_level', ConfigWeb::setWord($data[0]['stu_edu_level']));
            $document->setValue('xfnc_itap', ConfigWeb::setCheck($data[0]['itap']));
            
//            ======    นักศึกษา    ======
            $document->setValue('stu_fullname', ConfigWeb::setWord($data[0]['stu_fullname']));
            $document->setValue('stu_idcard', ConfigWeb::setWord($data[0]['stu_idcard']));
            
            $age = '0 วัน';
            if ($data[0]['stu_birthday'] !== NULL) {
                $datetime1 = new DateTime($data[0]['stu_birthday']);
                $datetime2 = new DateTime();
                if ($datetime1 < $datetime2) {
                    $diff = $datetime1->diff($datetime2);
                    $age = $diff->y . '  ปี  ' .
                    $diff->m . '  เดือน  ' .
                    $diff->d . '  วัน  ';
                }
            }
            $document->setValue('xfnc_stu_birthday', ConfigWeb::setWord($age));
            $document->setValue('stu_address', ConfigWeb::setWord($data[0]['stu_address']));
            $document->setValue('stu_mobile', ConfigWeb::setWord($data[0]['stu_mobile']));
            $document->setValue('stu_email', ConfigWeb::setWord($data[0]['stu_email']));
            
//            ======    สถานภาพการศึกษา    ======
            $edu_status = '';
            if ($data[0]['stu_edu_status'] == 'complete')
                $edu_status = "จบการศึกษา";
            else if($data[0]['stu_edu_status'] == 'studying')
                $edu_status = "กำลังศึกษา";
            $document->setValue('xfnc_stu_edu_status', ConfigWeb::setWord($edu_status));

            $document->setValue('stu_edu_major', ConfigWeb::setWord($data[0]['stu_edu_major']));
            $document->setValue('stu_edu_faculty', ConfigWeb::setWord($data[0]['stu_edu_faculty']));
            $document->setValue('stu_edu_institute', ConfigWeb::setWord($data[0]['stu_edu_institute']));
            $document->setValue('stu_edu_year_enrolled', ConfigWeb::setWord($data[0]['stu_edu_year_enrolled']));
            $document->setValue('stu_edu_year_graduated', ConfigWeb::setWord($data[0]['stu_edu_year_graduated']));
            $document->setValue('stu_edu_gpa', ConfigWeb::setWord($data[0]['stu_edu_gpa']));
            
            $xfnc_stu_edu_level = '';
            if(strpos($data[0]['stu_edu_level'], 'เอก') !== false){
                $xfnc_stu_edu_level = 'ปริญญาโท';
            }else if(strpos($data[0]['stu_edu_level'], 'โท') !== false){
                $xfnc_stu_edu_level = 'ปริญญาตรี';
            }
            
            $document->setValue('xfnc_stu_edu_level', ConfigWeb::setWord($xfnc_stu_edu_level));
            $document->setValue('edu_stu_gpa_before', ConfigWeb::setWord($data[0]['stu_edu_gpa_before']));

            $document->setValue('xfnc_is_history_1', ConfigWeb::setCheck($data[0]['is_history']));
            $document->setValue('xfnc_is_history_0', ConfigWeb::setCheck(!$data[0]['is_history']));
            
            $sqlExportHistory = str_replace('##PERSON_ID##', $Scholar->student->id ,Yii::app()->params['sqlExportHistory']);
            $dataHistory = Yii::app()->db->createCommand($sqlExportHistory)->queryAll();
            if(!empty($dataHistory)){
                $document->setValue('history_edu_level', ConfigWeb::setWord($dataHistory[0]['history_edu_level']));
                $document->setValue('history_name', ConfigWeb::setWord($dataHistory[0]['history_name']));
                $document->setValue('history_source', ConfigWeb::setWord($dataHistory[0]['history_source']));
                $document->setValue('history_description', ConfigWeb::setWord($dataHistory[0]['history_description']));
                $document->setValue('history_begin', ConfigWeb::setWord($dataHistory[0]['history_begin']));
                $document->setValue('history_end', ConfigWeb::setWord($dataHistory[0]['history_end']));
                
                $history_diff = ConfigWeb::GetPeriodDate($dataHistory[0]['history_begin'], $dataHistory[0]['history_end']);
                $history_begin_history_end = $history_diff->y . '  ปี  ' .
                    $history_diff->m . '  เดือน  ' .
                    $history_diff->d . '  วัน  ';
                $document->setValue('xfnc_history_begin_history_end', ConfigWeb::setWord($history_begin_history_end));
            } else{
                $document->setValue('history_edu_level', ConfigWeb::setWord(''));
                $document->setValue('history_name', ConfigWeb::setWord(''));
                $document->setValue('history_source', ConfigWeb::setWord(''));
                $document->setValue('history_description', ConfigWeb::setWord(''));
                $document->setValue('history_begin', ConfigWeb::setWord(''));
                $document->setValue('history_end', ConfigWeb::setWord(''));
                $document->setValue('xfnc_history_begin_history_end', ConfigWeb::setWord(''));
            }
            
            $document->setValue('portfolio_thesis', ConfigWeb::setWord($data[0]['portfolio_thesis']));
            $document->setValue('xfnc_portfolio_journal_international', ConfigWeb::setCheck($data[0]['portfolio_journal_international']));
            $document->setValue('portfolio_journal_international_amount', ConfigWeb::setWord($data[0]['portfolio_journal_international_amount']));
            $document->setValue('portfolio_journal_international_desc', ConfigWeb::setWord($data[0]['portfolio_journal_international_desc']));
            
            $document->setValue('xfnc_portfolio_journal_incountry', ConfigWeb::setCheck($data[0]['portfolio_journal_incountry']));
            $document->setValue('portfolio_journal_incountry_amount', ConfigWeb::setWord($data[0]['portfolio_journal_incountry_amount']));
            $document->setValue('portfolio_journal_incountry_desc', ConfigWeb::setWord($data[0]['portfolio_journal_incountry_desc']));
//            
            $document->setValue('xfnc_portfolio_patent', ConfigWeb::setCheck($data[0]['portfolio_patent']));
            $document->setValue('portfolio_patent_amount', ConfigWeb::setWord($data[0]['portfolio_patent_amount']));
            $document->setValue('portfolio_patent_desc', ConfigWeb::setWord($data[0]['portfolio_patent_desc']));
//            
            $document->setValue('xfnc_portfolio_prototype', ConfigWeb::setCheck($data[0]['portfolio_prototype']));
            $document->setValue('portfolio_prototype_amount', ConfigWeb::setWord($data[0]['portfolio_prototype_amount']));
            $document->setValue('portfolio_prototype_desc', ConfigWeb::setWord($data[0]['portfolio_prototype_desc']));
//            
            $document->setValue('xfnc_portfolio_conference_international', ConfigWeb::setCheck($data[0]['portfolio_conference_international']));
            $document->setValue('portfolio_conference_international_amount', ConfigWeb::setWord($data[0]['portfolio_conference_international_amount']));
            $document->setValue('portfolio_conference_international_desc', ConfigWeb::setWord($data[0]['portfolio_conference_international_desc']));
//            
            $document->setValue('xfnc_portfolio_conference_incountry', ConfigWeb::setCheck($data[0]['portfolio_conference_incountry']));
            $document->setValue('portfolio_conference_incountry_amount', ConfigWeb::setWord($data[0]['portfolio_conference_incountry_amount']));
            $document->setValue('portfolio_conference_incountry_desc', ConfigWeb::setWord($data[0]['portfolio_conference_incountry_desc']));
//            
            $document->setValue('xfnc_portfolio_award', ConfigWeb::setCheck($data[0]['portfolio_award']));
            $document->setValue('portfolio_award_amount', ConfigWeb::setWord($data[0]['portfolio_award_amount']));
            $document->setValue('portfolio_award_desc', ConfigWeb::setWord($data[0]['portfolio_award_desc']));
     
            $document->setValue('xfnc_portfolio_other', ConfigWeb::setCheck($data[0]['portfolio_other']));
            $document->setValue('portfolio_other_text', ConfigWeb::setWord($data[0]['portfolio_other_text']));
            $document->setValue('portfolio_other_amount', ConfigWeb::setWord($data[0]['portfolio_other_amount']));
            $document->setValue('portfolio_other_desc', ConfigWeb::setWord($data[0]['portfolio_other_desc']));

            $document->setValue('xfnc_is_work_0', ConfigWeb::setCheck(!$data[0]['is_work']));
            $document->setValue('xfnc_is_work_1', ConfigWeb::setCheck($data[0]['is_work']));
            $document->setValue('xfnc_is_experience_0', ConfigWeb::setCheck(!$data[0]['is_work']));
            $document->setValue('xfnc_is_experience_1', ConfigWeb::setCheck($data[0]['is_work']));
            $document->setValue('work_position', ConfigWeb::setWord($data[0]['work_position']));
            $document->setValue('work_company', ConfigWeb::setWord($data[0]['work_company']));

            $document->setValue('workwithproject_text1', ConfigWeb::setWord($data[0]['workwithproject_text1']));
            $document->setValue('workwithproject_text2', ConfigWeb::setWord($data[0]['workwithproject_text2']));
            $document->setValue('workwithproject_text3', ConfigWeb::setWord($data[0]['workwithproject_text3']));

            $document->setValue('pro_men_fullname', ConfigWeb::setWord($data[0]['pro_men_fullname']));
            $document->setValue('pro_men_position', ConfigWeb::setWord($data[0]['pro_men_position']));
            $document->setValue('pro_men_workarea', ConfigWeb::setWord($data[0]['pro_men_workarea']));
            $document->setValue('pro_men_expert', ConfigWeb::setWord($data[0]['pro_men_expert']));
            $document->setValue('pro_men_mobile', ConfigWeb::setWord($data[0]['pro_men_mobile']));
            $document->setValue('pro_men_email', ConfigWeb::setWord($data[0]['pro_men_email']));
            $document->setValue('pro_men_edu_level', ConfigWeb::setWord($data[0]['pro_men_edu_level']));
            $document->setValue('pro_men_edu_major', ConfigWeb::setWord($data[0]['pro_men_edu_major']));
            $document->setValue('pro_men_edu_faculty', ConfigWeb::setWord($data[0]['pro_men_edu_faculty']));
            $document->setValue('pro_men_edu_institute', ConfigWeb::setWord($data[0]['pro_men_edu_institute']));
            $document->setValue('pro_men_edu_country', ConfigWeb::setWord($data[0]['pro_men_edu_country']));
            $document->setValue('pro_men_year_enrolled', ConfigWeb::setWord($data[0]['pro_men_edu_enrolled']));
            $document->setValue('pro_men_year_graduated', ConfigWeb::setWord($data[0]['pro_men_edu_graduated']));

            $document->setValue('ind_name', ConfigWeb::setWord($data[0]['ind_name']));
            $document->setValue('ind_address', ConfigWeb::setWord($data[0]['ind_address']));
            $document->setValue('ind_fullname', ConfigWeb::setWord($data[0]['ind_fullname']));
            $document->setValue('ind_mobile', ConfigWeb::setWord($data[0]['ind_mobile']));
            $document->setValue('ind_email', ConfigWeb::setWord($data[0]['ind_email']));
            $document->setValue('xfnc_ind_type_manufacture', ConfigWeb::setCheck($data[0]['ind_type_manufacture']));
            $document->setValue('xfnc_ind_type_export', ConfigWeb::setCheck($data[0]['ind_type_export']));
            $document->setValue('xfnc_ind_type_service', ConfigWeb::setCheck($data[0]['ind_type_service']));
            $document->setValue('ind_type_description', ConfigWeb::setWord($data[0]['ind_type_description']));
            
            $document->setValue('ind_incash_salary', ConfigWeb::setCheck($data[0]['ind_incash_salary']));
            $document->setValue('ind_incash_salary_cost', ConfigWeb::setWord(number_format($data[0]['ind_incash_salary_cost'], 2, '.', ',')));
            
            $document->setValue('ind_incash_rents', ConfigWeb::setCheck($data[0]['ind_incash_rents']));
            $document->setValue('ind_incash_rents_cost', ConfigWeb::setWord(number_format($data[0]['ind_incash_rents_cost'], 2, '.', ',')));
            
            $document->setValue('ind_incash_traveling', ConfigWeb::setCheck($data[0]['ind_incash_traveling']));
            $document->setValue('ind_incash_traveling_cost', ConfigWeb::setWord(number_format($data[0]['ind_incash_traveling_cost'], 2, '.', ',')));
            
            $document->setValue('ind_incash_other', ConfigWeb::setCheck($data[0]['ind_incash_other']));
            $document->setValue('ind_incash_other_cost', ConfigWeb::setWord(number_format($data[0]['ind_incash_other_cost'], 2, '.', ',')));
            $document->setValue('ind_incash_other_text', ConfigWeb::setWord($data[0]['ind_incash_other_text']));
            
            $document->setValue('ind_incash_other2', ConfigWeb::setCheck($data[0]['ind_incash_other2']));
            $document->setValue('ind_incash_other2_cost', ConfigWeb::setWord(number_format($data[0]['ind_incash_other2_cost'], 2, '.', ',')));
            $document->setValue('ind_incash_other2_text', ConfigWeb::setWord($data[0]['ind_incash_other2_text']));
            
            $document->setValue('ind_inkind_equipment', ConfigWeb::setCheck($data[0]['ind_inkind_equipment']));
            $document->setValue('ind_inkind_equipment_cost', ConfigWeb::setWord(number_format($data[0]['ind_inkind_equipment_cost'], 2, '.', ',')));
            
            $document->setValue('ind_inkind_other', ConfigWeb::setCheck($data[0]['ind_inkind_other']));
            $document->setValue('ind_inkind_other_text', ConfigWeb::setWord($data[0]['ind_inkind_other_text']));
            $document->setValue('ind_inkind_other_cost', ConfigWeb::setWord(number_format($data[0]['ind_inkind_other_cost'], 2, '.', ',')));
            
            $document->setValue('ind_inkind_other2', ConfigWeb::setCheck($data[0]['ind_inkind_other2']));
            $document->setValue('ind_inkind_other2_text', ConfigWeb::setWord($data[0]['ind_inkind_other2_text']));
            $document->setValue('ind_inkind_other2_cost', ConfigWeb::setWord(number_format($data[0]['ind_inkind_other2_cost'], 2, '.', ',')));
            
            $document->setValue('ind_support_desc', ConfigWeb::setWord($data[0]['ind_support_desc']));

            $document->setValue('prj_name', ConfigWeb::setWord($data[0]['prj_name']));
            $document->setValue('prj_objective', ConfigWeb::setWord($data[0]['prj_objective']));
            $document->setValue('prj_scope', ConfigWeb::setWord($data[0]['prj_scope']));
            $document->setValue('prj_begin', ConfigWeb::setWord($data[0]['prj_begin']));
            $document->setValue('prj_end', ConfigWeb::setWord($data[0]['prj_end']));
            
            $prj_diff = ConfigWeb::GetPeriodDate($data[0]['prj_begin'], $data[0]['prj_end']);
            $prj_begin_prj_end = $prj_diff->y . '  ปี  ' .
                                $prj_diff->m . '  เดือน  ' .
                                $prj_diff->d . '  วัน  ';
            $document->setValue('xfnc_prj_begin_prj_end', ConfigWeb::setWord($prj_begin_prj_end));
            
            $source = NULL;
            $nstda = NULL;
            $other = NULL;
            $none = NULL;
            
            if($data[0]['prj_funding'] == 'source')
                $source = True;
            else if($data[0]['prj_funding'] == 'nstda')
                $nstda = True;
            else if($data[0]['prj_funding'] == 'other')
                $other = True;
            else if($data[0]['prj_funding'] == 'none')
                $none = True;
            
            $document->setValue('xfnc_prj_funding_name', ConfigWeb::setCheck($source));
            $document->setValue('prj_funding_name', ConfigWeb::setWord($data[0]['prj_funding_name']));
            
            $document->setValue('xfnc_is_prj_funding_code', ConfigWeb::setCheck($nstda));
            $document->setValue('prj_funding_code', ConfigWeb::setWord($data[0]['prj_funding_code']));
            
            $document->setValue('xfnc_is_prj_funding_etc', ConfigWeb::setCheck($other));
            $document->setValue('prj_funding_etc', ConfigWeb::setWord($data[0]['prj_funding_etc']));
            
            $document->setValue('xfnc_prj_funding_none', ConfigWeb::setCheck($none));
            
            $document->setValue('prj_budget', ConfigWeb::setWord(number_format($data[0]['prj_budget'], 2, '.', ',')));
            
            $document->setValue('prj_stu_name', ConfigWeb::setWord($data[0]['prj_stu_name']));
            $document->setValue('prj_stu_objective', ConfigWeb::setWord($data[0]['prj_stu_objective']));
            $document->setValue('prj_stu_begin', ConfigWeb::setWord($data[0]['prj_stu_begin']));
            $document->setValue('prj_stu_end', ConfigWeb::setWord($data[0]['prj_stu_end']));
            
            $prj_stu_diff = ConfigWeb::GetPeriodDate($data[0]['prj_stu_begin'], $data[0]['prj_stu_end']);
            $prj_stu_begin_prj_end = $prj_stu_diff->y . '  ปี  ' .
                                     $prj_stu_diff->m . '  เดือน  ' .
                                     $prj_stu_diff->d . '  วัน  ';
            $document->setValue('xfnc_prj_stu_begin_prj_stu_end', ConfigWeb::setWord($prj_stu_begin_prj_end));
            
            $document->setValue('prj_stu_expect', ConfigWeb::setWord($data[0]['prj_stu_expect']));
            $document->setValue('prj_stu_cooperation', ConfigWeb::setWord($data[0]['prj_stu_cooperation']));
            
            $document->setValue('xfnc_effect_new', ConfigWeb::setCheck($data[0]['effect_new']));
            $document->setValue('effect_new_desc', ConfigWeb::setWord($data[0]['effect_new_desc']));
            
            $document->setValue('xfnc_effect_cost', ConfigWeb::setCheck($data[0]['effect_cost']));
            $document->setValue('effect_cost_desc', ConfigWeb::setWord($data[0]['effect_cost_desc']));
            
            $document->setValue('xfnc_effect_quality', ConfigWeb::setCheck($data[0]['effect_quality']));
            $document->setValue('effect_quality_desc', ConfigWeb::setWord($data[0]['effect_quality_desc']));
            
            $document->setValue('xfnc_effect_environment', ConfigWeb::setCheck($data[0]['effect_environment']));
            $document->setValue('effect_environment_desc', ConfigWeb::setWord($data[0]['effect_environment_desc']));
            
            $document->setValue('xfnc_effect_other', ConfigWeb::setCheck($data[0]['effect_other']));
            $document->setValue('effect_other_text', ConfigWeb::setWord($data[0]['effect_other_text']));
            $document->setValue('effect_other_desc', ConfigWeb::setWord($data[0]['effect_other_desc']));
            
            $document->setValue('xfnc_relevance_automotive', ConfigWeb::setCheck($data[0]['relevance_automotive']));
            $document->setValue('relevance_automotive_desc', ConfigWeb::setWord($data[0]['relevance_automotive_desc']));
            $document->setValue('xfnc_relevance_electronics', ConfigWeb::setCheck($data[0]['relevance_electronics']));
            $document->setValue('relevance_electronics_desc', ConfigWeb::setWord($data[0]['relevance_electronics_desc']));
            $document->setValue('xfnc_relevance_tourism', ConfigWeb::setCheck($data[0]['relevance_tourism']));
            $document->setValue('relevance_tourism_desc', ConfigWeb::setWord($data[0]['relevance_tourism_desc']));
            $document->setValue('xfnc_relevance_agriculture', ConfigWeb::setCheck($data[0]['relevance_agriculture']));
            $document->setValue('relevance_agriculture_desc', ConfigWeb::setWord($data[0]['relevance_agriculture_desc']));
            $document->setValue('xfnc_relevance_food', ConfigWeb::setCheck($data[0]['relevance_food']));
            $document->setValue('relevance_food_desc', ConfigWeb::setWord($data[0]['relevance_food_desc']));
            $document->setValue('xfnc_relevance_robotics', ConfigWeb::setCheck($data[0]['relevance_robotics']));
            $document->setValue('relevance_robotics_desc', ConfigWeb::setWord($data[0]['relevance_robotics_desc']));
            $document->setValue('xfnc_relevance_aviation', ConfigWeb::setCheck($data[0]['relevance_aviation']));
            $document->setValue('relevance_aviation_desc', ConfigWeb::setWord($data[0]['relevance_aviation_desc']));
            $document->setValue('xfnc_relevance_biofuels', ConfigWeb::setCheck($data[0]['relevance_biofuels']));
            $document->setValue('relevance_biofuels_desc', ConfigWeb::setWord($data[0]['relevance_biofuels_desc']));
            $document->setValue('xfnc_relevance_digital', ConfigWeb::setCheck($data[0]['relevance_digital']));
            $document->setValue('relevance_digital_desc', ConfigWeb::setWord($data[0]['relevance_digital_desc']));
            $document->setValue('xfnc_relevance_medical', ConfigWeb::setCheck($data[0]['relevance_medical']));
            $document->setValue('relevance_medical_desc', ConfigWeb::setWord($data[0]['relevance_medical_desc']));
            
            $document->setValue('incash_fee', ConfigWeb::setCheck($data[0]['incash_fee']));
            $document->setValue('incash_monthly', ConfigWeb::setCheck($data[0]['incash_monthly']));
            $document->setValue('incash_other', ConfigWeb::setCheck($data[0]['incash_other']));
            $document->setValue('incash_other2', ConfigWeb::setCheck($data[0]['incash_other2']));
            
            $document->setValue('incash_other_text', ConfigWeb::setWord($data[0]['incash_other_text']));
            $document->setValue('incash_other2_text', ConfigWeb::setWord($data[0]['incash_other2_text']));
           
            $document->setValue('incash_fee_cost', ConfigWeb::setWord(number_format($data[0]['incash_fee_cost'], 2, '.', ',')));
            $document->setValue('incash_monthly_cost', ConfigWeb::setWord(number_format($data[0]['incash_monthly_cost'], 2, '.', ',')));
            $document->setValue('incash_other_cost', ConfigWeb::setWord(number_format($data[0]['incash_other_cost'], 2, '.', ',')));
            $document->setValue('incash_other2_cost', ConfigWeb::setWord(number_format($data[0]['incash_other2_cost'], 2, '.', ',')));
            
            $document->setValue('incash_fee_source', ConfigWeb::setWord($data[0]['incash_fee_source']));
            $document->setValue('incash_monthly_source', ConfigWeb::setWord($data[0]['incash_monthly_source']));
            $document->setValue('incash_other_source', ConfigWeb::setWord($data[0]['incash_other_source']));
            $document->setValue('incash_other2_source', ConfigWeb::setWord($data[0]['incash_other2_source']));
            
            $document->setValue('inkind_other', ConfigWeb::setCheck($data[0]['inkind_other']));
            $document->setValue('inkind_other2', ConfigWeb::setCheck($data[0]['inkind_other2']));
            $document->setValue('inkind_other_text', ConfigWeb::setWord($data[0]['inkind_other_text']));
            $document->setValue('inkind_other2_text', ConfigWeb::setWord($data[0]['inkind_other2_text']));
            
            $document->setValue('inkind_other_cost', ConfigWeb::setWord(number_format($data[0]['inkind_other_cost'], 2, '.', ',')));
            $document->setValue('inkind_other2_cost', ConfigWeb::setWord(number_format($data[0]['inkind_other2_cost'], 2, '.', ',')));
            
            $document->setValue('inkind_other_source', ConfigWeb::setWord($data[0]['inkind_other_source']));
            $document->setValue('inkind_other2_source', ConfigWeb::setWord($data[0]['inkind_other2_source']));
//            
            $document->setValue('professor_comment', ConfigWeb::setWord($data[0]['professor_comment']));
            $document->setValue('mentor_comment', ConfigWeb::setWord($data[0]['mentor_comment']));
            $document->setValue('industrial_comment', ConfigWeb::setWord($data[0]['industrial_comment']));
//            
            $document->setValue('xfnc_att_cv', ConfigWeb::setCheck($data[0]['att_cv']));
            $document->setValue('xfnc_att_project', ConfigWeb::setCheck($data[0]['att_project']));
            $document->setValue('xfnc_att_pro_men_other', ConfigWeb::setCheck($data[0]['att_pro_men_other']));
            $document->setValue('xfnc_att_transcript', ConfigWeb::setCheck($data[0]['att_transcript']));
            $document->setValue('xfnc_att_id_card', ConfigWeb::setCheck($data[0]['att_id_card']));
            $document->setValue('xfnc_att_portfolio', ConfigWeb::setCheck($data[0]['att_portfolio']));
            $document->setValue('xfnc_att_stu_other', ConfigWeb::setCheck($data[0]['att_stu_other']));
            $document->setValue('xfnc_att_stu_other2', ConfigWeb::setCheck($data[0]['att_stu_other2']));
            $document->setValue('xfnc_att_certificate', ConfigWeb::setCheck($data[0]['att_certificate']));
            $document->setValue('xfnc_att_industrial_join', ConfigWeb::setCheck($data[0]['att_industrial_join']));
            $document->setValue('xfnc_att_ind_other', ConfigWeb::setCheck($data[0]['att_ind_other']));
                
//            $document->setValue('xxx', ConfigWeb::setWord(''));
//            $document->setValue('xxx', ConfigWeb::setCheck(NULL));
            
            
            $newImage = ConfigWeb::setCheckFileImage(Yii::app()->basePath . '/../uploads/'.$Scholar->student->image_path);
            $newImage2 = NULL;
            if(!empty($Scholar->professor->id)){
                $newImage2 = ConfigWeb::setCheckFileImage(Yii::app()->basePath . '/../uploads/'.$Scholar->professor->image_path); 
            }else if(!empty($Scholar->mentor->id)){
                $newImage2 = ConfigWeb::setCheckFileImage(Yii::app()->basePath . '/../uploads/'.$Scholar->mentor->image_path); 
            }
            
            $document->replaceImage('image1.jpeg', $newImage); 
            if(!empty($newImage2)){
                $document->replaceImage('image2.jpeg', $newImage2); 
            }
            $filename = InitialData::FullNameScholar(ConfigWeb::getActiveScholarType()) . $id . '.docx';
            $path = Yii::app()->basePath . '/../template/tgist/ApplicationForm';
            $pathSave = $path . '/ApplicationForm_'. $data[0]['running'] . '.docx';
            if (!is_dir($path)) {
               @mkdir($path, 0, true);
            }
 
            $document->save($pathSave);
            if($download){
                $pathUrl = '/template/tgist/ApplicationForm/ApplicationForm_'. $data[0]['running'] . '.docx';
                header('Location: ' . Yii::app()->baseUrl . $pathUrl);
                Yii::app()->end();
            }
        }else{
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error ['message'];
            else
                $this->render('error', $error);
        }
    }
    
    public function actionChangeEmail() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = Yii::app()->session['person_id'];
        $person_type = ConfigWeb::getActivePersonType();
        
        $scholar_id = Yii::app()->request->getQuery('id');
        $utype = Yii::app()->request->getQuery('utype');
        if (empty($scholar_id) || empty($utype))
            throw new CHttpException(404, 'The requested page does not exist.');
        else {
            $Student_id_old = NULL;
            $Professor_id_old = NULL;
            $Mentor_id_old = NULL;
            $Industrial_id_old = NULL;
            $model = new ChangeEmailForm;
            $criteria = new CDbCriteria ();
            $criteria->condition = "id = " . $scholar_id . " and " . strtolower($person_type) . '_id = ' .$person_id;
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            if(!empty($Scholar)){
                if ($utype == 'student') {
                    $model->attributes = $Scholar->student->attributes;
                    $Student_id_old = $Scholar->student->id;
                } else if ($utype == 'professor') {
                    $model->attributes = $Scholar->professor->attributes;
                    $Professor_id_old = $Scholar->professor->id;
                } else if ($utype == 'mentor') {
                    $model->attributes = $Scholar->mentor->attributes;
                    $Mentor_id_old = $Scholar->mentor->id;
                } else if ($utype == 'industrial') {
                    $model->attributes = $Scholar->industrial->attributes;
                    $Industrial_id_old = $Scholar->industrial->id;
                } else {
                    throw new CHttpException(404, 'The requested page does not exist.');
                }
            }else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
            
        }
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['ChangeEmailForm'])) {
                if (isset($_POST ['next'])) {
                    $model->attributes = Yii::app()->input->post('ChangeEmailForm');
                    // ============ Student ============
                    if ($utype == 'student' && $model->email != $Scholar->student->email) {
                        if ($model->validate()) {
                            $criteria->condition = "email = '" . $model->email . "'";
                            $criteria->limit = 1;
                            $PersonNewEmail = Person::model()->find($criteria);
                            if(empty($PersonNewEmail)){
//                                $model->id = NULL;
                                $model->type = 'student';
                                $model->first_created = date("Y-m-d H:i:s");
                                $model->last_updated = date("Y-m-d H:i:s");
                                // ----------------- GEN TOKEN ------------------------
                                $getTokenRand = rand(0, 99999);
                                $getTime = date("H:i:s");
                                $email = $model->email;
                                $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                                $model->setIsNewRecord(TRUE);
                                if ($model->save()) {
                                    if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {}
                                    $Scholar->student_id = $model->id;
                                    $Scholar->setIsNewRecord(FALSE);
                                    if($Scholar->update()){
                                        $criteria->condition = "person_id = " . $Student_id_old . " and scholar_id = " . $scholar_id;
                                        $criteria->limit = 1;
                                        $Comment = Comment::model()->find($criteria);
                                        $Comment->person_id = $model->id;
                                        $Comment->status = 'draft';
    //                                    $Comment->comment = '';
                                        $Comment->first_created = $Comment->first_created;
                                        $Comment->last_updated = date("Y-m-d H:i:s");
                                        $Comment->setIsNewRecord(FALSE);
                                        if($Comment->update()){
                                            //Reset Token & Resent Sand Mail
                                            //=======================================================================
                                            $SendMail = new SendMail ();
                                            $scholar_type = strtoupper($scholar_type);
                                            $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                            $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                            $subject = str_replace("##TYPE##", "นักเรียน/นักศึกษา", $subject);
                                            $SendMail->subject = $subject;
                                            $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                            $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                            $PERSONNAME = $model->fname . "  " . $model->lname;
                                            $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                            $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                            $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                                'token' => $model->token,
                                                'scholartype' => ConfigWeb::getActiveScholarType()
                                            ));
                                            $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                            $message = str_replace("##URL##", $URL, $message);
                                            $SendMail->body = $message;
                                            $SendMail->to = $model->email;
                                            $SendMail->from = "noreply@nstda.or.th";
                                            $SendMail->send();
                                            //=======================================================================
                                            Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
                                            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                        }
                                    }
                                }else{
                                    Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                    $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                }
                            }else{
                                $Student_id_old = $Scholar->student->id;
                                $Scholar->student_id = $PersonNewEmail->id;
                                $Scholar->setIsNewRecord(FALSE);
                                if($Scholar->update()){
                                    $criteria->condition = "person_id = " . $Student_id_old . " and scholar_id = " . $scholar_id;
                                    $criteria->limit = 1;
                                    $Comment = Comment::model()->find($criteria);
                                    $Comment->person_id = $PersonNewEmail->id;
                                    $Comment->status = 'draft';
                                    $Comment->first_created = $Comment->first_created;
                                    $Comment->last_updated = date("Y-m-d H:i:s");
                                    $Comment->setIsNewRecord(FALSE);
                                    if($Comment->update()){
                                        //=======================================================================
                                        $SendMail = new SendMail ();
                                        $scholar_type = strtoupper($scholar_type);
                                        $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                        $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                        $subject = str_replace("##TYPE##", "นักเรียน/นักศึกษา", $subject);
                                        $SendMail->subject = $subject;
                                        $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                        $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                        $PERSONNAME = $Scholar->student->fname . "  " . $Scholar->student->lname;
                                        $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                        $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                        $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                            'token' => $Scholar->student->token,
                                            'scholartype' => ConfigWeb::getActiveScholarType()
                                        ));
                                        $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                        $message = str_replace("##URL##", $URL, $message);
                                        $SendMail->body = $message;
                                        $SendMail->to = $Scholar->student->email;
                                        $SendMail->from = "noreply@nstda.or.th";
                                        $SendMail->send();
                                        //=======================================================================
                                        
                                        Yii::app()->user->setFlash('success', "เปลี่ยน Email เรียบร้อยแล้ว / Email Success!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }else{
                                        Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }
                                }
                            }
                        }
                    }
                    // ============ Professor ============
                    else if ($utype == 'professor' && $model->email != $Scholar->professor->email) {       
                        if ($model->validate()) {
                            $criteria->condition = "email = '" . $model->email . "'";
                            $criteria->limit = 1;
                            $PersonNewEmail = Person::model()->find($criteria);
                            if(empty($PersonNewEmail)){
//                                $model->id = NULL;
                                $model->type = 'professor';
                                $model->first_created = date("Y-m-d H:i:s");
                                $model->last_updated = date("Y-m-d H:i:s");
                                // ----------------- GEN TOKEN ------------------------
                                $getTokenRand = rand(0, 99999);
                                $getTime = date("H:i:s");
                                $email = $model->email;
                                $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                                $model->setIsNewRecord(TRUE);
                                if ($model->save()) {
                                    if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {}
                                    $Scholar->professor_id = $model->id;
                                    $Scholar->setIsNewRecord(FALSE);
                                    if($Scholar->update()){
                                        $criteria->condition = "person_id = " . $Professor_id_old . " and scholar_id = " . $scholar_id;
                                        $criteria->limit = 1;
                                        $Comment = Comment::model()->find($criteria);
                                        $Comment->person_id = $model->id;
                                        $Comment->status = 'draft';
    //                                    $Comment->comment = '';
                                        $Comment->first_created = $Comment->first_created;
                                        $Comment->last_updated = date("Y-m-d H:i:s");
                                        $Comment->setIsNewRecord(FALSE);
                                        if($Comment->update()){
                                            //=======================================================================
                                            $SendMail = new SendMail ();
                                            $scholar_type = strtoupper($scholar_type);
                                            $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                            $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                            $subject = str_replace("##TYPE##", "อาจารย์ที่ปรึกษา", $subject);
                                            $SendMail->subject = $subject;
                                            $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                            $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                            $PERSONNAME = $model->fname . "  " . $model->lname;
                                            $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                            $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                            $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                                'token' => $model->token,
                                                'scholartype' => ConfigWeb::getActiveScholarType()
                                            ));
                                            $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                            $message = str_replace("##URL##", $URL, $message);
                                            $SendMail->body = $message;
                                            $SendMail->to = $model->email;
                                            $SendMail->from = "noreply@nstda.or.th";
                                            $SendMail->send();
                                            //=======================================================================

                                            Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
                                            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                        }
                                    }
                                }else{
                                    Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                    $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                }
                            }else{
                                $Professor_id_old = $Scholar->professor->id;
                                $Scholar->professor_id = $PersonNewEmail->id;
                                $Scholar->setIsNewRecord(FALSE);
                                
                                if($Scholar->update()){
                                    $criteria->condition = "person_id = " . $Professor_id_old . " and scholar_id = " . $scholar_id;
                                    $criteria->limit = 1;
                                    $Comment = Comment::model()->find($criteria);
                                    $Comment->person_id = $PersonNewEmail->id;
                                    $Comment->status = 'draft';
                                    $Comment->first_created = $Comment->first_created;
                                    $Comment->last_updated = date("Y-m-d H:i:s");
                                    $Comment->setIsNewRecord(FALSE);
                                    if($Comment->update()){
                                        //=======================================================================
                                        $SendMail = new SendMail ();
                                        $scholar_type = strtoupper($scholar_type);
                                        $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                        $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                        $subject = str_replace("##TYPE##", "อาจารย์ที่ปรึกษา", $subject);
                                        $SendMail->subject = $subject;
                                        $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                        $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                        $PERSONNAME = $Scholar->professor->fname . "  " . $Scholar->professor->lname;
                                        $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                        $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                        $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                            'token' => $Scholar->professor->token,
                                            'scholartype' => ConfigWeb::getActiveScholarType()
                                        ));
                                        $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                        $message = str_replace("##URL##", $URL, $message);
                                        $SendMail->body = $message;
                                        $SendMail->to = $Scholar->professor->email;
                                        $SendMail->from = "noreply@nstda.or.th";
                                        $SendMail->send();
                                        //=======================================================================

                                        Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }else{
                                        Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }
                                }
                            }
                        }
                    }
                    // ============ Mentor ============
                    else if ($utype == 'mentor' && $model->email != $Scholar->mentor->email) {       
                        if ($model->validate()) {
                            $criteria->condition = "email = '" . $model->email . "'";
                            $criteria->limit = 1;
                            $PersonNewEmail = Person::model()->find($criteria);
                            if(empty($PersonNewEmail)){
//                                $model->id = NULL;
                                $model->type = 'mentor';
                                $model->first_created = date("Y-m-d H:i:s");
                                $model->last_updated = date("Y-m-d H:i:s");
                                // ----------------- GEN TOKEN ------------------------
                                $getTokenRand = rand(0, 99999);
                                $getTime = date("H:i:s");
                                $email = $model->email;
                                $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                                $model->setIsNewRecord(TRUE);
                                if ($model->save()) {
                                    if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {}
                                    $Scholar->mentor_id = $model->id;
                                    $Scholar->setIsNewRecord(FALSE);
                                    if($Scholar->update()){
                                        $criteria->condition = "person_id = " . $Mentor_id_old . " and scholar_id = " . $scholar_id;
                                        $criteria->limit = 1;
                                        $Comment = Comment::model()->find($criteria);
                                        $Comment->person_id = $model->id;
                                        $Comment->status = 'draft';
    //                                    $Comment->comment = '';
                                        $Comment->first_created = $Comment->first_created;
                                        $Comment->last_updated = date("Y-m-d H:i:s");
                                        $Comment->setIsNewRecord(FALSE);
                                        if($Comment->update()){
                                            //=======================================================================
                                            $SendMail = new SendMail ();
                                            $scholar_type = strtoupper($scholar_type);
                                            $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                            $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                            $subject = str_replace("##TYPE##", "นักวิจัยสวทช.", $subject);
                                            $SendMail->subject = $subject;
                                            $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                            $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                            $PERSONNAME = $model->fname . "  " . $model->lname;
                                            $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                            $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                            $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                                'token' => $model->token,
                                                'scholartype' => ConfigWeb::getActiveScholarType()
                                            ));
                                            $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                            $message = str_replace("##URL##", $URL, $message);
                                            $SendMail->body = $message;
                                            $SendMail->to = $model->email;
                                            $SendMail->from = "noreply@nstda.or.th";
                                            $SendMail->send();
                                            //=======================================================================

                                            Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
                                            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                        }
                                    }
                                }else{
                                    Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                    $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                }
                            }else{
                                $Mentor_id_old = $Scholar->mentor->id;
                                $Scholar->mentor_id = $PersonNewEmail->id;
                                $Scholar->setIsNewRecord(FALSE);
                                
                                if($Scholar->update()){
                                    $criteria->condition = "person_id = " . $Mentor_id_old . " and scholar_id = " . $scholar_id;
                                    $criteria->limit = 1;
                                    $Comment = Comment::model()->find($criteria);
                                    $Comment->person_id = $PersonNewEmail->id;
                                    $Comment->status = 'draft';
                                    $Comment->first_created = $Comment->first_created;
                                    $Comment->last_updated = date("Y-m-d H:i:s");
                                    $Comment->setIsNewRecord(FALSE);
                                    if($Comment->update()){
                                        //=======================================================================
                                        $SendMail = new SendMail ();
                                        $scholar_type = strtoupper($scholar_type);
                                        $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                        $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                        $subject = str_replace("##TYPE##", "นักวิจัยสวทช.", $subject);
                                        $SendMail->subject = $subject;
                                        $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                        $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                        $PERSONNAME = $Scholar->mentor->fname . "  " . $Scholar->mentor->lname;
                                        $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                        $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                        $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                            'token' => $Scholar->mentor->token,
                                            'scholartype' => ConfigWeb::getActiveScholarType()
                                        ));
                                        $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                        $message = str_replace("##URL##", $URL, $message);
                                        $SendMail->body = $message;
                                        $SendMail->to = $Scholar->mentor->email;
                                        $SendMail->from = "noreply@nstda.or.th";
                                        $SendMail->send();
                                        //=======================================================================

                                        Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }else{
                                        Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }
                                }
                            }
                        }
                    }
                    // ============ Industrial ============
                    else if ($utype == 'industrial' && $model->email != $Scholar->industrial->email) {       
                        if ($model->validate()) {
                            $criteria->condition = "email = '" . $model->email . "'";
                            $criteria->limit = 1;
                            $PersonNewEmail = Person::model()->find($criteria);
                            if(empty($PersonNewEmail)){
//                                $model->id = NULL;
                                $model->type = 'industrial';
                                $model->first_created = date("Y-m-d H:i:s");
                                $model->last_updated = date("Y-m-d H:i:s");
                                // ----------------- GEN TOKEN ------------------------
                                $getTokenRand = rand(0, 99999);
                                $getTime = date("H:i:s");
                                $email = $model->email;
                                $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                                $model->setIsNewRecord(TRUE);
                                if ($model->save()) {
                                    if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {}
                                    $Scholar->industrial_id = $model->id;
                                    $Scholar->setIsNewRecord(FALSE);
                                    if($Scholar->update()){
                                        $criteria->condition = "person_id = " . $Industrial_id_old . " and scholar_id = " . $scholar_id;
                                        $criteria->limit = 1;
                                        $Comment = Comment::model()->find($criteria);
                                        $Comment->person_id = $model->id;
                                        $Comment->status = 'draft';
    //                                    $Comment->comment = '';
                                        $Comment->first_created = $Comment->first_created;
                                        $Comment->last_updated = date("Y-m-d H:i:s");
                                        $Comment->setIsNewRecord(FALSE);
                                        if($Comment->update()){
                                            //=======================================================================
                                            $SendMail = new SendMail ();
                                            $scholar_type = strtoupper($scholar_type);
                                            $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                            $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                            $subject = str_replace("##TYPE##", "บริษัท/ภาคอุตสาหกรรม", $subject);
                                            $SendMail->subject = $subject;
                                            $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                            $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                            $PERSONNAME = $model->fname . "  " . $model->lname;
                                            $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                            $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                            $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                                'token' => $model->token,
                                                'scholartype' => ConfigWeb::getActiveScholarType()
                                            ));
                                            $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                            $message = str_replace("##URL##", $URL, $message);
                                            $SendMail->body = $message;
                                            $SendMail->to = $model->email;
                                            $SendMail->from = "noreply@nstda.or.th";
                                            $SendMail->send();
                                            //=======================================================================

                                            Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
                                            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                        }
                                    }
                                }else{
                                    Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                    $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                }
                            }else{
                                $Industrial_id_old = $Scholar->industrial->id;
                                $Scholar->industrial_id = $PersonNewEmail->id;
                                $Scholar->setIsNewRecord(FALSE);
                                
                                if($Scholar->update()){
                                    $criteria->condition = "person_id = " . $Industrial_id_old . " and scholar_id = " . $scholar_id;
                                    $criteria->limit = 1;
                                    $Comment = Comment::model()->find($criteria);
                                    $Comment->person_id = $PersonNewEmail->id;
                                    $Comment->status = 'draft';
                                    $Comment->first_created = $Comment->first_created;
                                    $Comment->last_updated = date("Y-m-d H:i:s");
                                    $Comment->setIsNewRecord(FALSE);
                                    if($Comment->update()){
                                        //=======================================================================
                                        $SendMail = new SendMail ();
                                        $scholar_type = strtoupper($scholar_type);
                                        $subject = Yii::app()->params ['EmailTemplateChangeEmailSubject'];
                                        $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                        $subject = str_replace("##TYPE##", "บริษัท/ภาคอุตสาหกรรม", $subject);
                                        $SendMail->subject = $subject;
                                        $message = Yii::app()->params ['EmailTemplateChangeEmailBody'];
                                        $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                        $PERSONNAME = $Scholar->industrial->fname . "  " . $Scholar->industrial->lname;
                                        $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                        $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                        $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                            'token' => $Scholar->industrial->token,
                                            'scholartype' => ConfigWeb::getActiveScholarType()
                                        ));
                                        $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                        $message = str_replace("##URL##", $URL, $message);
                                        $SendMail->body = $message;
                                        $SendMail->to = $Scholar->industrial->email;
                                        $SendMail->from = "noreply@nstda.or.th";
                                        $SendMail->send();
                                        //=======================================================================

                                        Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }else{
                                        Yii::app()->user->setFlash('error', "ไม่สามารถเปลี่ยน Email ได้ / Change Email error!!");
                                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                                    }
                                }
                            }
                        }
                    }else{
                        Yii::app()->user->setFlash('success', "เปลี่ยน Email เรียบร้อยแล้ว / Email Success!!");
                        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
                    }
                }
//                Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
//                $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
            }
        }
        $model->scholar_id = $scholar_id;
        $model->type = $utype;
        $this->render('ChangeEmail', array(
            'model' => $model,
            'id' => $scholar_id,
            'utype' => $utype
        ));
    }
    
    public function actionReSend() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = Yii::app()->session['person_id'];
        $person_type = ConfigWeb::getActivePersonType();
        $scholar_id = Yii::app()->request->getQuery('id');
        $utype = Yii::app()->request->getQuery('utype');
        if (empty($scholar_id))
            throw new CHttpException(404, 'The requested page does not exist.');
        else {
            if ($utype == 'student') {
                // ระบบส่งเมล์แจ้ง นักเรียน/นักศึกษา *************************
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $scholar_id;
                $criteria->limit = 1;
                $Scholar = Scholar::model()->find($criteria);

                $SendMail = new SendMail ();
                $scholar_type = strtoupper($scholar_type);
                $subject = Yii::app()->params ['EmailTemplateRecommendationSubject'];
                $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                $subject = str_replace("##TYPE##", "นักเรียน/นักศึกษา", $subject);
                $SendMail->subject = $subject;
                $message = Yii::app()->params ['EmailTemplateRecommendationBody'];
                $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                $PERSONNAME = $Scholar->student->fname . "  " . $Scholar->student->lname;
                $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                    'token' => $Scholar->student->token,
                    'scholartype' => ConfigWeb::getActiveScholarType()
                ));
                $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                $message = str_replace("##URL##", $URL, $message);
                $SendMail->body = $message;
                $SendMail->to = $Scholar->student->email;
                $SendMail->from = "noreply@nstda.or.th";
                $SendMail->send();
            } else if ($utype == 'professor') {
                // ระบบส่งเมล์แจ้ง อาจารย์ที่ปรึกษา *************************
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $scholar_id;
                $criteria->limit = 1;
                $Scholar = Scholar::model()->find($criteria);

                $SendMail = new SendMail ();
                $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                $subject = Yii::app()->params ['EmailTemplateRecommendationSubject'];
                $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                $subject = str_replace("##TYPE##", "อาจารย์ที่ปรึกษา", $subject);
                $SendMail->subject = $subject;
                $message = Yii::app()->params ['EmailTemplateRecommendationBody'];
                $message = str_replace("##TYPE##", "อาจารย์ที่ปรึกษา", $message);
                $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                $PERSONNAME = $Scholar->professor->fname . "  " . $Scholar->professor->lname;
                $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                    'token' => $Scholar->professor->token,
                    'scholartype' => ConfigWeb::getActiveScholarType()
                ));
                $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                $message = str_replace("##URL##", $URL, $message);
                $SendMail->body = $message;
                $SendMail->to = $Scholar->professor->email;
                $SendMail->from = "noreply@nstda.or.th";
                $SendMail->send();
            } else if ($utype == 'mentor') {
                // ระบบส่งเมล์แจ้ง นักวิจัยสวทช. *************************
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $scholar_id;
                $criteria->limit = 1;
                $Scholar = Scholar::model()->find($criteria);

                $SendMail = new SendMail ();
                $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                $subject = Yii::app()->params ['EmailTemplateRecommendationSubject'];
                $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                $subject = str_replace("##TYPE##", "นักวิจัยสวทช.", $subject);
                $SendMail->subject = $subject;
                $message = Yii::app()->params ['EmailTemplateRecommendationBody'];
                $message = str_replace("##TYPE##", "นักวิจัยสวทช.", $message);
                $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                $PERSONNAME = $Scholar->mentor->fname . "  " . $Scholar->mentor->lname;
                $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                    'token' => $Scholar->mentor->token,
                    'scholartype' => ConfigWeb::getActiveScholarType()
                ));
                $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                $message = str_replace("##URL##", $URL, $message);
                $SendMail->body = $message;
                $SendMail->to = $Scholar->mentor->email;
                $SendMail->from = "noreply@nstda.or.th";
                $SendMail->send();
            } else if ($utype == 'industrial') {
                // ระบบส่งเมล์แจ้ง บริษัท/ภาคอุตสาหกรรม *************************
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $scholar_id;
                $criteria->limit = 1;
                $Scholar = Scholar::model()->find($criteria);

                $SendMail = new SendMail ();
                $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                $subject = Yii::app()->params ['EmailTemplateRecommendationSubject'];
                $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                $subject = str_replace("##TYPE##", "บริษัท/ภาคอุตสาหกรรม", $subject);
                $SendMail->subject = $subject;
                $message = Yii::app()->params ['EmailTemplateRecommendationBody'];
                $message = str_replace("##TYPE##", "บริษัท/ภาคอุตสาหกรรม", $message);
                $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                $PERSONNAME = $Scholar->industrial->fname . "  " . $Scholar->industrial->lname;
                $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                    'token' => $Scholar->industrial->token,
                    'scholartype' => ConfigWeb::getActiveScholarType()
                ));
                $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                $message = str_replace("##URL##", $URL, $message);
                $SendMail->body = $message;
                $SendMail->to = $Scholar->industrial->email;
                $SendMail->from = "noreply@nstda.or.th";
                $SendMail->send();
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        }
        Yii::app()->user->setFlash('success', "ส่ง Email เรียบร้อยแล้ว / Email Success!!");
        $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
    }

    public function actionRecommendation() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();

        $scholar_tgist_id = NULL;
        $model = new TgistRecommendationForm ();
        $sql = "SELECT 
                CONCAT(s.fname,'  ',s.lname) AS student_name,
                CONCAT(p.fname,'  ',p.lname) AS professor_name,
                CONCAT(m.fname,'  ',m.lname) AS mentor_name,
                CONCAT(i.fname,'  ',i.lname) AS industrial_name,
                    i.industrial AS industrial_full,
                    project.name AS project_name,
                    project.objective AS project_objective,
                    project.scope AS project_scope,
                    scholar_tgist.project_name AS project_student_name,
                    scholar_tgist.project_begin AS project_student_begin,
                    scholar_tgist.project_end AS project_student_end,
                    scholar_tgist.objective AS project_student_objective,
                    scholar_tgist.expect AS project_student_expect,
                    scholar_tgist.id AS scholar_tgist_id
                FROM
                scholar
                LEFT JOIN scholar_tgist ON scholar.scholar_tgist_id = scholar_tgist.id
                LEFT JOIN project ON scholar_tgist.project_id = project.id
                LEFT JOIN person s ON scholar.student_id = s.id 
                LEFT JOIN person p ON scholar.professor_id = p.id 
                LEFT JOIN person m ON scholar.mentor_id = m.id 
                LEFT JOIN person i ON scholar.industrial_id = i.id 
                WHERE scholar.id=" . ConfigWeb::getActiveScholarId();
        $RecommendationDisplay = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($RecommendationDisplay [0] as $key => $value) {
            if ($key != 'scholar_tgist_id')
                $model->$key = $value;
            else {
                $scholar_tgist_id = $value;
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $scholar_tgist_id;
                $criteria->limit = 1;
                $ScholarTgist = ScholarTgist::model()->find($criteria);
                // $model->industrial_support = $ScholarTgist->industrial_support;
                $model->industrial_incash_salary = $ScholarTgist->industrial_incash_salary;
                $model->industrial_incash_salary_cost = $ScholarTgist->industrial_incash_salary_cost;
                $model->industrial_incash_rents = $ScholarTgist->industrial_incash_rents;
                $model->industrial_incash_rents_cost = $ScholarTgist->industrial_incash_rents_cost;
                $model->industrial_incash_traveling = $ScholarTgist->industrial_incash_traveling;
                $model->industrial_incash_traveling_cost = $ScholarTgist->industrial_incash_traveling_cost;
                $model->industrial_incash_other = $ScholarTgist->industrial_incash_other;
                $model->industrial_incash_other_cost = $ScholarTgist->industrial_incash_other_cost;
                $model->industrial_incash_other_text = $ScholarTgist->industrial_incash_other_text;
                $model->industrial_incash_other2 = $ScholarTgist->industrial_incash_other2;
                $model->industrial_incash_other2_cost = $ScholarTgist->industrial_incash_other2_cost;
                $model->industrial_incash_other2_text = $ScholarTgist->industrial_incash_other2_text;
                $model->industrial_inkind_equipment = $ScholarTgist->industrial_inkind_equipment;
                $model->industrial_inkind_equipment_cost = $ScholarTgist->industrial_inkind_equipment_cost;
                $model->industrial_inkind_other = $ScholarTgist->industrial_inkind_other;
                $model->industrial_inkind_other_cost = $ScholarTgist->industrial_inkind_other_cost;
                $model->industrial_inkind_other_text = $ScholarTgist->industrial_inkind_other_text;
                $model->industrial_inkind_other2 = $ScholarTgist->industrial_inkind_other2;
                $model->industrial_inkind_other2_cost = $ScholarTgist->industrial_inkind_other2_cost;
                $model->industrial_inkind_other2_text = $ScholarTgist->industrial_inkind_other2_text;
//                $model->industrial_support_desc = $ScholarTgist->industrial_support_desc;
                $criteria->condition = "" . "scholar_id = " . ConfigWeb::getActiveScholarId() . " and person_id = " . $person_id;
                $criteria->limit = 1;
                $Comment = Comment::model()->find($criteria);
                $model->comment = $Comment->comment;
                $model->status = $Comment->status;
                
                $criteria->condition = " id = " . $ScholarTgist->id;
                $criteria->limit = 1;
            }
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistRecommendationForm'])) {
                $model->attributes = Yii::app()->input->post('TgistRecommendationForm');

                if (empty($model->industrial_incash_salary)) {
                    $model->industrial_incash_salary_cost = 0;
                }
                if (empty($model->industrial_incash_rents)) {
                    $model->industrial_incash_rents_cost = 0;
                }
                if (empty($model->industrial_incash_traveling)) {
                    $model->industrial_incash_traveling_cost = 0;
                }
                if (empty($model->industrial_incash_other)) {
                    $model->industrial_incash_other_cost = 0;
                    $model->industrial_incash_other_text = NULL;
                }
                if (empty($model->industrial_incash_other2)) {
                    $model->industrial_incash_other2_cost = 0;
                    $model->industrial_incash_other2_text = NULL;
                }
                if (empty($model->industrial_inkind_equipment)) {
                    $model->industrial_inkind_equipment_cost = 0;
                }
                if (empty($model->industrial_inkind_other)) {
                    $model->industrial_inkind_other_cost = 0;
                    $model->industrial_inkind_other_text = NULL;
                }
                if (empty($model->industrial_inkind_other2)) {
                    $model->industrial_inkind_other2_cost = 0;
                    $model->industrial_inkind_other2_text = NULL;
                }
                if (isset($_POST ['draft']) || isset($_POST ['savesend']) || isset($_POST ['save'])) {

                    if ($model->validate()) {
                        // $ScholarTgist->industrial_support = $model->industrial_support;
                        // $ScholarTgist->first_created = $ScholarTgist->first_created;
                        // $ScholarTgist->last_updated = date("Y-m-d H:i:s");
                        $ScholarTgist->industrial_incash_salary = $model->industrial_incash_salary;
                        $ScholarTgist->industrial_incash_salary_cost = $model->industrial_incash_salary_cost;
                        $ScholarTgist->industrial_incash_rents = $model->industrial_incash_rents;
                        $ScholarTgist->industrial_incash_rents_cost = $model->industrial_incash_rents_cost;
                        $ScholarTgist->industrial_incash_traveling = $model->industrial_incash_traveling;
                        $ScholarTgist->industrial_incash_traveling_cost = $model->industrial_incash_traveling_cost;
                        $ScholarTgist->industrial_incash_other = $model->industrial_incash_other;
                        $ScholarTgist->industrial_incash_other_cost = $model->industrial_incash_other_cost;
                        $ScholarTgist->industrial_incash_other_text = $model->industrial_incash_other_text;
                        $ScholarTgist->industrial_incash_other2 = $model->industrial_incash_other2;
                        $ScholarTgist->industrial_incash_other2_cost = $model->industrial_incash_other2_cost;
                        $ScholarTgist->industrial_incash_other2_text = $model->industrial_incash_other2_text;
                        $ScholarTgist->industrial_inkind_equipment = $model->industrial_inkind_equipment;
                        $ScholarTgist->industrial_inkind_equipment_cost = $model->industrial_inkind_equipment_cost;
                        $ScholarTgist->industrial_inkind_other = $model->industrial_inkind_other;
                        $ScholarTgist->industrial_inkind_other_cost = $model->industrial_inkind_other_cost;
                        $ScholarTgist->industrial_inkind_other_text = $model->industrial_inkind_other_text;
                        $ScholarTgist->industrial_inkind_other2 = $model->industrial_inkind_other2;
                        $ScholarTgist->industrial_inkind_other2_cost = $model->industrial_inkind_other2_cost;
                        $ScholarTgist->industrial_inkind_other2_text = $model->industrial_inkind_other2_text;
                        $ScholarTgist->industrial_support_desc = $model->industrial_support_desc;
                        $ScholarTgist->setIsNewRecord(FALSE);
                        if ($ScholarTgist->update()) {
                            if (isset($_POST ['draft'])) {
                                $Comment->status = 'draft';
                            } else if (isset($_POST ['savesend'])) {
                                $Comment->status = 'sent';
                            }
                            $Comment->comment = $model->comment;
                            $Comment->first_created = $Comment->first_created;
                            $Comment->last_updated = date("Y-m-d H:i:s");
                            $Comment->setIsNewRecord(FALSE);
                            if ($Comment->update()) {
                                $sql = "SELECT COUNT(*) AS total,
                                            IFNULL(SUM(CASE WHEN status='draft' THEN 1 ELSE 0 END),0) AS draft,
                                            IFNULL(SUM(CASE WHEN status='sent' THEN 1 ELSE 0 END),0) AS sent
                                        FROM comment WHERE scholar_id=" . ConfigWeb::getActiveScholarId();
                                $ReCheckConfirm = Yii::app()->db->createCommand($sql)->queryAll();
                                if ($ReCheckConfirm [0] ['total'] == $ReCheckConfirm [0] ['sent']) {
                                    $criteria = new CDbCriteria ();
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
                                }
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));
                            }
                        }
                    }
                }
            }
        }

        if ($model->project_student_begin !== NULL && $model->project_student_end !== NULL) {
            $diff = ConfigWeb::GetPeriodDate($model->project_student_begin, $model->project_student_end);
            if ($diff) {
                $model->project_student_func = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
            } else {
                $model->project_student_func = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
            }
            if (!empty($model->project_student_begin))
                $model->project_student_begin = date("d/m/Y", strtotime($model->project_student_begin));
            if (!empty($model->project_student_end))
                $model->project_student_end = date("d/m/Y", strtotime($model->project_student_end));
        }

        $model->incash_sum = $model->industrial_incash_salary_cost + $model->industrial_incash_rents_cost + $model->industrial_incash_traveling_cost + $model->industrial_incash_other_cost + $model->industrial_incash_other2_cost;
        $model->inkind_sum = $model->industrial_inkind_equipment_cost + $model->industrial_inkind_other_cost + $model->industrial_inkind_other2_cost;
        $model->sum = $model->incash_sum + $model->inkind_sum;

        $this->render('recommendation', array(
            'model' => $model
        ));
    }

    public function actionProfessor() {
        $Scholar = NULL;
        $Professor = NULL;
        $model = new TgistStudentForm ();

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        
        if (!empty($Scholar->professor_id)) {
            $criteria->condition = "id = " . $Scholar->professor_id;
            $criteria->limit = 1;
            $Professor = Person::model()->find($criteria);
            $model->attributes = $Professor->attributes;
            $model->professor_id = $Scholar->professor_id;
            $model->status = $Scholar->status;
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistStudentForm'])) {
                $model->attributes = Yii::app()->input->post('TgistStudentForm');
                
                $criteria->condition = "email = '" . $model->email . "'";
                $criteria->limit = 1;
                $Professor = Person::model()->find($criteria);
                
                if (isset($_POST ['next'])) {
                    if ($model->validate()) {
                        if (!empty($Professor)) {
                            $model->first_created = $Professor->first_created;
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->setIsNewRecord(FALSE);
                            if ($model->update()) {
                                $criteria->condition = "email = '" . $model->email . "' and type = 'professor' ";
                                $criteria->limit = 1;
                                $Professor = Person::model()->find($criteria);

                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->professor_id = $Professor->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                    $this->redirect(Yii::app()->createUrl($UrlNext));
                                }
                            }
                        } else {
                            $model->id = NULL;
                            $model->type = 'professor';
                            $model->first_created = date("Y-m-d H:i:s");
                            $model->last_updated = date("Y-m-d H:i:s");
                            // ----------------- GEN TOKEN ------------------------
                            $getTokenRand = rand(0, 99999);
                            $getTime = date("H:i:s");
                            $email = $model->email;
                            $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                            $model->setIsNewRecord(TRUE);
                            if ($model->save()) {
                                if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {
                                    
                                }
                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->professor_id = $model->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $criteria->condition = "email = '" . $model->email . "' and type = 'professor' ";
                                    $criteria->limit = 1;
                                    $Professor = Person::model()->find($criteria);

                                    $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                    $criteria->limit = 1;
                                    $Scholar = Scholar::model()->find($criteria);
                                    $Scholar->professor_id = $Professor->id;
                                    $Scholar->last_updated = date("Y-m-d H:i:s");
                                    $Scholar->setIsNewRecord(FALSE);
                                    if ($Scholar->update()) {
                                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                        $this->redirect(Yii::app()->createUrl($UrlNext));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        $this->render('professor', array(
            'model' => $model
        ));
    }
    
    public function actionMentor() {
        $Scholar = NULL;
        $Mentor = NULL;
        $model = new TgistStudentForm ();

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);

        if (!empty($Scholar->mentor_id)) {
            $criteria->condition = "id = " . $Scholar->mentor_id;
            $criteria->limit = 1;
            $Mentor = Person::model()->find($criteria);
            $model->attributes = $Mentor->attributes;
            $model->mentor_id = $Scholar->mentor_id;
            $model->status = $Scholar->status;
        }

	if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistStudentForm'])) {
                $model->attributes = Yii::app()->input->post('TgistStudentForm');
                
                $criteria->condition = "email = '" . $model->email . "'";
                $criteria->limit = 1;
                $Mentor = Person::model()->find($criteria);
                
                if (isset($_POST ['next'])) {
                    if ($model->validate()) {
                        if (!empty($Mentor)) {
                            $model->first_created = $Mentor->first_created;
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->setIsNewRecord(FALSE);
                            if ($model->update()) {
                                $criteria->condition = "email = '" . $model->email . "' and type = 'mentor' ";
                                $criteria->limit = 1;
                                $Mentor = Person::model()->find($criteria);

                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->mentor_id = $Mentor->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                    $this->redirect(Yii::app()->createUrl($UrlNext));
                                }
                            }
                        } else {
                            $model->id = NULL;
                            $model->type = 'mentor';
                            $model->first_created = date("Y-m-d H:i:s");
                            $model->last_updated = date("Y-m-d H:i:s");
                            // ----------------- GEN TOKEN ------------------------
                            $getTokenRand = rand(0, 99999);
                            $getTime = date("H:i:s");
                            $email = $model->email;
                            $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                            $model->setIsNewRecord(TRUE);
                            if ($model->save()) {
                                if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {
                                    
                                }
                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->mentor_id = $model->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $criteria->condition = "email = '" . $model->email . "' and type = 'mentor' ";
                                    $criteria->limit = 1;
                                    $Mentor = Person::model()->find($criteria);

                                    $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                    $criteria->limit = 1;
                                    $Scholar = Scholar::model()->find($criteria);
                                    $Scholar->mentor_id = $Mentor->id;
                                    $Scholar->last_updated = date("Y-m-d H:i:s");
                                    $Scholar->setIsNewRecord(FALSE);
                                    if ($Scholar->update()) {
                                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                        $this->redirect(Yii::app()->createUrl($UrlNext));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->render('mentor', array(
            'model' => $model
        ));
    }
    
    public function actionIndustrial() {
        $Scholar = NULL;
        $Industrial = NULL;
        $model = new TgistStudentForm ();

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);

        if (!empty($Scholar->industrial_id)) {
            $criteria->condition = "id = " . $Scholar->industrial_id;
            $criteria->limit = 1;
            $Industrial = Person::model()->find($criteria);
            $model->attributes = $Industrial->attributes;
            $model->industrial_id = $Scholar->industrial_id;
            $model->status = $Scholar->status;
        }

	if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistStudentForm'])) {
                $model->attributes = Yii::app()->input->post('TgistStudentForm');
                
                $criteria->condition = "email = '" . $model->email . "'";
                $criteria->limit = 1;
                $Industrial = Person::model()->find($criteria);
                
                if (isset($_POST ['next'])) {
                    if ($model->validate()) {
                        if (!empty($Industrial)) {
                            $model->first_created = $Industrial->first_created;
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->setIsNewRecord(FALSE);
                            if ($model->update()) {
                                $criteria->condition = "email = '" . $model->email . "' and type = 'industrial' ";
                                $criteria->limit = 1;
                                $Industrial = Person::model()->find($criteria);

                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->industrial_id = $Industrial->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                    $this->redirect(Yii::app()->createUrl($UrlNext));
                                }
                            }
                        } else {
                            $model->id = NULL;
                            $model->type = 'industrial';
                            $model->first_created = date("Y-m-d H:i:s");
                            $model->last_updated = date("Y-m-d H:i:s");
                            // ----------------- GEN TOKEN ------------------------
                            $getTokenRand = rand(0, 99999);
                            $getTime = date("H:i:s");
                            $email = $model->email;
                            $model->token = RegisterForm::hashPassword($getTokenRand . $getTime . $email);
                            $model->setIsNewRecord(TRUE);
                            if ($model->save()) {
                                if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {
                                    
                                }
                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->industrial_id = $model->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $criteria->condition = "email = '" . $model->email . "' and type = 'industrial' ";
                                    $criteria->limit = 1;
                                    $Industrial = Person::model()->find($criteria);

                                    $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                    $criteria->limit = 1;
                                    $Scholar = Scholar::model()->find($criteria);
                                    $Scholar->industrial_id = $Industrial->id;
                                    $Scholar->last_updated = date("Y-m-d H:i:s");
                                    $Scholar->setIsNewRecord(FALSE);
                                    if ($Scholar->update()) {
                                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                        $this->redirect(Yii::app()->createUrl($UrlNext));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->render('industrial', array(
            'model' => $model
        ));
    }
    
    public function actionVerifyStudent() {
        $ScholarTgist = NULL;
        $model = new TgistStudentProjectForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        if (!empty($Scholar->scholar_tgist_id)) {
            $criteria->condition = "id = " . $Scholar->scholar_tgist_id;
            $criteria->limit = 1;
            $ScholarTgist = ScholarTgist::model()->find($criteria);
            $model->attributes = $ScholarTgist->attributes;
            $model->id = $ScholarTgist->id;
        }
        
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistStudentProjectForm'])) {
                $model->attributes = Yii::app()->input->post('TgistStudentProjectForm');
                
                if ($model->validate()) {
//                    $model->last_updated = date("Y-m-d H:i:s");
                    
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                        $this->redirect(Yii::app()->createUrl($UrlNext));
                    }
                }
            }
        }
        
        $this->render('verifystudent', array(
            'model' => $model
        ));
    }

    public function actionGetPersonDetail() {
        $criteria = new CDbCriteria ();
        $criteria->condition = "email = '" . $_POST ['email'] . "' and type = '" . $_POST ['type'] . "'";
        $criteria->limit = 1;
        $record = Person::model()->find($criteria);
        echo CJavaScript::jsonEncode($record);
    }
    
    public function actionGetPersonEmail() {
        $person_id = $_POST ['type']. '_id';
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = '" . $_POST [$person_id] . "' and type = '" . $_POST ['type'] . "'";
        $criteria->limit = 1;
        $record = Person::model()->find($criteria);
        echo CJavaScript::jsonEncode($record);
    }
    
    public function actionisAlreadyID() {
        $data = '0';
        $person_id = $_POST ['type']. '_id';
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = '" . $_POST [$person_id] . "' and type = '" . $_POST ['type'] . "'";
        $criteria->limit = 1;
        $record = Person::model()->find($criteria);
        if ($record !== null) {
            $data = '1';
        } else {
            $criteria->condition = "is = '" . $_POST [$person_id] . "' and type != '" . $_POST ['type'] . "'";
            $criteria->limit = 1;
            $record = Person::model()->find($criteria);
            if ($record !== null) {
                $data = InitialData::PERSON_TYPE($record->type);
            }
        }

        echo $data;
    }

    public function actionisAlreadyEmail() {
        $data = '0';
        $criteria = new CDbCriteria ();
        $criteria->condition = "email = '" . $_POST ['email'] . "' and type = '" . $_POST ['type'] . "'";
        $criteria->limit = 1;
        $record = Person::model()->find($criteria);
        if ($record !== null) {
            $data = '1';
        } else {
            $criteria->condition = "email = '" . $_POST ['email'] . "' and type != '" . $_POST ['type'] . "'";
            $criteria->limit = 1;
            $record = Person::model()->find($criteria);
            if ($record !== null) {
                $data = InitialData::PERSON_TYPE($record->type);
            }
        }

        echo $data;
    }

    public function actionGet_Func_Period($begin = NULL, $end = NULL, $mode = '') {
        $data = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
        if ($begin != NULL && $end != NULL) {
            if ($begin != NULL) {
                $begin = ConfigWeb::formatDataViewToDB($begin);
            }
            if ($end != NULL) {
                $end = ConfigWeb::formatDataViewToDB($end);
            }
            
            $data = ConfigWeb::GetCalProjectPeriodMsg($begin, $end, $mode);
        }
        echo $data;
    }

    public function actionViewHistory() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        $id = ConfigWeb::getActiveScholarId();

        $model = new ScholarHistoryForm ();

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . Yii::app()->request->getQuery('id') . " and person_id = $person_id";
        $criteria->limit = 1;
        $History = ScholarHistory::model()->find($criteria);
        $model->attributes = $History->attributes;

        if (!empty($model->begin) && !empty($model->end)) {
            $diff = ConfigWeb::GetPeriodDate($model->begin, $model->end);
            if ($diff) {
                $model->func_period = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
            } else {
                $model->func_period = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
            }
        }

        if (!empty($Project->begin))
            $model->begin = date("d/m/Y", strtotime($Project->begin));
        if (!empty($Project->end))
            $model->end = date("d/m/Y", strtotime($Project->end));

        $this->render('viewhistory', array(
            'model' => $model
        ));
    }

    public function actionEditHistory() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = Yii::app()->session['person_id'];
        $person_type = ConfigWeb::getActivePersonType();
        $id = ConfigWeb::getActiveScholarId();

        $model = new ScholarHistoryForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . Yii::app()->request->getQuery('id') . " and person_id = $person_id";
        $criteria->limit = 1;
        $History = ScholarHistory::model()->find($criteria);
        $model->attributes = $History->attributes;
        $model->id = $History->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['ScholarHistoryForm'])) {
                $model->attributes = Yii::app()->input->post('ScholarHistoryForm');
                $model->person_id = $person_id;
                $model->begin = ConfigWeb::formatDataViewToDB($model->begin);
                $model->end = ConfigWeb::formatDataViewToDB($model->end);
                $model->first_created = date("Y-m-d H:i:s");
                $model->last_updated = date("Y-m-d H:i:s");
                if ($model->validate()) {
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        $this->redirect(Yii::app()->createUrl('tgist/history'));
                    }
                }
            }
        }

        if (!empty($model->begin) && !empty($model->end)) {
            $diff = ConfigWeb::GetPeriodDate($model->begin, $model->end);
            if ($diff) {
                $model->func_period = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
            } else {
                $model->func_period = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
            }
        }

        if (!empty($Project->begin))
            $model->begin = date("d/m/Y", strtotime($Project->begin));
        if (!empty($Project->end))
            $model->end = date("d/m/Y", strtotime($Project->end));

        $this->render('addhistory', array(
            'model' => $model
        ));
    }

    public function actionDeletehistory() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = Yii::app()->session['person_id'];
        $person_type = ConfigWeb::getActivePersonType();
        $id = ConfigWeb::getActiveScholarId();

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . Yii::app()->request->getQuery('id') . " and person_id = $person_id";
        $criteria->limit = 1;
        $History = ScholarHistory::model()->find($criteria);
        if (!empty($History))
            $History->delete();
        else
            throw new CHttpException(404, 'The requested page does not exist.');
        $this->redirect(Yii::app()->createUrl('tgist/history'));
    }

    public function actionAddHistory() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = Yii::app()->session['person_id'];
        $person_type = ConfigWeb::getActivePersonType();
        $id = ConfigWeb::getActiveScholarId();

        $model = new ScholarHistoryForm ();
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['ScholarHistoryForm'])) {
                $model->attributes = Yii::app()->input->post('ScholarHistoryForm');
                $model->person_id = $person_id;
                $model->begin = ConfigWeb::formatDataViewToDB($model->begin);
                $model->end = ConfigWeb::formatDataViewToDB($model->end);
                $model->first_created = date("Y-m-d H:i:s");
                $model->last_updated = date("Y-m-d H:i:s");
                if ($model->validate()) {
                    if ($model->save()) {
                        $this->redirect(Yii::app()->createUrl('tgist/history'));
                    }
                }
            }
        }

        if (!empty($model->begin) && !empty($model->end)) {
            $diff = ConfigWeb::GetPeriodDate($model->begin, $model->end);
            if ($diff) {
                $model->func_period = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
            } else {
                $model->func_period = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
            }
        }

        if (!empty($Project->begin))
            $model->begin = date("d/m/Y", strtotime($Project->begin));
        if (!empty($Project->end))
            $model->end = date("d/m/Y", strtotime($Project->end));

        $this->render('addhistory', array(
            'model' => $model
        ));
    }

    public function actionHistory() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        $id = ConfigWeb::getActiveScholarId();

        $model = new TgistHistoryForm ();
        $Tgist = new ScholarTgist ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id";
        $criteria->limit = 1;
        $scholar = Scholar::model()->find($criteria);

        $criteria->condition = "id = " . $scholar->scholarTgist->id;
        $criteria->limit = 1;
        $Tgist = ScholarTgist::model()->find($criteria);
        $model->attributes = $Tgist->attributes;
        $model->id = $Tgist->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistHistoryForm'])) {
                $model->attributes = Yii::app()->input->post('TgistHistoryForm');
                $Tgist->is_history = $model->is_history;
                if ($model->validate()) {
                    $model->setIsNewRecord(FALSE);
                    if ($Tgist->update()) {
                        $scholar->first_created = $scholar->first_created;
                        $scholar->last_updated = date("Y-m-d H:i:s");
                        $scholar->setIsNewRecord(FALSE);
                        if ($scholar->update()) {
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    }
                }
            }
        }

        $sql_count = "SELECT COUNT(s.id) " . " FROM scholar_history s LEFT JOIN nstdamas_educationlevel e ON s.educationlevel_id=e.id " . " WHERE s.person_id=$person_id " . " ORDER BY s.begin ASC, s.end ASC";
        $count = Yii::app()->db->createCommand($sql_count)->queryScalar();

        $sql = "SELECT s.id,
                e.edl_name AS education,
                s.name AS name,
                s.begin AS begin
               FROM scholar_history s LEFT JOIN nstdamas_educationlevel e ON s.educationlevel_id=e.id
               WHERE s.person_id=$person_id 
               ORDER BY s.begin ASC, s.end ASC";

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount' => $count,
            'pagination' => array(
                'pageSize' => 1000
            )
        ));

//		if ($count > 0) {
//                    $model->is_history = '1';
//		}

        $this->render('history', array(
            'model' => $model,
            'dataProvider' => $dataProvider
        ));
    }

    public function actionWorking() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        $id = ConfigWeb::getActiveScholarId();

        $model = new TgistWorkingForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id";
        $criteria->limit = 1;
        $scholar = Scholar::model()->find($criteria);

        $criteria->condition = "id = " . $scholar->scholarTgist->id;
        $criteria->limit = 1;
        $ScholarTgist = ScholarTgist::model()->find($criteria);
        $model->attributes = $ScholarTgist->attributes;
        $model->id = $ScholarTgist->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistWorkingForm'])) {
                $model->attributes = Yii::app()->input->post('TgistWorkingForm');
                if ($model->validate()) {
                    $ScholarTgist->is_work = $model->is_work;
                    $ScholarTgist->work_company = $model->work_company;
                    $ScholarTgist->work_position = $model->work_position;
                    $ScholarTgist->work_location = $model->work_location;
                    $ScholarTgist->work_phone = $model->work_phone;
                    $ScholarTgist->work_fax = $model->work_fax;
                    $ScholarTgist->is_workwithproject = $model->is_workwithproject;
                    $ScholarTgist->workwithproject_text1 = $model->workwithproject_text1;
                    $ScholarTgist->workwithproject_text2 = $model->workwithproject_text2;
                    $ScholarTgist->workwithproject_text3 = $model->workwithproject_text3;
                    $ScholarTgist->is_takescholar = $model->is_takescholar;
                    $model->setIsNewRecord(FALSE);
                    if ($ScholarTgist->update()) {
                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                        $this->redirect(Yii::app()->createUrl($UrlNext));
                    }
                }
            }
        }

        $this->render('working', array(
            'model' => $model
        ));
    }

    public function actionExperience() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        $id = ConfigWeb::getActiveScholarId();

        $model = new TgistExperienceForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id";
        $criteria->limit = 1;
        $scholar = Scholar::model()->find($criteria);

        $criteria->condition = "id = " . $scholar->scholarTgist->id;
        $criteria->limit = 1;
        $ScholarTgist = ScholarTgist::model()->find($criteria);
        $model->attributes = $ScholarTgist->attributes;
        $model->id = $ScholarTgist->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistExperienceForm'])) {
                $model->attributes = Yii::app()->input->post('TgistExperienceForm');
                if ($model->validate()) {
                    $ScholarTgist->is_experience = $model->is_experience;
                    $ScholarTgist->portfolio_thesis = $model->portfolio_thesis;
                    $ScholarTgist->portfolio_journal_international = $model->portfolio_journal_international;
                    $ScholarTgist->portfolio_journal_incountry = $model->portfolio_journal_incountry;
                    $ScholarTgist->portfolio_patent = $model->portfolio_patent;
                    $ScholarTgist->portfolio_prototype = $model->portfolio_prototype;
                    $ScholarTgist->portfolio_conference_international = $model->portfolio_conference_international;
                    $ScholarTgist->portfolio_conference_incountry = $model->portfolio_conference_incountry;
                    $ScholarTgist->portfolio_award = $model->portfolio_award;
                    $ScholarTgist->portfolio_other = $model->portfolio_other;
                    $ScholarTgist->portfolio_journal_international_amount = $model->portfolio_journal_international_amount;
                    $ScholarTgist->portfolio_journal_incountry_amount = $model->portfolio_journal_incountry_amount;
                    $ScholarTgist->portfolio_patent_amount = $model->portfolio_patent_amount;
                    $ScholarTgist->portfolio_prototype_amount = $model->portfolio_prototype_amount;
                    $ScholarTgist->portfolio_conference_international_amount = $model->portfolio_conference_international_amount;
                    $ScholarTgist->portfolio_conference_incountry_amount = $model->portfolio_conference_incountry_amount;
                    $ScholarTgist->portfolio_award_amount = $model->portfolio_award_amount;
                    $ScholarTgist->portfolio_other_text = $model->portfolio_other_text;
                    $ScholarTgist->portfolio_other_amount = $model->portfolio_other_amount;
                    $ScholarTgist->portfolio_journal_international_desc = $model->portfolio_journal_international_desc;
                    $ScholarTgist->portfolio_journal_incountry_desc = $model->portfolio_journal_incountry_desc;
                    $ScholarTgist->portfolio_patent_desc = $model->portfolio_patent_desc;
                    $ScholarTgist->portfolio_prototype_desc = $model->portfolio_prototype_desc;
                    $ScholarTgist->portfolio_conference_international_desc = $model->portfolio_conference_international_desc;
                    $ScholarTgist->portfolio_conference_incountry_desc = $model->portfolio_conference_incountry_desc;
                    $ScholarTgist->portfolio_award_desc = $model->portfolio_award_desc;
                    $ScholarTgist->portfolio_other_desc = $model->portfolio_other_desc;

                    $model->setIsNewRecord(FALSE);
                    if ($ScholarTgist->update()) {
                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                        $this->redirect(Yii::app()->createUrl($UrlNext));
                    }
                }
            }
        }

        $this->render('experience', array(
            'model' => $model
        ));
    }

    public function actiongetData() {
        $person_id = Yii::app()->session['person_id'];
        $id = Yii::app()->request->getQuery('prj_id');
        $model = new TgistPrimaryProjectForm('add');
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id and creater_id = $person_id";
        $criteria->limit = 1;
        $project = Project::model()->find($criteria);
        if ($project !== NULL) {
            $model->attributes = $project->attributes;
            $diff = ConfigWeb::GetPeriodDate($model->begin, $model->end);
            if ($diff) {
                $model->func_period = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
            } else {
                $model->func_period = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
            }
            if (!empty($model->begin))
                $model->begin = date("d/m/Y", strtotime($model->begin));
            if (!empty($model->end))
                $model->end = date("d/m/Y", strtotime($model->end));
        }
        echo json_encode($model);
    }

    public function actionPrimaryProject() {
        $mode = Yii::app()->request->getQuery('mode');
        $prj_id = Yii::app()->request->getQuery('prj_id');
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        $model = new TgistPrimaryProjectForm($mode);
        $modelForm = new TgistPrimaryProjectForm ();
        $Project = NULL;
        $error = NULL;
        $Scholar = NULL;
        $criteria = new CDbCriteria ();

        if ($mode != 'add') {
            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
        }
        if (!empty($Scholar->scholarTgist->project_id)) {
            $criteria->condition = "id = " . $Scholar->scholarTgist->project_id;
            $criteria->limit = 1;
            $Project = Project::model()->find($criteria);
            $model->attributes = $Project->attributes;
            $model->project_id = $Scholar->scholarTgist->project_id;
        }

        if (!empty($prj_id) && $mode == 'edit') {
            $criteria->condition = "id = $prj_id and creater_id = $person_id";
            $criteria->limit = 1;
            $Project = Project::model()->find($criteria);
            if ($Project !== NULL) {
                $model->attributes = $Project->attributes;
            }
        }
        
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistPrimaryProjectForm'])) {
                $model->attributes = Yii::app()->input->post('TgistPrimaryProjectForm');
                $modelForm->attributes = Yii::app()->input->post('TgistPrimaryProjectForm');
                if (isset($_POST ['add'])) {
                    $model = new TgistPrimaryProjectForm('add');
                    $model->attributes = Yii::app()->input->post('TgistPrimaryProjectForm');
                    $model->creater_id = $person_id;
                    $model->id = NULL;

                    if ($model->funding == $model->CHECKBOX_FUNDING_SOURCE) {
                        $model->funding_code = NULL;
                        $model->funding_code_name = NULL;
                        $model->funding_etc = NULL;
                    } else if ($model->funding == $model->CHECKBOX_FUNDING_NSTDA) {
                        $model->funding_name = NULL;
                        $model->funding_etc = NULL;
                    } else if ($model->funding == $model->CHECKBOX_FUNDING_OTHER) {
                        $model->funding_name = NULL;
                        $model->funding_code = NULL;
                        $model->funding_code_name = NULL;
                    } else if ($model->funding == $model->CHECKBOX_FUNDING_NONE) {
                        $model->funding_code = NULL;
                        $model->funding_code_name = NULL;
                        $model->funding_name = NULL;
                        $model->funding_etc = NULL;
                    }
                    if (!is_numeric($model->budget) || empty($model->budget)) {
                        $model->budget = 0;
                    }

                    $model->begin = ConfigWeb::formatDataViewToDB($model->begin);
                    $model->end = ConfigWeb::formatDataViewToDB($model->end);
                    $model->first_created = date("Y-m-d H:i:s");
                    $model->last_updated = date("Y-m-d H:i:s");
                    if ($model->validate()) {
                        if ($model->save()) {
                            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                            $criteria->limit = 1;
                            $Scholar = Scholar::model()->find($criteria);
                            $Scholar->scholarTgist->project_id = $model->id;
                            // $Scholar->scholarTgist->last_updated = date("Y-m-d H:i:s");
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if ($Scholar->scholarTgist->update()) {
                                $this->redirect(Yii::app()->createUrl('tgist/primaryproject'));
                            }
                        }
                    }
                } else if (isset($_POST ['edit'])) {
                    if ($model->funding == $model->CHECKBOX_FUNDING_SOURCE) {
                        $model->funding_code = NULL;
                        $model->funding_code_name = NULL;
                        $model->funding_etc = NULL;
                    } else if ($model->funding == $model->CHECKBOX_FUNDING_NSTDA) {
                        $model->funding_name = NULL;
                        $model->funding_etc = NULL;
                    } else if ($model->funding == $model->CHECKBOX_FUNDING_OTHER) {
                        $model->funding_name = NULL;
                        $model->funding_code = NULL;
                        $model->funding_code_name = NULL;
                    } else if ($model->funding == $model->CHECKBOX_FUNDING_NONE) {
                        $model->funding_code = NULL;
                        $model->funding_code_name = NULL;
                        $model->funding_name = NULL;
                        $model->funding_etc = NULL;
                    }
                    if (!is_numeric($model->budget) || empty($model->budget)) {
                        $model->budget = 0;
                    }
                    $model->creater_id = $person_id;
                    $model->begin = ConfigWeb::formatDataViewToDB($model->begin);
                    $model->end = ConfigWeb::formatDataViewToDB($model->end);

                    $model->first_created = $Project->first_created;
                    $model->last_updated = date("Y-m-d H:i:s");
                    $model->setIsNewRecord(FALSE);
                    if ($model->validate()) {
                        if ($model->update()) {
                            $Scholar->scholarTgist->project_id = $model->id;
                            // $Scholar->scholarTgist->last_updated = date("Y-m-d H:i:s");
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if ($Scholar->scholarTgist->update()) {
                                $this->redirect(Yii::app()->createUrl('tgist/primaryproject'));
                            }
                        }
                    } else {
                        $error = TRUE;
                        $model->attributes = $modelForm->attributes;
                    }
                } else if (isset($_POST ['next'])) {
                    $model = new TgistPrimaryProjectForm('next');
                    $model->attributes = Yii::app()->input->post('TgistPrimaryProjectForm');
                    $model->begin = (!empty($Project->begin)) ? $Project->begin : $model->begin;
                    $model->end = (!empty($Project->end)) ? $Project->end : $model->end;
                    $model->funding = (!empty($Project->funding)) ? $Project->funding : $model->funding;
                    if (!empty($model->project_id)) {
                        $Scholar->scholarTgist->project_id = $model->project_id;
                        // $Scholar->scholarTgist->last_updated = date("Y-m-d H:i:s");
                        $Scholar->scholarTgist->setIsNewRecord(FALSE);
                        if ($Scholar->scholarTgist->update()) {
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->controller->id . "/" . Yii::app()->controller->action->id, TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    } else {
                        $error = TRUE;
                        $model->attributes = $modelForm->attributes;
                        $model->validate();
                    }
                }
            }
        }

        if (!empty($model->begin) && !empty($model->end)) {
            $model->func_period = ConfigWeb::GetCalProjectPeriodMsg(
                    ConfigWeb::formatDataViewToDB($model->begin),
                    ConfigWeb::formatDataViewToDB($model->end), 'primary');
            /*
            $begin = str_replace('-', '', ConfigWeb::formatDataViewToDB($model->begin));
            $end = str_replace('-', '', ConfigWeb::formatDataViewToDB($model->end)); 
            $begin_from = Yii::app()->params['tgist_project_begin_min'];
            $begin_to = Yii::app()->params['tgist_project_begin_max'];
            $end_last = Yii::app()->params['tgist_project_sub_begin_min'];
            $end_last = date('Y-m-d', strtotime($end_last . ' +6 month -1 day'));
            
            $from = str_replace('-', '', $begin_from);
            $to = str_replace('-', '', $begin_to);
            $last = str_replace('-', '', $end_last);
            
            $isOk = false;
            if($begin >= $from && $begin<= $to && $end >= $last){
                $isOk = true;
            }
            
            if($isOk){
                $diff = ConfigWeb::GetPeriodDate($model->begin, $model->end);
                if ($diff) {
                    if($diff->y >= 1 || $diff->m >= 6){
                        $model->func_period = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
                    }else {
                        $model->func_period = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (ระยะเวลาต้องไม่ต่ำกว่า 6 เดือน)';
                    }
                } else {
                    $model->func_period = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
                }
            }else{
                if($begin < $from || $begin > $to){
                    $model->func_period =  'วันเริ่มโครงการหลักต้องไม่เกินวันที่ '
                                        . ''.date("d/m/Y", strtotime($begin_to));
                }else if($end < $last){
                    $model->func_period =  'วันสิ้นสุดโครงการหลักต้องมากกว่าวันที่ '.date("d/m/Y", strtotime($end_last))
                                        . ' เป็นต้นไป ';
                }
            }*/
//            $model->end = date('Y-m-d', strtotime($model->end . ' -1 day'));
            $model->begin = date("d/m/Y", strtotime(ConfigWeb::formatDataViewToDB($model->begin)));
            $model->end = date("d/m/Y", strtotime(ConfigWeb::formatDataViewToDB($model->end)));
        }
        
        if (!Yii::app()->request->isPostRequest) {
            if (!empty($Project->begin))
                $model->begin = date("d/m/Y", strtotime($Project->begin));
            if (!empty($Project->end))
                $model->end = date("d/m/Y", strtotime($Project->end));
            if (!empty($Project->funding))
                $model->funding = $Project->funding;
        }
        
        if($person_type == 'mentor'){
            $model->funding = $model->CHECKBOX_FUNDING_NSTDA;
        }
        $this->render('primaryproject', array(
            'model' => $model,
            'mode' => $mode
        ));
    }

    public function actionStudentProject() {
        $ScholarTgist = NULL;
        $model = new TgistStudentProjectForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        if (!empty($Scholar->scholar_tgist_id)) {
            $criteria->condition = "id = " . $Scholar->scholar_tgist_id;
            $criteria->limit = 1;
            $ScholarTgist = ScholarTgist::model()->find($criteria);
            $model->attributes = $ScholarTgist->attributes;
            $model->id = $ScholarTgist->id;
        }
        
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistStudentProjectForm'])) {
                $model->attributes = Yii::app()->input->post('TgistStudentProjectForm');
                
                if ($model->validate()) {
//                    $model->last_updated = date("Y-m-d H:i:s");
                    
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                        $this->redirect(Yii::app()->createUrl($UrlNext));
                    }
                }
            }
        }
        
        $this->render('studentproject', array(
            'model' => $model
        ));
    }
    

    public function actionComment() {
        $model = new TgistCommentForm ();
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();

        $criteria = new CDbCriteria ();
        $criteria->condition = "scholar_id = " . ConfigWeb::getActiveScholarId() . " and person_id = " . $person_id;
        $criteria->limit = 1;
        $Comment = Comment::model()->find($criteria);
        if (!empty($Comment->id)) {
            $model->attributes = $Comment->attributes;
            $model->id = $Comment->id;

            $model->steppage = $Comment->steppage;
            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            $model->scholar_status = $Scholar->status;
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['TgistCommentForm'])) {
                $model->attributes = Yii::app()->input->post('TgistCommentForm');
                if (isset($_POST ['savedraft']) || isset($_POST ['savesend'])) {
                    $steppage = WorkflowData::getMaxPage();
                    $wf = WorkflowData::getWorkflow();
                    $min = count($wf) - 2;
                    $max = count($wf) - 1;
                    $ac_min = (string)(pow(2,$min) - 1);
                    $ac_max = (string)(pow(2,$max) - 1);
                    
                    if($steppage == $ac_min || $steppage == $ac_max) {
                        if (isset($_POST ['savedraft'])) {
                            $model->status = 'draft';
                        } elseif (isset($_POST ['savesend'])) {
                            $model->status = 'sent';
                        }
                        if ($model->validate()) {
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->setIsNewRecord(FALSE);
                            if ($model->update()) {
                                if (isset($_POST ['savesend'])) {
                                    $sql = "SELECT COUNT(*) AS total,
                                            IFNULL(SUM(CASE WHEN status='draft' THEN 1 ELSE 0 END),0) AS draft,
                                            IFNULL(SUM(CASE WHEN status='sent' THEN 1 ELSE 0 END),0) AS sent
                                            FROM comment
                                            where scholar_id=" . ConfigWeb::getActiveScholarId()
                                            . " and person_id=" . $Scholar->professor_id
                                            . " or person_id=" . $Scholar->mentor_id
                                            . " or person_id=" . $Scholar->industrial_id;
                                    $ReCheckConfirm = Yii::app()->db->createCommand($sql)->queryAll(); 

                                    if ($ReCheckConfirm [0] ['total'] == $ReCheckConfirm [0] ['sent']) {
                                        $criteria = new CDbCriteria ();
                                        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                        $criteria->limit = 1;
                                        $Scholar = Scholar::model()->find($criteria);
                                        $Scholar->status = 'confirm';
                                        $Scholar->first_created = $Scholar->first_created;
                                        $Scholar->last_updated = date("Y-m-d H:i:s");
                                        $Scholar->setIsNewRecord(FALSE);
                                        $Scholar->update();

                                        $criteria->condition = "id = " . $Scholar->student_id;
                                        $criteria->limit = 1;
                                        $PersonStudent = Person::model()->find($criteria);
                                        if ($PersonStudent) {
                                            $emailStudent = $PersonStudent->email;
                                            // ระบบส่งเมล์แจ้ง นักเรียน/นักศึกษา ด้วย *************************
                                            $SendMail = new SendMail ();
                                            $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                                            $subject = Yii::app()->params ['EmailTemplateRecommendationSubject'];
                                            $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                            $subject = str_replace("##TYPE##", "นักเรียน/นักศึกษา", $subject);
                                            $SendMail->subject = $subject;
                                            $message = Yii::app()->params ['EmailTemplateRecommendationBody'];
                                            $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                                            $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                            $PERSONNAME = $Scholar->student->fname . "  " . $Scholar->student->lname;
                                            $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                            $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                            $verifyUrl = Yii::app()->createUrl('site/login', array(
                                                'scholartype' => ConfigWeb::getActiveScholarType()
                                            ));
                                            $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                            $message = str_replace("##URL##", $URL, $message);
                                            $SendMail->body = $message;
                                            $SendMail->to = $Scholar->student->email;
                                            $SendMail->from = "noreply@nstda.or.th";
                                            $SendMail->send();
                                        }

                                    }
                                }
                                $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                $this->redirect(Yii::app()->createUrl($UrlNext));

                            }
                        }
                    } else {
                        $error = 'error';
                        Yii::app()->user->setFlash('error','คุณยังไม่ได้ทำรายการครบทุกขั้นตอน');
                    }
                } elseif (isset($_POST ['save'])) {
                    $model->last_updated = date("Y-m-d H:i:s");
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                        $criteria->limit = 1;
                        $Scholar = Scholar::model()->find($criteria);
                        $Scholar->last_updated = date("Y-m-d H:i:s");
                        $Scholar->setIsNewRecord(FALSE);
                        $Scholar->update();
                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                        $this->redirect(Yii::app()->createUrl($UrlNext));
                    }
                }
            }
        }
        $this->render('comment', array(
            'model' => $model
        ));
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
                
                $path = Yii::app()->basePath . Yii::app()->params['pathUploads'] . $Scholar->scholarTgist[$typedw];
                if (file_exists($path)) {
                    return Yii::app()->getRequest()->sendFile($Scholar->scholarTgist[$typedw], @file_get_contents($path));
                } else {
                    throw new CHttpException(404, 'The specified post cannot be found.');
                }
            }
        }
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
                    
                    $model->birthday = ConfigWeb::formatDataViewToDB($model->birthday);
                    if(ConfigWeb::getActivePersonType() == "student"){
                        $model->id_card_created = ConfigWeb::formatDataViewToDB($model->id_card_created);
                        $model->id_card_expired = ConfigWeb::formatDataViewToDB($model->id_card_expired);
                    }
                    if ($model->update()) {
                        if(ConfigWeb::getActivePersonType() == "student"){
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }else{
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    }
                }
            }
        }
        
        if(ConfigWeb::getActivePersonType() == "student"){
            if (!empty($model->id_card_created))
                $model->id_card_created = date("d/m/Y", strtotime(ConfigWeb::formatDataViewToDB($model->id_card_created)));
            if (!empty($model->id_card_expired))
                $model->id_card_expired = date("d/m/Y", strtotime(ConfigWeb::formatDataViewToDB($model->id_card_expired)));
        }
        if ($model->birthday !== NULL) {
            $datetime1 = new DateTime(ConfigWeb::formatDataViewToDB($model->birthday));
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
                $model->birthday = date("d/m/Y", strtotime(ConfigWeb::formatDataViewToDB($model->birthday)));
        }

        $this->render('miniprofile', array('model' => $model));
    }
    
    
    public function actionMiniAttachment() {
        $person_id = ConfigWeb::getActivePersonId();
        $person_type = ConfigWeb::getActivePersonType();
        
        $model = new TgistUploadAttachmentForm();
        $criteria = new CDbCriteria;
        $criteria->condition = "id = $person_id";
        $criteria->limit = 1;
        $user = Person::model()->find($criteria);
        
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId() . " and " . $person_type . "_id = " . $person_id;
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        
        $model->attributes = $Scholar->scholarTgist->attributes;
        $model->id = $Scholar->scholarTgist->id;
        
        if($person_type == 'student'){
            $model->industrial_certificate_path = $Scholar->scholarTgist->industrial_certificate_path;
            $model->student_transcript_path = $Scholar->scholarTgist->student_transcript_path;
            $model->student_portfolio_path = $Scholar->scholarTgist->student_portfolio_path;
            $model->student_attachment_other_path = $Scholar->scholarTgist->student_attachment_other_path;
            $model->student_attachment_other2_path = $Scholar->scholarTgist->student_attachment_other2_path;
            $model->student_attachment_other3_path = $Scholar->scholarTgist->student_attachment_other3_path;
        }else if ($person_type == 'professor'){
            $model->cv_path = $user->cv_path;
            $model->professor_attachment_other_path = $Scholar->scholarTgist->professor_attachment_other_path;
            $model->professor_attachment_other2_path = $Scholar->scholarTgist->professor_attachment_other2_path;
            $model->professor_attachment_other3_path = $Scholar->scholarTgist->professor_attachment_other3_path;
//                $model->professor_proposal_path = $Scholar->scholarTgist->professor_proposal_path;
        }else if($person_type == 'mentor'){
            $model->cv_path = $user->cv_path;
            $model->mentor_attachment_other_path = $Scholar->scholarTgist->mentor_attachment_other_path;
            $model->mentor_attachment_other2_path = $Scholar->scholarTgist->mentor_attachment_other2_path;
            $model->mentor_attachment_other3_path = $Scholar->scholarTgist->mentor_attachment_other3_path;
        }else if($person_type == 'industrial'){
            $model->cv_path = $user->cv_path;
            $model->industrial_attachment_other_path = $Scholar->scholarTgist->industrial_attachment_other_path;
            $model->industrial_attachment_other2_path = $Scholar->scholarTgist->industrial_attachment_other2_path;
            $model->industrial_attachment_other3_path = $Scholar->scholarTgist->industrial_attachment_other3_path;
        }
        
        $criteria->condition = ""
                . "id = " . ConfigWeb::getActiveScholarId()
                . " and  " . strtolower($person_type) . '_id = ' .$person_id;
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        if(!empty($Scholar))
            $model->status = $Scholar->status;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST['TgistUploadAttachmentForm'])) {
                $model->attributes = Yii::app()->input->post('TgistUploadAttachmentForm');
                if (isset($_POST['upload'])) {
                    if($person_type == 'student'){
                        $FileUpload = CUploadedFile::getInstance($model, 'student_transcript');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_transcript.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->student_transcript_path = $fileName;
                            $model->student_transcript_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('student_transcript')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_portfolio');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_portfolio.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->student_portfolio_path = $fileName;
                            $model->student_portfolio_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('student_portfolio')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_certificate');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_certificate.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->industrial_certificate_path = $fileName;
                            $model->industrial_certificate_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('industrial_certificate')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads'] . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_attachment_other');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_attachment_other.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->student_attachment_other_path = $fileName;
                            $model->student_attachment_other_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('student_attachment_other')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_attachment_other2');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_attachment_other2.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->student_attachment_other2_path = $fileName;
                            $model->student_attachment_other2_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('student_attachment_other2')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'student_attachment_other3');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_student_attachment_other3.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->student_attachment_other3_path = $fileName;
                            $model->student_attachment_other3_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('student_attachment_other3')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        
                    }else if($person_type == 'professor'){
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
                        $FileUpload = CUploadedFile::getInstance($model, 'professor_attachment_other');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_professor_attachment_other.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->professor_attachment_other_path = $fileName;
                            $model->professor_attachment_other_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('professor_attachment_other')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'professor_attachment_other2');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_professor_attachment_other2.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->professor_attachment_other2_path = $fileName;
                            $model->professor_attachment_other2_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('professor_attachment_other2')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'professor_attachment_other3');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_professor_attachment_other3.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->professor_attachment_other3_path = $fileName;
                            $model->professor_attachment_other3_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('professor_attachment_other3')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                    }
                    else if($person_type == 'mentor'){
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
                        $FileUpload = CUploadedFile::getInstance($model, 'mentor_attachment_other');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_mentor_attachment_other.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->mentor_attachment_other_path = $fileName;
                            $model->mentor_attachment_other_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('mentor_attachment_other')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'mentor_attachment_other2');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_mentor_attachment_other2.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->mentor_attachment_other2_path = $fileName;
                            $model->mentor_attachment_other2_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('mentor_attachment_other2')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'mentor_attachment_other3');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_mentor_attachment_other3.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->mentor_attachment_other3_path = $fileName;
                            $model->mentor_attachment_other3_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('mentor_attachment_other3')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                    }
                    else if($person_type == 'industrial'){
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
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_attachment_other');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_attachment_other.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->industrial_attachment_other_path = $fileName;
                            $model->industrial_attachment_other_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('industrial_attachment_other')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_attachment_other2');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_attachment_other2.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->industrial_attachment_other2_path = $fileName;
                            $model->industrial_attachment_other2_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('industrial_attachment_other2')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                        $FileUpload = CUploadedFile::getInstance($model, 'industrial_attachment_other3');
                        if (!empty($FileUpload)) {
                            $ext = pathinfo($FileUpload, PATHINFO_EXTENSION);
                            $fileName = $user->id . '\\' .ConfigWeb::getActiveScholarId()."_industrial_attachment_other3.$ext";
                            $Scholar->scholarTgist->id = $Scholar->scholarTgist->id;
                            $Scholar->scholarTgist->industrial_attachment_other3_path = $fileName;
                            $model->industrial_attachment_other3_path = $fileName;
                            $Scholar->scholarTgist->setIsNewRecord(FALSE);
                            if($model->validate('industrial_attachment_other3')){
                                if ($Scholar->scholarTgist->update()) {
                                    $FileUpload->saveAs(Yii::app()->basePath . Yii::app()->params['pathUploads']  . $fileName);
                                }
                            }
                        }
                    }
                    if(!empty($errores)){
                        foreach ($errores as $key=>$value) {
                            Yii::app()->user->setFlash('error_'.$key, $model->attributeLabels()[$key] ." - ". $value[0]);
                        }
                    }
                }
                
                $error = '';
                if (isset($_POST['next']) || isset($_POST['savesend']) || isset($_POST['draft']) || isset($_POST['save']) || isset($_POST['saveapply'])) {
                    if ($person_type == 'professor') {
                        if (empty($user->cv_path)){
                            $error = 'error';
                            Yii::app()->user->setFlash('error1', $model->attributeLabels()['cv_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
//                        if (empty($Scholar->scholarTgist->professor_attachment_other_path)){
//                            $error = 'error';
//                            Yii::app()->user->setFlash('error2', $model->attributeLabels()['professor_attachment_other_path'] . ' - กรุณาอัพโหลด / Please upload file');
//                        }
                    }
                    
                    else if ($person_type == 'mentor') {
                        if (empty($user->cv_path)){
                            $error = 'error';
                            Yii::app()->user->setFlash('error1', $model->attributeLabels()['cv_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                    }
                    else if ($person_type == 'industrial') {
                        if (empty($user->cv_path)){
                            $error = 'error';
                            Yii::app()->user->setFlash('error1', $model->attributeLabels()['cv_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                    }
                    else if ($person_type == 'student') {
//                        if (empty($user->copy_id_card_path)) {
//                            $error = 'error';
//                            Yii::app()->user->setFlash('error3', $model->attributeLabels()['copy_id_card_path'] . ' - กรุณาอัพโหลด / Please upload file');
//                        }
                        if (empty($Scholar->scholarTgist->student_transcript_path)) {
                            $error = 'error';
                            Yii::app()->user->setFlash('error2', $model->attributeLabels()['student_transcript_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                        if (empty($Scholar->scholarTgist->industrial_certificate_path)){
                            $error = 'error';
                            Yii::app()->user->setFlash('error2', $model->attributeLabels()['industrial_certificate_path'] . ' - กรุณาอัพโหลด / Please upload file');
                        }
                    }
                    
                    if (isset($_POST['savesend']) && $error == '') {
                        $steppage = WorkflowData::getMaxPage();
                        $wf = WorkflowData::getWorkflow();
                        $min = count($wf) - 2;
                        $max = count($wf) - 1;
                        $ac_min = (string)(pow(2,$min) - 1);
                        $ac_max = (string)(pow(2,$max) - 1);
                        if($steppage == $ac_min || $steppage == $ac_max) {
                            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                            $criteria->limit = 1;
                            $Scholar = Scholar::model()->find($criteria);
                            $Scholar->status = 'pending_recommendations';
                            $Scholar->last_updated = date("Y-m-d H:i:s");
                            $Scholar->setIsNewRecord(FALSE);
                            $Scholar->update();
                            
                            $criteria->condition = "scholar_id = " . ConfigWeb::getActiveScholarId() . " and person_id = " . $Scholar->professor_id;
                            $criteria->limit = 1;
                            $ScholarProfessor = Comment::model()->find($criteria);
                            if (empty($ScholarProfessor)) {
                                $commentForProfessor = new Comment ();
                                $commentForProfessor->scholar_id = ConfigWeb::getActiveScholarId();
                                $commentForProfessor->person_id = $Scholar->professor_id;
                                $commentForProfessor->status = 'draft';
                                $commentForProfessor->first_created = date("Y-m-d H:i:s");
                                $commentForProfessor->last_updated = date("Y-m-d H:i:s");
                                $commentForProfessor->save();
                                $emailProfessor = $commentForProfessor->person->email;
                                // ระบบส่งเมล์แจ้ง อาจารย์ที่ปรึกษา ด้วย *************************
                                $SendMail = new SendMail ();
                                $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                                $subject = Yii::app()->params ['EmailTemplateAlertConfirmSubject'];
                                $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                $subject = str_replace("##TYPE##", "อาจารย์ที่ปรึกษา", $subject);
                                $SendMail->subject = $subject;
                                $message = Yii::app()->params ['EmailTemplateAlertConfirmBody'];
                                $message = str_replace("##TYPE##", "อาจารย์ที่ปรึกษา", $message);
                                $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                                $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                $PERSONNAME = $Scholar->professor->fname . "  " . $Scholar->professor->lname;
                                $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                    'token' => $Scholar->professor->token,
                                    'scholartype' => ConfigWeb::getActiveScholarType()
                                ));
                                $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                $message = str_replace("##URL##", $URL, $message);
                                $SendMail->body = $message;
                                $SendMail->to = $Scholar->professor->email;
                                $SendMail->from = "noreply@nstda.or.th";
                                $SendMail->send();
                            }

                            $criteria->condition = "scholar_id = " . ConfigWeb::getActiveScholarId() . " and person_id = " . $Scholar->mentor_id;
                            $criteria->limit = 1;
                            $ScholarMentor = Comment::model()->find($criteria);
                            if (empty($ScholarMentor)) {
                                $commentForMentor = new Comment ();
                                $commentForMentor->scholar_id = ConfigWeb::getActiveScholarId();
                                $commentForMentor->person_id = $Scholar->mentor_id;
                                $commentForMentor->status = 'draft';
                                $commentForMentor->first_created = date("Y-m-d H:i:s");
                                $commentForMentor->last_updated = date("Y-m-d H:i:s");
                                $commentForMentor->save();
                                $emailMentor = $commentForMentor->person->email;
                                // ระบบส่งเมล์แจ้ง นักวิจัย สวทช. ด้วย *************************
                                $SendMail = new SendMail ();
                                $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                                $subject = Yii::app()->params ['EmailTemplateAlertConfirmSubject'];
                                $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                $subject = str_replace("##TYPE##", "นักวิจัย สวทช.", $subject);
                                $SendMail->subject = $subject;
                                $message = Yii::app()->params ['EmailTemplateAlertConfirmBody'];
                                $message = str_replace("##TYPE##", "นักวิจัย สวทช.", $message);
                                $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                                $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                $PERSONNAME = $Scholar->mentor->fname . "  " . $Scholar->mentor->lname;
                                $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                    'token' => $Scholar->mentor->token,
                                    'scholartype' => ConfigWeb::getActiveScholarType()
                                ));
                                $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                $message = str_replace("##URL##", $URL, $message);
                                $SendMail->body = $message;
                                $SendMail->to = $Scholar->mentor->email;
                                $SendMail->from = "noreply@nstda.or.th";
                                $SendMail->send();
                            }

                            $criteria->condition = "scholar_id = " . ConfigWeb::getActiveScholarId() . " and person_id = " . $Scholar->industrial_id;
                            $criteria->limit = 1;
                            $ScholarIndustrial = Comment::model()->find($criteria);
                            if (empty($ScholarIndustrial)) {
                                $commentForIndustrial = new Comment ();
                                $commentForIndustrial->scholar_id = ConfigWeb::getActiveScholarId();
                                $commentForIndustrial->person_id = $Scholar->industrial_id;
                                $commentForIndustrial->status = 'draft';
                                $commentForIndustrial->first_created = date("Y-m-d H:i:s");
                                $commentForIndustrial->last_updated = date("Y-m-d H:i:s");
                                $commentForIndustrial->save();
                                $emailIndustrial = $commentForIndustrial->person->email;
                                // ระบบส่งเมล์แจ้ง บริษัท/ภาคอุตสาหกรรม ด้วย *************************
                                $SendMail = new SendMail ();
                                $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                                $subject = Yii::app()->params ['EmailTemplateAlertConfirmSubject'];
                                $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                $subject = str_replace("##TYPE##", "บริษัท/ภาคอุตสาหกรรม", $subject);
                                $SendMail->subject = $subject;
                                $message = Yii::app()->params ['EmailTemplateAlertConfirmBody'];
                                $message = str_replace("##TYPE##", "บริษัท/ภาคอุตสาหกรรม", $message);
                                $message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
                                $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                $PERSONNAME = $Scholar->industrial->fname . "  " . $Scholar->industrial->lname;
                                $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                $message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
                                $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                    'token' => $Scholar->industrial->token,
                                    'scholartype' => ConfigWeb::getActiveScholarType()
                                ));
                                $URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
                                $message = str_replace("##URL##", $URL, $message);
                                $SendMail->body = $message;
                                $SendMail->to = $Scholar->industrial->email;
                                $SendMail->from = "noreply@nstda.or.th";
                                $SendMail->send();
                            }
                        } else {
                            $error = 'error';
                            Yii::app()->user->setFlash('error','คุณยังไม่ได้ทำรายการครบทุกขั้นตอน');
                        }
                    } 
                    else if (isset($_POST['draft']) && $error == '') {
                        $model->setIsNewRecord(FALSE);
                        if ($model->update()) {
                            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                            $criteria->limit = 1;
                            $Scholar = Scholar::model()->find($criteria);
                            $Scholar->status = 'draft';
                            $Scholar->last_updated = date("Y-m-d H:i:s");
                            $Scholar->setIsNewRecord(FALSE);
                            $Scholar->update();
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    }
                    else if (isset($_POST['saveapply']) && $error == '') {
                        $model->setIsNewRecord(FALSE);
                        if ($model->update()) {
                            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                            $criteria->limit = 1;
                            $Scholar = Scholar::model()->find($criteria);
                            $Scholar->status = 'pending_scholarships';
                            $Scholar->last_updated = date("Y-m-d H:i:s");
                            $Scholar->setIsNewRecord(FALSE);
                            $Scholar->update();
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
                    }
                    else if (isset($_POST['save']) && $error == '') {
                        $model->setIsNewRecord(FALSE);
                        if ($model->update()) {
                            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                            $criteria->limit = 1;
                            $Scholar = Scholar::model()->find($criteria);
                            $Scholar->last_updated = date("Y-m-d H:i:s");
                            $Scholar->setIsNewRecord(FALSE);
                            $Scholar->update();
                        }
                    }

                    if ($error == '') {
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
            $model = new TgistUploadAttachmentForm();
            $criteria = new CDbCriteria;
            $criteria->condition = "id = $person_id";
            $criteria->limit = 1;
            $user = Person::model()->find($criteria);

            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId() . " and " . $person_type . "_id = " . $person_id;
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);

            $model->attributes = $Scholar->scholarTgist->attributes;
            if($person_type == 'student'){
                $model->industrial_certificate_path = $Scholar->scholarTgist->industrial_certificate_path;
                $model->student_transcript_path = $Scholar->scholarTgist->student_transcript_path;
                $model->student_portfolio_path = $Scholar->scholarTgist->student_portfolio_path;
                $model->student_attachment_other_path = $Scholar->scholarTgist->student_attachment_other_path;
                $model->student_attachment_other2_path = $Scholar->scholarTgist->student_attachment_other2_path;
                $model->student_attachment_other3_path = $Scholar->scholarTgist->student_attachment_other3_path;
            }else if ($person_type == 'professor'){
                $model->cv_path = $user->cv_path;
                $model->professor_attachment_other_path = $Scholar->scholarTgist->professor_attachment_other_path;
                $model->professor_attachment_other2_path = $Scholar->scholarTgist->professor_attachment_other2_path;
                $model->professor_attachment_other3_path = $Scholar->scholarTgist->professor_attachment_other3_path;
//                $model->professor_proposal_path = $Scholar->scholarTgist->professor_proposal_path;
            }else if($person_type == 'mentor'){
                $model->cv_path = $user->cv_path;
                $model->mentor_attachment_other_path = $Scholar->scholarTgist->mentor_attachment_other_path;
                $model->mentor_attachment_other2_path = $Scholar->scholarTgist->mentor_attachment_other2_path;
                $model->mentor_attachment_other3_path = $Scholar->scholarTgist->mentor_attachment_other3_path;
            }else if($person_type == 'industrial'){
                $model->cv_path = $user->cv_path;
                $model->industrial_attachment_other_path = $Scholar->scholarTgist->industrial_attachment_other_path;
                $model->industrial_attachment_other2_path = $Scholar->scholarTgist->industrial_attachment_other2_path;
                $model->industrial_attachment_other3_path = $Scholar->scholarTgist->industrial_attachment_other3_path;
            }
            
            $criteria->condition = ""
                . "id = " . ConfigWeb::getActiveScholarId()
                . " and  " . strtolower($person_type) . '_id = ' .$person_id;
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            if(!empty($Scholar))
                $model->status = $Scholar->status;

        }
        
        $this->render('miniattachment', array(
            'model' => $model,
        ));
        
    }
}