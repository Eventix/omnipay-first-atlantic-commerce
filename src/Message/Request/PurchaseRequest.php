<?php

namespace Omnipay\FAC\Message\Request;


use Omnipay\Common\Item;
use Omnipay\FAC\Message\Response\PurchaseResponse;

/**
 * Class PurchaseRequest
 * @package Omnipay\FAC\Message\Request
 *
 * @method PurchaseResponse send()
 */
class PurchaseRequest extends AbstractFACRequest
{

    /**
     * @var string;
     */
    protected $requestName = 'HostedPageAuthorize';
    protected $endpointName = 'HostedPage';

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('aquirerId', 'merchantId', 'merchantPassword', 'orderNumber', 'transactionCode');

        // Mandatory fields
        $data['Request'] = [
            'TransactionDetails' => [
                'AcquirerId' => $this->getAquirerId(),
                'Amount' => $this->formatAmount(),
                'Currency' => $this->getCurrencyNumber(),
                'CurrencyExponent' => $this->getCurrencyExponent(),
                'IPAddress' => $this->getClientIp(),
                'MerchantId' => $this->getMerchantId(),
                'OrderNumber' => $this->getOrderNumber(),
                'Signature' => $this->generateSignature(),
                'SignatureMethod' => 'SHA1',
                'TransactionCode' => $this->getTransactionCode(),
            ],
            'CardHolderResponseURL' => $this->getReturnUrl(),
        ];

        return $data;
    }

    /**
     * Return the tokenize response object
     *
     * @param \SimpleXMLElement $xml Response xml object
     *
     * @return CreateCardResponse
     */
    protected function newResponse($xml)
    {
        $data = json_decode(json_encode((array)$xml),true);
        $data = array_merge($this->getParameters(), $data);
        $reponse = new PurchaseResponse($this, $data);
        $reponse->setRequestResultName($this->requestName);
        return $reponse;
    }
}