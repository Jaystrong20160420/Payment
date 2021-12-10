<?php

namespace Payment\common\Ali;

use Payment\common\Exception\PayException;
use Payment\Utils\ArrayUtil;

/**
 *  公钥证书模式
 */
class AliCertConfig extends AliConfig
{
    public $appCertPath;
    public $alipayPublicCertPath;
    public $rootCertPath;

    public $isCheckAlipayPublicCert;
    public $appCertSN;
    public $alipayRootCertSN;

    /**
     * @param $config
     *
     * @throws PayException
     */
    protected function initConfig($config)
    {
        parent::initConfig($config);

        if (empty($config['app_cert_path'])) {
            throw new PayException('请设置应用公钥证书路径');
        }

        if (!is_file($config['app_cert_path'])) {
            throw new PayException('文件不合法：应用公钥证书');
        }

        if (empty($config['alipay_public_cert_path'])) {
            throw new PayException('请设置支付宝公钥证书路径');
        }

        if (!is_file($config['alipay_public_cert_path'])) {
            throw new PayException('文件不合法：支付宝公钥证书');
        }

        if (empty($config['root_cert_path'])) {
            throw new PayException('请设置支付宝根证书路径');
        }

        if (!is_file($config['root_cert_path'])) {
            throw new PayException('文件不合法：支付宝根证书');
        }

        $this->isCheckAlipayPublicCert = true;

        $this->appCertPath          = $config['app_cert_path'];          // 应用公钥证书路径
        $this->alipayPublicCertPath = $config['alipay_public_cert_path'];// 支付宝公钥证书路径
        $this->rootCertPath         = $config['root_cert_path'];         // 支付宝根证书路径

        $this->alipayrsaPublicKey = $this->getPublicKey($this->alipayPublicCertPath);// 公钥
        $this->appCertSN          = $this->getCertSN($this->appCertPath);            // 调用getCertSN获取证书序列号
        $this->alipayRootCertSN   = $this->getRootCertSN($this->rootCertPath);       // 调用getRootCertSN获取支付宝根证书序列号
    }

    /**
     * 从证书中提取公钥
     *
     * @param $cert
     *
     * @return mixed
     */
    public function getPublicKey($certPath)
    {
        $cert       = file_get_contents($certPath);
        $pkey       = openssl_pkey_get_public($cert);
        $keyData    = openssl_pkey_get_details($pkey);
        $public_key = str_replace('-----BEGIN PUBLIC KEY-----', '', $keyData['key']);
        $public_key = trim(str_replace('-----END PUBLIC KEY-----', '', $public_key));
        return $public_key;
    }

    /**
     * 从证书中提取序列号
     *
     * @param $cert
     *
     * @return string
     */
    public function getCertSN($certPath)
    {
        $cert = file_get_contents($certPath);
        $ssl  = openssl_x509_parse($cert);
        $SN   = md5(ArrayUtil::array2string(array_reverse($ssl['issuer'])) . $ssl['serialNumber']);
        return $SN;
    }

    /**
     * 提取根证书序列号
     *
     * @param $cert  根证书
     *
     * @return string|null
     */
    public function getRootCertSN($certPath)
    {
        $cert                        = file_get_contents($certPath);
        $this->alipayRootCertContent = $cert;
        $array                       = explode("-----END CERTIFICATE-----", $cert);
        $SN                          = null;
        for ($i = 0; $i < count($array) - 1; $i++) {
            $ssl[$i] = openssl_x509_parse($array[$i] . "-----END CERTIFICATE-----");
            if (strpos($ssl[$i]['serialNumber'], '0x') === 0) {
                $ssl[$i]['serialNumber'] = $this->hex2dec($ssl[$i]['serialNumber']);
            }
            if ($ssl[$i]['signatureTypeLN'] == "sha1WithRSAEncryption" || $ssl[$i]['signatureTypeLN'] == "sha256WithRSAEncryption") {
                if ($SN == null) {
                    $SN = md5(ArrayUtil::array2string(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                } else {

                    $SN = $SN . "_" . md5(ArrayUtil::array2string(array_reverse($ssl[$i]['issuer'])) . $ssl[$i]['serialNumber']);
                }
            }
        }
        return $SN;
    }

    /** 私有方法 0x转高精度数字
     * @param $hex
     *
     * @return float
     */
    private function hex2dec($hex)
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return round($dec, 0);
    }

}