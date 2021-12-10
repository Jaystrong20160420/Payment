<?php
namespace Payment;

use Payment\pay\Ali\AliAppPayStrategy;
use Payment\common\BaseStrategy;
use Payment\common\GlobalConfig;
use Payment\Common\Exception\PayException;

class payContext
{
    /**
     * @var BaseStrategy
     */
    private $payment;

    public function __construct($type, $config)
    {
        switch ($type) {
            case GlobalConfig::ALI_APP_PAY :
                $this->payment = new AliAppPayStrategy($config);
                break;
            default :
                throw new PayException('当前仅支持：支付宝 与 微信');
        }
    }

    public function execute($data)
    {
        if (! $this->payment instanceof BaseStrategy) {
            throw new PayException('请检查初始化是否正确');
        }

        try {
            return $this->payment->handle($data);
        } catch (PayException $e) {
            throw $e;
        }
    }
}