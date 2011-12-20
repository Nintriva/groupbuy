<?php

class GroupbuyScheduleController extends Controller
{

 private $deals;

 public function actionStart()
 {
   $this->deals=Deal::model()->with(array('coupons_count'))->findAll();
   
 // echo "<table>";
  //echo "<th>Not Expired<th>Expired";
   date_default_timezone_set('Asia/Calcutta');

 //  while(true)
   {
    $this->actionCheckDeals();
   } 
 }

 public function actionCheckDeals()
 {
    
    date_default_timezone_set('Asia/Calcutta'); 
  
     foreach($this->deals as $deal)
    {
      // echo $deal->title."<br/>";
       
      // $deal=Deal::model()->findbyPk($d->id);   
       
       
            if(mktime()<strtotime($deal->end_date))
             {  //NOT EXPIRED CASE
                echo  "<br/>NOT EXPIRED:".$deal->title; 
               
                if($deal->coupons_count==$deal->max_available)
                  {  //ALL available coupons are soldout
                         /* ACTIONS NEED TO PERFORM HERE ARE, 
                                                 1.MAKE DEAL OFF
                                                2.PERFORM PAYMENTS
                                                 3.MAIL ABOUT PAYMENTS  
 
                                                */
                    $deal->is_deal_on=0;   
                    $deal->save();
                        
                  }
                else if($deal->coupons_count>=$deal->tipping_point)
                  { 
                      /* 1.MAKE DEAL ON
                         2.DO PAYMENTS.
                         3.MAIL ABOUT PAYMENTS
                      */
                    $deal->is_deal_on=1;
                    $deal->save();
                  } 
                else if($deal->coupons_count<$deal->tipping_point)
                 {
                   $deal->is_deal_on=0;
                   $deal->save();
                 }
                      
                    
             }
            else
             { //EXPIRED CASE
                echo "<br/>EXPIRED:".$deal->title;  
                 if($deal->coupons_count>=$deal->tipping_point)
                  { 
                      /* 1.MAKE DEAL OFF
                         2.DO PAYMENTS.
                         3.MAIL ABOUT PAYMENTS
                      */
                    $deal->is_deal_on=0;
                    $deal->save();
                  } 
                else if($deal->coupons_count<$deal->tipping_point)
                 {
                    /* 1.MAKE DEAL OFF
                         2.CANCEL ALL PAYMENTS.
                         3.MAIL ABOUT PAYMENTS
                      */ 
                    $deal->is_deal_on=0;
                    $deal->save(); 

                 }


 

             }    
           flush(); 
          

    } //end deal for loop
  
 } //end actionCheckDeals


}

?>
