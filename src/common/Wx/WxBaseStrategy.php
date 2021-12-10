<?php
namespace payment\common\Wx;


use Payment\common\AliConfig;
use Payment\common\BaseStrategy;

abstract class WxBaseStrategy implements BaseStrategy
{
    protected $config;
    protected $reqData;

    public function __construct(array $config)
    {
        $this->config = new AliConfig($config);
    }

    public function handle(array $requestData)
    {
        // 微信支付逻辑
    }


    abstract public function getBuildRequestDataClass();
}