<?php
 $session=new CHttpSession;
 $session->open();

$this->layout='coupon';


//echo "uid1:".$session['me']['id'];
//echo "<br/>uid2:".$session['signedRequest']['user_id'];
//echo "<br/>page id1:".$session['page_id'];
//echo "<br/>page id2".$session['signedRequest']['page']['id'];
$this->widget('Coupons',array('buyer_fb_id'=>$session['me']['id'],'page_id'=>$session['page_id']));


?>
