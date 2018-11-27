<?php

namespace Omnipay\FAC;

use Omnipay\Common\AbstractGateway;
use Omnipay\FAC\Message\Request\CompletePurchaseRequest;
use Omnipay\FAC\Message\Request\FetchTransactionRequest;
use Omnipay\FAC\Message\Request\PurchaseRequest;

class Gateway extends AbstractGateway
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FirstAtlanticCommerce';
    }

    /**
     * @@inheritdoc
     */
    public function getDefaultParameters()
    {
        return [
            'merchantId'       => null,
            'merchantPassword' => null,
            'acquirerId'       => null,
            'testMode'         => false,
        ];
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
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|FetchTransactionRequest
     */
    public function fetchTransaction(array $options = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    /**
     *  Authorize and immediately capture an amount on the customer’s card.
     *
     * @param array $parameters
     *
     * @return \Omnipay\FirstAtlanticCommerce\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\FAC\Message\Request\PurchaseRequest', $parameters);
    }
}