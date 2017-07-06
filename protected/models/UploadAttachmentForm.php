<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class UploadAttachmentForm extends CActiveRecord {

    public $cv;
    public $cv_path;
    public $professor_mentor_attachment_project;
    public $professor_mentor_attachment_project_path;
    public $professor_mentor_attachment_other;
    public $professor_mentor_attachment_other_path;
    
    public $student_transcript;
    public $student_transcript_path;
    public $copy_id_card;
    public $copy_id_card_path;
    public $student_portfolio;
    public $student_portfolio_path;
    public $student_card;
    public $student_card_path;
    public $student_attachment_other;
    public $student_attachment_other_path;
    public $student_attachment_other2;
    public $student_attachment_other2_path;
    
    public $industrial_certificate;
    public $industrial_certificate_path;
    public $industrial_join;
    public $industrial_join_path;
    public $industrial_attachment_other;
    public $industrial_attachment_other_path;
    public $status;

    public function tableName() {
        return 'scholar_stem';
    }

    public function rules() {
        return array(
            array('professor_mentor_attachment_other_path, student_portfolio_path, student_card_path, student_attachment_other_path, student_attachment_other2_path, industrial_attachment_other_path', 'safe'),
            array('cv, professor_mentor_attachment_project, professor_mentor_attachment_other,'
                . 'student_transcript, copy_id_card, student_portfolio,, student_attachment_other, student_attachment_other2,'
                . 'industrial_certificate, industrial_join, industrial_attachment_other', 'file',
                'allowEmpty' => TRUE,
                'types' => 'pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar', 
                'maxSize'=>1024 * 1024 * 5, 'tooLarge'=>'ขนาดต้องไม่เกิน 5MB / File has to be smaller than 5MB'),
            
            array('cv', 'reqByType', 'professor', 'on' => 'cv'),
            array('professor_mentor_attachment_project', 'reqByType', 'professor', 'on' => 'professor_mentor_attachment_project'),
            array('cv_path', 'reqByType', 'mentor', 'on' => 'cv'),
            array('professor_mentor_attachment_project_path', 'reqByType', 'mentor', 'on' => 'professor_mentor_attachment_project'),
            array('student_transcript_path', 'reqByType', 'student', 'on' => 'student_transcript'),
            array('copy_id_card_path', 'reqByType', 'student', 'on' => 'copy_id_card'),
            array('student_card_path', 'reqByType', 'student', 'on' => 'student_card'),
            array('industrial_certificate_path', 'reqByType', 'industrial', 'on' => 'industrial_certificate'),
            array('industrial_join_path', 'reqByType', 'industrial', 'on' => 'industrial_join'),
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
            'cv' => '1. เอกสารประวัติส่วนตัว(CV) / Curriculum Vitae',
            'cv_path' => '1. เอกสารประวัติส่วนตัว(CV) / Curriculum Vitae',
            'professor_mentor_attachment_project' => '2. แผนการดำเนินงานโครงการย่อย / Plan project',
            'professor_mentor_attachment_project_path' => '2. แผนการดำเนินงานโครงการย่อย / Plan project<br/>(สามารถดูตัวอย่างแผนดำเนินการได้ที่นี่ '
            . '<a href="' . Yii::app()->createUrl(Yii::app()->params['ExProjectPlanLink']) . '" target="_blank"><u>คลิก ตัวอย่างแผนดำเนินการ</u></a>, '
            . '<a href="' . Yii::app()->createUrl(Yii::app()->params['ProjectPlanLink']) . '" target="_blank"><u>คลิก แผนดำเนินการ)</u></a>',
            'professor_mentor_attachment_other' => '5. เอกสารแนบเพิ่มเติม / Additional',
            'professor_mentor_attachment_other_path' => '5. เอกสารแนบเพิ่มเติม / Additional',
            
            'student_transcript' => '1. ใบแสดงผลการศึกษาล่าสุด / Last Transcript',
            'student_transcript_path' => '1. ใบแสดงผลการศึกษาล่าสุด / Last Transcript',
            'copy_id_card' => '2. สำเนาบัตรประชาชน / Identification Card',
            'copy_id_card_path' => '2. สำเนาบัตรประชาชน / Identification Card',
            'student_card' => '3. สำเนาบัตรนักศึกษา / Student Card',
            'student_card_path' => '3. สำเนาบัตรนักศึกษา / Student Card',
            'student_portfolio' => '4. ผลงานที่เคยได้รับ / Portfolio',
            'student_portfolio_path' => '4. ผลงานที่เคยได้รับ / Portfolio',
            'student_attachment_other' => '5. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other_path' => '5. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other2' => '6. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other2_path' => '6. เอกสารแนบเพิ่มเติม / Additional',
            
            'industrial_certificate' => '1. เอกสารหนังสือรับรองการจัดตั้ง',
            'industrial_certificate_path' => '1. เอกสารหนังสือรับรองการจัดตั้ง',
            'industrial_join' => '2. หนังสือยืนยันเข้าร่วมโครงการ<br/>(สามารถ Download ได้ที่นี่ <a href="' . Yii::app()->createUrl(Yii::app()->params['IndustrialJoinLink']) . '" target="_blank"><u>คลิก</u></a>)',
            'industrial_join_path' => '2. หนังสือยืนยันเข้าร่วมโครงการ<br/>(สามารถ Dowload ได้ที่นี่ <a href="' . Yii::app()->createUrl(Yii::app()->params['IndustrialJoinLink']) . '" target="_blank"><u>คลิก</u></a>)',
            'industrial_attachment_other' => '3. เอกสารแนบเพิ่มเติม / Additional',
            'industrial_attachment_other_path' => '3. เอกสารแนบเพิ่มเติม / Additional',
        );
    }

}
