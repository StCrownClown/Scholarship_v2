<?php

/**
 * This is the model class for table "scholar_stem".
 *
 * The followings are the available columns in table 'scholar_stem':

 *
 * The followings are the available model relations:
 * @property Scholar[] $scholars
 * @property Project $project
 */
class StemStudentProjectForm extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return StemStudentProjectForm the static model class
     */
    public $id;
    public $project_name;
    public $project_begin;
    public $project_end;
    public $objective;
    public $expect;
    public $cooperation;
    
    public $effect_new;
    public $effect_cost;
    public $effect_quality;
    public $effect_environment;
    public $effect_other;
    public $effect_other_text;
    
    public $effect_new_desc;
    public $effect_cost_desc;
    public $effect_quality_desc;
    public $effect_environment_desc;
    public $effect_other_desc;
    
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
    
    public $relevance_automotive_desc;
    public $relevance_electronics_desc;
    public $relevance_tourism_desc;
    public $relevance_agriculture_desc;
    public $relevance_food_desc;
    public $relevance_robotics_desc;
    public $relevance_aviation_desc;
    public $relevance_biofuels_desc;
    public $relevance_digital_desc;
    public $relevance_medical_desc;
    
    public $func_period;
    public $last_updated;
    public $itap;
    
    public $mentor_has_professor;
    public $mentor_has_professor_prefix_id;
    public $mentor_has_professor_fname;
    public $mentor_has_professor_lname;
    public $mentor_has_professor_mobile;
    public $mentor_has_professor_email;    
    public $mentor_has_professor_institute_id;
    public $mentor_has_professor_institute_other;
    public $mentor_has_professor_faculty_id;
    public $mentor_has_professor_faculty_other;
    public $mentor_has_professor_relation;
            
    public $incash_sum;
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
    public $inkind_sum;
    public $inkind_other;
    public $inkind_other_text;
    public $inkind_other_cost;
    public $inkind_other_source;
    public $inkind_other2;
    public $inkind_other2_text;
    public $inkind_other2_cost;
    public $inkind_other2_source;
    public $sum;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scholar_stem';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('project_begin, project_end', 'PeriodBetween6M_12M', 'project_begin', 'project_end'),
            array('project_begin, project_end', 'PeriodBetweenPrimaryPrj', 'project_begin', 'project_end'),
            array('project_begin', 'limitStart', 'project_begin'),
            array('project_end', 'limitEnd', 'project_end'),
            array('objective,
                    expect,
                    cooperation', 'length', 'max' => 1500),
            array('effect_new_desc,
                    effect_cost_desc,
                    effect_quality_desc,
                    effect_environment_desc,
                    effect_other_desc,
                    relevance_automotive_desc,
                    relevance_electronics_desc,
                    relevance_tourism_desc,
                    relevance_agriculture_desc,
                    relevance_food_desc,
                    relevance_robotics_desc,
                    relevance_aviation_desc,
                    relevance_biofuels_desc,
                    relevance_digital_desc,
                    relevance_medical_desc', 'length', 'max' => 1000),
            array('incash_fee_source,
                    incash_monthly_source,
                    incash_other_source,
                    incash_other2_source,
                    inkind_other_source,
                    inkind_other2_source', 'length', 'max' => 150),
            array('mentor_has_professor_email', 'length', 'max' => 100),
            array('mentor_has_professor_email', 'email'),
            array('mentor_has_professor_mobile', 'length', 'max' => 10, 'min' => 10),
            array('mentor_has_professor_mobile', 'numerical', 'integerOnly'=>true),
            array('mentor_has_professor', 'reqByPersonType', 'mentor'),
            array('mentor_has_professor_prefix_id, '
                . 'mentor_has_professor_fname, '
                . 'mentor_has_professor_lname,'
                . 'mentor_has_professor_email,'
                . 'mentor_has_professor_relation',
                'reqIsOther',
                'mentor_has_professor'),
            array('mentor_has_professor_institute_id', 'reqIgnore2', 'mentor_has_professor'),
            array('mentor_has_professor_faculty_id', 'reqIgnore2', 'mentor_has_professor'),
            
            array('mentor_has_professor_institute_other, mentor_has_professor_faculty_other', 'length', 'max' => 200),
            array('mentor_has_professor_institute_other', 'reqOther', 'mentor_has_professor_institute_id'),
            array('mentor_has_professor_faculty_other', 'reqOther', 'mentor_has_professor_faculty_id'),
            
            array('project_name, project_begin, project_end, objective, expect', 'required'),
            array('project_name, effect_other_text', 'length', 'max' => 255),
            array('effect_new, effect_cost, effect_quality, effect_environment, effect_other, relevance_automotive, relevance_electronics, relevance_tourism, relevance_agriculture, relevance_food, relevance_robotics, relevance_aviation, relevance_biofuels, relevance_digital, relevance_medical, itap', 'length', 'max' => 10),
            array('project_begin, project_end, objective, expect, cooperation, func_period, last_updated', 'safe'),
            
            array('effect_new_desc', 'reqIsOther', 'effect_new'),
            array('effect_cost_desc', 'reqIsOther', 'effect_cost'),
            array('effect_quality_desc', 'reqIsOther', 'effect_quality'),
            array('effect_environment_desc', 'reqIsOther', 'effect_environment'),
            array('effect_other_text, effect_other_desc', 'reqIsOther', 'effect_other'),
            
            array('effect_new, effect_cost, effect_quality, effect_environment, effect_other',
                'reqCheckOnce',
                'effect_new',
                'effect_cost',
                'effect_quality',
                'effect_environment',
                'effect_other'),
            
            array('relevance_automotive_desc', 'reqIsOther', 'relevance_automotive'),
            array('relevance_electronics_desc', 'reqIsOther', 'relevance_electronics'),
            array('relevance_tourism_desc', 'reqIsOther', 'relevance_tourism'),
            array('relevance_agriculture_desc', 'reqIsOther', 'relevance_agriculture'),
            array('relevance_food_desc', 'reqIsOther', 'relevance_food'),
            array('relevance_robotics_desc', 'reqIsOther', 'relevance_robotics'),
            array('relevance_aviation_desc', 'reqIsOther', 'relevance_aviation'),
            array('relevance_biofuels_desc', 'reqIsOther', 'relevance_biofuels'),
            array('relevance_digital_desc', 'reqIsOther', 'relevance_digital'),
            array('relevance_medical_desc', 'reqIsOther', 'relevance_medical'),
                
            array('relevance_automotive, relevance_electronics, relevance_electronics, relevance_tourism, relevance_agriculture, relevance_food, relevance_robotics, relevance_aviation,relevance_biofuels,relevance_digital,relevance_medical',
                'reqCheckOnce',
                'relevance_automotive',
                'relevance_electronics',
                'relevance_tourism',
                'relevance_agriculture',
                'relevance_food',
                'relevance_robotics',
                'relevance_aviation',
                'relevance_biofuels',
                'relevance_digital',
                'relevance_medical'),
            array('incash_fee_cost, incash_fee_source', 'reqIsCheck', 'incash_fee'),
            array('incash_monthly_cost, incash_monthly_source', 'reqIsCheck', 'incash_monthly'),
            array('incash_other_text, incash_other_cost, incash_other_source', 'reqIsCheck', 'incash_other'),
            array('incash_other2_text, incash_other2_cost, incash_other2_source', 'reqIsCheck', 'incash_other2'),
            array('inkind_other_text, inkind_other_cost, inkind_other_source', 'reqIsCheck', 'inkind_other'),
            array('inkind_other2_text, inkind_other2_cost, inkind_other2_source', 'reqIsCheck', 'inkind_other2'),
            array('inkind_other2_cost, inkind_other_cost, incash_other2_cost, incash_other_cost,incash_monthly_cost,incash_fee_cost', 'numerical'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('inkind_other2_text, inkind_other2_cost, inkind_other2_source,inkind_other_text, inkind_other_cost, inkind_other_source,incash_other2_text, incash_other2_cost, incash_other2_source,incash_other_text, incash_other_cost, incash_other_source,incash_monthly_cost, incash_monthly_source,incash_fee_cost, incash_fee_source,inkind_other2, inkind_other, incash_other2, incash_other, incash_monthly, incash_fee', 'safe'),
            array('mentor_has_professor_mobile, id, project_name, project_begin, project_end, objective, expect, cooperation, effect_new, effect_cost, effect_quality, effect_environment, effect_other, effect_other_text, relevance_automotive, relevance_electronics, relevance_tourism, relevance_agriculture, relevance_food, relevance_robotics, relevance_aviation, relevance_biofuels, relevance_digital, relevance_medical, itap', 'safe', 'on' => 'search'),
        );
    }
    
    public function reqByPersonType($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        if ($this->$attribute == NULL && $person_type == $params[0] && $this->$attribute != '0') {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }
    
    public function reqOther($attribute, $params) {
        if ($this->$params[0] == '0' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }
    
    public function limitStart($attribute,$params){
        $begin_from = Yii::app()->params['stem_project_sub_begin_min'];
        $begin_max = date('Y-m-d', strtotime(Yii::app()->params['stem_project_sub_begin_min'] . ' +6 month'));
        $curr_begin = str_replace('-', '', ConfigWeb::formatDataViewToDB($this->$params[0]));
        $from = str_replace('-', '', $begin_from);
        if($curr_begin < $from){
            $this->addError($attribute, 'วันเริ่มโครงการย่อยต้องอยู่ในช่วง '
                    . ''.date("d/m/Y", strtotime($begin_from)).''
                    . ' ถึง '
                    . ''.date("d/m/Y", strtotime($begin_max)));
        }
//        if ($isOk)
//            $this->addError($attribute, 'วันเริ่มโครงการย่อยต้องไม่เกินวันที่ '
//                    .date("d/m/Y", strtotime($begin_from)));
    }
    
    public function limitEnd($attribute,$params){
        $end_last = Yii::app()->params['stem_project_end_max'];
        $curr_end = str_replace('-', '', ConfigWeb::formatDataViewToDB($this->$params[0])); 
        $last = str_replace('-', '', $end_last);
        if($curr_end > $last){
            $this->addError($attribute, 'วันเริ่มโครงการย่อยต้องไม่เกินวันที่ ' 
                    . date("d/m/Y", strtotime($end_last)));
        }
    }
    
    public function PeriodBetweenPrimaryPrj($attribute,$params){
        $begin = ConfigWeb::formatDataViewToDB($this->$params[0]);
        $end = ConfigWeb::formatDataViewToDB($this->$params[1]);
        $cur_start = new DateTime($begin);
        $cur_end = new DateTime($end);

        $criteria = new CDbCriteria ();
        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
        $criteria->limit = 1;
        $Scholar = Scholar::model()->find($criteria);

        $criteria->condition = "id = " . $Scholar->scholarStem->project_id;
        $criteria->limit = 1;
        $Project = Project::model()->find($criteria);

        $pk_begin = $Project->begin;
        $pk_end = $Project->end;

        $pk_begin = ConfigWeb::formatDataViewToDB($pk_begin);
        $pk_end = ConfigWeb::formatDataViewToDB($pk_end);
        $datetime_pk_begin = new DateTime($pk_begin);
        $datetime_pk_end = new DateTime($pk_end);

        if(!($datetime_pk_begin <= $cur_start && $datetime_pk_end >= $cur_end)){
            $this->addError($attribute, 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด<br/>(โครงการย่อยระยะเวลาต้องไม่เกินโครงการหลัก '.date('d/m/Y', strtotime($pk_begin)).' - '.date('d/m/Y', strtotime($pk_end)).')');
        }
    }
    
    public function PeriodBetween6M_12M($attribute,$params){
        $begin = $this->$params[0];
        $end = $this->$params[1];
	$diff = ConfigWeb::GetPeriodDate($begin, $end);
        if ($diff) {
            if(!($diff->m >= 6 && $diff->m <= 11 || ($diff->y == 1 && $diff->m == 0 && $diff->d == 0))){
                $this->addError($attribute, 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด<br/>(ระยะเวลาต้องไม่ต่ำกว่า 6 เดือนหรือมากกว่า 12 เดือน)');
            }
            if($diff->d != 0){
                $this->addError($attribute, 'ระยะเวลาโครงการจะต้องคำนวนออกมาเป็นจำนวนเต็ม เช่น 6 เดือน หรือ 12 เดือน (ไม่ต้องมีวัน) ');
            }
        }
    }
    
    public function reqCheckOnce($attribute, $params) {
        $cnt = 0;
        foreach ($params as &$value) {
            $cnt = $cnt + $this->$value;
        }

        if ($cnt == 0) {
            $this->addError($attribute, 'โปรดเลือกอย่างน้อย 1 / Please select one.');
        }
    }

    public function reqIsCheck($attribute, $params) {
        if ($this->$params[0] == '1' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }

    public function reqIsOther($attribute, $params) {
        if (!empty($this->$params[0]) && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง / Should not be blank');
        }
    }
    
    public function reqIgnore2($attribute, $params) {
        if (!is_numeric($this->$attribute)) {
            if(!empty($this->$params[0]) && empty($this->$attribute))
                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scholars' => array(self::HAS_MANY, 'Scholar', 'scholar_stem_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'project_name' => 'ชื่อโครงการย่อย / Project Name',
            'project_begin' => 'วันเริ่มโครงการย่อย / Begin',
            'project_end' => 'วันสิ้นสุดโครงการย่อย / End',
            'func_period' => 'ระยะเวลาโครงการย่อย / Period',
            'objective' => 'วัตถุประสงค์ของโครงการย่อย'
            . ' / Objective for this project.',
            'expect' => 'ผลที่คาดหวังในเชิงเศรษฐกิจ สังคม หรืออื่นๆ หลังจากการทำโครงการวิจัยย่อยนี้'
            . ' / The expected results in terms of economic, social or other after doing this project.',
            'cooperation' => 'ความร่วมมือทางวิชาการและงานวิจัยระหว่างอาจารย์และนักวิจัย สวทช.ที่ผ่านมา และในอนาคต(หากมี)'
            . ' / Technical cooperation and research between professors and NSTDA researchers. The past and the future (if any).',
            'effect_new' => 'การสร้างผลิตภัณฑ์ใหม่',
            'effect_cost' => 'การลดต้นทุนการผลิต',
            'effect_quality' => 'การเพิ่มคุณภาพการผลิต',
            'effect_environment' => 'การแก้ปัญหาสิ่งแวดล้อม',
            'effect_other' => 'อื่นๆ',
            'effect_other_text' => 'อื่นๆ',
            
            'effect_new_desc' => 'โปรดระบุ  <span class="required">*</span>',
            'effect_cost_desc' => 'โปรดระบุ  <span class="required">*</span>',
            'effect_quality_desc' => 'โปรดระบุ  <span class="required">*</span>',
            'effect_environment_desc' => 'โปรดระบุ  <span class="required">*</span>',
            'effect_other_desc' => 'โปรดระบุ  <span class="required">*</span>',
            
            'relevance_automotive' => 'อุตสาหกรรมยานยนต์สมัยใหม่ (Next – Generation Automotive)',
            'relevance_electronics' => 'อุตสาหกรรมอิเล็กทรอนิกส์อัจฉริยะ (Smart Electronics)',
            'relevance_tourism' => 'อุตสาหกรรมการท่องเที่ยวกลุ่มรายได้ดีและการท่องเที่ยวเชิงสุขภาพ (Affluent, Medical and Wellness Tourism)',
            'relevance_agriculture' => 'การเกษตรและเทคโนโลยีชีวภาพ (Agriculture and Biotechnolgy)',
            'relevance_food' => 'อุตสาหกรรมการแปรรูปอาหาร (Food for the Future)',
            'relevance_robotics' => 'อุตสาหกรรมหุ่นยนต์ (Robotics)',
            'relevance_aviation' => 'อุตสาหกรรมการบินและโลจิสติกส์ (Aviation and Logistics)',
            'relevance_biofuels' => 'อุตสาหกรรมเชื้อเพลิงชีวภาพและเคมีชีวภาพ (Biofuels and Biochemicals)',
            'relevance_digital' => 'อุตสาหกรรมดิจิตอล (Digital)',
            'relevance_medical' => 'อุตสาหกรรมการแพทย์ครบวงจร (Medical Hub)',
            
            'relevance_automotive_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_electronics_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_tourism_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_agriculture_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_food_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_robotics_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_aviation_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_biofuels_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_digital_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            'relevance_medical_desc'=> 'โปรดระบุ  <span class="required">*</span>',
            
            'itap' => 'ITAP',
            
            'mentor_has_professor' => 'อาจารย์ที่ปรึกษาที่เกี่ยวข้องกับงานวิจัย',
            'mentor_has_professor_prefix_id' => 'คำนำหน้า / Prefix <span class="required">*</span>',
            'mentor_has_professor_fname' => 'ชื่อ / Firstname <span class="required">*</span>',
            'mentor_has_professor_lname' => 'นามสกุล / Lastname <span class="required">*</span>',
            'mentor_has_professor_mobile' => 'เบอร์มือถือ / Mobile',
            'mentor_has_professor_email' => 'อีเมล์ / Email <span class="required">*</span>',   
            'mentor_has_professor_institute_id' => 'สถาบัน / Institute <span class="required">*</span>',
            'mentor_has_professor_institute_other' => 'อื่นๆ / Other <span class="required">*</span>',
            'mentor_has_professor_faculty_id' => 'คณะ / Faculty <span class="required">*</span>',
            'mentor_has_professor_faculty_other' => 'อื่นๆ / Other <span class="required">*</span>',
            'mentor_has_professor_relation' => 'ความเกี่ยวข้องกับโครงการวิจัย <span class="required">*</span>',
    
            'incash_fee' => 'ค่าเทอม / Fee',
            'incash_fee_cost' => 'ค่าเทอม / Fee',
            'incash_fee_source' => 'แหล่งทุน / Source Funding',
            'incash_monthly' => 'ค่าใช้จ่ายรายเดือน / Monthly Charges',
            'incash_monthly_cost' => 'ค่าใช้จ่ายรายเดือน / Monthly Charges',
            'incash_monthly_source' => 'แหล่งทุน / Source Funding',
            'incash_other' => 'อื่นๆ / Other',
            'incash_other_cost' => 'อื่นๆ / Other',
            'incash_other_text' => 'อื่นๆ / Other',
            'incash_other_source' => 'แหล่งทุน / Source Funding',
            'incash_other2' => 'อื่นๆ / Other',
            'incash_other2_text' => 'อื่นๆ / Other',
            'incash_other2_cost' => 'อื่นๆ / Other',
            'incash_other2_source' => 'แหล่งทุน / Source Funding',
            'inkind_other' => 'อื่นๆ / Other',
            'inkind_other_cost' => 'อื่นๆ / Other',
            'inkind_other_text' => 'อื่นๆ / Other',
            'inkind_other_source' => 'แหล่งทุน / Source Funding',
            'inkind_other2' => 'อื่นๆ / Other',
            'inkind_other2_cost' => 'อื่นๆ / Other',
            'inkind_other2_text' => 'อื่นๆ / Other',
            'inkind_other2_source' => 'แหล่งทุน / Source Funding',
            'sum' => 'สรุปประมาณค่าใช้จ่ายโครงการย่อย',
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
        $criteria->compare('effect_new', $this->effect_new, true);
        $criteria->compare('effect_cost', $this->effect_cost, true);
        $criteria->compare('effect_quality', $this->effect_quality, true);
        $criteria->compare('effect_environment', $this->effect_environment, true);
        $criteria->compare('effect_other', $this->effect_other, true);
        $criteria->compare('effect_other_text', $this->effect_other_text, true);
        $criteria->compare('relevance_automotive', $this->relevance_automotive, true);
        $criteria->compare('relevance_electronics', $this->relevance_electronics, true);
        $criteria->compare('relevance_tourism', $this->relevance_tourism, true);
        $criteria->compare('relevance_agriculture', $this->relevance_agriculture, true);
        $criteria->compare('relevance_food', $this->relevance_food, true);
        $criteria->compare('relevance_robotics', $this->relevance_robotics, true);
        $criteria->compare('relevance_aviation', $this->relevance_aviation, true);
        $criteria->compare('relevance_biofuels', $this->relevance_biofuels, true);
        $criteria->compare('relevance_digital', $this->relevance_digital, true);
        $criteria->compare('relevance_medical', $this->relevance_medical, true);
        $criteria->compare('itap', $this->itap, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $scholar_stem = scholar_stem::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($scholar_stem->id)) ? $scholar_stem->id + 1 : 1;
    }

}
