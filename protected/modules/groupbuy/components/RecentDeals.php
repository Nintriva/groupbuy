<?php
/**
 RecentDeals is a widget used to display a list of recent(expired+soldout) Deals
 */

class RecentDeals extends CWidget
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
        $this->user_id=VerifiedFbPages::model()->getAdvertiser($this->page_id);
              
        //$session->close(); 
     }  

 
   $this->dealDataProvider=new CActiveDataProvider('Deal',array(
       'pagination'=>array(
         'pageSize'=>1,
               
          ),
         'criteria'=>array(
                          'select'=>'id',
                         //'select'=>'description,discount_value,retail_price,discount_percentage,discount_value,end_date,tipping_point',  
                         'condition'=>"t.advertiser='".$this->user_id."' and t.published='1' and t.status='8' and t.status!='5' ",
                         //'order'=>"t.is_expired asc,t.id desc",
                          'order'=>"t.id desc",  
                                  
          ), 
     
 
   ));

 
   
 }

 public function run()
 {
   $this->render('recentDeals');
 }
}

?>
