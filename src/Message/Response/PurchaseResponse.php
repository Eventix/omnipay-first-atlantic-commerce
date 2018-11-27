<?php

namespace Omnipay\FAC\Message\Response;


class PurchaseResponse extends AbstractFACResponse
{
    /**
     * When you do a `purchase` the request is never successful because
     * you need to redirect off-site to complete the purchase.
     *
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return isset($this->data[$this->requestResultName]['SingleUseToken']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        return $this->getEndpoint() . 'MerchantPages/' . $this->getPageSet() . '/' . $this->getPageName() . '/'. $this->getSingleUseToken();
    }

    /**
     * Get single use token
     *
     * @return string
     */
    public function getSingleUseToken()
    {
        return isset($this->data[$this->requestResultName]['SingleUseToken']) ? $this->data[$this->requestResultName]['SingleUseToken'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getTransactionReference()
    {
        return $this->getSingleUseToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getPageSet()
    {
        return isset($this->data['pageSet']) ? $this->data['pageSet'] : null;
    }

    /**
     * @return string
     */
    public function getPageName()
    {
        return isset($this->data['pageName']) ? $this->data['pageName'] : null;
    }

}