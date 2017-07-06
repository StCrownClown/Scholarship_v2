<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CompanyForm extends CActiveRecord {

    public $id;
    public $industrial;
    public $industrial_en;
    public $prefix_id;
    public $fname;
    public $lname;
    public $mobile;
    public $email;
    public $phone;
    public $fax;
    public $industrial_type_manufacture;
    public $industrial_type_export;
    public $industrial_type_service;
    public $industrial_type_description;

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
            array('prefix_id, fname, lname, mobile, email, industrial, industrial_type_description', 'required'),
            array('fname, lname, email', 'length', 'max' => 100),
            array('mobile', 'length', 'max' => 10, 'min' => 10),
            array('phone,fax', 'length', 'max' => 20),
            array('phone, fax', 'reqNumberAndSharp'),
            array('mobile', 'numerical', 'integerOnly'=>true),
            array('email', 'email'),
            array('email', 'isAlready', 'safe'),
            array('industrial_en, industrial_type_manufacture, industrial_type_export, industrial_type_service', 'safe'),
            array('industrial_type_manufacture, industrial_type_export, industrial_type_service',
                'reqCheckOnce',
                'industrial_type_manufacture',
                'industrial_type_export',
                'industrial_type_service'),
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
    
    public function reqCheckOnce($attribute, $params) {
        $cnt = 0;
        foreach ($params as &$value) {
            $cnt = $cnt + $this->$value;
        }

        if ($cnt == 0) {
            $this->addError($attribute, 'โปรดเลือกอย่างน้อย 1 / Please select one.');
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
            'industrial' => 'ชื่อหน่วยงาน/สถานประกอบการ/บริษัท / Name',
            'industrial_en' => 'ชื่อภาษาอังกฤษ (ถ้ามี) / Name (EN)',
            'prefix_id' => 'คำนำหน้า / Prefix',
            'fname' => 'ชื่อ / Firstname',
            'lname' => 'นามสกุล / Lastname',
            'mobile' => 'เบอร์มือถือ / Mobile',
            'email' => 'อีเมล์ / Email',
            'phone' => 'โทรศัพท์ / Phone',
            'fax' => 'โทรสาร / Fax',
            'industrial_type_manufacture' => 'ผลิต / Manufacture',
            'industrial_type_export' => 'ส่งออก / Export',
            'industrial_type_service' => 'ให้บริการ / Service',
            'industrial_type_description' => 'โปรดระบุสินค้าและบริการของบริษัท/ภาคอุตสาหกรรม',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
