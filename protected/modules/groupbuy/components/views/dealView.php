
<?php
//echo "End date:".date('M-d-Y H:i:s',strtotime($this->deal->end_date));

$tz=$this->deal->getTimeZone();
date_default_timezone_set($tz[$this->deal->timezone]); 

// echo Yii::app()->controller->module->registerCss('dealView.css');
?>
<?php 

 //echo CHtml::image(Yii::app()->controller->module->registerImage('deal.png'), "logo");


?>


<?php
            
//echo "<br/>Free coupons available:".$this->deal->free_coupons;

 
?>

    <div id="main-content" class="main-content contests_show_main contest_details"> 
     
	<div id="policies_column"> 
             <?php 
            if($this->deal->image!=null)
            {
          echo "<b/><br/><br/><img src='".Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/deal/'.$this->deal->image)."' width='500' height='250' title='".$this->deal->title."'>";
            }
       ?>
 
 
	    <div id="group-deal-info"> 
              
	      <div id="deal"> 
                   <!--http://wildfireapp.com/website/6/contests/140635/entries/new?preview_key=70a52eef49cf6373758b508c72660f84-->
	         <!--  <a href="#" id="buynow-bt">Buy now</a> --> 
                    

                  <?php
                         //ALLLOW USER TO BUY IFF THE DEAL IS NOT EXPIRED AND IS NOT YET REACHED THE MAX_AVAILABLE UNITS
                  if($this->deal->is_free_coupon==0)
                   {
                     if($this->facebook_view==true)  
                       {

                          //FACEBOOK VIEW

                        if($this->deal->isDealAvailable())
                      // if(mktime()<strtotime($this->deal->end_date)&&$this->deal->coupons_count<$this->deal->max_available)    
                         {
                         // if($this->deal->free_coupons==0) 
                           echo CHtml::link('Buy now',array('paypal/submitOrder','deal_id'=>$this->deal->id),array('id'=>'buynow-bt'));
                          // echo CHtml::link('Buy now',array('facebook/paypalTest'),array('id'=>'buynow-bt')); 
                           
                          //else
                          // echo CHtml::link('Buy now',array('coupon/freeCoupon','deal_id'=>$this->deal->id),array('id'=>'buynow-bt'));  
                         }
                       else
                         {
                           echo "<div id='closed-bt'  >DEAL CLOSED</div>";
                          // echo CHtml::link('DEAL CLOSED',array(''),array('id'=>'buynow-bt','style'=>'background:red;'));   
                         }
                      }
                     else
                      {
                         //BACKEND VIEW                        
 
                         if($this->deal->isDealAvailable())  
                         {                          
                           echo "<div id='bt'>".CHtml::link('Buy now',array('','id'=>$this->deal->id),array('id'=>'buynow-bt'))."</div>";  
                         }
                       else
                         {

                           echo "<div id='closed-bt'  >DEAL CLOSED</div>";
                          //echo "<div id='bt'>".CHtml::link('DEAL CLOSED',array('','id'=>$this->deal->id),array('id'=>'buynow-bt','style'=>'background:red;'))."</div>";   
                         }

                      
   
                      } 
                    }

                 else
                  {
                         //group free coupon 

                        if($this->facebook_view==true)  
                       {

                          //FACEBOOK VIEW

                        if($this->deal->isDealAvailable()) 
                         {
                          // if($this->deal->free_coupons==0) 
                           //echo CHtml::link('Buy now',array('paypal/submitorder','deal_id'=>$this->deal->id),array('id'=>'buynow-bt'));
                          //else
                           echo CHtml::link('GET COUPON',array('coupon/FreeCouponOrder','deal_id'=>$this->deal->id),array('id'=>'buynow-bt'));  
                         }
                       else
                         {
                           echo "<div id='closed-bt'  >FREE COUPON CLOSED</div>";
                          // echo CHtml::link('DEAL CLOSED',array(''),array('id'=>'buynow-bt','style'=>'background:red;'));   
                         }
                      }
                     else
                      {
                         //BACKEND VIEW                        
 
                         if($this->deal->isDealAvailable())  
                         {                          
                           echo "<div id='bt'>".CHtml::link('GET COUPON',array('','id'=>$this->deal->id),array('id'=>'buynow-bt'))."</div>";  
                         }
                       else
                         {

                           echo "<div id='closed-bt'  >DEAL CLOSED</div>";
                          //echo "<div id='bt'>".CHtml::link('DEAL CLOSED',array('','id'=>$this->deal->id),array('id'=>'buynow-bt','style'=>'background:red;'))."</div>";   
                         }

                      
   
                      }




 

                  }
                   ?> 
                <?php
                 if($this->deal->is_free_coupon==0)
                  {

                 ?>
	        Deal: <strong><?php echo $this->deal->getCurrencySymbol().($this->deal->deal_price); ?></strong>
                 <?php
                  }
                 else
                  {
                  ?>
                   <?php echo $this->deal->discount_percentage."%"; ?>DISCOUNT
                <?php
                  } 

                  ?>  
	      </div> <!--deal --> 
                <?php  echo "<div style='font-size:20px;'>".$this->deal->title."</div>"; ?>            
	      <div id="groupdeal-summary"> 
               
	        <h3>Group Deal Summary</h3> 
	        <p> 
                  <?php echo nl2br($this->deal->description); ?>
	          
	        </p> 
	        <ul id="discount-value"> 
	          <li> 
                  <?php
                    if($this->deal->is_free_coupon==0)
                    {
                  ?>
	            <h3>Value</h3> 
	            <span> 
	              <?php echo $this->deal->getCurrencySymbol().$this->deal->retail_price; ?>
	            </span> 
	          </li> 
	          <li> 
	            <h3>Discount</h3> 
	            <span> 
	               <?php echo $this->deal->discount_percentage."%";?>
	            </span> 
	          </li> 
	          <li> 
	            <h3>You save</h3> 
	            <span> 
	               <?php echo $this->deal->getCurrencySymbol().$this->deal->discount_value; ?>
	            </span>
                  <?php
                    }
                    else
                     { 
                   ?>
                     <h3></h3> 
	            <span> 
	              <?php //echo "$".$this->deal->retail_price; ?>
	            </span> 
	          </li> 
	          <li> 
	            <h3></h3> 
	            <span> 
	               <?php //echo $this->deal->discount_percentage."%";?>
	            </span> 
	          </li> 
	          <li> 
	            <h3></h3> 
	            <span> 
	               <?php //echo "$".$this->deal->discount_value;
                           // echo $this->deal->discount_percentage."%";
 
                      ?>
	            </span>
                    <?php
                      }
 
                     ?>
	          </li> 
	        </ul> 
	      </div> 
 
	        <div id="progress">
 
	          <h2 id="timer1"> 
                   <?php



