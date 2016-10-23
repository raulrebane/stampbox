<?php

/**
 * @property string $customer_id
 * @property string $invited_email
 * @property string $invited_when
 * @property integer $from_count
 * @property integer $to_count
 * @property string $invite
 * @property string $name
 */
class Invite extends CFormModel
{
    public $invite_email;
    public $mailboxlist;
    public $emailslist;
    public $loading_inprogress;
    public $invite_list;
    public $invited_list;
    public $task_id;
    public $percent_complete;
}