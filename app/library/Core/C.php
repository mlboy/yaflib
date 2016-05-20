<?php
namespace Core;
class C extends \Yaf\Controller_Abstract{
    public $session;
    public function init(){
        //$token = Request::input('_token_',md5(uniqid(time().mt_rand(),true)));
        //if($token) session_id($token);
        $this->session = \Yaf\Session::getInstance();
    }
    public function assign($key,$val = null){
        return $this->getView()->assign($key,$val);
    }
    public function display($display = null,array $params = null){
        if (!$display) {
            $controller = strtolower($this->getRequest()->getControllerName());
            $action = $this->getRequest()->getActionName();
            $display = $controller.'/'.$action;
        }
        return $this->getView()->display($display);
    }
    public function with($key,$val = null){
        return $this->getView()->with($key,$val);
    }
    public function json($data){
        header('Content-Type:application/json; charset=utf-8');
        if(is_string($data)) echo $data;
        echo json_encode($data);
        return true;
    }
    public function raw(){
        return file_get_contents('php://input');
    }
    public function post($key = null,$defult = null){
        return Request::post($key,$defult);
    }
    public function get($key = null,$defult = null){
        return Request::get($key,$defult);
    }
    public function input($key = null,$defult = null){
        return Request::input($key,$defult);
    }
    public function param($key = null,$defult =null) {
        return $this->getRequest()->getParam($key, $defult);
    }
    public function isPost(){
        if( $this->getRequest()->getMethod() === 'POST') {
            return true;
        }
        return false;
    }
}
