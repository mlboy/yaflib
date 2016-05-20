<?php
class VPlugin extends Yaf\Plugin_Abstract {
    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
    }
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        $config = Yaf\Registry::get('config');
        if ($request->module == $config['application']['dispatcher']['defaultModule']) {
            $tmp =  APP_PATH.'views/';
        }else{
            $tmp = APP_PATH.'modules/'.$request->module.'/views/';
        }
        $view = new \Core\V($tmp);
        $dispatcher = Yaf\Dispatcher::getInstance();
        $dispatcher->setView($view);
    }
}
