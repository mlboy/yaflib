<?php
namespace Core;
class Curl extends C{
    public $pk = 'id';
    public $model;
    /*
    public $showField = array(
        'id'    =>  array('编号','string'),
        'sex'   =>  array('性别','enum',array('1'=>'男','2'=>'女'),false),
        'phone' =>  array('手机号','string','(086)%s'),
        'email' =>  array('邮箱','render',array('self','addLink')),
        'status' => array('状态','string','xx'),
        '#operate' => array('操作','operate',array(
            array('show','查看'),
            array('edit','编辑'),
            array('del','删除'),
        )),
    );
    public $addField = array('id','organization','status');
    public $editField = array('id','organization','status');
     */
    public function init(){
        parent::init();
        if (!$this->session['admin']) {
            $this->redirect('/admin/public/login');
        }
        $auth = new \Auth();
        if(!$auth->check('admin/index/index',$this->session['admin']['id'])){
            echo '没有权限';die;
        }
        $className = str_replace('Controller','',get_class($this)).'Model';
        $this->model = new $className;
        //$this->model = new RuleModel();
        $this->with('pk',$this->pk);
    }
    public function indexAction() {
        //<?php foreach($this->showField as $field)
        $page['page'] = $this->post('pageCurrent',1);
        $page['size'] = $this->post('pageSize',10);
        $page['order'] = $this->post('orderField',null);
        $page['by'] = $this->post('orderDirection','desc');
        \Paginator::currentPageResolver(function() use($page){
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
        $rows = $this->formatField($data['data']);
        //print_R($rows);die;
        $this->with('showField',$this->showField);
        $this->with('data',$rows);
        $this->with('page',$page);


        $this->display('public/index');
    }
    public function formatField($data) {
        $ret = array();
        foreach ($data as $k=>&$v) {
            foreach ($this->showField as $field =>$format) {
                if (is_array($format)) {
                    if (isset($v[$field])) {
                        if ($format[1] === 'string') {
                            if (isset($format[2])) {
                                $ret[$k][$field] = sprintf($format[2],$v[$field]);
                            } else {
                                $ret[$k][$field] = $v[$field];
                            }
                        } else if ($format[1] === 'enum') {
                            if (isset($format[2]) && is_array($format[2])) {
                                $ret[$k][$field] = isset($format[2][$v[$field]]) ? $format[2][$v[$field]] : $v[$field];
                            } else {
                                $ret[$k][$field] = '';
                            }
                        } else if ($format[1] === 'render') {
                            $ret[$k][$field] = call_user_func_array($format[2],array($v[$field],$v));
                        } else {
                            $ret[$k][$field] = $v[$field];
                        }
                    } else {
                        if ($format[1] === 'render') {
                            $ret[$k][$field] = call_user_func_array($format[2],array($v[$field],$v));
                        } else if ($format[1] === 'string') {
                            $ret[$k][$field] = $format[2];
                        } else if ($format[1] === 'operate') {
                            if (isset($format[2]) && is_array($format[2])) {
                                $ret[$k][$field] = '';
                                foreach ($format[2] as $operate) {
                                    if (is_array($operate)) {
                                        if ($operate[0] === 'show') {
                                            $tmp = "<a href=\"%s\" class=\"%s\" data-toggle=\"dialog\" data-id=\"form\" data-title=\"%s\"  data-width=\"800\">%s</a> ";
                                            $href = Helper::url('show',array($this->pk=>$v[$this->pk]));
                                            $class = 'btn btn-blue';
                                            $ret[$k][$field] .= sprintf($tmp,$href,$class,$operate[1],$operate[1]);
                                        } else if ($operate[0] === 'edit') {
                                            $tmp = "<a href=\"%s\" class=\"%s\" data-toggle=\"dialog\" data-id=\"form\" data-title=\"%s\"  data-width=\"800\">%s</a> ";
                                            $href = Helper::url('edit',array($this->pk=>$v[$this->pk]));
                                            $class = 'btn btn-green';
                                            $ret[$k][$field] .= sprintf($tmp,$href,$class,$operate[1],$operate[1]);
                                        } else if ($operate[0] === 'del') {
                                            $tmp = "<a href=\"%s\" class=\"%s\" data-toggle=\"doajax\" data-confirm-msg=\"确定要删除该行信息吗？\">%s</a> ";
                                            $href = Helper::url('del',array($this->pk=>$v[$this->pk]));
                                            $class = 'btn btn-red';
                                            $ret[$k][$field] .= sprintf($tmp,$href,$class,$operate[1],$operate[1]);
                                        } else {
                                             $ret[$k][$field] .= $operate[1];
                                        }
                                    }
                                }
                            } else {
                                $ret[$k][$field] = '';
                            }
                        } else {
                            $ret[$k][$field] = '';
                        }
                    }
                }
            }
        }
        return $ret;
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
            $id = $this->post($this->pk);
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
            $id = $this->get($this->pk);
            $res = $this->model;
            $res = $res->where($this->pk,$id)->first();
            if ($res) {
                $data = $res->toArray();
            } else {
                $data = array();
            }
            $this->with('data',$data);
            $this->display();
        }
    }
    public function statusAction(){
        $id = $this->get($this->pk);
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
        $id = $this->get($this->pk);
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
