<?php

namespace Payment;

use Payment\common\BaseStrategy;
use Payment\common\Exception\PayException;
use Payment\common\GlobalConfig;
use Payment\Notify\Ali\AliAppPayNotifyStrategy;

class NotifyContext
{
    /**
     * @var BaseStrategy
     */
    private $notify;

    public function __construct($type, $config)
    {
        switch ($type) {
            case GlobalConfig::ALI_APP_PAY_NOTIFY :
                $this->payment = new AliAppPayNotifyStrategy($config);// 支付宝app支付回调
                break;
            default :
                throw new PayException('当前仅支持：支付宝 与 微信');
        }
    }

    public function execute($data)
    {
        if (! $this->notify instanceof BaseStrategy) {
            throw new PayException('请检查初始化是否正确');
        }

        try {
            return $this->notify->handle($data);
        } catch (PayException $e) {
            throw $e;
        }
    }
}