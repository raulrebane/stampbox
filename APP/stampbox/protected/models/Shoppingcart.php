<?php

/**
 * This is the model class for table "ds.t_shoppingcart".
 *
 * The followings are the available columns in table 'ds.t_shoppingcart':
 * @property string $customer_id
 * @property string $batch_id
 * @property integer $stamp_amount
 * @property string $price
 * @property string $paypal_token
 * @property string $paypal_timestamp
 * @property string $paypal_correlation_id
 */
class Shoppingcart extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ds.t_shoppingcart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id', 'required'),
			array('stamp_amount', 'numerical', 'integerOnly'=>true),
			array('price', 'length', 'max'=>6),
			array('paypal_token, paypal_correlation_id', 'length', 'max'=>30),
			array('batch_id, paypal_timestamp', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('customer_id, batch_id, stamp_amount, price, paypal_token, paypal_timestamp, paypal_correlation_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_id' => 'Customer',
			'batch_id' => 'Batch',
			'stamp_amount' => 'Stamp Amount',
			'price' => 'Price',
			'paypal_token' => 'Paypal Token',
			'paypal_timestamp' => 'Paypal Timestamp',
			'paypal_correlation_id' => 'Paypal Correlation',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('batch_id',$this->batch_id,true);
		$criteria->compare('stamp_amount',$this->stamp_amount);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('paypal_token',$this->paypal_token,true);
		$criteria->compare('paypal_timestamp',$this->paypal_timestamp,true);
		$criteria->compare('paypal_correlation_id',$this->paypal_correlation_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Shoppingcart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
