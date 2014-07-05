<?php

/**
 * This is the model class for table "ds.t_whitelist".
 *
 * The followings are the available columns in table 'ds.t_whitelist':
 * @property string $e_mail
 * @property string $customer_id
 */
class Whitelist extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ds.t_whitelist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('e_mail, customer_id', 'required'),
			array('e_mail', 'length', 'max'=>100),
                        array('e_mail', 'email'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('e_mail, customer_id', 'safe', 'on'=>'search'),
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
			'e_mail' => 'E Mail',
			'customer_id' => 'Customer',
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

		$criteria->compare('e_mail',$this->e_mail,true);
		$criteria->compare('customer_id',Yii::app()->user->getId());

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Whitelist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
