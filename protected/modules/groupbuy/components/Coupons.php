<?php
/**
 BuyerCoupon is a widget used to list all the coupons of a buyer
 */

class Coupons extends CWidget
{


  public $buyer_fb_id;

  public $page_id;
  public $advertiser;
  
  public $couponDataProvider;  


 public function init()
 {

  $this->advertiser=VerifiedFbPages::model()->getAdvertiser($this->page_id);

 
  $this->couponDataProvider=new CActiveDataProvider('Coupon',array(
       'pagination'=>array(
         'pageSize'=>1,
               
          ),
       
         'criteria'=>array(
                         //'select'=>'id,title,published',  
                         'condition'=>"t.user='".$this->buyer_fb_id."'  and coupon_deal.advertiser='".$this->advertiser."' and t.status!=4 and t.status!=1 ",//dnt display redeemed(3),deleted(4) or expired coupons(5)
                        //  'condition'=>"t.user='".$this->buyer_fb_id."'  ",
                         'order'=>'t.entry_date desc',
                         'with'=>array(
                                        'coupon_deal',
                                        'buyer' ,
                      
                                      ),                            
          ), 
     
 
   ));
 
  
  
 }

 public function run()
 {
   $this->render('coupons');
 }
}

?>
