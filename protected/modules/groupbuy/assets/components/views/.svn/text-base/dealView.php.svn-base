
<?php

 echo Yii::app()->controller->module->registerCss('dealView.css');

//calling the images
?>
<?php 

 //echo CHtml::image(Yii::app()->controller->module->registerImage('deal.png'), "logo");


?>




    <div id="main-content" class="main-content contests_show_main contest_details"> 
 
	<div id="policies_column"> 
 
 
 
	    <div id="group-deal-info"> 
	      <div id="deal"> 
                   <!--http://wildfireapp.com/website/6/contests/140635/entries/new?preview_key=70a52eef49cf6373758b508c72660f84-->
	         <!--  <a href="#" id="buynow-bt">Buy now</a> --> 
                  <?php echo CHtml::link('Buy now',array('paypal/submitorder','deal_id'=>$this->deal->id),array('id'=>'buynow-bt')); ?> 
 
	        Deal: <strong><?php echo "$".($this->deal->retail_price-$this->deal->discount_value); ?></strong> 
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
 


