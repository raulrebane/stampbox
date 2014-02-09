<?php

/**
 * This is the model class for table "ds.t_stamps_issued".
 *
 * The followings are the available columns in table 'ds.t_stamps_issued':
 * @property string $Stamp_token
 * @property string $stamp_id
 * @property string $batch_id
 * @property string $issued_to
 * @property string $status
 * @property string $timestamp
 */
class MyStamps extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MyStamps the static model class
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
		return 'ds.t_stamps_issued';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Stamp_token, stamp_id, batch_id, issued_to, status, timestamp', 'required'),
			array('Stamp_token', 'length', 'max'=>64),
			array('status', 'length', 'max'=>1),
			array('timestamp', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Stamp_token, stamp_id, batch_id, issued_to, status, timestamp', 'safe', 'on'=>'search'),
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
			'Stamp_token' => 'Stamp Token',
			'stamp_id' => 'Stamp',
			'batch_id' => 'Batch',
			'issued_to' => 'Issued To',
			'status' => 'Status',
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

		$criteria->compare('Stamp_token',$this->Stamp_token,true);
		$criteria->compare('stamp_id',$this->stamp_id,true);
		$criteria->compare('batch_id',$this->batch_id,true);
		$criteria->compare('issued_to',$this->issued_to,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('timestamp',$this->timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}