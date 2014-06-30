<?php

/**
 * This is the model class for table "ds.t_customer_mailbox".
 *
 * The followings are the available columns in table 'ds.t_customer_mailbox':
 * @property string $customer_id
 * @property string $e_mail
 * @property string $e_mail_username
 * @property string $e_mail_password
 * @property string $status
 * @property string $maildomain
 * @property string $worker_ip
 * @property string $worker_type
 * @property string $last_seen
 */
class usermailbox extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return usermailbox the static model class
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
		return 'ds.t_customer_mailbox';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, e_mail, status, maildomain', 'required'),
			array('e_mail, e_mail_username, maildomain', 'length', 'max'=>100),
			array('e_mail_password', 'length', 'max'=>32),
			array('status', 'length', 'max'=>1),
			array('worker_type', 'length', 'max'=>20),
			array('last_seen', 'length', 'max'=>6),
			array('worker_ip', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, e_mail, e_mail_username, e_mail_password, status, maildomain, worker_ip, worker_type, last_seen', 'safe', 'on'=>'search'),
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
			'e_mail' => 'E Mail',
			'e_mail_username' => 'E Mail Username',
			'e_mail_password' => 'E Mail Password',
			'status' => 'Status',
			'maildomain' => 'Maildomain',
			'worker_ip' => 'Worker Ip',
			'worker_type' => 'Worker Type',
			'last_seen' => 'Last Seen',
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

		$criteria->compare('customer_id',Yii::app()->user->getId(),true);
		$criteria->compare('e_mail',$this->e_mail,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('worker_type',$this->worker_type,true);
		$criteria->compare('last_seen',$this->last_seen,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}