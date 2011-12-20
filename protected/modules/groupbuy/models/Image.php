<?php


class Image extends CFormModel
{
 public $image;
 
   
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('image', 'required'),
			array('image', 'file','types'=>'jpg,gif,png,jpeg'),
			
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
                          'image'=>'Image',
                        
		);
	}

}


?>
