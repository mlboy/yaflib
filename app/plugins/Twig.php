<?php
class TwigPlugin extends Yaf\Plugin_Abstract {
    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
    }
    // 模板路径
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        $config = Yaf\Registry::get('config');
        $dispatcher= Yaf\Dispatcher::getInstance();
        $twig = '';
        // view 放在module 目录里
        if($request->module==$config['application']['dispatcher']['defaultModule']){
            $twig = new Core\Twig(APP_PATH.'views', $config['twig']);
        } else {
            $twig = new Core\Twig(APP_PATH.'modules/'.$request->module.'/views', $config['twig']);
        }
        $twig->twig->addFunction("url", new Twig_Function_Function("Core\Helper::url"));
        // 语言对应
        $twig->twig->addFunction("lang", new Twig_Function_Function("Core\Helper::lang"));
        // 图片路径
        $twig->twig->addFunction("path", new Twig_Function_Function("Core\Helper::path"));
        $dispatcher->setView($twig);
    }
}
