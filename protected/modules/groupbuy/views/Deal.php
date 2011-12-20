<?php

/**
 * This is the model class for table "tbl_deal".
 *
 * The followings are the available columns in table 'tbl_deal':
 * @property integer $id
 * @property string $title
 * @property integer $tipping_point
 * @property string $retail_price
 * @property string $discount_percentage
 * @property string $discount_value
 * @property string $description
 * @property string $website
 * @property string $address1
 * @property string $address2
 * @property string $deal_price
 * @property integer $max_available
 * @property string $start_date
 * @property string $end_date
 * @property integer $is_deal_on
 * @property integer $category
 * @property string $advertiser
 */
class Deal extends CActiveRecord
{
    
    //const IS_FREE_COUPON=0;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Deal the static model class
	 */
  public $group_discount;
  public $discount_mode;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_deal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,start_date,end_date,coupon_expiry,timezone,free_coupons,paypal_address,max_available,email', 'required','on'=>'insert,update'),
                        array('paypal_address,email','email'),   
                     //   array('start_date,end_date,coupon_expiry','type','type'=>'datetime','datetimeFormat'=>'yy-mm-dd h:mm:s'),  
			array('tipping_point, max_available, is_deal_on, category,max_purchase_units', 'numerical', 'integerOnly'=>true),
                        array('discount_value,discount_percentage,deal_price,retail_price','numerical','integerOnly'=>false),  
			array('title', 'length', 'max'=>256),
			//array('retail_price,', 'length', 'max'=>6),
			//array('discount_percentage, discount_value, advertiser', 'length', 'max'=>10),
                       //  array('start_date,end_date,coupon_expiry','date','format'=>'yyyy-mm-d h:m:s','on'=>'insert'),
                          //To validate tipping.It sholud be atleast 1.  
                         array('tipping_point','compare','compareValue'=>'0','operator'=>'>','message'=>'Tipping point shouldn\'t be greater than zero.','on'=>'insert,update'),                  
                           //To validate free coupons.It should be less than or equal to max_available units  
                         array('free_coupons','compare','compareAttribute'=>'max_available','operator'=>'<=','on'=>'insert,update','message'=>'Free coupons shouldn\'t be greater than maximum available units'),            

