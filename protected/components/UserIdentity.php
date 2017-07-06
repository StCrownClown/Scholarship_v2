<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        //$user = Person::model()->findByAttributes(array('email' => $this->username));
        $criteria = new CDbCriteria;
        $criteria->condition = "email = '$this->username' or id_card = '$this->username'";
        $criteria->limit = 1;
        $user = Person::model()->find($criteria);

        $criteria = new CDbCriteria;
        $criteria->condition = "username = '$this->username' and active = 1 ";
        $criteria->limit = 1;
        $admin = Account::model()->find($criteria);

        if ($admin !== null) {
            if ($admin->password !== $this->password) {
                if ($admin->token !== $this->password) {
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                } else {// Okay!
                    $this->errorCode = self::ERROR_NONE;
                }
            } else { // Okay!
                $this->errorCode = self::ERROR_NONE;
            }
        } else if ($user !== null) {
            $pass = md5($this->password . Yii::app()->params['PrivateKeyPWD']);

            if ($user->password !== $pass) {
                if ($user->token !== $this->password) {
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                } else {// Okay!
                    $this->errorCode = self::ERROR_NONE;
                }
            } else {// Okay!
                $this->errorCode = self::ERROR_NONE;
            }
        } else {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        return !$this->errorCode;
    }

}
