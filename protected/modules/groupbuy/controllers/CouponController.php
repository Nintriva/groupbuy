<?php

class CouponController extends Controller
{

     public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'couponGeneration + freeCoupon',
                        'redeem + redeem',
			
		);
	}
    public function filterRedeem($filterChain)
    {
       if(isset($_REQUEST['id']))
        {
          $coupon=Coupon::model()->findbyPk($_REQUEST['id']);
          if($coupon==null)
           {
            $this->render("error",array('msg'=>'Invalid Coupon'));
            return;
           } 
          if($coupon->status==1)
            {
              $this->render("error",array('msg'=>'The coupon is in pending.so you cannot redeem now.')); 
              return;  
            }
          if($coupon->status==3)
            {
              $this->render("error",array('msg'=>'The coupon is already redeemed')); 
              return;  
            }
          if($coupon->status==5)
            {
              $this->render("error",array('msg'=>'Sorry.The coupon is expired')); 
              return;  
            }
          $filterChain->run();
        
        }
        else
        {
           $this->render("error",array('msg'=>'Invalid Coupon')); 
        }

    }
        public function actionRedeem($id)
        {
          $coupon=Coupon::model()->findbyPk($id);
          $coupon->status=3;
          $coupon->save();
          $this->redirect(array("deal/seeBuyers","id"=>$coupon->coupon_deal->id));
        } 
        public function actionFreeCouponOrder($deal_id)
 	{
               $model=new FreeCouponOrder;
               $deal=Deal::model()->findbyPk($deal_id); 

               $session=new CHttpSession;
               $session->open(); 
               $session['coupon_deal_id']=$deal_id;//This session value is used for coupon generation after payment  
              
                 
               if(Buyer::model()->isUserExist($session['me']['id']))
                 {   
                    $buyer=Buyer::model()->findbyPk($session['me']['id']);  
                    $model->max_purchase_units=$deal->max_purchase_units-$buyer->getUserBoughtCount($deal->id);               
                 }  
               else
                 {
                    $model->max_purchase_units=$deal->max_purchase_units;
                 }

              
             
               
               //$model->user_coupon_count=$buyer->getUserCouponCount($deal->id);

 
               $model->coupon_balance=$deal->getBalance();

             //  $model->paymentType="Sale";    
               $model->L_NAME0=$deal->title;
         

               // uncomment the following code to enable ajax-based validation
                     
                    if(isset($_POST['ajax']) && $_POST['ajax']==='free-coupon-order-form')
                      {
                           echo CActiveForm::validate($model);
                              Yii::app()->end();
                       }
                       

                        if(isset($_POST['FreeCouponOrder']))
                        {
                         $model->attributes=$_POST['FreeCouponOrder'];

                          if($model->validate())
                            {
                               //echo "okkkkkkkkk";
                             // $this->redirect(array("coupon/freeCoupon",'deal_id'=>));
                             $session['free_order_details']=$model;
 
                             $this->actionFreeCoupon($deal_id);
                             return;  
 
                            }
                        }
             $this->render('free_order_form',array('model'=>$model,'deal'=>$deal)); 
 
          }   

        public function actionFreeCoupon($deal_id)
        {
           $session=new CHttpSession;                  
           $session->open();  

           if(isset($_GET['deal_id']))
           {
            $deal=Deal::model()->findbyPk($_GET['deal_id']);     
           }
           else
           {
             $deal=Deal::model()->findbyPk($deal_id);     
           }       
              
          $tz=$deal->getTimeZone();   
          date_default_timezone_set($tz[$deal->timezone]);

              //$coupon=$deal->generateFreeCoupon($session['me']['id']);
 
              $transaction=$deal->store_free_transaction();   
              $deal->store_buyer();  
              
             if(!$deal->isBuyerInDeal($session['me']['id']))
               {  
                 $deal->associateBuyerToDeal($session['me']['id']);
               } 

             // $deal->free_coupons--;
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

                  
                  // $deal->generateCoupons();
                  $deal->makeAllPreveousCouponActive();
                  $coupon=$deal->generateCoupon($transaction,2);
                 
                   $deal->assignConfirmationCodeToOnDealBuyer($session['me']['id']); 
                  //SEND MAIL TO ALL COUPON BUYERS
                 /*
                  foreach($deal->coupons as $c)
                    {
                                        //change coupon status
                                    if($c->status!=2&&$deal->isTipped())
                                       {
                                        $c->status=2;
                                        $c->save(); 
                                       }
                   
                    } */
                  // $deal->mailBuyers("coupon_generated");
                   $deal->mailBuyers("tipped");
                  //SEND MAIL TO DEAL OWNER
                   $m=$deal->getMail("owner","tipped");
                   mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                   $this->redirect(array('facebook/userCoupons'));      
                  }
               else
                 {
                   //generate  coupon(s) for a single transaction after tipping point
                       
                   $coupon=$deal->generateCoupon($transaction,2);
                   $deal->assignConfirmationCodeToOnDealBuyer($session['me']['id']);
                   
                   $m=$transaction->getMail("buyer","bought");               
                   mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                   $m=$deal->getMail("owner","sold_status");  
                   mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                    //redirect to coupon list
                   $this->redirect(array('facebook/userCoupons'));
                 } 
 
 
              }         
                

                 $coupon=$deal->generateCoupon($transaction,1);
                 $deal->assignCodeToOffDealBuyer($session['me']['id']);  
                 $m=$transaction->getMail("buyer","bought");               
                 mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                  
                 $m=$deal->getMail("owner","sold_status");  
                 mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');  
                  
                
                //  $this->redirect(array('facebook/userCoupons'));
                 $this->render('bought_summary',array('deal'=>$deal));

                 //$this->redirect(array('facebook/authenticate'));

        }       //END FREE COUPON ACTION
 
	
 public function filterCouponGeneration($filterChain)
     {
         $session=new CHttpSession;                  
         $session->open();
       /* 
         if(!isset($session['signedRequest']['user_id']))
         {
          $this->redirect(array("facebook/askPermission"));
          return;        
      
         }  */
             
       if(!isset($_REQUEST['deal_id']))
         {
           $this->render("error_summary",array('error_message'=>'Invalid Deal')); 
           return;
         }  
           
           $deal=Deal::model()->findbyPk($_REQUEST['deal_id']);
          $tz=$deal->getTimeZone();   
          date_default_timezone_set($tz[$deal->timezone]);               
 
            if($deal==null)
             {
                   $this->render("error_summary",array('error_message'=>'Invalid Deal')); 
                   return;
             }
          /*  else if($deal->free_coupons==0)
             {
               $this->render("error_summary",array('error_message'=>'Sorry.No Free coupons available'));

             } */

            else if(!isset($session['me']['id']))
             {
                  $session['coupon_deal_id']=$deal->id;
                /*
                echo "<pre>";  
                print_r($session['me']);
                echo "</pre>";
                return; 
                */
                   $this->redirect(array("facebook/askPermission"));

                   //$this->redirect(array("facebook/authenticate")); 
 
             }
            else if($deal->isSoldOut())
             {
                  if(!$deal->isExpired())
                    $this->render("error_summary",array('error_message'=>'Sorry the deal is SOLD OUT Before expiry.'));
                  else                   
                     $this->render("error_summary",array('error_message'=>'Sorry the deal is SOLD OUT.'));  
             }
            else if($deal->isExpired())
             {
                   if($deal->isTipped()) 
                     $this->render("error_summary",array('error_message'=>'Sorry the deal CLOSED')); 
                   else 
                     $this->render("error_summary",array('error_message'=>'Sorry the deal is expired'));   

             }
            else if($deal->status==0)
             {
               $this->render("error_summary",array('error_message'=>'The deal is not published')); 
             }
            else if($deal->status==5)
             {
               $this->render("error_summary",array('error_message'=>'Sorry the deal is Unpublished')); 
             } 
            else if($deal->getUserBoughtCount($session['me']['id'])>=$deal->max_purchase_units)
             {
               $this->render("error_summary",array('error_message'=>'Sorry.Your limit on this deal is '.$deal->max_purchase_units.'. You are already bought '.$deal->getUserBoughtCount($session['me']['id'])));   
             }
            else
             {
                $filterChain->run();
             }

     } 
 
                     
      
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
