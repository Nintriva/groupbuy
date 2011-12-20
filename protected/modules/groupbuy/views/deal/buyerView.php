<?php
$this->breadcrumbs=array(
	//'Deals'=>array('index'),
        'Groupbuy Deals'=>array('/groupbuy/default/index'),
         $deal->title=>array('/groupbuy/deal/view','id'=>$deal->id), 
        'Buyers' =>array('/groupbuy/deal/seeBuyers','id'=>$deal->id),
         $buyer->first_name.' '.$buyer->last_name,
	
);

$this->widget("BuyerView",array('buyer_fb_id'=>$buyer->fb_id,'deal_id'=>$deal->id));

?>
