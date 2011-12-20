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
    
    const NOT_PUBLISHED=0;
    const PUBLISHED=1;
    const TIPPED=2;
    const SOLD_OUT=3;
    const EXPIRED=4;
    const UNPUBLISHED=5;
    const SOLD_OUT_BEFORE_EXPIRY=6; 
    const DELETED=7;
    const SHOW_IN_RECENT_DEALS=8;
  
 public function getStatus($status_code)
   {
    $status=array(
          'NOT PUBLISHED', //0
          'PUBLISHED',  //1
          'TIPPED',    //2
          'SOLD OUT', //3
          'EXPIRED', //4
          'UNPUBLISHED',//5  
          'SOLD OUT BEFORE EXPIRY',//6  
          'DELETED',//7 
          'SHOW_IN_RECENT_DEALS',//8
       );

   return($status[$status_code]);

   }
public function getStatusText()
{
if($this->status==0)
 {
  return("NOT PUBLISHED");
 }
else if($this->status==1)
 {
   return("PUBLISHED"."<br/> will close on ".date('M-d-Y H:i:s',strtotime($this->end_date)));
 }
else if($this->status==2)
 {
   return("TIPPED"."<br/> will close on ".date('M-d-Y H:i:s',strtotime($this->end_date)));
 }
else if($this->status==3)
 {
   return("SOLD OUT");
 }
else if($this->status==4)
 {
   return("EXPIRED AT ".date('M-d-Y H:i:s',strtotime($this->end_date)));
 } 
else if($this->status==6)
 {
   return("SOLD OUT BEFORE EXPIRY".date('M-d-Y H:i:s',strtotime($this->end_date)));
 }
else if($this->status==8)
 {
   return("MOVED TO RECENT DEALS"."<br/>closed at ".date('M-d-Y H:i:s',strtotime($this->end_date)));
 }
else if($this->status==5)
 {
    if($this->isExpired()&&$this->isTipped())
    {
      return("SOLD OUT AND UNPUBLISHED");
    }
    else if($this->isExpired()&&!$this->isTipped())
    {
      return("EXPIRED AND UNPUBLISHED"."<br/>closed at ".date('M-d-Y H:i:s',strtotime($this->end_date))); 
    }
    else if(!$this->isExpired()&&!$this->isDealAvailable())
    {
      return("SOLD OUT BEFORE EXPIRY DATE AND UNPUBLISHED");  
    }
  return("UNPUBLISHED");
 
   
 }
}
public function getDealOptions()
{
  return array(
          '0'=>'Group Buy item',
          '1'=>'Group Free coupon',
         ); 
}
  public function getCurrencyCodeOptions()
        {
          return array(
            'USD'=>'USD($)',
            'HKD'=>'HKD($)',   
            'GBP'=>'GBP(£)',
            'EUR'=>'EUR(€)',
            'JPY'=>'JPY(¥)',
            'CAD'=>'CAD($)',
            'AUD'=>'AUD($)',

           );  

        }
 public function getCurrencySymbol()
 {
   if($this->currency_code=="USD")
     {
      return("$");
     }
  else if($this->currency_code=="HKD")
     {
      return("$");
     }
  else if($this->currency_code=="GBP")
     {
      return("£");
     }
 else if($this->currency_code=="EUR")
     {
      return("€");
     }
  else if($this->currency_code=="JPY")
     {
      return("¥");
     }
   else if($this->currency_code=="CAD")
     {
      return("$");
     }
  else if($this->currency_code=="AUD")
     {
      return("$");
     }
  
 }
