<?php
$route = array();
$route['product'] = new Yaf\Route\Rewrite(
    '/item/:id',
    array(
        'controller' =>'product',
        'action' =>'index',
        'module' => 'index',
    )
);
return $route;
