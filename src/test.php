<?php

$basic = function ($classname) {
    $file = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $classname . '.php';

    if (!file_exists($file)) {
        echo "类不存在：{$classname}";
    }

    require_once $file;
};

\spl_autoload_register($basic);

use Payment\PayContext;

$config = [
    'app_id'                  => 'rsa',
    'sign_type'               => 'rsa',
    'alipay_rsa_public_key'   => 'rsa',
    'rsa_private_key'         => 'rsa',
    'app_cert_path'           => 'E:\BaiduNetdiskDownload\Redis.text',
    'alipay_public_cert_path' => 'E:\BaiduNetdiskDownload\Redis.text',
    'root_cert_path'          => 'E:\BaiduNetdiskDownload\Redis.text',
    'type'                    => 'MODE_RSA_CERT_PUBLIC_KEY',
    'sign_type'               => 'RSA2',

];

$instance = new PayContext(\Payment\common\GlobalConfig::ALI_APP_PAY, $config);
$result   = $instance->execute([
    'bizContent' => [
        'out_trade_no' => 'sdkfjsklf',
        'total_amount' => '3',
        'subject'      => '支付',
    ],
]);


print_r($result);