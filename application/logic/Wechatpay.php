<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 * Time: 11:12
 */

//微信支付
class Wechatpay implements Pay  {
    public function __construct(){}

    public function pay()
    {
        echo 'pay bill by wechatpay';
    }
}