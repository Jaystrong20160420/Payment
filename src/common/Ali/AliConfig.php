<?php

namespace Payment\common\Ali;

use Payment\common\ConfigInterface;
use Payment\common\Exception\PayException;
use Payment\Utils\ArrayUtil;

class AliConfig extends ConfigInterface
{
    /**
     *  公钥模式
     */
    const MODE_RSA_PUBLIC_KEY = 'MODE_RSA_PUBLIC_KEY';
    /**
     *  公钥证书模式
     */
    const MODE_RSA_CERT_PUBLIC_KEY = 'MODE_RSA_CERT_PUBLIC_KEY';

    public $gatewayUrl = 'https://mapi.alipay.com/gateway.do?';

    public $appId;
    public $rsaPrivateKey;
    public $alipayrsaPublicKey;


    public $version  = '1.0';
    public $signType = 'RSA2';
    public $charset  = 'utf-8';
    public $format   = 'JSON';


    // 应用授权
    public $appAuthToken;

//    public $timestamp   = '';


    /**
     * @param array $config
     *
     * @throws PayException
     */
    public function __construct(array $config)
    {
        $this->initConfig($config);
    }

    /**
     * @param $config
     *
     * @throws PayException
     */
    protected function initConfig($config)
    {
        $config = ArrayUtil::paraFilter($config);

        // 验证
        if (empty($config['app_id'])) {
            throw new PayException('配置项错误：appId为空');
        }

        if (strlen($config['app_id']) > 32) {
            throw new PayException('appI最大长度为32');
        }


        if (empty($config['alipay_rsa_public_key'])) {
            throw new PayException('配置项错误: 公钥为空');
        }

        if (empty($config['rsa_private_key'])) {
            throw new PayException('配置项错误: 私钥为空');
        }

        // 签名类型:待定**********************************************
        if (key_exists('sign_type', $config)) {
            $this->signType = $config['sign_type'];
        }


        $this->appId         = $config['app_id'];         // 应用appId
        $this->rsaPrivateKey = $config['rsa_private_key'];// 私钥
    }
}