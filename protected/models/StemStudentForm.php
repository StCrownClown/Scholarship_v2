<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class StemStudentForm extends CActiveRecord {

    public $id;
    public $prefix_id;
    public $fname;
    public $lname;
    public $mobile;
    public $email;
    public $status;

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
            array('prefix_id, fname, lname, mobile, email', 'required'),
            array('fname, lname, email', 'length', 'max' => 100),
            array('mobile', 'length', 'max' => 10, 'min' => 10),
            array('mobile', 'numerical'),
            array('email', 'email'),
//            array('email', 'isAlready', 'safe'),
        );
    }

    public function isAlready($attribute, $params) {
        $id = $this->id;
        $criteria = new CDbCriteria;
        $criteria->condition = "email = '" . $this->$attribute . "' and type = 'student'";
        $criteria->limit = 1;
        $record = Person::model()->find($criteria);
        if ($record !== null && $id != $record->id) {
            $this->addError($attribute, 'อีเมล์นี้ได้ถูกลงทะเบียนไว้แล้ว หากต้องการใช้ข้อมูลเดิมนี้ กรุณายืนยัน ?'
                    . ' / This email is already registered. To use the same information, please confirm ?'
                    . CHtml::submitButton('ยืนยัน / Confirm', array(
                        'name' => 'confirmemail',
                        'class' => 'btn btn-warning',
                        'confirm' => "คุณต้องการใช้ข้อมูลเดิม ของ Email นี้ หรือไม่?"
                        . "\nAre you use of Email this same information, please confirm ?",
                            )
                    )
            );
        }

        $criteria->condition = "email = '" . $this->$attribute . "' and type != 'student'";
        $criteria->limit = 1;
        $record = Person::model()->find($criteria);
        if ($record !== null) {
            $this->addError($attribute, 'อีเมล์นี้ได้ถูกลงทะเบียนไว้แล้ว ในประเภท ' . InitialData::PERSON_TYPE($record->type) . ' !! '
                    . ' / This email is already registered in ' . InitialData::PERSON_TYPE_EN($record->type) . ' !!');
        }
    }

    public function relations() {
        return array(
            'prefix' => array(self::BELONGS_TO, 'NstdamasPrefix', 'prefix_id'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'prefix_id' => 'คำนำหน้า / Prefix',
            'fname' => 'ชื่อ / Firstname',
            'lname' => 'นามสกุล / Lastname',
            'mobile' => 'เบอร์มือถือ / Mobile',
            'email' => 'อีเมล์ / Email',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
