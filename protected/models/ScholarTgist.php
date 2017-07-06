<?php

/**
 * This is the model class for table "scholar_tgist".
 *
 * The followings are the available columns in table 'scholar_tgist':

 *
 * The followings are the available model relations:
 * @property Scholar[] $scholars
 * @property Project $project
 */
class ScholarTgist extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ScholarTgist the static model class
     */
    public $id;
    public $running;
    public $project_id;
    public $project_name;
    public $project_begin;
    public $project_end;
    public $expect;
    public $cooperation;
    public $effect_new;
    public $effect_cost;
    public $effect_quality;
    public $effect_environment;
    public $effect_other;
    public $effect_other_text;
    public $relevance_automotive;
    public $relevance_electronics;
    public $relevance_tourism;
    public $relevance_agriculture;
    public $relevance_food;
    public $relevance_robotics;
    public $relevance_aviation;
    public $relevance_biofuels;
    public $relevance_digital;
    public $relevance_medical;
    public $itap;
    public $is_history;
    public $portfolio_thesis;
    public $portfolio_journal_international;
    public $portfolio_journal_international_amount;
    public $portfolio_journal_international_desc;
    public $portfolio_journal_incountry;
    public $portfolio_journal_incountry_amount;
    public $portfolio_journal_incountry_desc;
    public $portfolio_patent;
    public $portfolio_patent_amount;
    public $portfolio_patent_desc;
    public $portfolio_prototype;
    public $portfolio_prototype_amount;
    public $portfolio_prototype_desc;
    public $portfolio_conference_international;
    public $portfolio_conference_international_amount;
    public $portfolio_conference_international_desc;
    public $portfolio_conference_incountry;
    public $portfolio_conference_incountry_amount;
    public $portfolio_conference_incountry_desc;
    public $portfolio_award;
    public $portfolio_award_amount;
    public $portfolio_award_desc;
    public $portfolio_other;
    public $portfolio_other_text;
    public $portfolio_other_amount;
    public $portfolio_other_desc;
    public $work_position;
    public $work_company;
    public $work_location;
    public $work_phone;
    public $work_fax;
    public $is_work;
    public $is_workwithproject;
    public $workwithproject_text1;
    public $workwithproject_text2;
    public $workwithproject_text3;
    public $incash_fee;
    public $incash_fee_cost;
    public $incash_fee_source;
    public $incash_monthly;
    public $incash_monthly_cost;
    public $incash_monthly_source;
    public $incash_other;
    public $incash_other_text;
    public $incash_other_cost;
    public $incash_other_source;
    public $incash_other2;
    public $incash_other2_text;
    public $incash_other2_cost;
    public $incash_other2_source;
    public $inkind_other;
    public $inkind_other_text;
    public $inkind_other_cost;
    public $inkind_other_source;
    public $inkind_other2;
    public $inkind_other2_text;
    public $inkind_other2_cost;
    public $inkind_other2_source;
    public $industrial_incash_salary;
    public $industrial_incash_salary_cost;
    public $industrial_incash_rents;
    public $industrial_incash_rents_cost;
    public $industrial_incash_traveling;
    public $industrial_incash_traveling_cost;
    public $industrial_incash_other;
    public $industrial_incash_other_text;
    public $industrial_incash_other_cost;
    public $industrial_incash_other2;
    public $industrial_incash_other2_text;
    public $industrial_incash_other2_cost;
    public $industrial_inkind_equipment;
    public $industrial_inkind_equipment_cost;
    public $industrial_inkind_other;
    public $industrial_inkind_other_text;
    public $industrial_inkind_other_cost;
    public $industrial_inkind_other2;
    public $industrial_inkind_other2_text;
    public $industrial_inkind_other2_cost;
    public $student_before_gpa;
    public $industrial_support_desc;
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scholar_tgist';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('project_id, effect_new, effect_cost, effect_quality, effect_environment, effect_other, relevance_automotive, relevance_electronics, relevance_tourism, relevance_agriculture, relevance_food, relevance_robotics, relevance_aviation, relevance_biofuels, relevance_digital, relevance_medical, itap, is_history, portfolio_journal_international, portfolio_journal_international_amount, portfolio_journal_incountry, portfolio_journal_incountry_amount, portfolio_patent, portfolio_patent_amount, portfolio_prototype, portfolio_prototype_amount, portfolio_conference_international, portfolio_conference_international_amount, portfolio_conference_incountry, portfolio_conference_incountry_amount, portfolio_award, portfolio_award_amount, portfolio_other_amount, portfolio_other, is_workwithproject, incash_fee, incash_monthly, incash_other, incash_other2, inkind_other, inkind_other2, industrial_incash_salary, industrial_incash_rents, industrial_incash_traveling, industrial_incash_other, industrial_incash_other2, industrial_inkind_equipment, industrial_inkind_other, industrial_inkind_other2', 'numerical', 'integerOnly' => true),
            array('project_name, effect_other_text, incash_fee_source, incash_monthly_source, incash_other_text, incash_other_source, incash_other2_text, incash_other2_source, inkind_other_text, inkind_other_source, inkind_other2_text, inkind_other2_source, industrial_incash_other_text, industrial_incash_other2_text, industrial_inkind_other_text, industrial_inkind_other2_text', 'length', 'max' => 255),
            array('work_phone, work_fax', 'length', 'max' => 50),
            array('work_phone, work_fax', 'reqNumberAndSharp', 'id'),
            array('incash_fee_cost, incash_monthly_cost, incash_other_cost, incash_other2_cost, inkind_other_cost, inkind_other2_cost, industrial_incash_salary_cost, industrial_incash_rents_cost, industrial_incash_traveling_cost, industrial_incash_other_cost, industrial_incash_other2_cost, industrial_inkind_equipment_cost, industrial_inkind_other_cost, industrial_inkind_other2_cost', 'length', 'max' => 10),
            array('running, project_begin, project_end, expect, cooperation, portfolio_thesis, portfolio_other_text, work_company, work_position, work_location, workwithproject_text1, workwithproject_text2, workwithproject_text3', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('student_before_gpa, id, project_id, project_name, project_begin, project_end, expect, cooperation, effect_new, effect_cost, effect_quality, effect_environment, effect_other, effect_other_text, relevance_automotive, relevance_electronics, relevance_tourism, relevance_agriculture, relevance_food, relevance_robotics, relevance_aviation, relevance_biofuels, relevance_digital, relevance_medical, itap, is_history, portfolio_thesis, portfolio_journal_international, portfolio_journal_international_amount, portfolio_journal_incountry, portfolio_journal_incountry_amount, portfolio_patent, portfolio_patent_amount, portfolio_prototype, portfolio_prototype_amount, portfolio_conference_international, portfolio_conference_international_amount, portfolio_conference_incountry, portfolio_conference_incountry_amount, portfolio_award, portfolio_award_amount, portfolio_other_amount, portfolio_other, portfolio_other_text, work_company, work_location, work_phone, work_fax, is_workwithproject, workwithproject_text1, workwithproject_text2, workwithproject_text3, incash_fee, incash_fee_cost, incash_fee_source, incash_monthly, incash_monthly_cost, incash_monthly_source, incash_other, incash_other_text, incash_other_cost, incash_other_source, incash_other2, incash_other2_text, incash_other2_cost, incash_other2_source, inkind_other, inkind_other_text, inkind_other_cost, inkind_other_source, inkind_other2, inkind_other2_text, inkind_other2_cost, inkind_other2_source, industrial_incash_salary, industrial_incash_salary_cost, industrial_incash_rents, industrial_incash_rents_cost, industrial_incash_traveling, industrial_incash_traveling_cost, industrial_incash_other, industrial_incash_other_text, industrial_incash_other_cost, industrial_incash_other2, industrial_incash_other2_text, industrial_incash_other2_cost, industrial_inkind_equipment, industrial_inkind_equipment_cost, industrial_inkind_other, industrial_inkind_other_text, industrial_inkind_other_cost, industrial_inkind_other2, industrial_inkind_other2_text, industrial_inkind_other2_cost', 'safe', 'on' => 'search'),
        );
    }
    
    public function reqNumberAndSharp($attribute, $params) {
        if (strlen($this->$attribute) > 0) {
            $num = str_replace("#", "", $this->$attribute);
            if (!is_numeric($num) && !empty($num) || strlen($num) < 1) {
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ถูกต้อง');
            }
        }
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scholars' => array(self::HAS_MANY, 'Scholar', 'scholar_tgist_id'),
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'project_id' => 'Project',
            'project_name' => 'Project Name',
            'project_begin' => 'Project Begin',
            'project_end' => 'Project End',
            'expect' => 'Expect',
            'cooperation' => 'Cooperation',
            'effect_new' => 'Effect New',
            'effect_cost' => 'Effect Cost',
            'effect_quality' => 'Effect Quality',
            'effect_environment' => 'Effect Environment',
            'effect_other' => 'Effect Other',
            'effect_other_text' => 'Effect Other Text',
            'relevance_automotive' => 'Relevance Automotive',
            'relevance_electronics' => 'Relevance Electronics',
            'relevance_tourism' => 'Relevance Tourism',
            'relevance_agriculture' => 'Relevance Agriculture',
            'relevance_food' => 'Relevance Food',
            'relevance_robotics' => 'Relevance Robotics',
            'relevance_aviation' => 'Relevance Aviation',
            'relevance_biofuels' => 'Relevance Biofuels',
            'relevance_digital' => 'Relevance Digital',
            'relevance_medical' => 'Relevance Medical',
            'itap' => 'Itap',
            'is_history' => 'Is History',
            'portfolio_thesis' => 'Portfolio Thesis',
            'portfolio_journal_international' => 'Portfolio Journal International',
            'portfolio_journal_international_amount' => 'Portfolio Journal International Amount',
            'portfolio_journal_incountry' => 'Portfolio Journal Incountry',
            'portfolio_journal_incountry_amount' => 'Portfolio Journal Incountry Amount',
            'portfolio_patent' => 'Portfolio Patent',
            'portfolio_patent_amount' => 'Portfolio Patent Amount',
            'portfolio_prototype' => 'Portfolio Prototype',
            'portfolio_prototype_amount' => 'Portfolio Prototype Amount',
            'portfolio_conference_international' => 'Portfolio Conference International',
            'portfolio_conference_international_amount' => 'Portfolio Conference International Amount',
            'portfolio_conference_incountry' => 'Portfolio Conference Incountry',
            'portfolio_conference_incountry_amount' => 'Portfolio Conference Incountry Amount',
            'portfolio_award' => 'Portfolio Award',
            'portfolio_award_amount' => 'Portfolio Award Amount',
            'portfolio_other_amount' => 'Portfolio Other Amount',
            'portfolio_other' => 'Portfolio Other',
            'portfolio_other_text' => 'Portfolio Other Text',
            'work_company' => 'Work Company',
            'work_location' => 'Work Location',
            'work_phone' => 'Work Phone',
            'work_fax' => 'Work Fax',
            'is_workwithproject' => 'Is Workwithproject',
            'workwithproject_text1' => 'Workwithproject Text1',
            'workwithproject_text2' => 'Workwithproject Text2',
            'workwithproject_text3' => 'Workwithproject Text3',
            'incash_fee' => 'Incash Fee',
            'incash_fee_cost' => 'Incash Fee Cost',
            'incash_fee_source' => 'Incash Fee Source',
            'incash_monthly' => 'Incash Monthly',
            'incash_monthly_cost' => 'Incash Monthly Cost',
            'incash_monthly_source' => 'Incash Monthly Source',
            'incash_other' => 'Incash Other',
            'incash_other_text' => 'Incash Other Text',
            'incash_other_cost' => 'Incash Other Cost',
            'incash_other_source' => 'Incash Other Source',
            'incash_other2' => 'Incash Other2',
            'incash_other2_text' => 'Incash Other2 Text',
            'incash_other2_cost' => 'Incash Other2 Cost',
            'incash_other2_source' => 'Incash Other2 Source',
            'inkind_other' => 'Inkind Other',
            'inkind_other_text' => 'Inkind Other Text',
            'inkind_other_cost' => 'Inkind Other Cost',
            'inkind_other_source' => 'Inkind Other Source',
            'inkind_other2' => 'Inkind Other2',
            'inkind_other2_text' => 'Inkind Other2 Text',
            'inkind_other2_cost' => 'Inkind Other2 Cost',
            'inkind_other2_source' => 'Inkind Other2 Source',
            'industrial_incash_salary' => 'Industrial Incash Salary',
            'industrial_incash_salary_cost' => 'Industrial Incash Salary Cost',
            'industrial_incash_rents' => 'Industrial Incash Rents',
            'industrial_incash_rents_cost' => 'Industrial Incash Rents Cost',
            'industrial_incash_traveling' => 'Industrial Incash Traveling',
            'industrial_incash_traveling_cost' => 'Industrial Incash Traveling Cost',
            'industrial_incash_other' => 'Industrial Incash Other',
            'industrial_incash_other_text' => 'Industrial Incash Other Text',
            'industrial_incash_other_cost' => 'Industrial Incash Other Cost',
            'industrial_incash_other2' => 'Industrial Incash Other2',
            'industrial_incash_other2_text' => 'Industrial Incash Other2 Text',
            'industrial_incash_other2_cost' => 'Industrial Incash Other2 Cost',
            'industrial_inkind_equipment' => 'Industrial Inkind Equipment',
            'industrial_inkind_equipment_cost' => 'Industrial Inkind Equipment Cost',
            'industrial_inkind_other' => 'Industrial Inkind Other',
            'industrial_inkind_other_text' => 'Industrial Inkind Other Text',
            'industrial_inkind_other_cost' => 'Industrial Inkind Other Cost',
            'industrial_inkind_other2' => 'Industrial Inkind Other2',
            'industrial_inkind_other2_text' => 'Industrial Inkind Other2 Text',
            'industrial_inkind_other2_cost' => 'Industrial Inkind Other2 Cost',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('project_id', $this->project_id);
        $criteria->compare('project_name', $this->project_name, true);
        $criteria->compare('project_begin', $this->project_begin, true);
        $criteria->compare('project_end', $this->project_end, true);
        $criteria->compare('expect', $this->expect, true);
        $criteria->compare('cooperation', $this->cooperation, true);
        $criteria->compare('effect_new', $this->effect_new);
        $criteria->compare('effect_cost', $this->effect_cost);
        $criteria->compare('effect_quality', $this->effect_quality);
        $criteria->compare('effect_environment', $this->effect_environment);
        $criteria->compare('effect_other', $this->effect_other);
        $criteria->compare('effect_other_text', $this->effect_other_text, true);
        $criteria->compare('relevance_automotive', $this->relevance_automotive);
        $criteria->compare('relevance_electronics', $this->relevance_electronics);
        $criteria->compare('relevance_tourism', $this->relevance_tourism);
        $criteria->compare('relevance_agriculture', $this->relevance_agriculture);
        $criteria->compare('relevance_food', $this->relevance_food);
        $criteria->compare('relevance_robotics', $this->relevance_robotics);
        $criteria->compare('relevance_aviation', $this->relevance_aviation);
        $criteria->compare('relevance_biofuels', $this->relevance_biofuels);
        $criteria->compare('relevance_digital', $this->relevance_digital);
        $criteria->compare('relevance_medical', $this->relevance_medical);
        $criteria->compare('itap', $this->itap);
        $criteria->compare('is_history', $this->is_history);
        $criteria->compare('portfolio_thesis', $this->portfolio_thesis, true);
        $criteria->compare('portfolio_journal_international', $this->portfolio_journal_international);
        $criteria->compare('portfolio_journal_international_amount', $this->portfolio_journal_international_amount);
        $criteria->compare('portfolio_journal_incountry', $this->portfolio_journal_incountry);
        $criteria->compare('portfolio_journal_incountry_amount', $this->portfolio_journal_incountry_amount);
        $criteria->compare('portfolio_patent', $this->portfolio_patent);
        $criteria->compare('portfolio_patent_amount', $this->portfolio_patent_amount);
        $criteria->compare('portfolio_prototype', $this->portfolio_prototype);
        $criteria->compare('portfolio_prototype_amount', $this->portfolio_prototype_amount);
        $criteria->compare('portfolio_conference_international', $this->portfolio_conference_international);
        $criteria->compare('portfolio_conference_international_amount', $this->portfolio_conference_international_amount);
        $criteria->compare('portfolio_conference_incountry', $this->portfolio_conference_incountry);
        $criteria->compare('portfolio_conference_incountry_amount', $this->portfolio_conference_incountry_amount);
        $criteria->compare('portfolio_award', $this->portfolio_award);
        $criteria->compare('portfolio_award_amount', $this->portfolio_award_amount);
        $criteria->compare('portfolio_other_amount', $this->portfolio_other_amount);
        $criteria->compare('portfolio_other', $this->portfolio_other);
        $criteria->compare('portfolio_other_text', $this->portfolio_other_text, true);
        $criteria->compare('work_company', $this->work_company, true);
        $criteria->compare('work_location', $this->work_location, true);
        $criteria->compare('work_phone', $this->work_phone, true);
        $criteria->compare('work_fax', $this->work_fax, true);
        $criteria->compare('is_workwithproject', $this->is_workwithproject);
        $criteria->compare('workwithproject_text1', $this->workwithproject_text1, true);
        $criteria->compare('workwithproject_text2', $this->workwithproject_text2, true);
        $criteria->compare('workwithproject_text3', $this->workwithproject_text3, true);
        $criteria->compare('incash_fee', $this->incash_fee);
        $criteria->compare('incash_fee_cost', $this->incash_fee_cost, true);
        $criteria->compare('incash_fee_source', $this->incash_fee_source, true);
        $criteria->compare('incash_monthly', $this->incash_monthly);
        $criteria->compare('incash_monthly_cost', $this->incash_monthly_cost, true);
        $criteria->compare('incash_monthly_source', $this->incash_monthly_source, true);
        $criteria->compare('incash_other', $this->incash_other);
        $criteria->compare('incash_other_text', $this->incash_other_text, true);
        $criteria->compare('incash_other_cost', $this->incash_other_cost, true);
        $criteria->compare('incash_other_source', $this->incash_other_source, true);
        $criteria->compare('incash_other2', $this->incash_other2);
        $criteria->compare('incash_other2_text', $this->incash_other2_text, true);
        $criteria->compare('incash_other2_cost', $this->incash_other2_cost, true);
        $criteria->compare('incash_other2_source', $this->incash_other2_source, true);
        $criteria->compare('inkind_other', $this->inkind_other);
        $criteria->compare('inkind_other_text', $this->inkind_other_text, true);
        $criteria->compare('inkind_other_cost', $this->inkind_other_cost, true);
        $criteria->compare('inkind_other_source', $this->inkind_other_source, true);
        $criteria->compare('inkind_other2', $this->inkind_other2);
        $criteria->compare('inkind_other2_text', $this->inkind_other2_text, true);
        $criteria->compare('inkind_other2_cost', $this->inkind_other2_cost, true);
        $criteria->compare('inkind_other2_source', $this->inkind_other2_source, true);
        $criteria->compare('industrial_incash_salary', $this->industrial_incash_salary);
        $criteria->compare('industrial_incash_salary_cost', $this->industrial_incash_salary_cost, true);
        $criteria->compare('industrial_incash_rents', $this->industrial_incash_rents);
        $criteria->compare('industrial_incash_rents_cost', $this->industrial_incash_rents_cost, true);
        $criteria->compare('industrial_incash_traveling', $this->industrial_incash_traveling);
        $criteria->compare('industrial_incash_traveling_cost', $this->industrial_incash_traveling_cost, true);
        $criteria->compare('industrial_incash_other', $this->industrial_incash_other);
        $criteria->compare('industrial_incash_other_text', $this->industrial_incash_other_text, true);
        $criteria->compare('industrial_incash_other_cost', $this->industrial_incash_other_cost, true);
        $criteria->compare('industrial_incash_other2', $this->industrial_incash_other2);
        $criteria->compare('industrial_incash_other2_text', $this->industrial_incash_other2_text, true);
        $criteria->compare('industrial_incash_other2_cost', $this->industrial_incash_other2_cost, true);
        $criteria->compare('industrial_inkind_equipment', $this->industrial_inkind_equipment);
        $criteria->compare('industrial_inkind_equipment_cost', $this->industrial_inkind_equipment_cost, true);
        $criteria->compare('industrial_inkind_other', $this->industrial_inkind_other);
        $criteria->compare('industrial_inkind_other_text', $this->industrial_inkind_other_text, true);
        $criteria->compare('industrial_inkind_other_cost', $this->industrial_inkind_other_cost, true);
        $criteria->compare('industrial_inkind_other2', $this->industrial_inkind_other2);
        $criteria->compare('industrial_inkind_other2_text', $this->industrial_inkind_other2_text, true);
        $criteria->compare('industrial_inkind_other2_cost', $this->industrial_inkind_other2_cost, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $scholar_tgist = scholar_tgist::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($scholar_tgist->id)) ? $scholar_tgist->id + 1 : 1;
    }

}
