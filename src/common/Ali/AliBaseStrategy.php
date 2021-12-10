<?php
namespace Payment\common\Ali;



use Payment\AliFormatConfig;
use Payment\common\BaseRequestData;
use Payment\common\BaseStrategy;
use Payment\common\Exception\PayException;

abstract class AliBaseStrategy implements BaseStrategy
{
    protected $config;
    protected $reqData;

    /**
     * @param array $config
     *
     * @throws PayException
     */
    public function __construct(array $config)
    {
        $this->config = AliFormatConfig::get($config['type'], $config);
    }

    /**
     * @param array $requestData
     *
     * @return mixed
     * @throws PayException
     */
    public function handle(array $requestData)
    {
        $buildClass = $this->getBuildRequestDataClass();

        try {
            $this->reqData = new $buildClass($this->config, $requestData);
        } catch (PayException $e) {
            throw $e;
        }

        $this->reqData->setSign();

        $data = $this->reqData->getRequestData();

        return $this->returnData($data);
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    protected function returnData($data)
    {
        return $data;
    }

    /**
     * @return BaseRequestData
     */
    abstract public function getBuildRequestDataClass();
}