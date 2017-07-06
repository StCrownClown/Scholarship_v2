<?php

class InitialData {

    public static function FullNameScholar($key = NULL) {
        $data = array(
            'stem' => 'STEM',
        	'nuirc' => 'NUIRC',
        	'tgist' => 'TGIST',
        );
        return ($key != NULL) ? $data[$key] : $data;
    }

    public static function getDataToSelect2($sql, $addOther = FALSE) {
        $ObjectList = Yii::app()->db->createCommand($sql)->queryAll();
        $dataList = [];
        foreach ($ObjectList as $value) {
            $dataList [$value ['id']] = str_replace("/NULL", "", $value ['name']);
        }
        if ($addOther) {
            $dataList ["0"] = "อื่นๆ / Other";
        }
        return array_filter($dataList, function ($value) {
            return $value !== '';
        });
    }

    public static function getDataToSelect2name($sql, $addOther = FALSE) {
        $ObjectList = Yii::app()->db->createCommand($sql)->queryAll();
        $dataList = [];
        foreach ($ObjectList as $value) {
            $dataList [$value ['name']] = str_replace("/NULL", "", $value ['name']);
        }
        if ($addOther) {
            $dataList ["0"] = "อื่นๆ / Other";
        }
        return array_filter($dataList, function ($value) {
            return $value !== '';
        });
    }

    public static function PageName($key = NULL) {
    	if(Yii::app()->session['scholar_type'] == 'stem') {
	        $data = array(
	            'site/miniuploadphotoprofile' => 'Photo',
	            'site/miniprofile' => 'Profile',
	            'site/minieducation' => 'Education',
	            'site/miniattachment' => 'Attachment',
	            'stem/student' => 'Student',
	            'stem/primaryproject' => 'Primary<br/>Project',
	            'stem/studentproject' => 'Research<br/>Project',
	            'stem/company' => 'Company',
	            'stem/comment' => 'Comment',
	            'site/address' => 'Address',
	            'stem/history' => 'History',
	            'stem/experience' => 'Reward',
	            'stem/working' => 'Working',
	            'site/company' => 'Company',
	            'stem/recommendation' => 'Recommendation',
	            'scholar/index' => 'Finish'
	        );
    	}  	
    	else if (Yii::app()->session['scholar_type'] == 'nuirc') {
    		$data = array(
                    'site/miniuploadphotoprofile' => 'Photo',
                    'nuirc/miniprofile' => 'Profile',
                    'site/minieducation' => 'Education',
                    'nuirc/miniattachment' => 'Attachment',
                    'nuirc/professor' => 'Professor',
                    'nuirc/mentor' => 'Mentor',
                    'nuirc/industrial' => 'Industrial',
                    'nuirc/primaryproject' => 'Primary<br/>Project',
                    'nuirc/studentproject' => 'Research<br/>Project',
                    'nuirc/company' => 'Company',
                    'nuirc/comment' => 'Comment',
                    'site/address' => 'Address',
                    'nuirc/history' => 'History',
                    'nuirc/experience' => 'Reward',
                    'nuirc/working' => 'Working',
                    'site/company' => 'Company',
                    'nuirc/recommendation' => 'Recommendation',
                    'nuirc/verifystudent' => 'Verify Student',
                    'scholar/index' => 'Finish',
    		);
    	}
    	else if (Yii::app()->session['scholar_type'] == 'tgist') {
    		$data = array(
                    'site/miniuploadphotoprofile' => 'Photo',
                    'tgist/miniprofile' => 'Profile',
                    'site/minieducation' => 'Education',
                    'tgist/miniattachment' => 'Attachment',
                    'tgist/professor' => 'Professor',
                    'tgist/mentor' => 'Mentor',
                    'tgist/primaryproject' => 'Primary<br/>Project',
                    'tgist/studentproject' => 'Research<br/>Project',
                    'tgist/company' => 'Company',
                    'tgist/comment' => 'Comment',
                    'site/address' => 'Address',
                    'tgist/history' => 'History',
                    'tgist/experience' => 'Reward',
                    'tgist/working' => 'Working',
                    'site/company' => 'Company',
                    'tgist/recommendation' => 'Recommendation',
                    'tgist/verifystudent' => 'Verify Student',
                    'scholar/index' => 'Finish'
    		);
    	}
        return ($key != NULL) ? $data [$key] : $data;
    }

