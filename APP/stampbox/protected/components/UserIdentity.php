<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */

// trial code to integrate with ds.t_customer table in database
//

class UserIdentity extends CUserIdentity
{

    private $_id;
    public function authenticate()
    {

	$record=TCustomer::model()->findByAttributes(array('username'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if ($record->password !== crypt($this->password, $record->password))  
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else

        {
            $this->_id=$record->customer_id;
            $this->errorCode=self::ERROR_NONE;
            $this->setState('name', $record->firstname);
            $this->setState('username', $record->username);
        }
        return !$this->errorCode;
    }
 

    public function getId()
    {
        return $this->_id;
    }


}

