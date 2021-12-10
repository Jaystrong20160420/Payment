<?php

namespace Payment\query;

use Payment\common\BaseStrategy;
use Payment\common\ConfigInterface;

abstract class QueryStrategy implements BaseStrategy
{
    protected $requestData;
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function handle(array $requestData)
    {
        // TODO: Implement handle() method.
    }
}