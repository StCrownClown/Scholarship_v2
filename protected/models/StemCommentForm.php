<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':

 *
 * The followings are the available model relations:
 * @property Scholar $scholar
 * @property Person $person
 */
class StemCommentForm extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return StemCommentForm the static model class
     */
    public $id;
    public $scholar_id;
    public $person_id;
    public $status;
    public $scholar_status;
    public $comment;
    public $first_created;
    public $last_updated;
    public $steppage;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('comment', 'required'),
            array('scholar_id, person_id', 'numerical', 'integerOnly' => true),
            array('status', 'length', 'max' => 50),
            array('comment, first_created, last_updated, scholar_status', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, scholar_id, person_id, status, comment, first_created, last_updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'scholar' => array(self::BELONGS_TO, 'Scholar', 'scholar_id'),
            'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'scholar_id' => 'Scholar',
            'person_id' => 'Person',
            'status' => 'Status',
            'comment' => 'แสดงความคิดเห็นของท่านต่อผู้เสนอขอรับทุน / Recommendation',
            'first_created' => 'First Created',
            'last_updated' => 'Last Updated',
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
        $criteria->compare('scholar_id', $this->scholar_id);
        $criteria->compare('person_id', $this->person_id);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('first_created', $this->first_created, true);
        $criteria->compare('last_updated', $this->last_updated, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $comment = comment::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($comment->id)) ? $comment->id + 1 : 1;
    }

}
