<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ResetPasswordForm extends CActiveRecord {
    
    public $id;
    public $password;
    public $confirmPassword;
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
            array('password, confirmPassword', 'required'),
            array('password, confirmPassword', 'length', 'min' => 6, 'max' => 10),
            array('confirmPassword', 'compare', 'compareAttribute' => 'password'),
            array('password',
                'match', 'pattern' => '/[\*a-zA-Z0-9@#$%]{6,10}$/',
                'message' => 'กรอกได้เฉพาะ a-z A-Z 0-9 @#$% ความยาว 6-10 ตัวอักษร',
            ),
        );
    }

    public function relations() {
        return array();
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'password' => 'New Password',
            'confirmPassword' => 'Confirm New Password',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }
}
