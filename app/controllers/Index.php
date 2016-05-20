<?php
class IndexController extends Core\C {
    // 默认Action
    public function indexAction() {
		$hello = 'hello world';
		$adapter = \Yaf\Registry::get('DB');
		$sql = new Zend\Db\Sql\Sql($adapter);
        $select = $sql->select();
        $select->from('test');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        // var_dump($results);
        var_dump($results->toArray());
        $this->with('hello',$hello);
        $this->display();
    }
	public function hiAction() {
		echo $hello = 'hello world';
        //$this->with('hello',$hello);
        //$this->display('hi');
    }
    public function loginAction(){
        if( $this->isPost()) {
            $loginname = $this->post('loginname');
            $password = $this->post('password');
            $user = \Business\UserModel::login($loginname,$password);
            if ($user) {
                $this->session['user'] = $user;
                $ret = array(
                    'errno' => 0,
                    'errmsg' =>'登陆成功正在跳转',
                );
            } else {
                $ret = array(
                    'errno' => 1,
                    'errmsg' =>'用户名或密码错误',
                );
            }
            $this->json($ret);
            return $ret;
        }
        $this->display();
    }
    public function logoutAction() {
        $this->session['user'] = null;
        $this->forward('login');
        //$this->display();
    }
    public function testAction(){
        Db\Factory::db();
    }
}

