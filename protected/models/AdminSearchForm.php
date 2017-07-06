<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class AdminSearchForm extends CFormModel {

    public $year;
    public $status;
    public $student;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('year', 'length', 'min' => 4, 'max' => 4),
            array('student', 'length', 'max' => 255),
            array('status, student', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'year' => 'ปีงบประมาณ',
            'status' => 'สถานะทุน',
            'student' => 'ชื่อนักเรียน/นักศึกษา',
        );
    }

}
