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
class Signature{

    /*
    * @name     签名一个数组
    * @params   array   $params 签名数组
    * @params   stirng  $secret 签名秘钥
    * @return   string  签名串
    */
    static public function make($params=array(),$secret=''){
        $str = '';
        ksort($params);
        $str = http_build_query($params);
        $str .= $secret;
        return md5($str);
    }
    static public function str($params=array(),$secret=''){
        $str = '';
        ksort($params);
        $str = http_build_query($params);
        $str .= $secret;
        return $str;
    }

}
