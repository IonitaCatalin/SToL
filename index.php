<?php
require_once 'app/init.php';
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); 
$inputs['URI'] = '/'.substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($script_name));
$inputs['URI'] = str_replace('//', '/', $inputs['URI']);
$inputs['method'] = $_SERVER['REQUEST_METHOD'];
$inputs['raw_input'] = file_get_contents('php://input');

parse_str($inputs['raw_input'] , $post);
$inputs = array_merge($inputs,$post);

echo parse_url($inputs['URI'],PHP_URL_QUERY);

$app = new App($inputs);
$app->run();

?>