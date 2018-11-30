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
        $currency = $this->getCurrency();
        $currencies = [
            "AFN" => "971",
            "DZD" => "012",
            "ARS" => "032",
            "AMD" => "051",
            "AWG" => "533",
            "AUD" => "036",
            "AZN" => "944",
            "BSD" => "044",
            "BHD" => "048",
            "THB" => "764",
            "PAB" => "590",
            "BBD" => "052",
            "BYR" => "974",
            "BZD" => "084",
            "BMD" => "060",
            "VEF" => "937",
            "BOB" => "068",
            "BRL" => "986",
            "BND" => "096",
            "BGN" => "975",
            "BIF" => "108",
            "CAD" => "124",
            "CVE" => "132",
            "KYD" => "136",
            "XOF" => "952",
            "XAF" => "950",
            "XPF" => "953",
            "CLP" => "152",
            "COP" => "170",
            "KMF" => "174",
            "CDF" => "976",
            "BAM" => "977",
            "NIO" => "558",
            "CRC" => "188",
            "HRK" => "191",
            "CUP" => "192",
            "CZK" => "203",
            "GMD" => "270",
            "DKK" => "208",
            "MKD" => "807",
            "DJF" => "262",
            "STD" => "678",
            "DOP" => "214",
            "VND" => "704",
            "XCD" => "951",
            "EGP" => "818",
            "SVC" => "222",
            "ETB" => "230",
            "EUR" => "978",
            "FKP" => "238",
            "FJD" => "242",
            "HUF" => "348",
            "GHS" => "936",
            "GIP" => "292",
            "HTG" => "332",
            "PYG" => "600",
            "GNF" => "324",
            "GYD" => "328",
            "HKD" => "344",
            "UAH" => "980",
            "ISK" => "352",
            "INR" => "356",
            "IRR" => "364",
            "IQD" => "368",
            "JMD" => "388",
            "JOD" => "400",
            "KES" => "404",
            "PGK" => "598",
            "LAK" => "418",
            "KWD" => "414",
            "MWK" => "454",
            "AOA" => "973",
            "MMK" => "104",
            "GEL" => "981",
            "LVL" => "428",
            "LBP" => "422",
            "ALL" => "008",
            "HNL" => "340",
            "SLL" => "694",
            "LRD" => "430",
            "LYD" => "434",
            "SZL" => "748",
            "LTL" => "440",
            "LSL" => "426",
            "MGA" => "969",
            "MYR" => "458",
            "MUR" => "480",
            "MXN" => "484",
            "MXV" => "979",
            "MDL" => "498",
            "MAD" => "504",
            "MZN" => "943",
            "BOV" => "984",
            "NGN" => "566",
            "ERN" => "232",
            "NAD" => "516",
            "NPR" => "524",
            "ANG" => "532",
            "ILS" => "376",
            "RON" => "946",
            "TWD" => "901",
            "NZD" => "554",
            "BTN" => "064",
            "KPW" => "408",
            "NOK" => "578",
            "PEN" => "604",
            "MRO" => "478",
            "TOP" => "776",
            "PKR" => "586",
            "MOP" => "446",
            "CUC" => "931",
            "UYU" => "858",
            "PHP" => "608",
            "GBP" => "826",
            "BWP" => "072",
            "QAR" => "634",
            "GTQ" => "320",
            "ZAR" => "710",
            "OMR" => "512",
            "KHR" => "116",
            "MVR" => "462",
            "IDR" => "360",
            "RUB" => "643",
            "RWF" => "646",
            "SHP" => "654",
            "SAR" => "682",
            "RSD" => "941",
            "SCR" => "690",
            "SGD" => "702",
            "SBD" => "090",
            "KGS" => "417",
            "SOS" => "706",
            "TJS" => "972",
            "SSP" => "728",
            "LKR" => "144",
            "XSU" => "994",
            "SDG" => "938",
            "SRD" => "968",
            "SEK" => "752",
            "CHF" => "756",
            "SYP" => "760",
            "BDT" => "050",
            "WST" => "882",
            "TZS" => "834",
            "KZT" => "398",
            "TTD" => "780",
            "MNT" => "496",
            "TND" => "788",
            "TRY" => "949",
            "TMT" => "934",
            "AED" => "784",
            "UGX" => "800",
            "COU" => "970",
            "CLF" => "990",
            "UYI" => "940",
            "USD" => "840",
            "UZS" => "860",
            "VUV" => "548",
            "CHE" => "947",
            "CHW" => "948",
            "KRW" => "410",
            "YER" => "886",
            "JPY" => "392",
            "CNY" => "156",
            "ZMK" => "894",
            "ZWL" => "932",
            "PLN" => "985"
        ];
        return isset($currencies[$currency]) ? $currencies[$currency] : '840';
    }

    /**
     * @return string
     */
    public function getCurrencyExponent()
    {
        $currency = $this->getCurrency();
        $currencies = [
            "AFN" => "2",
            "DZD" => "2",
            "ARS" => "2",
            "AMD" => "2",
            "AWG" => "2",
            "AUD" => "2",
            "AZN" => "2",
            "BSD" => "2",
            "BHD" => "3",
            "THB" => "2",
            "PAB" => "2",
            "BBD" => "2",
            "BYR" => "0",
            "BZD" => "2",
            "BMD" => "2",
            "VEF" => "2",
            "BOB" => "2",
            "BRL" => "2",
            "BND" => "2",
            "BGN" => "2",
            "BIF" => "0",
            "CAD" => "2",
            "CVE" => "2",
            "KYD" => "2",
            "XOF" => "0",
            "XAF" => "0",
            "XPF" => "0",
            "CLP" => "0",
            "COP" => "2",
            "KMF" => "0",
            "CDF" => "2",
            "BAM" => "2",
            "NIO" => "2",
            "CRC" => "2",
            "HRK" => "2",
            "CUP" => "2",
            "CZK" => "2",
            "GMD" => "2",
            "DKK" => "2",
            "MKD" => "2",
            "DJF" => "0",
            "STD" => "2",
            "DOP" => "2",
            "VND" => "0",
            "XCD" => "2",
            "EGP" => "2",
            "SVC" => "2",
            "ETB" => "2",
            "EUR" => "2",
            "FKP" => "2",
            "FJD" => "2",
            "HUF" => "2",
            "GHS" => "2",
            "GIP" => "2",
            "HTG" => "2",
            "PYG" => "0",
            "GNF" => "0",
            "GYD" => "2",
            "HKD" => "2",
            "UAH" => "2",
            "ISK" => "0",
            "INR" => "2",
            "IRR" => "2",
            "IQD" => "3",
            "JMD" => "2",
            "JOD" => "3",
            "KES" => "2",
            "PGK" => "2",
            "LAK" => "2",
            "KWD" => "3",
            "MWK" => "2",
            "AOA" => "2",
            "MMK" => "2",
            "GEL" => "2",
            "LVL" => "2",
            "LBP" => "2",
            "ALL" => "2",
            "HNL" => "2",
            "SLL" => "2",
            "LRD" => "2",
            "LYD" => "3",
            "SZL" => "2",
            "LTL" => "2",
            "LSL" => "2",
            "MGA" => "2",
            "MYR" => "2",
            "MUR" => "2",
            "MXN" => "2",
            "MXV" => "2",
            "MDL" => "2",
            "MAD" => "2",
            "MZN" => "2",
            "BOV" => "2",
            "NGN" => "2",
            "ERN" => "2",
            "NAD" => "2",
            "NPR" => "2",
            "ANG" => "2",
            "ILS" => "2",
            "RON" => "2",
            "TWD" => "2",
            "NZD" => "2",
            "BTN" => "2",
            "KPW" => "2",
            "NOK" => "2",
            "PEN" => "2",
            "MRO" => "2",
            "TOP" => "2",
            "PKR" => "2",
            "MOP" => "2",
            "CUC" => "2",
            "UYU" => "2",
            "PHP" => "2",
            "GBP" => "2",
            "BWP" => "2",
            "QAR" => "2",
            "GTQ" => "2",
            "ZAR" => "2",
            "OMR" => "3",
            "KHR" => "2",
            "MVR" => "2",
            "IDR" => "2",
            "RUB" => "2",
            "RWF" => "0",
            "SHP" => "2",
            "SAR" => "2",
            "RSD" => "2",
            "SCR" => "2",
            "SGD" => "2",
            "SBD" => "2",
            "KGS" => "2",
            "SOS" => "2",
            "TJS" => "2",
            "SSP" => "2",
            "LKR" => "2",
            "XSU" => "0",
            "SDG" => "2",
            "SRD" => "2",
            "SEK" => "2",
            "CHF" => "2",
            "SYP" => "2",
            "BDT" => "2",
            "WST" => "2",
            "TZS" => "2",
            "KZT" => "2",
            "TTD" => "2",
            "MNT" => "2",
            "TND" => "3",
            "TRY" => "2",
            "TMT" => "2",
            "AED" => "2",
            "UGX" => "2",
            "COU" => "2",
            "CLF" => "0",
            "UYI" => "0",
            "USD" => "2",
            "UZS" => "2",
            "VUV" => "0",
            "CHE" => "2",
            "CHW" => "2",
            "KRW" => "0",
            "YER" => "2",
            "JPY" => "0",
            "CNY" => "2",
            "ZMK" => "2",
            "ZWL" => "2",
            "PLN" => "2"
        ];
        return isset($currencies[$currency]) ? $currencies[$currency] : '2';
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