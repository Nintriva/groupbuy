<?php

class GroupbuyScheduleController extends Controller
{

 private $deals;

 private $unpublished_deals;

 public function actionStart()
 {
   
   $criteria1=new CDbCriteria;
   
   $criteria1->condition='published=1 or is_expired=1 ';                      
   
  // $criteria1->params=array(':published'=>'1'); 
   //$criteria1->together=true;   


   $this->deals=Deal::model()->findAll($criteria1);

  $criteria2=new CDbCriteria;
 
   $criteria2->condition="published='0' and auto_publish='1' and is_expired='0' ";                      
   
   $criteria2->together=true;   


   $this->unpublished_deals=Deal::model()->findAll($criteria2);


 
    $this->CheckDeals();
   
    $this->autoPublish();  
     
 }

 public function autoPublish()
 {
    $tz=Deal::model()->getTimeZone(); 
    foreach($this->unpublished_deals as $up_deal)
    {
      date_default_timezone_set($tz[$up_deal->timezone]);
      if(mktime()>=strtotime($up_deal->start_date)&&$up_deal->published==0)
       {
         $up_deal->status=1;
         $up_deal->auto_publish=0;   
         $up_deal->published=1;
         $up_deal->save(false); 

       }
       
    }
 }

 public function CheckDeals()
 {
  
  //mail('sirinibin2006@gmail.com','Cron test','cron test','From:mahesheu@gmail.com');  
   
   $tz=Deal::model()->getTimeZone(); 
  
  $i=0;

     foreach($this->deals as $deal)
      {         
        echo $deal->title."<br/>";

        date_default_timezone_set($tz[$deal->timezone]); 
         
       
            if($deal->isExpired()&&($deal->is_expired==0))
             {  //EXPIRED CASE
                echo  "<br/> EXPIRED:".$deal->title; 
                  $deal->is_deal_on=0;
                  $deal->is_expired=1;
                   
                  $deal->save(false);                 


              if($deal->isTipped())   
                {
                   $deal->status=3;
                   $deal->save(false); 
                  //$nvpstr.="&L_EMAIL$j=$receiverEmail&L_Amt$j=$amount&L_UNIQUEID$j=$uniqueID&L_NOTE$j=$note;
                    echo "<br/>Sending money to deal owner...";

                   $total_amt=0.0;
                  foreach($deal->paid_transactions as $t)
                    {
                         
                       $total_amt+=$t->amount;
                       
                    }
                   $nvpstr="&L_EMAIL0=".$deal->paypal_address."&L_Amt0=".$total_amt."&L_UNIQUEID0=&L_NOTE0=";
 
                   $receiverType="EmailAddress";
                   $currency="USD";
                   $emailSubject="Deal".$deal->title."\'s Total amount of ".$total_amt." is credited";  

                   $nvpstr.="&EMAILSUBJECT=".$emailSubject."&RECEIVERTYPE=".$receiverType."&CURRENCYCODE=".$currency;

                   if($total_amt>0.0)
                   {    
                     $resArray=$deal->hash_call("MassPay",$nvpstr);
                   }
                   else
                    {
                          foreach($deal->coupons as $c)
                                 {

                                   $m=$c->getMail("buyer","ended_with_tipped");  
                                   mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 

                              
            
                                 } 
 
                              $m=$deal->getMail("owner","ended_with_tipped");
                              mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');
                       return;

                    } 
 
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
                              foreach($deal->paid_transactions as $t)
                                  {    
                                     $t->is_paid_to_owner=1;
                                     $t->paid_timestamp=$resArray['TIMESTAMP'];
                                     $t->save();

                                  }
                              
                               foreach($deal->coupons as $c)
                                 {
                                   $m=$c->getMail("buyer","ended_with_tipped");  
                                   mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');            
            
                                 } 
 
                              $m=$deal->getMail("owner","ended_with_tipped");
                              mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');    
                      


                          }

 
             
 
 
                } //end tipped if case
               else
               {         
                  $deal->status=4;
                  $deal->save(false);  
                        /*This is the non tipped case even after expiry.
                         here  we need to refund all of the amounts of buyers */
                                                  
                      foreach($deal->free_transactions as $t)
                      {           
                        echo "free<br/>";
                                $t->is_cancelled=1;
                                $t->cancelled_at=date('Y-m-d H:i:s');
                                $t->save(false);     
    
                             //sending mails to the customers who bought free coupons  
                               $m=$t->getMail("buyer","expired_without_tipped1");  
                               mail($t->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');
                           
                          
   
                      }                          
                     
                     //Refund all transactions.
                foreach($deal->paid_transactions as $t)
                   {
                     echo "paid<br/>";
                       
                        
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
                             $t->is_cancelled=1;
                             $t->cancelled_at=date('Y-m-d H:i:s');   
                             $t->refund_transaction_id=$resArray['REFUNDTRANSACTIONID'];
                             $t->total_refund_amt=$resArray['TOTALREFUNDEDAMOUNT'];
                             $t->fee_refund_amt=$resArray['FEEREFUNDAMT'];
                             $t->net_refund_amt=$resArray['NETREFUNDAMT'];
                             $t->refund_timestamp=$resArray['TIMESTAMP'];
                             $t->refund_currency_code=$resArray['CURRENCYCODE'];

                             $t->save();
                            
                               $m=$t->getMail("buyer","expired_without_tipped");  
                             
                              mail($t->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');                                       
                               
 
                          } 

                   } //end refund transaction loop

                 // mail to the deal owner about the refund.

               if($deal->getBoughtCount()==0)
                 {
                    $m=$deal->getMail("owner","expired_without_buyers");
                    mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 

                 }
                else if($deal->getBoughtCount()>0) 
                 { 
                 $m=$deal->getMail("owner","expired_without_tipped");
                 mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                 }
                  
                 
         
               }  //end non-tipped else case
                

             }// end expired if case
            else if($deal->is_expired==0&&!$deal->isExpired())
             { 
                   //NOT EXPIRED CASE 
                  echo  "<br/>NOT EXPIRED:".$deal->title; 
                         if($deal->isTipped())
                          {
                            if($deal->is_tipped==0)
                              { 
                                $deal->tipped_at=date('Y-m-d H:i:s');
                                $deal->is_deal_on=1;
                                $deal->is_tipped=1;
                                $deal->status=2;
                                $deal->save(false);

                                //generate coupons for all transactions  
                                $deal->generateCoupons();   
                  
                              //SEND MAIL TO ALL COUPON BUYERS
                               foreach($deal->coupons as $c)
                                {

                                     if($c->status!=2&&$deal->isTipped())
                                      {
                                        $c->status=2;
                                        $c->save(); 
                                      /*Now tha coupon is active and money collected.(but it is still in the hand of xgate.
                                       it will get transafered only after ending the deal or sold out before expiry) */
 
                                      }
                                      
                     
                                $m=$c->getMail("buyer","tipped"); 
                                mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                                }
                               //SEND MAIL TO DEAL OWNER
                                $m=$deal->getMail("owner","tipped");
                                mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                     //Transfer all current payment to the deal owner
            
                              } //end if is_tipped flag not set case
                            else
                             {
                                     
                              //tipped but flag already set case

                             }
 
 
                          }


                //ALL COUPONS SOLD OUT BEFORE EXPIRY CASE
                if($deal->isEmpty())
                 {
                     $deal->is_deal_on=0;
                     $deal->is_expired=1;
                     $deal->status=6;
                     $deal->save(false); 

                //transfer all the payments to the dealowner.  
                      $total_amt=0.0;
                    foreach($deal->paid_transactions as $t)
                      {
                        $total_amt+=$t->amount;
                       
                      }
                     $nvpstr="&L_EMAIL0=".$deal->paypal_address."&L_Amt0=".$total_amt."&L_UNIQUEID0=&L_NOTE0=";
 
                      $receiverType="EmailAddress";
                      $currency="USD";
                      $emailSubject="Deal".$deal->title."\'s Total amount of ".$total_amt." is credited";  

                      $nvpstr.="&EMAILSUBJECT=".$emailSubject."&RECEIVERTYPE=".$receiverType."&CURRENCYCODE=".$currency;

                    if($total_amt>0.0)
                      {  
                      $resArray=$deal->hash_call("MassPay",$nvpstr);
                      }
                     else
                      {
                            //All the coupons were free  
                                foreach($deal->coupons as $c)
                                { 
                     
                                 $m=$c->getMail("buyer","sold_out_before_exp"); 
                                  mail($c->buyer->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com'); 
                                }

                                 //SEND MAIL TO DEAL OWNER
                                 $m=$deal->getMail("owner","sold_out_before_exp");
                                 mail($deal->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');

                                 return; 

 
                      }
          
 
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
                             foreach($deal->paid_transactions as $t)
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

               } //end is empty case(ie all coupons are sold out before expiry)   

          }    //end else if (NON- EXPIRED CASE)
         else if($deal->is_expired==1&&$deal->isExpired())
          {
             //Expired and flag is also set case
             echo "Expired".$deal->title."<br/>";

               foreach($deal->coupons as $c)
                  {
                    if($c->status!=3&&$deal->isCouponExpired())
                      {
                         $deal->RedeemCoupon($c);

                      }
 

                  } 
                        


          }
      

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
