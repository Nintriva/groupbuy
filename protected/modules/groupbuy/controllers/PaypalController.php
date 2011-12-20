<?php
class PaypalController extends Controller
{
 
 public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'paypalCheckout + SubmitOrder,DoPayment,ReviewOrder',
			
		);
	}

public function filterPaypalCheckout($filterChain)
     {
         $session=new CHttpSession;                  
         $session->open(); 
         
        if(isset($_REQUEST['deal_id']))
         {
           $deal=Deal::model()->findbyPk($_REQUEST['deal_id']);
         }
       else if(isset($session['coupon_deal_id']))
         {
           $deal=Deal::model()->findbyPk($session['coupon_deal_id']);    

         }   
       else 
         {
            $this->render("error_summary",array('error_message'=>'Invalid Deal')); 
             return;   

         }
           
           
            if($deal==null)
             {
                   $this->render("error_summary",array('error_message'=>'Invalid Deal')); 
                   return; 
             }
            
          
          /*
           if(!isset($session['signedRequest']['user_id']))
            {
               echo $session['signedRequest']['user_id'];
               return;    
              //  echo "ok wait";
               // return; 
                  $this->redirect(array("facebook/askPermission"));
                  return;        
      
            }
           */
        
 
          $tz=$deal->getTimeZone();   
          date_default_timezone_set($tz[$deal->timezone]);               
 
          

            if(!isset($session['me']['id']))
             {
                 $session['coupon_deal_id']=$deal->id;
                /*
                echo "<pre>";  
                print_r($session['me']);
                echo "</pre>";
                return; 
                */
                   $this->redirect(array("facebook/askPermission"));
 
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
            else if(isset($session['reshash1'])||(isset($session['reshash2']))||(isset($session['reshash3'])))
             {
                $resArray=$session['reshash1']; 
                $ack = strtoupper($resArray["ACK"]);
                
		   if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')
                     {
                       unset($session['reshash1']); 
 
                       $this->render("error_summary",array('error_message'=>$resArray['L_SHORTMESSAGE0'].":<br/>".$resArray['L_LONGMESSAGE0'])); 
                       return;

                     }
                  
                   if(isset($session['reshash2']))
                   {
                     $resArray=$session['reshash2']; 
                                                 
                     $ack = strtoupper($resArray["ACK"]);
                
		       if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')
                        {
                           unset($session['reshash2']);
                              unset($session['reshash1']);                        
 
                           $this->render("error_summary",array('error_message'=>$resArray['L_SHORTMESSAGE0'].":<br/>".$resArray['L_LONGMESSAGE0'])); 
                           return; 

                        }
                    }
                  if(isset($session['reshash3']))
                  {
                    $resArray=$session['reshash3']; 
                     
                    $ack = strtoupper($resArray["ACK"]);
                
		       if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')
                       {
                          unset($session['reshash3']); 
                          unset($session['reshash1']);
                           unset($session['reshash2']);   
                          $this->render("error_summary",array('error_message'=>$resArray['L_SHORTMESSAGE0'].":<br/>".$resArray['L_LONGMESSAGE0']));
                          return;  

                        }
                     
                   } 
              
                $filterChain->run(); 

             }    
            else
             {
                $filterChain->run();
             }

     } 


	public function actionSubmitOrder($deal_id)
 	{
               $model=new PaypalOrder;
               $deal=Deal::model()->findbyPk($deal_id); 

               $session=new CHttpSession;
               $session->open(); 
               // echo "<pre>";
               //print_r($session['me']);
                //echo "</pre>"; 
                //return;
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

               $model->paymentType="Sale";    
               $model->L_NAME0=$deal->title;
               $model->L_AMT0=$deal->deal_price;  
               $model->currencyCodeType=$deal->currency_code;
               // uncomment the following code to enable ajax-based validation
                     
                    if(isset($_POST['ajax']) && $_POST['ajax']==='paypal-order-orderform-form')
                      {
                           echo CActiveForm::validate($model);
                              Yii::app()->end();
                       }
                       

                        if(isset($_POST['PaypalOrder']))
                        {
                         $model->attributes=$_POST['PaypalOrder'];

                        

                          if($model->validate())
                            {

          
                                /* The returnURL is the location where buyers return when a
			payment has been succesfully authorized.
			The cancelURL is the location buyers are sent to when they hit the
			cancel button during authorization of payment during the PayPal flow
			*/
                               

		               // $url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);  
                                 
                                  //These are the URL's used to return to facebook app page
                           //     $session->open();
  
                              //storing the item to session for coupon generation.
                                $session['qty0']=$model->L_QTY0;
                                $session['amt0']=$model->L_AMT0; 
                                $session['name0']=$model->L_NAME0;
                                $session['number0']=$deal->id;
                               //echo $session['qty0'];
                                //return;                                
 

                                 if(isset($session['page_link']))
                                  {    
                                    $url=$session['page_link']."?sk=app_".Yii::app()->controller->module->app_id;
                                    $session['fb_returnURL'] =$url;
		                    $session['fb_cancelURL'] =$url;
                                    // $session['fb_returnURL'] =urlencode($url);
		                    //$session['fb_cancelURL'] =urlencode($url); 
                                  }
                                 
                               
                                      $serverName = $_SERVER['SERVER_NAME'];
		                      $serverPort = $_SERVER['SERVER_PORT'];
                                    //$url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']); 
                                         $returnURL=urlencode('http://'.$serverName.':'.$serverPort.Yii::app()->createUrl("paypal/paypalReturn")); 
                                      //  $returnURL=urlencode(dirname('http://'.$serverName.':'.$serverPort.Yii::app()->createUrl("paypal/paypalReturn"))); 
                                        $cancelURL=urlencode('http://'.$serverName.':'.$serverPort.Yii::app()->createUrl("paypal/cancelPayment")); 
                                   //  $url=dirname('http://'.$serverName.':'.$serverPort); 
        
                           //          echo "url:".$url;

                                     //$returnURL =urlencode($url); 
                                     //$cancelURL =urlencode($url.Yii::app()->baseUrl."/index.php/groupbuy/paypal/cancelPayment");                        
                                   //  echo  "<br/>returnUrl:".urldecode($returnURL);
                                     //echo "<br/>base:".Yii::app()->baseUrl;
                                   //  echo "<br>test:".Yii::app()->createUrl("paypal/paypalReturn"); 
                                    // return;                                
 
                             
                                /* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
                                $itemamt = 0.00;
                                $itemamt = $model->L_QTY0*$model->L_AMT0;
                              
                                //$amt=$itemamt;  
                                 $amt = 5.00+2.00+1.00+$itemamt;
                                $maxamt= $amt+25.00;
                                //$maxamt=$amt;
     
                                $nvpstr="";
		   
                                /*
                             * Setting up the Shipping address details
                                */
                              //$shiptoAddress=""; /* now shipping address is empty.we can use it when we need it*/                         
                                $shiptoAddress = "&SHIPTONAME=".$model->PERSONNAME."&SHIPTOSTREET=".$model->SHIPTOSTREET."&SHIPTOCITY=".$model->SHIPTOCITY."&SHIPTOSTATE=".$model->SHIPTOSTATE."&SHIPTOCOUNTRYCODE=".$model->SHIPTOCOUNTRYCODE."&SHIPTOZIP=".$model->SHIPTOZIP;  
     
$nvpstr = "&ADDRESSOVERRIDE=1".$shiptoAddress."&L_NAME0=".$model->L_NAME0."&L_AMT0=".$model->L_AMT0."&L_QTY0=".$model->L_QTY0."&MAXAMT=".(string)$maxamt."&ITEMAMT=".(string)$itemamt."&AMT=".$itemamt."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL."&CURRENCYCODE=".$model->currencyCodeType; 

/*
     $nvpstr="&useraction=commit&ADDRESSOVERRIDE=1".$shiptoAddress."&L_NAME0=".$model->L_NAME0."
&L_AMT0=".$model->L_AMT0."&L_QTY0=".$model->L_QTY0."&MAXAMT=".(string)$maxamt."&AMT=".(string)$amt."&ITEMAMT=".(string)$itemamt."&NOSHIPPING=1&CALLBACKTIMEOUT=4&L_SHIPPINGOPTIONAMOUNT1=8.00&L_SHIPPINGOPTIONlABEL1=UPS Next Day Air&L_SHIPPINGOPTIONNAME1=UPS Air&L_SHIPPINGOPTIONISDEFAULT1=true&L_SHIPPINGOPTIONAMOUNT0=3.00&L_SHIPPINGOPTIONLABEL0=UPS Ground 7 Days&L_SHIPPINGOPTIONNAME0=Ground&L_SHIPPINGOPTIONISDEFAULT0=false&INSURANCEAMT=1.00&INSURANCEOPTIONOFFERED=true&CALLBACK=https://www.ppcallback.com/callback.pl&SHIPPINGAMT=8.00&SHIPDISCAMT=-3.00&TAXAMT=2.00&L_NUMBER0=".$deal->id."&L_DESC0=".$deal->description."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$model->currencyCodeType."&PAYMENTACTION=".$model->paymentType;  
*/                        
                                
/* 

                                $nvpstr="&ADDRESSOVERRIDE=1".$shiptoAddress."&L_NAME0=".$model->L_NAME0."
&L_AMT0=".$model->L_AMT0."&L_QTY0=".$model->L_QTY0."&MAXAMT=".(string)$maxamt."&AMT=".(string)$amt."&ITEMAMT=".(string)$itemamt."&CALLBACKTIMEOUT=4&L_SHIPPINGOPTIONAMOUNT1=8.00&L_SHIPPINGOPTIONlABEL1=UPS Next Day Air&L_SHIPPINGOPTIONNAME1=UPS Air&L_SHIPPINGOPTIONISDEFAULT1=true&L_SHIPPINGOPTIONAMOUNT0=3.00&L_SHIPPINGOPTIONLABEL0=UPS Ground 7 Days&L_SHIPPINGOPTIONNAME0=Ground&L_SHIPPINGOPTIONISDEFAULT0=false&INSURANCEAMT=1.00&INSURANCEOPTIONOFFERED=true&CALLBACK=https://www.ppcallback.com/callback.pl&SHIPPINGAMT=8.00&SHIPDISCAMT=-3.00&TAXAMT=2.00&L_NUMBER0=1000&L_DESC0=Size: 8.8-oz&L_NUMBER1=10001&L_DESC1=Size: Two 24-piece boxes&L_ITEMWEIGHTVALUE1=0.5&L_ITEMWEIGHTUNIT1=lbs&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$model->currencyCodeType."&PAYMENTACTION=".$model->paymentType; 
  */                                              
                             //echo  $nvpstr;   
                             $nvpHeader=$deal->nvpHeader();     

                             $nvpstr = $nvpHeader.$nvpstr;          
                            // echo $nvpstr;
                              /* Make the call to PayPal to set the Express Checkout token
			If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors
			*/ 
                             $resArray=$deal->hash_call("SetExpressCheckout",$nvpstr);      
                             $session->open();
                             $session['reshash1']=$resArray; 
                              $ack = strtoupper($resArray["ACK"]);
		              if($ack=="SUCCESS")
                                   {
					// Redirect to paypal.com here    
                                        $token = urldecode($resArray["TOKEN"]);
                                       // $token=urlencode($token); 
					$payPalURL = Yii::app()->controller->module->PAYPAL_URL.$token;
                                         
                                         echo "
                                            <script type='text/javascript'>
                                            window.parent.location='".$payPalURL."';
                                            </script>
                                           ";
 
                                        // $token = urldecode($resArray["TOKEN"]);
					//$payPalURL = PAYPAL_URL.$token;
					//header("Location: ".$payPalURL);
					// $payPalURL=preg_replace('/[ ]/','',$payPalURL);
                                         //echo "url:".$payPalURL."gggggggggg";
 
                                       // $this->redirect($payPalURL);
                                        return;                                        
				  } 
                                else  {
                                          unset($session['reshash1']);  
					 
                                          $this->render("error_summary",array('error_message'=>$resArray['L_SHORTMESSAGE0'].":<br/>".$resArray['L_LONGMESSAGE0'])); 
                                           return;

                                        //  $this->redirect(array('paypal/APIError','msg'=>"Error on 1'st hash call"));   
					
				      } 
                                 
                            
                              
                            } //end validate
                          }
                          $this->render('submitOrder',array('model'=>$model,'deal'=>$deal)); 
          
           
		//$this->render('submitOrder',array('deal'=>$deal));
	}
       //This action is used as a paypal return url
       public function actionPaypalReturn()
       {
         
          if(isset($_REQUEST['PayerID'])&&isset($_REQUEST['token']))
           {
             //echo  "PayerID:".$_REQUEST['PayerID'];
             $session=new CHttpSession;
             $session->open();
             $session['token']=$_REQUEST['token'];
             $session['PayerID']=$_REQUEST['PayerID'];
                 
              
            
           }
            
                if(isset($session['fb_returnURL']))
                  {     
                     // echo $session['fb_returnURL'];
                      //return;   
                      $this->redirect($session['fb_returnURL']);
                  }
                else
                  {
                   $this->redirect(array('paypal/reviewOrder'));
                  
                  } 
           // Yii::app()->request->redirect('http://www.google.com');
  
          
 
       }
 
       public function actionReviewOrder()
        {
 
             $session=new CHttpSession;
             $session->open();
               
            $deal=Deal::model()->findbyPk($session['coupon_deal_id']);  
             
          if(isset($session['PayerID'])&&isset($session['token']))
           {   
             $token=urlencode($session['token']); 
           }  
              
           
           /* At this point, the buyer has completed in authorizing payment
			at PayPal.  The script will now call PayPal with the details
			of the authorization, incuding any shipping information of the
			buyer.  Remember, the authorization is not a completed transaction
			at this state - the buyer still needs an additional step to finalize
			the transaction
			*/

		  // $token =urlencode( $_REQUEST['token']);
              /* Build a second API request to PayPal, using the token as the
			ID to get the details on the payment authorization
			*/
		   $nvpstr="&TOKEN=".$token;
                   $nvpHeader=$deal->nvpHeader();    
 
		   $nvpstr = $nvpHeader.$nvpstr;
                   /* Make the API call and store the results in an array.  If the
			call was a success, show the authorization details, and provide
			an action to complete the payment.  If failed, show the error
			*/
                           
		   $resArray=$deal->hash_call("GetExpressCheckoutDetails",$nvpstr);

                    $session->open();   //need to open a new session after hash call                     

		    $session['reshash2']=$resArray; 
                     
       
		   $ack = strtoupper($resArray["ACK"]);
                        
                
		   if($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING')
                    { 
                           $this->render("confirm_payment");      
                        //echo CHtml::link("confirm",array('paypal/doPayment'));			  
 
                    } 
                   else 
                     {
                         unset($session['reshash2']);  
                        $this->render("error_summary",array('error_message'=>$resArray['L_SHORTMESSAGE0'].":<br/>".$resArray['L_LONGMESSAGE0'])); 
			//$this->redirect(array('/paypal/APIError','msg'=>"Error on loading confirmation button"));   
	             }
               
               

        }
       public function actionDoPayment()
        {
          

          $session=new CHttpSession;
          $session->open();
          $deal=Deal::model()->findbyPk($session['coupon_deal_id']);
  
          $tz=$deal->getTimeZone();
          date_default_timezone_set($tz[$deal->timezone]); 


          $resArray=$session['reshash2'];  //result of 2'nd call

  /*
    [FIRSTNAME] => Test
    [LASTNAME] => User
    [COUNTRYCODE] => US
    [SHIPTONAME] => Test User
    [SHIPTOSTREET] => 1 Main St
    [SHIPTOCITY] => San Jose
    [SHIPTOSTATE] => CA
    [SHIPTOZIP] => 95131
    [SHIPTOCOUNTRYCODE] => US
    [SHIPTOCOUNTRYNAME] => United States
    */        
          


              
          
        
      
          $nvpstr='&TOKEN='.urlencode($resArray['TOKEN']).'&PAYERID='.urlencode($resArray['PAYERID']).'&PAYMENTACTION=Sale&AMT='.urlencode($resArray['AMT']).'&CURRENCYCODE='. urlencode($resArray['CURRENCYCODE']).'&IPADDRESS='.urlencode($_SERVER['SERVER_NAME']);
       
           
          $resArray=$deal->hash_call("DoExpressCheckoutPayment",$nvpstr);
           
          $session->open();          
          $session['reshash3']=$resArray;
 
          
           
           $ack=strtoupper($resArray['ACK']);
           if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')   //CHECK WHETHER THE PAYMENT IS SUCCESS OR NOT
             {
                unset($session['token']);
                unset($session['PayerID']);
                unset($session['coupon_deal_id']);
                
                unset($session['reshash1']);  
                unset($session['reshash2']); 
                unset($session['reshash3']);   

               $this->render("error_summary",array('error_message'=>$resArray['L_SHORTMESSAGE0'].":<br/>".$resArray['L_LONGMESSAGE0'])); 

                // $this->redirect(array('facebook/authenticate/'));  
                //echo "ERRRR";  
                 
             }
           else
             {
             
              
                 //TRANSACTION SUCCESS

                 //store transaction
                $transaction=$deal->store_transaction(); 
                 
                
                 //store buyer
                $deal->store_buyer();

                if(!$deal->isBuyerInDeal($session['me']['id']))
                  {  
                    $deal->associateBuyerToDeal($session['me']['id']);
                  } 
                
                //unset payment related session variables
                unset($session['token']);
                unset($session['PayerID']);
                unset($session['coupon_deal_id']); 
                
                unset($session['reshash1']);  
                unset($session['reshash2']); 
                unset($session['reshash3']); 
                 


               //check it wether the deal is reaching tipping point or not 
                if($deal->isTipped()&&$deal->is_tipped==0)
                   {    
                      $deal->is_tipped=1;
                      $deal->is_deal_on=1;
                      $deal->status=2; 
                      $deal->tipped_at=date('Y-m-d H:i:s');
   
                      $deal->save(false);   
                      //generate coupons for all transactions  
                      //$deal->generateCoupons();
                      $deal->makeAllPreveousCouponActive();
                      $coupon=$deal->generateCoupon($transaction,2);

                    //  $deal->assignConfirmationCodeToBuyers();
                      $deal->assignConfirmationCodeToOnDealBuyer($session['me']['id']); 
                      //$deal->mailBuyers("coupon_generated"); 


                              //SEND MAIL TO ALL COUPON BUYERS
                     /* foreach($deal->coupons as $c)
                        {
                                      //changing coupon status to 2 ie purchased money collected.
                                     if($c->status!=2&&$deal->isTipped())
                                       {
                                        $c->status=2;
                                        $c->save(false); 
                                       }
                     
                          
                         } */
                       $deal->mailBuyers("tipped");
                     //SEND MAIL TO DEAL OWNER
                      $m=$deal->getMail("owner","tipped");
                      mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');


                       //redirect to coupon list.

                      $this->redirect(array('facebook/userCoupons'));  
                   }

                 //Check wether Already tipped or not
                else if($deal->getBoughtCount()>$deal->tipping_point)
                 {
                   //generate  coupon(s) for a single transaction
                       
                   $deal->generateCoupon($transaction,2);
                   //$deal->assignConfirmationCodeToBuyer($session['me']['id']); 
                   $deal->assignConfirmationCodeToOnDealBuyer($session['me']['id']);
                   $m=$transaction->getMail("buyer","bought");
                  mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                  $m=$deal->getMail("owner","sold_status");  
                  mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                    //redirect to coupon list
                   $this->redirect(array('facebook/userCoupons'));
                 }
                  //Not tipped case
           
                 $coupon=$deal->generateCoupon($transaction,1);

                 $deal->assignCodeToOffDealBuyer($session['me']['id']);
                   
                   


                 $m=$transaction->getMail("buyer","bought");
                /*
                 echo "<pre>"; 
                 print_r($me);
                 echo "</pre>";               
                 echo "me".$session['me']['email'];
                 return; */

                 mail($session['me']['email'],$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                 $m=$deal->getMail("owner","sold_status");  

                 mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');  
       
                 
                 $this->render('bought_summary',array('deal'=>$deal));
               //  $this->redirect(array('facebook/userCoupons'));

                 
             } 
        
          
          
 
  
  } //end do payament
       public function actionCancelPayment()
        {
            $session=new CHttpSession;
            $session->open();
 
             //$this->render("confirm_payment");
           //echo "Payment Cancelled";
             
             if(isset($session['token']))
                unset($session['token']);
            
             if(isset($session['PayerID']))
                unset($session['PayerID']);
                
             if(isset($session['coupon_deal_id']))  
               unset($session['coupon_deal_id']);

              if(isset($session['reshash1'])) 
                unset($session['reshash1']);  
              if(isset($session['reshash2'])) 
                unset($session['reshash2']);  
              if(isset($session['reshash3'])) 
                unset($session['reshash3']);  
   
          //    echo "Payment Cancelled";
              
            $this->redirect(array('facebook/authenticate'));   
           
             //echo  CHtml::link("err",array('/groupbuy/deal/error'));
          // $this->redirect(CController::createUrl('/groupbuy/deal/error'));
          // echo "<br/>url:".CController::createUrl('/paypal/doPayment');
 
          
        }   
       public function actionAPIError()
        {

            if(isset($_GET['msg']))
              {
                echo "ERROR::".$_GET['msg'];

              }   
           /*$session=new CHttpSession;
           $session->open(); 
           echo "API Error occured";
           echo "<br/>".$session['curl_error_no'].":".$session['curl_error_msg'];
           $session->close();   
             */           

 
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
