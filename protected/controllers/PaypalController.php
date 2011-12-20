<?php

class PaypalController extends Controller
{

 
 
	public function actionSubmitOrder($deal_id)
 	{
               $session=new CHttpSession;
               $session->open(); 
               $session['coupon_deal_id']=$deal_id;//This session value is used for coupon generation after payment  
	       $session->close(); 
 
               $deal=Deal::model()->findbyPk($deal_id); 

               

               $model=new PaypalOrder;

               $model->paymentType="Sale";    
               $model->L_NAME0=$deal->title;
               $model->L_AMT0=$deal->deal_price;  

               // uncomment the following code to enable ajax-based validation
                     /*
                    if(isset($_POST['ajax']) && $_POST['ajax']==='paypal-order-orderform-form')
                      {
                           echo CActiveForm::validate($model);
                              Yii::app()->end();
                       }
                       */

                        if(isset($_POST['PaypalOrder']))
                        {
                         $model->attributes=$_POST['PaypalOrder'];

                         $model->max_purchase_units=$deal->max_purchase_units; 

                          if($model->validate())
                            {

          
                                /* The returnURL is the location where buyers return when a
			payment has been succesfully authorized.
			The cancelURL is the location buyers are sent to when they hit the
			cancel button during authorization of payment during the PayPal flow
			*/
                               

		               // $url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);  
                                 
                                  //These are the URL's used to return to facebook app page
                                $session->open();
  
                              //storing the item to session for coupon generation.
                                $session['qty0']=$model->L_QTY0;
                                $session['amt0']=$model->L_AMT0; 
                                $session['name0']=$model->L_NAME0;
                                $session['number0']=$model->id;
                                 

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
                                     $url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']); 
 
                                     $returnURL =urlencode($url.'/paypalReturn'); 
                                     $cancelURL =urlencode($url.'/cancelPayment');                        
                               
                             
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
                              $shiptoAddress=""; /* now shipping address is empty.we can use it when we need it*/                         
                                // $shiptoAddress = "&SHIPTONAME=".$model->PERSONNAME."&SHIPTOSTREET=".$model->SHIPTOSTREET."&SHIPTOCITY=".$model->SHIPTOCITY."&SHIPTOSTATE=".$model->SHIPTOSTATE."&SHIPTOCOUNTRYCODE=".$model->SHIPTOCOUNTRYCODE."&SHIPTOZIP=".$model->SHIPTOZIP;  
     


     $nvpstr="&useraction=commit&ADDRESSOVERRIDE=1".$shiptoAddress."&L_NAME0=".$model->L_NAME0."
&L_AMT0=".$model->L_AMT0."&L_QTY0=".$model->L_QTY0."&MAXAMT=".(string)$maxamt."&AMT=".(string)$amt."&ITEMAMT=".(string)$itemamt."&NOSHIPPING=1&CALLBACKTIMEOUT=4&L_SHIPPINGOPTIONAMOUNT1=8.00&L_SHIPPINGOPTIONlABEL1=UPS Next Day Air&L_SHIPPINGOPTIONNAME1=UPS Air&L_SHIPPINGOPTIONISDEFAULT1=true&L_SHIPPINGOPTIONAMOUNT0=3.00&L_SHIPPINGOPTIONLABEL0=UPS Ground 7 Days&L_SHIPPINGOPTIONNAME0=Ground&L_SHIPPINGOPTIONISDEFAULT0=false&INSURANCEAMT=1.00&INSURANCEOPTIONOFFERED=true&CALLBACK=https://www.ppcallback.com/callback.pl&SHIPPINGAMT=8.00&SHIPDISCAMT=-3.00&TAXAMT=2.00&L_NUMBER0=".$deal->id."&L_DESC0=".$deal->description."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$model->currencyCodeType."&PAYMENTACTION=".$model->paymentType;  
                        
                                
/* 

                                $nvpstr="&ADDRESSOVERRIDE=1".$shiptoAddress."&L_NAME0=".$model->L_NAME0."
&L_AMT0=".$model->L_AMT0."&L_QTY0=".$model->L_QTY0."&MAXAMT=".(string)$maxamt."&AMT=".(string)$amt."&ITEMAMT=".(string)$itemamt."&CALLBACKTIMEOUT=4&L_SHIPPINGOPTIONAMOUNT1=8.00&L_SHIPPINGOPTIONlABEL1=UPS Next Day Air&L_SHIPPINGOPTIONNAME1=UPS Air&L_SHIPPINGOPTIONISDEFAULT1=true&L_SHIPPINGOPTIONAMOUNT0=3.00&L_SHIPPINGOPTIONLABEL0=UPS Ground 7 Days&L_SHIPPINGOPTIONNAME0=Ground&L_SHIPPINGOPTIONISDEFAULT0=false&INSURANCEAMT=1.00&INSURANCEOPTIONOFFERED=true&CALLBACK=https://www.ppcallback.com/callback.pl&SHIPPINGAMT=8.00&SHIPDISCAMT=-3.00&TAXAMT=2.00&L_NUMBER0=1000&L_DESC0=Size: 8.8-oz&L_NUMBER1=10001&L_DESC1=Size: Two 24-piece boxes&L_ITEMWEIGHTVALUE1=0.5&L_ITEMWEIGHTUNIT1=lbs&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$model->currencyCodeType."&PAYMENTACTION=".$model->paymentType; 
  */                                              
                             //echo  $nvpstr;   
                             $nvpHeader=$this->nvpHeader();     

                             $nvpstr = $nvpHeader.$nvpstr;          
                            // echo $nvpstr;
                              /* Make the call to PayPal to set the Express Checkout token
			If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors
			*/ 
                             $resArray=$this->hash_call("SetExpressCheckout",$nvpstr);    
                             
                          //   $session->open();
                            // $session['reshash']=$resArray; 
                            // $session->close();
                             /* echo "<pre>";
                              print_r($resArray); 
                              echo "</pre>";  
                              return;*/
                              $ack = strtoupper($resArray["ACK"]);

		              if($ack=="SUCCESS")
                                   {
                                        

					// Redirect to paypal.com here
                                      
                                        
                                        $token = urldecode($resArray["TOKEN"]);
					$payPalURL = Yii::app()->controller->module->PAYPAL_URL.$token;
                                        $this->redirect($payPalURL);   
                                        
				  } 
                                else  {
					 
                                          
                                          $this->redirect(array('/paypal/APIError','msg'=>"Error on 1'st hash call"));   
					
				      } 
                                 
                            
                              
                            }
                          }
                          $this->render('orderform',array('model'=>$model,'deal'=>$deal)); 
          
           
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
                   $this->redirect($session['fb_returnURL']);
                  }
                else
                  {
                   $this->redirect(array('paypal/reviewOrder'));
                  
                  } 
           // Yii::app()->request->redirect('http://www.google.com');
  
