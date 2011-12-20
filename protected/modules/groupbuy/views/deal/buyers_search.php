<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'buyers-search-buyers_search-form',
	'enableAjaxValidation'=>false,
        //'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get', 
)); ?>

	
        <div class="row">
		<?php //echo $form->label($model,'first_name'); ?>
		<?php //echo $form->textField($model,'first_name'); ?>
	</div>
        <div class="row">
		<?php echo $form->label($model,'coupon_id'); ?>
		<?php echo $form->textField($model,'coupon_id'); ?>
	</div>
       
       <div class="row">
		<?php //echo $form->label($model,'confirmation_code'); ?>
		<?php //echo $form->textField($model,'confirmation_code')."(Optional)"; ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('SEARCH'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
