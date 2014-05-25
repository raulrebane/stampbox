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
                        <p>Please enter new password for your <b>Stampbox</b> account</p>
                    </div>
                    <div class="feature">
                    </div>
                </div>
                <div class="col-md-6 darker">
                    <div class="vertical-middle">
                    <?php $form = $this->beginWidget(
                        'bootstrap.widgets.TbActiveForm',array(
                        'id' => 'ResetPasswd',
                        'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
                        ?>
                        <div class="form-group">
                            <?php echo $form->passwordField($model, 'newpassword', array('class' => 'form-control', 'id'=>'newpassword', 'placeholder'=>'Enter new password')); ?>
                            <?php echo $form->passwordField($model, 'verifynewpassword', array('class' => 'form-control', 'id'=>'verifynewpassword', 'placeholder'=>'Enter password again')); ?>
                        </div>
                        <button type="submit" class="btn btn-aqua">Save</button>
                    <?php $this->endWidget(); unset($form);?>                       
                    </div>
                </div>
            </div>
        </div>

        <script src="scripts/44101f0b.main.js"></script>
        <script src="scripts/a1187778.plugins.js"></script>
</body>
</html>