<?php

/**
 * This is the model class for table "nstdamas_prefix".
 *
 * The followings are the available columns in table 'nstdamas_prefix':

 *
 * The followings are the available model relations:
 * @property Person[] $people
 * @property Person[] $people1
 * @property Person[] $people2
 * @property Person[] $people3
 */
class NstdamasPrefix extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NstdamasPrefix the static model class
	 */
	    		public $id;
			public $prf_name;
			public $prf_name_en;
		    	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nstdamas_prefix';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'numerical', 'integerOnly'=>true),
			array('prf_name, prf_name_en', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, prf_name, prf_name_en', 'safe', 'on'=>'search'),
		);
	}

	public function checkFormatDate($attribute,$params)
    {
        if(!empty($this->$attribute)){
            // Years from 2500 to 2999 are valid. dd/MM/yyyy or dd/MM/yyyy hh:mm:ss
            // http://www.regular-expressions.info/javascriptexample.html
            if (preg_match('/(0[1-9]|[12][0-9]|3[01])[\/](0[1-9]|1[012])[\/](25|26|27|28|29)\d\d(\s(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9])?/',$this->$attribute) !== 1) {
                $this->addError($attribute,Yii::t('web','Date format is invalid.'));
            }
        }
    }

    protected function beforeSave()
    {
        // Format dates based on Oracle thai > dd/mm/yyyy
        foreach($this->metadata->tableSchema->columns as $columnName => $column)
        {
            if ($column->dbType == 'DATE')
            {
                if(!empty($this->$columnName)){
                    $pos = strpos($this->$columnName, " ");
                    if ($pos !== false){
                        list($date,$time) = explode(" ",$this->$columnName);
                        $pos = strpos($date, "-");
                        if ($pos !== false){
                            $date = strtotime($date);
                            $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy HH:mm:ss',$date);
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY HH24:MI:SS')");
                        }else{
                            list($day,$month,$year) = explode("/",$date);
                            $this->$columnName = $day.'/'.$month.'/'.($year-543).' '.$time;
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY HH24:MI:SS')");
                        }
                    }else{
                        $pos = strpos($this->$columnName, "-");
                        if ($pos !== false){
                            $date = strtotime($this->$columnName);
                            $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy',$date);
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY')");
                        }else{
                            list($day,$month,$year) = explode("/",$this->$columnName);
                            $this->$columnName = $day.'/'.$month.'/'.($year-543);
                            $this->$columnName = new CDbExpression("to_date('{$this->$columnName}','DD/MM/YYYY')");
                        }
                    }
                }
            }
        }
        return parent::beforeSave();
    }

    protected function afterFind()
    {
        // Format dates based on Oracle thai > dd/mm/yyyy
        foreach($this->metadata->tableSchema->columns as $columnName => $column)
        {
            if (!strlen($this->$columnName)) continue;

            if ($column->dbType == 'DATE')
            {
                $pos = strpos($this->$columnName, " ");
                if ($pos !== false){
                    list($date,$time) = explode(" ",$this->$columnName);
                    $pos = strpos($date, "-");
                    if ($pos !== false){
                        $date = strtotime($date);
                        $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy HH:mm:ss',$date);
                    }
                }else{
                    $pos = strpos($this->$columnName, "-");
                    if ($pos !== false){
                        $date = strtotime($this->$columnName);
                        $this->$columnName = Yii::app()->dateFormatter->format('dd/MM/yyyy',
                            $date
                        );
                    }
                }

                list($day,$month,$year) = explode("/",$this->$columnName);
                $this->$columnName = $day.'/'.$month.'/'.($year+543);
                $this->$columnName .= (isset($time))?' '.$time:'';
            }

        }
        return parent::afterFind();
    }
    
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'people' => array(self::HAS_MANY, 'Person', 'prefix_id'),
			'people1' => array(self::HAS_MANY, 'Person', 'father_prefix_id'),
			'people2' => array(self::HAS_MANY, 'Person', 'mother_prefix_id'),
			'people3' => array(self::HAS_MANY, 'Person', 'parent_prefix_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'prf_name' => 'Prf Name',
			'prf_name_en' => 'Prf Name En',
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
		$criteria->compare('prf_name',$this->prf_name,true);
		$criteria->compare('prf_name_en',$this->prf_name_en,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getID()
	{
		$nstdamas_prefix=nstdamas_prefix::model()->find(array(
			'select'=>'max(id) as id',
		));
		return (isset($nstdamas_prefix->id))?$nstdamas_prefix->id+1:1;
	}

    public function saveAs()
    {
        $doc = nstdamas_prefix::model()->find(array(
            'select' => 'id',
            'condition' => 'id=:id',
            'params' => array(':id' => $this->id),
        ));
        if(isset($doc->id)){
            $this->setIsNewRecord(false); // update
        }
        return $this->save();
    }
    
    public function loadData()
    {
        $doc = nstdamas_prefix::model()->find(array(
            'condition' => 'id=:id',
            'params' => array(':id' => $this->id),
        ));
        if(isset($doc->id)){
            $array = get_object_vars($doc);
            foreach($array as $name => $value){
                $this->setAttribute($name,$value);
            }
        }
    }
    
}