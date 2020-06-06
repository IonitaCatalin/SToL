<?php
require_once 'app/init.php';
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); 
$inputs['URI'] = '/'.substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($script_name));
$inputs['URI'] = str_replace('//', '/', $inputs['URI']);
$inputs['method'] = $_SERVER['REQUEST_METHOD'];

$url_query = parse_url($inputs['URI'], PHP_URL_QUERY);

GLOBAL $array_of_query_string;

$query_string = parse_url($inputs['URI'], PHP_URL_QUERY);

if(isset($query_string))
{
	parse_str($query_string, $array_of_query_string);
	$inputs['URI'] = strtok($inputs['URI'],'?');
	$inputs['URI'] = $inputs['URI'] . '/redirect';
}

// if(isset($url_query))
// {
//     $query_strings=explode('&',$url_query);
//     $inputs['URI']=strtok($inputs['URI'],'?');
//     foreach($query_strings as $pairs)
//     {
//         $uri_element=explode('=',$pairs, 2)[1];
//         $inputs['URI']=$inputs['URI'].'/'.$uri_element;
//     }
// }

//file_put_contents('abc.txt', $inputs['URI'], FILE_APPEND | LOCK_EX); // pentru a vedea ruta
//print_r($inputs);

$app = new App($inputs);
$app->run();

?>