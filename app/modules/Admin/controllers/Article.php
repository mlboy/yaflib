<?php
class ArticleController extends Core\Curd{
    public $pk = 'article_id';
    public $listField = array(
        array(
            'index' => 'article_id',
            'title' => '编号',
        ),
        array(
            'index' => 'title',
            'title' => '标题',
            'render' => array('self','addLink'),
        ),
        array(
            'index' => '#operate',
            'title' =>'操作',
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
        'article_id',
        array('article_id','编号'),
    );
    //array($name,$lable,$type,$defulft,$required,$class),
    public $addField = array(
        'id',
        array(
            'index' => 'phone',
            'title' => '手机号',
            'text','',true),
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
