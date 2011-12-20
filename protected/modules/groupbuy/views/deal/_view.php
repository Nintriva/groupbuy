<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tipping_point')); ?>:</b>
	<?php echo CHtml::encode($data->tipping_point); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('retail_price')); ?>:</b>
	<?php echo CHtml::encode($data->retail_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discount_percentage')); ?>:</b>
	<?php echo CHtml::encode($data->discount_percentage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discount_value')); ?>:</b>
	<?php echo CHtml::encode($data->discount_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('website')); ?>:</b>
	<?php echo CHtml::encode($data->website); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address1')); ?>:</b>
	<?php echo CHtml::encode($data->address1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address2')); ?>:</b>
	<?php echo CHtml::encode($data->address2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deal_price')); ?>:</b>
	<?php echo CHtml::encode($data->deal_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_available')); ?>:</b>
	<?php echo CHtml::encode($data->max_available); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_date')); ?>:</b>
	<?php echo CHtml::encode($data->end_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_deal_on')); ?>:</b>
	<?php echo CHtml::encode($data->is_deal_on); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::encode($data->category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('advertiser')); ?>:</b>
	<?php echo CHtml::encode($data->advertiser); ?>
	<br />

	*/ ?>

</div>