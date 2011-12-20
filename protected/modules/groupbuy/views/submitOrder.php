<?php
/*
$this->breadcrumbs=array(
	'Paypal'=>array('/groupbuy/paypal'),
	'SubmitOrder',
);*/
?>
<?php

$this->layout='deal';
?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'paypal-order-orderform-form',
	'enableAjaxValidation'=>true,
)); 


?>
<table style='border:solid 1px;border-color:silver;'>
<th colspan='2'>PAYPAL EXPRESSCHECKOUT FORM
<tr> <td>	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'paymentType'); ?>
		<?php echo $form->hiddenField($model,'paymentType'); ?>
		<?php echo $form->error($model,'paymentType'); ?>
	</div>

 <tr>	<div class="row">
	  <td>	<?php echo $form->labelEx($model,'L_NAME0'); ?>
	  <td>	<?php         echo $model->L_NAME0;    echo $form->hiddenField($model,'L_NAME0'); 
 
                ?>
		<?php echo $form->error($model,'L_NAME0'); ?>
	</div>

<tr>	<div class="row">
      <td>		<?php echo $form->labelEx($model,'L_AMT0'); ?>
      <td> <div id='price'><?php echo "$".$model->L_AMT0; ?> </div>
                        <?php  $form->hiddenField($model,'L_AMT0'); ?>
		<?php echo $form->error($model,'L_AMT0'); ?>
	</div>

<tr>	<div class="row">
        <td>	<?php echo $form->labelEx($model,'L_QTY0'); ?>
	<td>	<?php echo $form->textField($model,'L_QTY0',array('value'=>'1','onkeyup'=>'calculate_total();')); ?>(max:<?php 
                                                                                                           if($model->max_purchase_units>0) 
                                                                                                            echo $model->max_purchase_units;
                                                                                                           else 
                                                                                                            echo "0"; 
                                                                                                          ?>)
		<?php echo $form->error($model,'L_QTY0'); ?>
	</div>
<tr>
        <div class="row"> 
        <td><label>Total:*</label> 
        <td><div id='total'><?php echo "$".$model->L_AMT0;?></div> 
        </div> 

<tr>	<div class="row">
   <td>		<?php echo $form->labelEx($model,'currencyCodeType'); ?>
   <td> 		<?php echo $form->dropDownList($model,'currencyCodeType',$model->getCurrencyCodeOptions()); ?>
		<?php echo $form->error($model,'currencyCodeType'); ?>
	</div>




<tr>	<div class="row">
    <td>	<?php echo $form->labelEx($model,'PERSONNAME'); ?>
    <td>		<?php echo $form->textField($model,'PERSONNAME'); ?>
		<?php echo $form->error($model,'PERSONNAME'); ?>
	</div>

        
<tr>	<div class="row">
    <td>		<?php echo $form->labelEx($model,'SHIPTOSTREET'); ?>
    <td>		<?php echo $form->textField($model,'SHIPTOSTREET'); ?>
		<?php echo $form->error($model,'SHIPTOSTREET'); ?>
	</div>

<tr>	<div class="row">
   <td>		<?php echo $form->labelEx($model,'SHIPTOCITY'); ?> 
   <td>		<?php echo $form->textField($model,'SHIPTOCITY'); ?>
		<?php echo $form->error($model,'SHIPTOCITY'); ?>
	</div>

<tr>	<div class="row">
   <td>		<?php echo $form->labelEx($model,'SHIPTOSTATE'); ?>
   <td>		<?php echo $form->textField($model,'SHIPTOSTATE'); ?>
		<?php echo $form->error($model,'SHIPTOSTATE'); ?>
	</div>

<tr>	<div class="row">
    <td>		<?php echo $form->labelEx($model,'SHIPTOCOUNTRYCODE'); ?> 
    <td>		<?php //echo $form->textField($model,'SHIPTOCOUNTRYCODE');
                        echo $form->dropDownList($model,'SHIPTOCOUNTRYCODE',$model->getCountryList()); 
                ?>
		<?php echo $form->error($model,'SHIPTOCOUNTRYCODE'); ?>
	</div>

<tr>	<div class="row">
    <td>		<?php echo $form->labelEx($model,'SHIPTOZIP'); ?> 
    <td>		<?php echo $form->textField($model,'SHIPTOZIP'); ?>
		<?php echo $form->error($model,'SHIPTOZIP'); ?>
	</div>
     
     
<tr><td><td>	<div class="submit">
		<?php echo CHtml::submitButton('Order confirmed.PAY NOW'); ?>
	</div>
</table>

<?php $this->endWidget(); ?>

</div>

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
<script type='text/javascript'>
function calculate_total()
{

var qty=document.getElementById('PaypalOrder_L_QTY0').value;




var price=document.getElementById('price').innerHTML;
 price=price.substring(1);

if(qty==0||qty=="")
{
if(qty=="")
return;
alert("Invalid quantity");
return;
}

if(!checkforInteger(qty))
 {
  alert("Invalid quantity"); 
  return;
 }

document.getElementById('total').innerHTML='$'+(qty*price);
//document.getElementById('total_amt').innerHTML='$'+(qty*price);

//document.getElementById('payment_amt').value='$'+(qty*price);


}
function checkforInteger(value)
 {
        if (parseInt(value) != value)
	   return false;
        else 
           return true;
 }

</script>



