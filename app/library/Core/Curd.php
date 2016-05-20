<?php
namespace Core;
class Curd extends C{
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
        $rows = $this->listField($this->listField,$data['data']);
        //print_R($rows);die;
        $this->with('fields',$this->listField);
        $this->with('data',$rows);
        $this->with('page',$page);
        $this->display('public/index');
    }
    public function formatField2($data) {
        $ret = array();
        foreach ($data as $k=>&$v) {
            foreach ($this->listField as $field =>$format) {
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
    public function operateFormat($r,$obj,$data = array(array('show','查看'),array('edit','编辑'),array('del','删除'))){
        $ret = '';
        foreach($data as $k=>$v) {
            if ($v[0] == 'show') {
                $tmp = "<a href=\"%s\" class=\"%s\" data-toggle=\"dialog\" data-id=\"form\" data-title=\"%s\"  data-width=\"800\">%s</a> ";
                $href = Helper::url('show',array($this->pk=>$obj[$this->pk]));
                $class = 'btn btn-blue';
                $ret .= sprintf($tmp,$href,$class,$v[1],$v[1]);
            }
            if ($v[0] == 'edit') {
                $tmp = "<a href=\"%s\" class=\"%s\" data-toggle=\"dialog\" data-id=\"form\" data-title=\"%s\"  data-width=\"800\">%s</a> ";
                $href = Helper::url('edit',array($this->pk=>$obj[$this->pk]));
                $class = 'btn btn-green';
                $ret .= sprintf($tmp,$href,$class,$v[1],$v[1]);
            }
            if ($v[0] == 'del') {
                $tmp = "<a href=\"%s\" class=\"%s\" data-toggle=\"doajax\" data-confirm-msg=\"确定要删除该行信息吗？\">%s</a> ";
                $href = Helper::url('del',array($this->pk=>$obj[$this->pk]));
                $class = 'btn btn-red';
                $ret .= sprintf($tmp,$href,$class,$v[1],$v[1]);
            }
        }
        return $ret;
    }
    public function listField(&$fields = null,$data = null) {
        if (!$fields) $fields = $this->listField;
        $ret = array();
        foreach ($fields as &$field) {
            //array($name,$lable,$type,$defulft,$required),
            (!isset($field['index'])) && $field['index'] = $this->pk;
            (!isset($field['title'])) && $field['title'] = $field['index'];
            (!isset($field['render'])) && $field['render'] = null;
            $index = preg_replace('/(#?)(\w+)/i','${2}',$field['index']);
            foreach($data as $k=>$v) {
                if (isset($v[$index])) {
                    $col = $v[$index];
                } else {
                    $col = '';
                }
                if (isset($field['render'])) {
                    if (is_string($field['render'])) {
                        $callback = $field['render'];
                    } else {
                        if (is_array($field['render'][1])) {
                            $callback = $field['render'][0];
                        } else{
                            $callback = $field['render'];
                        }
                        $def = $field['render'][1];
                    }
                    if (is_callable($callback)) {
                        $ret[$k][$field['index']] = call_user_func_array($callback,array($col,$v,$def));
                    } else {
                        $ret[$k][$field['index']] = sprintf($field['render'],$col);
                    }
                } else {
                    $ret[$k][$field['index']] = $col;
                }
            }
        }
        return $ret;
    }
    public function showAction() {
        $id = $this->get($this->pk);
        $res = $this->model;
        $res = $res->where($this->pk,$id)->first();
        if ($res) {
            $data = $res->toArray();
        } else {
            $data = array();
        }
        $fields = $this->showField($this->showField,$data);
        $this->with('data',$data);
        $this->with('fields',$fields);
        $this->display('public/show');
    }
    public function showField($fields = null,$data = null) {
        if (!$fields) $fields = $this->showField;
        $ret = array();
        foreach ($fields as $field) {
            //array($name,$lable,$type,$defulft,$required),
            if (is_string($field)) {
                $tmp = '<label for="' .$field. '" class="control-label x85">' .$field. ':</label>';
                $tmp .= $data[$field];
                $ret[$field] = $tmp;
            } else if (is_array($field)) {
                (!isset($field[1])) && $field[1] = $field[0];
                (!isset($field[2])) && $field[2] = 'text';
                (!isset($field[3])) && $field[3] = '';
                (!isset($field[4])) && $field[4] = true;
                $tmp = '<label for="' .$field[0]. '" class="control-label x85">' .$field[1]. ':</label>';
                if ($field[2] === 'text') {
                    $tmp.= $field[3]?$field[3]:$data[$field[0]]?$data[$field[0]]:'';
                } else if ($field[2] === 'textarea'){
                    $tmp.= $field[3]?$field[3]:$data[$field[0]]?$data[$field[0]]:'';
                } else if ($field[2] === 'select') {
                    $tmp.= $field[3]?$field[3]:$data[$field[0]]?$data[$field[0]]:'';
                } else if ($field[2] === 'check') {
                    $tmp.= $field[3]?$field[3]:$data[$field[0]]?$data[$field[0]]:'';
                } else if ($field[2] === 'datetime') {
                    $tmp.= $field[3]?$field[3]:$data[$field[0]]?$data[$field[0]]:'';
                } else if ($field[2] === 'kindeditor') {
                    $tmp.= $field[3]?$field[3]:$data[$field[0]]?$data[$field[0]]:'';
                }
                $ret[$field[0]] = sprintf($tmp,$field);
            }
        }
        return $ret;
    }
    public function editField($editField = null,$data = null) {
        if (!$editField) $editField = $this->editField;
        $ret = array();
        foreach ($addField as $field) {
            if (is_string($field)) {
                $tmp = '<label for="' .$field. '" class="control-label x85">' .$field. ':</label>';
                $tmp .= '<input type="text" name="' .$field. '" id="' .$field. '" value="" data-rule="required">';
                $ret[$field] = $tmp;
            }
        }
        return $ret;
    }
    public function addField($addField = null,$data = null) {
        if (!$addField) $addField = $this->addField;
        $ret = array();
        foreach ($addField as $field) {
            //array($name,$lable,$type,$defulft,$required),
            if (is_string($field)) {
                $tmp = '<label for="' .$field. '" class="control-label x85">' .$field. ':</label>';
                $tmp .= '<input type="text" name="' .$field. '" id="' .$field. '" value="" data-rule="required">';
                $ret[$field] = $tmp;
            } else if (is_array($field)) {
                (!isset($field[1])) && $field[1] = $field[0];
                (!isset($field[2])) && $field[2] = 'text';
                (!isset($field[3])) && $field[3] = '';
                (!isset($field[4])) && $field[4] = true;
                $tmp = '<label for="' .$field[0]. '" class="control-label x85">' .$field[1]. ':</label>';
                if ($field[2] === 'text') {
                    $tmp .= '<input type="text" name="' .$field[0]. '" id="' .$field[0]. '" value="'.$field[3].'" ';
                    if ($field[4] === true) {
                        $tmp .= 'data-rule="required"';
                    } else if ($field[4] !== false) {
                        $tmp .= 'data-rule="'.$field[4].'"';
                    }
                    $tmp .= '>';
                } else if ($field[2] === 'textarea'){
                    $tmp .= '<textarea name="' .$field[0]. '"class="form-control">' .$field[3].'</textarea>';

                } else if ($field[2] === 'select') {

                } else if ($field[2] === 'check') {
                    //$tmp .= '<input type="radio" name="' .$field[0].'" id="'.$field[0].'" data-toggle="icheck" value="true" data-rule="checked" data-label="男&nbsp;&nbsp;">';
                } else if ($field[2] === 'datetime') {
                    $tmp .= '<input type="text" name="' .$field[0].'" id="' .$field[0] .'" value="'.$field[3].'" data-toggle="datepicker" data-rule="required;date" size="15">';
                } else if ($field[2] === 'kindeditor') {
                    $tmp .= '<div style="display: inline-block; vertical-align: middle;">';
                    $tmp .= '<textarea name="'.$field[0].'" id="'.$field[0].'" class="j-content" style="width: 700px;" data-toggle="kindeditor" data-minheight="200">'.$field[3].'</textarea>';
                    $tmp .= '</div>';
                }
                $ret[$field[0]] = sprintf($tmp,$field);
            }
        }
        return $ret;
    }
    public function addAction() {
        if ($this->isPost()) {
            $res = $this->model;
            foreach ($this->addField as $field) {
                if (is_array($field)) {
                    $field  = $field[0];
                }
                $res->$field = $this->post($field);
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
            $addField = $this->addField();
            $this->with('fields',$addField);
            $this->display('public/add');
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
            $this->display('public/edit');
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
