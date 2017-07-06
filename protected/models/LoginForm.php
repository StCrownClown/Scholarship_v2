<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {

    public $username;
    public $password;
    public $rememberMe;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
// username and password are required
            array('username, password', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            array('username', 'length', 'max' => 100),
            array('password', 'length', 'max' => 50),
                // password needs to be authenticated
                //array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => 'Email or ID Card',
            'rememberMe' => 'Remember me next time',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    /* public function authenticate($attribute, $params) {
      if (!$this->hasErrors()) {
      $this->_identity = new UserIdentity($this->username, $this->password);
      if (!$this->_identity->authenticate())
      $this->addError('password', 'Incorrect username or password.');
      }
      }
     */

//    public function authenticate($attribute, $params) {
//        $criteria = new CDbCriteria;
//        $criteria->condition = "email = '$this->username' or id_card = '$this->username'";
//        $criteria->limit = 1;
//        $records = Person::model()->find($criteria);
//        if (!isset($records->email)) {
//            $this->addError($attribute, 'USERNAME INVALID');
//            //$this->errorCode = self::ERROR_USERNAME_INVALID;
//        } elseif ($records->password !== md5($this->password . Yii::app()->params['PrivateKeyPWD'])) {
//            $this->addError($attribute, 'PASSWORD INVALID');
//            //$this->errorCode = self::ERROR_PASSWORD_INVALID;
//        } 
//    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
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

    public function loginAdmin() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
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
