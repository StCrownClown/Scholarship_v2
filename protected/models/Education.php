<?php

/**
 * This is the model class for table "education".
 *
 * The followings are the available columns in table 'education':

 *
 * The followings are the available model relations:
 * @property Person $person
 * @property NstdamasEducationlevel $educationlevel
 * @property NstdamasMajor $major
 * @property NstdamasFaculty $faculty
 * @property NstdamasInstitute $institute
 * @property NstdamasCountry $country
 */
class Education extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Education the static model class
     */
    public $id;
    public $person_id;
    public $seq;
    public $educationlevel_id;
    public $country_id;
    public $institute_id;
    public $institute_other;
    public $faculty_id;
    public $faculty_other;
    public $major_id;
    public $major_other;
    public $month_enrolled;
    public $year_enrolled;
    public $month_graduated;
    public $year_graduated;
    public $gpa_year_1;
    public $gpa_year_1_first;
    public $gpa_year_1_second;
    public $gpa_year_2;
    public $gpa_year_2_first;
    public $gpa_year_2_second;
    public $gpa_year_3;
    public $gpa_year_3_first;
    public $gpa_year_3_second;
    public $gpa_year_4;
    public $gpa_year_4_first;
    public $gpa_year_4_second;
    public $gpa_year_5;
    public $gpa_year_5_first;
    public $gpa_year_5_second;
    public $gpa_year_6;
    public $gpa_year_6_first;
    public $gpa_year_6_second;
    public $avg_gpa;
    public $transcript_path;
    public $ck_institute_other;
    public $ck_faculty_other;
    public $ck_disciplines_other;
    public $is_highest;
    public $result_date;
    public $status;
    public $active;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'education';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('person_id, seq, educationlevel_id, country_id, institute_id, faculty_id, major_id, year_enrolled, year_graduated, gpa_year_1, gpa_year_2, gpa_year_3, gpa_year_4, gpa_year_5, gpa_year_6, is_highest, active', 'numerical', 'integerOnly' => true),
            array('gpa_year_1_first, gpa_year_1_second, gpa_year_2_first, gpa_year_2_second, gpa_year_3_first, gpa_year_3_second, gpa_year_4_first, gpa_year_4_second, gpa_year_5_first, gpa_year_5_second, gpa_year_6_first, gpa_year_6_second, avg_gpa', 'numerical'),
            array('institute_other, faculty_other, major_other, transcript_path, status', 'length', 'max' => 255),
            array('month_enrolled, month_graduated', 'length', 'max' => 20),
            array('ck_institute_other, ck_faculty_other, ck_disciplines_other', 'length', 'max' => 10),
            array('result_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, person_id, seq, educationlevel_id, country_id, institute_id, institute_other, faculty_id, faculty_other, major_id, major_other, month_enrolled, year_enrolled, month_graduated, year_graduated, gpa_year_1, gpa_year_1_first, gpa_year_1_second, gpa_year_2, gpa_year_2_first, gpa_year_2_second, gpa_year_3, gpa_year_3_first, gpa_year_3_second, gpa_year_4, gpa_year_4_first, gpa_year_4_second, gpa_year_5, gpa_year_5_first, gpa_year_5_second, gpa_year_6, gpa_year_6_first, gpa_year_6_second, avg_gpa, transcript_path, ck_institute_other, ck_faculty_other, ck_disciplines_other, is_highest, result_date, status, active', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
            'educationlevel' => array(self::BELONGS_TO, 'NstdamasEducationlevel', 'educationlevel_id'),
            'major' => array(self::BELONGS_TO, 'NstdamasMajor', 'major_id'),
            'faculty' => array(self::BELONGS_TO, 'NstdamasFaculty', 'faculty_id'),
            'institute' => array(self::BELONGS_TO, 'NstdamasInstitute', 'institute_id'),
            'country' => array(self::BELONGS_TO, 'NstdamasCountry', 'country_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'person_id' => 'Person',
            'seq' => 'Seq',
            'educationlevel_id' => 'Educationlevel',
            'country_id' => 'Country',
            'institute_id' => 'Institute',
            'institute_other' => 'Institute Other',
            'faculty_id' => 'Faculty',
            'faculty_other' => 'Faculty Other',
            'major_id' => 'Major',
            'major_other' => 'Major Other',
            'month_enrolled' => 'Month Enrolled',
            'year_enrolled' => 'Year Enrolled',
            'month_graduated' => 'Month Graduated',
            'year_graduated' => 'Year Graduated',
            'gpa_year_1' => 'Gpa Year 1',
            'gpa_year_1_first' => 'Gpa Year 1 First',
            'gpa_year_1_second' => 'Gpa Year 1 Second',
            'gpa_year_2' => 'Gpa Year 2',
            'gpa_year_2_first' => 'Gpa Year 2 First',
            'gpa_year_2_second' => 'Gpa Year 2 Second',
            'gpa_year_3' => 'Gpa Year 3',
            'gpa_year_3_first' => 'Gpa Year 3 First',
            'gpa_year_3_second' => 'Gpa Year 3 Second',
            'gpa_year_4' => 'Gpa Year 4',
            'gpa_year_4_first' => 'Gpa Year 4 First',
            'gpa_year_4_second' => 'Gpa Year 4 Second',
            'gpa_year_5' => 'Gpa Year 5',
            'gpa_year_5_first' => 'Gpa Year 5 First',
            'gpa_year_5_second' => 'Gpa Year 5 Second',
            'gpa_year_6' => 'Gpa Year 6',
            'gpa_year_6_first' => 'Gpa Year 6 First',
            'gpa_year_6_second' => 'Gpa Year 6 Second',
            'avg_gpa' => 'Avg Gpa',
            'transcript_path' => 'Transcript Path',
            'ck_institute_other' => 'Ck Institute Other',
            'ck_faculty_other' => 'Ck Faculty Other',
            'ck_disciplines_other' => 'Ck Disciplines Other',
            'is_highest' => 'Is Highest',
            'result_date' => 'Result Date',
            'status' => 'Status',
            'active' => 'Active',
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
        $criteria->compare('person_id', $this->person_id);
        $criteria->compare('seq', $this->seq);
        $criteria->compare('educationlevel_id', $this->educationlevel_id);
        $criteria->compare('country_id', $this->country_id);
        $criteria->compare('institute_id', $this->institute_id);
        $criteria->compare('institute_other', $this->institute_other, true);
        $criteria->compare('faculty_id', $this->faculty_id);
        $criteria->compare('faculty_other', $this->faculty_other, true);
        $criteria->compare('major_id', $this->major_id);
        $criteria->compare('major_other', $this->major_other, true);
        $criteria->compare('month_enrolled', $this->month_enrolled, true);
        $criteria->compare('year_enrolled', $this->year_enrolled);
        $criteria->compare('month_graduated', $this->month_graduated, true);
        $criteria->compare('year_graduated', $this->year_graduated);
        $criteria->compare('gpa_year_1', $this->gpa_year_1);
        $criteria->compare('gpa_year_1_first', $this->gpa_year_1_first);
        $criteria->compare('gpa_year_1_second', $this->gpa_year_1_second);
        $criteria->compare('gpa_year_2', $this->gpa_year_2);
        $criteria->compare('gpa_year_2_first', $this->gpa_year_2_first);
        $criteria->compare('gpa_year_2_second', $this->gpa_year_2_second);
        $criteria->compare('gpa_year_3', $this->gpa_year_3);
        $criteria->compare('gpa_year_3_first', $this->gpa_year_3_first);
        $criteria->compare('gpa_year_3_second', $this->gpa_year_3_second);
        $criteria->compare('gpa_year_4', $this->gpa_year_4);
        $criteria->compare('gpa_year_4_first', $this->gpa_year_4_first);
        $criteria->compare('gpa_year_4_second', $this->gpa_year_4_second);
        $criteria->compare('gpa_year_5', $this->gpa_year_5);
        $criteria->compare('gpa_year_5_first', $this->gpa_year_5_first);
        $criteria->compare('gpa_year_5_second', $this->gpa_year_5_second);
        $criteria->compare('gpa_year_6', $this->gpa_year_6);
        $criteria->compare('gpa_year_6_first', $this->gpa_year_6_first);
        $criteria->compare('gpa_year_6_second', $this->gpa_year_6_second);
        $criteria->compare('avg_gpa', $this->avg_gpa);
        $criteria->compare('transcript_path', $this->transcript_path, true);
        $criteria->compare('ck_institute_other', $this->ck_institute_other, true);
        $criteria->compare('ck_faculty_other', $this->ck_faculty_other, true);
        $criteria->compare('ck_disciplines_other', $this->ck_disciplines_other, true);
        $criteria->compare('is_highest', $this->is_highest);
        $criteria->compare('result_date', $this->result_date, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('active', $this->active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $education = education::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($education->id)) ? $education->id + 1 : 1;
    }

}
