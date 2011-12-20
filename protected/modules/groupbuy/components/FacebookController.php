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
           
           

 
        




               $signedRequest=$facebook->getSignedRequest(); 
 
                  
                  
                     
                     
                     
             
           /*    echo "<pre>";  
               print_r($signedRequest); 
               echo "</pre>"; 
           */
 
      /*               echo "<pre>";
                     print_r($session['me']);  
                     echo "</pre>"; 
                     return;  
        */                                         
              if(isset($signedRequest))
              {

               $page_details=$facebook->api("/".$signedRequest['page']['id']); //api call to get page details(page url)
                 
               
               $session['page_link']=$page_details['link'];
  
              
                    
              }        
 



      $this->auth_url = "http://www.facebook.com/dialog/oauth?client_id=".Yii::app()->controller->module->app_id."&redirect_uri=".$session['page_link']."?sk=app_".Yii::app()->controller->module->app_id."&scope=email,user_location";



     
         if(isset($signedRequest['user_id']))
           {
             $session['me']=$facebook->api("/me"); //API CALL to get user details      
                 
           } 

          

              if(!isset($signedRequest['user_id'])||!isset($session['me']['location']))
              {   
               echo("<script> top.location.href='" . $this->auth_url . "'</script>");
              }  
                        
         // $access_token=$facebook->getAccessToken();

  
                if(isset($signedRequest))
                  {
                      
                    // $session['me']=$facebook->api("/me"); //API CALL to get user details
                     
                   //  echo "<pre>";
                    // print_r($session['me']);  
                     //echo "</pre>"; 
                     //return;  
                     if(isset($session['me']))
                       {
                          $me=$session['me'];
                       } 
                       
                     
  
                         


                       
                        
                        $session['page_id']=$signedRequest['page']['id'];
                        $isVarified=VerifiedFbPages::model()->isVerified($signedRequest['page']['id']);                    
         
                     /*
                        $session['user_fb_id']=$me['id'];
                        $session['first_name']=$me['first_name'];
                        $session['last_name']=$me['last_name'];
                        $sessopn['email']=$me['email'];   
                       */ 
                        
                     
                        if($signedRequest['page']['admin']==1)
                          {  
                   
                            if($isVarified==true)  
                             {
                               $this->redirect(array('facebook/admin'));     
                               
                        
                             }
                            else
                             {
                                //if not varified render authcode_form for admin
                              $this->render('authcode_form',array('model'=>$auth));
                        
                             } 
                         
                 
                         } //end if 5
                       else if($signedRequest['page']['liked']==1)
                         {
                            
                   
                            if($isVarified==true)  
                             {
                               $this->redirect(array('facebook/liked'));   
                        
                             }
                            else
                             {
                               echo "THE SERVICE IS NOT YET STARTED.COME BACK LATER"; 
                        
                             }
                            
                           
                         }  
                                 //NON_LIKED USER
                       else  
                        {
                          
                   
                            if($isVarified==true)  
                             {
                               $this->redirect(array('facebook/user'));  
                        
                             }
                            else
                             {
                               echo "THE SERVICE IS NOT YET STARTED.COME BACK LATER"; 
                        
                             }
                        }	
                      
                  }
             

        

	}
public function actionFacebookResponse()
{
echo "ERR:";
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

 public function actionInstall()
        {
          //echo "Install here";
         // Yii::import('groupbuy.extensions.facebook.lib.facebook.php');
                  
         
           $facebook = new Facebook(array(
            'appId'  => Yii::app()->controller->module->app_id,
            'secret' => Yii::app()->controller->module->app_secret,
            'cookie' => true,
               ));

        //$fb_user = $facebook->require_login();
        /*
          $this->auth_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            .Yii::app()->controller->module->app_id."&redirect_uri=" . urlencode(Yii::app()->controller->module->canvas_page)."&scope=email,read_stream";  
          */
        // echo("<script> top.location.href='" . $this->auth_url . "'</script>");

         $signedRequest=$facebook->getSignedRequest(); 
 
         $page_details=$facebook->api("/".$signedRequest['page']['id']);        



          $this->auth_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            .Yii::app()->controller->module->app_id."&redirect_uri=" .$page_details['link']."?sk=app_".Yii::app()->controller->module->app_id."&scope=email";  
       
         $access_token=$facebook->getAccessToken();  
        if(!isset($access_token))
         {   
         echo("<script> top.location.href='" . $this->auth_url . "'</script>");
         } 

        

          echo "<pre>";  
          print_r($page_details);
          echo "</pre>";
                  
        
         //echo "access token:".$facebook->getAccessToken();   
 
          $me=$facebook->api("/me");
          
          echo "<pre>";
          print_r($me);
          echo "</pre>";  
        
         echo "<pre>";
         print_r($signedRequest);
         echo "</pre>";
       
      //  $page_details=$facebook->api("/".$signedRequest['page']['id']);
       // echo "<pre>";  
       // print_r($page_details);
       // echo "</pre>";

    

/*
         echo "<pre>";
         print_r($facebook);   
         echo "</pre>";   
          // echo "ok";
           //$session=$facebook->getSession();
           //$session  = $facebook->getSession();
            // print_r($session); 
          
           // echo "okkkkk".$facebook->getUser();

    


             $fbme = $facebook->api("/me");
 

            echo "<pre>";  
            print_r($fbme);
            echo "</pre>";
                        
  */          
           
        //  echo "ok";
          
        



 
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
