<?php
class UserController extends Core\Curd{
    public $pk = 'id';
    /*
    public $listField = array(
        'id'    =>  array('编号','string'),
        'sex'   =>  array('性别','enum',array('1'=>'男','2'=>'女'),false),
        'phone' =>  array('手机号','string','(086)%s'),
        'email' =>  array('邮箱','render',array('self','addLink')),
        'status' => array('状态','string','xx'),
        '#operate' => array('操作','operate',array(
            array('show','查看'),
            array('edit','编辑'),
            array('del','删除'),
        ),false),
    );
     */
    public $listField = array(
        //title: '编号', dataIndex: 'id', width: 80, elCls: 'center'
        array(
            'index' => 'id',
            'title' => '编号',
        ),
        array(
            'index' =>'phone',
            'title' =>'手机号',
            'render' => '(086)%s',
        ),
        array(
            'index' => 'email',
            'title' => '邮箱',
            'render' => array('self','addLink')
        ),
        array(
            'index' => 'nickname',
            'title' => '昵称',
            'render' => array('self','addLink')
        ),
        array(
            'index' => '#operate',
            'title' => '操作',
            'render' => array(
                array('self','operateFormat'),
                array(
                    array('show','查看'),
                    array('edit','编辑'),
                    array('del','删除'),
                ),
            ),
        ),
    );
    public $showField = array(
        'id',
        array('phone','手机号','text','',true),
        array('realname','真实姓名','text','',true),
        array('realname','真实姓名','kindeditor','',true),
        'status',
        array('updated_at','修改时间','datetime',''),
    );
    //array($name,$lable,$type,$defulft,$required,$class),
    public $addField = array(
        'id',
        array('phone','手机号','text','',true),
        array('realname','真实姓名','text','',true),
        array('realname','真实姓名','kindeditor','',true),
        'status',
        array('updated_at','修改时间','datetime',''),
    );
    public $editField = array('id','organization','status');
    public function addLink($item,$row){
        return 'mailto:'.$item;
    }
}
