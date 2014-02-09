<?php

/**
 * This is the model class for table "ds.t_stamps_transactions".
 *
 * The followings are the available columns in table 'ds.t_stamps_transactions':
 * @property string $transaction_code
 * @property string $customer_id
 * @property string $stamp_id
 * @property string $points
 * @property string $timestamp
 */
class StampsTransactions extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TStampsTransactions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ds.t_stamps_transactions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transaction_code, customer_id, stamp_id, points', 'required'),
			array('transaction_code, timestamp', 'length', 'max'=>5),
			array('points', 'length', 'max'=>4),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('transaction_code, customer_id, stamp_id, points, timestamp', 'safe', 'on'=>'search'),
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
			'transaction_code' => 'Transaction Code',
			'customer_id' => 'Customer',
			'stamp_id' => 'Stamp',
			'points' => 'Points',
			'timestamp' => 'Timestamp',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('transaction_code',$this->transaction_code,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('stamp_id',$this->stamp_id,true);
		$criteria->compare('points',$this->points,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}