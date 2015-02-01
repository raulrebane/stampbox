<?php

/**
 * This is the model class for table "ds.t_mailbox_config".
 *
 * The followings are the available columns in table 'ds.t_mailbox_config':
 * @property string $maildomain
 * @property string $mailtype
 * @property string $incoming_hostname
 * @property string $incoming_port
 * @property string $incoming_socket_type
 * @property string $incoming_auth
 * @property string $outgoing_hostname
 * @property string $outgoing_port
 * @property string $outgoing_socket_type
 * @property string $outgoing_auth
 */
class mailconfig extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return mailconfig the static model class
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
		return 'ds.t_mailbox_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('maildomain, mailtype', 'required'),
			array('maildomain', 'length', 'max'=>100),
			array('mailtype', 'length', 'max'=>10),
			array('incoming_hostname, incoming_port, incoming_socket_type, incoming_auth, outgoing_hostname, outgoing_port, outgoing_socket_type, outgoing_auth', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('maildomain, mailtype, incoming_hostname, incoming_port, incoming_socket_type, incoming_auth, outgoing_hostname, outgoing_port, outgoing_socket_type, outgoing_auth', 'safe', 'on'=>'search'),
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
			'maildomain' => 'Maildomain',
			'mailtype' => 'Mailtype',
			'incoming_hostname' => 'Mail server name',
			'incoming_port' => 'Port',
			'incoming_socket_type' => 'Connection security',
			'incoming_auth' => 'Incoming Auth',
			'outgoing_hostname' => 'Mail sending server',
			'outgoing_port' => 'Port',
			'outgoing_socket_type' => 'Security',
			'outgoing_auth' => 'Outgoing Auth',
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

		$criteria->compare('maildomain',$this->maildomain,true);
		$criteria->compare('mailtype',$this->mailtype,true);
		$criteria->compare('incoming_hostname',$this->incoming_hostname,true);
		$criteria->compare('incoming_port',$this->incoming_port,true);
		$criteria->compare('incoming_socket_type',$this->incoming_socket_type,true);
		$criteria->compare('incoming_auth',$this->incoming_auth,true);
		$criteria->compare('outgoing_hostname',$this->outgoing_hostname,true);
		$criteria->compare('outgoing_port',$this->outgoing_port,true);
		$criteria->compare('outgoing_socket_type',$this->outgoing_socket_type,true);
		$criteria->compare('outgoing_auth',$this->outgoing_auth,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}