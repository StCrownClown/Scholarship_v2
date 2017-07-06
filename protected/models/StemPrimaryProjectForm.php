<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class StemPrimaryProjectForm extends CActiveRecord {

    public $id;
    public $project_id;
    public $creater_id;
    public $name;
    public $objective;
    public $scope;
    public $begin;
    public $end;
    public $funding;
    public $funding_name;
    public $funding_code;
    public $funding_code_name;
    public $funding_etc;
    public $budget;
    public $func_period;
    public $last_updated;
    public $CHECKBOX_FUNDING_SOURCE = 'source';
    public $CHECKBOX_FUNDING_NSTDA = 'nstda';
    public $CHECKBOX_FUNDING_OTHER = 'other';
    public $CHECKBOX_FUNDING_NONE = 'none';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'project';
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('project_id, budget', 'required', 'on' => 'next'),
            array('budget', 'greaterThanZero'),
            array('begin', 'limitStart'),
            array('end', 'limitEnd'),
            array('begin', 'PeriodMore6M', 'begin', 'end'),
            array('end', 'PeriodMore6M', 'begin', 'end'),
            array('name, objective, scope, begin, end, funding', 'required'),
            array('funding_name, funding_code_name, funding_etc', 'length', 'max' => 100),
            array('funding_code, budget', 'length', 'max' => 20),
            array('budget', 'numerical'),
            array('funding_name', 'reqIsOther', 'funding'),
            array('funding_code, funding_code_name', 'reqIsOther', 'funding'),
            array('funding_etc', 'reqIsOther', 'funding'),
            array('func_period, last_updated , id, funding_code_name', 'safe')
        );
    }
    
    public function limitStart($attribute,$params){
        date_default_timezone_set('UTC');
        $begin_from = Yii::app()->params['stem_project_begin_min'];
        $begin_to = date('Y-m-d', strtotime(Yii::app()->params['stem_project_sub_begin_min'] . ' +6 month'));
        $begin = str_replace('-', '', $this->$attribute);
        $from = str_replace('-', '', $begin_from);
        $to = str_replace('-', '', $begin_to);
        if($begin < $from || $begin > $to){
            $this->addError($attribute, 'วันเริ่มโครงการหลักต้องไม่เกินวันที่ '
                    . ''.date("d/m/Y", strtotime($begin_to)) );
        }
    }
    
    public function limitEnd($attribute,$params){
        date_default_timezone_set('UTC');
        $end_last = Yii::app()->params['stem_project_sub_begin_min'];
        $end_last = date('Y-m-d', strtotime($end_last . ' +6 month -1 day'));
        $end = str_replace('-', '', $this->$attribute); 
        $last = str_replace('-', '', $end_last);
        if($end < $last){
            $this->addError($attribute, 'วันสิ้นสุดโครงการหลักต้องมากกว่าวันที่ ' . date("d/m/Y", strtotime($end_last)));
        }
    }
    
    public function PeriodMore6M($attribute,$params){
        $begin = $this->$params[0];
        $end = $this->$params[1];
	$diff = ConfigWeb::GetPeriodDate($begin, $end);
        if ($diff) {
            if($diff->m < 6 && $diff->y == 0){
                $this->addError($attribute, 'ระยะเวลาโครงการหลักไม่ตรงกับข้อกำหนด (ระยะเวลาต้องไม่ต่ำกว่า 6 เดือน)');
            }
        }
    }
    
    public function greaterThanZero($attribute,$params){
        if ($this->$attribute<=0)
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ต้องมากกว่า 0 บาท');
    }
 
    public function reqIsOther($attribute, $params) {
        if ($this->$params[0] == $this->CHECKBOX_FUNDING_SOURCE && $attribute == 'funding_name' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง / Should not be blank');
        } else if ($this->$params[0] == $this->CHECKBOX_FUNDING_NSTDA && ($attribute == 'funding_code' || $attribute == 'funding_code_name') && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง / Should not be blank');
        } else if ($this->$params[0] == $this->CHECKBOX_FUNDING_OTHER && $attribute == 'funding_etc' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง / Should not be blank');
        }
    }

    public function relations() {
        return array(
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
            'creater' => array(self::BELONGS_TO, 'Person', 'creater_id'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'project_id' => 'เลือกโครงการวิจัยหลัก / Select <span class="required">*</span>',
            'name' => 'ชื่อโครงการหลัก / Project Name',
            'objective' => 'วัตถุประสงค์ของโครงการหลัก / Objective',
            'scope' => 'ขอบเขตแนวทางการศึกษาวิจัย / Scope',
            'begin' => 'วันเริ่มโครงการหลัก / Begin',
            'end' => 'วันสิ้นสุดโครงการหลัก / End',
            'funding' => 'แหล่งทุนวิจัย / Research Funding',
            'funding_name' => 'ระบุชื่อแหล่งทุน <span class="required">*</span>',
            'funding_code' => 'ระบุรหัสโครงการ(PXXXXXXX) <span class="required">*</span>',
            'funding_code_name' => 'ระบุชื่อโครงการ <span class="required">*</span>',
            'funding_etc' => 'อื่นๆ โปรดระบุ <span class="required">*</span>',
            'budget' => 'งบประมาณโครงการหลัก (บาท) ไม่ต้องใส่ , เช่น 1000000 เป็นต้น <span class="required">*</span>',
            'func_period' => 'ระยะเวลาโครงการหลัก / Period',
        );
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
