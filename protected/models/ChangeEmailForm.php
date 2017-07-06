<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ChangeEmailForm extends CActiveRecord {

    public $id;
    public $scholar_id;
    public $type;
    public $email;
    public $prefix_id;
    public $fname;
    public $lname;
    public $mobile;
    public $phone;
    public $fax;

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
            array('phone,fax', 'length', 'max' => 20),
            array('phone, fax', 'reqNumberAndSharp'),
            array('mobile', 'numerical'),
            array('email', 'email'),
            //array('email', 'isAlready', 'safe'),
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

    public function isAlready($attribute, $params) {
        $id = $this->id;
        $criteria = new CDbCriteria;
        $criteria->condition = "email = '" . $this->$attribute . "' and type = 'industrial'";
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

        $criteria->condition = "email = '" . $this->$attribute . "' and type != 'industrial'";
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
            'type' => 'ประเภท / Type',
            'email' => 'อีเมล์ / Email',
            'prefix_id' => 'คำนำหน้า / Prefix',
            'fname' => 'ชื่อ / Firstname',
            'lname' => 'นามสกุล / Lastname',
            'mobile' => 'เบอร์มือถือ / Mobile',
            'phone' => 'เบอร์โต๊ะ / Phone',
            'fax' => 'โทรสาร / Fax',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