public function daysInTodaysDealsAfterExp()
{
  return array(
          '1'=>'1',
          '2'=>'2',
          '3'=>'3',
          '4'=>'4',
          '5'=>'5',
          '6'=>'6',
          '7'=>'7',  
         ); 
}


	/**
	 * Returns the static model of the specified AR class.
	 * @return Deal the static model class
	 */
  public $group_discount;
  public $discount_mode;

  public $excel;
  public $excel1;//for ON DEALS
  public $excel2; //for OFF DEALS
   
  public $is_full_upload;
  public $is_full_upload1;//for ON DEALS
  public $is_full_upload2;//for OFF DEALS

 // public $excel1;

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
                        array('excel','file','types'=>'xls,xlsx','on'=>'upload'),
                        array('excel1','file','types'=>'xls,xlsx','on'=>'upload1'),
                        array('excel2','file','types'=>'xls,xlsx','on'=>'upload2'),
                         //array('excel1','required','on'=>'upload1'), 
                      //  array('excel2','required','on'=>'upload2'), 
                        // array('excel1,excel2','types'=>'xls,xlsx'),
			array('title,start_date,end_date,coupon_expiry,timezone,free_coupons,paypal_address,max_available,email', 'required','on'=>'publish'),
                        array('title','required','on'=>'add'),   
                        array('paypal_address,email','email'),   
                     //   array('start_date,end_date,coupon_expiry','type','type'=>'datetime','datetimeFormat'=>'yy-mm-dd h:mm:s'),  
			array('tipping_point, max_available, is_deal_on, category,max_purchase_units', 'numerical', 'integerOnly'=>true),
                        array('discount_value,discount_percentage,deal_price,retail_price','numerical','integerOnly'=>false),  
			array('title', 'length', 'max'=>256),
			//array('retail_price,', 'length', 'max'=>6),
			//array('discount_percentage, discount_value, advertiser', 'length', 'max'=>10),
                       //  array('start_date,end_date,coupon_expiry','date','format'=>'yyyy-mm-d h:m:s','on'=>'insert'),
                          //To validate tipping.It sholud be atleast 1.  
                     //   array('start_date','compare','compareValue'=>date('Y-m-d H:i:s'),'operator'=>'>=','on'=>'publish','message'=>'Start date should be greater than current datetime'),   


                         array('tipping_point','compare','compareValue'=>'0','operator'=>'>','message'=>'Tipping point should be greater than zero.','on'=>'publish'),                  
                           //To validate free coupons.It should be less than or equal to max_available units  
                         array('free_coupons','compare','compareAttribute'=>'max_available','operator'=>'<=','on'=>'publish','message'=>'Free coupons shouldn\'t be greater than maximum available units'),            

                           //To validate tipping is less than or equal to max_available units  
                         array('tipping_point','compare','compareAttribute'=>'max_available','operator'=>'<=','on'=>'publish','message'=>'Tipping point shouldn\'t be greater than maximum available units'),
                          //To validate group_discount is greater than 0 
                        array('group_discount','compare','compareValue'=>'0','operator'=>'>','on'=>'publish','message'=>'Groupdiscount should be greater than zero'),
                          //To validate retail price is greater than 0 
                        array('retail_price','compare','compareValue'=>'0','operator'=>'>','on'=>'groupbuy_item','message'=>'Retail price shoulb be greater than zero'),
                         //To validate max_purchase_units is greater than 0
                        array('max_purchase_units','compare','compareValue'=>'0','operator'=>'>','on'=>'publish','message'=>'Maximum purchase units value should be atleast 1'),
                          //To validate max_purchase_units is less than or equal to max_available        
                         array('max_purchase_units','compare','compareAttribute'=>'max_available','operator'=>'<=','on'=>'publish','message'=>'Maximum units a user can buy shouldn\'t be greater than maximum available units. '),        
                         //To validate end_date is greater than start_date      
                        array('end_date','compare','compareAttribute'=>'start_date','operator'=>'>','on'=>'publish','message'=>'Ending time should be greater than starting time'), 
                         //To validate coupon_expiry date is greater than end_date 
                        array('coupon_expiry','compare','compareAttribute'=>'end_date','operator'=>'>','on'=>'publish','message'=>'Coupon expiry time should be greater than Deal end time'),       
			array('description', 'length', 'max'=>400),
                        array('fine_print', 'length', 'max'=>1000), 
			array('website', 'length', 'max'=>50),
			array('address1, address2', 'length', 'max'=>100),
			array('days_in_todays_deal_after_exp,start_date, end_date,group_discount,coupon_expiry,tipped_at,max_purchase_units,published,timezone,free_coupons,is_expired,auto_publish,is_free_coupon,is_full_upload,is_full_upload1,is_full_upload2,excel1,excel2,currency_code', 'safe'),
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
                  
                 'deal_buyers'=>array(self::MANY_MANY,'Buyer','tbl_deal_buyer(deal_id,buyer_fb_id)'),
  
                 'coupons'=>array(self::HAS_MANY,'Coupon','deal',

                                                                  'condition'=>'coupons.status!=4', 
                                                                  ),
                 'coupons_count'=>array(self::STAT,'Coupon','deal',
                                                                   'condition'=>'status!=4', 
                                                                  ), 


                 'transactions'=>array(self::HAS_MANY,'Transaction','deal_id',

                                                                            'condition'=>'transactions.is_cancelled=0',
                                                                              ),
                 'cancelled_transactions'=>array(self::HAS_MANY,'Transaction','deal_id',

                                                                            'condition'=>'cancelled_transactions.is_cancelled=1',
                                                                              ),

                
                 'paid_transactions'=>array(self::HAS_MANY,'Transaction','deal_id',

                                                                           'condition'=>'paid_transactions.is_free=0  and paid_transactions.is_cancelled=0  ',  
                                                                                  ),    
                 'free_transactions'=>array(self::HAS_MANY,'Transaction','deal_id',

                                                                           'condition'=>'free_transactions.is_free=1 and free_transactions.is_cancelled=0',  
                                                                                  ), 
                    

                 'transactions_count'=>array(self::STAT,'Transaction','deal_id'),
                    //ALL DEAL CONFIRMATION CODES   
                 'all_used_confirmation_codes'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'all_used_confirmation_codes.is_used=1',  
                                                                                     ), 
                 'all_uploaded_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    
                                                                                     ), 
                   
                 'all_unused_confirmation_codes'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'all_unused_confirmation_codes.is_used=0',  
                                                                                     ), 
                 'all_unused_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                     'condition'=>'is_used=0',  
                                                                                     ),                
 
                 'all_used_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'is_used=1',  
                                                                                     ), 
                 'all_unused_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                     'condition'=>'is_used=0',  
                                                                                        ),
                       
                   //DEAL CONFIRMATION CODES (COMMON TO BOTH ON AND OFF DEALS)   
                 'used_confirmation_codes'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'used_confirmation_codes.is_used=1 and used_confirmation_codes.should_deal_on=2',  
                                                                                     ), 
                 'uploaded_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'should_deal_on=2',  
                                                                                     ), 
                     
                 'unused_confirmation_codes'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'unused_confirmation_codes.is_used=0 and  unused_confirmation_codes.should_deal_on=2',  
                                                                                     ), 
                 'next_unused_confirmation_code'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'next_unused_confirmation_code.is_used=0 and next_unused_confirmation_code.should_deal_on=2',
                                                                                    'limit'=>'1',    
                                                                                     ),   
                 'unused_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                     'condition'=>'is_used=0 and should_deal_on=2',  
                                                                                     ),                
 
                 'used_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'is_used=1 and should_deal_on=2',  
                                                                                     ), 
                 'unused_confirmation_code_count'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                     'condition'=>'is_used=0 and should_deal_on=2',  
                                                                                        ),
                       
             //ON DEAL CONFIRMATION CODES  
                  'used_confirmation_codes1'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'used_confirmation_codes1.is_used=1 and used_confirmation_codes1.should_deal_on=1',  
                                                                                     ), 
                  'uploaded_confirmation_code_count1'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'should_deal_on=1',  
                                                                                     ), 
                   
                 'unused_confirmation_codes1'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'unused_confirmation_codes1.is_used=0 and unused_confirmation_codes1.should_deal_on=1',  
                                                                                     ), 
                 'next_unused_confirmation_code1'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'next_unused_confirmation_code1.is_used=0 and next_unused_confirmation_code1.should_deal_on=1',
                                                                                    'limit'=>'1',  
                                                                                     ),
                 'unused_confirmation_code_count1'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                     'condition'=>'is_used=0 and should_deal_on=1',  
                                                                                     ),                
 
                 'used_confirmation_code_count1'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'is_used=1 and should_deal_on=1',  
                                                                                     ), 
                 'unused_confirmation_code_count1'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                     'condition'=>'is_used=0 and should_deal_on=1',
                                                                                     ),
                                                                                        
                  
              //OFF DEAL CONFIRMATION CODES  
                 'used_confirmation_codes2'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'used_confirmation_codes2.is_used=1 and used_confirmation_codes2.should_deal_on=0',  
                                                                                     ), 
                  'uploaded_confirmation_code_count2'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'should_deal_on=0',  
                                                                                     ),  
                   
                 'unused_confirmation_codes2'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'unused_confirmation_codes2.is_used=0 and unused_confirmation_codes2.should_deal_on=0',  
                                                                                     ), 
                 'next_unused_confirmation_code2'=>array(self::HAS_MANY,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'next_unused_confirmation_code2.is_used=0 and next_unused_confirmation_code2.should_deal_on=0',
                                                                                    'limit'=>'1',  
                                                                                     ),
                 'unused_confirmation_code_count2'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                     'condition'=>'is_used=0 and should_deal_on=0',  
                                                                                     ),                
 
                 'used_confirmation_code_count2'=>array(self::STAT,'BuyerConfirmationCode','deal_id',
                                                                                    'condition'=>'is_used=1 and should_deal_on=0',  
                                                                                     ),                        
                   
                    
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
                        'is_free_coupon' => 'Select a type:',
                         'excel'=>'Upload Confirmation Codes(Uploaded codes:'.$this->uploaded_confirmation_code_count.',UnUsed:'.$this->unused_confirmation_code_count.')', 
                        'excel1'=>'Upload Confirmation Codes DEAL ON (Uploaded codes:'.$this->uploaded_confirmation_code_count1.',UnUsed:'.$this->unused_confirmation_code_count1.')',
                        'excel2'=>'Upload Confirmation Codes DEAL OFF(Uploaded codes:'.$this->uploaded_confirmation_code_count2.',UnUsed:'.$this->unused_confirmation_code_count2.')',
                        'days_in_todays_deal_after_exp'=>'Move to Recent Deals After:',
                        'currency_code'=>'Choose currency',  
                        
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

