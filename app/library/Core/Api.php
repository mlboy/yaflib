<?php
namespace Core;
class Api extends C{
    public function init(){
        //parent::init();
        $token = Request::input('_token_',md5(uniqid(time().mt_rand(),true)));
        if($token) session_id($token);
        $this->session = \Yaf\Session::getInstance();

        $this->status = include(APP_PATH . 'conf'.DS.'status.php');
        $this->debug = \Yaf\Application::app()->getConfig()->api->debug;
        $this->uri = '/' .$this->getRequest()->getModuleName() . '/'  .$this->getRequest()->getControllerName() . '/' . $this->getRequest()->getActionName();
        $this->signature();
    }
    public function signature(){
        $appid = Request::get('appid');
        $signature = Request::get('signature');
        $api_users = \Yaf\Application::app()->getConfig()->api->users->toArray();
        if(!isset($api_users[$appid])){
            $ret = $this->error('appid_error');
            return $this->json($ret);
        }
        $sign_key =$api_users[$appid];
        $uri = Request::uri();
        $params = $this->input() + array('_URI_'=>$uri);
        unset($params['signature']);
        unset($params['appid']);
        if(Signature::make($params,$sign_key) !== $signature){
            $ret = $this->error('signature_error');
            if($this->debug) {
                $ret['debug']['signature'] = Signature::make($params,$sign_key);
                $ret['debug']['signstr'] = Signature::str($params,$sign_key);
                $ret['debug']['input'] = $this->input();
            }
            return $this->json($ret);
        }
    }
    public function json($data){
        header('Content-Type:application/json; charset=utf-8');
        if(is_string($data)) echo $data;
        echo json_encode($data);
        die;
        return true;
    }
    public function status($key=''){
        if(!isset($this->status[$key])){
            $this->status[$key] = array(
                'status'  => '0',
                'message' => empty($key)?'未知错误':$key,
            );
        }
        $ret = $this->status[$key];
        return $ret;
    }
    public function ok($data=null){
        if(is_array($data)){
            if(!empty($data)){
                $ret = $this->status('ok');
                $ret['data'] = $data;
            }else{
                $ret = $this->status('data_empty');
            }
        }else{
            $ret = static::status('ok');
        }
        return $ret;
    }
    public function error($key='',$msg = null){
        $ret = $this->status($key,$msg);
        return $ret;
    }

}
