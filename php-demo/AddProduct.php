<?php
$secretId = "AKIDz8krbsJ5yKBZQpn74WFkmLPx3EXAMPLE";
$secretKey = "Gu5t9xGARNpq86cd98joQYCN3EXAMPLE";
$host = "yunxiaowei.qcloud.com";
$service = "industry";
$version = "v1";
$action = "AddProduct";
// $timestamp = time();
$timestamp = 1551113065;
$algorithm = "HMAC-SHA256";

// step 1: build canonical request string
$httpRequestMethod = "POST";
$canonicalUri = "/industry-cgi";
$canonicalQueryString = "";
$canonicalHeaders = "content-type:application/json; charset=utf-8\n";
$signedHeaders = "content-type";
$payload = '{\"remark\": "", \"product_name\": \"test1\"}';
$hashedRequestPayload = hash("SHA256", $payload);
$canonicalRequest = $httpRequestMethod."\n"
    .$canonicalUri."\n"
    .$canonicalQueryString."\n"
    .$canonicalHeaders."\n"
    .$signedHeaders."\n"
    .$hashedRequestPayload;
echo $canonicalRequest.PHP_EOL;

// step 2: build string to sign
$date = gmdate("Y-m-d", $timestamp);
$credentialScope = $date."/".$service."/yxw_request";
$hashedCanonicalRequest = hash("SHA256", $canonicalRequest);
$stringToSign = $algorithm."\n"
    .$timestamp."\n"
    .$credentialScope."\n"
    .$hashedCanonicalRequest;
echo $stringToSign.PHP_EOL;

// step 3: sign string
$secretDate = hash_hmac("SHA256", $date, "YXW".$secretKey, true);
$secretService = hash_hmac("SHA256", $service, $secretDate, true);
$secretSigning = hash_hmac("SHA256", "yxw_request", $secretService, true);
$signature = hash_hmac("SHA256", $stringToSign, $secretSigning);
echo $signature.PHP_EOL;

// step 4: build authorization
$authorization = $algorithm
    ." Credential=".$secretId."/".$credentialScope
    .", SignedHeaders=content-type, Signature=".$signature;
echo $authorization.PHP_EOL;

$curl = "curl -X POST https://".$host.$canonicalUri
    .' -H "Authorization: '.$authorization.'"'
    .' -H "Content-Type: application/json; charset=utf-8"'
    .' -H "YXW-Action: '.$action.'"'
    .' -H "YXW-Timestamp: '.$timestamp.'"'
    .' -H "YXW-Version: '.$version.'"'
    ." -d '".$payload."'";
echo $curl.PHP_EOL;