    public static function PERSON_TYPE($key = NULL) {
        $data = array(
            'student' => 'นักเรียน/นักศึกษา',
            'professor' => 'อาจารย์สถาบัน',
            'industrial' => 'บริษัท/ภาคอุตสาหกรรม',
            'mentor' => 'นักวิจัย'
        );
        return ($key != NULL) ? $data [$key] : $data;
    }

    public static function PERSON_TYPE_EN($key = NULL) {
        $data = array(
            'student' => 'Student',
            'professor' => 'Professor',
            'industrial' => 'Industrial',
            'mentor' => 'Mentor'
        );
        return ($key != NULL) ? $data [$key] : $data;
    }

    public static function CHECKBOX_FUNDING($key = NULL) {
        $data = array(
            'source' => 'ได้รับอนุมัติทุนวิจัยแล้วจาก ',
            'nstda' => 'ได้รับอนุมัติโครงการแล้วจาก สวทช. ',
            'other' => 'อื่นๆ ',
            'none' => 'ไม่มี'
        );
        return ($key != NULL) ? $data [$key] : $data;
    }

    public static function STATUS_COLOR($key = NULL) {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $data = array(
            'stem' => array(
                'draft' => 'default',
                'pending_recommendations' => 'dark',
                'confirm' => 'warning',
                'pending_scholarships' => 'dark',
                'sent' => 'success',),
            'nuirc' => array(
                'draft' => 'default',
                'pending_recommendations' => 'dark',
                'confirm' => 'warning',
                'pending_scholarships' => 'dark',
                'sent' => 'success',),
            'tgist' => array(
                'draft' => 'default',
                'pending_recommendations' => 'dark',
                'confirm' => 'warning',
                'pending_scholarships' => 'dark',
                'sent' => 'success',),
        );
        return ($key != NULL) ? $data [$scholar_type][$key] : $data [$scholar_type];
    }

    public static function STATUS_SEARCH($key = NULL) {
        $data = array(
            '0' => 'ทั้งหมด',
            'draft' => 'เอกสารร่าง',
            'pending_recommendations' => 'รอข้อเสนอแนะ',
            'confirm' => 'ตรวจสอบ',
            'pending_scholarships' => 'ส่งใบสมัคร',
        );
        return ($key != NULL) ? $data [$key] : $data;
    }

    public static function STATUS($key = NULL) {
        $scholar_type = ConfigWeb::getActiveScholarType();
        $data = array(
            'stem' => array(
                'draft' => 'เอกสารร่าง',
                'pending_recommendations' => 'รอข้อเสนอแนะ',
                'confirm' => 'ตรวจสอบ',
                'pending_scholarships' => 'ส่งใบสมัคร',
                'sent' => 'ส่งเรียบร้อย',),
            'nuirc' => array(
                'draft' => 'เอกสารร่าง',
                'pending_recommendations' => 'อยู่ระหว่างการรับรอง',
                'confirm' => 'ตรวจสอบ',
                'pending_scholarships' => 'ส่งใบสมัคร',
                'sent' => 'รับรองเรียบร้อยแล้ว',),
            'tgist' => array(
                'draft' => 'เอกสารร่าง',
                'pending_recommendations' => 'อยู่ระหว่างการรับรอง',
                'confirm' => 'ตรวจสอบ',
                'pending_scholarships' => 'ส่งใบสมัคร',
                'sent' => 'รับรองเรียบร้อยแล้ว',),
        );
        return ($key != NULL) ? $data [$scholar_type][$key] : $data [$scholar_type];
    }

