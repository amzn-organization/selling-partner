<?php

function calculateSignature(
  $key,
  $dateStamp = null,
  $regionName = 'us-east-1',
  $serviceName = 'execute-api'
) {

  if (!$dateStamp) {
    $dateStamp = Date('Ymd');
  }

  $kDate = hash_hmac('sha256', $dateStamp, "AWS4" . $key);
  $kRegion = hash_hmac('sha256', $regionName, $kDate);
  $kService = hash_hmac('sha256', $serviceName, $kRegion);
  $kSigning = hash_hmac('sha256', "aws4_request", $kService);

  return $kSigning;
}