                           //To validate tipping is less than or equal to max_available units  
                         array('tipping_point','compare','compareAttribute'=>'max_available','operator'=>'<=','on'=>'insert,update','message'=>'Tipping point shouldn\'t be greater than maximum available units','on'=>'insert,update'),
                          //To validate group_discount is greater than 0 
                        /*array('group_discount','compare','compareValue'=>'0','operator'=>'>','on'=>'insert,update','message'=>'Groupdiscount should be greater than 0'),*/
                          //To validate retail price is greater than 0 
                        array('retail_price','compare','compareValue'=>'0','operator'=>'>','on'=>'insert,update','message'=>'Retail price shoulb be greater than 0'),
                         //To validate max_purchase_units is greater than 0
                        array('max_purchase_units','compare','compareValue'=>'0','operator'=>'>','on'=>'insert,update','message'=>'Maximum purchase units value should be atleast 1'),
                          //To validate max_purchase_units is less than or equal to max_available        
                         array('max_purchase_units','compare','compareAttribute'=>'max_available','operator'=>'<=','on'=>'insert,update','message'=>'Maximum units a user can buy shouldn\'t be greater than maximum available units. '),        
                         //To validate end_date is greater than start_date      
                        array('end_date','compare','compareAttribute'=>'start_date','operator'=>'>','on'=>'insert,update','message'=>'Ending time should be greater than starting time'), 
                         //To validate coupon_expiry date is greater than end_date 
                        array('coupon_expiry','compare','compareAttribute'=>'end_date','operator'=>'>','on'=>'insert,update','message'=>'Coupon expiry time should be greater than Deal end time'),       
			array('description', 'length', 'max'=>400),
                        array('fine_print', 'length', 'max'=>1000), 
			array('website', 'length', 'max'=>50),
			array('address1, address2', 'length', 'max'=>100),
			array('start_date, end_date,group_discount,coupon_expiry,tipped_at,max_purchase_units,published,timezone,free_coupons,is_expired,auto_publish', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, tipping_point, retail_price, discount_percentage, discount_value, description, website, address1,fine_print, address2, deal_price, max_available, start_date, end_date, is_deal_on, category, advertiser', 'safe', 'on'=>'search'),
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
                 'coupons'=>array(self::HAS_MANY,'Coupon','deal'),
                 'coupons_count'=>array(self::STAT,'Coupon','deal'), 


                 'transactions'=>array(self::HAS_MANY,'Transaction','deal_id',

                                                                            'condition'=>'transactions.is_cancelled=0',
                                                                              ),

                
                 'paid_transactions'=>array(self::HAS_MANY,'Transaction','deal_id',

                                                                           'condition'=>'paid_transactions.is_free=0  and paid_transactions.is_cancelled=0  ',  
                                                                                  ),    
                 'free_transactions'=>array(self::HAS_MANY,'Transaction','deal_id',

                                                                           'condition'=>'free_transactions.is_free=1 and free_transactions.is_cancelled=0',  
                                                                                  ), 
                    

                 'transactions_count'=>array(self::STAT,'Transaction','deal_id'),

                 //'free_coupons'=>array(self::HAS_MANY,'Coupon','deal'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'tipping_point' => 'Tipping point',
			'retail_price' => 'Retail Price',
			'discount_percentage' => 'Discount Percentage',
			'discount_value' => 'Discount Value',
			'description' => 'Description',
                        'paypal_address'=>'Paypal email(Sandbox test account):',
                        'email'=>'Email(To receive notifications):',
			'website' => 'Website',
			'address1' => 'Address1',
			'address2' => 'Address2',
                        'fine_print'=>'Fine print',
			'deal_price' => 'Deal Price',
			'max_available' => 'Maximum units available',
                        'max_purchase_units'=>'Maximum units a user can buy:', 
			'start_date' => 'Start on',
			'end_date' => 'End on',
                        'coupon_expiry'=>'Coupon expiry date:', 
			'is_deal_on' => 'Is Deal On',
			'category' => 'Category',
			'advertiser' => 'Advertiser',
                        'auto_publish' => 'Auto Publish at start time',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('tipping_point',$this->tipping_point);
		$criteria->compare('retail_price',$this->retail_price,true);
		$criteria->compare('discount_percentage',$this->discount_percentage,true);
		$criteria->compare('discount_value',$this->discount_value,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('deal_price',$this->deal_price,true);
		$criteria->compare('max_available',$this->max_available);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('is_deal_on',$this->is_deal_on);
		$criteria->compare('category',$this->category);
		$criteria->compare('advertiser',$this->advertiser,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
    
    public function getTimeZone()
      { 
        return array(
        '-12'=>'Pacific/Kwajalein',
        '-11'=>'Pacific/Samoa',
        '-10'=>'Pacific/Honolulu',
        '-9'=>'America/Juneau',
        '-8'=>'America/Los_Angeles',
        '-7'=>'America/Denver',
        '-6'=>'America/Mexico_City',
        '-5'=>'America/New_York',
        '-4'=>'America/Caracas',
        '-3.5'=>'America/St_Johns',
        '-3'=>'America/Argentina/Buenos_Aires',
        '-2'=>'Atlantic/Azores',// no cities here so just picking an hour ahead
        '-1'=>'Atlantic/Azores',
        '0'=>'Europe/London',
        '1'=>'Europe/Paris',
        '2'=>'Europe/Helsinki',
        '3'=>'Europe/Moscow',
        '3.5'=>'Asia/Tehran',
        '4'=>'Asia/Baku',
        '4.5'=>'Asia/Kabul',
        '5'=>'Asia/Karachi',
        '5.5'=>'Asia/Calcutta',
        '6'=>'Asia/Colombo',
        '7'=>'Asia/Bangkok',
        '8'=>'Asia/Singapore',
        '9'=>'Asia/Tokyo',
        '9.5'=>'Australia/Darwin',
        '10'=>'Pacific/Guam',
        '11'=>'Asia/Magadan',
        '12'=>'Asia/Kamchatka'
    );



      }

 public function cancelAllTransactions()
 {
   foreach($this->transactions as $t)
          {
           $t->is_cencelled=1;
           $t->cancelled_at=date('Y-m-d H:i:s');
           $t->save();             
 
          }  

  
 } 
 public function refundToAll()
 { 

          foreach($this->free_transactions as $t)
                   {
                       
 
                               $t->is_cancelled=1;
                               $t->cancelled_at=date('Y-m-d H:i:s');
                               $t->save();   
                             //sending mails to the customers who bought free coupons  
                               $m=$t->getMail("buyer","unpublished1");  
                               mail($t->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');
                      
                      $this->free_coupons+=$t->quantity;
                              
                          
   
                  }
               $this->save();
           //Refund all transactions.
                foreach($this->paid_transactions as $t)
                   {
                        
                        $nvpStr="&TRANSACTIONID=".$t->transaction_id."&REFUNDTYPE=Full&CURRENCYCODE=".$t->currency_code."&NOTE='' ";

                        //if(strtoupper($refundType)=="PARTIAL") $nvpStr=$nvpStr."&AMT=$amount";

                         /* Make the API call to PayPal, using API signature.
                         The API response is stored in an associative array called $resArray */
                         $resArray=$this->hash_call("RefundTransaction",$nvpStr);  
 
                            $ack=strtoupper($resArray['ACK']);
                         if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')   //CHECK WHETHER THE REFUND IS SUCCESS OR NOT
                          {
                           //mail about refund failure.
                              
                        mail($t->buyer->email,'Paypal:Refund to your paypal account is Failed','Refund to your account failed due to the following reason:'.$resArray['L_SHORTMESSAGE0'].":".$resArray['L_LONGMESSAGE0'],'From:parrysgroupbuy@groupbuy.com');                                       
                              
                          
                              mail($this->email,'Refund to '.$t->buyer->first_name.' '.$t->buyer->last_name.'\'s paypal account is failed','Refund to '.$t->buyer->first_name." ".$t->buyer->last_name.'\'s account is failed due to the following reason:'.$resArray['L_SHORTMESSAGE0'].":".$resArray['L_LONGMESSAGE0'],'From:parrysgroupbuy@groupbuy.com');  
                 
                 
                          }
                         else
                          {
                             $t->is_refunded=1;
                             $t->is_cancelled=1;
                             $t->cancelled_at=date('Y-m-d H:i:s'); 
                             $t->refund_transaction_id=$resArray['REFUNDTRANSACTIONID'];
                             $t->total_refund_amt=$resArray['TOTALREFUNDEDAMOUNT'];
                             $t->fee_refund_amt=$resArray['FEEREFUNDAMT'];
                             $t->net_refund_amt=$resArray['NETREFUNDAMT'];
                             $t->refund_timestamp=$resArray['TIMESTAMP'];
                             $t->refund_currency_code=$resArray['CURRENCYCODE'];

                             $t->save();
                            
                               $m=$t->getMail("buyer","unpublished");  
                             
                              mail($t->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');                                       
                               
 
                          } 

                   } //end refund transaction loop


 }
 public function getBoughtCount()
 {
  $c=0;
    foreach($this->transactions as $t)
      {
        
          $c+=$t->quantity;
      }

  return($c);

 }
 public function getFreeBoughtCount()
 {
  $c=0;
    foreach($this->free_transactions as $t)
      {
         
          $c+=$t->quantity;
      }

  return($c);

 }

 public function getPaidBoughtCount()
 {
  $c=0;
    foreach($this->paid_transactions as $t)
      {
             
          $c+=$t->quantity;
      }

  return($c);

 }
 public function store_transaction()
        {
          $tz=$this->getTimeZone();
          date_default_timezone_set($tz[$this->timezone]); 

 
         $transaction=new Transaction;
         $session=new CHttpSession;        
         $session->open();     
         $payment_result=$session['reshash'];  
          
         $transaction->transaction_id=$payment_result['TRANSACTIONID'];
         $transaction->amount=$payment_result['AMT'];
         $transaction->quantity=$session['qty0'];
         $transaction->deal_id=$session['coupon_deal_id']; 

         $transaction->currency_code=$payment_result['CURRENCYCODE'];
         $transaction->ordertime=$payment_result['ORDERTIME'];
         $transaction->timestamp=$payment_result['TIMESTAMP'];

         $transaction->buyer_paypal_id=$session['PayerID'];        
         $transaction->buyer_fb_id=$session['me']['id']; 
        
            
         $transaction->save();


         return($transaction);
        }
 public function store_free_transaction()
        {
          $tz=$this->getTimeZone();
          date_default_timezone_set($tz[$this->timezone]); 

         $transaction=new Transaction;
         $session=new CHttpSession;        
         $session->open();       
          
         $transaction->transaction_id=rand();

         $transaction->amount=($this->retail_price-$this->discount_value);

         $transaction->quantity=1;
         $transaction->deal_id=$this->id; 

         $transaction->currency_code=null;
         $transaction->ordertime=date('Y-m-d H:i:s');
         $transaction->timestamp=date('Y-m-d H:i:s');

         $transaction->buyer_paypal_id=null;        
         $transaction->buyer_fb_id=$session['me']['id'];
         $transaction->is_free=1;  
        
            
         if(!$transaction->save())
           {
             $e=$transaction->getErrors();  
             echo "<pre>";
             print_r($e);      
             echo "</pre>";
             return;    
           }


         return($transaction);
        }

//generate coupons for all transactions
public function generateCoupons()
{
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$me['timezone']]);
  
    foreach($this->transactions as $t)
      { 
           
        $this->generateCoupon($t);
            
      }


}
//generate  a  coupons for a single transaction

public function generateCoupon($transaction)
{
 
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$me['timezone']]);
  
 
          for($i=0;$i<$transaction->quantity;$i++)
             {   
                $coupon[$i]=new Coupon;        
 
                $coupon[$i]->id=rand(); 

                $coupon[$i]->user=$transaction->buyer_fb_id;
                

                $coupon[$i]->status=1; //1->PURCHASED ONHOLD,2.PURCHASED MONEY COLLECTED,3.REDEEMED 
                $coupon[$i]->deal=$transaction->deal_id;

                $coupon[$i]->entry_date=date('Y-m-d H:i:s');
                $coupon[$i]->transaction_id=$transaction->transaction_id; 
  
                if($transaction->is_free==1)
                 {
                   $coupon[$i]->is_free=1;
 
                 }
 
           //$coupon->deleted_date=new CDbExpression('NOW()'); //hAVE TO FIX THIS(WHAT IS DELETED DATE????)
  
                $coupon[$i]->save();
            }      

  return($coupon[0]);   


}
//generate  a  coupons for a single transaction

public function generateFreeCoupon($buyer_fb_id)
{
 
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$me['timezone']]);
  
   
                $coupon=new Coupon;        
 
