<?php
return array(
    // Yii::app()->params['params']
    'serverDomain' => $_SERVER['HTTP_HOST'],
    'adminUrlSSO' => 'https://app.biotec.or.th/nstdaScholarship_sso/sso.php',
    'adminEmail' => array('jakkrich.changgon@nstda.or.th','siam.nak@biotec.or.th','nstda_stemworkforce@hotmail.com'),
    'adminStemEmail' => 'noreply@nstda.or.th',
    'adminStemTell' => '02-564-7000 ต่อ 1462 ,1461, 1460',
    'adminPassword' => 'admin', // test , webNoSSO
    'debugMail' => true, // send mail to admin. user not mode test
    'errorReportOn' => true, // error_reporting
    'ScholarTypeList' => array('stem', 'test', 'nuirc', 'tgist'),
    'PrivateKeyPWD' => 'scholarship',
    'pathUploads' => '/../uploads/',
    'pathUploadsView' => '/uploads/',
    'GroupPreRegister' => array(
        'stem' => array(
            'professor',
            'mentor',
        ),
        'nuirc' => array(
            'student',
            'mentor',
        ),
        'tgist' => array(
            'student',
            'mentor',
            'professor',
        )
    ),
    'Status' => array(
        'Draft' => 'draft',
        'PendingRecommendations' => 'pending_recommendations',
        'Confirm' => 'confirm',
        'PendingScholarships' => 'pending_scholarships',
        'Send' => 'sent'
    ),
    'StatusPersent' => array(
        'draft' => '25',
        'pending_recommendations' => '50',
        'confirm' => '75',
        'pending_scholarships' => '100',
        'send' => 'send'
    ),
    'IndustrialJoinLink' => 'https://www.nstda.or.th/stemworkforce',
    'ExProjectPlanLink' => '/../template/stem/ExProjectPlan_rev.docx',
    'ProjectPlanLink' => '/../template/stem/ExProjectPlan.docx',
    'IndustrialJoinLink' => '/../template/stem/IndustrialJoin.pdf',
//   Alert Confirm Status
   'EmailTemplateAlertConfirmSubject' => "NSTDA Scholarships - ใบสมัครทุน ##SCHOLARTYPE## ในส่วน นักศึกษาและภาคอุตสาหกรรม กรอกข้อมูลในระบบครบถ้วนแล้ว ",
    'EmailTemplateAlertConfirmBody' => ""
    . "เรียน คุณ##PERSONNAME##<br><br>"
    . "นักศึกษาและภาคอุตสาหกรรม กรอกข้อมูลในระบบครบถ้วนแล้ว<br>"
    . "ขอเรียนแจ้งสถานะใบสมัครของท่าน : \"ตรวจสอบ\"<br><br>"
    . "โปรดเข้าระบบและตรวจสอบความถูกต้องของข้อมูล จนมาถึงส่วนที่ 9 Comment คลิก \"บันทึกและสมัคร\"<br>"
    . "ระบบจะขึ้นเตือนยืนยันความจริงของข้อมูล คลิก \"ยินยอม\" ยืนยันการสมัคร \"ตกลง\"<br>"
    . "สถานะใบสมัคร(ในหน้า Home) จะเป็น \"ส่งใบสมัคร\" จึงเสร็จสิ้นขั้นตอนการสมัครคะ<br><br>"
    . "สามารถเข้าสู่ระบบได้ที่นี่ <a href='##URL##' target='_blank'>Click!!</a><br>"
    . "ขอบคุณค่ะ<br>"
    . "ทีมงาน STEM Workforce",
//    Email Template Recommendation
    'EmailTemplateRecommendationSubject' => "NSTDA Scholarships - ใบสมัครทุน ##SCHOLARTYPE## สำหรับ ##TYPE##",
    'EmailTemplateRecommendationBody' => ""
    . "เรียน คุณ##PERSONNAME##<br>"
    . "=================================<br>"
    . "มีใบสมัครทุน ##SCHOLARTYPE## ที่ต้องดำเนินการ<br>"
    . "โดยสามารถเข้าสู่ระบบได้ที่ Click!!<br>##URL##<br>"
    . "=================================<br>"
    . "<br>"
    . "หากมีปัญหาหรือคำถาม สามารถติดต่อกลับ ##TELLADMIN##<br><br>",

//   NUIRC, TGIST Confirm Alert
   'EmailTemplateAlertConfirmSubject' => "NSTDA Scholarships - ใบสมัครทุน ##SCHOLARTYPE## ในส่วน อาจารย์ที่ปรึกษา, นักวิจัยสวทช. และภาคอุตสาหกรรม กรอกข้อมูลในระบบครบถ้วนแล้ว ",
    'EmailTemplateAlertConfirmBody' => ""
    . "เรียน คุณ##PERSONNAME##<br><br>"
    . "อาจารย์ที่ปรึกษา, นักวิจัยสวทช. และภาคอุตสาหกรรม กรอกข้อมูลในระบบครบถ้วนแล้ว<br>"
    . "ขอเรียนแจ้งสถานะใบสมัครของท่าน : \"ตรวจสอบ\"<br><br>"
    . "โปรดเข้าระบบและตรวจสอบความถูกต้องของข้อมูล จนมาถึงส่วนที่ 11 Attachment คลิก \"บันทึกและสมัคร\"<br>"
    . "ระบบจะขึ้นเตือนยืนยันความจริงของข้อมูล คลิก \"ยินยอม\" ยืนยันการสมัคร \"ตกลง\"<br>"
    . "สถานะใบสมัคร(ในหน้า Home) จะเป็น \"ส่งใบสมัคร\" จึงเสร็จสิ้นขั้นตอนการสมัครคะ<br><br>"
    . "สามารถเข้าสู่ระบบได้ที่นี่ <a href='##URL##' target='_blank'>Click!!</a><br>"
    . "ขอบคุณค่ะ<br>",
    
    'EmailTemplateResetPasswordSubject' => "NSTDA Scholarships - New Password!!",
    'EmailTemplateResetPasswordBody' => ""
    . "Hello : ##EMAIL##<br><br>"
    . "Please keep this e-mail for your records. Your account information is as follows:<br>"
    . "You have successfully reset your password<br/>"
    . "=================================<br>"
    . "Username : ##USERNAME##<br>"
    . "Password : ##PASSWORD##<br>"
    . "=================================<br>"
    . "Goto STEM Login page. <a href='##URL##' target='_blank' data-saferedirecturl='##URL###'>Click!!</a><br>"
    . "<br><br>Regards,<br>"
    . "If you have problems or questions, please contact. ##TELLADMIN##<br><br>",
    
    'EmailTemplateChangeEmailSubject' => "NSTDA Scholarships - ใบสมัครทุน ##SCHOLARTYPE## สำหรับ ##TYPE## (New Email)",
    'EmailTemplateChangeEmailBody' => ""
    . "เรียน คุณ##PERSONNAME##<br>"
    . "=================================<br>"
    . "มีการเปลี่ยน Email ใหม่ กรุณาใช้ Email นี้ในการดำเนินการ<br>"
    . "โดยสามารถเข้าสู่ระบบได้ที่ <a href='##URL##' target='_blank'>Click!!</a><br>"
    . "=================================<br>"
    . "<br>"
    . "หากมีปัญหาหรือคำถาม สามารถติดต่อกลับ ##TELLADMIN##<br><br>",
    
    'footerYear' => '2015',
    'footer' => 'Copyright &copy {year} by NSTDA. All Rights Reserved. ' . Yii::powered(),
    'footerMail' => "jakkrich.changgon@nstda.or.th",
    'footerLink' => '#',
    // For Stem
    'stem_project_begin_min' => '1900-01-01',
    'stem_project_sub_begin_min' => '2017-09-01',
//    'stem_project_begin_max' => '2017-09-01',
    'stem_project_end_max' => '2018-08-31',
    
    'sqlExport' => "
            SELECT 
                year(adddate(sch.last_updated,interval 3 month))+543 AS fyear,
		sch.id AS x_id,
		stu.id AS stu_id,
                pro.id AS pro_id,
                men.id AS men_id,
                ind.id AS ind_id,
                stem.running AS running,
		stem.itap AS itap,

		CONCAT(stu_pre.prf_name,stu.fname,' ' ,stu.lname) AS stu_fullname,
		stu.id_card AS stu_idcard,
                stu.id_card_created AS stu_id_card_created,
                stu.id_card_expired AS stu_id_card_expired,
		stu.birthday AS stu_birthday,
		CONCAT(IF(stu_add.number='','',CONCAT('เลขที่',stu_add.number)), IF(stu_add.building='','',CONCAT(' ','อาคาร',stu_add.building)), IF(stu_add.floor='','',CONCAT(' ','ชั้น',stu_add.floor)), IF(stu_add.room='','',CONCAT(' ','ห้อง',stu_add.room)), IF(stu_add.village='','',CONCAT(' ','หมู่บ้าน',stu_add.village)), IF(stu_add.moo='','',CONCAT(' ','หมู่',stu_add.moo)), IF(stu_add.alley='','',CONCAT(' ','ซอย',stu_add.alley)), IF(stu_add.road='','',CONCAT(' ','ถนน',stu_add.road)), IF(stu_add.subdistrict='','',CONCAT(' ','ตำบล',stu_add.subdistrict)), IF(stu_add.district='','',CONCAT(' ','อำเภอ',stu_add.district)), IF(stu_add_pro.prv_name='','',CONCAT(' ','จังหวัด',stu_add_pro.prv_name)), ' ',stu_add.zipcode) AS stu_address,
		stu.mobile AS stu_mobile,
		stu.email AS stu_email,
		
		stu_edu.status AS stu_edu_status,
		stu_edu_level.edl_name AS stu_edu_level,
		stu_edu.avg_gpa AS stu_edu_gpa,
		IF(stu_edu.institute_id is NULL, stu_edu.institute_other, stu_edu_ins.ist_name) AS stu_edu_institute,
		IF(stu_edu.faculty_id is NULL, stu_edu.faculty_other, stu_edu_fac.fct_name) AS stu_edu_faculty,
		IF(stu_edu.major_id is NULL, stu_edu.major_other, stu_edu_maj.mjr_name) AS stu_edu_major,
		stu_edu.year_enrolled AS stu_edu_year_enrolled,
		stu_edu.year_graduated AS stu_edu_year_graduated,
		stem.student_before_gpa AS stu_edu_gpa_before,
		

		stem.is_history AS is_history,

		stem.portfolio_thesis AS portfolio_thesis,
		
		stem.portfolio_journal_international AS portfolio_journal_international,
		stem.portfolio_journal_international_desc AS portfolio_journal_international_desc,
		stem.portfolio_journal_international_amount AS portfolio_journal_international_amount,
		stem.portfolio_journal_incountry AS portfolio_journal_incountry,
		stem.portfolio_journal_incountry_desc AS portfolio_journal_incountry_desc,
		stem.portfolio_journal_incountry_amount AS portfolio_journal_incountry_amount,
		stem.portfolio_patent AS portfolio_patent,
		stem.portfolio_patent_desc AS portfolio_patent_desc,
		stem.portfolio_patent_amount AS portfolio_patent_amount,
		stem.portfolio_prototype AS portfolio_prototype,
		stem.portfolio_prototype_desc AS portfolio_prototype_desc,
		stem.portfolio_prototype_amount AS portfolio_prototype_amount,
		stem.portfolio_conference_international AS portfolio_conference_international,
		stem.portfolio_conference_international_desc AS portfolio_conference_international_desc,
		stem.portfolio_conference_international_amount AS portfolio_conference_international_amount,
		stem.portfolio_conference_incountry AS portfolio_conference_incountry,
		stem.portfolio_conference_incountry_desc AS portfolio_conference_incountry_desc,
		stem.portfolio_conference_incountry_amount AS portfolio_conference_incountry_amount,
		stem.portfolio_award AS portfolio_award,
		stem.portfolio_award_desc AS portfolio_award_desc,
		stem.portfolio_award_amount AS portfolio_award_amount,
		stem.portfolio_other AS portfolio_other,
		stem.portfolio_other_desc AS portfolio_other_desc,
		stem.portfolio_other_amount AS portfolio_other_amount,
		stem.portfolio_other_text AS portfolio_other_text,

		stem.is_work AS is_work,
		stem.work_position AS work_position,
		stem.work_company AS work_company,
		stem.workwithproject_text1 AS workwithproject_text1,
		stem.workwithproject_text2 AS workwithproject_text2,
		stem.workwithproject_text3 AS workwithproject_text3,
		
		IF(sch.professor_id is NULL,CONCAT(men_pre.prf_name,men.fname,' ' ,men.lname),CONCAT(pro_pre.prf_name,pro.fname,' ' ,pro.lname)) AS pro_men_fullname,
		IF(sch.professor_id is NULL,men.position,pro.position) AS pro_men_position,
                IF(sch.professor_id is NULL,men.academic_position,pro.academic_position) AS pro_men_academic_position,
		IF(sch.professor_id is NULL,CONCAT(men_org.org_name,' ',men_dep.dpm_name),CONCAT(pro_ins.ist_name,' ',pro_fac.fct_name,' ',pro_maj.mjr_name)) AS pro_men_workarea,
		IF(sch.professor_id is NULL,men.mobile,pro.mobile) AS pro_men_mobile,
		IF(sch.professor_id is NULL,men.email,pro.email) AS pro_men_email,
		IF(sch.professor_id is NULL,men.expert,pro.expert) AS pro_men_expert,
                
		IF(sch.professor_id is NULL,men_edu_level.edl_name,pro_edu_level.edl_name) AS pro_men_edu_level,
		IF(sch.professor_id is NULL,IF(men_edu.institute_id is NULL, men_edu.institute_other, men_edu_ins.ist_name),IF(pro_edu.institute_id is NULL, pro_edu.institute_other, pro_edu_ins.ist_name)) AS pro_men_edu_institute,
		IF(sch.professor_id is NULL,IF(men_edu.faculty_id is NULL, men_edu.faculty_other, men_edu_fac.fct_name),IF(pro_edu.faculty_id is NULL, pro_edu.faculty_other, pro_edu_fac.fct_name)) AS pro_men_edu_faculty,
		IF(sch.professor_id is NULL,IF(men_edu.major_id is NULL, men_edu.major_other, men_edu_maj.mjr_name),IF(pro_edu.major_id is NULL, pro_edu.major_other, pro_edu_maj.mjr_name)) AS pro_men_edu_major,
		IF(sch.professor_id is NULL,men_edu_cou.cnt_name,pro_edu_cou.cnt_name) AS pro_men_edu_country,		
		IF(sch.professor_id is NULL,men_edu.year_enrolled,pro_edu.year_enrolled) AS pro_men_edu_enrolled,
		IF(sch.professor_id is NULL,men_edu.year_graduated,pro_edu.year_graduated) AS pro_men_edu_graduated,

		ind.industrial AS ind_name,
		CONCAT(IF(ind_add.number='','',CONCAT('เลขที่',ind_add.number)), IF(ind_add.building='','',CONCAT(' ','อาคาร',ind_add.building)), IF(ind_add.floor='','',CONCAT(' ','ชั้น',ind_add.floor)), IF(ind_add.room='','',CONCAT(' ','ห้อง',ind_add.room)), IF(ind_add.village='','',CONCAT(' ','หมู่บ้าน',ind_add.village)), IF(ind_add.moo='','',CONCAT(' ','หมู่',ind_add.moo)), IF(ind_add.alley='','',CONCAT(' ','ซอย',ind_add.alley)), IF(ind_add.road='','',CONCAT(' ','ถนน',ind_add.road)), IF(ind_add.subdistrict='','',CONCAT(' ','ตำบล',ind_add.subdistrict)), IF(ind_add.district='','',CONCAT(' ','อำเภอ',ind_add.district)), IF(ind_add_pro.prv_name='','',CONCAT(' ','จังหวัด',ind_add_pro.prv_name)), ' ', ind_add.zipcode) AS ind_address,
		CONCAT(ind_pre.prf_name,ind.fname,' ' ,ind.lname) AS ind_fullname,
		ind.mobile AS ind_mobile,
		ind.email AS ind_email,
		ind.industrial_type_manufacture AS ind_type_manufacture,
		ind.industrial_type_export AS ind_type_export,
		ind.industrial_type_service AS ind_type_service,
		ind.industrial_type_description AS ind_type_description,

		stem.industrial_incash_salary AS ind_incash_salary,
		stem.industrial_incash_salary_cost AS ind_incash_salary_cost,
		stem.industrial_incash_rents AS ind_incash_rents,
		stem.industrial_incash_rents_cost AS ind_incash_rents_cost,
		stem.industrial_incash_traveling AS ind_incash_traveling,
		stem.industrial_incash_traveling_cost AS ind_incash_traveling_cost,
		stem.industrial_incash_other AS ind_incash_other,
		stem.industrial_incash_other_cost AS ind_incash_other_cost,
		stem.industrial_incash_other_text AS ind_incash_other_text,
		stem.industrial_incash_other2 AS ind_incash_other2,
		stem.industrial_incash_other2_cost AS ind_incash_other2_cost,
		stem.industrial_incash_other2_text AS ind_incash_other2_text,
		stem.industrial_inkind_equipment AS ind_inkind_equipment,
		stem.industrial_inkind_equipment_cost AS ind_inkind_equipment_cost,
		stem.industrial_inkind_other AS ind_inkind_other,
		stem.industrial_inkind_other_cost AS ind_inkind_other_cost,
		stem.industrial_inkind_other_text AS ind_inkind_other_text,
		stem.industrial_inkind_other2 AS ind_inkind_other2,
		stem.industrial_inkind_other2_cost AS ind_inkind_other2_cost,
		stem.industrial_inkind_other2_text AS ind_inkind_other2_text,

		stem2.industrial_support_desc AS ind_support_desc,
		
		prj.name AS prj_name,
		prj.objective AS prj_objective,
		prj.scope AS prj_scope,
		prj.begin AS prj_begin,
		prj.end AS prj_end,
		prj.funding AS prj_funding,
		prj.funding_name AS prj_funding_name,
		prj.funding_code AS prj_funding_code,
		prj.funding_etc AS prj_funding_etc,
		prj.budget AS prj_budget,

		stem.project_name AS prj_stu_name,
		stem.objective AS prj_stu_objective,
		stem.project_begin AS prj_stu_begin,
		stem.project_end AS prj_stu_end,
		stem.expect AS prj_stu_expect,
		stem.cooperation AS prj_stu_cooperation,

		stem.effect_new AS effect_new,
		stem.effect_new_desc AS effect_new_desc,
		stem.effect_cost AS effect_cost,
		stem.effect_cost_desc AS effect_cost_desc,
		stem.effect_quality AS effect_quality,
		stem.effect_quality_desc AS effect_quality_desc,
		stem.effect_environment AS effect_environment,
		stem.effect_environment_desc AS effect_environment_desc,
		stem.effect_other AS effect_other,
		stem.effect_other_desc AS effect_other_desc,
		stem.effect_other_text AS effect_other_text,

		stem.relevance_automotive AS relevance_automotive,
		stem.relevance_automotive_desc AS relevance_automotive_desc,
		stem.relevance_electronics AS relevance_electronics,
		stem.relevance_electronics_desc AS relevance_electronics_desc,
		stem.relevance_tourism AS relevance_tourism,
		stem.relevance_tourism_desc AS relevance_tourism_desc,
		stem.relevance_agriculture AS relevance_agriculture,
		stem.relevance_agriculture_desc AS relevance_agriculture_desc,
		stem.relevance_food AS relevance_food,
		stem.relevance_food_desc AS relevance_food_desc,
		stem.relevance_robotics AS relevance_robotics,
		stem.relevance_robotics_desc AS relevance_robotics_desc,
		stem.relevance_aviation AS relevance_aviation,
		stem.relevance_aviation_desc AS relevance_aviation_desc,
		stem.relevance_biofuels AS relevance_biofuels,
		stem.relevance_biofuels_desc AS relevance_biofuels_desc,
		stem.relevance_digital AS relevance_digital,
		stem.relevance_digital_desc AS relevance_digital_desc,
		stem.relevance_medical AS relevance_medical,
		stem.relevance_medical_desc AS relevance_medical_desc,

		stem.incash_fee AS incash_fee,
		stem.incash_fee_cost AS incash_fee_cost,
		stem.incash_fee_source AS incash_fee_source,
		stem.incash_monthly AS incash_monthly,
		stem.incash_monthly_cost AS incash_monthly_cost,
		stem.incash_monthly_source AS incash_monthly_source,
		stem.incash_other AS incash_other,
		stem.incash_other_cost AS incash_other_cost,
		stem.incash_other_source AS incash_other_source,
		stem.incash_other_text AS incash_other_text,
		stem.incash_other2 AS incash_other2,
		stem.incash_other2_cost AS incash_other2_cost,
		stem.incash_other2_source AS incash_other2_source,
		stem.incash_other2_text AS incash_other2_text,

		stem.inkind_other AS inkind_other,
		stem.inkind_other_cost AS inkind_other_cost,
		stem.inkind_other_source AS inkind_other_source,
		stem.inkind_other_text AS inkind_other_text,
		stem.inkind_other2 AS inkind_other2,
		stem.inkind_other2_cost AS inkind_other2_cost,
		stem.inkind_other2_source AS inkind_other2_source,
		stem.inkind_other2_text AS inkind_other2_text,

		pro_com.`comment` AS pro_comment,
		men_com.`comment` AS men_comment,
		ind_com.`comment` AS ind_comment,

		IF(IF(sch.professor_id is NULL,men.cv_path,pro.cv_path) is NULL,0,1) AS att_cv,
		IF(stem.professor_mentor_attachment_project_path is NULL,0,1) AS att_project,
		IF(stem.professor_mentor_attachment_other_path is NULL,0,1) AS att_pro_men_other,
		
		IF(stem.student_transcript_path is NULL,0,1) AS att_transcript,
		IF(stu.copy_id_card_path is NULL,0,1) AS att_id_card,
		IF(stem.student_portfolio_path is NULL,0,1) AS att_portfolio,
                IF(stem.student_card_path is NULL,0,1) AS att_stu_card, 
		IF(stem.student_attachment_other_path is NULL,0,1) AS att_stu_other, 
		IF(stem.student_attachment_other2_path is NULL,0,1) AS  att_stu_other2,
		IF(stem.industrial_certificate_path is NULL,0,1) AS att_certificate,
		IF(stem.industrial_join_path is NULL,0,1) AS att_industrial_join,
		IF(stem.industrial_attachment_other_path is NULL,0,1) AS att_ind_other,

		sch.status AS status

            FROM scholar sch LEFT JOIN person stu ON sch.student_id=stu.id
                    LEFT JOIN person men ON sch.mentor_id=men.id 
                    LEFT JOIN person ind ON sch.industrial_id=ind.id
                    LEFT JOIN person pro ON sch.professor_id=pro.id
                    LEFT JOIN education stu_edu ON sch.education_id=stu_edu.id
                    LEFT JOIN education pro_edu ON pro.id=pro_edu.person_id 
                    LEFT JOIN education men_edu ON men.id=men_edu.person_id
                    LEFT JOIN nstdamas_educationlevel stu_edu_level ON stu_edu.educationlevel_id=stu_edu_level.id
                    LEFT JOIN nstdamas_educationlevel pro_edu_level ON pro_edu.educationlevel_id=pro_edu_level.id
                    LEFT JOIN nstdamas_educationlevel men_edu_level ON men_edu.educationlevel_id=men_edu_level.id
                    LEFT JOIN nstdamas_institute stu_edu_ins ON stu_edu.institute_id=stu_edu_ins.id
                    LEFT JOIN nstdamas_institute pro_edu_ins ON pro_edu.institute_id=pro_edu_ins.id
                    LEFT JOIN nstdamas_institute men_edu_ins ON men_edu.institute_id=men_edu_ins.id
                    LEFT JOIN nstdamas_faculty stu_edu_fac ON stu_edu.faculty_id=stu_edu_fac.id
                    LEFT JOIN nstdamas_faculty pro_edu_fac ON pro_edu.faculty_id=pro_edu_fac.id
                    LEFT JOIN nstdamas_faculty men_edu_fac ON men_edu.faculty_id=men_edu_fac.id
                    LEFT JOIN nstdamas_major stu_edu_maj ON stu_edu.major_id=stu_edu_maj.id
                    LEFT JOIN nstdamas_major pro_edu_maj ON pro_edu.major_id=pro_edu_maj.id
                    LEFT JOIN nstdamas_major men_edu_maj ON men_edu.major_id=men_edu_maj.id
                    LEFT JOIN nstdamas_country pro_edu_cou ON pro_edu.country_id=pro_edu_cou.id
                    LEFT JOIN nstdamas_country men_edu_cou ON men_edu.country_id=men_edu_cou.id
                    LEFT JOIN scholar_stem stem ON sch.scholar_stem_id=stem.id
                    LEFT JOIN scholar_stem2 stem2 ON stem.scholar_stem2_id=stem2.id
                    LEFT JOIN nstdamas_prefix stu_pre ON stu.prefix_id=stu_pre.id
                    LEFT JOIN nstdamas_prefix pro_pre ON pro.prefix_id=pro_pre.id
                    LEFT JOIN nstdamas_prefix men_pre ON men.prefix_id=men_pre.id
                    LEFT JOIN nstdamas_prefix ind_pre ON ind.prefix_id=ind_pre.id
                    LEFT JOIN address stu_add ON stu.id=stu_add.person_id
                    LEFT JOIN address ind_add ON ind.id=ind_add.person_id
                    LEFT JOIN nstdamas_province stu_add_pro ON stu_add.province_id=stu_add_pro.id
                    LEFT JOIN nstdamas_province ind_add_pro ON ind_add.province_id=ind_add_pro.id
                    LEFT JOIN nstdamas_org men_org ON men.org_id=men_org.id
                    LEFT JOIN nstdamas_department men_dep ON men.department_id=men_dep.id
                    LEFT JOIN master_institute pro_ins ON pro.institute_id=pro_ins.id
                    LEFT JOIN nstdamas_faculty pro_fac ON pro.faculty_id=pro_fac.id
                    LEFT JOIN nstdamas_major pro_maj ON pro.major_id=pro_maj.id
                    LEFT JOIN project prj ON stem.project_id=prj.id 
                    LEFT JOIN `comment` pro_com ON pro.id=pro_com.person_id AND sch.id=pro_com.scholar_id
                    LEFT JOIN `comment` men_com ON men.id=men_com.person_id AND sch.id=men_com.scholar_id
                    LEFT JOIN `comment` ind_com ON ind.id=ind_com.person_id AND sch.id=ind_com.scholar_id

            WHERE sch.type='stem'
            AND stu_add.type='contact'
            AND IF(sch.professor_id is NULL,men_edu.is_highest=true, pro_edu.is_highest=true)
            AND sch.`status` IN ('pending_scholarships') ",
    'sqlExportQuickReport' => "
        SELECT 
            IF(sch.professor_id is NULL,CONCAT(men_pre.prf_name,men.fname,' ' ,men.lname),CONCAT(pro_pre.prf_name,pro.fname,' ' ,pro.lname)) AS pro_men_fullname, 
            IF(sch.professor_id is NULL,CONCAT(men_org.org_name,' ',men_dep.dpm_name),CONCAT(pro_ins.ist_name,' ',pro_fac.fct_name,' ',pro_maj.mjr_name)) AS pro_men_workarea,
            CONCAT('''', IF(sch.professor_id is NULL,men.mobile,pro.mobile)) AS pro_men_mobile,
            IF(sch.professor_id is NULL,men.email,pro.email) AS pro_men_email,

            CONCAT(stu_pre.prf_name,stu.fname,' ' ,stu.lname) AS stu_fullname,
            CONCAT('''', stu.id_card) AS stu_idcard,
            CONCAT('''', stu.mobile) AS stu_mobile,
            stu.email AS stu_email,

            ind.industrial AS ind_name,
            CONCAT(ind_pre.prf_name,ind.fname,' ' ,ind.lname) AS ind_fullname,
            CONCAT('''', ind.mobile) AS ind_mobile,
            ind.email AS ind_email,
            ind.industrial_type_manufacture AS ind_type_manufacture,
            ind.industrial_type_export AS ind_type_export,
            ind.industrial_type_service AS ind_type_service,
            ind.industrial_type_description AS ind_type_description,

            sch.status AS status,
            sch.last_updated AS last_updated
          FROM scholar sch LEFT JOIN person stu ON sch.student_id=stu.id
           LEFT JOIN person men ON sch.mentor_id=men.id 
           LEFT JOIN person ind ON sch.industrial_id=ind.id
           LEFT JOIN person pro ON sch.professor_id=pro.id
           LEFT JOIN scholar_stem stem ON sch.scholar_stem_id=stem.id
           LEFT JOIN nstdamas_prefix stu_pre ON stu.prefix_id=stu_pre.id
           LEFT JOIN nstdamas_prefix pro_pre ON pro.prefix_id=pro_pre.id
           LEFT JOIN nstdamas_prefix men_pre ON men.prefix_id=men_pre.id
           LEFT JOIN nstdamas_prefix ind_pre ON ind.prefix_id=ind_pre.id
           LEFT JOIN nstdamas_org men_org ON men.org_id=men_org.id
           LEFT JOIN nstdamas_department men_dep ON men.department_id=men_dep.id
           LEFT JOIN master_institute pro_ins ON pro.institute_id=pro_ins.id
           LEFT JOIN nstdamas_faculty pro_fac ON pro.faculty_id=pro_fac.id
           LEFT JOIN nstdamas_major pro_maj ON pro.major_id=pro_maj.id
          WHERE sch.type='stem' ",
    'sqlExportStem' => "
        SELECT 
            year(adddate(sch.last_updated,interval 3 month))+543 AS fyear,
            sch.id AS sch_id,
            stem.running AS running,
            CONVERT(SUBSTRING(stem.running, 5) ,UNSIGNED INTEGER) as int_running,
            IF(sch.professor_id is NULL,CONCAT(men_pre.prf_name,men.fname,' ' ,men.lname),CONCAT(pro_pre.prf_name,pro.fname,' ' ,pro.lname)) AS pro_men_fullname,
            CONCAT(stu_pre.prf_name,stu.fname,' ' ,stu.lname) AS stu_fullname,
            IF(stu_edu.institute_id is NULL, stu_edu.institute_other, stu_edu_ins.ist_name) AS stu_edu_institute,
            IF(stu_edu.faculty_id is NULL, stu_edu.faculty_other, stu_edu_fac.fct_name) AS stu_edu_faculty,
            IF(stu_edu.major_id is NULL, stu_edu.major_other, stu_edu_maj.mjr_name) AS stu_edu_major,
            stu_edu_level.edl_name AS stu_edu_level,

            stem.student_before_gpa AS stu_edu_gpa_before,
            stu_edu.avg_gpa AS stu_edu_gpa,

            stem.project_name AS prj_stu_name,
            '' AS prj_stu_range,
            stem.project_begin AS prj_stu_begin,
            stem.project_end AS prj_stu_end,
            (stem.incash_fee_cost+stem.incash_monthly_cost+stem.incash_other_cost+stem.incash_other2_cost+stem.inkind_other_cost+stem.inkind_other2_cost) AS prj_stu_sum,

            IF(prj.funding='source',prj.funding_name,'') AS prj_source,
            IF(prj.funding='nstda',prj.funding_code,'') AS prj_nstda,
            IF(prj.funding='other',prj.funding_etc,'') AS prj_other,
            IF(prj.funding='none','none','') AS prj_none,

            ind.industrial AS ind_name,
            (stem.industrial_incash_salary_cost+stem.industrial_incash_rents_cost+stem.industrial_incash_traveling_cost+stem.industrial_incash_other_cost+stem.industrial_incash_other2_cost+stem.industrial_inkind_equipment_cost+stem.industrial_inkind_other_cost+stem.industrial_inkind_other2_cost) AS ind_sum,
						
            ind.industrial_type_description AS ind_desc,
            IF(stem.relevance_automotive=1,stem.relevance_automotive_desc,'') AS relevance_automotive,
            IF(stem.relevance_electronics=1,stem.relevance_electronics_desc,'')	AS relevance_electronics,
            IF(stem.relevance_tourism=1,stem.relevance_tourism_desc,'')	AS relevance_tourism,
            IF(stem.relevance_agriculture=1,stem.relevance_agriculture_desc,'')	AS relevance_agriculture,	
            IF(stem.relevance_food=1,stem.relevance_food_desc,'') AS relevance_food,
            IF(stem.relevance_robotics=1,stem.relevance_robotics_desc,'') AS relevance_robotics,
            IF(stem.relevance_aviation=1,stem.relevance_aviation_desc,'') AS relevance_aviation,
            IF(stem.relevance_biofuels=1,stem.relevance_biofuels_desc,'') AS relevance_biofuels,
            IF(stem.relevance_digital=1,stem.relevance_digital_desc,'') AS relevance_digital,
            IF(stem.relevance_medical=1,stem.relevance_medical_desc,'') AS relevance_medical,
            IF(stem.effect_new=1,stem.effect_new_desc,'') AS effect_new,
            IF(stem.effect_cost=1,stem.effect_cost_desc,'') AS effect_cost,
            IF(stem.effect_quality=1,stem.effect_quality_desc,'') AS effect_quality,
            IF(stem.effect_environment=1,stem.effect_environment_desc,'') AS effect_environment,
            IF(stem.effect_other=1,CONCAT(stem.effect_other_text,' :: ',stem.effect_other_desc),'') AS effect_other,

            sch.status AS status

        FROM scholar sch LEFT JOIN person stu ON sch.student_id=stu.id
            LEFT JOIN person men ON sch.mentor_id=men.id 
            LEFT JOIN person ind ON sch.industrial_id=ind.id
            LEFT JOIN person pro ON sch.professor_id=pro.id
            LEFT JOIN education stu_edu ON sch.education_id=stu_edu.id
            LEFT JOIN education pro_edu ON pro.id=pro_edu.person_id 
            LEFT JOIN education men_edu ON men.id=men_edu.person_id
            LEFT JOIN nstdamas_educationlevel stu_edu_level ON stu_edu.educationlevel_id=stu_edu_level.id
            LEFT JOIN nstdamas_educationlevel pro_edu_level ON pro_edu.educationlevel_id=pro_edu_level.id
            LEFT JOIN nstdamas_educationlevel men_edu_level ON men_edu.educationlevel_id=men_edu_level.id
            LEFT JOIN nstdamas_institute stu_edu_ins ON stu_edu.institute_id=stu_edu_ins.id
            LEFT JOIN nstdamas_institute pro_edu_ins ON pro_edu.institute_id=pro_edu_ins.id
            LEFT JOIN nstdamas_institute men_edu_ins ON men_edu.institute_id=men_edu_ins.id
            LEFT JOIN nstdamas_faculty stu_edu_fac ON stu_edu.faculty_id=stu_edu_fac.id
            LEFT JOIN nstdamas_faculty pro_edu_fac ON pro_edu.faculty_id=pro_edu_fac.id
            LEFT JOIN nstdamas_faculty men_edu_fac ON men_edu.faculty_id=men_edu_fac.id
            LEFT JOIN nstdamas_major stu_edu_maj ON stu_edu.major_id=stu_edu_maj.id
            LEFT JOIN nstdamas_major pro_edu_maj ON pro_edu.major_id=pro_edu_maj.id
            LEFT JOIN nstdamas_major men_edu_maj ON men_edu.major_id=men_edu_maj.id
            LEFT JOIN nstdamas_country pro_edu_cou ON pro_edu.country_id=pro_edu_cou.id
            LEFT JOIN nstdamas_country men_edu_cou ON men_edu.country_id=men_edu_cou.id
            LEFT JOIN scholar_stem stem ON sch.scholar_stem_id=stem.id
            LEFT JOIN nstdamas_prefix stu_pre ON stu.prefix_id=stu_pre.id
            LEFT JOIN nstdamas_prefix pro_pre ON pro.prefix_id=pro_pre.id
            LEFT JOIN nstdamas_prefix men_pre ON men.prefix_id=men_pre.id
            LEFT JOIN nstdamas_prefix ind_pre ON ind.prefix_id=ind_pre.id
            LEFT JOIN address stu_add ON stu.id=stu_add.person_id
            LEFT JOIN address ind_add ON ind.id=ind_add.person_id
            LEFT JOIN nstdamas_province stu_add_pro ON stu_add.province_id=stu_add_pro.id
            LEFT JOIN nstdamas_province ind_add_pro ON ind_add.province_id=ind_add_pro.id
            LEFT JOIN nstdamas_org men_org ON men.org_id=men_org.id
            LEFT JOIN nstdamas_department men_dep ON men.department_id=men_dep.id
            LEFT JOIN master_institute pro_ins ON pro.institute_id=pro_ins.id
            LEFT JOIN nstdamas_faculty pro_fac ON pro.faculty_id=pro_fac.id
            LEFT JOIN nstdamas_major pro_maj ON pro.major_id=pro_maj.id
            LEFT JOIN project prj ON stem.project_id=prj.id 
            LEFT JOIN `comment` pro_com ON pro.id=pro_com.person_id AND sch.id=pro_com.scholar_id
            LEFT JOIN `comment` men_com ON men.id=men_com.person_id AND sch.id=men_com.scholar_id
            LEFT JOIN `comment` ind_com ON ind.id=ind_com.person_id AND sch.id=ind_com.scholar_id

        WHERE sch.type='stem'
            AND stu_add.type='contact'
            AND IF(sch.professor_id is NULL,men_edu.is_highest=true, pro_edu.is_highest=true)
            AND sch.`status` IN ('pending_scholarships')  ",
    'sqlExportHistory' => "
        SELECT
            edu.edl_name AS history_edu_level,
            his.name AS history_name,
            his.source AS history_source,
            his.description AS history_description,
            his.begin AS history_begin,
            his.end AS history_end
        FROM
            scholar_history his
        LEFT JOIN nstdamas_educationlevel edu ON his.educationlevel_id=edu.id
        WHERE person_id=##PERSON_ID##
        ORDER BY last_updated DESC",
);
