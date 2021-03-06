<?php

/**
 * This is the model class for table "ds.t_customer".
 *
 * The followings are the available columns in table 'ds.t_customer':
 * @property string $customer_id
 * @property string $username
 * @property string $firstname
 * @property string $lastname
 * @property string $password
 * @property string $last_seen
 * @property string $status
 * @property string $preferred_lang
 * @property integer $bad_logins
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'ds.t_customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, firstname, lastname, password', 'required'),
			array('bad_logins', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>128),
			array('firstname, lastname', 'length', 'max'=>100),
			array('password', 'length', 'max'=>64),
			array('status', 'length', 'max'=>1),
			array('preferred_lang', 'length', 'max'=>3),
			array('last_seen', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, username, firstname, lastname, password, last_seen, status, preferred_lang, bad_logins', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'password' => 'Password',
			'last_seen' => 'Last Seen',
			'status' => 'Status',
			'preferred_lang' => 'Preferred Lang',
			'bad_logins' => 'Bad Logins',
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

		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('last_seen',$this->last_seen,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('preferred_lang',$this->preferred_lang,true);
		$criteria->compare('bad_logins',$this->bad_logins);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}