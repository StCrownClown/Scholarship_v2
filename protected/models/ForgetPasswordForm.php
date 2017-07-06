<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ForgetPasswordForm extends CFormModel {

    public $nationality_id;
    public $id_card;
    public $email;
    public $type;
    public $token;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('nationality_id, id_card, email', 'required'),
            array('id_card', 'length', 'max' => 30),
            array('id_card', 'isAlready', 'self'),
            array('id_card', 'CheckDigiIdCard', 'nationality_id'),
            array('email', 'email'),
            array('email', 'length', 'max' => 100),
            array('email', 'isAlready', 'self'),
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

    public function isAlready($attribute, $params) {
        $attr = array(
            'th' => '',
            'en' => ''
        );
        if ($attribute == 'id_card') {
            $attr['en'] = 'ID Card';
            $attr['th'] = 'เลขบัตรประชาชน';
        } elseif ($attribute == 'email') {
            $attr['en'] = 'Email';
            $attr['th'] = 'Email';
        }
        $record = Person::model()->findByAttributes(array($attribute => $this->$attribute));
        if ($record === null) {
            $this->addError($attribute, 'ไม่พบ' . $attr['th'] . 'ในระบบ</br>' . $attr['en'] . ' not found.');
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'nationality_id' => 'สัญชาติ / Nationality',
            'id_card' => 'เลขบัตรประชาชน / ID Card',
            'email' => 'Email',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect username or password.');
        }
    }

}
