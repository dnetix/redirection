<?php

require_once (__DIR__ . "/../bootstrap.php");

use Dnetix\Redirection\PlacetoPay;

// Creating a random reference for the test
$reference = 'TEST_' . time();

// Request Information
$paymentRequest = [
    'buyer' => [
        'name' => 'John',
        'surname' => 'Doe',
        'email' => 'john.doe@example.com'
    ],
    'payment' => [
        'reference' => $reference,
        'description' => 'Testing payment',
        'amount' => [
            'taxes' => [
                [
                    'kind' => 'valueAddedTax',
                    'amount' => 10,
                    'base' => 120
                ],
                [
                    'kind' => 'exciseDuty',
                    'amount' => 10,
                    'base' => 120
                ]

            ],
            'details' => [
                [
                    'kind' => 'discount',
                    'amount' => 1200
                ]
            ],
            'currency' => 'USD',
            'total' => 120
        ],
    ],
    'fields' => [
        [
            'keyword' => 'additional',
            'value' => 'ABC123',
            'displayOn' => 'both'
        ]
    ],
    'expiration' => date('c', strtotime('+2 days')),
    'returnUrl' => 'http://example.com/response?reference=' . $reference,
    'ipAddress' => '127.0.0.1',
    'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
];

try {
    $response = placetopay()->request($paymentRequest);

    if ($response->isSuccessful()) {
        // Redirect the client to the processUrl or display it on the JS extension
        // $response->processUrl();
    } else {
        // There was some error so check the message
        // $response->status()->message();
    }
    var_dump($response);
} catch (Exception $e) {
    var_dump($e->getMessage());
}

