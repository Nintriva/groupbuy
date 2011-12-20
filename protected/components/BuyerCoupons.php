<?php
/**
 BuyerCoupon is a widget used to list all the coupons of a buyer
 */

class BuyerCoupons extends CWidget
{


  public $buyer_fb_id;

  
  public $couponDataProvider;  


 public function init()
 {

 $this->couponDataProvider=new CActiveDataProvider('Coupon',array(
       'pagination'=>array(
         'pageSize'=>1,
               
          ),
       
         'criteria'=>array(
                         //'select'=>'id,title,published',  
                         'condition'=>"t.user='".$this->buyer_fb_id."' ",
                         'order'=>'t.entry_date desc',
                         'with'=>array(
                                        'buyer',
                                        'deal' 
                      
                                      ),                            
          ), 
     
 
   ));
 
  
  
 }

 public function run()
 {
   $this->render('buyerCoupons');
 }
}

?>
