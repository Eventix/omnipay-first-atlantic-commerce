<?php

namespace Omnipay\FAC\Message\Response;


use Omnipay\Common\Message\AbstractResponse;

abstract class AbstractFACResponse extends AbstractResponse
{

    /**
     * FACPG2 live endpoint URL
     *
     * @var string
     */
    protected $liveEndpoint = 'https://marlin.firstatlanticcommerce.com/';

    /**
     * FACPG2 test endpoint URL
     *
     * @var string
     */
    protected $testEndpoint = 'https://ecm.firstatlanticcommerce.com/';

    /**
     * FACPG2 XML root
     *
     * @var string
     */
    public $requestResultName;

    /**
     * Returns the live or test endpoint depending on TestMode.
     *
     * @return string Endpoint URL
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @return string|null Get the reponse code
     */
    public function getResponseCode()
    {
        return isset($this->data[$this->requestResultName]['ResponseCode']) ? $this->data[$this->requestResultName]['ResponseCode'] : null;
    }

    /**
     * @return string|null Get the reponse code
     */
    public function getResponseCodeDescription()
    {
        return isset($this->data[$this->requestResultName]['ResponseCodeDescription']) ? $this->data[$this->requestResultName]['ResponseCodeDescription'] : null;
    }

    /**
     * @return string
     */
    public function getTestMode()
    {
        return isset($this->data['testMode']) ? $this->data['testMode'] : false;
    }

    public function setRequestResultName($value){
        $this->requestResultName = $value. 'Result';
    }


}