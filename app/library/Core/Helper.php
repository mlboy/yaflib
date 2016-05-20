<?php
namespace Core;
class Helper {
	//url处理
     public static function url($route = null, $params = array()) {
        // rewrite start
        $config = \Yaf\Registry::get('config');
        $url = $config['root'];
        // 系统默认
        $request = \Yaf\Dispatcher::getInstance()->getRequest();
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $actionName = $request->getActionName();
        $para = $request->getParams();
        //$params = array_merge($params,$para);
        if (!$route) {
            //当前
            $route = $moduleName.'/'.$controllerName.'/'.$actionName;
        } else if ($route == '/') {
            $route = '/';
        } else {
            $route_arr = explode('/',$route);
            $route_len = count($route_arr);
            if ($route_len == 1) {
                $route = $moduleName .'/'. $controllerName.'/'.$route_arr[0];
            } else if ($route_len == 2) {
                //$route = $moduleName .'/'. $route_arr[0].'/'.$route_arr[1];
                $route = $route_arr[0].'/'.$route_arr[1];
            } else {
                $route = $route_arr[0].'/'.$route_arr[1].'/'.$route_arr[2];
            }
        }
        $route = strtolower($route);
        $url = $url.$route;
        $url = rtrim($url, '/');
        foreach ($params as $key => $value) {
            if(empty($value) && $key!='page')
                continue;
            //$url .= '/'.$key.'/'.$value;
        }
        $url = preg_replace('/^index\/index$/i', '', $url);
        $url_params = http_build_query($params);
        if ($url_params) {
            $url .= '?'.$url_params;
        }
        return $url;
    }
	//资源处理
    public static function asset($url,$ver= false){
        if(!$ver){
            return $url;
        }
        $path = BASE_PATH.'/public/';
        $path = rtrim($path, '/');
        $filePath = $path . '/' .  ltrim($url, '/');
        if (!file_exists($filePath)) {
            return $url;
        }
        $lastUpdated = filemtime($filePath);
        $pathInfo = pathinfo($url);
        if ($pathInfo['dirname'] === '.') {
            $directory = '';
        } elseif ($pathInfo['dirname'] === '/') {
            $directory = '/';
        } else {
            $directory = $pathInfo['dirname'] . '/';
        }
        return $directory . $pathInfo['filename'] . '.' . $pathInfo['extension'] . '?v=' . $lastUpdated;
    }
}
