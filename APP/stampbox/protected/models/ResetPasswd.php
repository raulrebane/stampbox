<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ResetPasswd extends CFormModel
{
    public $emailaddress;
    public $resettoken;
    public $notified;
    public $newpassword;
    public $verifynewpassword;
    
    public function rules()
    {
            return array(
                    array('emailaddress, newpassword, verifynewpassword', 'required'),
                    array('emailaddress, resettoken, newpassword, verifynewpassword', 'safe'),
            );
    }
}
