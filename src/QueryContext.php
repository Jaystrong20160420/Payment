<?php
namespace Payment;

use Payment\common\BaseStrategy;

class QueryContext
{
    /**
     * @var BaseStrategy
     */
    private $query;

    public function __construct($type, $config)
    {

    }

    public function execute()
    {
        $this->query->handle();
    }
}