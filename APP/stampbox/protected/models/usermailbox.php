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
 * @property boolean $sending_service 
 * @property boolean $receiving_service 
 * @property boolean $sorting_service
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
			array('e_mail', 'required'),
                        array('e_mail', 'email'),
                        //array('e_mail', 'checkregistered'),
			array('e_mail, e_mail_username, maildomain', 'length', 'max'=>100),
			array('e_mail_password', 'length', 'max'=>32),
                        array('status', 'length', 'max'=>1),
			array('e_mail_username, customer_id, maildomain, sending_service, receiving_service, sorting_service', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, e_mail, e_mail_username, e_mail_password, status, maildomain', 'safe', 'on'=>'search'),
		);
	}
        
        public function checkregistered($attribute,$params)
        {
            $customer = Yii::app()->db->createCommand(array('select'=> array('customer_id'),
                        'from' => 'ds.v_registered_email',
                        'where'=> 'username = :1',
                        'params' => array(':1'=>mb_convert_case($this->e_mail, MB_CASE_LOWER, "UTF-8")),))->queryRow();
            //Yii::log("DB query returned customer_id: " .CVarDumper::dumpAsString($customer), 'info', 'application');
            if ($customer == !FALSE) {
                $this->addError('useremail', 'This e-mail address is already registered');
                return false;
            }
         return true;
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
                        'sending_service'=>'Sending stamps',
                        'receiving_service'=>'Receiving stamps',
                        'sorting_service'=>'E-mail sorting'
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
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}