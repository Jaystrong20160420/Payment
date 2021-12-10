<?php

namespace Payment\Utils;

class Rsa2Encrypt
{
    /**
     * @var string
     */
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function encrypt($data)
    {
        if ($this->key === false) {
            return '';
        }

        $res = openssl_get_privatekey($this->key);

        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);

        return $sign;
    }
}