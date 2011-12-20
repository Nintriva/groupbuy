<?php
$this->breadcrumbs=array(
	//'Deals'=>array('index'),
        'Groupbuy Deals'=>array('/groupbuy/default/index'),
	$model->title,
);


?>
<?php 

//$this->layout='deal';

if($model->published==0)
{
  echo " |".CHtml::link('X-Delete','#',array("submit"=>array('/groupbuy/deal/delete','id'=>$model->id),'confirm' => 'Are you sure?'));

  //Only if not expired     
  if((!$model->isExpired()&&$model->isDealAvailable())||$model->status==0)
  {
   echo " |".CHtml::link('Publish',array('/groupbuy/deal/publish','id'=>$model->id));
   echo " |".CHtml::link('Edit',array('/groupbuy/deal/update','id'=>$model->id));
  if($this->deal->image==null)  
   {
     echo CHtml::link("  |Upload an image",array('/groupbuy/image/image_upload','deal_id'=>$this->deal->id));
    
   }
 else
   {
     echo CHtml::link("  |DELETE IMAGE",array('/groupbuy/image/remove','deal_id'=>$this->deal->id));
 
   }
 }

}
else
{
 echo " |".CHtml::link('Unpublish',array('/groupbuy/deal/ConfirmUnPublish','id'=>$model->id));
}



//echo "|".CHtml::link('Go back',array('/groupbuy/default/index'));


//$this->widget('InstallApp',array('api_key'=>'241476069209769'));




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
