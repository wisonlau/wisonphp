<?php
/**
 * Created by PhpStorm.
 * User: wison
 * Date: 2018/7/20
 */

//付款
class PayBill {

    private $Alipay;
    private $Wechatpay;

    public function __construct( Alipay $Alipay, Wechatpay $Wechatpay )
    {
        $this->Alipay = $Alipay;
        $this->Wechatpay = $Wechatpay;
    }

    public function payMyBill()
    {
        $this->Alipay->pay();
        $this->Wechatpay->pay();
    }
}