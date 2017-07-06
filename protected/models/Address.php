<?php

/**
 * This is the model class for table "address".
 *
 * The followings are the available columns in table 'address':

 *
 * The followings are the available model relations:
 * @property Person $person
 * @property NstdamasProvince $province
 * @property NstdamasCountry $country
 */
class Address extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Address the static model class
	 */
			public $id;
			public $person_id;
			public $type;
			public $address;
			public $number;
			public $village;
			public $building;
			public $floor;
			public $room;
			public $moo;
			public $alley;
			public $road;
			public $subdistrict;
			public $district;
			public $province_id;
			public $country_id;
			public $zipcode;
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
		return 'address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('person_id, province_id, country_id', 'numerical', 'integerOnly'=>true),
			array('type, zipcode', 'length', 'max'=>10),
			array('number, floor, room, moo', 'length', 'max'=>50),
			array('village, building, alley, road, subdistrict, district', 'length', 'max'=>255),
			array('address, first_created, last_updated', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, person_id, type, address, number, village, building, floor, room, moo, alley, road, subdistrict, district, province_id, country_id, zipcode, first_created, last_updated', 'safe', 'on'=>'search'),
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
			'province' => array(self::BELONGS_TO, 'NstdamasProvince', 'province_id'),
			'country' => array(self::BELONGS_TO, 'NstdamasCountry', 'country_id'),
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
			'type' => 'Type',
			'address' => 'Address',
			'number' => 'Number',
			'village' => 'Village',
			'building' => 'Building',
			'floor' => 'Floor',
			'room' => 'Room',
			'moo' => 'Moo',
			'alley' => 'Alley',
			'road' => 'Road',
			'subdistrict' => 'Subdistrict',
			'district' => 'District',
			'province_id' => 'Province',
			'country_id' => 'Country',
			'zipcode' => 'Zipcode',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('village',$this->village,true);
		$criteria->compare('building',$this->building,true);
		$criteria->compare('floor',$this->floor,true);
		$criteria->compare('room',$this->room,true);
		$criteria->compare('moo',$this->moo,true);
		$criteria->compare('alley',$this->alley,true);
		$criteria->compare('road',$this->road,true);
		$criteria->compare('subdistrict',$this->subdistrict,true);
		$criteria->compare('district',$this->district,true);
		$criteria->compare('province_id',$this->province_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('first_created',$this->first_created,true);
		$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getID()
	{
		$address=address::model()->find(array(
			'select'=>'max(id) as id',
		));
		return (isset($address->id))?$address->id+1:1;
	}
}