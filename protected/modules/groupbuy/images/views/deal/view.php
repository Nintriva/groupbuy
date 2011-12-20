<?php
$this->breadcrumbs=array(
	//'Deals'=>array('index'),
        'Groupbuy Deals'=>array('/groupbuy/default/index'),
	$model->title,
);


?>
<?php 

$this->layout='deal';
//Yii::app()->layout='deal';

$this->widget('DealView',array('deal_id'=>$model->id));
/*
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'tipping_point',
		'retail_price',
		'discount_percentage',
		'discount_value',
		'description',
		'website',
		'address1',
		'address2',
		'deal_price',
		'max_available',
		'start_date',
		'end_date',
		'is_deal_on',
		'category',
		'advertiser',
	),
));
*/
 ?>
