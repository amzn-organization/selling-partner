<?php

require_once('./vendor/autoload.php');

use SellingPartnerApi\Configuration;

class Config
{
  public static function get(): Configuration
  {

    $config = new SellingPartnerApi\Configuration([
      "lwaClientId" => "amzn1.application-oa2-client.7a16e....",
      "lwaClientSecret" => "53088...",
      "lwaRefreshToken" => "Atzr|IwEB...",
      "awsAccessKeyId" => "AKIAU...",
      "awsSecretAccessKey" => "ElWOBI1...",
      "endpoint" => SellingPartnerApi\Endpoint::NA,
      "roleArn" => "..."
    ]);

    return $config;
  }
}
