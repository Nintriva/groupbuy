<?php

class DealController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
          public $layout='main';
	/**
	 * @return array action filters
	 */
          public $deal=null;


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
                        'ViewException + view,index',
		);
	}

        public function filterViewException($filterChain)
        {
             $deal_id = null;
            if(isset($_GET['id']))
             {
               $deal_id = $_GET['id'];
             } 
           else if(isset($_POST['id']))
             {  
              $deal_id = $_POST['id'];
             }
             if($deal_id!=null)
               {
                  $this->loadModel($deal_id);
                  $filterChain->run();
               }   
              else
              {  
                //This is the default view(last deal of the current user) for the link /groupbuy/deal/view 
                $this->loadDefaultDealView();
              }


         // $filterChain->run();
        }


	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','facebooklogin'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','delete','admin','update','publish','unpublish','error','SeeBuyers','Duplicate','ConfirmUnPublish','Refund'),
				'users'=>array('@'),
			),
		      /*	array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),*/
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

       

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
           // echo  $id;
               //  return;
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
               
	}

        public function actionIndex()
        {

        }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Deal;

                
                  

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                $model->advertiser=Yii::app()->user->id;
 
		if(isset($_POST['Deal']))
		{
      

		 $model->attributes=$_POST['Deal'];
 
                 $tz=$model->getTimeZone();
                 
                 date_default_timezone_set($tz[$model->timezone]);
 

			if($model->save(false))
                           {

                  
				$this->redirect(array('default/index'));
                           }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
     public function actionDuplicate($id)
	{ 
                $original=Deal::model()->findbyPk($id);
               $tz=$original->getTimeZone();
             date_default_timezone_set($tz[$original->timezone]); 
 
              
		$model=new Deal;
                /*
                     skipped list when duplicating:tipped_at,is_tipped,is_deal_on,category,published,is_expired,image,status

                  */
                
                                                                   
                $model->title=$original->title; 
                $model->tipping_point=$original->tipping_point;
                $model->retail_price=$original->retail_price;
                $model->discount_percentage=$original->discount_percentage;
                $model->discount_value=$original->discount_value;       
		$model->description=$original->description;
                $model->paypal_address=$original->paypal_address; 
                $model->email=$original->email; 
                $model->website=$original->website;
                $model->address1=$original->address1;
                $model->address2=$original->address2;       
                $model->fine_print=$original->fine_print;
                $model->deal_price=$original->deal_price;     
                $model->max_purchase_units=$original->max_purchase_units; 
                $model->max_available=$original->max_available;
                
              //  $model->start_date=$original->start_date; 
               // $model->end_date=$original->end_date;
                
               // $model->coupon_expiry=$original->coupon_expiry;
                $model->advertiser=$original->advertiser; 
                $model->timezone=$original->timezone; 
                $model->free_coupons=$original->free_coupons; 
               
                $model->save(false);
                $this->redirect(array('default/index'));   

               // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                //$model->advertiser=Yii::app()->user->id;
 
             /*
		if(isset($_POST['Deal']))
		{
      

		 $model->attributes=$_POST['Deal'];
 
                 $tz=$model->getTimeZone();
                 
                 date_default_timezone_set($tz[$model->timezone]);
 

			if($model->save())
                           {

                  
				$this->redirect(array('/groupbuy/default/index'));
                           }
		}

		$this->render('create',array(
			'model'=>$model,
		));
              */
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Deal']))
		{
			$model->attributes=$_POST['Deal'];
			if($model->save(false))
                           {
                              //echo "fine print:".$model->fine_print;
				$this->redirect(array('deal/view','id'=>$model->id));
                            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model=$this->loadModel($id);
                        $model->status=7;//DELETE STATUS
                        $model->save(false);        

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/groupbuy/default/index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Deal('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Deal']))
			$model->attributes=$_GET['Deal'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
              if($this->deal==null)
                {
		$this->deal=Deal::model()->findByPk((int)$id);
		if($this->deal===null)
			throw new CHttpException(404,'The requested page does not exit.(Inside load model function)');
                }
		return $this->deal;
	}
 

    //This is the default view(last deal of the current user) for the link /groupbuy/deal/view
        public function loadDefaultDealView()
	{
                    
                          
                     $this->render('publishedDeals');
			
               
		
	} 
        public function actionPublish($id)
         {

          
            $model=$this->loadModel($id);

           
             // $model=Deal::model()->findbyPk($id);
            // $model->is_deal_on=1;
             $tz=$model->getTimeZone();
             date_default_timezone_set($tz[$model->timezone]);
             
 
            $model->published=1;
            $model->status=1; 
            $model->start_date=date('Y-m-d H:i:s');
            $model->setScenario('publish');  
  
           if(isset($_POST['Deal']))
		{
	           $model->attributes=$_POST['Deal'];

                  if(!$model->validate('publish')) 
                    {
               
                         $this->render('update',array(
			   'model'=>$model,));
		     
                  
                      return;
                    }
                }
                

                
           
        if(!$model->validate('publish')) 
             {
                  $this->render('update',array(
	 		'model'=>$model,
	        	));
                  return; 
 
             }
           else
            {
               $model->save(false);

            }
                        
              
                
            
           $m=$model->getMail("owner","published");
           mail($model->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');    
           
           $this->redirect(array('deal/view','id'=>$id));     
                     
 
         } 
        public function actionConfirmUnPublish($id)
        {
          $model=$this->loadModel($id);
          if($model->getBoughtCount()>0&&$model->isDealAvailable())
            {
              $this->render('confirmUnPublish',array('deal'=>$model));

            }
          else
            {
              $this->actionUnPublish($id); 
   
            } 

        }
         
         public function actionRefund($id)
         {
            $model=$this->loadModel($id);
     
            
             $model->refundToAll();
            // $model->cancelAllTransactions();
       
               //Redeem all coupons if issued
             foreach($model->coupons as $c)
              {
                $model->RedeemCoupon($c);

              }
           $this->actionUnPublish($id); 
                    
 
         }  
        public function actionUnPublish($id)
         {
            $model=$this->loadModel($id);
           // $model->is_deal_on=0; 
            $model->published=0;
            $model->status=5; 
            $model->save(false);
           
             //  $this->redirect(Yii::app()->user->returnUrl); 
             $m=$model->getMail("owner","unpublished");
             mail($model->email,$m['subject'],$m['body'],'From:parrysgroupbuy@groupbuy.com');       
           
            $this->redirect(array('deal/view','id'=>$id));                    
 
         } 
        public function actionSeeBuyers($id)
         {

            $deal=Deal::model()->findbyPk($id); 
 
            $this->render('buyers_list',array('deal'=>$deal));
         } 

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='deal-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
      public function actionError()
      {
           echo " Deal error occured";
           if(isset($_GET['msg']))
             {
            echo "<br/>msg:".$_GET['msg'];
             }
           //  $this->redirect(array('/groupbuy/coupon/error'));  
      }
}
