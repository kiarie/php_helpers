<?php
require 'vendor/autoload.php';

use \elipa\curl\Curl as Curl_class;
$curl = new Curl_class;
$get_response = $curl->get("https://requestb.in/1hovcu81?lol=hahaha");
print_r($post_response);

$post_response = $curl->post("https://requestb.in/1hovcu81", array('data' => 'itcamehere'));
print_r($post_response);
