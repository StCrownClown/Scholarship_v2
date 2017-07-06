<?php

/**
 * This is the model class for table "nstdamas_org".
 *
 * The followings are the available columns in table 'nstdamas_org':

 *
 * The followings are the available model relations:
 * @property NstdamasDepartment[] $nstdamasDepartments
 * @property Person[] $people
 */
class NstdamasOrg extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return NstdamasOrg the static model class
     */
    public $id;
    public $org_name;
    public $org_name_en;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'nstdamas_org';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'required'),
            array('id', 'numerical', 'integerOnly' => true),
            array('org_name, org_name_en', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, org_name, org_name_en', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'nstdamasDepartments' => array(self::HAS_MANY, 'NstdamasDepartment', 'org_id'),
            'people' => array(self::HAS_MANY, 'Person', 'org_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'org_name' => 'Org Name',
            'org_name_en' => 'Org Name En',
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
        $criteria->compare('org_name', $this->org_name, true);
        $criteria->compare('org_name_en', $this->org_name_en, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $nstdamas_org = nstdamas_org::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($nstdamas_org->id)) ? $nstdamas_org->id + 1 : 1;
    }

}
