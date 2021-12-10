<?php

namespace Payment\common;

interface BaseStrategy
{
    public function handle(array $requestData);
}