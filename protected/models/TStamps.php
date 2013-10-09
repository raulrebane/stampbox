<?php

/**
 * This is the model class for table "ds.t_stamps".
 *
 * The followings are the available columns in table 'ds.t_stamps':
 * @property string $stamp_id
 * @property string $customer_id
 * @property string $sender
 * @property string $receiver
 * @property string $e_mail_hash
 * @property string $status
 * @property string $last_updated
 *
 * The followings are the available model relations:
 * @property TCustomer $customer
 */
class TStamps extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ds.t_stamps';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stamp_id, customer_id', 'required'),
			array('stamp_id', 'length', 'max'=>128),
			array('sender, receiver, e_mail_hash', 'length', 'max'=>100),
			array('status', 'length', 'max'=>1),
			array('last_updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stamp_id, customer_id, sender, receiver, e_mail_hash, status, last_updated', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'TCustomer', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stamp_id' => 'Stamp',
			'customer_id' => 'Customer',
			'sender' => 'Sender',
			'receiver' => 'Receiver',
			'e_mail_hash' => 'E Mail Hash',
			'status' => 'Status',
			'last_updated' => 'Last Updated',
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

		$criteria->compare('stamp_id',$this->stamp_id,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('receiver',$this->receiver,true);
		$criteria->compare('e_mail_hash',$this->e_mail_hash,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TStamps the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
