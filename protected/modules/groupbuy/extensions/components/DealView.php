<?php
/**
 UserProfile is a widget used to display a list of Categories
 */

class DealView extends CWidget
{

  public $deal_id;

 public $deal;


 public function init()
 {
 
  $criteria=new CDbCriteria;

  $criteria->select='description,discount_value,retail_price,discount_percentage,discount_value,end_date,tipping_point';

  $criteria->condition='id=:id';
                      
  $criteria->params=array(':id'=>$this->deal_id); 

   
  $this->deal=Deal::model()->find($criteria);
   
 }

 public function run()
 {
   $this->render('dealView');
 }
}

?>
