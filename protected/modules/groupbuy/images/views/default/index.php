<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<?php



echo CHtml::button('+ Create a Group buy Campaign', array('submit' => array('deal/create')));

$this->widget('UserGroupDeals',array('user_id'=>Yii::app()->user->id));

 //echo  Yii::app()->user->id;
?>
