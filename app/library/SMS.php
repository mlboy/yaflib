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
class SMS{
    static $urlTmp  = 'http://221.122.112.136:8080/sms/mt.jsp?cpName={__USER__}&cpPwd={__PASS__}&phones={__PHONE__}&msg={__MESSAGE__}';
    static $url     = '';
    static $params   = array(
        '{__USER__}' => '***',
        '{__PASS__}' => '***',
    );
    static $salt ='【书读百遍】';
    static public function send($phone=null,$message=''){
        if(!$phone || empty($message)){
            return false;
        }
        $msg = $message;
        $message=iconv("utf-8","gbk",$message.static::$salt);
        static::$params['{__PHONE__}']    = $phone;
        static::$params['{__MESSAGE__}']  = rawurlencode($message);
        static::$url = strtr(static::$urlTmp,static::$params);
        $res = static::call(static::$url);
        static::log('PHONE:'.$phone."\t".$msg."\t" . ($res?'ok':'no'));
        if(!$res){
            return false;
        }
        return true;
    }
    static public function call($url){

        if($url){
            $html = file_get_contents($url);
            if($html =='0'){
                return true;
            }else{
                return false;
            }
        }
    }
    static public function log($log){
        $logpath ='/tmp/zyx-sms.log';
        $logstr = 'TIME:'.date('Y-m-d H:i:s').PHP_EOL;
        $logstr .= $log.PHP_EOL;
        file_put_contents($logpath, $logstr, FILE_APPEND);
    }
}
