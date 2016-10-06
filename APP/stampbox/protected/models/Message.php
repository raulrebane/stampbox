<?php

/**
 * This is the model class for table "ds.t_messages".
 *
 */
class Message extends CActiveRecord
{
    public $message_id;
    public $customer_id;
    public $message_type;
    public $page_id;
    public $message;
 
    public static function model($className=__CLASS__)	{
		return parent::model($className);
    }

    public function tableName()	{
        return 'ds.t_messages';
    }
        
    public function addMessage($i_type, $i_page, $i_message) {
        $this->customer_id = Yii::app()->user->getId();
        $this->message_type = $i_type;
        $this->page_id = $i_page;
        $this->message = $i_message;
        $this->save();
    }
}