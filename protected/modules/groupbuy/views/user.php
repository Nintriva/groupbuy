<?php
/*
$this->breadcrumbs=array(
	'Facebook'=>array('/groupbuy/facebook'),
	'User',
);
*/

$this->layout='deal';
?>

<h1>This is the normal user view (NON LIKED AND NON ADMIN)</h1>

<?php

 $session=new CHttpSession;
 $session->open();




if(VerifiedFbPages::model()->isVerified($session['page_id'])==true)
  {
    //echo "<h1>This is the  admin view</h1>";
        
   $this->widget('PublishedDeals',array('page_id'=>$session['page_id']));
  
  //$this->widget('PublishedDeals',array('user_id'=>'admin'));
}
else
{

 echo "Not authorised access";
}

$session->close();
?>


