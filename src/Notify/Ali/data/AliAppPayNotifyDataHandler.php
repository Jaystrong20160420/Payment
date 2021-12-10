<?php

namespace Payment\Notify\Ali\data;


class AliAppPayNotifyDataHandler extends NotifyDataHandler
{
    /**
     * @param array $notifyData
     */
    public function getReturnData(array $notifyData)
    {
        return [
            'notify_time'      => $notifyData['notify_time'],// 通知的发送时间
            'trade_status'     => $this->formatParamValue($notifyData['trade_status']),// 交易目前所处的状态
            'app_id'           => $notifyData['app_id'],// 支付宝分配给开发者的应用 APPID
            'trade_no'         => $notifyData['trade_no'],// 支付宝交易凭证号
            'out_trade_no'     => $this->formatParamValue($notifyData['out_trade_no']),// 原支付请求的商家订单号
            'total_amount'     => $this->formatParamValue($notifyData['total_amount']),// 本次交易支付的订金金额，单位为人民币（元）
            'receipt_amount'   => $this->formatParamValue($notifyData['receipt_amount']),// 商家在交易中实际收到的款项，单位为人民币（元）
            'buyer_pay_amount' => $this->formatParamValue($notifyData['buyer_pay_amount']),// 用户在交易中支付的金额
            'subject'          => $this->formatParamValue($notifyData['subject']),// 商品的标题/交易标题/订单标题/订单关键字等，是请求时对应的参数，在通知中原样传回
            'body'             => $this->formatParamValue($notifyData['body']),// 该笔订单的备注、描述、明细等。对应请求时的 body 参数，在通知中原样传回
        ];
    }
}