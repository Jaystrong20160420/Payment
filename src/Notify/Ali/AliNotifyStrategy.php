<?php

namespace Payment\Notify\Ali;


use Payment\AliFormatConfig;
use Payment\common\Exception\PayException;
use Payment\Notify\Ali\data\AliAppPayNotifyDataHandler;
use Payment\Notify\Ali\data\AliAppRefundNotifyDataHandler;
use Payment\Notify\NotifyStrategy;
use Payment\Utils\ArrayUtil;
use Payment\Utils\RsaEncrypt;

class AliAppPayNotifyStrategy extends NotifyStrategy
{
    public function __construct(array $config)
    {
        parent::__construct($config);

        try {
            $this->config = AliFormatConfig::get($config['type'], $config);
        } catch (PayException $e) {
            throw $e;
        }
    }

    /** 异步通知参数
     *
     * @return array|false
     */
    protected function getNotifyData()
    {
        $data = empty($_POST) ? $_GET : $_POST;
        if (empty($data) || !is_array($data)) {
            return false;
        }

        return $data;
    }

    /**
     * @param $notifyType
     *
     * @return false
     */
    private function getNotifyDataHandler($notifyType)
    {
        static $handler;

        if (empty($handler)) {
            switch ($notifyType) {
                // 通知的类型。['trade_status_sync', 'batch_refund_notify', 'batch_trans_notify']
                case 'trade_status_sync':
                    $handler = new AliAppPayNotifyDataHandler();// 支付成功
                    break;
                case 'batch_refund_notify':
                    $handler = new AliAppRefundNotifyDataHandler();// 退款
                    break;
                default :
                    return false;
            }
        }

        return $handler;
    }

    /** 返回处理后的数据
     * @param array $notifyData
     *
     * @return false|mixed|void
     */
    protected function getReturnData(array $notifyData)
    {
        return $this->getNotifyDataHandler($notifyData['notify_type'])->getReturnData($notifyData);
    }

    /** 验证异步通知的数据
     * @param array $notifyData
     *
     * @return false|void
     */
    protected function checkNotifyData(array $notifyData)
    {
        // 验签
        if (!$this->verifySign($notifyData)) {
            return false;
        }

        // 检查请求是否来自支付宝**********不知道有没有，反正我查阅了资料是没有的哈
    }



    /** 验签
     * @param $notifyData
     *
     * @return bool
     */
    private function verifySign($notifyData)
    {
        $signType = strtoupper($notifyData['sign_type']);
        $sign     = $notifyData['sign'];

        // 第一步： 在通知返回参数列表中，除去 sign、sign_type 两个参数外，凡是通知返回回来的参数皆是待验签的参数
        $values = ArrayUtil::removeKeys($notifyData, ['sign', 'sign_type']);

        // 第二步： 将剩下参数进行 url_decode, 然后进行字典排序，组成字符串，得到待签名字符串：
        $values = urldecode($values);                  // url_decode
        $values = ArrayUtil::arraySort($values);       // 字典排序，组成字符串
        $preStr = ArrayUtil::createLinkstring($values);//待签名字符串

        // 第三步： 将签名参数（sign）使用 base64 解码为字节码串。
        // 第四步： 使用 RSA 的验签方法，通过签名字符串、签名参数（经过 base64 解码）及支付宝公钥验证签名。
       if ($signType === 'RSA') {// 使用rsa方式
            $rsa = new RsaEncrypt($this->config->rsaPrivateKey);

            return $rsa->rsaVerify($preStr, $sign);
        } else {
            return false;
        }
    }

    /**
     * @param bool   $flag
     * @param string $msg
     *
     * @return string
     */
    protected function replyNotify($flag, $msg = '')
    {
        if ($flag) {
            return 'success';
        } else {
            return 'fail';
        }
    }

}