
<input type='button' value='Print' onclick='printDiv();'>
<script>
function printDiv()
{
   var divToPrint=document.getElementById('coupon'); 
 newWin= window.open("");

   newWin.document.write(divToPrint.innerHTML);

  newWin.print();
  newWin.close();

}
</script>
<div id="coupon">
<div id="outer" >
    <div id="left">
        <h1><?php echo $data->coupon_deal->title; ?></h1>
<hr>

        <h2><?php echo $data->coupon_deal->description; ?></h2>

         <h3> Recipients</h3>
         <?php
          $fid=$data->buyer->fb_id;
         ?>   
         <img src="https://graph.facebook.com/<?= $fid ?>/picture">      
         <h4><?php echo $data->buyer->first_name."  ".$data->buyer->last_name; ?></h4>

         <h3> Expires on</h3>
          <h5><?php echo $data->coupon_deal->coupon_expiry; ?></h5>
           

         <h3> Fine Print</h3>
         <h5><?php echo $data->coupon_deal->fine_print; ?></h5>



        
    </div>
    <div id="right">
   <h1><?php echo "#".$data->id; ?></h1>
<hr>
 <?php
        $this->widget('application.modules.groupbuy.extensions.qrcode.QRCodeGenerator',array(
                 'data' => $data->id."<br/>".$data->buyer->first_name." ".$data->buyer->last_name,
                 'subfolderVar' => false,
                 'matrixPointSize' => 4,
                 'filename'=>'qrcode.png',
                 'filePath'=>Yii::app()->getModule("groupbuy")->getBasePath().'/assets/images/coupon-qrcode',
                // 'fileUrl'=>Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/coupon-qrcode'), 
                 )); 
         echo CHtml::image(Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/coupon-qrcode/qrcode.png'),$data->id,array('width'=>'100','height'=>'75')); 
        // echo "<img src='".Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/images/coupon-qrcode/qrcode.png')."' width='75' height='75'>";
 
    ?>
    <h2>Coupon status:<br></h2>
    <?php
        if($data->status==2)
         {
           echo "Active"; 
 
         } 
        else if($data->status==3) 
         { 
           echo "Redeemed"; 
         }
        else if($status->status==5) 
         {
          echo "Expired"; 
         } 
    ?>
<h3> Redeemable After:</h3>
         <h4><?php echo $data->coupon_deal->end_date; ?></h4>

         <h3> Redeem At:</h3>
          <h5> <?php echo $data->coupon_deal->address1."<br/>".$data->coupon_deal->address2."<br/>".$data->coupon_deal->website; ?></h5>
    
   
       
    </div>
</div>

<style>

 
 
#outer{
  font-family: "lucida grande", tahoma, verdana, arial, sans-serif; font-size: 11px; text-align: left;

    position:relative;
    width:505px;
    height:320px;
    left:10;
    top:10;
border: 3px dashed #ccc; 

}
#left{
    position:absolute;
    left:0;
    top:0;
    height:100%;
    width:50%;
    margin-left:10px;

}
#right{
    position:absolute;
    left:320px;
    top:0;
    height:100%;
    width:40%;

.coupon {
  padding: 10px;
  text-align: center;
  border: 3px dashed #ccc; 
}
hr {
 border: ;
 width: 100%;
height: 5px;
}

}


</style>
</div>

