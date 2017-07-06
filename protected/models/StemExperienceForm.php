<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class StemExperienceForm extends CActiveRecord {

    public $id;
    public $portfolio_thesis;
    public $portfolio_journal_international;
    public $portfolio_journal_incountry;
    public $portfolio_patent;
    public $portfolio_prototype;
    public $portfolio_conference_international;
    public $portfolio_conference_incountry;
    public $portfolio_award;
    public $portfolio_other;
    public $portfolio_journal_international_amount;
    public $portfolio_journal_incountry_amount;
    public $portfolio_patent_amount;
    public $portfolio_prototype_amount;
    public $portfolio_conference_international_amount;
    public $portfolio_conference_incountry_amount;
    public $portfolio_award_amount;
    public $portfolio_other_text;
    
    public $portfolio_journal_international_desc;
    public $portfolio_journal_incountry_desc;
    public $portfolio_patent_desc;
    public $portfolio_prototype_desc;
    public $portfolio_conference_international_desc;
    public $portfolio_conference_incountry_desc;
    public $portfolio_award_desc;
    public $portfolio_other_desc;
    public $portfolio_other_amount;
    


    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scholar_stem';
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('portfolio_journal_international_amount,'
                . 'portfolio_journal_international_desc', 'reqIsOther', 'portfolio_journal_international'),
            array('portfolio_journal_incountry_amount,'
                . 'portfolio_journal_incountry_desc', 'reqIsOther', 'portfolio_journal_incountry'),
            array('portfolio_patent_amount,'
                . 'portfolio_patent_desc', 'reqIsOther', 'portfolio_patent'),
            array('portfolio_prototype_amount,'
                . 'portfolio_prototype_desc', 'reqIsOther', 'portfolio_prototype'),
            array('portfolio_conference_international_amount,'
                . 'portfolio_conference_international_desc', 'reqIsOther', 'portfolio_conference_international'),
            array('portfolio_conference_incountry_amount,'
                . 'portfolio_conference_incountry_desc', 'reqIsOther', 'portfolio_conference_incountry'),
            array('portfolio_award_amount,'
                . 'portfolio_award_desc', 'reqIsOther', 'portfolio_award'),
            array('portfolio_other_text,'
                . 'portfolio_other_amount,'
                . 'portfolio_other_desc', 'reqIsOther', 'portfolio_other'),
            array('portfolio_journal_international_amount,'
                . 'portfolio_journal_incountry_amount,'
                . 'portfolio_patent_amount,'
                . 'portfolio_prototype_amount,'
                . 'portfolio_conference_international_amount,'
                . 'portfolio_conference_incountry_amount,'
                . 'portfolio_award_amount', 'numerical'),
            array('portfolio_thesis,portfolio_journal_international,portfolio_journal_incountry,portfolio_patent,portfolio_prototype,portfolio_conference_international,portfolio_conference_incountry,portfolio_award,portfolio_other,portfolio_journal_international_amount,portfolio_journal_incountry_amount,portfolio_patent_amount,portfolio_prototype_amount,portfolio_conference_international_amount,portfolio_conference_incountry_amount,portfolio_award_amount,portfolio_other_text', 'safe'),
        );
    }

    public function reqIsOther($attribute, $params) {
        if ($this->$params[0] == '1' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }

    public function relations() {
        return array();
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'portfolio_thesis' => 'ชื่อหัวข้อปริญญานิพนธ์ (กรณีสมัครระดับปริญญาโท) หรือ ชื่อหัวข้อวิทยานิพนธ์ (กรณีสมัครระดับปริญญาเอก)',
            'portfolio_journal_international' => 'ตีพิมพ์ในวารสารวิจัยนานาชาติ',
            'portfolio_journal_incountry' => 'ตีพิมพ์ในวารสารวิจัยในประเทศ',
            'portfolio_patent' => 'สิทธิบัตร/อนุสิทธิบัตร',
            'portfolio_prototype' => 'ต้นแบบ',
            'portfolio_conference_international' => 'ผลงานนำเสนอในที่ประชุมวิชาการนานาชาติ',
            'portfolio_conference_incountry' => 'ผลงานนำเสนอในที่ประชุมวิชาการในประเทศ',
            'portfolio_award' => 'รางวัล',
            'portfolio_other' => 'อื่นๆ',
            'portfolio_journal_international_amount' => 'จำนวน (เรื่อง) <span class="required">*</span>',
            'portfolio_journal_incountry_amount' => 'จำนวน (เรื่อง) <span class="required">*</span>',
            'portfolio_patent_amount' => 'จำนวน (ฉบับ) <span class="required">*</span>',
            'portfolio_prototype_amount' => 'จำนวน (ชิ้น) <span class="required">*</span>',
            'portfolio_conference_international_amount' => 'จำนวน (เรื่อง) <span class="required">*</span>',
            'portfolio_conference_incountry_amount' => 'จำนวน (เรื่อง) <span class="required">*</span>',
            'portfolio_award_amount' =>  'จำนวน (รางวัล) <span class="required">*</span>',
            'portfolio_other_text' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_journal_international_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_journal_incountry_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_patent_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_prototype_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_conference_international_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_conference_incountry_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_award_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_other_desc' => 'โปรดระบุ <span class="required">*</span>',
            'portfolio_other_amount' => 'จำนวน (ชิ้น) <span class="required">*</span>',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
