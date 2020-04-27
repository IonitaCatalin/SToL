<?php

//session_start(); // pornit din login controller

require_once '../app/init.php';
echo 'Request';

$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']) ); 
$inputs['URI'] = '/'.substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($script_name));
$inputs['URI'] = str_replace('//', '/', $inputs['URI']);

$inputs['method'] = $_SERVER['REQUEST_METHOD'];

//Raw input for requests
$inputs['raw_input'] = file_get_contents('php://input');

//POST data
parse_str($inputs['raw_input'] , $post);
echo '<br>'.$script_name;
//Merge all
$inputs = array_merge($inputs,$post);

$app = new App($inputs);
$app->run();