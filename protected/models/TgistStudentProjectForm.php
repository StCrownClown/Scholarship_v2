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
class TgistStudentProjectForm extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TgistStudentProjectForm the static model class
     */
    
    public $scholar_id;
    public $scholar_education;
    public $project_name_th;
    public $project_name_en;
    public $expect;
    public $scope;
    public $project_period;
    public $cooperation_nstda;
    public $cooperation_university;
    public $cooperation_industrial;
    public $project_profit;
    public $project_other_connect;
    public $knowledge;
    public $new_process;
    public $new_technology;
    public $project_prototype_ind;
    public $project_prototype_gnd;
    public $project_prototype_lab;
    public $project_commercial;
    public $project_public;
    public $academic_article_nat;
    public $academic_article_int;
    public $project_conference_nat;
    public $project_conference_int;
    public $period_in_industrial;
    public $final_report;
    public $technical_paper;
    public $textbook;
    public $thesis;
    public $others;
    public $course_should_study;
    public $professor_support;

    public $professor_id;
    public $professor_accord_nstda;
    public $professor_nstda_cooperation;
    public $professor_research_fund;
    public $professor_research_name;
    public $professor_research_id;
    public $professor_research_cluster;
    public $professor_program_research;
    public $professor_project_begin;
    public $professor_project_end;
    public $professor_budget;
    
    public $mentor_id;
    public $mentor_accord_nstda;
    public $mentor_nstda_cooperation;
    
    public $industrial_id;
    public $industrial_accord_nstda;
    public $industrial_nstda_cooperation;
    
    public $scholar_status;
    public $status;

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
            array('project_name_th', 'match',
                'pattern' => '/^[ก-เ._ \s]+$/',
                'message' => 'ต้องกรอกเป็นภาษาไทยเท่านั้น'),
            array('project_name_en', 'match',
                'pattern' => '/^[a-zA-Z._ \s]+$/',
                'message' => 'ต้องกรอกเป็นภาษาอังกฤษเท่านั้น'),
            array('knowledge,new_process,new_technology,project_prototype_ind,project_prototype_gnd,project_prototype_lab', 'numerical', 'integerOnly'=>true),
            array('academic_article_nat,academic_article_int,project_conference_nat,project_conference_int,final_report,technical_paper', 'numerical', 'integerOnly'=>true),
            array('knowledge,new_process,new_technology,project_prototype_ind,project_prototype_gnd,project_prototype_lab', 'length', 'max' => 3),
            array('academic_article_nat,academic_article_int,project_conference_nat,project_conference_int,final_report,technical_paper', 'length', 'max' => 2),
            array('knowledge, new_process, new_technology, final_report, technical_paper, textbook, thesis, others, scholar_status, status, expect, scope', 'safe'),
            array('scholar_education, cooperation_nstda, cooperation_university, cooperation_industrial, project_period, project_commercial, project_public', 'safe'),
            array('project_profit, project_other_connect, project_prototype_ind, project_prototype_gnd, project_prototype_lab, project_commercial', 'safe'),
            array('project_public, academic_article_nat, academic_article_int, project_conference_nat, project_conference_int, period_in_industrial', 'safe'),
            array('professor_support, professor_accord_nstda, professor_nstda_cooperation, professor_research_fund, professor_research_name', 'safe'),
            array('professor_research_id, professor_research_cluster, professor_program_research, professor_project_begin, professor_project_end', 'safe'),
            array('professor_budget, mentor_accord_nstda, mentor_nstda_cooperation, industrial_accord_nstda, industrial_nstda_cooperation, course_should_study', 'safe'),
            array('expect, scholar_education, project_name_th, project_name_en, scope, project_period, cooperation_nstda, cooperation_university, cooperation_industrial, project_profit, project_other_connect, course_should_study, professor_support', 'reqOther'),
            array('mentor_accord_nstda, mentor_nstda_cooperation', 'reqByPersonType', 'mentor'),
            array('professor_nstda_cooperation, professor_accord_nstda', 'reqByPersonType', 'professor'),
            array('industrial_accord_nstda, industrial_nstda_cooperation', 'reqByPersonType', 'industrial'),
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

