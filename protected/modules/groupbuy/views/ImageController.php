<?php

class ImageController extends Controller
{
	public function actionRemove($deal_id)
	{
           if(isset($deal_id)) 
             {
              $deal=Deal::model()->findbyPk($deal_id); 
             }
 
             unlink(Yii::app()->getModule('groupbuy')->basePath.'/assets/images/deal/'.$deal->image);
             $deal->image=null;
             $deal->save(false);   
				
                   

                   $this->redirect(array('/groupbuy/deal/view','id'=>$deal->id)); 
	}

	public function actionUpload()
	{
          //echo "ok".Yii::app()->getModule('groupbuy')->basePath;
		//$this->render('upload');
	}
        public function actionImage_upload()
         {  
             if(isset($_GET['deal_id'])) 
             {
              $deal=Deal::model()->findbyPk($_GET['deal_id']); 
             }

            $model=new Image;

           
            // uncomment the following code to enable ajax-based validation
           /*
             if(isset($_POST['ajax']) && $_POST['ajax']==='image-image_upload-form')
              {
                  echo CActiveForm::validate($model);
                   Yii::app()->end();
              }
           */

              if(isset($_POST['Image']))
             {
               $model->attributes=$_POST['Image'];
               if($model->validate())
               {
                  // form inputs are valid, do something here

                              
                    $myfile=CUploadedFile::getInstance($model,'image'); 
                    $img_name=uniqid().$myfile->name;

              
                    // $model->image_name='protected/businessImages/'.$img_name; //src field
                   //   $img_name='protected/businessImages/'.$img_name; 
                        
                 
                  
			//if($model->save())
                          {
                           //     $myfile->SaveAs(Yii::app()->getBasePath().'/businessImages/'.$img_name);
                               $myfile->SaveAs(Yii::app()->getModule('groupbuy')->basePath.'/assets/images/deal/'.$img_name);
                                
                              $deal->image=$img_name;
                              //$deal->save();

 
                               if(!$deal->save(false))
                              {
                                $e=$deal->getErrors();
                                echo "<pre>";
                                print_r($e);
                                echo "</pre>"; 
                                 echo "errorrrr";
                                return; 
                              }  
				
                           }  

                   $this->redirect(array('/groupbuy/deal/view','id'=>$deal->id)); 
                      
                }
              }
               $this->render('image_upload',array('model'=>$model,'deal'=>$deal));
           } 
 


 

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
