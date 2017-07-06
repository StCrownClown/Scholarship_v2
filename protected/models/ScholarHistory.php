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
class ScholarHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScholarHistory the static model class
	 */
			public $id;
			public $person_id;
			public $educationlevel_id;
			public $z_student_education;
			public $z_student_education_year;
			public $name;
			public $source;
                        public $description;
			public $begin;
			public $end;
			public $z_study_year;
			public $z_fees_amount;
			public $z_personal_amount;
			public $z_personal_dev_amount;
			public $z_researcher_amount;
			public $z_other_amount_name;
			public $z_other_amount;
			public $z_seq;
			public $z_active;
			public $first_created;
			public $last_updated;
		
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'scholar_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('person_id, educationlevel_id, z_student_education_year, z_study_year, z_seq, z_active', 'numerical', 'integerOnly'=>true),
			array('z_fees_amount, z_personal_amount, z_personal_dev_amount, z_researcher_amount, z_other_amount', 'numerical'),
			array('z_student_education, name, source, z_other_amount_name', 'length', 'max'=>255),
			array('begin, end, first_created, last_updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, person_id, educationlevel_id, z_student_education, z_student_education_year, name, source, begin, end, z_study_year, z_fees_amount, z_personal_amount, z_personal_dev_amount, z_researcher_amount, z_other_amount_name, z_other_amount, z_seq, z_active, first_created, last_updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'person_id' => 'Person',
			'educationlevel_id' => 'Educationlevel',
			'z_student_education' => 'Z Student Education',
			'z_student_education_year' => 'Z Student Education Year',
			'name' => 'Name',
			'source' => 'Source',
			'begin' => 'Begin',
			'end' => 'End',
			'z_study_year' => 'Z Study Year',
			'z_fees_amount' => 'Z Fees Amount',
			'z_personal_amount' => 'Z Personal Amount',
			'z_personal_dev_amount' => 'Z Personal Dev Amount',
			'z_researcher_amount' => 'Z Researcher Amount',
			'z_other_amount_name' => 'Z Other Amount Name',
			'z_other_amount' => 'Z Other Amount',
			'z_seq' => 'Z Seq',
			'z_active' => 'Z Active',
			'first_created' => 'First Created',
			'last_updated' => 'Last Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('person_id',$this->person_id);
		$criteria->compare('educationlevel_id',$this->educationlevel_id);
		$criteria->compare('z_student_education',$this->z_student_education,true);
		$criteria->compare('z_student_education_year',$this->z_student_education_year);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('source',$this->source,true);
		$criteria->compare('begin',$this->begin,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('z_study_year',$this->z_study_year);
		$criteria->compare('z_fees_amount',$this->z_fees_amount);
		$criteria->compare('z_personal_amount',$this->z_personal_amount);
		$criteria->compare('z_personal_dev_amount',$this->z_personal_dev_amount);
		$criteria->compare('z_researcher_amount',$this->z_researcher_amount);
		$criteria->compare('z_other_amount_name',$this->z_other_amount_name,true);
		$criteria->compare('z_other_amount',$this->z_other_amount);
		$criteria->compare('z_seq',$this->z_seq);
		$criteria->compare('z_active',$this->z_active);
		$criteria->compare('first_created',$this->first_created,true);
		$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getID()
	{
		$scholar_history=scholar_history::model()->find(array(
			'select'=>'max(id) as id',
		));
		return (isset($scholar_history->id))?$scholar_history->id+1:1;
	}
}