<?php

class FacebookController extends Controller
{
   
  
//   public $auth_url;

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
                          
          // $session->setTimeout(3600); //1 hr
         
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
                             return;
                           //  $this->redirect(array('facebook/authcode_form','model'=>$auth,'msg'=>'Authentication failed!'));   
                              
                          }//end if4
                         else
                          {

                              $isVarified=VerifiedFbPages::model()->isVerified($session['page_id']);
                              if(!$isVarified)
                               {
                          
                                $verified_pages=new VerifiedFbPages;                            
 
                                $verified_pages->page_id=$session['page_id'];
                                $verified_pages->page_name=$session['page_name'];
                                $verified_pages->page_link=$session['page_link'];      
   
                                $verified_pages->advertiser=$result->advertiser;  
                                $verified_pages->verified_time=new CDbExpression('NOW()');
                                $verified_pages->save();
                              }
                             
                               //$this->render('admin');                        
                          //   $this->actionAdmin();  
                            
                            $this->redirect(array('facebook/admin'));           
                          } //end else2
                             
                        }//end if3


               $signedRequest=$facebook->getSignedRequest(); 
 
/*
               echo "<pre>";
               print_r($signedRequest);
               echo "</pre>"; 

               echo "<pre>";
               print_r($session);
               echo "</pre>"; 
               echo "p:".$session['permission_asked'];  
               return;                                 
         */                
              if(isset($signedRequest))
              {
   
               /*  echo "<pre>";
               print_r($signedRequest);
               echo "</pre>";
                return;*/
               if(!isset($signedRequest['page']['id']))
                {
                  echo "This is Canvas page";
                  return;
               }     
             
               $page_details=$facebook->api("/".$signedRequest['page']['id']); //api call to get page details(page url)
                 
               
               $session['page_name']=$page_details['name'];
               $session['page_link']=$page_details['link'];
               $session['page_id']=$signedRequest['page']['id'];  
             
               

                       $isVarified=VerifiedFbPages::model()->isVerified($signedRequest['page']['id']);                    
         
                        if(!$isVarified)
                          {

                                    if($signedRequest['page']['admin']==1)
                                     {  
                   
                                         $this->render('authcode_form',array('model'=>$auth));
                                         return;  
                        
                                     }
                                    else 
                                    {
                                         echo "The service is not yet started in this page.Please Come back later.";
                                         return;
                                    } 
 
                          }
  
              
                    
              }        
/*
               echo "<pre>";
               print_r($signedRequest);
               echo "</pre>"; 

               echo "<pre>";
               print_r($page_details);
               echo "</pre>"; 

                return;



/* $this->auth_url = "http://www.facebook.com/dialog/oauth?client_id=".Yii::app()->controller->module->app_id."&redirect_uri=http://apps.facebook.com/ajaxtable&scope=email,user_location";*/

/*
 $this->auth_url = "http://www.facebook.com/dialog/oauth?client_id=".Yii::app()->controller->module->app_id."&redirect_uri=".$session['page_link']."?sk=app_".Yii::app()->controller->module->app_id."&scope=email,user_location";
*/

$auth_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            .Yii::app()->controller->module->app_id. "&redirect_uri=" . urlencode($session['page_link']."?sk=app_".Yii::app()->controller->module->app_id)."&scope=email,user_location";


     
         if(isset($signedRequest['user_id']))
           {
              //  if(!isset($session['me']))  
                 $session['me']=$facebook->api("/me"); //API CALL to get user details      
                 
           } 

          

         
              if((!isset($signedRequest['user_id']))||(!isset($session['me']['email'])))
              {   
                
                 
              echo("<script> top.location.href='" . $auth_url . "'</script>");
               return;
               //echo "ok2";
              }                
 
                if(isset($signedRequest))
                 {      

                        $session['signedRequest']=$signedRequest; 
                       
                    
                        if(isset($session['me']))
                           {
                             $this->actionSwitchUser();
                             
                           }     
                      
                  }
             

        

	} //end authenticate


public function actionSwitchUser()
{
  $session=new CHttpSession;         
  $session->open();



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

public function actionFbtest()
{

echo "Facebook test";
   $app_id = "236578386381406";

     $canvas_page = "http://apps.facebook.com/parrysgroupbuyapp/";

     $auth_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            . $app_id . "&redirect_uri=" . urlencode($canvas_page);

     $signed_request = $_REQUEST["signed_request"];

     list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

     $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

     if (empty($data["user_id"])) {
            echo("<script> top.location.href='" . $auth_url . "'</script>");
     } else {
            echo ("Welcome User: " . $data["user_id"]);
     } 

}
	
public function actionCanvas()
{

 echo "CANVAS PAGE";
}

public function actionUninstallCallback()
{

//here you'll get the user id who is removing or deauthorize your application
//$config['secret_key'] = Yii::app()->controller->module->app_secret; //this is your application's secret key
 if(isset($_REQUEST['signed_request']))
  {
   
   $data=$this->parse_signed_request($_REQUEST['signed_request'],Yii::app()->controller->module->app_secret);
   
 
  //  $s=serialize($data);
   // file_put_contents(Yii::app()->getModule('groupbuy')->basePath.'/fb_resp',$s);
  
//$user_id=$data['user_id'];
   $page_id=$data['profile_id'];
    
   VerifiedFbPages::model()->removePage($page_id);
   
   /* $v=new VerifiedFbPages;
    $v->page_id='666';
 //   $v->page_name=$page_id;
  //  $v->page_link=$data['user_id'];
    $v->advertiser='test';
    $v->verified_time=new CDbExpression('NOW()');
  
    $v->save();
   */

  }




}
/*
public function actionGet()
{
if(file_exists(Yii::app()->getModule('groupbuy')->basePath.'/fb_resp'))
  {
    $s=file_get_contents(Yii::app()->getModule('groupbuy')->basePath.'/fb_resp');
    $data_list=unserialize($s);

    echo "<pre>";
    print_r($data_list);
    echo "</pre>";
  }

}
*/


 
/*$fbUserId this is the Facebook User UID who is removed your application. So you can use this id to update your database or do other tasks if required for your application
*/
 
/* These methods are provided by facebook
 
http://developers.facebook.com/docs/authentication/canvas
 
*/
public function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = $this->base64_url_decode($encoded_sig);
  $data = json_decode($this->base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

public function base64_url_decode($input)
{
  return base64_decode(strtr($input, '-_', '+/'));
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