$epoch_1=strtotime($this->deal->end_date);
$epoch_2=mktime();
//echo "ep:".$epoch_1;

$diff_seconds  = $epoch_1 - $epoch_2;
$diff_seconds1=$diff_seconds;

//$diff_weeks    = floor($diff_seconds/604800);

//$diff_seconds -= $diff_weeks   * 604800;


$diff_days     = floor($diff_seconds/86400);

$diff_seconds -= $diff_days    * 86400;
$diff_hours    = floor($diff_seconds/3600);
$diff_seconds -= $diff_hours   * 3600;
$diff_minutes  = floor($diff_seconds/60);
$diff_seconds -= $diff_minutes * 60;

                
    //echo  "Time left to buy:".$diff_days." days ".$diff_hours." hr ".$diff_minutes." min ".$diff_seconds." sec";

//echo $diff_seconds;
 
   echo '<div align="center" id="timer"></div>';

 
echo "<input type='hidden' value='".$diff_seconds1."' id='diff_sec'>";

  echo "
 <script type='text/javascript'>



var p=document.getElementById('diff_sec').value;

get();

function gettime()
{
 
 

 day=Math.floor(p/86400);

 
rest=Math.floor(p%86400);

 hr=Math.floor(rest/3600);

 mi=Math.floor(rest%3600);

 minu=Math.floor(mi/60);

 se=Math.floor(p%60)

 document.getElementById('timer').innerHTML='Time left to buy:'+day+' days '+hr+' hr '+minu+' mins '+se+'sec';

p--;
if(p<=0)
 {

   document.getElementById('timer').innerHTML='Time left to buy:0 days 0 hr 0 min 0 sec'; 
  

 }
}  

function get()
{
	setInterval('gettime()',1000);
	
}
</script>


