<?php
class CategoryController extends Core\C{
    public function init(){
        parent::init();
        if (!$this->session['admin']) {
            $this->redirect('/admin/public/login');
        }
        $auth=new Auth();
        if(!$auth->check('admin/index/index',$this->session['admin']['id'])){
            echo '没有权限';die;
        }
        $this->model = new CategoryModel();
        $this->pk = 'id';
        $this->addField = array('id','organization','status');
        $this->editField = array('serials','organization','status');

        $this->with('pk',$this->pk);
    }
    public function jsonAction() {
        $res = $this->model;
        $res = $res->get();
        if ($res) {
            $data = $res->toArray();
        } else {
            $data = array();
        }
        $this->json($data);
    }
    public function indexAction() {
        $page['page'] = $this->post('pageCurrent',1);
        $page['size'] = $this->post('pageSize',10);
        $page['order'] = $this->post('orderField',null);
        $page['by'] = $this->post('orderDirection','desc');
        Paginator::currentPageResolver(function() use($page){
            return $page['page'];
        });
        $res = $this->model;
        if ( $page['order'] && $page['by']) {
            $res = $res->orderBy($page['order'],$page['by']);
        }
        $res = $res->paginate($page['size']);
        if ($res) {
            $data = $res->toArray();
            $page['total'] =$data['total'];
            $page['size'] =$data['per_page'];
            $page['current'] = $data['current_page'];
        } else {
            $data = array();
        }
        $this->with('data',$data['data']);
        $this->with('page',$page);
        $this->display();
    }
    public function addAction() {
        if ($this->isPost()) {
            $res = $this->model;
            foreach ($this->addField as $k =>$v) {
                $res->$v = $this->post($v);
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
            $this->display();
        }
    }
    public function editAction() {
        if ($this->isPost()) {
            $id = $this->post('id');
            $res = $this->model;
            $res = $res->where($this->pk,$id)->first();
            foreach ($this->editField as $k =>$v) {
                $res->$v = $this->post($v);
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
            $res = $this->model;
            $res = $res->where($this->pk,$id)->first();
            if ($res) {
                $data = $res->toArray();
                $cate = DeviceCategoryModel::where('serials',$data['serials'])->lists('category_id');
                $data['category_id'] = implode(',',$cate->toArray());
            } else {
                $data = array();
            }
            $this->with('data',$data);
            $this->display();
        }
    }
    public function statusAction(){
        $id = $this->get('id');
        !is_array($id) && $id = explode(',',$id);
        $res = $this->model;
        $res = $res->whereIn($this->pk,$id)->first();
        $res->status = 1-$res->status;
        if ($res->save()) {
            return $this->json(array(
                'statusCode'=>  200,
                'message'  =>  '操作成功',

            ));
        } else {
            return $this->json(array(
                'statusCode'=>  300,
                'message'  =>  '操作失败',

            ));
        }
    }
    public function delAction(){
        $id = $this->get('id');
        !is_array($id) && $id = explode(',',$id);
        $res = $this->model;
        $res = $res->whereIn($this->pk,$id);
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
}
