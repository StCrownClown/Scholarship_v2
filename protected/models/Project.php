<?php

/**
 * This is the model class for table "project".
 *
 * The followings are the available columns in table 'project':

 */
class Project extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Project the static model class
	 */
			public $id;
			public $creater_id;
			public $name;
			public $objective;
			public $scope;
			public $begin;
			public $end;
			public $budget;
			public $funding;
			public $funding_name;
			public $funding_code;
                        public $funding_code_name;
			public $funding_etc;
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
		return 'project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('creater_id', 'numerical', 'integerOnly'=>true),
			array('name, funding_name, funding_etc', 'length', 'max'=>255),
			array('budget, funding', 'length', 'max'=>10),
			array('funding_code', 'length', 'max'=>100),
			array('objective, scope, begin, end, first_created, last_updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, creater_id, name, objective, scope, begin, end, budget, funding, funding_name, funding_code, funding_etc, first_created, last_updated', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'creater_id' => 'Creater',
			'name' => 'Name',
			'objective' => 'Objective',
			'scope' => 'Scope',
			'begin' => 'Begin',
			'end' => 'End',
			'budget' => 'Budget',
			'funding' => 'Funding',
			'funding_name' => 'Funding Name',
			'funding_code' => 'Funding Code',
			'funding_etc' => 'Funding Etc',
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
		$criteria->compare('creater_id',$this->creater_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('objective',$this->objective,true);
		$criteria->compare('scope',$this->scope,true);
		$criteria->compare('begin',$this->begin,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('budget',$this->budget,true);
		$criteria->compare('funding',$this->funding,true);
		$criteria->compare('funding_name',$this->funding_name,true);
		$criteria->compare('funding_code',$this->funding_code,true);
		$criteria->compare('funding_etc',$this->funding_etc,true);
		$criteria->compare('first_created',$this->first_created,true);
		$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getID()
	{
		$project=project::model()->find(array(
			'select'=>'max(id) as id',
		));
		return (isset($project->id))?$project->id+1:1;
	}
}