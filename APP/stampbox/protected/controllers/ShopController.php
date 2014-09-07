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
        $dataProvider = new CActiveDataProvider('Offers', array('pagination'=>array('pageSize'=>100,)));
        
        $this->render('buy',array('dataProvider'=>$dataProvider,)); 
       }
    
    public function actionAddToCart($id) 
       {
        $offer = Offers::model()->find('offer_id=:1',array(':1'=>$id));
        $cart = Shoppingcart::model()->find('customer_id=:1', array(':1'=>Yii::app()->user->getId()));
        if ($cart === NULL)
        {
            $cart = new Shoppingcart();
            $cart->customer_id = Yii::app()->user->getId();
        }
        $cart->batch_id = $offer->batch_id;
        $cart->stamp_amount = $offer->offer_amount;
        $cart->price = $offer->offer_price;
        $cart->save();
        $this->render('cart',array('model'=>$cart,)); 
       }
       
    public function actionCart() 
       {
        $model = Shoppingcart::model()->find('customer_id=:1', array(':1'=>Yii::app()->user->getId()));
        $this->render('cart',array('model'=>$model,)); 
       }    
    
       public function actionPayPalExpress() {
           
       }
 
}
?>
