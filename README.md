# PlacetoPay Redirection PHP library

[![codecov](https://codecov.io/gh/dnetix/redirection/branch/master/graph/badge.svg?token=7QB12XVSTG)](https://codecov.io/gh/dnetix/redirection)

With this code you will be able to quickly connect with the PlacetoPay Checkout service.

In order to see more comprehensive examples of how it works, please refer to the examples and the [documentation](https://docs-gateway.placetopay.com/)

### Migration from version 1

- [ ] Change the settings parameter `url` for `baseUrl`
- [ ] If you have set the `timeout` for rest then change it to the root of the settings
- [ ] `PlacetoPayServiceException` is thrown instead of returning an ERROR status response

## Installation

Using composer from your project

```
composer require dnetix/redirection
```

Or If you just want to run the examples contained in this project run "composer install" to load the vendor autoload

## Usage

Create an object with the configuration required for that instance

```php 
$placetopay = new Dnetix\Redirection\PlacetoPay([
    'login' => 'YOUR_LOGIN', // Provided by PlacetoPay
    'tranKey' => 'YOUR_TRANSACTIONAL_KEY', // Provided by PlacetoPay
    'baseUrl' => 'https://THE_BASE_URL_TO_POINT_AT',
    'timeout' => 10, // (optional) 15 by default
]);
```

### Creating a new Payment Request to obtain a Session Payment URL

Just provide the information of the payment needed and you will get a process url if its successful, for this example we are using the MINIMUM INFORMATION that needs to be provided, to see the full structure refer to the documentation or the example on [examples/basic/payment.php](examples/basic/payment.php)

```
$reference = 'COULD_BE_THE_PAYMENT_ORDER_ID";
$request = [
    'payment' => [
        'reference' => $reference,
        'description' => 'Testing payment',
        'amount' => [
            'currency' => 'USD',
            'total' => 120,
        ],
    ],
    'expiration' => date('c', strtotime('+2 days')),
    'returnUrl' => 'http://example.com/response?reference=' . $reference,
    'ipAddress' => '127.0.0.1',
    'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
];

$response = $placetopay->request($request);
if ($response->isSuccessful()) {
    // STORE THE $response->requestId() and $response->processUrl() on your DB associated with the payment order
    // Redirect the client to the processUrl or display it on the JS extension
    $response->processUrl();
} else {
    // There was some error so check the message and log it
    $response->status()->message();
}
```

### Obtain information about a previously created session

```
$response = $placetopay->query('THE_REQUEST_ID_TO_QUERY');

if ($response->isSuccessful()) {
    // In order to use the functions please refer to the Dnetix\Redirection\Message\RedirectInformation class

    if ($response->status()->isApproved()) {
        // The payment has been approved
    }
} else {
    // There was some error with the connection so check the message
    print_r($response->status()->message() . "\n");
}
```
