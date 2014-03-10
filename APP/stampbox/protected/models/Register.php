<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Register extends CFormModel
{
	public $username;
        public $firstname;
        public $lastname;
	public $password;
	public $passwordrepeat;
        public $userlang;
        
        // mailbox config related fields
        public $maildomain;
        public $mailtype;
        public $incoming_hostname;
        public $incoming_port;
        public $incoming_socket_type;
        public $incoming_auth;
        public $e_mail_username;
        public $e_mail_password;
        public $registereddomain;
        public $registeredemail;
        public $top_senders;
        public $Invitations;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// required fields
			array('username, firstname, lastname, password, passwordrepeat, userlang', 'required'),
			array('username', 'length', 'max'=>128),
			array('firstname, lastname', 'length', 'max'=>100),
			array('password, passwordrepeat', 'length', 'max'=>16),
                        array('username', 'email'),
                        array('password', 'compare', 'compareAttribute'=>'passwordrepeat'),
                        array('maildomain, mailtype, incoming_hostname, incoming_port,e_mail_username,e_mail_password', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'E-mail',
                        'firstname'=>'First name',
                        'lastname'=>'Last name',
                        'password'=>'password',
                        'passwordrepeat'=>'Repeat password',
                        'userlang'=>'Language',
                        'maildomain'=>'E-mail provider',
                        'incoming_hostname'=>'Mail server name',
                        'incoming_port'=>'Mail server port',
                        'e_mail_username'=>'E-Mail username',
                        'e_mail_password'=>'E-Mail password',
		);
	}



}

