<?php

/**
 * This is the model class for table "scholar".
 *
 * The followings are the available columns in table 'scholar':

 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Person $student
 * @property Person $professor
 * @property Person $mentor
 * @property Person $industrial
 * @property Education $education
 * @property ScholarStem $scholarStem

 * @property ScholarNuirc $scholarNuirc
 * @property ScholarTgist $scholarTgist
 */
class Scholar extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Scholar the static model class
	 */
			public $id;
			public $student_id;
			public $education_id;
			public $type;
			public $status;
			public $professor_id;
			public $mentor_id;
			public $industrial_id;
			public $scholar_stem_id;

			public $scholar_nuirc_id;
            public $scholar_tgist_id;
                        public $steppage;
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
		return 'scholar';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('student_id, education_id, professor_id, mentor_id, industrial_id, scholar_stem_id, scholar_nuirc_id, scholar_tgist_id, steppage', 'numerical', 'integerOnly'=>true),
			array('type, status', 'length', 'max'=>255),
			array('first_created, last_updated, steppage', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.

			array('id, student_id, steppage, education_id, type, status, professor_id, mentor_id, industrial_id, scholar_stem_id, scholar_nuirc_id, scholar_tgist_id, first_created, last_updated', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'scholar_id'),
			'student' => array(self::BELONGS_TO, 'Person', 'student_id'),
			'professor' => array(self::BELONGS_TO, 'Person', 'professor_id'),
			'mentor' => array(self::BELONGS_TO, 'Person', 'mentor_id'),
			'industrial' => array(self::BELONGS_TO, 'Person', 'industrial_id'),
			'education' => array(self::BELONGS_TO, 'Education', 'education_id'),
			'scholarStem' => array(self::BELONGS_TO, 'ScholarStem', 'scholar_stem_id'),

			'scholarNuirc' => array(self::BELONGS_TO, 'ScholarNuirc', 'scholar_nuirc_id'),
			'scholarTgist' => array(self::BELONGS_TO, 'ScholarTgist', 'scholar_tgist_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'student_id' => 'Student',
			'education_id' => 'Education',
			'type' => 'Type',
			'status' => 'Status',
			'professor_id' => 'Professor',
			'mentor_id' => 'Mentor',
			'industrial_id' => 'Industrial',
			'scholar_stem_id' => 'Scholar Stem',

			'scholar_nuirc_id' => 'Scholar Nuirc',
			'scholar_tgist_id' => 'Scholar Tgist',
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
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('education_id',$this->education_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('professor_id',$this->professor_id);
		$criteria->compare('mentor_id',$this->mentor_id);
		$criteria->compare('industrial_id',$this->industrial_id);
		$criteria->compare('scholar_stem_id',$this->scholar_stem_id);

		$criteria->compare('scholar_nuirc_id',$this->scholar_nuirc_id);
		$criteria->compare('scholar_tgist_id',$this->scholar_tgist_id);
		$criteria->compare('first_created',$this->first_created,true);
		$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getID()
	{
		$scholar=scholar::model()->find(array(
			'select'=>'max(id) as id',
		));
		return (isset($scholar->id))?$scholar->id+1:1;
	}
        
        public function beforeSave() {
            unset($this->timestamp_column);
            return parent::beforeSave();
        }
}