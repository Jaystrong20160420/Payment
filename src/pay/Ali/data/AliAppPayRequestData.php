<?php

namespace Payment\pay\Ali\data;

use Payment\common\Ali\AliBaseRequestData;
use Payment\common\Exception\PayException;
use Payment\Utils\ArrayUtil;

class AliAppPayRequestData extends AliBaseRequestData
{
    public $method = 'alipay.trade.app.pay';

    public $alipaySdkVersion = "alipay-sdk-php-2020-04-15";

    // 用于异步通知的地址
    public $notifyUrl;

    // 用于同步通知的地址
    public $returnUrl;

    // 支付2.0请求参数
    public $bizContent;

    protected function checkDataParam()
    {
        if (!empty($this->postData['notify_url'])) {
            $this->notifyUrl = $this->postData['notify_url'];
        }

        if (!empty($this->postData['return_url'])) {
            $this->returnUrl = $this->postData['return_url'];
        }

        if (empty($this->postData['bizContent'])) {
            throw new PayException('请设置请求参数bizContent');
        }


        $this->checkBizContent();
    }

    protected function checkBizContent()
    {
        $bizContent = $this->postData['bizContent'];

        if (empty($bizContent['out_trade_no'])) {
            throw new PayException('订单号不能为空');
        }

        if (strlen($bizContent['out_trade_no']) > 64) {
            throw new PayException('订单号长度只允许在64个字符以内');
        }

        if (!is_numeric($bizContent['total_amount'])) {
            throw new PayException('支付金额必须是数字');
        }

        // 取值范围[0.01,100000000]，金额不能为0
        if (bccomp($bizContent['total_amount'], 0.01, 2) == -1
            || bccomp($bizContent['total_amount'], 100000000, 2) == 1) {
            throw new PayException('取值范围[0.01,100000000]，金额不能为0');
        }

        if (empty($bizContent['subject'])) {
            throw new PayException('请填写订单标题');
        }

        // 其他参数验证***************************************************************************************************

        $this->bizContent = $bizContent;
    }

    /**
     * @return mixed|void
     */
    protected function buildData()
    {
        $params['app_id']              = $this->appId;
        $params['method']              = $this->method;
        $params['format']              = $this->format;
        $params['sign_type']           = $this->signType;
        $params['timestamp']           = date("Y-m-d H:i:s");
        $params['alipay_sdk']          = $this->alipaySdkVersion;
        $params['charset']             = $this->charset;
        $params['version']             = $this->version;
        $params["app_cert_sn"]         = $this->appCertSN;
        $params["alipay_root_cert_sn"] = $this->alipayRootCertSN;
        $params['notify_url']          = $this->notifyUrl;
        $params['return_url']          = $this->returnUrl;
        $params['app_auth_token']      = $this->appAuthToken;
        $params['biz_content']         = json_encode($this->bizContent, JSON_UNESCAPED_UNICODE);

        $this->requestData = ArrayUtil::paraFilter($params);// 移除空值的key
    }

    /**
     * @return string
     */
    public function getRequestData()
    {
        return http_build_query($this->requestData);
    }

}