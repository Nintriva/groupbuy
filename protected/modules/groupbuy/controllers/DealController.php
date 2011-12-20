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
                        'Deal + update,delete',
                        'Publish + publish',
                        'Refund + refund',  
		);
	}
      
        public function filterRefund($filterChain)
        {
          if(!isset($_REQUEST['id']))
            {
               $this->render('error',array('msg'=>'Invalid deal id'));
                return;
            }
            $deal=Deal::model()->findbyPk($_REQUEST['id']);
                if($deal==null)
                  {
                     $this->render('error',array('msg'=>'Invalid deal'));
                     return;
                  }
                else if($deal->advertiser!=Yii::app()->user->id)
                  {
                    $this->render('error',array('msg'=>'UnAuthorized access.This is not your deal.'));
                    return;

                  }
                else if($deal->is_expired==1)
                  {
                    $this->render('error',array('msg'=>'Sorry.This deal is expired'));
                    return;

                  }
               else if($deal->status==7)
                  {
                    $this->render('error',array('msg'=>'This deal is deleted.'));
                    return;

                  } 
               else if($deal->is_refunded==1)
                  {
                    $this->render('error',array('msg'=>'Refund is already done for this deal'));
                    return;

                  } 
                
        
         $filterChain->run();
        }
     
        public function filterPublish($filterChain)
        {
          if(!isset($_REQUEST['id']))
            {
               $this->render('error',array('msg'=>'Invalid deal id'));
                return;
            }
            $deal=Deal::model()->findbyPk($_REQUEST['id']);
                if($deal==null)
                  {
                     $this->render('error',array('msg'=>'Invalid deal'));
                     return;
                  }
                else if($deal->advertiser!=Yii::app()->user->id)
                  {
                    $this->render('error',array('msg'=>'UnAuthorized access.This is not your deal.'));
                    return;

                  }
                else if($deal->is_expired==1)
                  {
                    $this->render('error',array('msg'=>'Sorry.You cannot publish an expired deal'));
                    return;

                  }
                else if($deal->published==1)
                  {
                    $this->render('error',array('msg'=>'This deal is already published'));
                    return;

                  }
               else if($deal->status==7)
                  {
                    $this->render('error',array('msg'=>'This deal is deleted.'));
                    return;

                  } 
                
        
         $filterChain->run();
        }
        public function filterDeal($filterChain)
        {
          if(!isset($_REQUEST['id']))
            {
               $this->render('error',array('msg'=>'Invalid deal id'));
                return;
            }
            $deal=Deal::model()->findbyPk($_REQUEST['id']);
                if($deal==null)
                  {
                     $this->render('error',array('msg'=>'Invalid deal'));
                     return;
                  }
                else if($deal->published==1)
                  {
                     $this->render("error",array('msg'=>'Sorry.The deal is published.'));
                     return;
                  }
           

         $filterChain->run(); 
         
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
               $this->redirect(array("default/index"));
                //This is the default view(last deal of the current user) for the link /groupbuy/deal/view 
               // $this->loadDefaultDealView();
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
				'actions'=>array('create','delete','admin','update','publish','unpublish','error','SeeBuyers','Duplicate','ConfirmUnPublish','Refund','Upload1','Upload2','BuyerView'),
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
 
                // echo "<pre>"; 
                // print_r($_POST['Deal']);
               //  echo "</pre>";
                // return;   
                // echo "ok"; 
                

		 $model->attributes=$_POST['Deal'];
            
                 $tz=$model->getTimeZone();
                 
                 date_default_timezone_set($tz[$model->timezone]);
              
                  $model->setScenario('add'); 
                   if($model->validate('add'))
                    {
			if($model->save(false))
                           {

                                   if(CUploadedFile::getInstance($model,'excel')!=null||CUploadedFile::getInstance($model,'excel1')!=null||CUploadedFile::getInstance($model,'excel2')!=null)
                                       {   
                                       //$model->setScenario('upload1');
                                        if(CUploadedFile::getInstance($model,'excel')!=null)
                                         {
                                            

                                             $model->setScenario('upload');
                                               if(!$model->validate('upload'))
                                                {
                                                   $this->render('create',array(
			                          'model'=>$model,
		                                    ));

                                                    return; 
                                                }
                                             
 
                                               $myfile=CUploadedFile::getInstance($model,'excel'); 
                                               $file_name=uniqid().$myfile->name;
                                               $myfile->SaveAs(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                      
                                               Yii::import('application.modules.groupbuy.extensions.phpexcelreader.JPhpExcelReader');
                                               $data=new JPhpExcelReader(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                                               // echo "t:".$model->is_full_upload1;
                                                //return;  
                                                 if($model->is_full_upload==1)
                                                  {
                                                   BuyerConfirmationCode::model()->deleteExistingCodes($model->id,2);  //1=>deal On codes,0=>deal OFF codes,2=>deal coupon codes(common for both ON and OFF deals)
                                                
                                                  } 
                                                $i=0; 
                                                foreach($data->sst as $code)
                                                 {
                                                  $buyerConfirmationCode[$i]=new BuyerConfirmationCode;
                                                   if($buyerConfirmationCode[$i]->isCodeExist($code,$model->id))
                                                    {
                                                     continue;
                                                    }
                                                  $buyerConfirmationCode[$i]->code=$code;
                                                  $buyerConfirmationCode[$i]->deal_id=$model->id;
                                                  $buyerConfirmationCode[$i]->should_deal_on=2;
                                                  $buyerConfirmationCode[$i]->advertiser=Yii::app()->user->id;
                                                  if(!$buyerConfirmationCode[$i]->save(false)){
                                                     $e=$buyerConfirmationCode[$i]->getErrors();
                                                     echo "<pre>";
                                                     print_r($e);
                                                     echo "</pre>";
                                                      return;  
                                                     }
                                                     
                                                  $i++; 
                                                 }     
                                                 unlink(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);      
                                                 $this->redirect(array("deal/update","id"=>$model->id));
                                                 return;
                                         }                     

                                        else if(CUploadedFile::getInstance($model,'excel1')!=null)
                                         {

                                             $model->setScenario('upload1');
                                               if(!$model->validate('upload1'))
                                                {
                                                   $this->render('create',array(
			                          'model'=>$model,
		                                    ));

                                                    return; 
                                                }
                                          // if($model->validate('upload1'))
                                            {     
                                               $myfile=CUploadedFile::getInstance($model,'excel1'); 
                                               $file_name=uniqid().$myfile->name;
                                               $myfile->SaveAs(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                      
                                               Yii::import('application.modules.groupbuy.extensions.phpexcelreader.JPhpExcelReader');
                                               $data=new JPhpExcelReader(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                                               // echo "t:".$model->is_full_upload1;
                                                //return;  
                                                 if($model->is_full_upload1==1)
                                                  {
                                                   BuyerConfirmationCode::model()->deleteExistingCodes($model->id,1);  //1=>deal On codes,0=>deal OFF codes
                                                
                                                  } 
                                                $i=0; 
                                                foreach($data->sst as $code)
                                                 {
                                                  $buyerConfirmationCode[$i]=new BuyerConfirmationCode;
                                                   if($buyerConfirmationCode[$i]->isCodeExist($code,$model->id))
                                                    {
                                                     continue;
                                                    }
                                                  $buyerConfirmationCode[$i]->code=$code;
                                                  $buyerConfirmationCode[$i]->deal_id=$model->id;
                                                  $buyerConfirmationCode[$i]->should_deal_on=1;
                                                  $buyerConfirmationCode[$i]->advertiser=Yii::app()->user->id;
                                                  if(!$buyerConfirmationCode[$i]->save(false)){
                                                     $e=$buyerConfirmationCode[$i]->getErrors();
                                                     echo "<pre>";
                                                     print_r($e);
                                                     echo "</pre>";
                                                      return;  
                                                     }
                                                     
                                                  $i++; 
                                                 }   
                                                 unlink(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);            
                                                 $this->redirect(array("deal/update","id"=>$model->id));
                                                // $this->render('update',array(
		                               //'model'=>$model));

                                               return; 
                                            }
                                        /* else
                                            {
                                              $this->render('create',array(
		                               'model'=>$model,));

                                            }*/ 
                                         }
                                      else if(CUploadedFile::getInstance($model,'excel2')!=null)
                                         {
                                            $model->setScenario('upload2');
                                               if(!$model->validate('upload2'))
                                                {
                                                   $this->render('create',array(
			                          'model'=>$model,
		                                    ));

                                                    return; 
                                                }
                                             $myfile=CUploadedFile::getInstance($model,'excel2'); 
                                               $file_name=uniqid().$myfile->name;
                                               $myfile->SaveAs(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                      
                                               Yii::import('application.modules.groupbuy.extensions.phpexcelreader.JPhpExcelReader');
                                               $data=new JPhpExcelReader(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                                                if($model->is_full_upload2==1)
                                                  {
                                                   BuyerConfirmationCode::model()->deleteExistingCodes($model->id,0);  //1=>deal On codes,0=>deal OFF codes
                                                
                                                  }
                                                $i=0; 
                                                foreach($data->sst as $code)
                                                 {
                                                  $buyerConfirmationCode[$i]=new BuyerConfirmationCode;
                                                   if($buyerConfirmationCode[$i]->isCodeExist($code,$model->id))
                                                    {
                                                     continue;
                                                    }
                                                  $buyerConfirmationCode[$i]->code=$code;
                                                  $buyerConfirmationCode[$i]->deal_id=$model->id;
                                                  $buyerConfirmationCode[$i]->should_deal_on=0;
                                                  $buyerConfirmationCode[$i]->advertiser=Yii::app()->user->id;
                                                  $buyerConfirmationCode[$i]->save(); 
                                                  $i++; 
                                                 }
                                                  unlink(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);    
                                                 $this->redirect(array("deal/update","id"=>$model->id));           
                                               //  $this->render('update',array('model'=>$model));
                                                return;

                                         }
                    

                                 // $this->render('create',array(
			          //  'model'=>$model,));
                                 //return;

                                } //end uploads     
                              else
                               {
                                 //NO Upload case
				$this->redirect(array('default/index'));
                                return;
                               } 
                          } //end save deal
                  } //end validate               

                 

                
		} //end isset['Deal']

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
                $model->is_free_coupon=$original->is_free_coupon;  
                 $model->image=$original->image;    
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

                
                       // echo "is_free:".$model->is_free_coupon;
                        //echo "<br/>Time zone:".$model->timezone; 
                            
                        //return;
  
			  if($model->save(false))
                            {
                              //echo "fine print:".$model->fine_print;
                                   if(CUploadedFile::getInstance($model,'excel')!=null||CUploadedFile::getInstance($model,'excel1')!=null||CUploadedFile::getInstance($model,'excel2')!=null)
                                       {   
                                           
                                       //$model->setScenario('upload1');
                                        if(CUploadedFile::getInstance($model,'excel')!=null)
                                         {
                                               $model->setScenario('upload');
                                               if(!$model->validate('upload'))
                                                {
                                                   $this->render('update',array(
			                          'model'=>$model,
		                                    ));

                                                    return; 
                                                }
                                               
                                               $myfile=CUploadedFile::getInstance($model,'excel'); 
                                               $file_name=uniqid().$myfile->name;
                                               $myfile->SaveAs(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                      
                                               Yii::import('application.modules.groupbuy.extensions.phpexcelreader.JPhpExcelReader');
                                               $data=new JPhpExcelReader(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                                               // echo "t:".$model->is_full_upload1;
                                                //return;  
                                                 if($model->is_full_upload==1)
                                                  {
                                                   
                                                   BuyerConfirmationCode::model()->deleteExistingCodes($model->id,2);  //1=>deal On codes,0=>deal OFF codes
                                                   
                                                  } 
                                                 //echo "ok2";
                                                  // return;
                                                $i=0; 
                                                foreach($data->sst as $code)
                                                 {
                                                       
                                                  $buyerConfirmationCode[$i]=new BuyerConfirmationCode;
                                                   if($buyerConfirmationCode[$i]->isCodeExist($code,Yii::app()->user->id))
                                                    {
                                                     continue;
                                                    } 
                                                  $buyerConfirmationCode[$i]->code=$code;
                                                  $buyerConfirmationCode[$i]->deal_id=$model->id;
                                                  $buyerConfirmationCode[$i]->should_deal_on=2;
                                                  $buyerConfirmationCode[$i]->advertiser=Yii::app()->user->id;
                                                  if(!$buyerConfirmationCode[$i]->save(false)){
                                                     $e=$buyerConfirmationCode[$i]->getErrors();
                                                     echo "<pre>";
                                                     print_r($e);
                                                     echo "</pre>";
                                                      return;  
                                                     }
                                                     
                                                  $i++; 
                                                 } 
                                                  unlink(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);              
                                                 $this->redirect(array("deal/update","id"=>$model->id));
                                                 return;
                                         }                             

                                        else if(CUploadedFile::getInstance($model,'excel1')!=null)
                                         {
                                             $model->setScenario('upload1');
                                               if(!$model->validate('upload1'))
                                                {
                                                   $this->render('update',array(
			                          'model'=>$model,
		                                    ));

                                                    return; 
                                                }
                                          // if($model->validate('upload1'))
                                            {     
                                               $myfile=CUploadedFile::getInstance($model,'excel1'); 
                                               $file_name=uniqid().$myfile->name;
                                               $myfile->SaveAs(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                      
                                               Yii::import('application.modules.groupbuy.extensions.phpexcelreader.JPhpExcelReader');
                                               $data=new JPhpExcelReader(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                                                 //  $data=new JPhpExcelReader(Yii::app()->createUrl('groupbuy/assets/excel/'.$file_name));
                                               // echo $data->dump(true,true);
                                                //echo "<pre>"; 
                                                //print_r($data->sst);
                                                //echo "</pre>"; 
                                                if($model->is_full_upload1==1)
                                                  {
                                                   BuyerConfirmationCode::model()->deleteExistingCodes($model->id,1);  //1=>deal On codes,0=>deal OFF codes
                                                
                                                  }  
                                                $i=0; 
                                                foreach($data->sst as $code)
                                                 {
                                                  $buyerConfirmationCode[$i]=new BuyerConfirmationCode;
                                                   if($buyerConfirmationCode[$i]->isCodeExist($code,$model->id))
                                                    {
                                                     continue;
                                                    }  
                                                  $buyerConfirmationCode[$i]->code=$code;
                                                  $buyerConfirmationCode[$i]->deal_id=$model->id;
                                                  $buyerConfirmationCode[$i]->should_deal_on=1;
                                                  $buyerConfirmationCode[$i]->advertiser=Yii::app()->user->id;
                                                  $buyerConfirmationCode[$i]->save(); 
                                                  $i++; 
                                                 }
                                                   unlink(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);               
                                                  $this->redirect(array("deal/update","id"=>$model->id));

                                               return; 
                                            }
                                        /* else
                                            {
                                              $this->render('create',array(
		                               'model'=>$model,));

                                            }*/ 
                                         }
                                      else if(CUploadedFile::getInstance($model,'excel2')!=null)
                                         {
                                             $model->setScenario('upload2');
                                               if(!$model->validate('upload2'))
                                                {
                                                   $this->render('update',array(
			                          'model'=>$model,
		                                    ));

                                                    return; 
                                                }  
                                             $myfile=CUploadedFile::getInstance($model,'excel2'); 
                                               $file_name=uniqid().$myfile->name;
                                               $myfile->SaveAs(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                      
                                               Yii::import('application.modules.groupbuy.extensions.phpexcelreader.JPhpExcelReader');
                                               $data=new JPhpExcelReader(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);
                                                if($model->is_full_upload2==1)
                                                  {
                                                   BuyerConfirmationCode::model()->deleteExistingCodes($model->id,0);  //1=>deal On codes,0=>deal OFF codes
                                                
                                                  }                             
                  
                                               $i=0; 
                                                foreach($data->sst as $code)
                                                 {
                                                  $buyerConfirmationCode[$i]=new BuyerConfirmationCode;
                                                   if($buyerConfirmationCode[$i]->isCodeExist($code,$model->id))
                                                    {
                                                     continue;
                                                    }
                                                  $buyerConfirmationCode[$i]->code=$code;
                                                  $buyerConfirmationCode[$i]->deal_id=$model->id;
                                                  $buyerConfirmationCode[$i]->should_deal_on=0;
                                                  $buyerConfirmationCode[$i]->advertiser=Yii::app()->user->id;
                                                  $buyerConfirmationCode[$i]->save(); 
                                                  $i++; 
                                                 }
                                                  unlink(Yii::app()->getModule('groupbuy')->basePath.'/assets/excel/'.$file_name);               
                                                 $this->redirect(array("deal/update","id"=>$model->id));
                                                return;

                                         }
                    


                                } //end uploads
                               else
                               {
                                 //no uploads
				$this->redirect(array('deal/view','id'=>$model->id));
                               }   
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
         public function actionBuyerView()
         {
           $buyer_fb_id=$_GET['buyer_fb_id'];
           $deal_id=$_GET['deal_id']; 

           $deal=Deal::model()->findbyPk($deal_id);
           $buyer=Buyer::model()->findbyPk($buyer_fb_id);   
           //echo $buyer->first_name;
          // return;  
           $this->render('buyerView',array('buyer'=>$buyer,'deal'=>$deal));
 
         }
         public function actionSeeBuyers($id)
         {
            
             /*
		$model=new Coupon('buyers_search');
		$model->unsetAttributes();  // clear any default values
		
                if(isset($_GET['Coupon']))
			$model->attributes=$_GET['Coupon'];

		$this->render('buyers_list',array(
			'model'=>$model,
		));*/ 
             $criteria=new CDbCriteria;
             $buyers_search=new BuyersSearch;
            
            $con="t.deal='".$id."' and t.status!=4";
 
            if(isset($_GET['BuyersSearch']))
              {   
		$buyers_search->attributes=$_GET['BuyersSearch'];

               
                 if($buyers_search->coupon_id!=null)  
                 $con.=" and t.id='".$buyers_search->coupon_id."' ";
               // $criteria->compare('fb_id',$buyers_search->first_name,true);
		//$criteria->compare('id',$buyers_search->coupon_id,true);
		//$criteria->compare('last_name',$this->last_name,true);
                //  $criteria->condition="t.id='".$buyers_search->coupon_id."'";
	        
              // echo "<pre>";
               // print_r($buyers_search); 
               // echo "</pre>";    
                //return;
                /*
                 $couponDataProvider=new CArrayDataProvider($deal->coupons,array(
                    'keyField' => 'id',     
                    'pagination'=>array(
                    'pageSize'=>10,   
                 ),*/
     // 'totalItemCount' =>$this->user->review_count,      
 
  // ));

   
              }
            $criteria->condition=$con;
            
            $deal=Deal::model()->findbyPk($id); 
              $couponDataProvider=new CActiveDataProvider('Coupon',array(
                   // 'keyField' => 'id',     
                    'pagination'=>array(
                    'pageSize'=>10,   
                 ), 
               'criteria'=>$criteria,
                   
               ));
          
 
            $this->render('buyers_list',array('deal'=>$deal,'buyers_search'=>$buyers_search,'couponDataProvider'=> $couponDataProvider));
            
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
            $model->is_refunded=0; 
           // $model->start_date=date('Y-m-d H:i:s');
            if(!$model->isNewRecord)
               {
                 $model->group_discount=$model->discount_percentage;
               } 
            $model->setScenario('publish');  
                             
           if(!$model->validate('publish')) 
             {
                 
           
                  $this->render('update',array(
	 		'model'=>$model,
	        	));
                  return; 
 
             }
           if($model->is_free_coupon==0) 
            {
              //groupbuy item 
                 $model->setScenario('groupbuy_item');  
                                      
                 if(!$model->validate('groupbuy_item')) 
                  {
                    $this->render('update',array(
	 	          	'model'=>$model,
	        	      ));
                    return; 
 
                  }
          

            }
          
               $model->save(false);

         
                                                      
            
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
           $model->deleteAllCoupons();
           
           $model->markAllCodesAreUnUsed(); 

           $model->removeAllBuyers();
           
           $model->is_tipped=0;
           $model->is_deal_on=0;
           $model->is_refunded=1;
           $model->save(false);  

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