";

 

                    
                    /*  $time_one = new DateTime();
                     $time_two = new DateTime($this->deal->end_date);

                     $difference = $time_one->diff($time_two);
                     echo $difference->format('%D days %h hours %i minutes %s seconds');
                      */
                    ?>
	             
	          </h2> 
 
	          <div id="progress-barmain"> 
<div id="p_x" ></div>
	            <div> 
                     <?php
                       $current_value=$this->deal->getBoughtCount();
                       $max_available=$this->deal->max_available;
                       $tipping_point=$this->deal->tipping_point;        
 
                      echo "<input type='hidden' id='current_value' value='".$current_value."' >
                      <input type='hidden' id='max_available' value='".$max_available."'>
                      <input type='hidden' id='tipping_point' value='".$tipping_point."' > "; 
                     ?>
<script type="text/javascript">
getprogress();
function getprogress()
{
var current_value=parseInt(document.getElementById('current_value').value);
var max_available=parseInt(document.getElementById('max_available').value);
var tipping_point=parseInt(document.getElementById('tipping_point').value);
//current_value=5;
//max_available=5;
//tipping_point=2;
//alert(current_value+"\n"+max_available+"\n"+tipping_point);
var per=(current_value/max_available)*100;
//alert(per);
document.getElementById('p_x').style.width=per+"%";
if(current_value>=tipping_point)
	{
	document.getElementById('p_x').style.background="green";
	}	
else
{
document.getElementById('p_x').style.background="#039";

}
if(current_value>=max_available)
{
document.getElementById('p_x').style.background="red";
}

}

</script>
	              <span style="width: 0%;"></span> 
	            </div> 
	          </div> 
 
      	          <h2>
                      <?php
                         
                               if($this->deal->isExpired())     
                                 {   
                                   
                                     //expired case

                                    if($this->deal->isTipped())
                                    {
                                       //tipping point reached       //Perform payments
                                        echo "DEAL SOLD OUT.<br/>";
                                        echo $this->deal->getBoughtCount()." bought(s).";  
                                    }
                                    else
                                     {
                                        //tipping point is not reached
                                       echo "DEAL EXPIRED<br/>";   //Cancel all payments
                                       echo $this->deal->getCancelledBoughtCount()." bought(s).";  
                                     }           
 
                                 }
                               else
                                 {  
                                      // not expired case
                 
                                    if($this->deal->isTipped())
                                      {
                                             
 
                                               if(!$this->deal->isDealAvailable())
                                               {
                                                     //Sold out complete units.

                                                  echo "DEAL SOLD OUT BEFORE EXPIRY.<br/>";
                                                  echo $this->deal->getBoughtCount()." bought(s).";                                      
   
                                               }     

                                             else if($this->deal->isDealOn())
                                               {
                                                    //tipping point reached.THE DEAL IS ON
                                                   echo $this->deal->getBoughtCount()." bought,The DEAL IS ON";
                                                   echo "<br/> Tipped at ".date('M-d-Y H:i:s',strtotime($this->deal->tipped_at))." with ".$this->deal->tipping_point." bought.";     

                                               }
 
                                      }
                                    else
                                      {
                                        //Not yet tipped. 
                                          echo $this->deal->getBoughtCount()." bought,".($this->deal->tipping_point-$this->deal->getBoughtCount())." more needed to get this deal";

                                      }   
 
                                                                
                                       
 

                                 }
     
                                       
                               
                            
  
                      ?>  
 
	             
 
	          </h2> 
	        </div> 
 
	    </div> 
 
 
	</div> 
	
	</div>
</div>


<?php
echo "<input type='hidden' id='deal_bar' value='".Yii::app()->assetManager->publish(Yii::getPathOfAlias("groupbuy")."/assets/images/deal.png")."' >
     <input type='hidden' id='buy_now_bt' value='".Yii::app()->assetManager->publish(Yii::getPathOfAlias("groupbuy")."/assets/images/bt.png")."' > 
        
";
?>
<script type='text/javascript'>
load_images();
function load_images()
{
 
  var deal_bar=document.getElementById('deal_bar').value;
  var buy_now_bt=document.getElementById('buy_now_bt').value;
  
 //alert(deal_bar+"\n"+buy_now_bt); 

document.getElementById('deal').style.background="url("+deal_bar+")";
document.getElementById('buynow-bt').style.background="url("+buy_now_bt+")";
}

