<?php

/**
 * This is the model class for table "tbl_coupon".
 *
 * The followings are the available columns in table 'tbl_coupon':
 * @property integer $id
 * @property string $user
 * @property integer $deal
 * @property integer $status
 * @property string $entry_date
 * @property string $deleted_date
 */
class Coupon extends CActiveRecord
{
  const STATUS_ONHOLD=1;    //PURCHASED ON HOLD
  const STATUS_ACTIVE=2;    //PURCHASED MONEY COLLECTED.
  const STATUS_REDEEMED=3;  //REDEEMED
  const STATUS_DELETED=4;
  const STATUS_EXPIRED=5;
  public function getStatusOptions()
  {
    return array(
     self::STATUS_ONHOLD=>'Purchased on hold',
     self::STATUS_ACTIVE=>'Purchased money collected',
     self::STATUS_REDEEMED=>'Redeemed',   
     self::STATUS_DELETED=>'Deleted',   
     self::STATUS_EXPIRED=>'Expired',     
    
    );  
   
  } 
  
 public function getCouponStatus($status_code)
 {
  if($status_code==1)
   {
    return("Pending");
   }
  else if($status_code==2)
  {
    return("Active");
  }
  else if($status_code==3)
  {
   return("Redeemed");
  }
  else if($status_code==4)
  {
   return("Deleted");
  }
  else if($status_code==5)
  {
   return("Expired");
  }
 }
public function getBuyerConfirmationCode()
{
   $criteria=new CDbCriteria;
            

           $criteria->condition="deal_id='".$this->coupon_deal->id."' and buyer_fb_id='".$this->buyer->fb_id."' ";	  
           if(BuyerConfirmationCode::model()->count($criteria)==0)
             {
               return("NO");
             }
           $c=BuyerConfirmationCode::model()->find($criteria);

  return($c->code);
}
	/**
	 * Returns the static model of the specified AR class.
	 * @return Coupon the static model class
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
		return 'tbl_coupon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, user, deal, status, entry_date', 'required'),
			array('id, deal, status', 'numerical', 'integerOnly'=>true),
			array('user', 'length', 'max'=>50),
                        array('transaction_id', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user, deal, status, entry_date, deleted_date', 'safe', 'on'=>'search'),
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
                 'coupon_deal'=>array(self::BELONGS_TO,'Deal','deal'),
                 'buyer'=>array(self::BELONGS_TO,'Buyer','user'), 
   
                 'transaction'=>array(self::BELONGS_TO,'Transaction','transaction_id'),   
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user' => 'User',
			'deal' => 'Deal',
			'status' => 'Status',
			'entry_date' => 'Entry Date',
			'deleted_date' => 'Deleted Date',
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
		$criteria->compare('user',$this->user,true);
		$criteria->compare('deal',$this->deal);
		$criteria->compare('status',$this->status);
		$criteria->compare('entry_date',$this->entry_date,true);
		$criteria->compare('deleted_date',$this->deleted_date,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

 public function getMail($user,$subject)
 {
 
  $tipped="";
  $totip="";

  $t="";
 
  if($this->coupon_deal->isTipped())
    {
       $t=" Congrats,The deal is yours now.";  
       $tipped="Congrats,The deal is yours now.which is Tipped at ".$this->coupon_deal->tipped_at." with ".$this->coupon_deal->getBoughtCount()." bought." ;
    } 
  else
    {
       $totip="Now The deal got ".$this->coupon_deal->getBoughtCount()." buyer.".($this->coupon_deal->tipping_point-$this->coupon_deal->getBoughtCount())." more need to get this offer.";

    }

   $mails=array(
              
              'buyer'=>array(
                    'bought'=>array('subject'=>'Thanks for buying Deal:'.$this->coupon_deal->title.$t,
                                    'body'=>'Dear  '.$this->buyer->first_name.','.$tipped.'.Thanks for buying the deal '.$this->coupon_deal->title.'.'.$totip.'The deal will end at '.$this->coupon_deal->end_date.'.Thank you.',

                                    ),     
     
                    'tipped'=>array('subject'=>'Congrats, Deal:'.$this->coupon_deal->title.' is Tipped',
                                    'body'=>'Dear  '.$this->buyer->first_name.',The deal '.$this->coupon_deal->title.' is ON now.It is tipped at '.$this->coupon_deal->tipped_at.' with '.$this->coupon_deal->tipping_point.' bought.The deal will end at '.$this->coupon_deal->end_date.'.Your coupons are generated.please look at the my coupons section.Thank you.',

                                    ),
                    'ended_with_tipped'=>array('subject'=>'Congrats,The Deal:'.$this->coupon_deal->title.' is yours now.',
                                    'body'=>'Dear '.$this->buyer->first_name.',Congrats, The deal '.$this->coupon_deal->title.'is yours now.The deal is ended by reaching tipping point '.$this->coupon_deal->tipping_point.' with '.$this->coupon_deal->getBoughtCount().' bought.You can come and collect your offer before your coupon expiry date:'.$this->coupon_deal->coupon_expiry.'.Thank you.',
                                    ),
                    'expired_without_tipped'=>array('subject'=>'Deal:'.$this->coupon_deal->title.' is Expired.',
                                    'body'=>'Dear '.$this->buyer->first_name.',The deal "'.$this->coupon_deal->title.'" you bought is expired  at '.$this->coupon_deal->end_date.' without reaching tipping point.So the deal is canceld now and  your amount is refunded in  your paypal account.Thank you.',       

                                    ),
                    'sold_out_before_exp'=>array('subject'=>'Congrats,The Deal:'.$this->coupon_deal->title.' is yours now',
                                    'body'=>'Dear  '.$this->buyer->first_name.',Congrats,The deal you bought '.$this->coupon_deal->title.'.is yours now which is sold out before expiry date:'.$this->coupon_deal->end_date.'.Now you can come with your coupon and collect your offer before coupon expiry date:'.$this->coupon_deal->coupon_expiry.'.Thank you.',

                                    ),  

                    'payment'=>array( 
                                    'subject'=>'Deal:'.$this->coupon_deal->title.' Payment notification',
                                    'body'=>'Dear '.$this->buyer->first_name.',Your payment for the deal '.$this->coupon_deal->title.' is successfull.',  

                                    ),  
                           ),  
           
    
  
             );
   return($mails[$user][$subject]);

 }



}