          $session->close(); 
 
       }
 
       public function actionReviewOrder()
        {
 
             $session=new CHttpSession;
             $session->open();
               
             
          if(isset($session['PayerID'])&&isset($session['token']))
           {   
             $token=urlencode($session['token']); 
           }  
                  // print_r($session['reshash']); 
             $session->close();  
           
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
                   $nvpHeader=$this->nvpHeader();    
 
		   $nvpstr = $nvpHeader.$nvpstr;
                   /* Make the API call and store the results in an array.  If the
			call was a success, show the authorization details, and provide
			an action to complete the payment.  If failed, show the error
			*/
                           
		   $resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr);

		             $session=new CHttpSession;
                             $session->open();
                             $session['reshash']=$resArray; 
                             $session->close();
		   $ack = strtoupper($resArray["ACK"]);
                        
                            /*  
                              echo "<pre>";
                              print_r($resArray); 
                              echo "</pre>";  
                              //return;
                            */
                
		   if($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING')
                    {
 
                              //$session->open();
                              //echo "<br/>New token:";

                              //print_r($session['reshash']); 
                              //$session->close();   
                       echo CHtml::link("CLICK HERE TO CONFIRM YOUR PAYMENT",array('paypal/DoPayment'));
			 //require_once "GetExpressCheckoutDetails.php";
			  
                    } 
                   else 
                     {
			$this->redirect(array('/paypal/APIError','msg'=>"Error on loading confirmation button"));   
	             }
               
               

        }
       public function actionDoPayment()
        {
          $session=new CHttpSession;
          $session->open();
          $resArray=$session['reshash']; 
          $session->close();
      
          $nvpstr='&TOKEN='.urlencode($resArray['TOKEN']).'&PAYERID='.urlencode($resArray['PAYERID']).'&PAYMENTACTION=Sale&AMT='.urlencode($resArray['AMT']).'&CURRENCYCODE='. urlencode($resArray['CURRENCYCODE']).'&IPADDRESS='.urlencode($_SERVER['SERVER_NAME']);
          /* Make the call to PayPal to finalize payment
    If an error occured, show the resulting errors
    */

          $resArray=$this->hash_call("DoExpressCheckoutPayment",$nvpstr);
       
          $session->open();

          $session['reshash']=$resArray;
 
          /*
          echo "<pre>";
          print_r($session['reshash']);
          echo "</pre>"; 
           return; */ 
           
           $ack=strtoupper($resArray['ACK']);
           if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')   //CHECK WHETHER THE PAYMENT IS SUCCESS OR NOT
             {
                unset($session['token']);
                unset($session['PayerID']);
                unset($session['coupon_deal_id']);

                 $this->redirect(array('/groupbuy/facebook/authenticate/'));  
                //echo "ERRRR";  
                 
             }
            $session->close();      

         /*     
           echo "<pre>";
         
           print_r($resArray);
           
           echo "</pre>";  */
          
             
           //echo "Thank you for the payment";
           $this->redirect(array('/groupbuy/coupon/generateCoupon')); 
  
        }
       public function actionCancelPayment()
        {
           echo "Payment Cancelled";
           
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


  //this function is used to get the appropriate NVP HEADER (i.e.API CREDENTIALS)
      
       private function nvpHeader()
        {
        /*
         global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
        */
         //global  $AUTH_token,$AUTH_signature,$AUTH_timestamp;
 
          $nvpHeaderStr = "";

          if(!empty(Yii::app()->controller->module->AUTHMODE))
           {
	   //$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
	   //$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
	   //$AuthMode = "THIRDPARTY";Partner's API Credential and Merchant Email as Subject are required.
	   $AuthMode =Yii::app()->controller->module->AUTHMODE; 
           } 
         else 
           {
	
	      if((!empty(Yii::app()->controller->module->API_USERNAME)) && (!empty(Yii::app()->controller->module->API_PASSWORD)) && (!empty(Yii::app()->controller->module->API_SIGNATURE)) && (!empty(Yii::app()->controller->module->SUBJECT)))
               {
		$AuthMode = "THIRDPARTY";
	       }
	
              else if((!empty(Yii::app()->controller->module->API_USERNAME)) && (!empty(Yii::app()->controller->module->API_PASSWORD)) && (!empty(Yii::app()->controller->module->API_SIGNATURE)))
               {
		$AuthMode = "3TOKEN";
	       }
	
	      else if (!empty(Yii::app()->controller->module->AUTH_TOKEN) && !empty(Yii::app()->controller->module->AUTH_SIGNATURE) && !empty(Yii::app()->controller->module->AUTH_TIMESTAMP)) 
              {
		$AuthMode = "PERMISSION";
	      }
             else if(!empty(Yii::app()->controller->module->SUBJECT))
             {
		$AuthMode = "FIRSTPARTY";
	     }
          }
    switch($AuthMode)
          {
	
	case "3TOKEN" : 
			$nvpHeaderStr = "&PWD=".urlencode(Yii::app()->controller->module->API_PASSWORD)."&USER=".urlencode(Yii::app()->controller->module->API_USERNAME)."&SIGNATURE=".urlencode(Yii::app()->controller->module->API_SIGNATURE);
			break;
	case "FIRSTPARTY" :
			$nvpHeaderStr = "&SUBJECT=".urlencode(Yii::app()->controller->module->SUBJECT);
			break;
	case "THIRDPARTY" :
			$nvpHeaderStr = "&PWD=".urlencode(Yii::app()->controller->module->API_PASSWORD)."&USER=".urlencode(Yii::app()->controller->module->API_USERNAME)."&SIGNATURE=".urlencode(Yii::app()->controller->module->API_SIGNATURE)."&SUBJECT=".urlencode(Yii::app()->controller->module->SUBJECT);
			break;		
	case "PERMISSION" :
		    $nvpHeaderStr = formAutorization(Yii::app()->controller->module->AUTH_TOKEN,Yii::app()->controller->module->AUTH_SIGNATURE,Yii::app()->controller->module->AUTH_TIMESTAMP);
		    break;
       }
	return $nvpHeaderStr;
}     

/**
  * hash_call: Function to perform the API call to PayPal using API signature
  * @methodName is name of API  method.
  * @nvpStr is nvp string.
  * returns an associtive array containing the response from the server.
*/


private function hash_call($methodName,$nvpStr)
{
   $session=new CHttpSession;
    
	//declaring of global variables
	//global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
	// form header string
	$nvpheader=$this->nvpHeader();
	//setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,Yii::app()->controller->module->API_ENDPOINT);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	
	//in case of permission APIs send headers as HTTPheders
	if(!empty(Yii::app()->controller->module->AUTH_TOKEN) && !empty(Yii::app()->controller->module->AUTH_SIGNATURE) && !empty(Yii::app()->controller->module->AUTH_TIMESTAMP))
	 {
		$headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
    curl_setopt($ch, CURLOPT_HEADER, false);
	}
	else 
	{
		$nvpStr=$nvpheader.$nvpStr;
	}
    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
	if(Yii::app()->controller->module->USE_PROXY)
	curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 

	//check if version is included in $nvpStr else include the version.
	if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) 
        {
		$nvpStr = "&VERSION=" . urlencode(Yii::app()->controller->module->VERSION) . $nvpStr;	
	}
	
	$nvpreq="METHOD=".urlencode($methodName).$nvpStr;
	
	//setting the nvpreq as POST FIELD to curl
	curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

	//getting response from server
	$response = curl_exec($ch);

	//convrting NVPResponse to an Associative Array
	$nvpResArray=$this->deformatNVP($response);
	$nvpReqArray=$this->deformatNVP($nvpreq);

	$session->open();
        $session['nvpReqArray']=$nvpReqArray;
        $session->close(); 

	if (curl_errno($ch)) {
		// moving to display page to display curl errors
                  $session->open();
                
		  $session['curl_error_no']=curl_errno($ch) ;
		  $session['curl_error_msg']=curl_error($ch);
		  //$location = "APIError.php";
                  $session->close(); 
                  $this->redirect(array('/paypal/APIError','msg'=>'Error in curl'));   
 	      	 // header("Location: $location");
	 } else {
		 //closing the curl
			curl_close($ch);
	  }

return $nvpResArray;
 }
/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
  * It is usefull to search for a particular key and displaying arrays.
  * @nvpstr is NVPString.
  * @nvpArray is Associative Array.
  */

private function deformatNVP($nvpstr)
{

	$intial=0;
 	$nvpArray = array();


	while(strlen($nvpstr)){
		//postion of Key
		$keypos= strpos($nvpstr,'=');
		//position of value
		$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

		/*getting the Key and Value values and storing in a Associative Array*/
		$keyval=substr($nvpstr,$intial,$keypos);
		$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
		//decoding the respose
		$nvpArray[urldecode($keyval)] =urldecode( $valval);
		$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
     }
	return $nvpArray;
}
private function formAutorization($auth_token,$auth_signature,$auth_timestamp)
{
	$authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
	return $authString;
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
