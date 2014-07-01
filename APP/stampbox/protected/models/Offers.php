<?php

/**
 * This is the model class for table "ds.t_shop_offers".
 *
 * The followings are the available columns in table 'ds.t_shop_offers':
 * @property string $offer_id
 * @property string $batch_id
 * @property string $start_from
 * @property string $end_date
 * @property string $status
 * @property integer $offer_amount
 * @property string $offer_price
 * @property string $entered_by
 * @property string $entered_when
 */
class Offers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ds.t_shop_offers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('offer_amount', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>1),
			array('offer_price', 'length', 'max'=>6),
			array('batch_id, start_from, end_date, entered_by, entered_when', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('offer_id, batch_id, start_from, end_date, status, offer_amount, offer_price, entered_by, entered_when', 'safe', 'on'=>'search'),
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
			'offer_id' => 'Offer',
			'batch_id' => 'Batch',
			'start_from' => 'Start From',
			'end_date' => 'End Date',
			'status' => 'Status',
			'offer_amount' => 'Offer Amount',
			'offer_price' => 'Offer Price',
			'entered_by' => 'Entered By',
			'entered_when' => 'Entered When',
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

		$criteria->compare('offer_id',$this->offer_id,true);
		$criteria->compare('batch_id',$this->batch_id,true);
		$criteria->compare('start_from',$this->start_from,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('offer_amount',$this->offer_amount);
		$criteria->compare('offer_price',$this->offer_price,true);
		$criteria->compare('entered_by',$this->entered_by,true);
		$criteria->compare('entered_when',$this->entered_when,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Offers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