/*
public function buyers_search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('first_name',$this->buyer);
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

		return new CActiveDataProvider("Coupon", array(
			'criteria'=>$criteria,
		));
	}
*/ 

    
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
    public function TimeZoneOptions()
      { 
        return array(
        '-12'=>'Pacific/Kwajalein(GMT-12.00)',
        '-11'=>'Pacific/Samoa(GMT-11.00)',
        '-10'=>'Pacific/Honolulu(GMT-10.00)',
        '-9'=>'America/Juneau(GMT-9.00)',
        '-8'=>'America/Los_Angeles(GMT-8.00)',
        '-7'=>'America/Denver(GMT-7.00)',
        '-6'=>'America/Mexico_City(GMT-6.00)',
        '-5'=>'America/New_York(GMT-5.00)',
        '-4'=>'America/Caracas(GMT-4.00)',
        '-3.5'=>'America/St_Johns(GMT-3.30)',
        '-3'=>'America/Argentina/Buenos_Aires(GMT-3.00)',
        '-2'=>'Atlantic/Azores(GMT-2.00)',// no cities here so just picking an hour ahead
        '-1'=>'Atlantic/Azores(GMT-1.00)',
        '0'=>'Europe/London(GMT-00.00)',
        '1'=>'Europe/Paris(GMT+1.00)',
        '2'=>'Europe/Helsinki(GMT+2.00)',
        '3'=>'Europe/Moscow(GMT+3.00)',
        '3.5'=>'Asia/Tehran(GMT+3.30)',
        '4'=>'Asia/Baku(GMT+4.00)',
        '4.5'=>'Asia/Kabul(GMT+4.30)',
        '5'=>'Asia/Karachi(GMT+5.00)',
        '5.5'=>'Asia/Calcutta(GMT+5.30)',
        '6'=>'Asia/Colombo(GMT+6.00)',
        '7'=>'Asia/Bangkok(GMT+7.00)',
        '8'=>'Asia/Singapore(GMT+8.00)',
        '9'=>'Asia/Tokyo(GMT+9.00)',
        '9.5'=>'Australia/Darwin(GMT+9.30)',
        '10'=>'Pacific/Guam(GMT+10.00)',
        '11'=>'Asia/Magadan(GMT+11.00)',
        '12'=>'Asia/Kamchatka(GMT+12.00)'
    );



      }
