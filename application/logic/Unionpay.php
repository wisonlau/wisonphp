<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 * Time: 11:50
 */

//银联支付
class Unionpay implements Pay  {
    public function __construct(){}

    public function pay()
    {
        echo 'pay bill by unionpay';
    }
}