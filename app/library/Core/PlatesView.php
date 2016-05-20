<?php
namespace Core;
class PlatesView implements \Yaf\View_Interface {
    protected $vars = array();
    protected $script_path = '';
    protected $engine;
    protected $extension;
    public function __construct($script_path = null,$extension = null) {
        $this->script_path  = $script_path;
        $this->extension    = $extension;
        //这里不初始化模板引擎，在需要的时候再初始化,滞后初始化位置
    }
    //初始化模板引擎
    public function getInstance(){
        if($this->engine){
            return $this->engine;
        }
        $this->engine = new Plates\Engine($this->script_path,$this->extension);
        //$this->engine = new \League\Plates\Engine($this->script_path,$this->extension);
        $this->addFunction('url',array('\Core\Helper','url'));
        $this->addFunction('asset',array('\Core\Helper','asset'));
        return $this->engine;
    }
    public function render ($tmp,$vars = null ){
        $engine = $this->getInstance();
        if(is_array($vars)){
            $this->vars = array_merge($this->vars,$vars);
        }
        return $engine->render($tmp,$this->vars);
    }
    public function display ($tmp = null,$vars = null ){
        echo $this->render($tmp,$vars);
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
    public function setFileExtension($ext){
        $engine = $this->getInstance();
        return $engine->setFileExtension($ext);
    }
    public function addFolder($module,$script_path,$fallbacks = null){
        $engine = $this->getInstance();
        return $engine->addFolder($module,$script_path,$fallbacks);
    }
    public function addFunction($name,$callback){
        $engine = $this->getInstance();
        return $engine->registerFunction($name,$callback);
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
