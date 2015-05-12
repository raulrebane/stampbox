<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::app()->clientScript->registerCoreScript("jquery");
?>

<h1>You are registered. Here's small introduction</h1>
<div class="row step-a">
<div id="introcarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#introcarousel" data-slide-to="0" class="active"></li>
    <li data-target="#introcarousel" data-slide-to="1"></li>
    <li data-target="#introcarousel" data-slide-to="2"></li>
    <li data-target="#introcarousel" data-slide-to="3"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
        <img src="images/balance.png" alt="...">
        <div class="carousel-caption">
        Your stamps and earned credits balance
      </div>
    </div>
    <div class="item">
      <img src="images/email.png" alt="...">
      <div class="carousel-caption">
        Here you see your e-mail accounts registered with stampbox
      </div>
    </div>
    <div class="item">
      <img src="images/activity.png" alt="...">
      <div class="carousel-caption">
        In this section you see last 10 actions based on e-mails received or sent
      </div>
    </div>
    <div class="item">
      <img src="images/invitations.png" alt="...">
      <div class="carousel-caption">
          In this section you see how many people you have invited from your contacts <br><br>
        <a class="btn btn-aqua" href="<?php echo Yii::app()->createUrl('site/index')?>">Understood, Let's start</a>
      </div>
      
    </div>
    
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#introcarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#introcarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div></div>