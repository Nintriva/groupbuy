<?php

class GroupbuyScheduleController extends Controller
{

 private $deals;

 public function actionStart()
 {
   
   $criteria=new CDbCriteria;
  // $criteria->condition='is_deal_on=:is_deal_on';  
     $criteria->condition='published=:published';                      
   //$criteria->params=array(':is_deal_on'=>'1');
   $criteria->params=array(':published'=>'1');    


   echo "ok1";
   $this->deals=Deal::model()->with(array('coupons_count',
                                          'coupons'=>array('buyer'),
                                         // 'free_coupons'=>array('buyer'),
                                          'transactions',
 
                                          ))->findAll($criteria);
  echo "ok2";   
  
 
    $this->actionCheckDeals();
     
 
 }

 public function actionCheckDeals()
 {
  
  //mail('sirinibin2006@gmail.com','Cron test','cron test','From:mahesheu@gmail.com');  
   
   $tz=Deal::model()->getTimeZone(); 
  
  $i=0;

     foreach($this->deals as $deal)
      {
            $criteria=new CDbCriteria;
 
     $criteria->condition='is_free="1" and deal="'.$deal->id.'" ';                        
     $free_coupons=Coupon::model()->findAll($criteria);           
                    

        date_default_timezone_set($tz[$deal->timezone]); 
         
       
            if($deal->isExpired()&&($deal->is_expired==0))
             {  //EXPIRED CASE
                echo  "<br/> EXPIRED:".$deal->title; 
                  $deal->is_deal_on=0;
                  $deal->is_expired=1;
                  $deal->save();                 


              if($deal->isTipped())   
                {
                  //$nvpstr.="&L_EMAIL$j=$receiverEmail&L_Amt$j=$amount&L_UNIQUEID$j=$uniqueID&L_NOTE$j=$note;
                    echo "<br/>Sending money to deal owner...";

                   $total_amt=0.0;
                  foreach($deal->transactions as $t)
                    {
                       $total_amt+=$t->amount;
                       
                    }
                   $nvpstr="&L_EMAIL0=".$deal->paypal_address."&L_Amt0=".$total_amt."&L_UNIQUEID0=&L_NOTE0=";
 
                   $receiverType="EmailAddress";
                   $currency="USD";
                   $emailSubject="Deal".$deal->title."\'s Total amount of ".$total_amt." is credited";  

                   $nvpstr.="&EMAILSUBJECT=".$emailSubject."&RECEIVERTYPE=".$receiverType."&CURRENCYCODE=".$currency;
                   $resArray=$deal->hash_call("MassPay",$nvpstr);
 
                 //  echo "<pre>";
                  // print_r($resArray);
                  // echo "</pre>";  
                    
                        $ack=strtoupper($resArray['ACK']);
                         if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')   //CHECK WHETHER THE PAYMENT IS SUCCESS OR NOT
                          {
                             mail($deal->email,'Payment of deal:'.$deal->title.' to  your paypal account is failed','Dear owner,Payment of deal:'.$deal->title.' to your paypal account is failed due to the following reason:'.$resArray['L_SHORTMESSAGE0'].":".$resArray['L_LONGMESSAGE0'],'From:parrysgroupbuy@groupbuy.com'); 

                          }
                         else
                          {
                                //PAYMENT TO DEAL OWNER SUCCESS
                              foreach($deal->transactions as $t)
                                  {
                                    $t->is_paid_to_owner=1;
                                    $t->paid_timestamp=$resArray['TIMESTAMP'];
                                    $t->save();
                                  // echo "okkkkkkkkkkkkkkkk<br/>";

                                  }
                              
                               foreach($deal->coupons as $c)
                                 {
                                   $m=$c->getMail("buyer","ended_with_tipped");  
                                   mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');            
            
                                 } 
 
                              $m=$deal->getMail("owner","ended_with_tipped");
                              mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');    
                      


                          }


                   /*      
                   $MASS_PAYMENT['receiveremail'][$i]=$deal->paypal_address;
                   $MASS_PAYMENT['amount'][$i]=$total_amt;
                   $MASS_PAYMENT['uniqueID'][$i]='';
                   $MASS_PAYMENT['note'][$i]='';                                
                   $i++;
                    */
 
             
 
 
                } //end tipped if case
               else
               {              
                       //sending mails to the customers who bought free coupons

                     
                          foreach($free_coupons as $c)
                             {
                                
                                   $m=$c->getMail("buyer","expired_without_tipped");  
                                   mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');
                                                                        
                             }
                            
                                                      
                       echo "<br/>After loop"; 
                     //Refund all transactions.
                foreach($deal->transactions as $t)
                   {
                        
                        $nvpStr="&TRANSACTIONID=".$t->transaction_id."&REFUNDTYPE=Full&CURRENCYCODE=".$t->currency_code."&NOTE='' ";

                        //if(strtoupper($refundType)=="PARTIAL") $nvpStr=$nvpStr."&AMT=$amount";

                         /* Make the API call to PayPal, using API signature.
                         The API response is stored in an associative array called $resArray */
                         $resArray=$deal->hash_call("RefundTransaction",$nvpStr);  
 
                            $ack=strtoupper($resArray['ACK']);
                         if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')   //CHECK WHETHER THE REFUND IS SUCCESS OR NOT
                          {
                           //mail about refund failure.
                              
                        mail($t->buyer->email,'Paypal:Refund to your paypal account is Failed','Refund to your account failed due to the following reason:'.$resArray['L_SHORTMESSAGE0'].":".$resArray['L_LONGMESSAGE0'],'From:parrysgroupbuy@groupbuy.com');                                       
                              
                          
                              mail($deal->email,'Refund to '.$t->buyer->first_name.' '.$t->buyer->last_name.'\'s paypal account is failed','Refund to '.$t->buyer->first_name." ".$t->buyer->last_name.'\'s account is failed due to the following reason:'.$resArray['L_SHORTMESSAGE0'].":".$resArray['L_LONGMESSAGE0'],'From:parrysgroupbuy@groupbuy.com');  
                 
                 
                          }
                         else
                          {
                             $t->is_refunded=1;
                             $t->refund_transaction_id=$resArray['REFUNDTRANSACTIONID'];
                             $t->total_refund_amt=$resArray['TOTALREFUNDEDAMOUNT'];
                             $t->fee_refund_amt=$resArray['FEEREFUNDAMT'];
                             $t->net_refund_amt=$resArray['NETREFUNDAMT'];
                             $t->refund_timestamp=$resArray['TIMESTAMP'];
                             $t->refund_currency_code=$resArray['CURRENCYCODE'];

                             $t->save();
                            //foreach($deal->coupons as $c)
                            // {
                               $m=$t->getMail("buyer","expired_without_tipped");  
                             
                              mail($t->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');                                       
                             //}    
 
                          } 

                   } //end refund transaction loop
                 // mail to the deal owner about the refund.

                 $m=$deal->getMail("owner","expired_without_tipped");
                 mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                 
                 
         
               }  //end non-tipped else case
                

             }// end expired if case
            else if($deal->is_expired==0&&!$deal->isExpired())
             { 
                   //NOT EXPIRED CASE & FLAG is_expired=1 case(ie if once  we are setted as 1 the further flow  will come here )
                  echo  "<br/>NOT EXPIRED:".$deal->title; 

                if($deal->isEmpty())
                 {
                     $deal->is_deal_on=0;
                     $deal->is_expired=1;
                     $deal->save(); 

                //transfer all the payments to the dealowner.  
                      $total_amt=0.0;
                    foreach($deal->transactions as $t)
                      {
                        $total_amt+=$t->amount;
                       
                      }
                     $nvpstr="&L_EMAIL0=".$deal->paypal_address."&L_Amt0=".$total_amt."&L_UNIQUEID0=&L_NOTE0=";
 
                      $receiverType="EmailAddress";
                      $currency="USD";
                      $emailSubject="Deal".$deal->title."\'s Total amount of ".$total_amt." is credited";  

                      $nvpstr.="&EMAILSUBJECT=".$emailSubject."&RECEIVERTYPE=".$receiverType."&CURRENCYCODE=".$currency;
                      $resArray=$deal->hash_call("MassPay",$nvpstr);
 
                 //  echo "<pre>";
                  // print_r($resArray);
                  // echo "</pre>";  
                    
                      $ack=strtoupper($resArray['ACK']);
                 if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')   //CHECK WHETHER THE PAYMENT IS SUCCESS OR NOT
                    {
                   mail($deal->email,'Payment of deal:'.$deal->title.' to  your paypal account is failed','Dear owner,Payment of deal:'.$deal->title.' to your paypal account is failed due to the following reason:'.$resArray['L_SHORTMESSAGE0'].":".$resArray['L_LONGMESSAGE0'],'From:parrysgroupbuy@groupbuy.com'); 

                   }
                else
                   {
                         //PAYMENT is success
                             foreach($deal->transactions as $t)
                                  {
                                    $t->is_paid_to_owner=1;
                                    $t->paid_timestamp=$resArray['TIMESTAMP'];
                                    $t->save();
                                  // echo "okkkkkkkkkkkkkkkk<br/>";

                                  }


                       foreach($deal->coupons as $c)
                        { 
                     
                         $m=$c->getMail("buyer","sold_out_before_exp"); 
                         mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                        }

                       //SEND MAIL TO DEAL OWNER
                       $m=$deal->getMail("owner","sold_out_before_exp");
                       mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                
                   }
                   
    
                  // do nothing

               } //end is empty case   

          }    //end else if (NON- EXPIRED CASE)
      
      

    } //end deal for loop

        
//do mass payments.
/*
$j=0;
$nvpstr="";

foreach($MASS_PAYMENT as $M)
     {
        $nvpstr.="&L_EMAIL".$j."=".$M['receiveremail']."&L_Amt".$j."=".$M['amount']."&L_UNIQUEID".$j."=".$M['uniqueID']."&L_NOTE".$j."=".$M['note'];
        $j++; 

     }    
/* Construct the request string that will be sent to PayPal.
   The variable $nvpstr contains all the variables and is a
   name value pair string with & as a delimiter */
/*   
$emailSubject="";

$nvpstr.="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=EmailAddress&CURRENCYCODE=USD" ;




/* Make the API call to PayPal, using API signature.
   The API response is stored in an associative array called $resArray */

//$resArray=$deal->hash_call("MassPay",$nvpstr);

  
  
 } //end actionCheckDeals





}

?>
