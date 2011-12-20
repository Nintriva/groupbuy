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
			array('fb_id, first_name, last_name,create_time, updated_time', 'required'),
			array('timezone', 'numerical'),
			array('fb_id', 'length', 'max'=>100),
			array('first_name, last_name, locale', 'length', 'max'=>30),
			array('location', 'length', 'max'=>200),
			array('email', 'length', 'max'=>50),
			array('gender', 'length', 'max'=>10),
                        array('location,locale,timezone,country,email', 'safe'),
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
                'coupons'=>array(self::HAS_MANY,'Coupon','user'),
                'coupons_count'=>array(self::STAT,'Coupon','user'),
                'transactions'=>array(self::HAS_MANY,'Transaction','buyer_fb_id',
                                                                                 'condition'=>'transactions.is_cancelled=0', 
                                                                                ), 

                'buyer_deals'=>array(self::MANY_MANY,'Deal','tbl_deal_buyer(buyer_fb_id,deal_id)'),   
               

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

   //To get the coupons count of user in a particular deal.
 
      public function getUserCouponCount($deal_id)
      {
             $criteria=new CDbCriteria;
            

             $criteria->condition="user='".$this->fb_id."' and deal='".$deal_id."' and status!=3 ";	  
          
            return(Coupon::model()->count($criteria));
                    


      }
//To get the bougt count of user in a particular deal.
 
      public function getUserBoughtCount($deal_id)
      {
             $criteria=new CDbCriteria;
            

             $criteria->condition="buyer_fb_id='".$this->fb_id."' and deal_id='".$deal_id."'  and is_cancelled=0 ";	  
            
            $transactions=Transaction::model()->findAll($criteria);        
   
           $c=0;
           foreach($transactions as $t)
            {
                  
              $c+=$t->quantity;  

            }
      

 
          return($c);

          //  return(Transaction::model()->count($criteria));
                    


      }
  
     public function isUserExist($fb_id)
      {
             $criteria=new CDbCriteria;
             $criteria->select='fb_id';

          
             $criteria->condition='fb_id=:fb_id';

             $criteria->params=array(':fb_id'=>$fb_id); 	  
          
            return(Buyer::model()->count($criteria)==1);
                    


      }



public function getMail($user,$subject,$deal)
     {

     $tipped="";
  $totip="";

  $t="";
 
  if($deal->isTipped())
    {
       $t=" Congrats,The deal is yours now.";  
       $tipped="Congrats,The deal is yours now.which is Tipped at ".$deal->tipped_at." with ".$deal->getBoughtCount()." bought." ;
    } 
  else
    {
       $totip="Now The deal got ".$deal->getBoughtCount()." buyer.".($deal->tipping_point-$deal->getBoughtCount())." more need to get this offer.";

    }

 

          $mails=array(
              
              'buyer'=>array(
                    'bought'=>array('subject'=>'Thanks for buying Deal:'.$deal->title.$t,
                                    'body'=>'Dear  '.$this->first_name.','.$tipped.'.Thanks for buying the deal '.$deal->title.'.'.$totip.'The deal will end at '.$deal->end_date.'.Thank you.',

                                    ),     
     
                    'tipped'=>array('subject'=>'Congrats, Deal:'.$deal->title.' is Tipped',
                                    'body'=>'Dear  '.$this->first_name.',The deal '.$deal->title.' is ON now.It is tipped at '.$deal->tipped_at.' with '.$deal->tipping_point.' bought.The deal will end at '.$deal->end_date.'.Your coupons are generated.please look at the my coupons section.Thank you.',

                                    ),
                    'ended_with_tipped'=>array('subject'=>'Congrats,The Deal:'.$deal->title.' is yours now.',
                                    'body'=>'Dear '.$this->first_name.',Congrats, The deal '.$deal->title.'is yours now.The deal is ended by reaching tipping point '.$deal->tipping_point.' with '.$deal->getBoughtCount().' bought.You can come and collect your offer before your coupon expiry date:'.$deal->coupon_expiry.'.Thank you.',
                                    ),
                    'expired_without_tipped'=>array('subject'=>'Deal:'.$deal->title.' is Expired.',
                                    'body'=>'Dear '.$this->first_name.',The deal "'.$deal->title.'" you bought is expired  at '.$deal->end_date.' without reaching tipping point.So the deal is canceld now and  your amount is refunded in  your paypal account.Thank you.',       

                                    ),
                    'sold_out_before_exp'=>array('subject'=>'Congrats,The Deal:'.$deal->title.' is yours now',
                                    'body'=>'Dear  '.$this->first_name.',Congrats,The deal you bought '.$deal->title.'.is yours now which is sold out before expiry date:'.$deal->end_date.'.Now you can come with your coupon and collect your offer before coupon expiry date:'.$deal->coupon_expiry.'.Thank you.',

                                    ),  

                    'payment'=>array( 
                                    'subject'=>'Deal:'.$deal->title.' Payment notification',
                                    'body'=>'Dear '.$this->first_name.',Your payment for the deal '.$deal->title.' is successfull.',  

                                    ),  
                           ),  
              
 
                      );                         


 
         return($mails[$user][$subject]);
        }


 
     
} 
