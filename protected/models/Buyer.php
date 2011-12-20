<?php

/**
 * This is the model class for table "tbl_buyer".
 *
 * The followings are the available columns in table 'tbl_buyer':
 * @property string $fb_id
 * @property string $first_name
 * @property string $last_name
 * @property string $location
 * @property string $email
 * @property string $gender
 * @property double $timezone
 * @property string $locale
 * @property string $create_time
 * @property string $updated_time
 */
class Buyer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Buyer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_buyer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fb_id, first_name, last_name,email,create_time, updated_time', 'required'),
			array('timezone', 'numerical'),
			array('fb_id', 'length', 'max'=>100),
			array('first_name, last_name, locale', 'length', 'max'=>30),
			array('location', 'length', 'max'=>200),
			array('email', 'length', 'max'=>50),
			array('gender', 'length', 'max'=>10),
                        array('location,locale,timezone,country', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('fb_id, first_name, last_name, location, email, gender, timezone, locale, create_time, updated_time', 'safe', 'on'=>'search'),
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
			'fb_id' => 'Fb',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'location' => 'Location',
			'email' => 'Email',
			'gender' => 'Gender',
			'timezone' => 'Timezone',
			'locale' => 'Locale',
			'create_time' => 'Create Time',
			'updated_time' => 'Updated Time',
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

		$criteria->compare('fb_id',$this->fb_id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('timezone',$this->timezone);
		$criteria->compare('locale',$this->locale,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('updated_time',$this->updated_time,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
     
} 
