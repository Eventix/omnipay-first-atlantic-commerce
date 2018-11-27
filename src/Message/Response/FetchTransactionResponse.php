<?php

namespace Omnipay\FAC\Message\Response;


class FetchTransactionResponse extends AbstractFACResponse
{
    public function getMessage(){
        return $this->getResponseCodeDescription();
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->isPaid();
    }

    /**
     * @return boolean
     */
    public function isOpen()
    {
        return !$this->isPaid();
    }

    /**
     * @return boolean
     */
    public function isCancelled()
    {
        return !$this->isPaid();
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
        return false;
    }

    /**
     * @return boolean
     */
    public function isPaid()
    {
        return $this->getReasonCode() == 1;
    }

    public function getOriginalResponseCode(){
        return isset($this->CCResults()['OriginalResponseCode']) ?  $this->CCResults()['OriginalResponseCode'] : null;
    }

    public function getReasonCode(){
        return isset($this->CCResults()['ReasonCode']) ?  $this->CCResults()['ReasonCode'] : null;
    }

    public function getResponseCodeDescription(){
        return isset($this->CCResults()['ReasonCodeDescription']) ?  $this->CCResults()['ReasonCodeDescription'] : null;
    }

    public function CCResults(){
        return isset($this->data[$this->requestResultName]['CreditCardTransactionResults']) ? $this->data[$this->requestResultName]['CreditCardTransactionResults'] : [];
    }
}