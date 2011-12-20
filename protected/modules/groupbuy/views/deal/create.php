<?php
$this->breadcrumbs=array(
	'Deals'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Deal', 'url'=>array('index')),
	array('label'=>'Manage Deal', 'url'=>array('admin')),
);
?>

<h1>Create Deal</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,)); ?>
