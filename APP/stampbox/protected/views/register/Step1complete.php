<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step1: Complete';

?>
                  <div class="vertical-middle">
                        <h2>Stampbox account created!</h2>
                        <h3>Confirmation e-mail was sent to <?php echo $model->useremail ?> Please follow instructions in that e-mail.</h3>
                        <p>If you have problems receiving confirmation<br>
                            e-mail <a href="register-confirmed.html">click here</a> to resend.</p>
                    </div>
