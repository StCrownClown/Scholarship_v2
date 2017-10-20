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

    public $professor_id;
    public $professor_research_fund;
    public $professor_research_name;
    public $professor_research_id;
    public $professor_research_cluster;
    public $professor_program_research;
    public $professor_project_begin;
    public $professor_project_end;
    public $professor_budget;
    
    public $mentor_id;
    public $industrial_id;
    
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
            array('professor_research_fund, professor_research_name', 'safe'),
            array('professor_research_id, professor_research_cluster, professor_program_research, professor_project_begin, professor_project_end', 'safe'),
            array('professor_budget', 'safe'),
            array('scholar_education, project_name_th, project_name_en, scope, project_period, cooperation_nstda, cooperation_university, cooperation_industrial, project_profit, project_other_connect', 'reqByPersonType', 'student'),
        );
    }
    
    public function reqByPersonType($attribute, $params) {
        $person_type = Yii::app()->session['person_type'];
        if ($this->$attribute == NULL && $person_type == $params[0] && $this->$attribute != '0') {
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
            'expect' => 'ผลที่คาดว่าจะได้รับจากการรับนักศึกษาเพื่อทำวิจัยเรื่องนี้',
            
            'scholar_education' => 'ต้องการเสนอขอรับทุนในระดับ <span class="required">*</span>',
            'project_name_th' => 'ชื่อโครงการวิจัยเพื่อวิทยานิพนธ์ที่เสนอขอรับทุน(ภาษาไทย) <span class="required">*</span>',
            'project_name_en' => 'ชื่อโครงการวิจัยเพื่อวิทยานิพนธ์ที่เสนอขอรับทุน(ภาษาอังกฤษ) <span class="required">*</span>',
            'scope' => 'ขอบเขต แนวทางการศึกษาวิจัย และประโยชน์ที่จะได้รับจากงานวิจัย <span class="required">*</span>',
            'project_period' => 'แนวทางการศึกษาวิจัยใช้เวลาในการศึกษาวิจัยของนักศึกษา <span class="required">*</span>',
            'cooperation_nstda' => 'ลักษณะความร่วมมือในการทำวิทยานิพนธ์ของนิสิต/นักศึกษา และสวทช. <span class="required">*</span> '
                                . '<br>**ลักษณะความร่วมมือในการทำวิทยานิพนธ์ของนิสิต/นักศึกษา สวทช. มหาวิทยาลัย หรืออื่นๆ เช่น หน่วยงานเอกชน หรือหน่วยงานร่วมมืออื่นๆ แบ่งเป็นสัดส่วน โดยรวมกันแล้วได้ 100%',
            'cooperation_university' => 'ลักษณะความร่วมมือในการทำวิทยานิพนธ์ของนิสิต/นักศึกษา และมหาวิทยาลัย <span class="required">*</span>',
            'cooperation_industrial' => 'หรืออื่นๆ เช่น หน่วยงานเอกชน หรือหน่วยงานร่วมมืออื่นๆ <span class="required">*</span>',
            'project_profit' => 'ประโยชน์ที่คาดว่าจะได้รับจากงานวิจัย <span class="required">*</span> '
                            . '<br> ***ประโยชน์ที่คาดว่าจะได้รับจากงานวิจัยเพื่อวิทยานิพนธ์ที่ขอรับทุน(รายละเอียดอย่างย่อ)รายละเอียดที่ครบถ้วนสมบูรณ์กรุณากรอกใน แบบฟอร์มข้อเสนอโครงการ โดยดาวน์โหลดจากด้านบน แล้วอัพโหลดไฟล์ในหน้า Attachment ***',
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
