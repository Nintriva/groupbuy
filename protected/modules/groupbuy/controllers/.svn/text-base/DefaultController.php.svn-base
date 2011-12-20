<?php

class DefaultController extends Controller
{
    public $layout = 'main';

   public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
                        
		);
	}

   public function accessRules()
	{
		return array(
			
			
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),
				'users'=>array('@'),
			    ),
			 
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}



	public function actionIndex()
	{ 
		$this->render('index');
	}
        

   

}
