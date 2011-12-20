<?php
/**
 Buyers is a widget used to display buyers list of deal coupons
 */

class BuyerView extends CWidget
{

  public $deal_id;

 // public $couponDataProvider;
  
  public $buyer_fb_id;

  //public $deal;
  public $buyer;

  public $couponDataProvider;

 public function init()
 {

   //$this->deal=Deal::model()->findbyPk($this->deal_id); 
   $this->buyer=Buyer::model()->findbyPk($this->buyer_fb_id);   

 }

 public function run()
 {
    $this->render('buyerView');
   //echo $this->buyer->first_name;
 }
}

?>
