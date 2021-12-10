<?php

namespace Payment\common;

abstract class ConfigInterface
{
    public function toArray()
    {
        return get_object_vars($this);
    }
}