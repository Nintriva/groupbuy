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
				'actions'=>array('create','update','publish','unpublish','error','SeeBuyers'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
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
                  date_default_timezone_set('Asia/Calcutta'); 
        
                  
 
                 
			$model->attributes=$_POST['Deal'];
  
                       
                        date_timezone_set($model->start_date, timezone_open('Asia/Calcutta'));
                        date_timezone_set($model->end_date, timezone_open('Asia/Calcutta'));                  
                        date_timezone_set($model->coupon_expiry, timezone_open('Asia/Calcutta'));  


 //echo date_format($date, 'Y-m-d H:i:sP') . "\n";
 
 

			if($model->save())
                           {

                  
				$this->redirect(array('/groupbuy/default/index'));
                           }
		}

		$this->render('create',array(
			'model'=>$model,
		));
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
			if($model->save())
                           {
                              //echo "fine print:".$model->fine_print;
				$this->redirect(array('/groupbuy/deal/view','id'=>$model->id));
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
			$this->loadModel($id)->delete();

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
              if($model->tipping_point==$model->coupons_count)
                {
                  //if(!isset($model->tipped_at))
                  $model->tipped_at=new CDbExpression('NOW()');
                 $model->is_deal_on=1;
                 

                }
               else
                {
                 $model->is_deal_on=0; 

                }   
            $model->published=1;
            $model->save();
            
            //echo "<pre>";
            //print_r($model);
            //echo "</pre>";
           // return;
           $this->redirect(array('/groupbuy/deal/view','id'=>$id));     
         //  $this->redirect(Yii:app()->returnUrl);                    
 
         } 
        public function actionUnPublish($id)
         {
            $model=$this->loadModel($id);
           // $model->is_deal_on=0; 
            $model->published=0;
            $model->save();
           
             //  $this->redirect(Yii::app()->user->returnUrl);   
           
            $this->redirect(array('/groupbuy/deal/view','id'=>$id));                    
 
         } 
        public function actionSeeBuyers($id)
         {

 
            $this->render('buyers_list',array('deal_id'=>$id));
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
        //  $this->redirect(array('/groupbuy/coupon/error'));  
      }
}
