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
<style>	
body { font-family: "lucida grande", tahoma, verdana, arial, sans-serif; font-size: 15px; text-align: left; }

	.table
	{
	background:#333;

	}
	.table ul
	{
	float:left;
	margin:0;
	padding:0;
	border:0px solid #C9C9C9;
	}
	.table ul li
	{
	list-style:none;
	padding:5px 10px;
	}
	.table ul li.title
	{
	font-weight:bold;
	background:#000;
	color:#fff;
	}
	.table ul li.even
	{
	background:#fff
	}
	.table ul li.odd
	{
	background:#FFFFE6
	}
	
	/* tutorial */

	input, textarea { 
		padding: 9px;
		border: solid 1px #E5E5E5;
		outline: 0;
		font: normal 13px/100% Verdana, Tahoma, sans-serif;
		width: 120px;
		background: #FFFFFF url('bg_form.png') left top repeat-x;
		background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #EEEEEE), to(#FFFFFF));
		background: -moz-linear-gradient(top, #FFFFFF, #EEEEEE 1px, #FFFFFF 25px);
		box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
		-moz-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
		-webkit-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
		}

	textarea { 
		width: 400px;
		max-width: 400px;
		height: 150px;
		line-height: 150%;
		}

	input:hover, textarea:hover,
	input:focus, textarea:focus { 
		border-color: #C9C9C9; 
		-webkit-box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 8px;
		}

	.form label { 
		margin-left: 10px; 
		color: #999999; 
		}

	.submit input {
		width: auto;
		padding: 9px 15px;
		background: #617798;
		border: 0;
		font-size: 14px;
		color: #FFFFFF;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		cursor: pointer;
		}
	</style>

</head>

<body>

<div class="container" id="page">

	<?php echo $content; ?>


</div><!-- page -->

</body>
</html>
