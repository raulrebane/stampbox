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
 * @property string $password1
 * @property string $last_seen
 * @property string $status
 * @property string $preferred_lang
 * @property integer $bad_logins
 *
 * The followings are the available model relations:
 * @property TCustomerMailbox[] $tCustomerMailboxes
 * @property TStamps[] $tStamps
 */
class TCustomer extends CActiveRecord
{
        public $password1;
        public $oldpassword;
        public $newpassword;
        
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
			array('oldpassword', 'length', 'max'=>64),
                    	array('newpassword', 'length', 'max'=>64),
                        array('password1', 'length', 'max'=>64),
			array('preferred_lang', 'length', 'max'=>10),
//			array('last_seen', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
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
			'tCustomerMailboxes' => array(self::HAS_MANY, 'TCustomerMailbox', 'customer_id'),
			'tStamps' => array(self::HAS_MANY, 'TStamps', 'customer_id'),
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
                        'password1' => 'Repeat password',
                        'newpassword' => 'New password',
                        'oldpassword' => 'Old password',
			'last_seen' => 'Last login',
			'status' => 'Status',
			'preferred_lang' => 'Preferred Lang',
			'bad_logins' => 'Bad Logins',
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TCustomer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
 
 /**
 * Generate a random salt in the crypt(3) standard Blowfish format.
 *
 * @param int $cost Cost parameter from 4 to 31.
 *
 * @throws Exception on invalid cost parameter.
 * @return string A Blowfish hash salt for use in PHP's crypt()
 */
	public function blowfishSalt($cost = 13)
	{
	    if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
	        throw new Exception("cost parameter must be between 4 and 31");
	    }
	    $rand = array();
	    for ($i = 0; $i < 8; $i += 1) {
	        $rand[] = pack('S', mt_rand(0, 0xffff));
	    }
	    $rand[] = substr(microtime(), 2, 6);
	    $rand = sha1(implode('', $rand), true);
	    $salt = '$2a$' . sprintf('%02d', $cost) . '$';
	    $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
	    return $salt;
	}
        
}
