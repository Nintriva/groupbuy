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

          $tz=$deal->getTimeZone();   
          date_default_timezone_set($tz[$deal->timezone]);


           if($deal->free_coupons==0)
             {
                //$this->redirect(array('/groupbuy/coupon/Error','msg'=>"No Free coupons available.")); 
                  $this->redirect(array('facebook/authenticate/'));

             }

              //$coupon=$deal->generateFreeCoupon($session['me']['id']);
 
              $transaction=$deal->store_free_transaction();
               
   
              $deal->store_buyer();  

              $deal->free_coupons--;
              $deal->save(false);         


   
            
           //checking whether the tipping point is reached or not.saving tipping time
            
            if($deal->isTipped())
              {
                // $deal->tipped_at=new CDbExpression('NOW()');
               if($deal->is_tipped==0)
                 {  
                  $deal->tipped_at=date('Y-m-d H:i:s');
                  $deal->is_deal_on=1;
                  $deal->is_tipped=1;
                  $deal->status=2; 
                  $deal->save(false);

                  //generate coupons for all transactions (PAid-it may be a case after some paid coupon if we are selling free coupons)  
                   $deal->generateCoupons();
                  
                  //SEND MAIL TO ALL COUPON BUYERS
                  foreach($deal->coupons as $c)
                    {
                                        //change coupon status
                                    if($c->status!=2&&$deal->isTipped())
                                       {
                                        $c->status=2;
                                        $c->save(); 
                                       }
                     
                      $m=$c->getMail("buyer","tipped"); 
                      mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                    }
                  //SEND MAIL TO DEAL OWNER
                   $m=$deal->getMail("owner","tipped");
                   mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                   $this->redirect(array('facebook/userCoupons'));      
                  }
               else
                 {
                   //generate  coupon(s) for a single transaction
                       
                   $coupon=$deal->generateCoupon($transaction);

                   $m=$transaction->getMail("buyer","bought");               
                   mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                   $m=$deal->getMail("owner","sold_status");  
                   mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                    //redirect to coupon list
                   $this->redirect(array('facebook/userCoupons'));
                 } 
 
 
              }         
                


                 $m=$transaction->getMail("buyer","bought");               
                 mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                  
                 $m=$deal->getMail("owner","sold_status");  
                 mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');  
                  
                 $buyer=Buyer::model()->findbyPk($session['me']['id']); 
                 $this->render('bought_summary',array('deal'=>$deal,'buyer'=>$buyer));

                 //$this->redirect(array('facebook/authenticate'));

        }       //END FREE COUPON ACTION
 
	
 
 
                     
      
           //$this->render('generateCoupon',array('deal'=>$deal,'coupon_id'=>$session['coupon_id']));   

        
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
