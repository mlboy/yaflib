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
class ReturnApi{
    static private $status = array();
    static private function init(){
        static::$status = Config::get('status');
    }
    static private function status($key=''){
        if(!isset(static::$status[$key])){
            static::$status[$key] = array(
                'status'  => '0',
                'message' => '未定义状态码',
            );
        }
        return  static::$status[$key];
    }
    static public function ok($data=null){
        static::init();
        if(is_array($data)){
            if(!empty($data)){
                $ret = static::status('ok');
                $ret['data'] = $data;
            }else{
                $ret = static::status('data_empty');
            }
        }else{
            $ret = static::status('ok');
        }
        return $ret;
    }
    static public function error($key=''){
        static::init();
        $ret = static::status($key);
        return $ret;
    }
}
