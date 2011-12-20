<?php

/**
 * This is the model class for table "tbl_groupbuy_authcode".
 *
 * The followings are the available columns in table 'tbl_groupbuy_authcode':
 * @property integer $id
 * @property string $authcode

 * @property string $advertiser

 * @property string $create_time
 * @property string $update_time
 */
class GroupbuyAuthcode extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return GroupbuyAuthcode the static model class
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
		return 'tbl_groupbuy_authcode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('authcode', 'required'),
			
			array('authcode, advertiser', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, authcode, advertiser, create_time, update_time', 'safe', 'on'=>'search'),
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
                'verified_pages'=>array(self::HAS_MANY,'VerifiedFbPages','advertiser'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'authcode' => 'Authcode',
			'advertiser' => 'Advertiser',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
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
		$criteria->compare('authcode',$this->authcode,true);
		$criteria->compare('advertiser',$this->advertiser,true);
		
		
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}


public function authenticate($authcode)
      {
         $criteria=new CDbCriteria;
         $criteria->select='advertiser';

         $criteria->condition='authcode=:authcode';
                      
         $criteria->params=array(':authcode'=>$authcode); 

   
         if(GroupbuyAuthcode::model()->count($criteria)==1)
           {
             return(GroupbuyAuthcode::model()->find($criteria)); 
           }
           else
            {
              return(false);
            }   

   

      }


public function isExist($advertiser)
    {
         $criteria=new CDbCriteria;
         $criteria->select='id';
         $criteria->condition='advertiser=:advertiser';                      
         $criteria->params=array(':advertiser'=>$advertiser); 

   
         if(GroupbuyAuthcode::model()->count($criteria)==1)
           {
             return(true); 
           }
           else
            {
              return(false);
            }   
      
    }
       
}
