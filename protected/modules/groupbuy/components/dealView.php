
<?php

$tz=$this->deal->getTimeZone();
date_default_timezone_set($tz[$this->deal->timezone]); 

// echo Yii::app()->controller->module->registerCss('dealView.css');
?>
<?php 

 //echo CHtml::image(Yii::app()->controller->module->registerImage('deal.png'), "logo");


?>


<?php
            
echo "<br/>Free coupons available:".$this->deal->free_coupons;

 
?>

    <div id="main-content" class="main-content contests_show_main contest_details"> 
     
	<div id="policies_column"> 
             <?php 
            if($this->deal->image!=null)
            {
          echo "<b/><br/><br/><img src='".Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/deal/'.$this->deal->image)."' width='300' height='200' title='".$this->deal->title."'>";
            }
       ?>
 
 
	    <div id="group-deal-info"> 
              
	      <div id="deal"> 
                   <!--http://wildfireapp.com/website/6/contests/140635/entries/new?preview_key=70a52eef49cf6373758b508c72660f84-->
	         <!--  <a href="#" id="buynow-bt">Buy now</a> --> 
                    

                  <?php
                         //ALLLOW USER TO BUY IFF THE DEAL IS NOT EXPIRED AND IS NOT YET REACHED THE MAX_AVAILABLE UNITS
                     if($this->facebook_view==true)  
                       {

                          //FACEBOOK VIEW

                        if($this->deal->isDealAvailable())
                      // if(mktime()<strtotime($this->deal->end_date)&&$this->deal->coupons_count<$this->deal->max_available)    
                         {
                          if($this->deal->free_coupons==0) 
                           echo "<div id='bt'>".CHtml::link('Buy now',array('paypal/submitorder','deal_id'=>$this->deal->id),array('id'=>'buynow-bt'))."</div>";
                          else
                           echo "<div id='bt'>".CHtml::link('Buy now',array('coupon/freeCoupon','deal_id'=>$this->deal->id),array('id'=>'buynow-bt'))."</div>";  
                         }
                       else
                         {

                          echo CHtml::link('DEAL CLOSED',array(''),array('id'=>'buynow-bt','style'=>'background:red;'));   
                         }
                      }
                     else
                      {
                         //BACKEND VIEW                        
 
                         if($this->deal->isDealAvailable())  
                         {                          
                           echo CHtml::link('Buy now',array('','id'=>$this->deal->id),array('id'=>'buynow-bt'));  
                         }
                       else
                         {

                          echo CHtml::link('DEAL CLOSED',array('','id'=>$this->deal->id),array('id'=>'buynow-bt','style'=>'background:red;'));   
                         }

                      
   
                      } 
                   ?> 
 
	        Deal: <strong><?php echo "$".($this->deal->deal_price); ?></strong> 
	      </div> <!--deal --> 
              
	      <div id="groupdeal-summary"> 
                 <?php  echo "<h3>".$this->deal->title."</h3>"; ?>            
	        <h3>Group Deal Summary</h3> 
	        <p> 
                  <?php echo $this->deal->description; ?>
	          
	        </p> 
	        <ul id="discount-value"> 
	          <li> 
	            <h3>Value</h3> 
	            <span> 
	              <?php echo "$".$this->deal->retail_price; ?>
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
	               <?php echo "$".$this->deal->discount_value; ?>
	            </span> 
	          </li> 
	        </ul> 
	      </div> 
 
	        <div id="progress">
 
	          <h2 id="timer1"> 
                   <?php


  if($this->deal->isDealAvailable())
 // if(mktime()<strtotime($this->deal->end_date)&&$this->deal->coupons_count<$this->deal->max_available)
 {
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

 }
