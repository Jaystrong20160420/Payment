<?php

namespace Payment\pay\Ali;

use Payment\common\Ali\AliBaseStrategy;
use Payment\pay\Ali\data\AliAppPayRequestData;

class AliAppPayStrategy extends AliBaseStrategy
{
    /**
     * @return string
     */
    public function getBuildRequestDataClass()
    {
        return AliAppPayRequestData::class;
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function returnData($data)
    {
        return $this->config->gatewayUrl . $data;
    }

}