public function removeAllBuyers()
{
 foreach($this->deal_buyers as $buyer)
  {
   $this->removeBuyerFromDeal($buyer->fb_id); 
  }
}

public function associateBuyerToDeal($buyer_fb_id)
      {
        $sql="INSERT INTO tbl_deal_buyer(deal_id,buyer_fb_id) VALUES('".$this->id."','".$buyer_fb_id."')";
        $command=Yii::app()->db->createCommand($sql); 
        return $command->execute();  
      }
public function removeBuyerFromDeal($buyer_fb_id)
      {
        $sql="DELETE FROM  tbl_deal_buyer WHERE deal_id='".$this->id."' AND buyer_fb_id='".$buyer_fb_id."' ";
        $command=Yii::app()->db->createCommand($sql); 
        return $command->execute();  
      }
public function isBuyerInDeal($buyer_fb_id)
      {
        $sql="SELECT * FROM  tbl_deal_buyer WHERE deal_id='".$this->id."' AND buyer_fb_id='".$buyer_fb_id."' ";
        $command=Yii::app()->db->createCommand($sql); 
        //return $command->execute();
          return $command->execute()==1?true:false;  
      }

public function isBuyerHaveCode($buyer_fb_id)
      {
        $sql="SELECT * FROM  tbl_buyer_confirmation_code WHERE deal_id='".$this->id."' AND buyer_fb_id='".$buyer_fb_id."' ";
        $command=Yii::app()->db->createCommand($sql); 
        //return $command->execute();
          return $command->execute()==1?true:false;  
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
                      
                    //  $this->free_coupons+=$t->quantity;
                              
                          
   
                  }
               $this->save(false);
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
public function markAllCodesAreUnUsed()
{
           $criteria=new CDbCriteria;
           $criteria->condition="deal_id='".$this->id."' ";	  
           if(BuyerConfirmationCode::model()->count($criteria)==0)
             {
               return;
             }
           $codes=BuyerConfirmationCode::model()->findAll($criteria);
   foreach($codes as $c)
   {
     $c->is_used=0;
     $c->buyer_fb_id=null;
     $c->save();

   }
  
}
/*
public function assignConfirmationCodeToBuyers()
 {
  if($this->unused_confirmation_code_count1==0&&$this->unused_confirmation_code_count==0)
    {
     return;
    }
 
   foreach($this->deal_buyers as $buyer)
   {
     if($this->unused_confirmation_code_count1!=0)
       { //assign DEAL ON CODES
         foreach($this->next_unused_confirmation_code1 as $c)
          {
           $c->buyer_fb_id=$buyer->fb_id;
           $c->is_used=1;  
           $c->save();    
          }
   
       }
     else if($this->unused_confirmation_code_count!=0)
       { // assign COMMON TO ON AND OFF DEAL CODES
          foreach($this->next_unused_confirmation_code as $c)
          {
           $c->buyer_fb_id=$buyer->fb_id;
           $c->is_used=1;    
           $c->save();  
          }

       }
     
   }
  

 }
*/

