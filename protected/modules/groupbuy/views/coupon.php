<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
   <!--
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
  <!--
  
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
   -->
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>


<div id="header">

 <?php 

 $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Today\'s Deals', 'url'=>array("/groupbuy/facebook/switchUser")),
                                array('label'=>'Recent Deals','url'=>array("/groupbuy/facebook/recentDeals")), 
				array('label'=>'How It Works','url'=>array("")), 
				array('label'=>'Change City', 'url'=>array("")), 
				array('label'=>'My Coupons', 'url'=>array("/groupbuy/facebook/userCoupons")), 
				
			),
		));
   
  ?>


      
</div>
	<div id="content"> 
         <?php echo $content; ?>
         
        </div>
	
<div id="footer">footer here</div>

</body>
<style type="text/css">
body  #header,#footer{
width:520px;
overflow:hidden;
margin:0; padding:0; border:0;
background-color: #fff;
font-family: "Lucida Grande", "Times New Roman", Times, serif;



}


 #header li {

float: left;
}

 #header ul {
list-style-type: none;
background-image: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/navi_bg.png'); ?>);
height: 80px;
margin: 0px;
padding:0px;
}

 #header ul a {
background-image: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/navi_bg_divider.png'); ?>);
background-repeat: no-repeat;
background-position: right;
padding-right: 5px;
padding-left: 5px;
display: block;
line-height: 80px;
text-decoration: none;
font-size: 15px;
color: #fff;
}

#header ul li a:hover{
background: url(<?php echo Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/navi_bg1.png'); ?>);
color:white;
}


#content
{
	
	height:500px;
        width:520px;
      /*   text-align:center; */
	background-color:#e6e6fa;
	
	
}


#footer
{
	
	height:30px;
	text-align:center;
	background-color:#bebebe;
	
	
	
	
}

</style>
</html>
