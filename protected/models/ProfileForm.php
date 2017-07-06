<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ProfileForm extends CActiveRecord {
    
    public $id;
    public $prefix_id;
    public $academic_position;
    public $fname;
    public $lname;
    public $mobile;
    public $email;
    public $phone;
    public $fax;
    public $position;
    public $institute_id;
    public $institute_other;
    public $faculty_id;
    public $faculty_other;
    public $major_id;
    public $major_other;
    public $expert;
    public $org_id;
    public $department_id;
    public $id_card;
    public $id_card_created;
    public $id_card_expired;
    public $is_taist;
    public $birthday;
    public $age;
    public $nationality_id;
    public $parent_fname;
    public $parent_lname;
    public $parent_mobile;
    public $parent_relationship;
    public $parent2_fname;
    public $parent2_lname;
    public $parent2_mobile;
    public $parent2_relationship;
    private $_identity;

    public $nickname;
    public $management_position;
    public $industrial;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'person';
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('is_taist', 'safe'),
            array('prefix_id, fname, lname, mobile,'
                . 'email', 'required'),
            array('institute_id, faculty_id, major_id, position, expert', 'reqByPersonType',
                ['professor',
                    'stem'=>array(),
                    'nuirc'=>array(
                        'position'
                    ),
                    'tgist'=>array(
                        'position'
                    ),
                ]),
            array('org_id, department_id, position, expert', 'reqByPersonType', 
                ['mentor',
                    'stem'=>array(),
                    'nuirc'=>array(),
                    'tgist'=>array(),
                ]),
            array('nationality_id, id_card, id_card_created, id_card_expired, fname_en, lname_en, birthday'
                . ', parent_fname, parent_lname, parent_mobile, parent_relationship'
                . ', parent2_fname, parent2_lname, parent2_mobile, parent2_relationship', 'reqByPersonType', 
                ['student',
                    'stem'=>array(),
                    'nuirc'=>array(
                        'parent_fname', 
                        'parent_lname',
                        'parent_mobile', 
                        'parent_relationship', 
                        'parent2_fname', 
                        'parent2_lname', 
                        'parent2_mobile',
                        'parent2_relationship'),
                    'tgist'=>array(
                        'parent_fname', 
                        'parent_lname',
                        'parent_mobile', 
                        'parent_relationship', 
                        'parent2_fname', 
                        'parent2_lname', 
                        'parent2_mobile',
                        'parent2_relationship'),
                ]),
            array('institute_other, faculty_other, major_other', 'length', 'max' => 200),
            array('institute_other', 'reqIsOther', 'institute_id'),
            array('faculty_other', 'reqIsOther', 'faculty_id'),
            array('major_other', 'reqIsOther', 'major_id'),
            array('position', 'length', 'max' => 120),
            array('fname, lname, email', 'length', 'max' => 100),
            array('phone, fax', 'length', 'max' => 20),
            array('phone, fax', 'reqNumberAndSharp'),
            array('mobile,parent_mobile,parent2_mobile', 'length', 'max' => 10, 'min' => 10),
            array('mobile,parent_mobile,parent2_mobile', 'numerical', 'integerOnly'=>true),
            array('id_card', 'length', 'max' => 30),
            array('id_card', 'uniqueIdCard', 'id_card', 'id'),
//            array('id_card', 'unique',
//                'className' => 'Person',
//                'attributeName' => 'id_card',
//                'message' => 'เลขบัตรประชาชน นี้ไม่สามารถใช้ได้</br>'
//                . 'ID Card is already exists.'),
            array('id_card', 'CheckDigiIdCard', 'nationality_id'),
            array('fname_en, lname_en', 'match',
                'pattern' => '/^[a-zA-Z._ \s]+$/',
                'message' => 'ต้องกรอกเป็นภาษาอังกฤษเท่านั้น'),
            array('fname, lname', 'match',
                'pattern' => '/^[ก-เ._ \s]+$/',
                'message' => 'ต้องกรอกเป็นภาษาไทยเท่านั้น'),

            array('nickname', 'length', 'max' => 20),
            array('nickname, management_position, industrial, academic_position', 'safe'),
            array('management_position', 'length', 'max' => 100),
            array('industrial', 'length', 'max' => 100),
            
        );
    }
    public function uniqueIdCard($attribute, $params) {
        $criteria = new CDbCriteria;
        $criteria->condition = "id_card = '" . $this->$params[0] . "'";
        $criteria->limit = 1;
        $user = Person::model()->find($criteria);
        
        if(!empty($user)){
            if($user->id != $this->$params[1]){
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' นี้ไม่สามารถใช้ได้</br>ID Card is already exists.');
            }
        }
    }
    
    public function CheckDigiIdCard($attribute, $params) {
        $pid = $this->$attribute;
        $sum = 0;
        if ($this->$params[0] == '17') {
            if (strlen($pid) != 13)
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ถูกต้อง');
            if (strlen($pid) == 13) {
                for ($i = 0, $sum = 0; $i < 12; $i++)
                    $sum += (int) ($pid[$i]) * (13 - $i);
                if ((11 - ($sum % 11)) % 10 != (int) ($pid[12]))
                    $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ถูกต้อง');
            }
        }
    }
    
    public function reqNumberAndSharp($attribute, $params) {
        if (strlen($this->$attribute) > 0) {
            $num = str_replace("#", "", $this->$attribute);
            if (!is_numeric($num) && !empty($num) || strlen($num) < 1) {
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ถูกต้อง');
            }
        }
    }

    public function reqIsOther($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        if ($this->$params[0] == '0' && $person_type == 'professor' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }

    public function reqByPersonType($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        $scholar_type =  Yii::app()->session['scholar_type'];
        if ($this->$attribute == NULL && $person_type == $params[0][0] && $this->$attribute != '0') {
            if (array_key_exists($scholar_type,$params[0])) {
                if (!in_array($attribute,$params[0][$scholar_type],TRUE)) {
                    $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
                }
            }
            else {
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
            }
        }
    }

    public function relations() {
        return array(
            'prefix' => array(self::BELONGS_TO, 'NstdamasPrefix', 'prefix_id'),
            'institute' => array(self::BELONGS_TO, 'NstdamasInstitute', 'institute_id'),
            'faculty' => array(self::BELONGS_TO, 'NstdamasFaculty', 'faculty_id'),
            'major' => array(self::BELONGS_TO, 'NstdamasMajor', 'major_id'),
            'org' => array(self::BELONGS_TO, 'NstdamasOrg', 'org_id'),
            'department' => array(self::BELONGS_TO, 'NstdamasDepartment', 'department_id'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'is_taist' => 'รับทุน TAIST อยู่',
            'nationality_id'=>'สัญชาติ / Nationality <span class="required">*</span>',
            'prefix_id' => 'คำนำหน้า / Prefix',
            'id_card_created' => 'วันออกบัตร / Date of Issue <span class="required">*</span>',
            'id_card_expired' => 'วันบัตรหมดอายุ  / Date of Expiry <span class="required">*</span>',
            'academic_position' => 'ตำแหน่งทางวิชาการ / Academic Position ',
            'fname' => 'ชื่อ / Firstname',
            'lname' => 'นามสกุล / Lastname',
            'fname_en' => 'ชื่ออังกฤษ / Firstname (EN) <span class="required">*</span>',
            'lname_en' => 'นามสกุลอังกฤษ / Lastname (EN) <span class="required">*</span>',
            'mobile' => 'เบอร์มือถือ / Mobile',
            'email' => 'อีเมล์ / Email',
            'birthday' => 'วันเกิด / Birthday <span class="required">*</span>',
            'phone' => 'โทรศัพท์ / Phone',
            'fax' => 'โทรสาร / Fax',
            'position' => 'ตำแหน่งงาน / Position <span class="required">*</span>',
            'institute_id' => 'สถาบัน / Institute <span class="required">*</span>',
            'institute_other' => 'สถาบันอื่นๆ / Other Institute <span class="required">*</span>',
            'faculty_id' => 'คณะ / Faculty <span class="required">*</span>',
            'faculty_other' => 'คณะอื่นๆ / Other Faculty <span class="required">*</span>',
            'major_id' => 'ภาควิชา / Department <span class="required">*</span>',
            'major_other' => 'ภาควิชาอื่นๆ / Other Department <span class="required">*</span>',
            'org_id' => 'ศูนย์ที่สังกัด / Organization <span class="required">*</span>',
            'department_id' => 'ห้องปฎิบัติการ <span class="required">*</span>',
            'expert' => 'ความเชี่ยวชาญ / Expertise  <span class="required">*</span>',
            'id_card' => 'รหัสบัตรประชาชน / ID Card / Passport Number <span class="required">*</span>',
            'parent_fname' => '(1) ชื่อ / Firstname <span class="required">*</span>',
            'parent_lname' => '(1) นามสกุล / Lastname <span class="required">*</span>',
            'parent_mobile' => '(1) เบอร์มือถือ / Mobile <span class="required">*</span>',
            'parent_relationship' => '(1) ความสัมพันธ์ / Relationship <span class="required">*</span>',
            'parent2_fname' => '(2) ชื่อ / Firstname <span class="required">*</span>',
            'parent2_lname' => '(2) นามสกุล / Lastname <span class="required">*</span>',
            'parent2_mobile' => '(2) เบอร์มือถือ / Mobile <span class="required">*</span>',
            'parent2_relationship' => '(2) ความสัมพันธ์ / Relationship <span class="required">*</span>',

            'nickname' => 'ชื่อเล่น / Nickname',
            'management_position' => 'ตำแหน่งบริหาร / Management Position',
            'industrial' => 'ชื่อหน่วยงาน/สถานประกอบการ/บริษัท ที่สังกัด(หากมี) <span class="required">*</span>',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
