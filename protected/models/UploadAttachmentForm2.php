<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class UploadAttachmentForm2 extends CActiveRecord {

    public $cv;
    public $cv_path;
    public $attachment_other;
    public $attachment_other_path;
    public $attachment_other2;
    public $attachment_other2_path;
    public $industrial_certificate;
    public $industrial_certificate_path;
    public $industrial_join;
    public $industrial_join_path;
    public $copy_id_card;
    public $copy_id_card_path;
    public $status;

    public function tableName() {
        return 'person';
    }

    public function rules() {
        return array(
            array('attachment_other_path, attachment_other2_path', 'safe'),
            array('cv, copy_id_card, attachment_other, attachment_other2, industrial_certificate, industrial_join', 'file', 'types' => 'pdf,doc,docx,zip,rar', 'allowEmpty' => TRUE),
            array('cv_path', 'reqByType', 'professor'),
            array('cv_path', 'reqByType', 'mentor'),
            array('industrial_certificate_path, industrial_join_path', 'reqByType', 'industrial'),
            array('cv_path', 'reqByType', 'student' , 'on'=>'cv'),
            array('copy_id_card_path', 'reqByType', 'student' , 'on'=>'copy'),
        );
    }

    public function reqByType($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        if ($this->$attribute == NULL && $person_type == $params[0] && $this->$attribute != '0') {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }

    public function attributeLabels() {
        return array(
            'cv' => 'เอกสารประวัติส่วนตัว(CV) / Curriculum Vitae',
            'cv_path' => 'เอกสารประวัติส่วนตัว(CV) / Curriculum Vitae',
            'attachment_other' => 'เอกสารแนบเพิ่มเติม / Additional',
            'attachment_other_path' => 'เอกสารแนบเพิ่มเติม / Additional',
            'attachment_other2' => 'เอกสารแนบเพิ่มเติม / Additional',
            'attachment_other2_path' => 'เอกสารแนบเพิ่มเติม / Additional',
            'copy_id_card' => 'สำเนาบัตรประชาชน / Identification Card',
            'copy_id_card_path' => 'สำเนาบัตรประชาชน / Identification Card',
            'industrial_certificate' => 'เอกสารหนังสือรับรองการจัดตั้ง',
            'industrial_certificate_path' => 'เอกสารหนังสือรับรองการจัดตั้ง',
            'industrial_join' => 'หนังสือยืนยันเข้าร่วมโครงการ<br/>(สามารถ Dowload ได้ที่นี่ <a href="' . Yii::app()->createUrl(Yii::app()->params['IndustrialJoinLink']) . '" target="_blank"> คลิก </a>)',
            'industrial_join_path' => 'หนังสือยืนยันเข้าร่วมโครงการ<br/>(สามารถ Dowload ได้ที่นี่ <a href="' . Yii::app()->createUrl(Yii::app()->params['IndustrialJoinLink']) . '" target="_blank"> คลิก </a>)',
        );
    }

}
