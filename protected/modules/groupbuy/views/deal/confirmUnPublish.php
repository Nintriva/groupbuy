<?php
$this->breadcrumbs=array(
	//'Deals'=>array('index'),
        'Groupbuy Deals'=>array('/groupbuy/default/index'),
         $deal->title=>array('/groupbuy/deal/view','id'=>$deal->id), 
	'Confirm Unpublish',
);

?>
<h2>Confirm your action to unpublish the  Deal:<?php echo $deal->title?></h2>

<h4></h4>

Note:If You Unpublish the deal,the deal will be off,and refund will be made to previous buyers.
<table style='border:solid 1px;width:400px;'>

<tr><th>Free boughts<th>Paid boughts
<tr><td><?php echo $deal->getFreeBoughtCount(); ?><td><?php echo  $deal->getPaidBoughtCount(); ?>
<tr><td>Do you want to Proceed?
<tr><td><div class="submit"><?php echo CHtml::link("PROCEED",array('/groupbuy/deal/Refund','id'=>$deal->id));?></div><td><div class="submit"><?php echo CHtml::link("CANCEL",array('/groupbuy/deal/view','id'=>$deal->id));?></div>
</table>


<style>	
body { font-family: "lucida grande", tahoma, verdana, arial, sans-serif; font-size: 15px; text-align: left; }

	.table
	{
	background:#333;

	}
	.table ul
	{
	float:left;
	margin:0;
	padding:0;
	border:0px solid #C9C9C9;
	}
	.table ul li
	{
	list-style:none;
	padding:5px 10px;
	}
	.table ul li.title
	{
	font-weight:bold;
	background:#000;
	color:#fff;
	}
	.table ul li.even
	{
	background:#fff
	}
	.table ul li.odd
	{
	background:#FFFFE6
	}
	
	/* tutorial */

	input, textarea { 
		padding: 9px;
		border: solid 2px #E5E5E5;
		outline: 0;
		font: normal 13px/100% Verdana, Tahoma, sans-serif;
		width: 150px;
		background: #FFFFFF url('bg_form.png') left top repeat-x;
		background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #EEEEEE), to(#FFFFFF));
		background: -moz-linear-gradient(top, #FFFFFF, #EEEEEE 1px, #FFFFFF 25px);
		box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
		-moz-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
		-webkit-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
		}

	textarea { 
		width: 400px;
		max-width: 400px;
		height: 150px;
		line-height: 150%;
		}

	input:hover, textarea:hover,
	input:focus, textarea:focus { 
		border-color: #C9C9C9; 
		-webkit-box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 8px;
		}

	.form label { 
		margin-left: 10px; 
		color: #999999; 
		}

	.submit input {
		width: auto;
		padding: 9px 15px;
		background: #617798;
		border: 0;
		font-size: 14px;
		color: #FFFFFF;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		cursor: pointer;
		}
        .errorSummary
          {
           font-size:10px;
           color:red; 
          }
         .errorMessage
          {
           font-size:10px;
           color:red; 
          }
	</style>
