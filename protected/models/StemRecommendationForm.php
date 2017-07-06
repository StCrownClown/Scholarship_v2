<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class StemRecommendationForm extends CFormModel {

    public $id;
    public $student_name;
    public $professor_name;
    public $mentor_name;
    public $industrial_name;
    public $industrial_full;
    public $project_name;
    public $project_objective;
    public $project_scope;
    public $project_student_name;
    public $project_student_begin;
    public $project_student_end;
    public $project_student_func;
    public $project_student_objective;
    public $project_student_expect;
    public $industrial_support;
    public $comment;
    public $status;
    public $industrial_support_desc;
    public $scholar_stem_id;
    public $incash_sum;
    public $industrial_incash_salary;
    public $industrial_incash_salary_cost;
    public $industrial_incash_rents;
    public $industrial_incash_rents_cost;
    public $industrial_incash_traveling;
    public $industrial_incash_traveling_cost;
    public $industrial_incash_other;
    public $industrial_incash_other_cost;
    public $industrial_incash_other_text;
    public $industrial_incash_other2;
    public $industrial_incash_other2_cost;
    public $industrial_incash_other2_text;
    public $inkind_sum;
    public $industrial_inkind_equipment;
    public $industrial_inkind_equipment_cost;
    public $industrial_inkind_other;
    public $industrial_inkind_other_cost;
    public $industrial_inkind_other_text;
    public $industrial_inkind_other2;
    public $industrial_inkind_other2_cost;
    public $industrial_inkind_other2_text;
    public $sum;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('comment, industrial_support_desc', 'required'),
            array('industrial_incash_salary,'
                . 'industrial_incash_rents,'
                . 'industrial_incash_traveling,'
                . 'industrial_incash_other,'
                . 'industrial_incash_other2,'
                . 'industrial_inkind_equipment,'
                . 'industrial_inkind_other,'
                . 'industrial_inkind_other2'
                , 'safe'),
            array('industrial_incash_salary_cost,'
                . 'industrial_incash_rents_cost,'
                . 'industrial_incash_traveling_cost,'
                . 'industrial_incash_other_cost,'
                . 'industrial_incash_other2_cost,'
                . 'industrial_inkind_equipment_cost,'
                . 'industrial_inkind_other_cost,'
                . 'industrial_inkind_other2_cost'
                , 'numerical'),
            array('industrial_incash_other_text,'
                . 'industrial_incash_other2_text,'
                . 'industrial_inkind_other_text,'
                . 'industrial_inkind_other2_text', 'length', 'max' => 150),
            array('industrial_incash_salary_cost', 'reqIsCheck', 'industrial_incash_salary'),
            array('industrial_incash_rents_cost', 'reqIsCheck', 'industrial_incash_rents'),
            array('industrial_incash_traveling_cost', 'reqIsCheck', 'industrial_incash_traveling'),
            array('industrial_incash_other_cost, industrial_incash_other_text', 'reqIsCheck', 'industrial_incash_other'),
            array('industrial_incash_other2_cost, industrial_incash_other2_text', 'reqIsCheck', 'industrial_incash_other2'),
            array('industrial_inkind_equipment_cost', 'reqIsCheck', 'industrial_inkind_equipment'),
            array('industrial_inkind_other_cost, industrial_inkind_other_text', 'reqIsCheck', 'industrial_inkind_other'),
            array('industrial_inkind_other2_cost, industrial_inkind_other2_text', 'reqIsCheck', 'industrial_inkind_other2'),
        );
    }

    public function reqIsCheck($attribute, $params) {
        if ($this->$params[0] == '1' && empty($this->$attribute)) {
            $this->addError($attribute, $this->attributeLabels()[$attribute] . ' ไม่ควรเป็นค่าว่าง');
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'student_name' => 'นักเรียน/นักศึกษา / Student',
            'professor_name' => 'อาจารย์ / Professor',
            'mentor_name' => 'นักวิจัย / Mentor',
            'industrial_name' => 'บริษัท/อุตสาหกรรม / Industrial',
            'project_name' => 'ชื่อโครงการหลัก / Project Name',
            'project_objective' => 'วัตถุประสงค์ของโครงการย่อย / Objective',
            'project_scope' => 'ขอบเขตแนวทางการศึกษาวิจัย / Scope',
            'project_student_name' => 'ชื่อโครงการย่อยสำหรับผู้ขอรับทุน / Project Name for Student',
            'project_student_begin' => 'วันเริ่มโครงการย่อย / Begin',
            'project_student_end' => 'วันสิ้นสุดโครงการย่อย / End',
            'project_student_func' => 'ระยะเวลาโครงการย่อย / Period',
            'project_student_objective'=>  'วัตถุประสงค์ของโครงการย่อย / Objective for this project.',
            'project_student_expect' => 'ผลที่คาดหวังในเชิงเศรษฐกิจ สังคม หรืออื่นๆ หลังจากการทำโครงการวิจัยนี้/The expected results in terms of economic, social or other after doing this project.',
            'industrial_support' => 'ค่าใช้จ่ายในการสนับสนุนโครงการวิจัยของนักเรียน/นักศึกษา (เช่น ค่าใช้จ่ายทั่วไป ค่าที่พัก ค่าเดินทาง ฯลฯ)',
            'industrial_support_desc' => 'โครงการวิจัยของนักเรียน/นักศึกษา ช่วยสนับสนุนบริษัท/ภาคอุตสาหกรรม และเกี่ยวข้องกับ 10 อุตสาหกรรมอย่างไร โปรดระบุ',
            'comment' => 'แสดงความคิดเห็นของท่านต่อผู้เสนอขอรับทุน/Recommendation',
            'industrial_incash_salary' => 'เงินเดือน / Salary',
            'industrial_incash_salary_cost' => 'เงินเดือน / Salary',
            'industrial_incash_rents' => 'ค่าที่พัก / Rents',
            'industrial_incash_rents_cost' => 'ค่าที่พัก / Rents',
            'industrial_incash_traveling' => 'ค่าเดินทาง / Traveling Expenses',
            'industrial_incash_traveling_cost' => 'ค่าเดินทาง / Traveling Expenses',
            'industrial_incash_other' => 'อื่นๆ / Other',
            'industrial_incash_other_cost' => 'อื่นๆ / Other',
            'industrial_incash_other_text' => 'อื่นๆ / Other',
            'industrial_incash_other2' => 'อื่นๆ / Other',
            'industrial_incash_other2_cost' => 'อื่นๆ / Other',
            'industrial_incash_other2_text' => 'อื่นๆ / Other',
            'industrial_inkind_equipment' => 'ค่าอุปกรณ์และสารเคมี / Equipment and Chemicals',
            'industrial_inkind_equipment_cost' => 'ค่าอุปกรณ์และสารเคมี / Equipment and Chemicals',
            'industrial_inkind_other' => 'อื่นๆ / Other',
            'industrial_inkind_other_cost' => 'อื่นๆ / Other',
            'industrial_inkind_other_text' => 'อื่นๆ/Other',
            'industrial_inkind_other2' => 'อื่นๆ / Other',
            'industrial_inkind_other2_cost' => 'อื่นๆ / Other',
            'industrial_inkind_other2_text' => 'อื่นๆ / Other',
            'sum' => 'สรุปประมาณการเงินสนับสนุนจนจบโครงการ',
        );
    }

}
