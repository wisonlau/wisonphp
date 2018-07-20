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
    private $Unionpay;
    private $PayPal;

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

    public function paySheBill(Unionpay $Unionpay, PayPal $PayPal)
    {
        $this->Unionpay = $Unionpay->pay();
        $this->PayPal = $PayPal->pay();
    }
}