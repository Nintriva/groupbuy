<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo "Code:".$code;
               // echo  "<br/>File:".$file;
                //echo  "<br/>Source:".$source;   
                //echo  "<br/>trace:".$trace;
                //echo  "<br/>Type:".$type;
                 // echo  "<br/>Line:".$line;
 ?></h2>

<div class="error">
<?php echo  CHtml::encode($message); ?>
</div>
