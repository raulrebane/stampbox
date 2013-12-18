<?php
class WSController extends CController
{
    public function actions()
    {
        return array('api'=>array('class'=>'CWebServiceAction',),);
    }

     /**
     * @param string username
     * @param string password
     * @return boolean
     * @soap
     */
    public function Authuser($username, $password)
    {
        $record=TCustomer::model()->findByAttributes(array('username'=>$username));
        if ($record === null)
		{return false;}
        else if ($record->password !== crypt($password, $record->password))
		{return false;}
        else
		{return true;}
    }
}


