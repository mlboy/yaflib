# yaflib
yaf php lib

一直说开源这个框架,但一直没时间整理,其实现在也是没整理的.

基本上都是一些开源的好用的类库的集合.

框架DB类用了ZendDB 或者 laravel 的Eloquent ORM  (可以在bootstrap中注释相应代码,默认是zenddb), 
回头会增加一个我自己写的小的操作dB的类库.

其中亮点在于模板,模板采用原生php格式,但是不是yaf原生模板,模板实现了继承,引用等.

集成了alipay的类,(并且把rsa加密和md5类合并在了一起,这样就可以通过配置切换了.之前淘宝的类是需要分别下载不同的两个.)

并且可以通过配置的形式实现增删改查.快速开发后台.后台用了bjui模板.

本类适合开发api接口,后台,以及web.


获取post参数方式

$this->post('name','默认值');

获取get参数方式

$this->get('name','默认值');

controller继承Core\C 

具有web属性

controller继承Core\Api

具有api接口属性,会有签名验证
