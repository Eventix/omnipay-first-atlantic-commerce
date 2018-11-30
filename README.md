#omnipay-first-atlantic-commerce V3
Only used in combination with hostedPaymentSolution

```php
$gateway = Omnipay::create('FAC');
$gateway->setMerchantId('xxxxx');
$gateway->setMerchantPassword('xxxxx');
$gateway->setAquirerId('xxxx');
$gateway->setTestMode(true);

$orderId = '5d096925-9795-47d0-aade-9d8ed584eb9f';

if(isset($_GET['ID'])){
    $options = array(
        'orderNumber' => $orderId,
    );

    $response = $gateway->fetchTransaction($options)->send();
    $error = $response->getMessage();
    $isPaid = $response->isPaid();
}


$options = array(
    'orderNumber' => $orderId,
    'amount' => '10.00',
    'currency' => 'USD',
    'returnUrl' => 'http://someurl.com/',
    'pageSet' => 'PageSet',
    'pageName' => 'PageName',
    'transactionCode' => '213'
);

$response = $gateway->purchase($options)->send();
$singeUseToken = $transactionId = $response->getTransactionReference();
header('Location: '. $response->getRedirectUrl());
exit;

```