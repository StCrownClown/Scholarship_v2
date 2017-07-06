<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class StemWorkingForm extends CActiveRecord {

    public $id;
    public $is_work;
    public $work_company;
    public $work_position;
    public $work_location;
    public $work_phone;
    public $work_fax;
    public $is_workwithproject;
    public $workwithproject_text1;
    public $workwithproject_text2;
    public $workwithproject_text3;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scholar_stem';
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('work_phone, work_fax', 'length', 'max' => 20),
            array('work_company, work_position', 'length', 'max' => 100),
            array('work_phone, work_fax', 'reqNumberAndSharp'),
            array('work_company, work_location, work_position', 'reqIsOther', 'is_work'),
            array('workwithproject_text1', 'reqIsOther', 'is_workwithproject'),
            array('is_work, work_company, work_location, work_phone, work_fax, is_workwithproject, workwithproject_text1, workwithproject_text2, workwithproject_text3', 'safe'),
        );
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
        if ($this->$params[0] == '1' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }

    public function relations() {
        return array();
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'is_work' => 'สถานภาพการทำงานปัจจุบัน <span class="required">*</span>',
            'work_company' => 'ชื่อบริษัท/ภาคอุตสาหกรรม/หน่วยงาน / Company <span class="required">*</span>',
            'work_position' => 'ตำแหน่ง / Position <span class="required">*</span>',
            'work_location' => 'ที่ตั้งบริษัท/ภาคอุตสาหกรรม / Location <span class="required">*</span>',
            'work_phone' => 'โทรศัพท์ / Phone',
            'work_fax' => 'โทรสาร / Fax',
            'is_workwithproject' => 'ประวัติการทำงานหรือประสบการณ์ในการทำงานที่เห็นว่าเกี่ยวข้องกับการทำโครงการที่เสนอขอรับทุน',
            'workwithproject_text1' => '(1) รายละเอียด <span class="required">*</span>',
            'workwithproject_text2' => '(2) รายละเอียด',
            'workwithproject_text3' => '(3) รายละเอียด',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
