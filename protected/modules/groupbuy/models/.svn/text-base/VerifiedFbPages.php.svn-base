<?php

/**
 * This is the model class for table "tbl_verified_fb_pages".
 *
 * The followings are the available columns in table 'tbl_verified_fb_pages':
 * @property integer $id
 * @property string $page_id
 * @property string $advertiser
 * @property string $verified_time
 */
class VerifiedFbPages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return VerifiedFbPages the static model class
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
		return 'tbl_verified_fb_pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page_id, advertiser, verified_time', 'required'),
			array('page_id, advertiser', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('page_id, advertiser, verified_time', 'safe', 'on'=>'search'),
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
			'page_id' => 'Page',
			'advertiser' => 'Advertiser',
			'verified_time' => 'Verified Time',
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

		
		$criteria->compare('page_id',$this->page_id,true);
		$criteria->compare('advertiser',$this->advertiser,true);
		$criteria->compare('verified_time',$this->verified_time,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
public function isVerified($page_id)
      {
         $criteria=new CDbCriteria;
         $criteria->condition='page_id=:page_id';
         $criteria->params=array(':page_id'=>$page_id); 

         if(VerifiedFbPages::model()->exists($criteria))
           {
             return(true); 
           }
           else
            {
              return(false);
            }   

      }
public function removePage($page_id)
{

  $criteria=new CDbCriteria;
  $criteria->condition="page_id='".$page_id."'";
 
  $page=VerifiedFbPages::model()->findbyPk($page_id); 
 
  $page->delete();
 

}

public function getAdvertiser($page_id)
      {
         $criteria=new CDbCriteria;
         $criteria->select='advertiser';

         $criteria->condition='page_id=:page_id';
         $criteria->params=array(':page_id'=>$page_id); 

   
         if(VerifiedFbPages::model()->count($criteria)>0)
           {
             return(VerifiedFbPages::model()->find($criteria)->advertiser); 
           }
           else
            {
              return(false);
            }   

      }
}
