<?php

namespace Payment\common;

class GlobalConfig
{
    const ALI_APP_PAY        = 'ali_app_pay';           // 支付宝支付-App支付
    const ALI_APP_PAY_NOTIFY = 'ali_app_pay_notify';    // 支付宝支付-支付回调
    const ALI_QUERY_TRADE    = 'ali_query_trade';       // 统一收单线下交易查询接口
    const ALI_QUERY_REFUND   = 'ali_query_refund';      // 统一收单交易退款查询接口
}