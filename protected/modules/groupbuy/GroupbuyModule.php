<?php

class GroupbuyModule extends CWebModule
{

  //Facebook details
   public $app_id="241476069209769";
   public $canvas_page="http://apps.facebook.com/ajaxtable/";
   public $app_secret="984da493a1492f5b2d323be510c3f7d6";


  //Paypal details
   public $API_USERNAME="sirini_1313513212_biz_api1.gmail.com";
   public $API_PASSWORD="1313513263";
   public $API_SIGNATURE="A1Q3o.L0B5Ai0-QOn7AUipH8gYbrATo0OKWn3lVOfV-eKHA92k-ZbGet";

 /**
# Endpoint: this is the server URL which you have to connect for submitting your API request.
*/
   public $API_ENDPOINT="https://api-3t.sandbox.paypal.com/nvp";

/* Define the PayPal URL. This is the URL that the buyer is
   first sent to to authorize payment with their paypal account
   change the URL depending if you are testing on the sandbox
   or going to the live PayPal site
   For the sandbox, the URL is
   https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
   For the live site, the URL is
   https://www.paypal.com/webscr&cmd=_express-checkout&token=
   */

   public $PAYPAL_URL="https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=";
  /**
# Version: this is the API version in the request.
# It is a mandatory parameter for each API request.
# The only supported value at this time is 2.3
*/
   public $VERSION="65.1";

// Ack related constants
   public $ACK_SUCCESS="SUCCESS";
   public $ACK_SUCCESS_WITH_WARNING="SUCCESSWITHWARNING";

/*
 # Third party Email address that you granted permission to make api call.
 */
   public $SUBJECT="";
/*for permission APIs ->token, signature, timestamp  are needed */
   public $AUTH_TOKEN='';
   public $AUTH_SIGNATURE='';
   public $AUTH_TIMESTAMP='';
 
//$AUTHMODE = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
//$AUTHMODE = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
//$AUTHMODE = "THIRDPARTY";Partner's API Credential and Merchant Email as Subject are required.
		 
   public $AUTHMODE='';

   /**
USE_PROXY: Set this variable to TRUE to route all the API requests through proxy.
like define('USE_PROXY',TRUE);
*/
   public $USE_PROXY=FALSE;
/**
PROXY_HOST: Set the host name or the IP address of proxy server.
PROXY_PORT: Set proxy port.

PROXY_HOST and PROXY_PORT will be read only if USE_PROXY is set to TRUE
*/
   public $PROXY_HOST="127.0.0.1";
   public $PROXY_PORT="808";


	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'groupbuy.models.*',
			'groupbuy.components.*',
                        'groupbuy.extensions.facebook.*',  
                        'groupbuy.extensions.facebook.lib.*',    
                        
		));

              $auth=new GroupbuyAuthcode;  

                if(!$auth->isExist(Yii::app()->user->id)&&!Yii::app()->user->isGuest)
                   {   
                    $auth->authcode=uniqid('',true);   
                    $auth->advertiser=Yii::app()->user->id;
                    $auth->create_time=new CDbExpression('NOW()');
                    $auth->update_time=new CDbExpression('NOW()');        
                    $auth->save();
                   } 
              
           
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

private $_assetsUrl;

/**
* @return string the base URL that contains all published asset files of this module.
*/
public function getAssetsUrl()
{
if($this->_assetsUrl===null)
$this->_assetsUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('groupbuy.assets'));
return $this->_assetsUrl;
}

/**
* @param string the base URL that contains all published asset files of this module.
*/
public function setAssetsUrl($value)
{
$this->_assetsUrl=$value;
}

public function registerCss($file, $media='all')
{
$href = $this->getAssetsUrl().'/css/'.$file;
return '<link rel="stylesheet" type="text/css" href="'.$href.'" media="'.$media.'" />';
}

public function registerImage($file)
{
return $this->getAssetsUrl().'/images/'.$file;
}
public function registerJs($file)
{
$href = $this->getAssetsUrl().'/js/'.$file;
return '<script type="text/javascript" src="'.$href.'" ></script>';
}



}
