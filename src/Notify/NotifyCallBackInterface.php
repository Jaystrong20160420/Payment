<?php

namespace Payment\Notify;

interface NotifyCallBackInterface
{
    public function callback($returnData);
}