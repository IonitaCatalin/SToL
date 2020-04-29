<?php
require_once 'app/init.php';
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); 
$inputs['URI'] = '/'.substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($script_name));
$inputs['URI'] = str_replace('//', '/', $inputs['URI']);
$inputs['method'] = $_SERVER['REQUEST_METHOD'];


$url_query=parse_url($inputs['URI'],PHP_URL_QUERY);
if(isset($url_query))
{
    $query_strings=explode('&',$url_query);
    $inputs['URI']=strtok($inputs['URI'],'?');
    foreach($query_strings as $pairs)
    {
        $uri_element=explode('=',$pairs, 2)[1];
        $inputs['URI']=$inputs['URI'].'/'.$uri_element;
    }
}
$app = new App($inputs);
$app->run();

?>