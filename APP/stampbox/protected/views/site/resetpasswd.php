<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="register-form imap-form">
    <?php if ($model->notified) { echo '<h1>Please check your e-mail!</h1>"       
            <div class="row step-a">
                <div class="col-md-6">
                    <div class="feature">
                        <p>We have sent you link to reset your <b>Stampbox</b>password.</p>
                    </div>
                    <div class="feature">
                    </div>
                </div>
                <div class="col-md-6 darker">
                    <div class="vertical-middle">';
                        echo '<button type="submit" class="btn btn-aqua">OK</button>';                      
                    echo '</div>
                </div>
            </div>
</div>'; }
    else { echo '<h1>Reset <b>your Stampbox</b> password</h1>
            <div class="row step-a">
                <div class="col-md-6">
                    <div class="feature">
                        <p>Enter your e-mail address and we will send you a link to reset your <b>Stampbox</b> login password</p>
                    </div>
                    <div class="feature">
                    </div>
                </div>
                <div class="col-md-6 darker">
                    <div class="vertical-middle">';
                    $form = $this->beginWidget('CActiveForm',array(
                        'id' => 'ResetPasswdForm','htmlOptions' => array('class' => 'form', 'role'=>'form'),));
                        echo '<div class="form-group">';
                            echo $form->emailField($model, 'emailaddress', array('class' => 'form-control', 'id'=>'emailaddress', 
                                'placeholder'=>'Enter email'));
                        echo '</div>
                        <button type="submit" class="btn btn-aqua">Continue</button>';
                    $this->endWidget(); unset($form);
                    echo '
                    </div>
                </div>
        </div>
</div>'; }
    ?>    
        <script src="scripts/44101f0b.main.js"></script>
        <script src="scripts/a1187778.plugins.js"></script>
</body>
</html>