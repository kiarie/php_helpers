<?php
ini_set("display_errors", 1);
error_reporting(E_ALL); 
require 'vendor/autoload.php';
// echo __DIR__;
// echo __FILE__;
// echo dirname(__FILE__);
try {
     $vars = array(
                'date' => date('H:i:s')
            );
            $content = http_build_query($vars);
    // $sock = new \elipa\sockets\Sock('http://localhost/ipay_admin/ipay-admin/creditcard/attempt/list/ke');
    $sock = new \elipa\sockets\Sock('https://api.ipayafrica.com/payments/v2/transact/mobilemoney');
    $data = array('sid' => '165DEM313E51485347795159927097DEMO','vid' => 'demo','hash' => '0313add30bcec487d9019f3f4236d7f927f0513cabc323299dbc7b3c9a76bf03');
    $get = $sock->method('POST', $data);
    $read = $sock->read_data(); //if you wanna wait for the response or see it 
     // $get = $sock->method('GET');
    // $read = $sock->read(); //if you wanna wait for the response or see it 
    $sock->close();
   // header('Content-type: text/plain');
   // print_r($get);
} catch (Exception $e) {
    echo 'test error';
    print($e->getMessage());
}
?>