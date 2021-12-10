<?php

namespace Payment\common\Ali;

use Payment\common\Exception\PayException;
use Payment\Utils\ArrayUtil;

/**
 *  公钥模式
 */
class AliDefaultConfig extends AliConfig
{
    protected function initConfig($config)
    {
        parent::initConfig($config);

        $this->alipayrsaPublicKey = $config['alipay_rsa_public_key'];// 公钥
    }
}