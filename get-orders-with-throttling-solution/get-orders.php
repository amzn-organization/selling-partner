<?php

require_once('./vendor/autoload.php');
require_once('./config.php');

$config = Config::get();

$apiInstance = new SellingPartnerApi\Api\OrdersApi($config);
$marketplace_ids = ['A2Q3Y263D00KWC'];
$created_after = '2020-01-01';
$created_before = null;
$last_updated_after = null;
$last_updated_before = null;
$order_statuses = null;
$fulfillment_channels = null;
$payment_methods = null;
$buyer_email = null;
$seller_order_id = null;
$max_results_per_page = null;
$easy_ship_shipment_statuses = null;
$next_token = null;
$amazon_order_ids = null;
$actual_fulfillment_supply_source_id = null;
$is_ispu = null;
$store_chain_store_id = null;

try {
  $result = $apiInstance->getOrders(
    $marketplace_ids,
    $created_after,
    $created_before,
    $last_updated_after,
    $last_updated_before,
    $order_statuses,
    $fulfillment_channels,
    $payment_methods,
    $buyer_email,
    $seller_order_id,
    $max_results_per_page,
    $easy_ship_shipment_statuses,
    $next_token,
    $amazon_order_ids,
    $actual_fulfillment_supply_source_id,
    $is_ispu,
    $store_chain_store_id
  );

  header('Content-Type: application/json');
  echo json_encode($result->getPayload());
} catch (Exception $e) {
  echo 'Exception when calling OrdersApi->getOrders: ', $e->getMessage(), PHP_EOL;
}