                $coupon->id=rand(); 

                $coupon->user=$buyer_fb_id;
                $coupon->is_free=1;                

                $coupon->status=1; //1->PURCHASED ONHOLD,2.PURCHASED MONEY COLLECTED,3.REDEEMED 
                $coupon->deal=$this->id;

                $coupon->entry_date=date('Y-m-d H:i:s');
                $coupon->transaction_id=null; 
 
               //$coupon->deleted_date=new CDbExpression('NOW()'); //hAVE TO FIX THIS(WHAT IS DELETED DATE????)
  
                $coupon->save();        
  return($coupon);

}

public function RedeemCoupon($coupon)
{

   $criteria=new CDbCriteria;
   $criteria->condition="deal='".$this->id."' ";

  
    $coupon->status=3; //3-status code means the Coupon is redeemed.
    $coupon->deleted_date=date('Y-m-d H:i:s');
    $coupon->save(); 

  
   
//   Coupon::model()->deleteAll($criteria); 

}
 //to check whether the deal coupon is expired or not
 public function isCouponExpired()
 {
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$this->timezone]);
   
   if(mktime()<strtotime($this->coupon_expiry))
     {

        return(false);
     } 
   else
     {
        return(true);
     }

 }


public function store_buyer()
 { 
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$me['timezone']]);                 
         
           $session=new CHttpSession;        
           $session->open();
           $me=$session['me'];
 
   
  

           $buyer=new Buyer;  

           if($buyer->isUserExist($me['id']))
             {  

               return;

             }

           $buyer->fb_id=$me['id'];
           $buyer->first_name=$me['first_name'];
           $buyer->last_name=$me['last_name'];

           if(isset($me['location']['name']))
             $buyer->location=$me['location']['name'];
           else
             $buyer->location='Not specified';

           $buyer->email=$me['email'];
           $buyer->gender=$me['gender'];

            if(isset($me['timezone']))
             $buyer->timezone=$me['timezone']; 
            else
             $buyer->timezone=0.0;   

             if(isset($me['locale']))  
               $buyer->locale=$me['locale'];
             else
               $buyer->locale='N/A';

             if(isset($session['signedRequest']['user']['country']))
               {
                $buyer->country=$session['signedRequest']['user']['country']; 
               }
              else
               {
                $buyer->country='Not specified';
               }  

        $buyer->create_time=date('Y-m-d H:i:s');
        $buyer->updated_time=date('Y-m-d H:i:s'); 
        $buyer->save();
                           
  }
 //to check whether the deal is expired or not
 public function isExpired()
 {
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$this->timezone]);
   
   if(mktime()<strtotime($this->end_date))
     {

        return(false);
     } 
   else
     {
        return(true);
     }

 }

