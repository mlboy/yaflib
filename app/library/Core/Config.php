<?php
/*
*+---------------------------------------+
*|                                       |
*|           By mlboy@126.com            |
*|       Copyright (C) 2015  mlboy       |
*|          All rights reserved          |
*|            www.maliang.xyz            |
*|                                       |
*+---------------------------------------+
*/
namespace Core;
class Config{
    static public $config=array();
    static public function get($path){
        $pos =strpos($path,'.');
        if($pos>0){
            $name =substr($path,0,$pos);
        }else{
            $name=$path;
        }
        if(!isset(static::$config[$name])){
            static::$config[$name]=require_once APP_PATH.'conf/'.$name.'.php';
        }
        if($pos>0){
            $params =explode('.',$path);
            return static::$config[$name][$params[1]];
        }else{
            return static::$config[$name];
        }
        return static::$config[$name];
        //return require_once __DIR__.'/../app/config/'.$name.'.php';
    }
}
