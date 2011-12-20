<?php

class CouponController extends Controller
{
        public function actionFreeCoupon()
        {
           $session=new CHttpSession; 
                 
           $session->open();  

           if(isset($_GET['deal_id']))
               {
                 $deal=Deal::model()->findbyPk($_GET['deal_id']);     
                     

               } 
            else
               {
                $this->redirect(array('/groupbuy/coupon/Error','msg'=>"Invalid Deal.")); 
                 
               }
          $tz=$deal->getTimeZone();   
          date_default_timezone_set($tz[$deal->timezone]);


           if($deal->free_coupons==0)
             {
                $this->redirect(array('/groupbuy/coupon/Error','msg'=>"No Free coupons available.")); 

             }
                $coupon=new Coupon;
                $coupon->id=rand();
                $session['coupon_id']=$coupon->id;
              if(isset($session['me']['id']))
                { 
                  $coupon->user=$session['me']['id'];
                }
             else
               {
                  $this->redirect(array('/groupbuy/coupon/Error','msg'=>"Invalid Facebook user"));  
                 // $coupon->user=Yii::app()->user->id;
               } 
                $session['coupon_deal_id']=$deal->id;

                $coupon->status=1; //1->PURCHASED ONHOLD,2.PURCHASED MONEY COLLECTED,3.REDEEMED 
                $coupon->deal=$deal->id;
                $coupon->entry_date=date('Y-m-d H:i:s');


               //$coupon->deleted_date=new CDbExpression('NOW()'); //hAVE TO FIX THIS(WHAT IS DELETED DATE????)
  
                $coupon->save();
                 
              //STORE BUYER DETAILS
             $criteria=new CDbCriteria;
             $criteria->select='fb_id';

             $criteria->condition='fb_id=:fb_id';
             $criteria->params=array(':fb_id'=>$session['me']['id']); 	  
 

         
            if(Buyer::model()->count($criteria)==0)
             {
  
                 $this->actionStoreBuyer();
             }                  
                 
           
           //checking whether the tipping point is reached or not.saving tipping time
            
            if($deal->coupons_count==$deal->tipping_point)
              {
                // $deal->tipped_at=new CDbExpression('NOW()');
                 $deal->tipped_at=date('Y-m-d H:i:s');
                 $deal->is_deal_on=1;
                 $deal->save();
                  
                  //SEND MAIL TO ALL COUPON BUYERS
                  foreach($deal->coupons as $c)
                    {
                     
                      $m=$c->getMail("buyer","tipped"); 
                      mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                    }
                  //SEND MAIL TO DEAL OWNER
                   $m=$deal->getMail("owner","tipped");
                   mail($deal->paypal_address,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
 
 
              }
            else if($deal->coupons_count==$deal->max_available)
              {
               $deal->is_deal_on=0;
               $deal->save(); 

                  foreach($deal->coupons as $c)
                    {
                     
                      $m=$c->getMail("buyer","sold_out_before_exp"); 
                      mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                    }
                  //SEND MAIL TO DEAL OWNER
                   $m=$deal->getMail("owner","sold_out_before_exp");
                   mail($deal->paypal_address,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                

 
              } 
 
                
                $deal->free_coupons--;
                $deal->save();        

                 $m=$coupon->getMail("buyer","bought");

               
 
                 mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                  
                 $m=$deal->getMail("owner","sold_status");  
                 mail($deal->paypal_address,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');  
                  

                 $this->redirect(array('facebook/userCoupons')); 

        }       //END FREE COUPON ACTION
 
	public function actionGenerateCoupon()
	{
          //echo  "deal id:".$_GET['deal_id'];
            
           
           $session=new CHttpSession; 
                 
           $session->open();

        
           $deal=Deal::model()->findbyPk($session['coupon_deal_id']); 
           $tz=$deal->getTimeZone();   
           date_default_timezone_set($tz[$deal->timezone]);
       
        
 
                     
 
           
 
           $payment_result=$session['reshash'];

          /* echo "<pre>";
           print_r($payment_result); 
           echo "</pre>";
            return;*/ 
 
           $ack=strtoupper($payment_result['ACK']);
           if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')   //CHECK WHETHER THE PAYMENT IS SUCCESS OR NOT
             {

                 $this->redirect(array('/groupbuy/coupon/Error','msg'=>"Payment not success"));  
                 
                 
             } 
       
           if(!isset($session['coupon_deal_id']))
            {            
              $this->redirect(array('/groupbuy/coupon/Error','msg'=>"Invalid deal")); 
            }
          
            
        
            

          for($i=0;$i<$session['qty0'];$i++)
             {   
               echo $i."<br/>";
        
                 $coupon[$i]=new Coupon;        
 
                $coupon[$i]->id=rand(); 

                $session['coupon_id']=$coupon[$i]->id;

                  if(isset($session['me']['id']))
                  { 
                   $coupon[$i]->user=$session['me']['id'];
                  }
                else
                 {
                   $this->redirect(array('/groupbuy/coupon/Error','msg'=>"Invalid Facebook user"));  
                     // $coupon->user=Yii::app()->user->id;
                 } 

               $coupon[$i]->status=1; //1->PURCHASED ONHOLD,2.PURCHASED MONEY COLLECTED,3.REDEEMED 
               $coupon[$i]->deal=$deal->id;
               $coupon[$i]->entry_date=date('Y-m-d H:i:s');


           //$coupon->deleted_date=new CDbExpression('NOW()'); //hAVE TO FIX THIS(WHAT IS DELETED DATE????)
  
               $coupon[$i]->save();
             }
               return;
           
             //checking whether the tipping point is reached or not.saving tipping time
            
            if($deal->coupons_count==$deal->tipping_point)
              {
                // $deal->tipped_at=new CDbExpression('NOW()');
                 $deal->tipped_at=date('Y-m-d H:i:s');
                 $deal->is_deal_on=1;
                 $deal->save();
                  
                  //SEND MAIL TO ALL COUPON BUYERS
                  foreach($deal->coupons as $c)
                    {
                     
                      $m=$c->getMail("buyer","tipped"); 
                      mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                    }
                  //SEND MAIL TO DEAL OWNER
                   $m=$deal->getMail("owner","tipped");
                   mail($deal->paypal_address,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
 
 
              }
            else if($deal->coupons_count==$deal->max_available)
              {
               $deal->is_deal_on=0;
               $deal->save(); 

                  foreach($deal->coupons as $c)
                    {
                     
                      $m=$c->getMail("buyer","sold_out_before_exp"); 
                      mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                    }
                  //SEND MAIL TO DEAL OWNER
                   $m=$deal->getMail("owner","sold_out_before_exp");
                   mail($deal->paypal_address,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                

 
              } 
  
             
 
         
        //  $this->redirect(array('coupon/storeBuyer'));
             $criteria=new CDbCriteria;
             $criteria->select='fb_id';

             $criteria->condition='fb_id=:fb_id';
             $criteria->params=array(':fb_id'=>$session['me']['id']); 	  
 
            //echo "count:".Buyer::model()->count($criteria); 
            
          if(Buyer::model()->count($criteria)==0)
             {
  
                 $this->actionStoreBuyer();
             }  
                      
                 $this->actionStoreTransaction();


                 $m=$coupon->getMail("buyer","bought");
                 mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                 $m=$deal->getMail("owner","sold_status");  
                 mail($deal->paypal_address,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');  

                 $this->redirect(array('facebook/userCoupons'));  
            
            
          
	}

        public function actionStoreBuyer()
        {
                  
         
           $buyer=new Buyer;  
           $session=new CHttpSession;        
           $session->open();
           $me=$session['me'];

           $buyer->fb_id=$me['id'];
           $buyer->first_name=$me['first_name'];
           $buyer->last_name=$me['last_name'];

           if(isset($me['location']['name']))
             $buyer->location=$me['location']['name'];
           else
             $buyer->location='Not specified';

           $buyer->email=$me['email'];
           $buyer->gender=$me['gender'];

            if(isset($me['timezone']))
             $buyer->timezone=$me['timezone']; 
            else
             $buyer->timezone=0.0;   

             if(isset($me['locale']))  
               $buyer->locale=$me['locale'];
             else
               $buyer->locale='N/A';

             if(isset($session['signedRequest']['user']['country']))
               {
                $buyer->country=$session['signedRequest']['user']['country']; 
               }
              else
               {
                $buyer->country='Not specified';
               }  

           $buyer->create_time=date('Y-m-d H:i:s');
           $buyer->updated_time=date('Y-m-d H:i:s');
 
           $buyer->save();
             
               
          
            
        }
       public function actionStoreTransaction()
        {
         $transaction=new Transaction;
         $session=new CHttpSession;        
         $session->open();     
         $payment_result=$session['reshash'];  
          
         $transaction->transaction_id=$payment_result['TRANSACTIONID'];
         $transaction->amount=$payment_result['AMT'];
         $transaction->currency_code=$payment_result['CURRENCYCODE'];
         $transaction->ordertime=$payment_result['ORDERTIME'];
         $transaction->timestamp=$payment_result['TIMESTAMP'];

         $transaction->buyer_paypal_id=$session['PayerID'];        
         $transaction->buyer_fb_id=$session['me']['id']; 
         $transaction->coupon_id=$session['coupon_id'];
            
         $transaction->save();

         
          //$deal=Deal::model()->findbyPk($session['coupon_deal_id']);  
             
         unset($session['token']);
         unset($session['PayerID']);
         unset($session['coupon_deal_id']);
 
        
 
 
                     
      
           //$this->render('generateCoupon',array('deal'=>$deal,'coupon_id'=>$session['coupon_id']));   

        }
	public function actionPrintCoupon()
	{
		$this->render('printCoupon');
	}
        public function actionError()
        {
           
          echo "Coupon Error occured";
          if(isset($_GET['msg']))
            {

             echo "<br>".$_GET['msg'];
            }
        }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
