<?php
class IndexController extends Core\C{
    public function init(){
        parent::init();
        if (!$this->session['admin']) {
            $this->redirect('/admin/public/login');
        }
        $auth=new Auth();
        if(!$auth->check('admin/index/index',$this->session['admin']['id'])){
            echo '没有权限';die;
        }
    }
    public function indexAction() {
        $this->display();
    }
    public function mainAction() {
        $this->display();
    }
    public function changeMyPwdAction() {
        $this->display();
    }
    public function postChangeMyPwdAction() {
        $passwordOld = $this->post('password_old');
        $password = $this->post('password');
        $admin = AdminModel::find($this->session['admin']['id']);
        if(!$admin){
            return $this->json(array(
                'statusCode'=>  300,
                'message'  =>  '用户不存在',

            ));
        }
        if($admin->password != md5($passwordOld)){
            return $this->json(array(
                'statusCode'=>  300,
                'message'  =>  '旧密码不正确',
            ));
        }
        $admin->password = md5($password);
        if(!$admin->save()){
            return $this->json(array(
                'statusCode'=>  300,
                'messsage'  =>  '保存失败',
            ));
        }else{
            return $this->json(array(
                'statusCode'=>  200,
                'message'   =>  '保存成功',
                'tabid' =>  'table, table-fixed',
	            'closeCurrent' => true,
            ));
        }
    }
    public function adminsAction() {
        $page['page'] = $this->post('pageCurrent',1);
        $page['size'] = $this->post('pageSize',10);
        $page['order'] = $this->post('orderField',null);
        $page['by'] = $this->post('orderDirection','desc');
        Paginator::currentPageResolver(function() use($page){
            return $page['page'];
        });
        $res = new AdminModel();
        if ( $page['order'] && $page['by']) {
            $res = $res->orderBy($page['order'],$page['by']);
        }
        $res = $res->paginate($page['size']);
        if ($res) {
            $data = $res->toArray();
            $page['total'] =$data['total'];
            $page['size'] =$data['per_page'];
            $page['current'] =$data['current_page'];
        } else {
            $data = array();
        }
        $this->with('data',$data['data']);
        $this->with('page',$page);
        $this->display();
    }
    public function delAdminAction(){
        $id = $this->get('id');
        if ($id == 1) {
             return $this->json(array(
                'statusCode'=>  300,
                'message'  =>  '本管理员不可以删除',

            ));
        }
        !is_array($id) && $id = explode(',',$id);
        $res = AdminModel::whereIn('id',$id);
        if ($res->delete()) {
            return $this->json(array(
                'statusCode'=>  200,
                'message'  =>  '删除成功',

            ));
        } else {
            return $this->json(array(
                'statusCode'=>  300,
                'message'  =>  '删除失败',

            ));
        }
    }
    //添加管理
    public function addAdminAction() {
        if ($this->isPost()) {
            $password = $this->post('password');
            $res = new AdminModel();
            $res->username = $this->post('username');
            $res->sex = $this->post('sex');
            $res->realname = $this->post('realname');
            $res->phone = $this->post('phone');
            $res->email = $this->post('email');
            $res->password = md5($password);
            if(!$res->save()){
                return $this->json(array(
                    'statusCode'=>  300,
                    'messsage'  =>  '保存失败',
                ));
            } else {
                return $this->json(array(
                    'statusCode'=>  200,
                    'message'   =>  '保存成功',
                    'tabid' =>  'table, table-fixed',
                    'closeCurrent' => true,
                ));
            }
        } else {
            $this->display();
        }
    }
    public function editAdminAction() {
        if ($this->isPost()) {
            $id = $this->post('id');
            if ($id == 1) {
                return $this->json(array(
                    'statusCode'=>  300,
                    'message'  =>  '本管理员不可以其他用户编辑',

                ));
            }
            $password = $this->post('password');
            $res = AdminModel::where('id',$id)->first();
            $res->username = $this->post('username');
            $res->sex = $this->post('sex');
            $res->realname = $this->post('realname');
            $res->phone = $this->post('phone');
            $res->email = $this->post('email');
            if ($password) {
                $res->password = md5($password);
            }
            if(!$res->save()){
                return $this->json(array(
                    'statusCode'=>  300,
                    'messsage'  =>  '保存失败',
                ));
            } else {
                return $this->json(array(
                    'statusCode'=>  200,
                    'message'   =>  '保存成功',
                    'tabid' =>  'table, table-fixed',
                    'closeCurrent' => true,
                ));
            }
        } else {
            $id = $this->get('id');
            $res = AdminModel::where('id',$id)->first();
            if ($res) {
                $data = $res->toArray();
            } else {
                $data = array();
            }
            $this->with('data',$data);
            $this->display();
        }
    }
}
