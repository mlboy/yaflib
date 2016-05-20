<?php
/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:\Yaf\Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 *
 * 注意:方法在Bootstrap类中的定义出现顺序, 决定了它们的被调用顺序.
 */
class Bootstrap extends Yaf\Bootstrap_Abstract {
    protected $config;
    // 配置初始化
    public function _initConfig(\Yaf\Dispatcher $dispatcher) {
        $this->config = Yaf\Application::app()->getConfig()->toArray();

        //模板文件中所有要的路径，html\css\javascript\image\link等中用到的路径，从WEB服务器的文档根开始
        $docRoot = isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']:'';
        $spath=rtrim(substr(dirname(str_replace('\\', '/', dirname(__FILE__))), strlen(rtrim($docRoot,DIRECTORY_SEPARATOR))), '/\\');
        $sroot='/'.$spath.'';
        $this->config['root'] =$sroot;
        Yaf\Registry::set('config', $this->config);

    }

    // 是否显示错误提示
    public function _initError(\Yaf\Dispatcher $dispatcher) {
        if($this->config['application']['debug']) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
    }

    public function _initLoader(Yaf\Dispatcher $dispatcher){
        $loader = Yaf\Loader::getInstance();
        $loader->registerLocalNamespace(array('Core'));
        //$modules = $dispatcher->getApplication()->getModules();
        //$loader->registerLocalNamespace($modules);
        //print_R($modules);
        //Yaf\Loader::autoload("core_Controller");
    }

    public function _initDb(){
		/*
        if (!isset($this->cofing['db'])) {
            return;
        }
        $dbtype = $this->config['db']['dbtype'];
        if (!isset($dbtype)) {
            return;
        }
        $host = $this->config['db']['master']['host'];
        $port = $this->config['db']['master']['port'];
        $dbname = $this->config['db']['master']['dbname'];
        $username = $this->config['db']['master']['username'];
        $password = $this->config['db']['master']['password'];
        // Eloquent ORM
        $class_alias=array(
            '\DB'    =>'\Illuminate\Database\Capsule\Manager',
            '\Eloquent'   =>'\Illuminate\Database\Eloquent\Model',
            '\Paginator'   =>'\Illuminate\Pagination\Paginator',
        );
        foreach($class_alias as $k=>$v){
            class_alias($v,$k);
        }

        $capsule = new DB;
        $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container));
        //$capsule->setCacheManager();
        $db_config = array(
            'driver'    => 'mysql',
            'host'      => $host,
            'database'  => $dbname,
            'username'  => $username,
            'password'  => $password,
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => ''
        );
        $capsule->addConnection($db_config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        //Yaf\Registry::set('db', $db);
		*/
		//新建对象
        $dbAdapter = new Zend\Db\Adapter\Adapter(Yaf\Application::app()->getConfig()->mysql->write->toArray());
        //设为全局变量
        \Yaf\Registry::set('DB', $dbAdapter);
    }
    // 注册插件
    public function _initPlugin(\Yaf\Dispatcher $dispatcher) {
        // 初始化模版引擎
        $view = new PlatesViewPlugin();
        $dispatcher->registerPlugin($view);
    }

    // 路由
    public function _initRoute(\Yaf\Dispatcher $dispatcher) {

        if ($dispatcher->getRequest()->isCli()) {
            $dispatcher->dispatch(new Yaf\Request\Simple());
        } else {
            $router = Yaf\Dispatcher::getInstance()->getRouter();
            // 默认进入index/index
            $modules = Yaf\Application::app()->getModules();
            $route = array();
            if($modules) {
                foreach ($modules as $module) {
                    $name = strtolower($module);
                    $route[$name] = new Yaf\Route\Rewrite(
                        '/('.$name.'|'.$name.'/|'.$name.'/index|'.$name.'/index/)$',
                        array(
                            'controller' => 'index',
                            'action' => 'index',
                            'module' => $name,
                        )
                    );
                }
            }
            $router_file = APP_PATH.'router.php';
            if(file_exists($router_file)) $other = include($router_file);
            array_merge($route,$other);
            //使用路由器装载路由协议
            foreach ($route as $k => $v) {
                $router->addRoute($k, $v);
            }
            Yaf\Registry::set('rewrite_route', $route);
        }
    }
    /**
     * 系统级错误跳转到首页
     * @param int errorCode
     * @param string error detail
     *
     */
    public static function errorHandler($error, $errstr) {
        //print_R($errstr);
        //die();
        //die($errstr);
    }
}

