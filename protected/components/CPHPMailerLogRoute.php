<?php

class CPHPMailerLogRoute extends CEmailLogRoute {

    protected function sendEmail($email, $subject, $message) {
        if($_SERVER['SERVER_NAME'] != 'localhost') {
            $SendMail = new SendMail;
            $SendMail->subject = '[' . Yii::app()->name . ' - ' . ConfigWeb::getActiveScholarType() . '] '
                    . '[' . $subject . ']';
            $SendMail->to = $email;
            $SendMail->body = $message . '<br/><br/>'
                    . '[ScholarId]:' . Yii::app()->session['tmpActiveScholarId'] . '<br/>'
                    . '[ScholarType]:' . ConfigWeb::getActiveScholarType() . '<br/>'
                    . '[Person]:' . Yii::app()->session['person_id'] . '<br/>'
                    . '[PersonType]:' . Yii::app()->session['person_type'] . '<br/>'
                    . '[Token]:' . Yii::app()->session['token'] . '<br/>';
            $SendMail->sendError();
        }
    }

}
