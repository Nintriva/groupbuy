<?php
$this->breadcrumbs=array(
	'Deals'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Deal', 'url'=>array('index')),
	array('label'=>'Create Deal', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('deal-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Deals</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'deal-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
		'tipping_point',
		'retail_price',
		'discount_percentage',
		'discount_value',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
