<?php
/**
 Buyers is a widget used to display buyers list of deal coupons
 */

class Buyers extends CWidget
{

  public $deal_id;

 // public $couponDataProvider;
  
  public $buyerDataProvider;

  public $deal;
 
  public $couponDataProvider;

 public function init()
 {

   $this->deal=Deal::model()->findbyPk($this->deal_id); 

  $this->couponDataProvider=new CArrayDataProvider($this->deal->coupons,array(
       'keyField' => 'id',     
      /* 'pagination'=>array(
         'pageSize'=>10,   
          ),*/ 
     // 'totalItemCount' =>$this->user->review_count,      
 
   ));

 /*
   $this->buyerDataProvider=new CArrayDataProvider($deal->deal_buyers,array(
       'keyField' => 'fb_id',     
       'pagination'=>array(
         'pageSize'=>1,   
          ),
     // 'totalItemCount' =>$this->user->review_count,      
 
   ));
*/
 

 }

 public function run()
 {
   $this->render('buyers');
 }
}

?>
