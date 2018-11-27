<?php

namespace Omnipay\FAC\Message\Request;


use Omnipay\FAC\Message\Response\FetchTransactionResponse;

/**
 * Class FetchTransactionRequest
 * @package Omnipay\Paynl\Message\Request
 *
 * @method FetchTransactionResponse send()
 */
class FetchTransactionRequest extends AbstractFACRequest
{

    /**
     * @var string;
     */
    protected $requestName = 'TransactionStatus';
    protected $endpointName = 'Services';

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('aquirerId', 'merchantId', 'merchantPassword', 'orderNumber');

        // Mandatory fields
        $data['Request'] = [
            'AcquirerId' => $this->getAquirerId(),
            'MerchantId' => $this->getMerchantId(),
            'OrderNumber' => $this->getOrderNumber(),
            'Password' => $this->getMerchantPassword(),
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
        $data = json_decode(json_encode((array)$xml), true);
        $data = array_merge($this->getParameters(), $data);
        $reponse = new FetchTransactionResponse($this, $data);
        $reponse->setRequestResultName($this->requestName);
        return $reponse;
    }
}