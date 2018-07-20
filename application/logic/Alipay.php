<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 * Time: 11:11
 */

//支付宝支付
class Alipay implements Pay {
    public function __construct(){}

    public function pay()
    {
        echo 'pay bill by alipay';
    }
}