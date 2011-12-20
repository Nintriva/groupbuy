<?php
//echo "ok";

 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$this->dealDataProvider,
	'itemView'=>'_userDealView',
));

?>