//    public function limitStart($attribute,$params){
//        $begin_from = Yii::app()->params['tgist_project_sub_begin_min'];
//        $begin_max = date('Y-m-d', strtotime(Yii::app()->params['tgist_project_sub_begin_min'] . ' +6 month'));
//        $curr_begin = str_replace('-', '', ConfigWeb::formatDataViewToDB($this->$params[0]));
//        $from = str_replace('-', '', $begin_from);
//        if($curr_begin < $from){
//            $this->addError($attribute, 'วันเริ่มโครงการย่อยต้องอยู่ในช่วง '
//                    . ''.date("d/m/Y", strtotime($begin_from)).''
//                    . ' ถึง '
//                    . ''.date("d/m/Y", strtotime($begin_max)));
//        }
////        if ($isOk)
////            $this->addError($attribute, 'วันเริ่มโครงการย่อยต้องไม่เกินวันที่ '
////                    .date("d/m/Y", strtotime($begin_from)));
//    }
//    
//    public function limitEnd($attribute,$params){
//        $end_last = Yii::app()->params['tgist_project_end_max'];
//        $curr_end = str_replace('-', '', ConfigWeb::formatDataViewToDB($this->$params[0])); 
//        $last = str_replace('-', '', $end_last);
//        if($curr_end > $last){
//            $this->addError($attribute, 'วันเริ่มโครงการย่อยต้องไม่เกินวันที่ ' 
//                    . date("d/m/Y", strtotime($end_last)));
//        }
//    }
//    
//    public function PeriodBetweenPrimaryPrj($attribute,$params){
//        $begin = ConfigWeb::formatDataViewToDB($this->$params[0]);
//        $end = ConfigWeb::formatDataViewToDB($this->$params[1]);
//        $cur_start = new DateTime($begin);
//        $cur_end = new DateTime($end);
//
//        $criteria = new CDbCriteria ();
//        $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
//        $criteria->limit = 1;
//        $Scholar = Scholar::model()->find($criteria);
//
//        $criteria->condition = "id = " . $Scholar->ScholarTgist->project_id;
//        $criteria->limit = 1;
//        $Project = Project::model()->find($criteria);
//
//        $pk_begin = $Project->begin;
//        $pk_end = $Project->end;
//
//        $pk_begin = ConfigWeb::formatDataViewToDB($pk_begin);
//        $pk_end = ConfigWeb::formatDataViewToDB($pk_end);
//        $datetime_pk_begin = new DateTime($pk_begin);
//        $datetime_pk_end = new DateTime($pk_end);
//
//        if(!($datetime_pk_begin <= $cur_start && $datetime_pk_end >= $cur_end)){
//            $this->addError($attribute, 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด<br/>(โครงการย่อยระยะเวลาต้องไม่เกินโครงการหลัก '.date('d/m/Y', strtotime($pk_begin)).' - '.date('d/m/Y', strtotime($pk_end)).')');
//        }
//    }
//    
//    public function PeriodBetween6M_12M($attribute,$params){
//        $begin = $this->$params[0];
//        $end = $this->$params[1];
//	$diff = ConfigWeb::GetPeriodDate($begin, $end);
//        if ($diff) {
//            if(!($diff->m >= 6 && $diff->m <= 11 || ($diff->y == 1 && $diff->m == 0 && $diff->d == 0))){
//                $this->addError($attribute, 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด<br/>(ระยะเวลาต้องไม่ต่ำกว่า 6 เดือนหรือมากกว่า 12 เดือน)');
//            }
//        }
//    }
//    
//    public function reqCheckOnce($attribute, $params) {
//        $cnt = 0;
//        foreach ($params as &$value) {
//            $cnt = $cnt + $this->$value;
//        }
//
//        if ($cnt == 0) {
//            $this->addError($attribute, 'โปรดเลือกอย่างน้อย 1 / Please select one.');
//        }
//    }
//
//    public function reqIsCheck($attribute, $params) {
//        if ($this->$params[0] == '1' && empty($this->$attribute)) {
//            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
//        }
//    }
//
//    public function reqIsOther($attribute, $params) {
//        if (!empty($this->$params[0]) && empty($this->$attribute)) {
//            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง / Should not be blank');
//        }
//    }
//    
//    public function reqIgnore2($attribute, $params) {
//        if (!is_numeric($this->$attribute)) {
//            if(!empty($this->$params[0]) && empty($this->$attribute))
//                $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
//        }
//    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scholars' => array(self::HAS_MANY, 'Scholar', 'scholar_tgist_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'project_begin' => 'วันเริ่มโครงการย่อย',
            'project_end' => 'วันสิ้นสุดโครงการย่อย',
            'expect' => 'ผลที่คาดว่าจะได้รับจากการรับนักศึกษาเพื่อทำวิจัยเรื่องนี้ <span class="required">*</span>',
            
            'scholar_education' => 'ต้องการเสนอขอรับทุนในระดับ <span class="required">*</span>',
            'project_name_th' => 'ชื่อโครงการวิจัยเพื่อวิทยานิพนธ์ที่เสนอขอรับทุน(ภาษาไทย) <span class="required">*</span>',
            'project_name_en' => 'ชื่อโครงการวิจัยเพื่อวิทยานิพนธ์ที่เสนอขอรับทุน(ภาษาอังกฤษ) <span class="required">*</span>',
            'scope' => 'ขอบเขต แนวทางการศึกษาวิจัย และประโยชน์ที่จะได้รับจากงานวิจัย <span class="required">*</span>',
            'project_period' => 'แนวทางการศึกษาวิจัยใช้เวลาในการศึกษาวิจัยของนักศึกษา <span class="required">*</span>',
            'cooperation_nstda' => 'ลักษณะความร่วมมือในการทำวิทยานิพนธ์ของนิสิต/นักศึกษา และสวทช. <span class="required">*</span>',
            'cooperation_university' => 'ลักษณะความร่วมมือในการทำวิทยานิพนธ์ของนิสิต/นักศึกษา และมหาวิทยาลัย <span class="required">*</span>',
            'cooperation_industrial' => 'หรืออื่นๆ เช่น หน่วยงานเอกชน หรือหน่วยงานร่วมมืออื่นๆ <span class="required">*</span>',
            'project_profit' => 'ประโยชน์ที่คาดว่าจะได้รับจากงานวิจัย <span class="required">*</span>',
            'project_other_connect' => 'โครงการวิจัยเพื่อวิทยานิพนธ์นี้มีส่วนเกี่ยวข้องหรือเชื่อมโยงภาคเอกชน ผู้ประกอบการหรือองค์กรอื่นๆหรือไม่ อย่างไร <span class="required">*</span>',
            'knowledge' => 'องค์ความรู้ จำนวน(เรื่อง)',
            'new_process' => 'กระบวนการใหม่ จำนวน(กระบวนการ)',
            'new_technology' => 'เทคโนโลยีใหม่  จำนวน(เทคโนโลยี)',
            'project_prototype_ind' => 'ต้นแบบ พร้อมใช้(อุตสาหกรรม) จำนวน(ต้นแบบ)',
            'project_prototype_gnd' => 'ต้นแบบ ระดับภาคสนาม จำนวน(ต้นแบบ)',
            'project_prototype_lab' => 'ต้นแบบ ระดับห้องปฏิบัติการ จำนวน(ต้นแบบ)',
            'project_commercial' => 'การนำไปใช้ประโยชน์เชิงพาณิชย์',
            'project_public' => 'การใช้ประโยชน์เชิงสาธารณประโยชน์ ',
            'academic_article_nat' => 'บทความทางวิชาการ วารสารระดับชาติ จำนวน(เรื่อง)',
            'academic_article_int' => 'บทความทางวิชาการ วารสารระดับนานาชาติ จำนวน(เรื่อง)',
            'project_conference_nat' => 'การเสนอผลงานในประชุม / สัมมนาระดับชาติ จำนวน(เรื่อง)',
            'project_conference_int' => 'การเสนอผลงานในประชุม / สัมมนาระดับนานาชาติ จำนวน(เรื่อง)',
            'period_in_industrial' => 'ระยะเวลาการเข้าปฏิบัติงานในอุตสาหกรรม',
            'final_report' => 'เอกสารทางวิชาการ รายงานฉบับสมบูรณ์(Final Report) จำนวน(ฉบับ)',
            'technical_paper' => 'เอกสารทางวิชาการ สรุปย่อทางเทคนิค(Technical Paper) จำนวน(ฉบับ)',
            'textbook' => 'หนังสือ/ตำราวิชาการ (Copyright)',
            'thesis' => 'วิทยานิพนธ์ (รายงานโครงงาน)',
            'others' => 'อื่นๆ (หากมี)',
            'course_should_study' => 'รายวิชาที่นักศึกษาควรต้องลงเรียนเพื่อให้สามารถทำงานวิจัยได้(โปรดระบุ อย่างน้อย 2รายวิชา) <span class="required">*</span>',
            'professor_support' => 'อาจารย์ที่ปรึกษา  <span class="required">*</span>',
            'professor_accord_nstda' => 'ความสอดคล้องของโครงงานวิจัยกับเป้าหมายของ สวทช. <span class="required">*</span>',
            'professor_nstda_cooperation' => 'ความร่วมมือทางวิชาการและงานวิจัยระหว่างอาจารย์ที่ปรึกษาและ สวทช. ที่ผ่านมา และในอนาคต <span class="required">*</span>',
            'mentor_accord_nstda' => 'ความสอดคล้องของโครงงานวิจัยกับเป้าหมายของ สวทช. <span class="required">*</span>',
            'mentor_nstda_cooperation' => 'ความร่วมมือทางวิชาการและงานวิจัยระหว่างอาจารย์ที่ปรึกษาและ สวทช. ที่ผ่านมา และในอนาคต <span class="required">*</span>',
            'industrial_accord_nstda' => 'ความสอดคล้องของโครงงานวิจัยกับเป้าหมายของ สวทช. <span class="required">*</span>',
            'industrial_nstda_cooperation' => 'ความร่วมมือทางวิชาการและงานวิจัยระหว่างอาจารย์ที่ปรึกษาและ สวทช. ที่ผ่านมา และในอนาคต <span class="required">*</span>',
            
//            'scholar_id' => 'Scholar',
//            'professor_id' => 'อาจารย์ที่ปรึกษา',
//            'mentor_id' => 'นักวิจัย สวทช.',
//            'Industrial_id' => 'นักวิจัย อุตสาหกรรม',
            'status' => 'Status',
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
        $criteria->compare('status', $this->status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

//     public function getID() {
//         $scholar_tgist = scholar_tgist::model()->find(array(
//             'select' => 'max(id) as id',
//         ));
//         return (isset($scholar_tgist->id)) ? $scholar_tgist->id + 1 : 1;
//     }

}