else
  {

    echo  "Time left to buy:0 days 0 hr 0 min 0 sec";

  }

                    
                    /*  $time_one = new DateTime();
                     $time_two = new DateTime($this->deal->end_date);

                     $difference = $time_one->diff($time_two);
                     echo $difference->format('%D days %h hours %i minutes %s seconds');
                      */
                    ?>
	             
	          </h2> 
 
	          <div id="progress-bar"> 
	            <div> 
                        <?php                     
                /*
                
                $this->widget('zii.widgets.jui.CJuiProgressBar', array(
                      'value'=>$this->deal->coupons_count,
                        // additional javascript options for the progress bar plugin
                      'options'=>array(
                       'max'=>$this->deal->max_available,
                      
                       'change'=>'js:function(event, ui) {}',
                       ),
                         'htmlOptions'=>array(
                         'style'=>'height:20px;'
                       ),
                     ));
                   */ 

                      ?>
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
                                      echo "DEAL SOLD OUT";
                                    }
                                    else
                                     {
                                        //tipping point is not reached
                                     echo "DEAL EXPIRED";   //Cancel all payments 
                                     
 
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

                                                  echo "DEAL SOLD OUT BEFORE EXPIRY.";                                      
   
                                               }     

                                             else if($this->deal->isDealOn())
                                               {
                                                    //tipping point reached.THE DEAL IS ON
                                                   echo $this->deal->coupons_count." bought,The DEAL IS ON";
                                                   echo "<br/> Tipped at ".$this->deal->tipped_at." with ".$this->deal->tipping_point." bought.";     

                                               }
 
                                      }
                                    else
                                      {
                                        //Not yet tipped. 
                                          echo $this->deal->coupons_count." bought,".($this->deal->tipping_point-$this->deal->coupons_count)." more needed to get this deal";

                                      }   
 
                                                                
                                       
 

                                 }
     
                                       
                               
                            
  
                      ?>  
 
	             
 
	          </h2> 
	        </div> 
 
	    </div> 
 
 
	</div> 
	
	</div> 
<style type='text/css'>

 
#main-content { margin: 0px; }
 
.main-content { position: relative; background: #eeeeee; padding: 10px 0; border-top: 1px solid #cccccc; overflow: hidden; width: 400px; }
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
 
 
#deal { width: 272px; display: block; font-size: 16px; margin-top: 5px; height: 43px; overflow: hidden; line-height: 38px; background: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/deal.png'); ?>); no-repeat; position: absolute; top: 10px; left: -3px; text-transform: uppercase; color: white; padding: 0 30px 0 20px; }
 
#deal strong { font-size: 23px; }
 
 
#deal #buynow-bt { float: right; color: white; padding: 6px 8px; line-height: 16px; margin-top: 4px; background: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/bt.png'); ?>); repeat-x top left; border: 1px solid #3b6e22; text-transform: none; font-size: 14px; font-weight: bold; }
#prize_column, #policies_column { height: 100%; background-color: white; border-bottom: 1px solid #cccccc; }
 
#policies_column { color: #333333; float: left; width: 300px; margin-left: 40px; padding-left: 25px; padding-right: 25px; }
#policies_column dt { font-weight: bold; }
#policies_column dd { color: #666666; }
#policies_column .prize, #policies_column dd { margin-bottom: 20px; margin-left: 0; }
#policies_column p { margin-top: 0; margin-bottom: 5px; }
#policies_column { position: relative; padding-top: 25px; }
#policies_column h1 { padding: 0; margin-bottom: 10px; }
#group-deal-info { overflow: hidden; margin: 10px 0; }
#groupdeal-summary { clear: both; padding-top: 20px; margin-bottom: 10px; }
 #discount-value { margin: 0; padding: 0; list-style: none; overflow: hidden; }
#discount-value li { width: 100px; float: left; }
#discount-value li h3 { margin-bottom: 10px; }
#discount-value span { font-size: 18px; }
#timer1 { padding: 5px; background: #fff9d7; border: 1px solid #e2c822; }
 #progress { padding: 10px 0 0 0; margin-top: 10px; border-top: 1px solid #b6bfcf; }

#progress h2 { margin-bottom: 10px; }
 #progress-bar { width: 310px; position: relative; height: 29px; background: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/progress-bg.png'); ?>); no-repeat; margin-bottom: 10px; }
#progress-bar div { display: block; height: 18px; width: 286px; position: absolute; top: 5px; left: 7px; }
 #progress-bar div span { background: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/progress-bg.png'); ?>); no-repeat; display: block; height: 18px; }
</style> 



