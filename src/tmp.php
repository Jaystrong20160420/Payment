<?php

// 公共请求参数
$commonRequestData = [
    // 必选
    'app_id'         => 'app_id',
    'method'         => 'alipay.trade.app.pay',
    'charset'        => 'utf-8',
    'sign_type'      => 'RSA2',
    'sign'           => '',// 看文档
    'timestamp'      => '2014-07-24 03:07:50',
    'version'        => '1.0',
    'biz_content'    => '1.0',// 请求参数的集合，最大长度不限，除公共参数外所有请求参数都必须放在这个参数中传递，具体参照各产品快速接入文档

    // 可选
    'format'         => 'JSON',// 仅支持JSON
    'return_url'     => 'https://m.alipay.com/Gk8NF23',// HTTP/HTTPS开头字符串
    'notify_url'     => 'http://api.test.alipay.net/atinterface/receive_notify.htm',// 支付宝服务器主动通知商户服务器里指定的页面http/https路径。
    'app_auth_token' => '',// 详见应用授权概述
];

// 公共相应参数
$commonResponseData = [
    // 必选
    'code'     => '',// 网关返回码
    'msg'      => '',// 网关返回码描述
    'sign'     => '',// 签名

    // 可选
    'sub_code' => '',// 业务返回码，参见具体的API接口文档
    'sub_msg'  => '',// 业务返回码描述，参见具体的API接口文档
];