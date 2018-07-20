<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 */

//PayPal支付
class PayPal implements Pay {
    public function __construct(){}

    public function pay()
    {
        echo 'pay bill by paypal';
    }
}