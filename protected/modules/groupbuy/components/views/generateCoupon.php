
<?php
$session=new CHttpSession; 
$session->open();


?>

 
<body> 
<div id="outer">
    <div id="left">
        <h1><?php echo $this->deal->title; ?></h1>
<hr>

        <h2><?php echo $this->deal->description; ?></h2>

         <h3> Recipients</h3>
         <h4><?php echo $session['me']['first_name']."  ".$session['me']['last_name']; ?></h4>

         <h3> Expires on</h3>
          <h5><?php echo $this->deal->coupon_expiry; ?></h5>
           

         <h3> Fine Print</h3>
         <h5><?php echo $this->deal->fine_print; ?></h5>



        
    </div>
    <div id="right">
   <h1><?php echo "#".$this->coupon_id; ?></h1>
<hr>
    <h2><br></h2>
<h3> Redeemable After:</h3>
         <h4><?php echo $this->deal->end_date; ?></h4>

         <h3> Redeem At:</h3>
          <h5> <?php echo $this->deal->address1."<br/>".$this->deal->address2."<br/>".$this->deal->website; ?></h5>
    
       
    </div>
</div>
</body>
<?php

$session->close();
?>
<style>


body { font-family: "lucida grande", tahoma, verdana, arial, sans-serif; font-size: 11px; text-align: left; }

 
#outer{
    position:relative;
    width:550px;
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

