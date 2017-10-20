<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class TgistUploadAttachmentForm extends CActiveRecord {
    
    public $cv;
    public $cv_path;
 
    public $student_proposal;
    public $student_proposal_path;
    public $student_transcript;
    public $student_transcript_path;
    public $student_portfolio;
    public $student_portfolio_path;
    public $student_attachment_other;
    public $student_attachment_other_path;
    public $student_attachment_other2;
    public $student_attachment_other2_path;
    public $student_attachment_other3;
    public $student_attachment_other3_path;
    
    public $professor_attachment_other;
    public $professor_attachment_other_path;
    public $professor_attachment_other2;
    public $professor_attachment_other2_path;
    public $professor_attachment_other3;
    public $professor_attachment_other3_path;
    
    public $mentor_attachment_other;
    public $mentor_attachment_other_path;
    public $mentor_attachment_other2;
    public $mentor_attachment_other2_path;
    public $mentor_attachment_other3;
    public $mentor_attachment_other3_path;
    
    public $status;

    public function tableName() {
        return 'scholar_tgist';
    }

    public function rules() {
        return array(
//            array('student_portfolio_path, student_attachment_other_path, student_attachment_other2_path', 'safe'),
            array('cv, student_proposal, student_transcript, student_portfolio, student_attachment_other, student_attachment_other2, student_attachment_other3,professor_attachment_other, professor_attachment_other2, professor_attachment_other3,mentor_attachment_other, mentor_attachment_other2, mentor_attachment_other3'
                ,'file',
                'allowEmpty' => TRUE,
                'types' => 'pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar', 
                'maxSize'=>1024 * 1024 * 5, 'tooLarge'=>'ขนาดต้องไม่เกิน 5MB / File has to be smaller than 5MB'),
//            array('cv', 'reqByType', 'professor', 'on' => 'cv'),
//            array('cv_path', 'reqByType', 'mentor', 'on' => 'cv'),
            array('student_transcript_path', 'reqByType', 'student', 'on' => 'student_transcript'),
//            array('copy_id_card_path', 'reqByType', 'student', 'on' => 'copy_id_card'),
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
            
            'student_proposal' => '1. ไฟล์ proposal',
            'student_proposal_path' => '1. ไฟล์ proposal',
            'student_transcript' => '2. ใบแสดงผลการศึกษาล่าสุด / Last Transcript',
            'student_transcript_path' => '2. ใบแสดงผลการศึกษาล่าสุด / Last Transcript',
            'student_portfolio' => '3. ผลงานที่เคยได้รับ / Portfolio',
            'student_portfolio_path' => '3. ผลงานที่เคยได้รับ / Portfolio',
            'student_attachment_other' => '4. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other_path' => '4. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other2' => '5. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other2_path' => '5. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other3' => '6. เอกสารแนบเพิ่มเติม / Additional',
            'student_attachment_other3_path' => '6. เอกสารแนบเพิ่มเติม / Additional',
            
            'professor_attachment_other' => '2. เอกสารแนบเพิ่มเติม / Additional',
            'professor_attachment_other_path' => '2. เอกสารแนบเพิ่มเติม / Additional',
            'professor_attachment_other2' => '3. เอกสารแนบเพิ่มเติม / Additional',
            'professor_attachment_other2_path' => '3. เอกสารแนบเพิ่มเติม / Additional',
            'professor_attachment_other3' => '4. เอกสารแนบเพิ่มเติม / Additional',
            'professor_attachment_other3_path' => '4. เอกสารแนบเพิ่มเติม / Additional',
            
            'mentor_attachment_other' => '2. เอกสารแนบเพิ่มเติม / Additional',
            'mentor_attachment_other_path' => '2. เอกสารแนบเพิ่มเติม / Additional',
            'mentor_attachment_other2' => '3. เอกสารแนบเพิ่มเติม / Additional',
            'mentor_attachment_other2_path' => '3. เอกสารแนบเพิ่มเติม / Additional',
            'mentor_attachment_other3' => '4. เอกสารแนบเพิ่มเติม / Additional',
            'mentor_attachment_other3_path' => '4. เอกสารแนบเพิ่มเติม / Additional',
            
//            'copy_id_card' => 'สำเนาบัตรประชาชน / Identification Card',
//            'copy_id_card_path' => 'สำเนาบัตรประชาชน / Identification Card',
            
        );
    }

}
