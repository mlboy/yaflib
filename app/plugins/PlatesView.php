<?php
class PlatesViewPlugin extends Yaf\Plugin_Abstract {
    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
    }
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        $config = Yaf\Registry::get('config');
        if ($request->module == $config['application']['dispatcher']['defaultModule']) {
            $tmp =  APP_PATH.'views';
        }else{
            $tmp = APP_PATH.'modules/'.$request->module.'/views';
        }
        $dispatcher = Yaf\Dispatcher::getInstance();

        //$dispatcher->autoRender(true);
        $dispatcher->autoRender(false);
        //如果是
        //  ajax请求则不自动调用模板
        //  $dispatcher->getRequest()->isXmlHttpRequest()
        //  配置为api模块
        if (in_array($request->module,explode(',',$config['api']['modules']))) {
            $dispatcher->disableView();
        } else {
            //$view = new \Core\PlatesView($tmp);
            $view = new \Core\PlatesView($tmp,$config['application']['view']['ext']);
            $dispatcher->setView($view);
        }
    }
    public function dispatchLoopStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
    }
}
