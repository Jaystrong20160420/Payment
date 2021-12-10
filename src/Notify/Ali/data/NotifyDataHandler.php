<?php

namespace Payment\Notify\Ali\data;

abstract class NotifyDataHandler
{
    /**
     * @param array $notifyData
     *
     * @return mixed
     */
    abstract public function getReturnData(array $notifyData);

    /**
     * @param $value
     *
     * @return false|mixed
     */
    protected function formatParamValue($value)
    {
        return isset($value) ? $value : false;
    }

}