    public static function ACADEMIC_POSITION($key = NULL) {
        $data = array(
            'doctor' => 'ดร. / Doctor',
            'doctor_assistant_professor' => 'ผศ.ดร. / Doctor Assistant Professor',
            'doctor_associate_professor' => 'รศ.ดร. / Doctor Associate Professor',
            'doctor_professor' => 'ศ.ดร. / Doctor Professor',
            'assistant_professor' => 'ผศ. / Assistant Professor',
            'associate_professor' => 'รศ. / Associate Professor',
            'professor' => 'ศ. / Professor',
            'emeritus_professor' => 'ศ.เกียรติคุณ / Emeritus Professor'
        );
        return ($key != NULL) ? $data [$key] : $data;
    }

    public static function YearList($key = NULL, $limit = 50) {
        $returnList = array();
        // $returnList[NULL] = "";
        for ($index = date("Y") + 1; $index >= date("Y") - $limit; $index --) {
            $returnList [$index + 543] = ($index) . " / " . ($index + 543);
        }
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function MonthList($key = NULL) {
        $MONTH = array(
            '1' => "มกราคม / January",
            '2' => "กุมภาพันธ์ / February",
            '3' => "มีนาคม / March",
            '4' => "เมษายน / April",
            '5' => "พฤษภาคม / May",
            '6' => "มิถุนายน / June",
            '7' => "กรกฏาคม / July",
            '8' => "สิงหาคม / August",
            '9' => "กันยายน / September",
            '10' => "ตุลาคม / October",
            '11' => "พฤศจิกายน / November",
            '12' => "ธันวาคม / December"
        );
        return ($key != NULL) ? $MONTH [$key] : $MONTH;
    }

    public static function Project($key = NULL) {
        $person_id = ConfigWeb::getActivePersonId();
        $sql = "SELECT
                    id,
                    name
                FROM project
                WHERE
                    creater_id = $person_id
                ORDER BY name DESC;";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function Education($key = NULL) {
        $person_id = ConfigWeb::getActivePersonId();
        $sql = "SELECT
                            education.id as id,
                        IF(LENGTH(CONCAT(nstdamas_educationlevel.edl_name,' / ',nstdamas_institute.ist_name))>0
                          ,CONCAT(nstdamas_educationlevel.edl_name,' / ',nstdamas_institute.ist_name)
                          ,CONCAT(nstdamas_educationlevel.edl_name,' / ',education.institute_other)) as name
                        FROM education
                        LEFT JOIN nstdamas_educationlevel ON education.educationlevel_id = nstdamas_educationlevel.id
                        LEFT JOIN nstdamas_institute ON education.institute_id = nstdamas_institute.id
                        WHERE
                                person_id =  $person_id
                        ORDER BY nstdamas_educationlevel.id DESC;";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasPrefixStudent($key = NULL) {
        $sql = "SELECT
                    id,
                    IF(LENGTH(TRIM(prf_name_en))>0,CONCAT(prf_name,'/',prf_name_en),prf_name) AS name
                FROM nstdamas_prefix
                ORDER BY prf_name DESC";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }
    
    public static function NstdamasPrefixWd($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(prf_name) AS name
                FROM nstdamas_prefix
                WHERE prf_name not like 'เด็ก%'
                ORDER BY prf_name DESC;";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }
    public static function NstdamasPrefix($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(prf_name,' / ',prf_name_en) AS name
                FROM nstdamas_prefix
                WHERE prf_name not like 'เด็ก%'
                ORDER BY prf_name DESC;";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasMarjor($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(mjr_name,' / ',mjr_name_en) AS name
                FROM
                    nstdamas_major
                WHERE
                    mjr_name NOT LIKE '%(เก่า)%'
                    AND mjr_name NOT LIKE '%(ไม่ใช้งาน)%'
                    ORDER BY mjr_name;";
        $returnList = InitialData::getDataToSelect2($sql, TRUE);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasFaculty($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(fct_name,' / ',fct_name_en) AS name
                FROM
                    nstdamas_faculty
                WHERE
                    fct_name NOT LIKE '%(เก่า)%'
                    AND fct_name NOT LIKE '%(ไม่ใช้งาน)%'
                    ORDER BY fct_name";
        $returnList = InitialData::getDataToSelect2($sql, TRUE);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasOrg($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(org_name,' / ',org_name_en) AS name
                FROM nstdamas_org
                ORDER BY id DESC";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasDepartment($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(dpm_name,' / ',dpm_name_en) AS name
                FROM nstdamas_department
                ORDER BY dpm_name ASC";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }
    
    public static function MasterInstituteStem($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(ist_name, '(',ist_shortname, ')') AS name
                FROM
                    master_institute
                WHERE
                    ist_type='stem'
                ORDER BY ist_priority DESC, ist_name ASC";
        $returnList = InitialData::getDataToSelect2($sql, TRUE);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }
    
    public static function NstdamasInstitute($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(ist_name,' / ',ist_name_en) AS name
                FROM
                    nstdamas_institute
                WHERE
                    ist_type_name='มหาวิทยาลัย'
                    AND ist_name NOT LIKE '%(เก่า)%'
                    AND ist_name NOT LIKE '%(ไม่ใช้งาน)%'
                    AND ist_name NOT LIKE '%โรงเรียน%'
                    AND LOWER(ist_name) NOT LIKE '%school%'
                    AND LOWER(ist_name_en) NOT LIKE '%school%'
                    AND ist_name IS NOT NULL 
                    AND ist_name_en IS NOT NULL
                    ORDER BY CONVERT (ist_name USING tis620) DESC";
        $returnList = InitialData::getDataToSelect2($sql, TRUE);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasCountry($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(cnt_name,' / ',cnt_name_en) AS name
                FROM
                    nstdamas_country
                ORDER BY cnt_name";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasDepartmentByOrg($org_id = NULL, $key = NULL) {
        $sql = "SELECT
                    id,
                    concat(dpm_name,'/',dpm_name_en) AS name
                FROM nstdamas_department
                WHERE org_id=" . $org_id . "
                ORDER BY dpm_name ASC";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasInstituteByCountry($country_id = NULL, $key = NULL) {
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
                AND LOWER(ist_name) NOT LIKE '%school%'
                AND LOWER(ist_name_en) NOT LIKE '%school%'
                AND ist_cnt_id=" . $country_id . "
                AND ist_name IS NOT NULL 
                AND ist_name_en IS NOT NULL
                ORDER BY CONVERT (ist_name USING tis620) DESC";
        $returnList = InitialData::getDataToSelect2($sql, TRUE);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasProvince($key = NULL) {
        $sql = "SELECT
                    id,
                    IF(LENGTH(TRIM(prv_name_en))>0,CONCAT(prv_name,' / ',prv_name_en),prv_name) AS name
                FROM nstdamas_province
                WHERE prv_cnt_id= 187
                ORDER BY prv_name ASC";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasDistrict($province_id = NULL, $key = NULL) {
        $sql = "SELECT DISTINCT
                    zpc_district AS id,
                    IF(LENGTH(TRIM(zpc_district_en))>0,CONCAT(zpc_district,' / ',zpc_district_en),zpc_district) AS name
                FROM nstdamas_zipcode
                WHERE zpc_district IS NOT NULL " . " AND zpc_prv_id=" . $province_id . " " . " ORDER BY zpc_district ASC";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasZipcode($zpc_district = NULL, $key = NULL) {
        $sql = "SELECT DISTINCT
                    zpc_code AS name,
                    zpc_code AS name
                FROM nstdamas_zipcode " . " WHERE zpc_code IS NOT NULL " . "AND zpc_district='$zpc_district' " . " ORDER BY zpc_code ASC";
        $returnList = InitialData::getDataToSelect2name($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasEducationLevel($key = NULL) {
        $sql = "SELECT
                    id,
                    concat(edl_name,' / ',edl_name_en) AS name
                FROM
                    nstdamas_educationlevel
                WHERE
                    edl_name like 'ปริญญา%'
                ORDER BY id";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public static function NstdamasNationality($key = NULL) {
        $sql = "SELECT concat(
                     COALESCE (ntn_name, ''),
                             ' / ',
                             COALESCE (ntn_name_en, '')
                    ) AS name, id
                FROM nstdamas_nationality 
                ORDER BY ntn_name
                ";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

}
