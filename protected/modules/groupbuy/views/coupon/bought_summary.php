<?php    
 $session=new CHttpSession;
 $session->open();
$this->layout='deal';
?>
<table cellpadding='10' align='center'>
<th>Thank you for buying the deal   <?php echo " ".$deal->title;?> !!!
<tr><td>Your coupon is in pendind now Since the deals needs <?php echo " ".($deal->tipping_point-$deal->getBoughtCount())." "; ?> more needs to tip.
<tr><td>Now You bougt  <?php echo " ".$deal->getUserBoughtCount($session['me']['id'])." "; ?>  coupons of this deal.
<tr><td>The deal have <?php echo " ".$deal->getBoughtCount()." ";?> boughts <?php echo " ".($deal->tipping_point-$deal->getBoughtCount())." "; ?> more needs to tip.
<tr><td style='font-size:12px;' align='center'><hr>Find your coupons at "Mycoupons" when the deal reaches tipping point.

</table>
