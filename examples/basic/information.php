<?php

require_once(__DIR__ . "/../bootstrap.php");

/**
 * IGNORE THIS PART, Just needed to obtain the requestId that will be queried
 */
if (isset($argv)) {
    // Called from the CLI
    if (!isset($argv[1]) || !is_numeric($argv[1])) {
        print_r("Usage: php examples/basic/information.php REQUEST_ID\n");
        print_r("REQUEST_ID should be replaced by the requestId wanted to query\n");
        die();
    }
    $requestId = $argv[1];
} else {
    // Called from browser
    if (!isset($_GET['requestId']) || !is_numeric($_GET['requestId'])) {
        print_r("Please include requestId as a GET parameter with the requestId to be queried");
        die();
    }
    $requestId = $_GET['requestId'];
}
/**
 * END OF IGNORE
 */

try {
    $response = placetopay()->query($requestId);

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

