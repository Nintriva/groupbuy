<?php

/**
 * This is the model class for table "tbl_transaction".
 *
 * The followings are the available columns in table 'tbl_transaction':
 * @property string $transaction_id
 * @property double $amount
 * @property string $currency_code
 * @property string $ordertime
 * @property string $timestamp
 * @property string $buyer_paypal_id
 * @property string $buyer_fb_id
 * @property string $coupon_id
 */
class Transaction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Transaction the static model class
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
		return 'tbl_transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transaction_id, amount,quantity,deal_id, ordertime, buyer_fb_id', 'required'),
			array('amount', 'numerical'),
			array('transaction_id, buyer_paypal_id, buyer_fb_id', 'length', 'max'=>100),
			array('currency_code', 'length', 'max'=>10),
			
			array('timestamp', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('transaction_id, amount, quantity,deal_id,currency_code, ordertime, timestamp, buyer_paypal_id, buyer_fb_id,', 'safe', 'on'=>'search'),
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
                 'deal'=>array(self::BELONGS_TO,'Deal','deal_id'),
                 'buyer'=>array(self::BELONGS_TO,'Buyer','buyer_fb_id'),
                 
                 'coupons'=>array(self::HAS_MANY,'Coupon','transaction_id'), //Here A transaction may contain more than one quantity of same coupon  

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'transaction_id' => 'Transaction',
			'amount' => 'Amount',
			'currency_code' => 'Currency Code',
			'ordertime' => 'Ordertime',
			'timestamp' => 'Timestamp',
			'buyer_paypal_id' => 'Buyer Paypal',
			'buyer_fb_id' => 'Buyer Fb',
			'coupon_id' => 'Coupon',
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

		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('currency_code',$this->currency_code,true);
		$criteria->compare('ordertime',$this->ordertime,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('buyer_paypal_id',$this->buyer_paypal_id,true);
		$criteria->compare('buyer_fb_id',$this->buyer_fb_id,true);
		$criteria->compare('coupon_id',$this->coupon_id,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
 public function getMail($user,$subject)
     {

          $tipped="";
      $totip="";

      $t="";
 
  if($this->deal->isTipped())
    {
       $t=" Congrats,The deal is yours now.";  
       $tipped="Congrats,The deal is yours now.which is Tipped at ".$this->deal->tipped_at." with ".$this->deal->getBoughtCount()." bought." ;
    } 
  else
    {
       $totip="Now The deal got ".$this->deal->getBoughtCount()." buyer.".($this->deal->tipping_point-$this->deal->getBoughtCount())." more need to get this offer.";

    }

 

          $mails=array(
              
              'buyer'=>array(
  
                          'expired_without_tipped'=>array('subject'=>'Deal:'.$this->deal->title.' is Expired.',
                                                           'body'=>'Dear '.$this->buyer->first_name.',The deal "'.$this->deal->title.'" you bought is expired  at '.$this->deal->end_date.' without reaching tipping point.So the deal is canceld now and  your amount is refunded in  your paypal account.Thank you.',),
                          

                           //used for to mail to free coupon buyers
                           'expired_without_tipped1'=>array('subject'=>'Deal:'.$this->deal->title.' is Expired.',
                                                           'body'=>'Dear '.$this->buyer->first_name.',The deal "'.$this->deal->title.'" you bought is expired  at '.$this->deal->end_date.' without reaching tipping point.So the deal is canceld now.Thank you.',),
                          
                          'bought'=>array('subject'=>'Thanks for buying Deal:'.$this->deal->title.$t,
                                    'body'=>'Dear  '.$this->buyer->first_name.','.$tipped.'.Thanks for buying the deal '.$this->deal->title.'.'.$totip.'The deal will end at '.$this->deal->end_date.'.Thank you.',

                                    ),
                          'unpublished'=>array('subject'=>'Deal:'.$this->deal->title.' is Unpublished.',
                                                           'body'=>'Dear '.$this->buyer->first_name.',The deal "'.$this->deal->title.'" you bought is Unpublished  by the deal owner.So the deal is cancelled now and  your amount is refunded in  your paypal account.Thank you.',

                          ),
                           //for free coupon holders
                          'unpublished1'=>array('subject'=>'Deal:'.$this->deal->title.' is Unpublished.',
                                                           'body'=>'Dear '.$this->buyer->first_name.',The deal "'.$this->deal->title.'" you bought is Unpublished  by the deal owner.So the deal is cancelled now.Thank you.',

                          ),
                           'coupon_generated'=>array('subject'=>'Coupon generated  for the Deal:'.$this->deal->title.'  you bought.',
                                                           'body'=>'Dear '.$this->buyer->first_name.',Coupon(s) generated for the deal "'.$this->deal->title.'" you bought.please find your coupons at "My Coupons" section  in our facebook groupbuy app and Redeem the coupon before '.$this->deal->coupon_expiry.' .Thank you.',

                          ),                      
                       ),        
 
                      );                         


 
         return($mails[$user][$subject]);
        }

}
