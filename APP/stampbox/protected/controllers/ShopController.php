<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ShopController extends Controller
{
    
    public function actionBuy() 
       {
        $model = new Register;
        
        $this->render('Buy',array('model'=>$model,)); 
       }
    
    public function actionCart() 
       {
        $model = new Register;
        
        $this->render('cart',array('model'=>$model,)); 
       }    
 
}
?>
