<?php

class StemController extends Controller {

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
            Yii::app()->session['scholar_type'] = 'stem';
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
        
        $stemModel = new ScholarStem;
        if ($stemModel->save()) {
            $ScholarStem2 = new ScholarStem2();
            $ScholarStem2->id = $stemModel->id;
            if ($ScholarStem2->save()) {
                $scholarModel = new Scholar;
                $scholarModel['scholar_' . strtolower($scholar_type) . '_id'] = $stemModel->id;
                $scholarModel[$person_type . '_id'] = $person_id;
                $scholarModel->type = strtolower($scholar_type);
                $scholarModel->first_created = date("Y-m-d H:i:s");
                $scholarModel->last_updated = date("Y-m-d H:i:s");
                $scholarModel->status = 'draft';
                if ($scholarModel->save()) {
                    $commentModel = new Comment;
                    $commentModel->scholar_id = $scholarModel->id;
                    $commentModel->person_id = $person_id;
                    $commentModel->status = 'draft';
                    $commentModel->first_created = date("Y-m-d H:i:s");
                    $commentModel->last_updated = date("Y-m-d H:i:s");
                    if ($commentModel->save()) {
                        Yii::app()->session['tmpActiveScholarId'] = $scholarModel->id;
                        $this->redirect(Yii::app()->createUrl(WorkflowData::WorkflowUrlStart()));
                    } else {
                        $scholarModel->delete();
                        $stemModel->delete();
                        throw new CHttpException(500, 'HTTPInternalServerError.');
                    }
                } else {
                    $ScholarStem2->delete();
                    throw new CHttpException(500, 'HTTPInternalServerError.');
                }
            } else {
                $stemModel->delete();
                throw new CHttpException(500, 'HTTPInternalServerError.');
            }
        } else {
            throw new CHttpException(500, 'HTTPInternalServerError.');
        }
    }
    
    public function actionExportExcelStem() {
        ConfigWeb::PageAdminOnly();
        $year = Yii::app()->request->getQuery('year');
        $whareOrg = '';
        $org_id = Yii::app()->session['admin_group'];
        if($org_id != 'all'){
            $whareOrg = " AND IF(sch.professor_id is NULL, men.org_id , 0) = " . $org_id;
        }
        $sql = Yii::app()->params['sqlExportStem'] . " "
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

            ExcelExporter::sendAsXLS('ExportExcelStem', $data, false, true, $fields);
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
        $ApplicationForm = Yii::app()->basePath . '/../template/stem/ApplicationForm/';

        $pathTemp = Yii::app()->basePath . '/../temp/' . $ssid;
        if (!is_dir($pathTemp)) {
            @mkdir($pathTemp, 0, true);
         }
            
        if(!empty($Scholar)){
            $ZipName = 'STEM_'.$Scholar[0]['running'].'.zip';
            $ZipPath = $pathTemp . '/' . $ZipName;

            $dZipPath = Yii::getPathOfAlias('ext.dZip');
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            include($dZipPath . DIRECTORY_SEPARATOR . 'dZip.inc.php');
            $zip = new dZip($ZipPath);
            spl_autoload_register(array('YiiBase', 'autoload'));
            
            
            foreach ($Scholar as $stem) {
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $stem['x_id'];
                $criteria->limit = 1;
                $Sch = Scholar::model()->find($criteria);
                
                $zip->addDir($stem['running']);
                if (file_exists($ApplicationForm."ApplicationForm_".$stem['running'].".docx")) {
                    $newFile = $stem['running']."/"."ApplicationForm_".$stem['running'].".docx";
                    $zip->addFile($ApplicationForm."ApplicationForm_".$stem['running'].".docx", $newFile);
                }
                
                $zip->addDir($stem['running']."/"."Student"); // Add Folder
                $fd_pro_men = (empty($stem['pro_id'])?"Mentor":"Professor");
                $zip->addDir($stem['running']."/".$fd_pro_men); // Add Folder
                $zip->addDir($stem['running']."/"."Industrial"); // Add Folder
   
                if(is_dir($path.$stem['stu_id'])){
                    $pathStu = "/"."Student/";
                    $file = $Sch->student->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->student->copy_id_card_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->student_transcript_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
					$file = $Sch->scholarStem->student_card_path;
					if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
					
                    $file = $Sch->scholarStem->student_portfolio_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }        
                    $file = $Sch->scholarStem->student_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    } 
                    $file = $Sch->scholarStem->student_attachment_other2_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
                $path_pro_men = (empty($stem['pro_id'])?$stem['men_id']:$stem['pro_id']);
                if(is_dir($path.$path_pro_men)){
                    $pathProMen = "/".$fd_pro_men."/";
                    $file = (empty($Sch->professor)?$Sch->mentor->image_path:$Sch->professor->image_path);
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = (empty($Sch->professor)?$Sch->mentor->cv_path:$Sch->professor->cv_path);
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->professor_mentor_attachment_project_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->professor_mentor_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
                if(is_dir($path.$stem['ind_id'])){
                    $pathInd = "/"."Industrial/";
                    $file = $Sch->industrial->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->industrial_certificate_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->industrial_join_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->industrial_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
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
        $ApplicationForm = Yii::app()->basePath . '/../template/stem/ApplicationForm/';
        
        $year = Yii::app()->request->getQuery('year');
        $whareOrg = '';
        $org_id = Yii::app()->session['admin_group'];
        if($org_id != 'all'){
            $whareOrg = " AND IF(sch.professor_id is NULL, men.org_id , 0) = " . $org_id;
        }
        $file_stem_ids = array();
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
            $ZipName = 'STEM_ALL.zip';
            $ZipPath = $pathTemp . '/' . $ZipName;

            $dZipPath = Yii::getPathOfAlias('ext.dZip');
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            include($dZipPath . DIRECTORY_SEPARATOR . 'dZip.inc.php');
            $zip = new dZip($ZipPath);
            spl_autoload_register(array('YiiBase', 'autoload'));

            foreach ($Scholar as $stem) {
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $stem['x_id'];
                $criteria->limit = 1;
                $Sch = Scholar::model()->find($criteria);
//                StemController::actionWord($stem['x_id']);
                $zip->addDir($stem['running']);
                if (file_exists($ApplicationForm."ApplicationForm_".$stem['running'].".docx")) {
                    $newFile = $stem['running']."/"."ApplicationForm_".$stem['running'].".docx";
                    $zip->addFile($ApplicationForm."ApplicationForm_".$stem['running'].".docx", $newFile);
                }else{
                    StemController::actionWord($stem['x_id']);
                    $newFile = $stem['running']."/"."ApplicationForm_".$stem['running'].".docx";
                    $zip->addFile($ApplicationForm."ApplicationForm_".$stem['running'].".docx", $newFile);
                }
                $zip->addDir($stem['running']."/"."Student"); // Add Folder
                $fd_pro_men = (empty($stem['pro_id'])?"Mentor":"Professor");
                $zip->addDir($stem['running']."/".$fd_pro_men); // Add Folder
                $zip->addDir($stem['running']."/"."Industrial"); // Add Folder
                
                if(is_dir($path.$stem['stu_id'])){
                    $pathStu = "/"."Student/";
                    $file = $Sch->student->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->student->copy_id_card_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->student_transcript_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->student_portfolio_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }        
                    $file = $Sch->scholarStem->student_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    } 
                    $file = $Sch->scholarStem->student_attachment_other2_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathStu.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
                $path_pro_men = (empty($stem['pro_id'])?$stem['men_id']:$stem['pro_id']);
                if(is_dir($path.$path_pro_men)){
                    $pathProMen = "/".$fd_pro_men."/";
                    $file = (empty($Sch->professor)?$Sch->mentor->image_path:$Sch->professor->image_path);
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = (empty($Sch->professor)?$Sch->mentor->cv_path:$Sch->professor->cv_path);
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->professor_mentor_attachment_project_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->professor_mentor_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathProMen.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                }
                
                if(is_dir($path.$stem['ind_id'])){
                    $pathInd = "/"."Industrial/";
                    $file = $Sch->industrial->image_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->industrial_certificate_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->industrial_join_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
                        $zip->addFile($path.$file, $newFile);
                    }
                    $file = $Sch->scholarStem->industrial_attachment_other_path;
                    if (is_file($path.$file)) {
                        $newFile = $stem['running'].$pathInd.basename($file);
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
            $document = $PHPWord->loadTemplate(Yii::app()->basePath . '/../template/stem/TemplateApplicationForm.docx');
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
            $document->setValue('id_card_created', ConfigWeb::setWord($data[0]['stu_id_card_created']));
            $document->setValue('id_card_expired', ConfigWeb::setWord($data[0]['stu_id_card_expired']));
            
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
            $document->setValue('comment_professor_mentor', ConfigWeb::setWord($data[0]['pro_comment'].$data[0]['men_comment']));
            $document->setValue('comment_industrial', ConfigWeb::setWord($data[0]['ind_comment']));
//            
            $document->setValue('xfnc_att_cv', ConfigWeb::setCheck($data[0]['att_cv']));
            $document->setValue('xfnc_att_project', ConfigWeb::setCheck($data[0]['att_project']));
            $document->setValue('xfnc_att_pro_men_other', ConfigWeb::setCheck($data[0]['att_pro_men_other']));
            $document->setValue('xfnc_att_transcript', ConfigWeb::setCheck($data[0]['att_transcript']));
            $document->setValue('xfnc_att_id_card', ConfigWeb::setCheck($data[0]['att_id_card']));
            $document->setValue('xfnc_att_portfolio', ConfigWeb::setCheck($data[0]['att_portfolio']));
            $document->setValue('xfnc_att_stu_card', ConfigWeb::setCheck($data[0]['att_stu_card']));
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
            $path = Yii::app()->basePath . '/../template/stem/ApplicationForm';
            $pathSave = $path . '/ApplicationForm_'. $data[0]['running'] . '.docx';
            if (!is_dir($path)) {
               @mkdir($path, 0, true);
            }
 
            $document->save($pathSave);
            if($download){
                $pathUrl = '/template/stem/ApplicationForm/ApplicationForm_'. $data[0]['running'] . '.docx';
                header('Location: ' . Yii::app()->baseUrl . $pathUrl);
                Yii::app()->end();
            }
			$this->redirect(Yii::app()->createUrl(WorkflowData::$home));
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
    
	public function actionTestMail()
	{
		$SendMail = new SendMail ();
		$scholar_type = 'Text STEM';
		$subject = Yii::app()->params ['EmailTemplateRecommendationSubject'];
		$subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
		$subject = str_replace("##TYPE##", "นักเรียน/นักศึกษา", $subject);
		$SendMail->subject = $subject;
		$message = Yii::app()->params ['EmailTemplateRecommendationBody'];
		$message = str_replace("##EMAILADMINSTEM##", Yii::app()->params ['adminStemEmail'], $message);
		$message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
		$PERSONNAME = "Jakkrich" . "  " . "Changgon";
		$message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
		$message = str_replace("##SCHOLARTYPE##", $scholar_type, $message);
		$verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
			'token' => "aaa",
			'scholartype' => "stem"
		));
		$URL = 'http://' . Yii::app()->params ['serverDomain'] . $verifyUrl;
		$message = str_replace("##URL##", $URL, $message);
		$SendMail->body = $message;
		$SendMail->to = "jakkrich.changgon@nstda.or.th";
		$SendMail->from = "noreply@nstda.or.th";
		$SendMail->send();
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

        $scholar_stem_id = NULL;
        $model = new StemRecommendationForm ();
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
                $model->$key = $value;
            else {
                $scholar_stem_id = $value;
                $criteria = new CDbCriteria ();
                $criteria->condition = "id = " . $scholar_stem_id;
                $criteria->limit = 1;
                $ScholarStem = ScholarStem::model()->find($criteria);
                // $model->industrial_support = $ScholarStem->industrial_support;
                $model->industrial_incash_salary = $ScholarStem->industrial_incash_salary;
                $model->industrial_incash_salary_cost = $ScholarStem->industrial_incash_salary_cost;
                $model->industrial_incash_rents = $ScholarStem->industrial_incash_rents;
                $model->industrial_incash_rents_cost = $ScholarStem->industrial_incash_rents_cost;
                $model->industrial_incash_traveling = $ScholarStem->industrial_incash_traveling;
                $model->industrial_incash_traveling_cost = $ScholarStem->industrial_incash_traveling_cost;
                $model->industrial_incash_other = $ScholarStem->industrial_incash_other;
                $model->industrial_incash_other_cost = $ScholarStem->industrial_incash_other_cost;
                $model->industrial_incash_other_text = $ScholarStem->industrial_incash_other_text;
                $model->industrial_incash_other2 = $ScholarStem->industrial_incash_other2;
                $model->industrial_incash_other2_cost = $ScholarStem->industrial_incash_other2_cost;
                $model->industrial_incash_other2_text = $ScholarStem->industrial_incash_other2_text;
                $model->industrial_inkind_equipment = $ScholarStem->industrial_inkind_equipment;
                $model->industrial_inkind_equipment_cost = $ScholarStem->industrial_inkind_equipment_cost;
                $model->industrial_inkind_other = $ScholarStem->industrial_inkind_other;
                $model->industrial_inkind_other_cost = $ScholarStem->industrial_inkind_other_cost;
                $model->industrial_inkind_other_text = $ScholarStem->industrial_inkind_other_text;
                $model->industrial_inkind_other2 = $ScholarStem->industrial_inkind_other2;
                $model->industrial_inkind_other2_cost = $ScholarStem->industrial_inkind_other2_cost;
                $model->industrial_inkind_other2_text = $ScholarStem->industrial_inkind_other2_text;
//                $model->industrial_support_desc = $ScholarStem->industrial_support_desc;
                $criteria->condition = "" . "scholar_id = " . ConfigWeb::getActiveScholarId() . " and person_id = " . $person_id;
                $criteria->limit = 1;
                $Comment = Comment::model()->find($criteria);
                $model->comment = $Comment->comment;
                $model->status = $Comment->status;
                
                $criteria->condition = " id = " . $ScholarStem->id;
                $criteria->limit = 1;
                $ScholarStem2 = ScholarStem2::model()->find($criteria);
                $model->industrial_support_desc = $ScholarStem2->industrial_support_desc;
            }
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['StemRecommendationForm'])) {
                $model->attributes = Yii::app()->input->post('StemRecommendationForm');

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
                        // $ScholarStem->industrial_support = $model->industrial_support;
                        // $ScholarStem->first_created = $ScholarStem->first_created;
                        // $ScholarStem->last_updated = date("Y-m-d H:i:s");
                        $ScholarStem->industrial_incash_salary = $model->industrial_incash_salary;
                        $ScholarStem->industrial_incash_salary_cost = $model->industrial_incash_salary_cost;
                        $ScholarStem->industrial_incash_rents = $model->industrial_incash_rents;
                        $ScholarStem->industrial_incash_rents_cost = $model->industrial_incash_rents_cost;
                        $ScholarStem->industrial_incash_traveling = $model->industrial_incash_traveling;
                        $ScholarStem->industrial_incash_traveling_cost = $model->industrial_incash_traveling_cost;
                        $ScholarStem->industrial_incash_other = $model->industrial_incash_other;
                        $ScholarStem->industrial_incash_other_cost = $model->industrial_incash_other_cost;
                        $ScholarStem->industrial_incash_other_text = $model->industrial_incash_other_text;
                        $ScholarStem->industrial_incash_other2 = $model->industrial_incash_other2;
                        $ScholarStem->industrial_incash_other2_cost = $model->industrial_incash_other2_cost;
                        $ScholarStem->industrial_incash_other2_text = $model->industrial_incash_other2_text;
                        $ScholarStem->industrial_inkind_equipment = $model->industrial_inkind_equipment;
                        $ScholarStem->industrial_inkind_equipment_cost = $model->industrial_inkind_equipment_cost;
                        $ScholarStem->industrial_inkind_other = $model->industrial_inkind_other;
                        $ScholarStem->industrial_inkind_other_cost = $model->industrial_inkind_other_cost;
                        $ScholarStem->industrial_inkind_other_text = $model->industrial_inkind_other_text;
                        $ScholarStem->industrial_inkind_other2 = $model->industrial_inkind_other2;
                        $ScholarStem->industrial_inkind_other2_cost = $model->industrial_inkind_other2_cost;
                        $ScholarStem->industrial_inkind_other2_text = $model->industrial_inkind_other2_text;
                        $ScholarStem->industrial_support_desc = $model->industrial_support_desc;
                        $ScholarStem->setIsNewRecord(FALSE);
                        if ($ScholarStem->update()) {
                            $ScholarStem2 = new ScholarStem2();
                            $ScholarStem2->id = $ScholarStem->id;
                            $ScholarStem2->industrial_support_desc = $model->industrial_support_desc;
                            $ScholarStem2->setIsNewRecord(FALSE);
                            $ScholarStem2->update();
                                    
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
                                        // ADD NEW 16/3/2017
                                        // ระบบส่งเมล์แจ้ง อาจารย์/นักวิจัย *************************
                                        $SendMail = new SendMail ();
                                        $scholar_type = strtoupper(ConfigWeb::getActiveScholarType());
                                        $subject = Yii::app()->params ['EmailTemplateAlertConfirmSubject'];
                                        $subject = str_replace("##SCHOLARTYPE##", $scholar_type, $subject);
                                        $typep = '';
                                        $token = '';
                                        if(!empty($Scholar->professor_id)){
                                            $typep = 'อาจารย์';
                                            $token = $Scholar->professor->token;
                                        }
                                        else if(!empty($Scholar->industrial_id)){
                                            $typep = 'นักวิจัย';
                                            $token = $Scholar->industrial->token;
                                        }
                                        $SendMail->subject = $subject;
                                        $message = Yii::app()->params ['EmailTemplateAlertConfirmBody'];
                                        $message = str_replace("##TELLADMIN##", Yii::app()->params ['adminStemTell'], $message);
                                        $PERSONNAME = $Scholar->professor->fname . "  " . $Scholar->professor->lname;
                                        $message = str_replace("##PERSONNAME##", $PERSONNAME, $message);
                                        $verifyUrl = Yii::app()->createUrl('site/verifytoken', array(
                                            'token' => $token,
                                            'scholartype' => ConfigWeb::getActiveScholarType()
                                        ));
                                        $URL = Yii::app()->params ['serverDomain'] . "/" . Yii::app()->createUrl("stem");
                                        $message = str_replace("##URL##", $URL, $message);
                                        $SendMail->body = $message;
                                        $SendMail->to = $Scholar->professor->email;
                                        $SendMail->from = "noreply@nstda.or.th";
                                        $SendMail->send();
                                        // END NEW 16/3/2017
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

    public function actionStudent() {
        $Scholar = NULL;
        $Student = NULL;
        $model = new StemStudentForm ();

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        if (!empty($Scholar->student_id)) {
            $criteria->condition = "id = " . $Scholar->student_id;
            $criteria->limit = 1;
            $Student = Person::model()->find($criteria);
            $model->attributes = $Student->attributes;
            $model->id = $Scholar->student_id;
            $model->status = $Scholar->status;
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['StemStudentForm'])) {
                $model->attributes = Yii::app()->input->post('StemStudentForm');
                
                $criteria->condition = "email = '" . $model->email . "'";
                $criteria->limit = 1;
                $Student = Person::model()->find($criteria);
                
                if(!empty($Student)){
                    $model->prefix_id = $Student->prefix_id;
                }
                
                if (isset($_POST ['next'])) {
                    if ($model->validate()) {
                        if (!empty($Student)) {
                            $model->first_created = $Student->first_created;
                            $model->last_updated = date("Y-m-d H:i:s");
                            $model->setIsNewRecord(FALSE);
                            if ($model->update()) {
                                $criteria->condition = "email = '" . $model->email . "' and type = 'student' ";
                                $criteria->limit = 1;
                                $Student = Person::model()->find($criteria);

                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->student_id = $Student->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                                    $this->redirect(Yii::app()->createUrl($UrlNext));
                                }
                            }
                        } else {
                            $model->id = NULL;
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
                                if (!@mkdir(Yii::app()->basePath . Yii::app()->params ['pathUploads'] . $model->id, 0, true)) {
                                    
                                }
                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->student_id = $model->id;
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                if ($Scholar->update()) {
                                    $criteria->condition = "email = '" . $model->email . "' and type = 'student' ";
                                    $criteria->limit = 1;
                                    $Student = Person::model()->find($criteria);

                                    $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                    $criteria->limit = 1;
                                    $Scholar = Scholar::model()->find($criteria);
                                    $Scholar->student_id = $Student->id;
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
        $this->render('student', array(
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
             
            /*$diff = ConfigWeb::GetPeriodDate($begin, $end);
            if ($diff) {
                if($mode == 'primary'){
                    $int_begin = str_replace('-', '', $begin);
                    $int_end = str_replace('-', '', $end);
                    $begin_from = Yii::app()->params['stem_project_begin_min'];
                    $begin_to = Yii::app()->params['stem_project_begin_max'];
                    
                    $end_last = Yii::app()->params['stem_project_sub_begin_min'];
                    $end_last = date('Y-m-d', strtotime($end_last . ' +6 month -1 day'));
//                    $end_last = date('Y-m-d', strtotime($end_last . ' -1 day'));
                    
                    $int_from = str_replace('-', '', $begin_from);
                    $int_to = str_replace('-', '', $begin_to);
                    $int_last = str_replace('-', '', $end_last);
                    
                    $isOk = false;
                    if($int_begin >= $int_from && $int_begin<= $int_to && $int_end >= $int_last){
                        $isOk = true;
                    }
                    if($isOk){
                        if($diff->y >= 1 || $diff->m >= 6)
                            $data = $diff->y . ' ปี  ' . $diff->m . ' เดือน  ' . $diff->d . ' วัน  ';
                        else
                            $data = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (ระยะเวลาต้องไม่ต่ำกว่า 6 เดือน)';
                    }else{
                        if($int_begin < $int_from || $int_begin > $int_to){
                            $data = 'วันเริ่มโครงการหลักต้องไม่เกินวันที่ '
                                . ''.date("d/m/Y", strtotime($begin_to));
                        }else if($int_end < $int_last){
                            $data = 'วันสิ้นสุดโครงการหลักต้องมากกว่าวันที่ '.date("d/m/Y", strtotime($end_last))
                                . ' เป็นต้นไป';
                        }
                    }
                }else if($mode == 'nonprimary'){
                    if($diff->m >= 6 && $diff->m <= 11 || ($diff->y == 1 && $diff->m == 0 && $diff->d == 0)){
                        $cur_start = new DateTime($begin);
                        $cur_end = new DateTime($end);
                        
                        $criteria = new CDbCriteria ();
                        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                        $criteria->limit = 1;
                        $Scholar = Scholar::model()->find($criteria);
                        
                        $criteria->condition = "id = " . $Scholar->scholarStem->project_id;
                        $criteria->limit = 1;
                        $Project = Project::model()->find($criteria);
                        
                        $pk_begin = $Project->begin;
                        $pk_end = $Project->end;
                        
                        $pk_begin = ConfigWeb::formatDataViewToDB($pk_begin);
                        $pk_end = ConfigWeb::formatDataViewToDB($pk_end);
                        $datetime_pk_begin = new DateTime($pk_begin);
                        $datetime_pk_end = new DateTime($pk_end);
                        
                        $begin_to = Yii::app()->params['stem_project_sub_begin_min'];
                        $begin_max = Yii::app()->params['stem_project_begin_max'];
                        $end_last = Yii::app()->params['stem_project_end_max'];
                        $datetime_begin_to = new DateTime($begin_to);
                        $datetime_begin_max = new DateTime($begin_max);
                        $datetime_end_last = new DateTime($end_last);
                        
                        if($datetime_pk_begin <= $cur_start && $datetime_pk_end >= $cur_end){
                            if($cur_start < $datetime_begin_to || $cur_start > $datetime_begin_max){
                                $data = 'วันเริ่มโครงการย่อยต้องอยู่ในช่วง '
                                        . ''.date("d/m/Y", strtotime($begin_to)).''
                                        . ' ถึง '
                                        . ''.date("d/m/Y", strtotime($begin_max));
                            }else if($cur_end < $end_last){
                                $data = 'วันสิ้นสุดโครงการย่อยต้องมากกว่า '
                                        . ''.date("d/m/Y", strtotime($end_last));
                            }else
                                $data = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
                        }else
                            $data = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (โครงการย่อยระยะเวลาต้องไม่เกินโครงการหลัก '.date('d/m/Y', strtotime($pk_begin)).' - '.date('d/m/Y', strtotime($pk_end)).')';
                    }else
                        $data = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (ระยะเวลาต้องไม่ต่ำกว่า 6 เดือนหรือมากกว่า 12 เดือน)';
                }else{
                    $data = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
                }
            }*/
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
                        $this->redirect(Yii::app()->createUrl('stem/history'));
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
        $this->redirect(Yii::app()->createUrl('stem/history'));
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
                        $this->redirect(Yii::app()->createUrl('stem/history'));
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

        $model = new StemHistoryForm ();
        $Stem = new ScholarStem ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id";
        $criteria->limit = 1;
        $scholar = Scholar::model()->find($criteria);

        $criteria->condition = "id = " . $scholar->scholarStem->id;
        $criteria->limit = 1;
        $Stem = ScholarStem::model()->find($criteria);
        $model->attributes = $Stem->attributes;
        $model->id = $Stem->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['StemHistoryForm'])) {
                $model->attributes = Yii::app()->input->post('StemHistoryForm');
                $Stem->is_history = $model->is_history;
                if ($model->validate()) {
                    $model->setIsNewRecord(FALSE);
                    if ($Stem->update()) {
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

        $model = new StemWorkingForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id";
        $criteria->limit = 1;
        $scholar = Scholar::model()->find($criteria);

        $criteria->condition = "id = " . $scholar->scholarStem->id;
        $criteria->limit = 1;
        $ScholarStem = ScholarStem::model()->find($criteria);
        $model->attributes = $ScholarStem->attributes;
        $model->id = $ScholarStem->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['StemWorkingForm'])) {
                $model->attributes = Yii::app()->input->post('StemWorkingForm');
                if ($model->validate()) {
                    $ScholarStem->is_work = $model->is_work;
                    $ScholarStem->work_company = $model->work_company;
                    $ScholarStem->work_position = $model->work_position;
                    $ScholarStem->work_location = $model->work_location;
                    $ScholarStem->work_phone = $model->work_phone;
                    $ScholarStem->work_fax = $model->work_fax;
                    $ScholarStem->is_workwithproject = $model->is_workwithproject;
                    $ScholarStem->workwithproject_text1 = $model->workwithproject_text1;
                    $ScholarStem->workwithproject_text2 = $model->workwithproject_text2;
                    $ScholarStem->workwithproject_text3 = $model->workwithproject_text3;
                    $model->setIsNewRecord(FALSE);
                    if ($ScholarStem->update()) {
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

        $model = new StemExperienceForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = $id";
        $criteria->limit = 1;
        $scholar = Scholar::model()->find($criteria);

        $criteria->condition = "id = " . $scholar->scholarStem->id;
        $criteria->limit = 1;
        $ScholarStem = ScholarStem::model()->find($criteria);
        $model->attributes = $ScholarStem->attributes;
        $model->id = $ScholarStem->id;

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['StemExperienceForm'])) {
                $model->attributes = Yii::app()->input->post('StemExperienceForm');
                if ($model->validate()) {
                    $ScholarStem->portfolio_thesis = $model->portfolio_thesis;
                    $ScholarStem->portfolio_journal_international = $model->portfolio_journal_international;
                    $ScholarStem->portfolio_journal_incountry = $model->portfolio_journal_incountry;
                    $ScholarStem->portfolio_patent = $model->portfolio_patent;
                    $ScholarStem->portfolio_prototype = $model->portfolio_prototype;
                    $ScholarStem->portfolio_conference_international = $model->portfolio_conference_international;
                    $ScholarStem->portfolio_conference_incountry = $model->portfolio_conference_incountry;
                    $ScholarStem->portfolio_award = $model->portfolio_award;
                    $ScholarStem->portfolio_other = $model->portfolio_other;
                    $ScholarStem->portfolio_journal_international_amount = $model->portfolio_journal_international_amount;
                    $ScholarStem->portfolio_journal_incountry_amount = $model->portfolio_journal_incountry_amount;
                    $ScholarStem->portfolio_patent_amount = $model->portfolio_patent_amount;
                    $ScholarStem->portfolio_prototype_amount = $model->portfolio_prototype_amount;
                    $ScholarStem->portfolio_conference_international_amount = $model->portfolio_conference_international_amount;
                    $ScholarStem->portfolio_conference_incountry_amount = $model->portfolio_conference_incountry_amount;
                    $ScholarStem->portfolio_award_amount = $model->portfolio_award_amount;
                    $ScholarStem->portfolio_other_text = $model->portfolio_other_text;
                    $ScholarStem->portfolio_other_amount = $model->portfolio_other_amount;
                    $ScholarStem->portfolio_journal_international_desc = $model->portfolio_journal_international_desc;
                    $ScholarStem->portfolio_journal_incountry_desc = $model->portfolio_journal_incountry_desc;
                    $ScholarStem->portfolio_patent_desc = $model->portfolio_patent_desc;
                    $ScholarStem->portfolio_prototype_desc = $model->portfolio_prototype_desc;
                    $ScholarStem->portfolio_conference_international_desc = $model->portfolio_conference_international_desc;
                    $ScholarStem->portfolio_conference_incountry_desc = $model->portfolio_conference_incountry_desc;
                    $ScholarStem->portfolio_award_desc = $model->portfolio_award_desc;
                    $ScholarStem->portfolio_other_desc = $model->portfolio_other_desc;

                    $model->setIsNewRecord(FALSE);
                    if ($ScholarStem->update()) {
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
        $model = new StemPrimaryProjectForm('add');
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
        $model = new StemPrimaryProjectForm($mode);
        $modelForm = new StemPrimaryProjectForm ();
        $Project = NULL;
        $error = NULL;
        $Scholar = NULL;
        $criteria = new CDbCriteria ();

        if ($mode != 'add') {
            $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
        }
        if (!empty($Scholar->scholarStem->project_id)) {
            $criteria->condition = "id = " . $Scholar->scholarStem->project_id;
            $criteria->limit = 1;
            $Project = Project::model()->find($criteria);
            $model->attributes = $Project->attributes;
            $model->project_id = $Scholar->scholarStem->project_id;
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
            if (isset($_POST ['StemPrimaryProjectForm'])) {
                $model->attributes = Yii::app()->input->post('StemPrimaryProjectForm');
                $modelForm->attributes = Yii::app()->input->post('StemPrimaryProjectForm');
                if (isset($_POST ['add'])) {
                    $model = new StemPrimaryProjectForm('add');
                    $model->attributes = Yii::app()->input->post('StemPrimaryProjectForm');
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
                            $Scholar->scholarStem->project_id = $model->id;
                            // $Scholar->scholarStem->last_updated = date("Y-m-d H:i:s");
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if ($Scholar->scholarStem->update()) {
                                $this->redirect(Yii::app()->createUrl('stem/primaryproject'));
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
                            $Scholar->scholarStem->project_id = $model->id;
                            // $Scholar->scholarStem->last_updated = date("Y-m-d H:i:s");
                            $Scholar->scholarStem->setIsNewRecord(FALSE);
                            if ($Scholar->scholarStem->update()) {
                                $this->redirect(Yii::app()->createUrl('stem/primaryproject'));
                            }
                        }
                    } else {
                        $error = TRUE;
                        $model->attributes = $modelForm->attributes;
                    }
                } else if (isset($_POST ['next'])) {
                    $model = new StemPrimaryProjectForm('next');
                    $model->attributes = Yii::app()->input->post('StemPrimaryProjectForm');
                    $model->begin = (!empty($Project->begin)) ? $Project->begin : $model->begin;
                    $model->end = (!empty($Project->end)) ? $Project->end : $model->end;
                    $model->funding = (!empty($Project->funding)) ? $Project->funding : $model->funding;
                    if (!empty($model->project_id)) {
                        $Scholar->scholarStem->project_id = $model->project_id;
                        // $Scholar->scholarStem->last_updated = date("Y-m-d H:i:s");
                        $Scholar->scholarStem->setIsNewRecord(FALSE);
                        if ($Scholar->scholarStem->update()) {
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
            $begin_from = Yii::app()->params['stem_project_begin_min'];
            $begin_to = Yii::app()->params['stem_project_begin_max'];
            $end_last = Yii::app()->params['stem_project_sub_begin_min'];
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
        $error = NULL;
        $ScholarStem = NULL;
        $model = new StemStudentProjectForm ();
        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        if (!empty($Scholar->scholar_stem_id)) {
            $criteria->condition = "id = " . $Scholar->scholar_stem_id;
            $criteria->limit = 1;
            $ScholarStem = ScholarStem::model()->find($criteria);
            $model->attributes = $ScholarStem->attributes;
            $model->id = $ScholarStem->id;
        }
        
        if (!empty($ScholarStem->project_begin))
            $model->project_begin = date("d/m/Y", strtotime($ScholarStem->project_begin));
        if (!empty($ScholarStem->project_end))
            $model->project_end = date("d/m/Y", strtotime($ScholarStem->project_end));
        
        if ($ScholarStem !== NULL) {
            if ($model->mentor_has_professor_institute_id == NULL && !empty($model->mentor_has_professor_institute_other))
                $model->mentor_has_professor_institute_id = 0;
            if ($model->mentor_has_professor_faculty_id == NULL && !empty($model->mentor_has_professor_faculty_other))
                $model->mentor_has_professor_faculty_id = 0;
        }
        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['StemStudentProjectForm'])) {
                $model->attributes = Yii::app()->input->post('StemStudentProjectForm');
                
                if ($model->validate()) {
                    $model->mentor_has_professor_institute_id = ($model->mentor_has_professor_institute_id == NULL || $model->mentor_has_professor_institute_id == 0) ? NULL : intval($model->mentor_has_professor_institute_id);
                    $model->mentor_has_professor_faculty_id = ($model->mentor_has_professor_faculty_id == NULL || $model->mentor_has_professor_faculty_id == 0) ? NULL : intval($model->mentor_has_professor_faculty_id);
                        
                    $model->project_begin = ConfigWeb::formatDataViewToDB($model->project_begin);
                    $model->project_end = ConfigWeb::formatDataViewToDB($model->project_end);
                    $model->last_updated = date("Y-m-d H:i:s");
                    if (empty($model->incash_fee)) {
                        $model->incash_fee_cost = 0;
                        $model->incash_fee_source = NULL;
                    }
                    if (empty($model->incash_monthly)) {
                        $model->incash_monthly_cost = 0;
                        $model->incash_monthly_source = NULL;
                    }
                    if (empty($model->incash_other)) {
                        $model->incash_other_cost = 0;
                        $model->incash_other_source = NULL;
                        $model->incash_other_text = NULL;
                    }
                    if (empty($model->incash_other2)) {
                        $model->incash_other2_cost = 0;
                        $model->incash_other2_source = NULL;
                        $model->incash_other2_text = NULL;
                    }
                    if (empty($model->inkind_other)) {
                        $model->inkind_other_cost = 0;
                        $model->inkind_other_source = NULL;
                        $model->inkind_other_text = NULL;
                    }
                    if (empty($model->inkind_other2)) {
                        $model->inkind_other2_cost = 0;
                        $model->inkind_other2_source = NULL;
                        $model->inkind_other2_text = NULL;
                    }
                    
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                        $this->redirect(Yii::app()->createUrl($UrlNext));
                    }
                }
            }
        }

        if ($error === NULL && !empty($model->project_begin) && !empty($model->project_end)) {
            $model->func_period = ConfigWeb::GetCalProjectPeriodMsg(
                    ConfigWeb::formatDataViewToDB($model->project_begin),
                    ConfigWeb::formatDataViewToDB($model->project_end), 'nonprimary');
            /*
            $diff = ConfigWeb::GetPeriodDate($model->project_begin, $model->project_end);
            if ($diff) {
                if(($diff->m >= 6 && $diff->m <= 11 && $diff->y == 0) || ($diff->y == 1 && $diff->m == 0 && $diff->d == 0)){
                    
                    $begin = ConfigWeb::formatDataViewToDB($model->project_begin);
                    $end = ConfigWeb::formatDataViewToDB($model->project_end);
                    $cur_start = new DateTime($begin);
                    $cur_end = new DateTime($end);
                        
                    $criteria = new CDbCriteria ();
                    $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                    $criteria->limit = 1;
                    $Scholar = Scholar::model()->find($criteria);

                    $criteria->condition = "id = " . $Scholar->scholarStem->project_id;
                    $criteria->limit = 1;
                    $Project = Project::model()->find($criteria);

                    $pk_begin = $Project->begin;
                    $pk_end = $Project->end;

                    $pk_begin = ConfigWeb::formatDataViewToDB($pk_begin);
                    $pk_end = ConfigWeb::formatDataViewToDB($pk_end);
                    $datetime_pk_begin = new DateTime($pk_begin);
                    $datetime_pk_end = new DateTime($pk_end);

                    if($datetime_pk_begin <= $cur_start && $datetime_pk_end >= $cur_end){
                        $begin_to = Yii::app()->params['stem_project_sub_begin_min'];
                        $begin_max = Yii::app()->params['stem_project_begin_max'];
                        $end_last = Yii::app()->params['stem_project_end_max'];
                        $datetime_begin_to = new DateTime($begin_to);
                        $datetime_begin_max = new DateTime($begin_max);
                        $datetime_end_last = new DateTime($end_last);
                        if($cur_start < $datetime_begin_to || $cur_start > $datetime_begin_max){
                            $model->func_period  = 'วันเริ่มโครงการย่อยต้องอยู่ในช่วง '
                                    . ''.date("d/m/Y", strtotime($begin_to)).''
                                    . ' ถึง '
                                    . ''.date("d/m/Y", strtotime($begin_max));
                        }else if($cur_end < $end_last){
                            $model->func_period = 'วันสิ้นสุดโครงการย่อยต้องมากกว่า '
                                    . ''.date("d/m/Y", strtotime($end_last));
                        }else
                            $model->func_period  = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
                        
                    }else
                        $model->func_period = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (โครงการย่อยระยะเวลาต้องไม่เกินโครงการหลัก '.date('d/m/Y', strtotime($pk_begin)).' - '.date('d/m/Y', strtotime($pk_end)).')';
                }else
                    $model->func_period = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (ระยะเวลาต้องไม่ต่ำกว่า 6 เดือนหรือมากกว่า 12 เดือน)';
            } else {
                $model->func_period = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
            }*/
        }

        if ($model->mentor_has_professor_institute_id == NULL && !empty($model->mentor_has_professor_institute_other))
            $model->mentor_has_professor_institute_id = 0;
        if ($model->mentor_has_professor_faculty_id == NULL && !empty($model->mentor_has_professor_faculty_other))
            $model->mentor_has_professor_faculty_id = 0;
        
        $model->incash_sum = $model->incash_fee_cost + $model->incash_monthly_cost + $model->incash_other_cost + $model->incash_other2_cost;
        $model->inkind_sum = $model->inkind_other_cost + $model->inkind_other2_cost;
        $model->sum = $model->incash_sum + $model->inkind_sum;
        $this->render('studentproject', array(
            'model' => $model
        ));
    }

    public function actionCompany() {
        $Scholar = NULL;
        $Industrial = NULL;
        $model = new StemCompanyForm ();

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);
        if (!empty($Scholar->industrial_id)) {
            $criteria->condition = "id = " . $Scholar->industrial_id;
            $criteria->limit = 1;
            $Industrial = Person::model()->find($criteria);
            $model->attributes = $Industrial->attributes;
            $model->id = $Scholar->industrial_id;
            $model->status = $Scholar->status;
        }

        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST ['StemCompanyForm'])) {
                $model->attributes = Yii::app()->input->post('StemCompanyForm');

                $criteria->condition = "email = '" . $model->email . "'";
                $criteria->limit = 1;
                $Industrial = Person::model()->find($criteria);
                if(!empty($Industrial)){
                    $model->prefix_id = $Industrial->prefix_id;
                    $model->industrial_type_manufacture = $Industrial->industrial_type_manufacture;
                    $model->industrial_type_export = $Industrial->industrial_type_export;
                    $model->industrial_type_service = $Industrial->industrial_type_service;
                }
                if (isset($_POST ['next'])) {
                    if ($model->validate()) {
                        if (!empty($Industrial)) {
                            // EDIT________________________________
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
                            // ADD_________________________________
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
        $this->render('company', array(
            'model' => $model
        ));
    }

    public function actionComment() {
        $model = new StemCommentForm ();
        $person_id = ConfigWeb::getActivePersonId();

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
            if (isset($_POST ['StemCommentForm'])) {
                $model->attributes = Yii::app()->input->post('StemCommentForm');
                if (isset($_POST ['savedraft']) || isset($_POST ['savesend'])) {
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
                                $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                                $criteria->limit = 1;
                                $Scholar = Scholar::model()->find($criteria);
                                $Scholar->status = 'pending_recommendations';
                                $Scholar->last_updated = date("Y-m-d H:i:s");
                                $Scholar->setIsNewRecord(FALSE);
                                $Scholar->update();
                                $criteria->condition = "scholar_id = " . ConfigWeb::getActiveScholarId() . " and person_id = " . $Scholar->student_id;
                                $criteria->limit = 1;
                                $ScholarStudent = Comment::model()->find($criteria);
                                if (empty($ScholarStudent)) {
                                    $commentForStudent = new Comment ();
                                    $commentForStudent->scholar_id = ConfigWeb::getActiveScholarId();
                                    $commentForStudent->person_id = $Scholar->student_id;
                                    $commentForStudent->status = 'draft';
                                    $commentForStudent->first_created = date("Y-m-d H:i:s");
                                    $commentForStudent->last_updated = date("Y-m-d H:i:s");
                                    $commentForStudent->save();
                                    $emailStudent = $commentForStudent->person->email;
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
                                }
                            }
                            $UrlNext = WorkflowData::WorkflowUrlNext(Yii::app()->urlManager->parseUrl(Yii::app()->request), TRUE);
                            $this->redirect(Yii::app()->createUrl($UrlNext));
                        }
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
                } elseif (isset($_POST ['saveapply'])) {
                    $model->last_updated = date("Y-m-d H:i:s");
                    $model->setIsNewRecord(FALSE);
                    if ($model->update()) {
                        // Gen Code 
                        // M60-1   M60-2   P60-1
                        // ประเภท ปีงบ - ลำดับแยกประเภท
                        // M=Mentor  P=Professor
                        // ปีงบดูจาก last_updated
                        $Scholar->scholarStem->updateByPk($Scholar->scholarStem->id,array("running"=>ConfigWeb::getRunningStem($Scholar->scholarStem->is_taist)));    
                        
                        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                        $criteria->limit = 1;
                        $Scholar = Scholar::model()->find($criteria);
                        $Scholar->status = 'pending_scholarships';
                        $Scholar->last_updated = date("Y-m-d H:i:s");
                        $Scholar->setIsNewRecord(FALSE);
                        $Scholar->update();
                        
                        StemController::actionWord($Scholar->id);
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

}
