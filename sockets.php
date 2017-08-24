<?php
ini_set("display_errors", 1);
error_reporting(E_ALL); 
require 'vendor/autoload.php';

try {
	//==================================
    //-------------------Sample POST-----
    //==================================
    $sock = new \elipa\sockets\Sock('https://api.ipayafrica.com/payments/v2/transact/mobilemoney');
    $data = array('sid' => '165DEM313E51485347795159927097DEMO','vid' => 'demo','hash' => '0313add30bcec487d9019f3f4236d7f927f0513cabc323299dbc7b3c9a76bf03');
    $get = $sock->method('POST', $data); //POST call with datas
    $read = $sock->read_data(); //if you wanna wait for the response data in the content-type that it came with 
    // $read = $sock->read(); //if you wanna WAIT for the response or see it 

    //==================================
    //-------------------Sample GET-----
    //==================================
    // $sock = new \elipa\sockets\Sock('http://localhost/ipay_admin/ipay-admin/creditcard/attempt/list/ke');
    // $get = $sock->method('GET'); //Just pass the  'GET' no need for data to be passed through
    // $read = $sock->read(); //if you wanna WAIT for the response or see it 
    $sock->close();
} catch (Exception $e) {
    echo 'test error';
    print($e->getMessage());
}
?>