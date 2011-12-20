<?php
/**
 Submit Order is a widget used to submit the order of a deal to paypal
 */

class SubmitOrder extends CWidget
{

  public $deal_id;

  public $deal;


 public function init()
 {
 
  $criteria=new CDbCriteria;

  $criteria->select='id,title,description,discount_value,retail_price,discount_percentage,discount_value,end_date,tipping_point,max_available,sold_no';

  $criteria->condition='id=:id';
                      
  $criteria->params=array(':id'=>$this->deal_id); 

   
  $this->deal=Deal::model()->find($criteria);
   
 }

 public function run()
 {
   $this->render('submitOrder');
 }
}

?>
