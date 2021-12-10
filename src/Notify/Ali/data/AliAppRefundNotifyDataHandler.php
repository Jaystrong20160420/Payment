<?php

namespace Payment\Notify\Ali\data;

class AliAppRefundNotifyDataHandler extends NotifyDataHandler
{
    /**
     * @param array $notifyData
     */
    public function getReturnData(array $notifyData)
    {
        return [
            'notify_time'  => $notifyData['notify_time'],// 通知的发送时间
            'trade_status' => $this->formatParamValue($notifyData['trade_status']),// 交易目前所处的状态
            'app_id'       => $notifyData['app_id'],// 支付宝分配给开发者的应用 APPID
            'trade_no'     => $notifyData['trade_no'],// 支付宝交易凭证号
            'out_trade_no' => $this->formatParamValue($notifyData['out_trade_no']),// 原支付请求的商家订单号
            'refund_fee'   => $this->formatParamValue($notifyData['refund_fee']),// 退款通知中，返回总退款金额，单位为人民币（元）
            'total_amount' => $this->formatParamValue($notifyData['out_biz_no']),// 本次交易支付的订金金额，单位为人民币（元）
            'out_biz_no'   => $this->formatParamValue($notifyData['out_biz_no']),// 商家业务 ID，主要是退款通知中返回退款申请的流水号
            'gmt_refund'   => $this->formatParamValue($notifyData['gmt_refund']),// 该笔订单的退款时间。格式为 yyyy-MM-dd HH:mm:ss.S
            'gmt_close'    => $this->formatParamValue($notifyData['gmt_refund']),// 该笔订单的结束时间。格式为 yyyy-MM-dd HH:mm:ss
        ];
    }
}