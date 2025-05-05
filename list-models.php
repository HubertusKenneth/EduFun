<?php
$api_key = 'AIzaSyCfYf2C5yEQHHPJgndZuuWSOoMVZhkqpLA';

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $api_key;

$response = file_get_contents($url);
header('Content-Type: application/json');
echo $response;
