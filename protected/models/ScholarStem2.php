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
class ScholarStem2 extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ScholarStem the static model class
     */
    public $id;
    public $industrial_support_desc;
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scholar_stem2';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(// The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, industrial_support_desc', 'safe', 'on' => 'search'),
        );
    }
    
    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'industrial_support_desc' => 'โครงการวิจัยของนักเรียน/นักศึกษา ช่วยสนับสนุนบริษัท/ภาคอุตสาหกรรม และเกี่ยวข้องกับ 10 อุตสาหกรรมอย่างไร โปรดระบุ',
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
        $criteria->compare('industrial_support_desc', $this->industrial_support_desc);
        
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
