<?php

/**
 * This is the model class for table "ds.t_invitations".
 *
 * The followings are the available columns in table 'ds.t_invitations':
 * @property string $customer_id
 * @property string $invited_email
 * @property string $invited_when
 * @property integer $from_count
 * @property integer $to_count
 * @property string $invite
 * @property string $name
 */
class Invitations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invitations the static model class
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
		return 'ds.t_invitations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, invited_email', 'required'),
			array('from_count, to_count', 'numerical', 'integerOnly'=>true),
			array('invited_email, name', 'length', 'max'=>100),
			array('invite', 'length', 'max'=>1),
			array('invited_when', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, invited_email, invited_when, from_count, to_count, invite, name', 'safe', 'on'=>'search'),
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
			'invited_email' => 'Email',
			'invited_when' => 'Invitation date',
			'from_count' => 'Received',
			'to_count' => 'Sent',
			'invite' => 'Invite',
			'name' => 'Name',
		);
	}
        
        public function primaryKey() {
                return array('customer_id', 'invited_email');
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
                $criteria->compare('customer_id', Yii::App()->user->getId());
		$criteria->compare('invited_email',$this->invited_email,true);
		$criteria->compare('from_count',$this->from_count);
		$criteria->compare('to_count',$this->to_count);
		$criteria->compare('invite',$this->invite,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}