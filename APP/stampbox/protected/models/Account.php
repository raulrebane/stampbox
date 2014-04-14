<?php

/**
 * This is the model class for table "ds.t_account".
 *
 * The followings are the available columns in table 'ds.t_customer':
 * @property string $customer_id
 * @property string $points_bal
 * @property string $stamps_bal

 */
class Account extends CFormModel
{
    public $statement_grid;
    public $statement_range;

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_id' => 'Customer',
			'points_bal' => 'Credits',
			'stamps_bal' => 'Stamps',
		);
	}


}