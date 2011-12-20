<?php
$this->breadcrumbs=array(
	//'Deals'=>array('index'),
        'Groupbuy Deals'=>array('/groupbuy/default/index'),
         $deal->title=>array('/groupbuy/deal/view','id'=>$deal->id), 
	'buyers',
);

?>
<h2>Buyers of Deal:<?php echo $deal->title?></h2>
<?php

//$this->widget('Buyers',array('deal_id'=>$deal->id));

/*
  $couponDataProvider=new CArrayDataProvider($deal->coupons,array(
       'keyField' => 'id',     
       'pagination'=>array(
         'pageSize'=>10,   
          ),
     // 'totalItemCount' =>$this->user->review_count,      
 
   ));
*/ 
?>
<div class="search-form" style="display:block">
<?php 

$this->renderPartial('buyers_search',array(
	'model'=>$buyers_search,
));

 
 ?>
</div><!-- search-form -->
<?php 
//echo "Rout:".$this->route;
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'deal-grid', 
        'dataProvider'=>$couponDataProvider,
	//'dataProvider'=>$model->buyers_search(),
	//'filter'=>$model,
	'columns'=>array(
                 /*
                array(
                        'name'=>'Buyer',
                        'value'=>'$data->buyer->first_name." ".$data->buyer->last_name;',
                       ),
                  */
                array(
                      'name'=>'BuyerName',
                      'type'=>'raw',
                     // 'header'=>'Buyer',    
                      'value'=>'CHtml::link($data->buyer->first_name." ".$data->buyer->last_name,array("deal/buyerView","buyer_fb_id"=>$data->buyer->fb_id,"deal_id"=>$data->coupon_deal->id));',
                      

                    ), 
                 /*
                array(
			'class'=>'CButtonColumn',
                        'template' => '{test}',
                         'buttons' => array(
                         'test' => array(
                         'label' =>$data->buyer->first_name.' '.$data->buyer->last_name,
                         // 'click' => 'js:function() { alert("'.$model->cat_id.'"); return false;}',
                          'url'=>'Yii::app()->createUrl("groupbuy/deal/buyerView", array("buyer_fb_id"=>$data->buyer->fb_id,"deal_id"=>$data->coupon_deal->id))',

                          ),
                       ),
                      ),
                  */
		array(
                        'name'=>'Coupon id',
                        'value'=>'$data->id',
                       ),
               array(
                        'name'=>'Coupon Status',
                        'value'=>'$data->getCouponStatus($data->status);',
                       ),
               array(
                        'name'=>'Confirmation Code',
                        'value'=>'$data->getBuyerConfirmationCode();',
                       ),
              array(
			'class'=>'CButtonColumn',
                        'template' => '{test}',
                         'buttons' => array(
                         'test' => array(
                         'label' => 'Redeem',
                         // 'click' => 'js:function() { alert("'.$model->cat_id.'"); return false;}',
                          'url'=>'Yii::app()->createUrl("groupbuy/coupon/redeem", array("id"=>$data->id))',

                          ),
                       ),


 		  ),		
		 
		/*
		'description',
		'website',
		'address1',
		'address2',
		'deal_price',
		'max_available',
		'start_date',
		'end_date',
		'is_deal_on',
		'category',
		'advertiser',
		*/
		/*array(
			'class'=>'CButtonColumn',
		),*/
	),
));

?>
