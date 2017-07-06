<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class EducationForm extends CActiveRecord {
    
    
    public $id;
    public $education_id;
    public $student_before_gpa;
    
    public $creater_id;
    public $person_id;
    public $educationlevel_id;
    public $country_id;
    public $institute_id;
    public $institute_other;
    public $faculty_id;
    public $faculty_other;
    public $major_id;
    public $major_other;
    public $month_enrolled;
    public $year_enrolled;
    public $month_graduated;
    public $year_graduated;
    public $is_highest;
    public $active;
    public $avg_gpa;
    public $status;
    public $first_created;
    private $_identity;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'education';
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('student_before_gpa', 'reqStudentByEduLvl', 
                ['student',
                    'stem' => array(),
                    'nuirc' => array('student_before_gpa'),
                    'tgist' => array('student_before_gpa'),
                ], 'educationlevel_id', 'on' => 'next'),
            array('education_id', 'required', 'on' => 'next'),
            array('educationlevel_id, country_id', 'required'),
            array('institute_id', 'reqIgnore', 'institute_other'),
            array('faculty_id', 'reqIgnore', 'faculty_other'),
            array('institute_other, faculty_other, major_other', 'length', 'max' => 200),
            array('institute_other', 'reqIsOther', 'institute_id'),
            array('faculty_other', 'reqIsOther', 'faculty_id'),
            array('major_other', 'reqIsOther', 'major_id'),
            array('avg_gpa, student_before_gpa', 'numerical', 'min' => 0, 'max' => 4),
            array('avg_gpa, student_before_gpa', 'length', 'min' => 1, 'max' => 4),
            array('avg_gpa', 'reqBystudentGPA', 'student', 'on' => array('add', 'edit')),
            array('status', 'reqByPersonType', 'student', 'on' => array('add', 'edit')),
            array('month_graduated, year_graduated', 'reqByStatus', 'status'),
            array('status, first_created, educationlevel_id, country_id, institute_id, faculty_id, major_id, month_enrolled, year_enrolled, month_graduated, year_graduated, is_highest, active', 'safe'),
        );
    }
    
    public function reqBystudentGPA($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        if ($person_type == $params[0]){
            if(empty($this->$attribute)) 
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
            if($this->$attribute <= 0) 
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ต้องมากกว่า 0.00');
        }
    }
    
    public function reqStudentByEduLvl($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        $scholar_type =  Yii::app()->session['scholar_type'];
        if ($person_type == $params[0][0]){
            if (array_key_exists($scholar_type,$params[0])) {
                if (!in_array($attribute,$params[0][$scholar_type],TRUE)) {
                    if(empty($this->$attribute))
                        $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
                    if($this->$attribute <= 0 && ($this->$params[1] == 13 || $this->$params[1] == 14)) 
                        $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ต้องมากกว่า 0.00');
                }
            } else {
                if(empty($this->$attribute))
                    $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
                if($this->$attribute <= 0 && ($this->$params[1] == 13 || $this->$params[1] == 14)) 
                    $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ต้องมากกว่า 0.00');
            }
        }
    }
	
    public function reqIgnore($attribute, $params) {
        if (!is_numeric($this->$attribute)) {
            if(empty($this->$params[0]))
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }
	
    public function reqIsComplate($attribute, $params) {
        if ($this->$params[0] == 'complete' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }
    
    public function reqByPersonType($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        if ($person_type == $params[0] && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }
    
    public function reqByStatus($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        if ($this->$params[0] == "complete" && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }
    
    public function reqIsOther($attribute, $params) {
        if ($this->$params[0] == '0' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }

    public function relations() {
        return array(
            'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
            'educationlevel' => array(self::BELONGS_TO, 'NstdamasEducationlevel', 'educationlevel_id'),
            'country' => array(self::BELONGS_TO, 'NstdamasCountry', 'country_id'),
            'institute' => array(self::BELONGS_TO, 'NstdamasInstitute', 'institute_id'),
            'faculty' => array(self::BELONGS_TO, 'NstdamasFaculty', 'faculty_id'),
            'major' => array(self::BELONGS_TO, 'NstdamasMajor', 'major_id'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'education_id' => 'เลือกข้อมูลการศึกษา / Select <span class="required">*</span>',
            'student_before_gpa' => 'เกรดเฉลี่ย ',
            'person_id' => 'Person',
            'educationlevel_id' => 'ระดับการศึกษา / Degree',
            'country_id' => 'ประเทศ / Country',
            'institute_id' => 'สถาบัน / Institute <span class="required">*</span>',
            'institute_other' => 'สถาบันอื่นๆ / Other Institute <span class="required">*</span>',
            'faculty_id' => 'คณะ / Faculty <span class="required">*</span>',
            'faculty_other' => 'คณะอื่นๆ / Other Faculty <span class="required">*</span>',
            'major_id' => 'ภาควิชา / Department',
            'major_other' => 'ภาควิชาอื่นๆ / Other Department <span class="required">*</span>',
            'month_enrolled' => 'เดือนที่เข้ารับการศึกษา / Month Enrolled',
            'year_enrolled' => 'ปีที่เข้ารับการศึกษา / Year Enrolled',
            'month_graduated' => 'เดือนที่จบการศึกษา / Month Graduated',
            'year_graduated' => 'ปีที่จบการศึกษา / Year Graduated',
            'avg_gpa' => 'เกรดเฉลี่ยรวม / Avg. GPA',
            'status' => 'สถานะ',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
