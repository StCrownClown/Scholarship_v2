<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ResetEmailForm extends CActiveRecord {
    
    public $id;
    public $token;
    public $email_old;
    public $email;
    public $confirmEmail;
    private $_identity;

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
            // username and password are required
            array('token, email_old', 'safe'),
            array('email', 'NotDupOldEmail', 'email_old'),
            array('email, confirmEmail', 'required'),
            array('email, confirmEmail', 'length', 'max' => 100),
            array('confirmEmail', 'compare', 'compareAttribute' => 'email'),
            array('email, confirmEmail', 'email'),
            array('email', 'unique',
                'className' => 'Person',
                'attributeName' => 'email',
                'message' => 'Email นี้ไม่สามารถใช้ได้</br>'
                . 'Email is already exists.'),
        );
    }
    
    public function NotDupOldEmail($attribute, $params) {
        if ($this->$params[0] == $this->$attribute) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ต้องไม่ซ้ำกับ Email เดิม');
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
            'email_old' => 'Old Email',
            'email' => 'New Email',
            'confirmEmail' => 'Confirm New Email',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }
}
