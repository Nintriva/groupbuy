<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<?php
            
         $criteria=new CDbCriteria;
         $criteria->select='authcode';

         $criteria->condition='advertiser=:advertiser';
         $criteria->params=array(':advertiser'=>Yii::app()->user->id); 

echo "<div style='font-size:20px;font-family:lucida grande;'>Your auth code:".GroupbuyAuthcode::model()->find($criteria)->authcode."</div>";


$this->widget('InstallApp',array('api_key'=>Yii::app()->controller->module->app_id));

echo CHtml::button('+ Create a Group buy Campaign', array('submit' => array('deal/create')));

$this->widget('UserGroupDeals',array('user_id'=>Yii::app()->user->id));

 //echo  Yii::app()->user->id;
?>
