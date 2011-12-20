<?php

/**
 * This is the model class for table "tbl_buyer_confirmation_code".
 *
 * The followings are the available columns in table 'tbl_buyer_confirmation_code':
 * @property string $code
 * @property integer $deal_id
 * @property string $buyer_fb_id
 * @property integer $is_used
 * @property integer $should_deal_on
 */
class BuyerConfirmationCode extends CActiveRecord
{
 public $excel1;
 public $excel2;

 public $is_full_upload;

public function getUploadOptions()
{
  return array('1'=>'Full upload',
               '0'=>'add to existing data',
              ); 
}
    public function deleteExistingCodes($deal_id,$is_on)
      {
        if($is_on!=null)
         {  
         $sql="DELETE FROM tbl_buyer_confirmation_code where deal_id='".$deal_id."' and should_deal_on='".$is_on."' ";
         }
        else
         {
          $sql="DELETE FROM tbl_buyer_confirmation_code where deal_id='".$deal_id."' and should_deal_on!=1 and should_deal_on!=0 ";
         }
         $command=Yii::app()->db->createCommand($sql); 
        return $command->execute();  
      }
      public function isCodeExist($code,$advertiser)
      {
        $criteria=new CDbCriteria;
        $criteria->condition="code='".$code."' and advertiser='".$advertiser."' "; 
         $n=BuyerConfirmationCode::model()->count($criteria);
         if($n==1)
           return(true);
         else
           return(false);
      }
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return BuyerConfirmationCode the static model class
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
		return 'tbl_buyer_confirmation_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('code, deal_id', 'required'),
                      //  array('excel1','required','on'=>'upload1'),
                     //   array('excel2','required','on'=>'upload2'),
                       // array('excel1','file'),
                       // array('excel2','file'),
			array('deal_id, is_used, should_deal_on', 'numerical', 'integerOnly'=>true),
			array('code, buyer_fb_id', 'length', 'max'=>100),
                       // array('excel1,excel2,is_full_upload', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('code, deal_id, buyer_fb_id, is_used, should_deal_on', 'safe', 'on'=>'search'),
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
			'code' => 'Code',
			'deal_id' => 'Deal',
			'buyer_fb_id' => 'Buyer Fb',
			'is_used' => 'Is Used',
			'should_deal_on' => 'Should Deal On',
                        'excel1'=>'Upload Confirmation Codes DEAL ON ',
                        'excel2'=>'Upload Confirmation Codes DEAL OFF',
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

		$criteria->compare('code',$this->code,true);
		$criteria->compare('deal_id',$this->deal_id);
		$criteria->compare('buyer_fb_id',$this->buyer_fb_id,true);
		$criteria->compare('is_used',$this->is_used);
		$criteria->compare('should_deal_on',$this->should_deal_on);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}
