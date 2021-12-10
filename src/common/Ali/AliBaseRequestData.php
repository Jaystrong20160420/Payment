<?php

namespace Payment\common\Ali;

use Payment\common\Ali\AliConfig;
use Payment\common\BaseRequestData;
use Payment\Common\PayException;
use Payment\Utils\Rsa2Encrypt;
use Payment\Utils\RsaEncrypt;


/**
 * @property $gatewayUrl
 * @property $appId
 * @property $version
 * @property $signType
 * @property $charset
 * @property $format
 * @property $rsaPrivateKey
 * @property $alipayrsaPublicKey
 * @property $appAuthToken
 */
abstract class AliBaseRequestData extends BaseRequestData
{
    /**
     * @param \Payment\common\Ali\AliConfig $config
     * @param array                         $reqData
     */
    public function __construct(AliConfig $config, array $reqData)
    {
        parent::__construct($config, $reqData);

    }

    /** 签名
     * @param $signStr
     *
     * @return mixed|string
     */
    protected function makeSign($signStr)
    {
        switch ($this->signType) {
            case 'MD5' :
                $signStr .= $this->md5Key;// 此处不需要通过 & 符号链接
                $sign = md5($signStr);
                break;
            case 'RSA' :
                $sign = (new RsaEncrypt($this->rsaPrivateKey))->encrypt($signStr);
                break;
            case 'RSA2' :
                $sign = (new Rsa2Encrypt($this->rsaPrivateKey))->encrypt($signStr);
                break;
            default :
                $sign = '';
        }

        return $sign;
    }

    /** 验证
     * @return mixed
     */
    abstract protected function checkBizContent();
}