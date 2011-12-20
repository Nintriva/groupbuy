<div class="form">

<?php
if(!$model->isNewRecord)
{
$model->group_discount=$model->discount_percentage;

$action=array("deal/update","id"=>$model->id);
}
else
{

 //$model->group_discount=0;
$action='';
}


?>

<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'deal-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
        'action'=>$action,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        <div id='deal_demo' style='visibility:hidden;width:200px;position:absolute;left:800px;top:200px;border:solid 1px;'>
     
        </div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>256,'onkeyup'=>'show_demo();')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div> 
         <div class="row">
		<?php echo $form->labelEx($model,'is_free_coupon'); ?>
		<?php echo $form->radioButtonList($model,'is_free_coupon',$model->getDealOptions(),array('onclick'=>'change_mode(this.value);','separator'=>'', 'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'is_free_coupon'); ?>
	</div>  
        
       <script type='text/javascript'>
       /*load_mode();
      function load_mode()
      { 
      
       var mode=document.getElementById('Deal_is_free_coupon_1').checked; 
        if(mode)
         {
          change_mode(1);
         } 
        else
         {
           change_mode(0);

         }  
              
      }*/
      function change_mode(mode)
      {
          
       if(mode==1)
       {

   
        document.getElementById('Deal_retail_price').value=0;  
        document.getElementById('retail_price').style.display='none'; 
        document.getElementById('paypal').style.display='none';   
        document.getElementById('Deal_paypal_address').value='noaddress@paypal.com';        
 
       // document.getElementById('Deal_retail_price').type='hidden';   
      // document.getElementById('Deal_retail_price').label='hidden'; 

       }  

     else
       {
         document.getElementById('retail_price').style.display='block';  
         document.getElementById('paypal').style.display='block'; 
         document.getElementById('Deal_paypal_address').value='';        
    
         //document.getElementById('Deal_retail_price').type='text';    
       }  
    //   alert(mode);

      } 
       </script>
        <div id='retail_price'>  
        <div class="row">
		<?php echo $form->labelEx($model,'currency_code'); ?>
		<?php echo $form->dropDownList($model,'currency_code',$model->getCurrencyCodeOptions()); ?>
		<?php echo $form->error($model,'retail_price'); ?>
	</div>  
	<div class="row">
		<?php echo $form->labelEx($model,'retail_price'); ?>
		<?php echo $form->textField($model,'retail_price',array('size'=>6,'maxlength'=>6,'onkeyup'=>'calculate_discount();;')); ?>
		<?php echo $form->error($model,'retail_price'); ?>
	</div>
        </div>
       <div class="row">
		<?php echo $form->labelEx($model,'Group discount'); ?>
                <?php echo $form->textField($model,'group_discount',array('onkeyup'=>'calculate_discount();')); ?>

		<?php echo $form->dropDownList($model,'discount_mode',array('1'=>'Value in %','2'=>'Value in USD'),array('onchange'=>'calculate_discount();')); ?>
		<?php echo $form->error($model,'group_discount'); ?>
         <script type='text/javascript'>
          // deal_demo();
          function calculate_discount()
           {
                
              
             var retail_price=document.getElementById('Deal_retail_price').value; 
             var group_discount=document.getElementById('Deal_group_discount').value;
             var discount_mode=document.getElementById('Deal_discount_mode').value;   
            //alert(retail_price);
             // return;
               if(IsNumeric(retail_price)==false||IsNumeric(group_discount)==false)
                  {
                  // alert("not numeric or empty");
                   return;
                  }
              //alert("ok");
               
             
              //alert(discount_mode);
               if(discount_mode==1)
                 {
                   
                    
                  document.getElementById('Deal_discount_percentage').value=parseFloat(group_discount).toFixed(2);
                  document.getElementById('Deal_discount_value').value=parseFloat((group_discount/100)*retail_price).toFixed(2); 
 
                  document.getElementById('Deal_deal_price').value=parseFloat(retail_price-((group_discount/100)*retail_price)).toFixed(2); 
                 }
               else if(discount_mode==2)
                 {
                  document.getElementById('Deal_discount_percentage').value=parseFloat((group_discount/retail_price)*100).toFixed(2);
                  document.getElementById('Deal_discount_value').value=parseFloat(group_discount).toFixed(2);

                   document.getElementById('Deal_deal_price').value=parseFloat(retail_price-group_discount).toFixed(2); 

                 } 
             show_demo();
             
           }
            function IsNumeric(n)
           { 
             //  alert(n);
            var n2 = n;
            n = parseFloat(n);
            return (n!='NaN' && n2==n);
           }

          
          function show_demo()
          {
           
           
          // var demo=document.getElementById('deal_demo');
              // demo.style.width='100px'; 
               //demo.style.height='100px';
               //demo.style.border='solid 1px'; 
              document.getElementById('deal_demo').style.visibility='visible';  
           
             var title=document.getElementById('Deal_title').value; 
             var retail_price=document.getElementById('Deal_retail_price').value;
             var discount_percentage=document.getElementById('Deal_discount_percentage').value;
             var discount_value=document.getElementById('Deal_discount_value').value; 
             var deal_price=document.getElementById('Deal_deal_price').value;
             var tipping_point=document.getElementById('Deal_tipping_point').value;
             var max_available=document.getElementById('Deal_max_available').value;  
             document.getElementById('deal_demo').innerHTML="<h2><u>"+title+"</u></h2>Retail Price:$"+retail_price+"<br>Deal price:$"+deal_price+"<br>Discount :"+discount_percentage+"%<br>You Save:$"+discount_value+"<br>"+tipping_point+" people Needs to buy to get this offer.";   
                     
          }


         </script>
	</div>
       <div class="row">
		<?php //echo $form->labelEx($model,'discount_percentage'); ?>
		<?php echo $form->hiddenField($model,'discount_percentage',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'discount_percentage'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'discount_value'); ?>
		<?php echo $form->hiddenField($model,'discount_value',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'discount_value'); ?>
	</div>
        <div class="row">
		<?php //echo $form->labelEx($model,'deal_price'); ?>
		<?php echo $form->hiddenField($model,'deal_price',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'deal_price'); ?>
	</div>

       <div class="row">
		<?php echo $form->labelEx($model,'tipping_point'); ?>
		<?php echo $form->textField($model,'tipping_point',array('onkeyup'=>'show_demo();')); ?>
		<?php echo $form->error($model,'tipping_point'); ?>
       </div>
       <div class="row">
		<?php echo $form->labelEx($model,'max_available'); ?>
		<?php echo $form->textField($model,'max_available',array('onkeyup'=>'show_demo();')); ?>
		<?php echo $form->error($model,'max_available'); ?>
       </div>
        <div class="row">
		<?php echo $form->labelEx($model,'max_purchase_units'); ?>
		<?php echo $form->textField($model,'max_purchase_units'); ?>
		<?php echo $form->error($model,'max_purchase_units'); ?>
	</div>
       <div class="row">
		<?php echo $form->labelEx($model,'timezone'); ?>
		<?php echo $form->dropDownList($model,'timezone',$model->TimeZoneOptions()); ?>
		<?php echo $form->error($model,'timezone'); ?>
       </div> 

       <div class="row">
		<?php echo $form->labelEx($model,'start_date'); ?>
		<?php  //echo $form->textField($model,'start_date'); ?>
		
                <?php
                      
                    $this->widget('application.modules.groupbuy.extensions.timepicker.EJuiDateTimePicker',array(
    'model'=>$model,
    'attribute'=>'start_date',
    'options'=>array(
        'hourGrid' => 4,
        'hourMin' => 0,
        'hourMax' => 23,
        'dateFormat'=>'yy-mm-dd', 
        'timeFormat' => 'hh:mm:s',
        'changeMonth' => true,
        'changeYear' => false,
        ),
    ));                 
                
                 ?>
               <?php echo $form->error($model,'start_date'); ?>
	</div>
        <div class="row">
                <?php echo $form->checkBox($model,'auto_publish'); ?>
                <?php echo $form->label($model,'auto_publish'); ?> 
		<?php echo $form->error($model,'auto_publish'); ?>
	</div> 

	<div class="row">
		<?php echo $form->labelEx($model,'end_date'); ?>
		<?php  //echo $form->textField($model,'end_date');
                      
                     $this->widget('application.modules.groupbuy.extensions.timepicker.EJuiDateTimePicker',array(
    'model'=>$model,
    'attribute'=>'end_date',
    'options'=>array(
        'hourGrid' => 4,
        'hourMin' => 0,
        'hourMax' => 23,
        'dateFormat'=>'yy-mm-dd', 
        'timeFormat' => 'hh:mm:s',
        'changeMonth' => true,
        'changeYear' => false,
        //'ampm' => ( strpos(Yii::app()->locale->timeFormat, 'a') === false ? false : true),     
        ),
    ));  
                
                 

               
                 ?>
		<?php echo $form->error($model,'end_date'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'coupon_expiry'); ?>
		<?php  //echo $form->textField($model,'end_date');
                      
                     $this->widget('application.modules.groupbuy.extensions.timepicker.EJuiDateTimePicker',array(
    'model'=>$model,
    'attribute'=>'coupon_expiry',
    'options'=>array(
        'hourGrid' => 4,
        'hourMin' => 0,
        'hourMax' => 23,
        'dateFormat'=>'yy-mm-dd', 
        'timeFormat' => 'hh:mm:s',
        'changeMonth' => true,
        'changeYear' => false,
        //'ampm' => ( strpos(Yii::app()->locale->timeFormat, 'a') === false ? false : true),     
        ),
    ));  
                
                 

               
                 ?>
		<?php echo $form->error($model,'coupon_expiry'); ?>
	</div>

         <div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>   	

        <div class="row" id='paypal'>
		<?php echo $form->labelEx($model,'paypal_address'); ?>
		<?php echo $form->textField($model,'paypal_address'); ?>
		<?php echo $form->error($model,'paypal_address'); ?>
	</div> 
         <div class="row">
		<?php echo $form->labelEx($model,'days_in_todays_deal_after_exp'); ?>
		<?php echo $form->dropDownList($model,'days_in_todays_deal_after_exp',$model->daysInTodaysDealsAfterExp())." days of exp/soldout."; ?>
		<?php echo $form->error($model,'days_in_todays_deal_after_exp'); ?>
	</div>
      
        <div class="row">
          
             <div  onclick='show_features();' id="advanced_features" style="cursor:pointer;color:blue;">+Show Advanced features</div>

            <div id='confirmation_code' style="display:none">
             <br>Purchase Confirmation Code(s) (Optional)<br/>
             <input type='radio' name='offer_code' onclick='hide_upload_form();hide_upload_selection();' checked="checked" >Offer no confirmation codes after purchase is made<br>
             <input type='radio' name='offer_code' onclick='show_upload_form();show_upload_selection();'>Offer one confirmation code per buyer
            </div>
           <div id="upload_selection" style="display:none;">    
          <br/><br/>
              <input type='radio' name='same_code' onclick='hide_upload_form12();' checked="checked">Use same Code for Deal On and Off <br/>
              <input type='radio' name='same_code' onclick='show_upload_form12();'>Use different Code for Deal On and Off        
           </div>  

          <div id='upload_form' style="display:none;">
           
            <div class="row">
               <?php echo CHtml::link("Download sample",Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/excel/sample.xls'),array("target"=>'_blank'));?>
		<?php  echo $form->labelEx($model,'excel'); ?>
		<?php  echo "step1:".$form->fileField($model,'excel');  ?>
		<?php  echo $form->error($model,'excel'); ?>
                  
	        </div>
                 <div class="row">
                  <?php  //echo "step2:".$form->radioButtonList($model,'is_full_upload1',array('1'=>'Full upload','0'=>'add to existing data'),array('separator'=>'', 'labelOptions'=>array('style'=>'display:inline')));  
                ?>
                    step2:
                     <input id="Deal_is_full_upload_0" value="1" type="radio" name="Deal[is_full_upload]" checked="checked"/>Full upload
                     <input id="Deal_is_full_upload_1" value="0" type="radio" name="Deal[is_full_upload]" />add to existing data  
		<?php  echo $form->error($model,'is_full_upload'); ?>
               
                 </div>
                 <div class="row">
                      <?php echo "step3:".CHtml::submitButton('UPLOAD'); ?>
                 </div> 

          </div> 

          <div id='upload_form1' style="display:none;">
              <div class="row">
               <?php echo CHtml::link("Download sample",Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/excel/sample.xls'),array("target"=>'_blank'));?> 
		<?php  echo $form->labelEx($model,'excel1'); ?>
		<?php  echo "step1:".$form->fileField($model,'excel1');  ?>
		<?php  echo $form->error($model,'excel1'); ?>
                  
	        </div>
                 <div class="row">
                  <?php  //echo "step2:".$form->radioButtonList($model,'is_full_upload1',array('1'=>'Full upload','0'=>'add to existing data'),array('separator'=>'', 'labelOptions'=>array('style'=>'display:inline')));  
                ?>
                    step2:
                     <input id="Deal_is_full_upload1_0" value="1" type="radio" name="Deal[is_full_upload1]" checked="checked"/>Full upload
                     <input id="Deal_is_full_upload1_1" value="0" type="radio" name="Deal[is_full_upload1]" />add to existing data  
		<?php  echo $form->error($model,'is_full_upload1'); ?>
               
                 </div>
                 <div class="row">
                      <?php echo "step3:".CHtml::submitButton('UPLOAD'); ?>
                 </div>    
         
		<?php //echo $form->labelEx($model,'is_full_upload'); ?>
	</div>	

               <div id='upload_form2' style="display:none;" >
                    <div class="row">
                   <?php echo CHtml::link("Download sample",Yii::app()->assetManager->publish(Yii::getPathOfAlias('groupbuy').'/assets/excel/sample.xls'),array("target"=>'_blank'));?>
                <?php echo $form->labelEx($model,'excel2'); ?>
		<?php echo "step1:".$form->fileField($model,'excel2'); ?>
		<?php echo $form->error($model,'excel2'); ?>
                 
                    </div> 
                    <div class="row"> 
                    <?php //echo "step2:".$form->radioButtonList($model,'is_full_upload2',array('1'=>'Full upload','0'=>'add to existing data'),array('separator'=>'', 'labelOptions'=>array('style'=>'display:inline')));  
                ?>
                    step2:
                    <input id="Deal_is_full_upload2_0" value="1" type="radio" name="Deal[is_full_upload2]" checked="checked"/>Full upload
                    <input id="Deal_is_full_upload2_1" value="0" type="radio" name="Deal[is_full_upload2]" />add to existing data  
		<?php  echo $form->error($model,'is_full_upload2'); ?>
                   
                    </div>     
                    <div class="row">
                      <?php echo "step3:".CHtml::submitButton('UPLOAD'); ?>
                    </div>             
           
	      </div> <!--end form2-->
         
        </div>
       
         <script type='text/javascript'>
          function show_features()
          {
           
           if(document.getElementById('confirmation_code').style.display=="none")
            {
             document.getElementById('confirmation_code').style.display="block";
             document.getElementById('advanced_features').innerHTML="-Hide Advanced features";
            }
           else
            {
             document.getElementById('confirmation_code').style.display="none";
             document.getElementById('advanced_features').innerHTML="+Show Advanced features";
 
            }

          }
         function show_upload_selection(){
           document.getElementById('upload_selection').style.display="block";    
          }
         function hide_upload_selection(){
           document.getElementById('upload_selection').style.display="none";    

          } 
         function show_upload_form(){  
        document.getElementById('upload_form').style.display="block";
       

        }
        function hide_upload_form(){  
        document.getElementById('upload_form').style.display="none"; 

        }    
        function show_upload_form12(){  
        hide_upload_form();
        document.getElementById('upload_form1').style.display="block";
        document.getElementById('upload_form2').style.display="block";    

        }  
        function hide_upload_form12(){ 
        show_upload_form();
        document.getElementById('upload_form1').style.display="none";
        document.getElementById('upload_form2').style.display="none";   

        }
      /*
        function show_upload_form2(){  
        document.getElementById('upload_form2').style.display="block";
           

        }
        function hide_upload_form2(){  
        document.getElementById('upload_form2').style.display="none";
           

        } 
         */ 
              

         </script>
        
               

	<div class="row">
		<?php echo $form->labelEx($model,'website'); ?>
		<?php echo $form->textField($model,'website',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'website'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address1'); ?>
		<?php echo $form->textField($model,'address1',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'address1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address2'); ?>
		<?php echo $form->textField($model,'address2',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'address2'); ?>
	</div>
        <!--
	<div class="row">
		<?php echo $form->labelEx($model,'is_deal_on'); ?>
		<?php echo $form->textField($model,'is_deal_on'); ?>
		<?php echo $form->error($model,'is_deal_on'); ?>
	</div>
        -->
        <!--   
	<div class="row">
		<?php echo $form->labelEx($model,'category'); ?>
		<?php echo $form->textField($model,'category'); ?>
		<?php echo $form->error($model,'category'); ?>
	</div>
        -->
       <div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('size'=>60,'maxlength'=>400,'rows'=>'8','cols'=>'50')); ?>
		<?php echo $form->error($model,'description'); ?>
      </div>
      <div class="row">
		<?php echo $form->labelEx($model,'fine_print'); ?>
		<?php echo $form->textArea($model,'fine_print',array('size'=>60,'maxlength'=>1000,'rows'=>'8','cols'=>'50')); ?>
		<?php echo $form->error($model,'fine_print'); ?>
      </div>  
      
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