public function assignConfirmationCodeToOnDealBuyer($buyer_fb_id)
 {
  if($this->unused_confirmation_code_count1==0&&$this->unused_confirmation_code_count==0)
    {
     return;
    }
  if($this->isBuyerHaveCode($buyer_fb_id))
    {
     return;
    }

    if($this->unused_confirmation_code_count1!=0)
       { //assign DEAL ON CODES
         foreach($this->next_unused_confirmation_code1 as $c)
          {
           $c->buyer_fb_id=$buyer_fb_id;
           $c->is_used=1;  
           $c->save(); 
          }
   
       }
     else if($this->unused_confirmation_code_count!=0)
       { // assign COMMON TO ON AND OFF DEAL CODES
          foreach($this->next_unused_confirmation_code as $c)
          {
           $c->buyer_fb_id=$buyer_fb_id;
           $c->is_used=1;  
           $c->save(); 
          }

       }
 }

public function assignCodeToOffDealBuyer($buyer_fb_id)
 {
  if($this->unused_confirmation_code_count2==0&&$this->unused_confirmation_code_count==0)
    {
     return;
    }
  if($this->isBuyerHaveCode($buyer_fb_id))
    {
     return;
    }

    if($this->unused_confirmation_code_count2!=0)
       { //assign DEAL ON CODES
         foreach($this->next_unused_confirmation_code2 as $c)
          {
           $c->buyer_fb_id=$buyer_fb_id;
           $c->is_used=1;  
           $c->save(); 
          }
   
       }
     else if($this->unused_confirmation_code_count!=0)
       { // assign COMMON TO ON AND OFF DEAL CODES
          foreach($this->next_unused_confirmation_code as $c)
          {
           $c->buyer_fb_id=$buyer_fb_id;
           $c->is_used=1;  
           $c->save(); 
          }

       }
 }
