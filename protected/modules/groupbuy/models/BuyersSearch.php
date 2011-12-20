<?php


class BuyersSearch extends CFormModel
{
 public $first_name;
 public $coupon_id;
 public $confirmation_code;

 
   
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('image', 'required'),
			//array('image', 'file','types'=>'jpg,gif,png,jpeg'),
                         array('first_name,coupon_id,confirmation_code','safe'),
			
		);
	}
      /**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			//'paymentType'=>'Payment Type',
                          'first_name'=>'First Name',
                        
		);
	}

}


?>
