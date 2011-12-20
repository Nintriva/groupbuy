<?php
$this->breadcrumbs=array(
	//'Deals'=>array('index'),
        'Groupbuy Deals'=>array('/groupbuy/default/index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Deal', 'url'=>array('index')),
	array('label'=>'Create Deal', 'url'=>array('create')),
	array('label'=>'View Deal', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Deal', 'url'=>array('admin')),
);
?>

<h1>Update Deal <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
