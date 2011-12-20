<?php
$this->layout='deal';
?>
<div align="center">
<table style='border:solid 1px;height:200px;width:400px;'>
<th colspan='2' align='center'>Confirm Your payment?
<tr><td align='center'>
 <div  onclick='dopayment();' id="confirm" style="cursor:pointer;color:blue;">CONFIRM</div>
<?php  
//echo CHtml::link("CONFIRM",array('paypal/DoPayment'),array('onclick'=>'dopayment(); return false;','id'=>'confirm'));
//echo CHtml::link("CONFIRM",array('paypal/DoPayment'));

?><td align='center'><?php  echo CHtml::link("CANCEL",array('paypal/cancelPayment'));?>
</table>
<?php
echo "

<script type='text/javascript'>
var i=0;
function dopayment()
{
  if(i==1)
   {
      
     return;
   }
  document.getElementById('confirm').style.color='silver';
  document.getElementById('confirm').style.cursor='';
   i++;
 document.getElementById('confirm').disabled=true;

 window.location.href='".Yii::app()->createUrl('groupbuy/paypal/DoPayment')."';

}
</script> 

";

?>
</div>