</script>
<style type='text/css'>

 
#main-content { margin: 0px; }
 
.main-content { position: relative; background: #eeeeee; padding: 0px 0; border-top: 1px solid #cccccc; overflow: hidden; width: 520px; 
margin-right:0px;
}
.main-content .left-column { float: left; width: 300px; }
.main-content .right-column { float: right; width: 460px; }
.main-content.blank_title { border-top: 0; }
 
body { font-family: "lucida grande", tahoma, verdana, arial, sans-serif; font-size: 11px; text-align: left; }
 
 
h1 { font-size: 18px; color: #333333; color: #333333; margin: 0; padding: 10px 30px 30px 30px; }
hr { border: 0; background-color: #cccccc; height: 1px; margin-top: 2px; margin-bottom: 2px; }
h2, h3, h4, h5 { color: #333333; font-size: 13px; margin: 0; padding: 0; }
a { color: #3b5998; text-decoration: none; }
 
 
#prize_column, #policies_column { height: 100%; background-color: white; border-bottom: 1px solid #cccccc; }
 
#prize_column { padding: 15px; float: left; width: 300px; margin-left: 30px; }
#prize_column #contest_buttons { margin: 0 50px; text-align: center; }
#prize_column #contest_buttons em { display: block; padding: 1em 0; }
#prize_column .contest-button { margin-bottom: 15px; }
#prize_column .contest-button:hover { margin-bottom: 17px; }
 
#prize_column { float: none; margin-left: auto; margin-right: auto; }
 
 
#deal { width: 470px;
 display: block; font-size: 16px; margin-top: 5px; height: 43px; overflow: hidden; line-height: 38px; no-repeat; position: absolute; top: 10px; left: -3px; text-transform: uppercase; color: white; padding: 0 30px 0 20px; }
 
#deal strong { font-size: 23px; }
 
#deal #closed-bt { float: right; color: white; padding: 6px 8px; line-height: 16px; margin-top: 4px;repeat-x top left; border: 1px solid #3b6e22; text-transform: none; font-size: 14px; font-weight: bold;background:red; }
 
#deal #buynow-bt {float: right; color: white; padding: 6px 8px; line-height: 16px; margin-top: 4px;repeat-x top left; border: 1px solid #3b6e22; text-transform: none; font-size: 14px; font-weight: bold; }



#prize_column, #policies_column { height: 100%; background-color: white; border-bottom: 1px solid #cccccc; }
 
#policies_column { 
border:solid 1px;
border-color:silver;
color: #333333; float: left; width: 508px; margin-left: 0px; padding-left: 5px; padding-right: 5px; }
#policies_column dt { font-weight: bold; }
#policies_column dd { color: #666666; }
#policies_column .prize, #policies_column dd { margin-bottom: 20px; margin-left: 0; }
#policies_column p { margin-top: 0; margin-bottom: 5px; }
#policies_column { position: relative; padding-top: 25px; }
#policies_column h1 { padding: 0; margin-bottom: 10px; }
#group-deal-info { overflow: hidden; margin: 10px 0; }
#groupdeal-summary { clear: both; padding-top: 20px; margin-bottom: 10px; }
 #discount-value { margin: 0; padding: 0; list-style: none; overflow: hidden; }
#discount-value li { width: 100px; float: right;margin-right:30px; }
#discount-value li h3 { margin-bottom: 10px; }
#discount-value span { font-size: 18px; }
#timer1 { padding: 5px; background: #fff9d7; border: 1px solid #e2c822; }
 #progress { padding: 10px 0 0 0; margin-top: 10px; border-top: 1px solid #b6bfcf; }

#progress h2 { margin-bottom: 10px; }
 #progress-barmain { width: 500px; position: relative; height: 29px; background-color:#ccc;-moz-border-radius:20px; -webkit-border-radius:20px;border-radius:20px;margin-bottom: 10px;overflow:hidden; }

#progress-barmain div { display: block; height: 29px; width: 300px;-moz-border-radius:20px;-webkit-border-radius:20px;border-radius:20px;}
 #progress-bar div span { background: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/progress-bg.png'); ?>); no-repeat; display: block; height: 18px; }

</style> 



