<?php

/**
 * This is the model class for table "scholar_history".
 *
 * The followings are the available columns in table 'scholar_history':

 *
 * The followings are the available model relations:
 * @property Person $person
 * @property NstdamasEducationlevel $educationlevel
 */
class ScholarHistoryForm extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ScholarHistory the static model class
     */
    public $id;
    public $person_id;
    public $educationlevel_id;
    public $name;
    public $source;
    public $description;
    public $begin;
    public $end;
    public $first_created;
    public $last_updated;
    public $func_period;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scholar_history';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('educationlevel_id, name, description, source, begin, end', 'required'),
            array('person_id, educationlevel_id', 'numerical', 'integerOnly' => true),
            array('name, source', 'length', 'max' => 100),
            array('begin, end, first_created, last_updated', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, person_id, educationlevel_id, name, source, begin, end, first_created, last_updated', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'person_id' => 'Person',
            'educationlevel_id' => 'ระดับการศึกษา / Degree',
            'name' => 'ชื่อทุนที่ได้รับ / Name',
            'description' => 'รายละเอียดทุนที่ได้รับ (เช่น ค่าเล่าเรียน,ค่าใช้จ่ายรายเดือน,ค่าทำวิจัย และระบุจำนวนเงินที่ได้รับ)',
            'source' => 'หน่วยงานที่ให้ทุน / Source',
            'begin' => 'วันที่เริ่มต้นรับทุน / Begin',
            'end' => 'วันที่สิ้นสุดรับทุน / End',
            'first_created' => 'First Created',
            'last_updated' => 'Last Updated',
            'func_period' => 'ระยะเวลา / Period',
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
        $criteria->compare('educationlevel_id', $this->educationlevel_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('source', $this->source, true);
        $criteria->compare('begin', $this->begin, true);
        $criteria->compare('end', $this->end, true);
        $criteria->compare('first_created', $this->first_created, true);
        $criteria->compare('last_updated', $this->last_updated, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $scholar_history = scholar_history::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($scholar_history->id)) ? $scholar_history->id + 1 : 1;
    }

}