//to check whether the deal is tipped or not
 public function isTipped()
 {
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$this->timezone]);
    
   if($this->getBoughtCount()>=$this->tipping_point)
    {
      return(true);      

    }
   else
   {
     return(false);   
    
   }
   
 }

//function to chect whether the deal is available or not 
 public function isDealAvailable()
 {
  $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$this->timezone]);
   
   if(mktime()<strtotime($this->end_date))
     {
        if($this->getBoughtCount()==$this->max_available)
         {
           return(false); 
         }
       else
         {
           return(true); 
         }
     }
   else
     {
      return(false);
     }   

 }

 //function to get no.of available coupons
 public function getBalance()
 {
  return($this->max_available-$this->getBoughtCount());
 }
  //function to check whether all the coupons of this deal sold out or not
 public function isEmpty()
 {
   if($this->getBoughtCount()==$this->max_available)
   {
     return(true);
   }
   else
   {
    return(false);
   } 
 }
 //Function to check whether the deal is on or not
 public function isDealOn()
 {
  $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$this->timezone]);
   
   if(mktime()<strtotime($this->end_date))
     {
        if($this->getBoughtCount()==$this->max_available)
         {
           return(false); 
         }
       else if($this->getBoughtCount()>=$this->tipping_point)
         {
           return(true);
         }
       else
         {
           return(false); 
         }
     }
   else
     {
      return(false);
     }   

 }
