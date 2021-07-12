<?php

require_once('./vendor/autoload.php');
require_once('./config.php');

$orderInfo = [];
$config = Config::get();
$apiInstance = new SellingPartnerApi\Api\OrdersApi($config);

$order_id = $_GET['order_id']; // string | 3-7-7 format.

try {
  $result = $apiInstance->getOrderAddress($order_id);
  $orderInfo['address'] = $result->getPayload();
} catch (Exception $e) {
  echo 'Exception when calling OrdersApi->getOrderAddress: ', $e->getMessage(), PHP_EOL;
}

$apiInstance = new SellingPartnerApi\Api\OrdersApi($config);
$order_id = $_GET['order_id']; // string | 3-7-7 format.

sleep(2); // Avoiding "Too Many Request"

try {
  $result = $apiInstance->getOrderBuyerInfo($order_id);
  $orderInfo['buyerInfo'] = $result->getPayload();
} catch (Exception $e) {
  echo 'Exception when calling OrdersApi->getOrderBuyerInfo: ', $e->getMessage(), PHP_EOL;
}

// Getting items
$apiInstance = new SellingPartnerApi\Api\OrdersApi($config);

try {
  $result = $apiInstance->getOrderItems($order_id, null);
  $orderInfo['items'] = $result->getPayload();
} catch (Exception $e) {
  echo 'Exception when calling OrdersApi->getOrderBuyerInfo: ', $e->getMessage(), PHP_EOL;
}

header('Content-Type: application/json');
echo json_encode($orderInfo);