public function getBuyerConfirmationCode($buyer_fb_id)
{
   $criteria=new CDbCriteria;
            

           $criteria->condition="deal_id='".$this->id."' and buyer_fb_id='".$buyer_fb_id."' ";	  
           if(BuyerConfirmationCode::model()->count($criteria)==0)
             {
               return("NO");
             }
           $c=BuyerConfirmationCode::model()->find($criteria);

  return($c->code);
}

 public function mailBuyers($subject)
 {
 
   foreach($this->deal_buyers as $buyer)
   {
     $m=$this->getBuyerMail($subject,$buyer);
     
     mail($buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
   }
  

 }
 public function mailDealOwner($topic)
 {

     $m=$this->getMail("owner",$topic);     
     mail($this->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 

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
public function getCancelledBoughtCount()
 {
  $c=0;
    foreach($this->cancelled_transactions as $t)
      {
        
          $c+=$t->quantity;
      }

  return($c);

 }

 public function getUserBoughtCount($buyer_fb_id)
      {
             $criteria=new CDbCriteria;
            

           $criteria->condition="buyer_fb_id='".$buyer_fb_id."' and deal_id='".$this->id."'  and is_cancelled=0 ";	  
            
           $transactions=Transaction::model()->findAll($criteria);        
   
           $c=0;
           foreach($transactions as $t)
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
 public function getCountToTip()
 {
   $t=$this->tipping_point-$this->getBoughtCount();
   return($t);
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
          $session=new CHttpSession;        
         $session->open(); 

          $tz=$this->getTimeZone();
          date_default_timezone_set($tz[$this->timezone]); 

 
         $transaction=new Transaction;
        

         $payment_result=$session['reshash3'];                   

         $transaction->transaction_id=$payment_result['TRANSACTIONID'];

         $transaction->amount=$payment_result['AMT'];
         $transaction->quantity=$session['qty0'];
         $transaction->deal_id=$session['coupon_deal_id']; 

         $transaction->currency_code=$payment_result['CURRENCYCODE'];
         $transaction->ordertime=$payment_result['ORDERTIME'];
         $transaction->timestamp=$payment_result['TIMESTAMP'];

         $transaction->buyer_paypal_id=$session['PayerID'];        
         $transaction->buyer_fb_id=$session['me']['id']; 
        
        $result2=$session['reshash2']; //result of paypal api 2nd call

         $transaction->first_name=$result2['FIRSTNAME'];         
         $transaction->last_name=$result2['LASTNAME'];   
         $transaction->country_code=$result2['COUNTRYCODE'];           
         $transaction->ship_to_name=$result2['SHIPTONAME']; 
         $transaction->ship_to_street=$result2['SHIPTOSTREET'];
         $transaction->ship_to_city=$result2['SHIPTOCITY'];    
         $transaction->ship_to_state=$result2['SHIPTOSTATE'];
         $transaction->ship_to_zip=$result2['SHIPTOZIP'];
         $transaction->ship_to_country_code=$result2['SHIPTOCOUNTRYCODE']; 
         $transaction->ship_to_country_name=$result2['SHIPTOCOUNTRYNAME'];     

         $transaction->paypal_address=$result2['EMAIL'];     
        
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
         $free_order=$session['free_order_details']; 
        
         $transaction->transaction_id=rand();

         $transaction->amount=($this->retail_price-$this->discount_value);

         $transaction->quantity=$free_order->L_QTY0;

         $transaction->deal_id=$this->id; 

         $transaction->currency_code=null;
         $transaction->ordertime=date('Y-m-d H:i:s');
         $transaction->timestamp=date('Y-m-d H:i:s');

         $transaction->buyer_paypal_id=null;        
         $transaction->buyer_fb_id=$session['me']['id'];
         $transaction->is_free=1;  
 
            

         $transaction->first_name=$free_order->PERSONNAME;         
        // $transaction->last_name=$result2['LASTNAME'];   
         $transaction->country_code=$free_order->SHIPTOCOUNTRYCODE;           
        // $transaction->ship_to_name=$result2['SHIPTONAME']; 
         $transaction->ship_to_street=$free_order->SHIPTOSTREET;
         $transaction->ship_to_city=$free_order->SHIPTOCITY;    
         $transaction->ship_to_state=$free_order->SHIPTOSTATE;
         $transaction->ship_to_zip=$free_order->SHIPTOZIP;
       //  $transaction->ship_to_country_code=$result2['SHIPTOCOUNTRYCODE']; 
        // $transaction->ship_to_country_name=$result2['SHIPTOCOUNTRYNAME'];     
      
            
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
           
        $this->generateCoupon($t,2);
            
      }


}
//generate  a  coupons for a single transaction

public function generateCoupon($transaction,$status)
{
 
   $tz=$this->getTimeZone();
   date_default_timezone_set($tz[$me['timezone']]);
  
 
          for($i=0;$i<$transaction->quantity;$i++)
             {   
                $coupon[$i]=new Coupon;        
 
                $coupon[$i]->id=rand(); 

                $coupon[$i]->user=$transaction->buyer_fb_id;
                

                $coupon[$i]->status=$status; //1->PURCHASED ONHOLD,2.PURCHASED MONEY COLLECTED,3.REDEEMED 
                $coupon[$i]->deal=$transaction->deal_id;

                $coupon[$i]->entry_date=date('Y-m-d H:i:s');
                $coupon[$i]->transaction_id=$transaction->transaction_id; 
  
                if($transaction->is_free==1)
                 {
                   $coupon[$i]->is_free=1;
 
                 }
 
           //$coupon->deleted_date=new CDbExpression('NOW()'); //hAVE TO FIX THIS(WHAT IS DELETED DATE????)
              //  $coupon[$i]->qrcode=$coupon[$i]->id."qrcode.png"; 
 
                $coupon[$i]->save();
               /*  $this->widget('application.modules.groupbuy.extensions.qrcode.QRCodeGenerator',array(
                 'data' => $coupon[$i]->id."<br/>".$coupon[$i]->buyer->first_name." ".$coupon[$i]->buyer->last_name,
                 'subfolderVar' => false,
                 'matrixPointSize' => 10,
                 'filename'=>$coupon[$i]->qrcode, 
                 )); */
            }      

  return($coupon[0]);   


}


public function makeAllPreveousCouponActive()
{
  if($this->coupons_count==0)
    {
      return;
    }
 
 foreach($this->coupons as $coupon)
    {
   
    $coupon->status=2; //3-status code means the Coupon is deleted.
   // $coupon->deleted_date=date('Y-m-d H:i:s');
    $coupon->save(); 
    } 
}

public function RedeemAllCoupons()
{

 foreach($this->coupons as $coupon)
    {
   
    $coupon->status=3; //3-status code means the Coupon is deleted.
   // $coupon->deleted_date=date('Y-m-d H:i:s');
    $coupon->save(); 
    } 
}

public function deleteAllCoupons()
{

   //$criteria=new CDbCriteria;
   //$criteria->condition="deal='".$this->id."' ";
   foreach($this->coupons as $coupon)
    {
   
    $coupon->status=4; //3-status code means the Coupon is deleted.
    $coupon->deleted_date=date('Y-m-d H:i:s');
    $coupon->save(); 
    } 

}
/*
public function ActivateAllCoupons()
{

   //$criteria=new CDbCriteria;
   //$criteria->condition="deal='".$this->id."' ";
   foreach($this->coupons as $coupon)
    {
   
    $coupon->status=2; //3-status code means the Coupon is deleted.
    $coupon->deleted_date=date('Y-m-d H:i:s');
    $coupon->save(); 
    } 

}
*/
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
public function isSoldOut()
{
  if($this->max_available==$this->getBoughtCount())
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
public function getBuyerMail($subject,$buyer)
     {

     $tipped="";
  $totip="";

  $t="";
 
  if($this->isTipped())
    {
       $t=" Congrats,The deal is yours now.";  
       $tipped="Congrats,The deal is yours now.which is Tipped at ".$this->tipped_at." with ".$this->getBoughtCount()." bought." ;
    } 
  else
    {
       $totip="Now The deal got ".$this->getBoughtCount()." buyer.".($this->tipping_point-$this->getBoughtCount())." more need to get this offer.";

    }

 

          $mails=array(
              
             
                    'bought'=>array('subject'=>'Thanks for buying Deal:'.$this->title.$t,
                                    'body'=>'Dear  '.$buyer->first_name.','.$tipped.'.Thanks for buying the deal '.$this->title.'.'.$totip.'The deal will end at '.$this->end_date.'.Thank you.',

                                    ),     
     
                    'tipped'=>array('subject'=>'Congrats, Deal:'.$this->title.' is Tipped',
                                    'body'=>'Dear  '.$buyer->first_name.',The deal '.$this->title.' is ON now.It is tipped at '.$this->tipped_at.' with '.$this->tipping_point.' bought.The deal will end at '.$this->end_date.'.Your coupons are generated.please look at the my coupons section.Thank you.',

                                    ),
                    'ended_with_tipped'=>array('subject'=>'Congrats,The Deal:'.$this->title.' is yours now.',
                                    'body'=>'Dear '.$buyer->first_name.',Congrats, The deal '.$this->title.'is yours now.The deal is ended by reaching tipping point '.$this->tipping_point.' with '.$this->getBoughtCount().' bought.You can come and collect your offer before your coupon expiry date:'.$this->coupon_expiry.'.Thank you.',
                                    ),
                    'expired_without_tipped'=>array('subject'=>'Deal:'.$this->title.' is Expired.',
                                    'body'=>'Dear '.$buyer->first_name.',The deal "'.$this->title.'" you bought is expired  at '.$this->end_date.' without reaching tipping point.So the deal is canceld now and  your amount is refunded in  your paypal account.Thank you.',       

                                    ),
                     'expired_without_tipped1'=>array('subject'=>'Deal:'.$this->title.' is Expired.',
                                                           'body'=>'Dear '.$buyer->first_name.',The deal "'.$this->title.'" you bought is expired  at '.$this->end_date.' without reaching tipping point.So the deal is canceld now.Thank you.',),
                    'sold_out_before_exp'=>array('subject'=>'Congrats,The Deal:'.$this->title.' is yours now',
                                    'body'=>'Dear  '.$buyer->first_name.',Congrats,The deal you bought '.$this->title.'.is yours now which is sold out before expiry date:'.$this->end_date.'.Now you can come with your coupon and collect your offer before coupon expiry date:'.$this->coupon_expiry.'.Thank you.',

                                    ),  

                    'payment'=>array( 
                                    'subject'=>'Deal:'.$this->title.' Payment notification',
                                    'body'=>'Dear '.$buyer->first_name.',Your payment for the deal '.$this->title.' is successfull.',  

                                    ),  
                       'coupon_generated'=>array('subject'=>'Coupon generated  for the Deal:'.$this->title.'  you bought.',
                                                           'body'=>'Dear '.$buyer->first_name.',Coupon(s) generated for the deal "'.$this->title.'" you bought.please find your coupons at "My Coupons" section  in our facebook groupbuy app and Redeem the coupon before '.$this->coupon_expiry.' .Thank you.',

                          ),      
                           
              
 
                      );                         


 
         return($mails[$subject]);
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
                 // $this->redirect(array('/paypal/APIError','msg'=>'Error in curl'));   
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
