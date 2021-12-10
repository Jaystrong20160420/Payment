<?php

namespace Payment\Notify;

abstract class NotifyStrategy
{
    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        /* 设置内部字符编码为 UTF-8 */
        mb_internal_encoding("UTF-8");
    }

    final public function handle(NotifyCallBackInterface $notifyCallBack)
    {
        $notifyData = $this->getNotifyData();

        $checkResult = $this->checkNotifyData($notifyData);

        if (!$checkResult) {
            return $this->replyNotify(false, '获取数据通知失败');
        }

        $flag = $this->callback($notifyCallBack, $notifyData);

        if ($flag) {
            $msg = 'OK';
        } else {
            $msg = '逻辑出现了错误';
        }

        return $this->replyNotify($flag, $msg);
    }

    protected function callback(NotifyCallBackInterface $notifyCallBack, $notifyData)
    {
        $returnData = $this->getReturnData($notifyData);
        if (false === $returnData) {
            return false;
        }

        return $notifyCallBack->callback($returnData);
    }

    /** 如果获取数据失败，返回false
     * @return array|false
     */
    abstract protected function getNotifyData();

    /**
     * @param array $notifyData
     *
     * @return mixed
     */
    abstract protected function getReturnData(array $notifyData);


    /** 检查异步通知的数据是否合法
     * @param array $data
     *
     * @return mixed
     */
    abstract protected function checkNotifyData(array $data);


    /**
     * @param boolean $flag
     * @param string $msg
     *
     * @return mixed
     */
    abstract protected function replyNotify($flag, $msg);
}