<?php

namespace Payment\common;

use Payment\Utils\ArrayUtil;

abstract class BaseRequestData
{
    /**
     * @var array
     */
    protected $postData;
    /**
     * @var array
     */
    protected $requestData;

    /**
     * @param ConfigInterface $config
     * @param array           $postData
     */
    public function __construct(ConfigInterface $config, array $postData)
    {
        $this->postData = array_merge($postData, $config->toArray());

        try {
            $this->checkDataParam();
        } catch (PayException $e) {
            throw $e;
        }
    }

    /** 请求参数
     * @return array
     */
    public function getRequestData()
    {
        return $this->requestData;
    }


    /**
     * 获取变量，通过魔术方法
     * @param string $name
     * @return null|string
     * @author helei
     */
    public function __get($name)
    {
        if (isset($this->postData[$name])) {
            return $this->postData[$name];
        }

        return null;
    }

    /**
     * 设置变量
     * @param $name
     * @param $value
     * @author helei
     */
    public function __set($name, $value)
    {
        $this->postData[$name] = $value;
    }

    /**
     * 设置签名
     * @author helei
     */
    public function setSign()
    {
        $this->buildData();

        $values = ArrayUtil::removeKeys($this->requestData, ['sign', 'sign_type']);

        $values = ArrayUtil::arraySort($values);

        $signStr = ArrayUtil::createLinkstring($values);

        $this->requestData['sign'] = $this->makeSign($signStr);
    }

    /** 验证本次请求的数据
     * @return mixed
     */
    abstract protected function checkDataParam();

    /** 构建本次请求的数据
     * @return mixed
     */
    abstract protected function buildData();

    /** 签名算法实现
     * @param $signStr
     *
     * @return mixed
     */
    abstract protected function makeSign($signStr);
}