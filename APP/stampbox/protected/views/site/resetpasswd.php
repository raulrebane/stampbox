<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="register-form imap-form">
    <h1>Reset <b>your Stampbox</b> password</h1>
            <div class="row step-a">
                <div class="col-md-6">
                    <div class="feature">
                        <p>Enter your e-mail address and we will send you a link to reset your <b>Stampbox</b> login password</p>
                    </div>
                    <div class="feature">
                    </div>
                </div>
                <div class="col-md-6 darker">
                    <div class="vertical-middle">
                    <?php $form = $this->beginWidget(
                        'bootstrap.widgets.TbActiveForm',array(
                        'id' => 'ResetPasswdForm',
                        'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
                        ?>
                        <div class="form-group">
                            <?php echo $form->emailField($model, 'emailaddress', array('class' => 'form-control', 'id'=>'emailaddress', 'placeholder'=>'Enter email')); ?>
                        </div>
                        <button type="submit" class="btn btn-aqua">Continue</button>
                    <?php $this->endWidget(); unset($form);?>                       
                    </div>
                </div>
            </div>
        </div>

        <script src="scripts/44101f0b.main.js"></script>
        <script src="scripts/a1187778.plugins.js"></script>
</body>
</html>