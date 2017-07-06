<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class NuircStudentForm extends CActiveRecord {

    public $id;
    public $email;
    public $status;
    public $scholar_id;
    public $professor_id;
    public $mentor_id;
    public $industrial_id;

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
//            array('email', 'required'),
//            array('fname, lname, email', 'length', 'max' => 100),
//            array('mobile', 'length', 'max' => 10, 'min' => 10),
//            array('mobile', 'numerical'),
            array('email', 'email'),
            array('professor_id, mentor_id, industrial_id', 'safe'),
//            array('id_card', 'length', 'max' => 30),
//            array('id_card', 'uniqueIdCard', 'id_card', 'id'),
//            array('id_card', 'CheckDigiIdCard', 'nationality_id'),
        );
    }

    public function relations() {
        return array(
            'professor' => array(self::BELONGS_TO, 'Scholar', 'professor_id'),
            'mentor' => array(self::BELONGS_TO, 'Scholar', 'mentor_id'),
            'industrial' => array(self::BELONGS_TO, 'Scholar', 'industrial_id'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'email' => 'อีเมล์ / Email <span class="required">*</span>',
            'professor_id' => 'เลือก อาจารย์ที่ปรึกษา <span class="required">*</span>',
            'mentor_id' => 'เลือก นักวิจัย สวทช. <span class="required">*</span>',
            'industrial_id' => 'เลือก นักวิจัย อุตสาหกรรม <span class="required">*</span>',
            'is_professor_email' => 'เลือก อาจารย์ที่ปรึกษา (กรณีที่ยังไม่มีข้อมูล ให้กรอกอีเมลล์อาจารย์ที่ปรึกษา) <span class="required">*</span>',
            'is_mentor_email' => 'เลือก นักวิจัย สวทช. (กรณีที่ยังไม่มีข้อมูล ให้กรอกอีเมลล์นักวิจัย สวทช.) <span class="required">*</span>',
            'is_industrial_email' => 'เลือก นักวิจัย อุตสาหกรรม (กรณีที่ยังไม่มีข้อมูล ให้กรอกอีเมลล์นักวิจัย อุตสาหกรรม) <span class="required">*</span>',
        );
    }
    
    public static function FindProfessor($key = NULL) {
        $sql = "SELECT
                        person.id as id,
                        CONCAT(person.fname,' ',person.lname) as name
                        FROM person
                        WHERE person.type = 'professor'
                        ORDER BY name
                        ;";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }
    
    public static function FindMentor($key = NULL) {
        $sql = "SELECT
                        person.id as id,
                        CONCAT(person.fname,' ',person.lname) as name
                        FROM person
                        WHERE person.type = 'mentor'
                        ORDER BY name
                        ;";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }
    
    public static function FindIndustrial($key = NULL) {
        $sql = "SELECT
                        person.id as id,
                        CONCAT(person.fname,' ',person.lname) as name
                        FROM person
                        WHERE person.type = 'industrial'
                        ORDER BY name
                        ;";
        $returnList = InitialData::getDataToSelect2($sql);
        return ($key != NULL) ? $returnList [$key] : $returnList;
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
