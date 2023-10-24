<?php
require 'vendor/autoload.php';

$client = new GuzzleHttp\Client();
$result = $client->request('GET', 'https://v2.it4u.company/sapi/site/random_email');
if($result->getStatusCode() === 200){
    $apiData = json_decode($result->getBody());
}
?>