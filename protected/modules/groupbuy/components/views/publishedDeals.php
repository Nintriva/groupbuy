<?php
//echo "ok";



 $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$this->dealDataProvider,
	'itemView'=>'_publishedDealView',
        //'template'=>'{items}\n{pager}',
        'summaryText'=>'',
 
    /*    'enableSorting' => true,
        'sortableAttributes'=>array(
            //'id'=>'By latest',
            'title'=>'By name',
        ), */

        'ajaxUpdate'=>false,  
        'htmlOptions'=>array(

                        'style'=>'width:400px;',

                       ),

        'afterAjaxUpdate' => 'js:function(id) { 
        


p=document.getElementById("diff_sec").value;
gettime();
getprogress();
load_images();

}',
 
));

?> 
