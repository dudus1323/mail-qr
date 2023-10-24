<?php
require 'vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

$client = new GuzzleHttp\Client();
$result = $client->request('GET', 'https://v2.it4u.company/sapi/site/random_email');
if($result->getStatusCode() === 200){
    $apiData = json_decode($result->getBody(), true);
    //var_dump($apiData['qr_content']);
    $qr = QrCode::create($apiData['qr_content']);
    if(extension_loaded('gd')){
        $writer = new PngWriter();
        $qr_result = $writer->write($qr);
    }
}
$qrData = $qr_result->getDataUri();

$transport = Transport::fromDsn('smtp://localhost');
$mailer = new Mailer($transport);

$email = (new Email())
    ->from('example@example.com')
    ->to($apiData['email'])
    ->subject($apiData['subject'])
    ->text($apiData['body']);
    $email->attachFromPath($qrData, 'qr_code.png', 'image/png');

$mailer->send($email);
?>