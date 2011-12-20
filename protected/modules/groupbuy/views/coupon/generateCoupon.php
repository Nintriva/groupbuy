<?php
$this->breadcrumbs=array(
	'Coupon'=>array('/groupbuy/coupon'),
	'GenerateCoupon',
);?>
<?php

$this->layout='deal';
?>

<?php
 $session=new CHttpSession; 
                 
 $session->open();

//$this->widget('GenerateCoupon',array('deal'=>$deal,'coupon_id'=>$coupon_id));

$this->widget('BuyerCoupons',array('buyer_fb_id'=>$session['me']['id']));
//echo "ok";

 $session=new CHttpSession; 
                 
 $session->open();
 
unset($session['token']);
unset($session['PayerID']);
unset($session['coupon_deal_id']);

?>
