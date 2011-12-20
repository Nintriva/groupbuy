<?php
$this->breadcrumbs=array(
	//'Deals'=>array('index'),
        'Groupbuy Deals'=>array('/groupbuy/default/index'),
	$deal->title=>array('/groupbuy/deal/view','id'=>$deal->id),
	'Upload Image',
);



?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'image-image_upload-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),    
)); 

$this->layout='main';
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php //echo $form->textField($model,'image');
                      echo $form->fileField($model,'image');  
                ?>
		<?php echo $form->error($model,'image'); ?>
	</div>


	<div class="row buttons">
                <?php echo CHtml::button('Cancel', array('submit' => array('/groupbuy/deal/view','id'=>$deal->id))); ?>  
		<?php echo CHtml::submitButton('UPLOAD'); ?>
         
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
