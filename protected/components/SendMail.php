<?php

class SendMail {

    public $body;
    public $subject;
    public $to;
    public $from;
    public $cc;
    public $bcc;
    public $attachFile;

    public function send() {
        $message = new YiiMailMessage;

        if (Yii::app()->params['mode'] == 'test' || Yii::app()->params['mailTest'] === true) {
            $to = Yii::app()->params['adminEmail'];
            if (Yii::app()->params['mailTestWeb'] === true) {
                $to = KR_USER_INFO::getEmail(ConfigWeb::getUserID());
            }
            $tmpBody = 'mail to : ' . CJSON::encode($this->to) . '<br><br>';
            $tmpBody .= 'mail cc : ' . CJSON::encode($this->cc) . '<br><br>';
            $this->to = $to;

            $this->cc = '';
            $this->body = $tmpBody . $this->body;
        }

        $message->setBody($this->body, 'text/html', 'utf-8');

        $subject = '=?UTF-8?B?' . base64_encode($this->subject) . '?=';
        $message->subject = $subject;

        if (Yii::app()->params['debugMail'] === true && Yii::app()->params['mode'] != 'test') {
            if (empty($this->bcc)) {
                $this->bcc = Yii::app()->params['adminEmail'];
            } else {
                if (!is_array($this->bcc)) {
                    $this->bcc = array($this->bcc, Yii::app()->params['adminEmail']);
                } else {
                    $this->bcc[] = Yii::app()->params['adminEmail'];
                }
            }
        }

        $mTo = array();
        if (is_array($this->to)) {
            foreach ($this->to as $key => $value) {
                if ($value != '') {
                    $email = $value;
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $mTo[] = $email;
                    }
                }
            }
        } else {
            $email = $this->to;
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mTo[] = $email;
            }
        }

        if (count($mTo) > 0) {
            foreach ($mTo as $key => $email) {
                $message->addTo($email);
            }
        } else {
            $to = Yii::app()->params['adminEmail'];
            $tmpBody = 'mail to : ' . CJSON::encode($this->to) . '<br><br>';
            $tmpBody .= 'mail cc : ' . CJSON::encode($this->cc) . '<br><br>';

            $message->addTo($to);

            $this->cc = '';
            $this->body = $tmpBody . $this->body;
        }

        if (!is_null($this->cc) || !empty($this->cc)) {
            if (is_array($this->cc)) {
                foreach ($this->cc as $key => $value) {
                    if ($value != '') {
                        $email = $value;
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $message->addCc($email);
                        }
                    }
                }
            } else {
                if ($this->cc != '') {
                    $email = $this->cc;
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $message->addCc($email);
                    }
                }
            }
        }

        if (!is_null($this->bcc) && !empty($this->bcc) && isset($this->bcc)) {
            if (is_array($this->bcc)) {
                foreach ($this->bcc as $key => $value) {
                    if ($value != '') {
                        $email = $value;
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $message->addBcc($email);
                        }
                    }
                }
            } else {
                $email = $this->bcc;
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message->addBcc($email);
                }
            }
        }

        if (is_null($this->from) || empty($this->from)) {
            $hostname = $_SERVER['SERVER_NAME'];
            $wReplace = array('www.', 'app.', 'app2.', 'mis-dev01.');
            $hostname = str_replace($wReplace, '', $hostname);
            $this->from = 'noreply@' . $hostname;
        }
        $message->from = $this->from;

        if (is_array($this->attachFile)) {
            foreach ($this->attachFile as $file) {
                if (ConfigWeb::checkFile($file)) {
                    $message->attach(Swift_Attachment::fromPath($file));
                }
            }
        } else {
            if (ConfigWeb::checkFile($this->attachFile)) {
                $message->attach(Swift_Attachment::fromPath($this->attachFile));
            }
        }

//        $message = "<html><body>{$message}</body></html>";
        Yii::app()->mail->send($message);
    }

    public function sendError() {
        $message = new YiiMailMessage;

        $message->setBody($this->body, 'text/html', 'utf-8');

        $subject = '=?UTF-8?B?' . base64_encode($this->subject) . '?=';
        $message->subject = $subject;

        if (is_array($this->to)) {
            foreach ($this->to as $key => $value) {
                if ($value != '')
                    $message->addTo($value);
            }
        }else {
            $message->addTo($this->to);
        }

        if (is_null($this->from) || empty($this->from)) {
            $hostname = $_SERVER['SERVER_NAME'];
            $wReplace = array('www.', 'app.', 'app2.', 'mis-dev01.');
            $hostname = str_replace($wReplace, '', $hostname);
            $this->from = 'noreply@' . $hostname;
        }
        $message->from = $this->from;


        Yii::app()->mail->send($message);
    }

}
