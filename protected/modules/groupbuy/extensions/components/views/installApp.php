
<style type="text/css">

a.btn {
  background-image:url("<?php echo Yii::app()->baseUrl; ?>/images/btn.png");
  background-repeat:no-repeat;
  overflow:hidden;
  display:block;
}
 a.btn.fb_install {
  background-position:0 -400px;
  width:58px;
  height:0;
  padding-top:19px;
  margin:9px 0 0 0;
} 
</style>

    <a href="http://www.facebook.com/add.php?api_key=<?php echo $this->api_key; ?>&pages" class="btn fb_install">Install on Facebook</a> 

  
