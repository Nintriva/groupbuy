<?php
 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$this->couponDataProvider,
	'itemView'=>'_couponView',
        'ajaxUpdate'=>false,
));

?>
