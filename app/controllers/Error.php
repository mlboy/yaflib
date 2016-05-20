<?php
class ErrorController extends Yaf\Controller_Abstract {
    public function errorAction($exception) {
        switch($exception->getCode()){
        case YAF\ERR\NOTFOUND\MODULE:
            case YAF\ERR\NOTFOUND\CONTROLLER:
                case YAF\ERR\NOTFOUND\ACTION:
                    case YAF\ERR\NOTFOUND\VIEW:
                        if(!$this->getRequest()->isCli()) {
                            header('HTTP/1.1 404 Not Found');
                            header("status: 404 Not Found");
                        }
                        break;
                    default:
                        header('HTTP/1.0 500 Internal Server Error');
                        break;
        }
        if(is_string($exception)) {
            echo 'E:'.$exception;
        } else {
            //print_R($exception);
            //echo get_class($exception);
            if(is_a($exception,'Illuminate\Database\QueryException')){
                echo 'SQL:'.$exception->getMessage();
            }else if(is_a($exception,'LogicException')){
                echo 'VIEW:'.$exception->getMessage();
            }else{
                echo get_class($exception).':'.$exception->getMessage();
            }
            //echo 'E:'.print_R($exception->getMessage());
        }
    }

}

