<?php
/**
 UserProfile is a widget used to display a list of Categories
 */

class UserGroupDeals extends CWidget
{

  public $user_id;

 public $dealDataProvider;


 public function init()
 {
 

     //$this->user_id=Yii:app()->user->id;

//  $this->category=Categories::model()->findbyPk($this->cat_id); 
     
 $this->dealDataProvider=new CActiveDataProvider('Deal',array(
       'pagination'=>array(
         'pageSize'=>5,
               
          ),
         'criteria'=>array(
                         'select'=>'id,title,published',  
                         'condition'=>"t.advertiser='".$this->user_id."' ",
                         'order'=>'t.id desc',
                                  
          ), 
     
 
   ));    
/*
  $this->reviewDataProvider=new CArrayDataProvider($this->user->reviews,array(
       'pagination'=>array(
         'pageSize'=>5,   
          ),
      'totalItemCount' =>$this->user->review_count,      
 
   ));
 $this->businessDataProvider=new CArrayDataProvider($this->user->business,array(
       'pagination'=>array(
         'pageSize'=>3,   
          ),
      'totalItemCount' =>$this->user->business_count,      
 
   ));
  */
 }

 public function run()
 {
   $this->render('userGroupDeals');
 }
}

?>
