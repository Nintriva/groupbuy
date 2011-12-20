

<?php

/*
 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$this->buyerDataProvider,
	'itemView'=>'_couponBuyerView',
      
          
));
*/

 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'deal-grid', 
        'dataProvider'=>$this->couponDataProvider,
	//'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(

                array(
                        'name'=>'Buyer',
                        'value'=>'$data->buyer->first_name." ".$data->buyer->last_name;',
                       ),
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
		array(
			'class'=>'CButtonColumn',
		),
	),
));
/*

 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'buyers-grid',
	//'dataProvider'=>$this->model->search(),
        'dataProvider'=>$this->couponDataProvider,
	//'filter'=>$this->model,
        'summaryText'=>'{count} record(s) found', 
        //'selectableRows'=>2,
	'columns'=>array(
		//'parent_id',
		//'cat_id',
		  array(
                        'name'=>'Buyer',
                        'value'=>'$data->buyer->first_name',
                       ),
             /*  array(
                                                'class'=>'CCheckBoxColumn',
                                                'checked'=>'false'      // The PHP expression would go here !
                                ),*/
             /*
                array(
			'class'=>'CButtonColumn',
                        'template' => '{test}',
                         'buttons' => array(
                                 'test' => array(
                                            'label' => 'View SubCategories',
                         // 'click' => 'js:function() { alert("'.$model->cat_id.'"); return false;}',
                                          'url'=>'Yii::app()->createUrl("admin/categories/admin", array("cat_id"=>$data->cat_id))',

                                              ),
                                        ), 


 		     ), 
		array(
			'class'=>'CButtonColumn',
                         //'template' => '{test}',

 		),
            
	   ),
       'htmlOptions' => array(
                         'style' => 'width:500px;',
                        ),  
));
*/

?>

