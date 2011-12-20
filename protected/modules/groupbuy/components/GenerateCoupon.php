<?php
/**
 GenerateCoupon is a widget used to generate a coupon for a deal
 */

class GenerateCoupon extends CWidget
{


  public $deal;
 
  public $coupon_id;
  
  //public $session;   


 public function init()
 {
 
  
  
 }

 public function run()
 {
   $this->render('generateCoupon');
 }
}

?>
