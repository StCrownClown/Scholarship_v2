<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CActiveRecord {

    public $nationality_id;
    public $id_card;
    public $email;
    public $password;
    public $confirmPassword;
    public $type;
    public $token;
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
            array('type, token', 'safe'),
            array('id_card', 'length', 'max' => 30),
            array('password', 'length', 'min' => 6),
            array('confirmPassword', 'length', 'min' => 6),
            array('email', 'length', 'max' => 100),
            array('nationality_id, id_card, email, password, confirmPassword', 'required'),
            array('id_card', 'unique',
                'className' => 'Person',
                'attributeName' => 'id_card',
                'message' => 'เลขบัตรประชาชน นี้ไม่สามารถใช้ได้</br>'
                . 'ID Card is already exists.'),
            array('id_card', 'CheckDigiIdCard', 'nationality_id'),
            array('email', 'email'),
            array('email', 'unique',
                'className' => 'Person',
                'attributeName' => 'email',
                'message' => 'Email นี้ไม่สามารถใช้ได้</br>'
                . 'Email is already exists.'),
            array('confirmPassword', 'compare', 'compareAttribute' => 'password'),
            array('password',
                'match', 'pattern' => '/[\*a-zA-Z0-9@#$%]{6,10}$/',
                'message' => 'กรอกได้เฉพาะ a-z A-Z 0-9 @#$% ความยาว 6-10 ตัวอักษร',
            ),
        );
    }

    public function relations() {
        return array(
            'nationality' => array(self::BELONGS_TO, 'NstdamasNationality', 'nationality_id'),
        );
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

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'id_card' => 'รหัสบัตรประชาชน / ID Card/Passport Number',
            'nationality_id' => 'สัญชาติ / Nationality',
            'type' => 'ประเภทข้อมูล / Type',
            'email' => 'อีเมล์ / Email',
            'password' => 'รหัสผ่าน / Password',
            'confirmPassword' => 'ยืนยันรหัสผ่าน / Confirm Password',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            //$duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            $duration = 0;
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else
            return false;
    }

    /**
     * @return boolean validate user
     */
    public function validatePassword($password, $username) {
        return $this->hashPassword($password) === $this->password;
    }

    /**
     * @return hashed value
     */
    public function hashPassword($password) {
        $key = Yii::app()->params['PrivateKeyPWD'];
        return md5($password . $key);
    }

}
