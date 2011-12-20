<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'buyer-confirmation-code-excel_upload1-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),  
       // 'action'=>'/groupbuy/index.php?r=groupbuy/deal/UploadExcel1',   
)); 

$this->layout='no';
?>

	<!--<p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'excel1'); ?>
		<?php //echo $form->textField($model,'code'); 
                      echo "<br/>step1:".$form->fileField($model,'excel1');  
                ?>
		<?php echo $form->error($model,'excel1'); ?>
	</div>
         <div class="row">
		<?php //echo $form->labelEx($model,'is_full_upload'); ?>
		<?php //echo $form->textField($model,'code'); 
                      //echo "step2:".$form->radioButtonList($model,'is_full_upload',$model->getUploadOptions(),array('separator'=>'', 'labelOptions'=>array('style'=>'display:inline')));
                   ?>    
                <input id="BuyerConfirmationCode_is_full_upload_0" value="1" type="radio" name="BuyerConfirmationCode[is_full_upload]"  checked='checked'/>Full upload    
                <input id="BuyerConfirmationCode_is_full_upload_1" value="0" type="radio" name="BuyerConfirmationCode[is_full_upload]" />add to existing data  
               
		<?php echo $form->error($model,'is_full_upload'); ?>
	</div>


	<div class="row buttons">
		<?php echo "step3:".CHtml::submitButton('UPLOAD'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