//function to get the appropriate mail
public function getMail($user,$subject)
 {
    $tz=$this->getTimeZone();
    date_default_timezone_set($tz[$this->timezone]);
 
  
  $to_tip="";
  $to_end="";

  if(!$this->isTipped())
    {
 
     $to_tip=($this->tipping_point-$this->getBoughtCount())." more need to make the deal ON.";   

    }
  else
    {
      $to_end=($this->max_available-$this->getBoughtCount())." more need to finish the deal before expiry."; 

    }
    
  

     
    

   $mails=array(
              'owner'=>array(
     
                    'published'=>array('subject'=>'Deal:'.$this->title.' publish notification',
                                       'body'=>'Dear deal owner,Your deal '.$this->title.' has been published at'.$this->start_date.'.Thank you.',

                                      ),
                    'unpublished'=>array('subject'=>'Deal:'.$this->title.' unpublish notification',
                                       'body'=>'Dear deal owner,Your deal '.$this->title.' has been unpublished at '.date("Y-m-d H:i:s").'.Thank you.',

                                      ),  
                    'tipped'=>array('subject'=>'Congrats,The Deal:'.$this->title.' is Tipped.',
                                    'body'=>'Dear deal owner,Your deal is ON now.It is tipped at '.$this->tipped_at.' with '.$this->tipping_point.' bought.Coupons are generated for the buyers.Thank you',

                                    ),
                    'ended_with_tipped'=>array('subject'=>'Congrats,The Deal:'.$this->title.' ended as tipped',
                                    'body'=>'Dear deal owner,Your deal '.$this->title.' is ended by reaching tipping point '.$this->tipping_point.' with  '.$this->getBoughtCount().' bought.Now you can start collecting the coupons and deliver your offer.The coupon expiry is at '.$this->coupon_expiry.'. Thank you',

                                    ),
                    'expired_without_tipped'=>array('subject'=>'Deal:'.$this->title.' Expired',
                                    'body'=>'Dear deal owner,Your deal '.$this->title.' is expired without reaching tipping point '.$this->tipping_point.' with '.$this->getBoughtCount().' bought.So the buyers got refunded their complete amount in their paypal account and the deal is cancelled.Thank you',

                                    ),  
                     'sold_out_before_exp'=>array('subject'=>'Congrats.The Deal:'.$this->title.' is sold out before expiry date:'.$this->end_date,
                                    'body'=>'Dear  deal owner ,Congrats,All coupons of your deal '.$this->title.'.is sold out before expiry date.Now you can start giving the offer by collecting coupons.The coupon expiry date is at '.$this->coupon_expiry.'.Thank you.',

                                    ),
                    'sold_status'=>array('subject'=>'Deal:'.$this->title.' sold status',
                                       'body'=>'Dear deal owner,Your deal '.$this->title.' got '.$this->getBoughtCount().' bought.'.$to_tip.$to_end.'.Thank you.',

                                      ),
                    'expired_without_buyers'=>array('subject'=>'Deal:'.$this->title.' is ended without any buyers',
                                       'body'=>'Dear deal owner,We are sorry to say that your deal '.$this->title.' ended at '.$this->end_date.' without any buyers so the deal cancelled.Thank you.',

                                      ),

                      
                           ),
           
    
  
             );
   return($mails[$user][$subject]);

 }

 //this function is used to get the appropriate NVP HEADER (i.e.API CREDENTIALS)
      
       public function nvpHeader()
        {
        /*
         global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
        */
         //global  $AUTH_token,$AUTH_signature,$AUTH_timestamp;
 
          $nvpHeaderStr = "";

          if(!empty(Yii::app()->controller->module->AUTHMODE))
           {
	   //$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
	   //$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
	   //$AuthMode = "THIRDPARTY";Partner's API Credential and Merchant Email as Subject are required.
	   $AuthMode =Yii::app()->controller->module->AUTHMODE; 
           } 
         else 
           {
	
	      if((!empty(Yii::app()->controller->module->API_USERNAME)) && (!empty(Yii::app()->controller->module->API_PASSWORD)) && (!empty(Yii::app()->controller->module->API_SIGNATURE)) && (!empty(Yii::app()->controller->module->SUBJECT)))
               {
		$AuthMode = "THIRDPARTY";
	       }
	
              else if((!empty(Yii::app()->controller->module->API_USERNAME)) && (!empty(Yii::app()->controller->module->API_PASSWORD)) && (!empty(Yii::app()->controller->module->API_SIGNATURE)))
               {
		$AuthMode = "3TOKEN";
	       }
	
	      else if (!empty(Yii::app()->controller->module->AUTH_TOKEN) && !empty(Yii::app()->controller->module->AUTH_SIGNATURE) && !empty(Yii::app()->controller->module->AUTH_TIMESTAMP)) 
              {
		$AuthMode = "PERMISSION";
	      }
             else if(!empty(Yii::app()->controller->module->SUBJECT))
             {
		$AuthMode = "FIRSTPARTY";
	     }
          }
    switch($AuthMode)
          {
	
	case "3TOKEN" : 
			$nvpHeaderStr = "&PWD=".urlencode(Yii::app()->controller->module->API_PASSWORD)."&USER=".urlencode(Yii::app()->controller->module->API_USERNAME)."&SIGNATURE=".urlencode(Yii::app()->controller->module->API_SIGNATURE);
			break;
	case "FIRSTPARTY" :
			$nvpHeaderStr = "&SUBJECT=".urlencode(Yii::app()->controller->module->SUBJECT);
			break;
	case "THIRDPARTY" :
			$nvpHeaderStr = "&PWD=".urlencode(Yii::app()->controller->module->API_PASSWORD)."&USER=".urlencode(Yii::app()->controller->module->API_USERNAME)."&SIGNATURE=".urlencode(Yii::app()->controller->module->API_SIGNATURE)."&SUBJECT=".urlencode(Yii::app()->controller->module->SUBJECT);
			break;		
	case "PERMISSION" :
		    $nvpHeaderStr = formAutorization(Yii::app()->controller->module->AUTH_TOKEN,Yii::app()->controller->module->AUTH_SIGNATURE,Yii::app()->controller->module->AUTH_TIMESTAMP);
		    break;
       }
	return $nvpHeaderStr;
}     

