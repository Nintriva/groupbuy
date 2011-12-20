<?php
/**
 UserProfile is a widget used to display a list of Categories
 */

class PublishedDeals extends CWidget
{

 public $user_id=null;

 public $dealDataProvider;

 public $page_id=null;

 public function init()
 {
   if($this->user_id==null)
     {
       // $session=new CHttpSession;
        //$session->open(); 
        $this->user_id=VarifiedFbPages::model()->getAdvertiser($this->page_id);
              
        //$session->close(); 
     }  

 
   $this->dealDataProvider=new CActiveDataProvider('Deal',array(
       'pagination'=>array(
         'pageSize'=>1,
               
          ),
         'criteria'=>array(
                          'select'=>'id',
                         //'select'=>'description,discount_value,retail_price,discount_percentage,discount_value,end_date,tipping_point',  
                         'condition'=>"t.advertiser='".$this->user_id."' and t.published='1' ",
                         'order'=>'t.id desc',
                                  
          ), 
     
 
   ));

 
   
 }

 public function run()
 {
   $this->render('publishedDeals');
 }
}

?>
