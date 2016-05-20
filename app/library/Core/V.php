<?php
namespace Core;
class V implements \Yaf\View_Interface {
    protected $vars = array();
    protected $script_path = '';
    public function __construct($script_path = null) {
        $this->script_path = $script_path;
        $this->vars = array();
    }
    public function render ($tmp,$vars = null ){
        ob_start();
        $this->display($tmp,$vars);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    public function setFunction($key,&$func){
        $this->func[$key] = $func;
        return true;
    }
    public function display ($tmp = null,$vars = null ){
        if(is_array($vars)){
            $this->vars = array_merge($this->vars,$vars);
        }
        extract($this->vars);
        include $this->script_path.$tmp;
        return true;
    }
    public function setScriptPath ($script_path){
        $this->script_path = $script_path;
        return true;
    }
    public function getScriptPath () {
        return $this->script_path;
    }
    public function assign ($key,$value = null) {
        $this->vars[$key] = $value;
        return $this;
    }
    public function with ($key,$value) {
        return $this->assign($key,$value);
    }
    public function __set ($key ,$value = NULL){
        $this->vars[$key] = $value;
        return true;
    }
    public function __get ($key){
        return $this->vars[$key];
    }
}
