<?php

class FacebookController extends Controller
{
   
  
   protected $auth_url;

   //public $layout='deal';
 
   


	

	public function actionAuthenticate()
	{
          // echo "Inside authenticate";  
           $facebook = new Facebook(array(
            'appId'  => Yii::app()->controller->module->app_id,
            'secret' => Yii::app()->controller->module->app_secret,
            'cookie' => true,
               ));

         
           $auth=new GroupbuyAuthcode;  
           $session=new CHttpSession;
                          
         
                //CHECK WHETHER we got token or not  to confirm the payment

            $session->open();
         
         

           if(isset($session['token'])&&$session['PayerID'])   //CHECK WHETHER THE 1'st API call to paypal  IS SUCCESS OR NOT
             {
                //echo "token:".$payment_result['TOKEN'];
               
                        
                 $this->redirect(array('/groupbuy/paypal/reviewOrder'));  
                 
                 
             } 
            
       
             
           
                           


             // echo "Inside authenticate";  
               
                    if(isset($_POST['GroupbuyAuthcode']))
                        {
                          //echo "form set ayi";  
			$auth->attributes=$_POST['GroupbuyAuthcode'];
                        $result=$auth->authenticate($auth->authcode);
			if(!$result)
                          {
                            echo "Authentication failed"; 
                            $this->render('authcode_form',array('model'=>$auth));
                              
                          }//end if4
                         else
                          {
                              $verified_pages=new VerifiedFbPages;                            
 
                             
                              $verified_pages->page_id=$session['page_id'];
                              $verified_pages->advertiser=$result->advertiser;  
                              $verified_pages->verified_time=new CDbExpression('NOW()');
                              $verified_pages->save();
  
                             
                               //$this->render('admin');                        
                          //   $this->actionAdmin();  
                            
                            $this->redirect(array('facebook/admin'));           
                          } //end else2
                             
                        }//end if3
           
           
   $me=$facebook->api("/698036837");
   
   echo "<pre>";
   print_r($me);
   echo "</pre>";
   return;
        




               $signedRequest=$facebook->getSignedRequest(); 
 
                                                
              if(isset($signedRequest))
              {

               $page_details=$facebook->api("/".$signedRequest['page']['id']); //api call to get page details(page url)
                 
               
               $session['page_link']=$page_details['link'];
  
              
                    
              }        
 



      $this->auth_url = "http://www.facebook.com/dialog/oauth?client_id=".Yii::app()->controller->module->app_id."&redirect_uri=".$session['page_link']."?sk=app_".Yii::app()->controller->module->app_id."&scope=email,user_location";



     
         if(isset($signedRequest['user_id']))
           {
              //  if(!isset($session['me']))  
                 $session['me']=$facebook->api("/me"); //API CALL to get user details      
                 
           } 

          

              if((!isset($signedRequest['user_id'])||!isset($session['me']['location']))&&(!isset($session['permission_asked'])))
              {   
                $session['permission_asked']=1; 
               echo("<script> top.location.href='" . $this->auth_url . "'</script>");
              }  
                        
         // $access_token=$facebook->getAccessToken();
                  /*
                    echo "<pre>";
                    print_r($session['me']);
                    echo "</pre>";
                    return;  
                   */                  
 
                if(isset($signedRequest))
                  {

                                       

                        $session['signedRequest']=$signedRequest; 
                        /* echo "<pre>";
                    print_r($session['signedRequest']);
                    echo "</pre>";
                    return;*/ 
                    
                        $session['page_id']=$signedRequest['page']['id'];
                        $isVarified=VerifiedFbPages::model()->isVerified($signedRequest['page']['id']);                    
         
                        if(!$isVarified)
                          {

                                    if($signedRequest['page']['admin']==1)
                                     {  
                   
                                         $this->render('authcode_form',array('model'=>$auth));
                        
                                     }
                                    else 
                                    {
                                         echo "The service is not yet started in this page.Come back later.";
                                    } 
 
                          }
                         else if(isset($session['me']))
                           {
                             $this->actionSwitchUser();
                             
                           }        
                        else if(!isset($session['me']))
                         {
                           echo "You are not yet granted the permission to access you basic info.<br/>";
                           echo "<a href='".$this->auth_url."'>CLICK HERE TO GRANT THE PERMISSION</a>";
                           return;

                         }  

                  
                        
                     
                       
                      
                  }
             

        

	} //end authenticate


public function actionSwitchUser()
{
  $session=new CHttpSession;         
  $session->open();

  if(!isset($session['me']))
    {
     echo "You are not yet granted the permission to access you basic info.<br/>";
     echo "<a href='".$this->auth_url."'>CLICK HERE TO GRANT THE PERMISSION</a>";           
     return; 
    }  

  $signedRequest=$session['signedRequest'];

                       
                          

                        if($signedRequest['page']['admin']==1)
                          {  
                  
                               $this->redirect(array('facebook/admin'));     
                    
                          } 
                       else if($signedRequest['page']['liked']==1)
                         {
                            
                   
                               $this->redirect(array('facebook/liked'));            
                           
                         }  
                                 //NON_LIKED USER
                       else  
                        {
                      
                               $this->redirect(array('facebook/user'));  
                        
                        }	
                       
}

public function actionAdmin()
	{
	   $this->render('admin');
          
	}
public function actionLiked()
	{
	   $this->render('likedUser');
          
	}
public function actionUser()
	{
	   $this->render('user');

	}
public function actionUserCoupons()
	{
	   $this->render('userCoupons');
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