/**
  * hash_call: Function to perform the API call to PayPal using API signature
  * @methodName is name of API  method.
  * @nvpStr is nvp string.
  * returns an associtive array containing the response from the server.
*/


public function hash_call($methodName,$nvpStr)
{
   $session=new CHttpSession;
    
	//declaring of global variables
	//global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
	// form header string
	$nvpheader=$this->nvpHeader();
	//setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,Yii::app()->controller->module->API_ENDPOINT);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	
	//in case of permission APIs send headers as HTTPheders
	if(!empty(Yii::app()->controller->module->AUTH_TOKEN) && !empty(Yii::app()->controller->module->AUTH_SIGNATURE) && !empty(Yii::app()->controller->module->AUTH_TIMESTAMP))
	 {
		$headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
    curl_setopt($ch, CURLOPT_HEADER, false);
	}
	else 
	{
		$nvpStr=$nvpheader.$nvpStr;
	}
    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
	if(Yii::app()->controller->module->USE_PROXY)
	curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 

	//check if version is included in $nvpStr else include the version.
	if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) 
        {
		$nvpStr = "&VERSION=" . urlencode(Yii::app()->controller->module->VERSION) . $nvpStr;	
	}
	
	$nvpreq="METHOD=".urlencode($methodName).$nvpStr;
	
	//setting the nvpreq as POST FIELD to curl
	curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

	//getting response from server
	$response = curl_exec($ch);

	//convrting NVPResponse to an Associative Array
	$nvpResArray=$this->deformatNVP($response);
	$nvpReqArray=$this->deformatNVP($nvpreq);

	$session->open();
        $session['nvpReqArray']=$nvpReqArray;
        $session->close(); 

	if (curl_errno($ch)) {
		// moving to display page to display curl errors
                  $session->open();
                
		  $session['curl_error_no']=curl_errno($ch) ;
		  $session['curl_error_msg']=curl_error($ch);
		  //$location = "APIError.php";
                  $session->close(); 
                  $this->redirect(array('/paypal/APIError','msg'=>'Error in curl'));   
 	      	 // header("Location: $location");
	 } else {
		 //closing the curl
			curl_close($ch);
	  }

return $nvpResArray;
 }
/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
  * It is usefull to search for a particular key and displaying arrays.
  * @nvpstr is NVPString.
  * @nvpArray is Associative Array.
  */

public function deformatNVP($nvpstr)
{

	$intial=0;
 	$nvpArray = array();


	while(strlen($nvpstr)){
		//postion of Key
		$keypos= strpos($nvpstr,'=');
		//position of value
		$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

		/*getting the Key and Value values and storing in a Associative Array*/
		$keyval=substr($nvpstr,$intial,$keypos);
		$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
		//decoding the respose
		$nvpArray[urldecode($keyval)] =urldecode( $valval);
		$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
     }
	return $nvpArray;
}
public function formAutorization($auth_token,$auth_signature,$auth_timestamp)
{
	$authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
	return $authString;
}

 
    
}
