 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title> Deals Frame</title> 
 
 
<style type="text/css"> 
 
 
#main-content { margin: 0px; }
 
.main-content { position: relative; background: #eeeeee; padding: 30px 0; border-top: 1px solid #cccccc; overflow: hidden; width: 760px; }
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
 
 
#deal { width: 272px; display: block; font-size: 16px; margin-top: 5px; height: 43px; overflow: hidden; line-height: 38px; background: url("<?php echo Yii::app()->baseUrl; ?>/images/deal.png") no-repeat; position: absolute; top: 10px; left: -3px; text-transform: uppercase; color: white; padding: 0 30px 0 20px; }
 
#deal strong { font-size: 23px; }
 
 
#deal #buynow-bt { float: right; color: white; padding: 6px 8px; line-height: 16px; margin-top: 4px; background: url("<?php echo Yii::app()->baseUrl; ?>/images/bt.png") repeat-x top left; border: 1px solid #3b6e22; text-transform: none; font-size: 14px; font-weight: bold; }
#prize_column, #policies_column { height: 100%; background-color: white; border-bottom: 1px solid #cccccc; }
 
#policies_column { color: #333333; float: left; width: 300px; margin-left: 40px; padding-left: 15px; padding-right: 15px; }
#policies_column dt { font-weight: bold; }
#policies_column dd { color: #666666; }
#policies_column .prize, #policies_column dd { margin-bottom: 20px; margin-left: 0; }
#policies_column p { margin-top: 0; margin-bottom: 5px; }
#policies_column { position: relative; padding-top: 25px; }
#policies_column h1 { padding: 0; margin-bottom: 10px; }
#group-deal-info { overflow: hidden; margin: 10px 0; }
#groupdeal-summary { clear: both; padding-top: 30px; margin-bottom: 10px; }
 #discount-value { margin: 0; padding: 0; list-style: none; overflow: hidden; }
#discount-value li { width: 100px; float: left; }
#discount-value li h3 { margin-bottom: 10px; }
#discount-value span { font-size: 18px; }
#timer { padding: 5px; background: #fff9d7; border: 1px solid #e2c822; }
 #progress { padding: 10px 0 0 0; margin-top: 10px; border-top: 1px solid #b6bfcf; }
#progress h2 { margin-bottom: 10px; }
 #progress-bar { width: 300px; position: relative; height: 29px; background: url("<?php echo Yii::app()->baseUrl; ?>/images/progress-bg.png") no-repeat; margin-bottom: 10px; }
#progress-bar div { display: block; height: 18px; width: 286px; position: absolute; top: 5px; left: 7px; }
 #progress-bar div span { background: url("progress-bar.png") no-repeat; display: block; height: 18px; }
</style> 
 
</head> 
 
<body> 
	
	
    <div id="main-content" class="main-content contests_show_main contest_details"> 
 
	<div id="policies_column"> 
 
 
 
	    <div id="group-deal-info"> 
	      <div id="deal"> 
                   <!--http://wildfireapp.com/website/6/contests/140635/entries/new?preview_key=70a52eef49cf6373758b508c72660f84-->
	          <a href="#" id="buynow-bt">Buy now</a> 
 
	        Deal: <strong><?php echo "$".$this->deal->discount_value; ?></strong> 
	      </div> <!--deal --> 
	      <div id="groupdeal-summary"> 
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
	          <h2 id="timer"> 
                   Time left to buy:
                   <?php
$epoch_1=strtotime($this->deal->end_date);
$epoch_2=mktime();

$diff_seconds  = $epoch_1 - $epoch_2;
$diff_weeks    = floor($diff_seconds/604800);
$diff_seconds -= $diff_weeks   * 604800;
$diff_days     = floor($diff_seconds/86400);
$diff_seconds -= $diff_days    * 86400;
$diff_hours    = floor($diff_seconds/3600);
$diff_seconds -= $diff_hours   * 3600;
$diff_minutes  = floor($diff_seconds/60);
$diff_seconds -= $diff_minutes * 60;

                    echo  $diff_days." days ".$diff_hours." hr ".$diff_minutes." min ".$diff_seconds." sec";
                    
                    /*  $time_one = new DateTime();
                     $time_two = new DateTime($this->deal->end_date);

                     $difference = $time_one->diff($time_two);
                     echo $difference->format('%D days %h hours %i minutes %s seconds');
                      */
                    ?>
	             
	          </h2> 
 
	          <div id="progress-bar"> 
	            <div> 
	              <span style="width: 0%;"></span> 
	            </div> 
	          </div> 
 
	          <h2> 
 
	              0 bought, <?php echo $this->deal->tipping_point;  ?> more needed to get this deal
 
	          </h2> 
	        </div> 
 
	    </div> 
 
 
	</div> 
	
	</div> 
 
 

</body> 
 
</html>

