<?php

namespace Payment;

use Payment\common\Ali\AliCertConfig;
use Payment\common\Ali\AliConfig;
use Payment\common\Ali\AliDefaultConfig;
use Payment\common\Exception\PayException;

class AliFormatConfig
{
    private static $config;

    public static function get($type, $config)
    {
        switch ($type) {
            case AliConfig::MODE_RSA_PUBLIC_KEY :
                self::$config = new AliDefaultConfig($config);
                break;
            case AliConfig::MODE_RSA_CERT_PUBLIC_KEY :
                self::$config = new AliCertConfig($config);
            default :
                throw new PayException('支付宝支付：无效的配置类型');
        }

        return self::$config;
    }
}