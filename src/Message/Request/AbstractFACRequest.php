<?php

namespace Omnipay\FAC\Message\Request;


use Omnipay\Common\Message\AbstractRequest;

/**
 * Class AbstractPaynlRequest
 * @package Omnipay\Paynl\Message\Request
 */
abstract class AbstractFACRequest extends AbstractRequest
{
    /**
     * FACPG2 live endpoint URL
     *
     * @var string
     */
    protected $liveEndpoint = 'https://marlin.firstatlanticcommerce.com/PGService/';

    /**
     * FACPG2 test endpoint URL
     *
     * @var string
     */
    protected $testEndpoint = 'https://ecm.firstatlanticcommerce.com/PGService/';

    /**
     * FACPG2 XML namespace
     *
     * @var string
     */
    protected $namespace = 'http://schemas.firstatlanticcommerce.com/gateway/data';

    /**
     * FACPG2 XML root
     *
     * @var string
     */
    protected $requestName;

    /**
     * Returns the amount formatted to match FAC's expectations.
     *
     * @return string The amount padded with zeros on the left to 12 digits and no decimal place.
     */
    protected function formatAmount()
    {
        $amount = $this->getAmount();

        $amount = str_replace('.', '', $amount);
        $amount = str_pad($amount, 12, '0', STR_PAD_LEFT);

        return $amount;
    }

    /**
     * Returns signature How to sign an FAC Authorize message in PHP
     *
     * @return string
     */
    protected function generateSignature()
    {
        $password = $this->getMerchantPassword();
        $data = [
            $this->getMerchantPassword(),
            $this->getMerchantId(),
            $this->getAquirerId(),
            $this->getOrderNumber(),
            $this->formatAmount(),
            $this->getCurrencyNumber(),
        ];
        $stringtohash = implode('', $data);
        $hash = sha1($stringtohash, true);
        $signature = base64_encode($hash);
        return $signature;
    }

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
     * Return the response object
     *
     * @param \SimpleXMLElement $xml Response xml object
     *
     * @return ResponseInterface
     */
    abstract protected function newResponse($xml);

    /**
     * Send the request payload
     *
     * @param array $data Request payload
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        // Ensure you append the ?wsdl query string to the URL
        $wsdlurl = $this->getEndpoint().$this->endpointName.'.svc?wsdl';
        $soapUrl = $this->getEndpoint().$this->endpointName.'.svc';

        $options = array(
            'location' => $soapUrl,
            'soap_version'=> SOAP_1_1,
            'exceptions'=>0,
            'trace'=>1,
            'cache_wsdl'=>WSDL_CACHE_NONE
        );

        $client = new \SoapClient($wsdlurl, $options);
        $result = $client->{$this->requestName}($data);

        return $this->response = $this->newResponse( $result );
    }

    /**
     * @param string $value sets your merchantId
     * @return $this
     */
    public function setMerchantId($value)
    {
        $this->setParameter('merchantId', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param string $value sets your merchantPassword
     * @return $this
     */
    public function setMerchantPassword($value)
    {
        $this->setParameter('merchantPassword', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantPassword()
    {
        return $this->getParameter('merchantPassword');
    }

    /**
     * @param string $value sets aquirerId
     * @return $this
     */
    public function setAquirerId($value)
    {
        $this->setParameter('aquirerId', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getAquirerId()
    {
        return $this->getParameter('aquirerId');
    }

    /**
     * @param string $value sets testmode
     * @return $this
     */
    public function setTestMode($value)
    {
        $this->setParameter('testMode', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    /**
     * @param string $value set Order Number
     * @return $this
     */
    public function setOrderNumber($value)
    {
        $this->setParameter('orderNumber', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->getParameter('orderNumber');
    }

    /**
     * @return string
     */
    public function getCurrencyNumber()
    {
        $curreny = $this->getCurrency();
        $currencies = [
            'EUR' => '0',
            'USD' => '840',
            'GTQ' => '320',
        ];
        return isset($currencies[$curreny]) ? $currencies[$curreny] : '840';
    }

    /**
     * @param string $value set PageSet
     * @return $this
     */
    public function setPageSet($value)
    {
        $this->setParameter('pageSet', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getPageSet()
    {
        return $this->getParameter('pageSet');
    }

    /**
     * @param string $value pageName
     * @return $this
     */
    public function setPageName($value)
    {
        $this->setParameter('pageName', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getPageName()
    {
        return $this->getParameter('pageName');
    }

    /**
     * @param string $value TransactionCode
     * @return $this
     */
    public function setTransactionCode($value)
    {
        $this->setParameter('transactionCode', $value);
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionCode()
    {
        return $this->getParameter('transactionCode');
    }
}