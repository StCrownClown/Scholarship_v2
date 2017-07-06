<?php

/**
 * This is the model class for table "scholar_stem".
 *
 * The followings are the available columns in table 'scholar_stem':

 *
 * The followings are the available model relations:
 * @property Scholar[] $scholars
 * @property Project $project
 */
class StemHistoryForm extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ScholarStem the static model class
     */
    public $id;
    public $is_history;
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scholar_stem';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('is_history', 'required'),
            array('is_history', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scholars' => array(self::HAS_MANY, 'Scholar', 'scholar_stem_id'),
            'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'is_history' => 'ประวัติการรับทุนอื่น',
            
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
        $criteria->compare('is_history', $this->is_history);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $scholar_stem = scholar_stem::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($scholar_stem->id)) ? $scholar_stem->id + 1 : 1;
    }

}
