<?php

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    echo "Yetkiniz bulunmuyor.";
    die();
}

require 'vendor/autoload.php';

use Kreait\Firebase\Configuration;
use Kreait\Firebase\Firebase;

$config = new Configuration();
$config->setAuthConfigFile(__DIR__ . 'google-service-account.json');

$firebase = new Firebase('https://eminent-goods-225121.firebaseio.com/', $config);

$reference = $firebase->getReference('akillicopkonteyneri/deneme');

$data = $reference->getData();

$data = $_POST['params'];

$data = json_decode($data);

$firebase->set([
    'latMap' => $data->lat,
    'longMap' => $data->lon,
    'level' => $data->Doluluk,
    'no' => $data->No
], '/konteynerListesi/konteyner' . $data->No);

?>
