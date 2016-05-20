<?php
class TestController extends Core\C{
    public function countAction() {
        $article = \Business\commentModel::lists($pid);
        $ret = $this->ok($article);
        return $this->json($ret);
    }
    public function testAction() {
        $files = array(
            array('path'=>'/tmp/test/','mask'=>IN_MODIFY | IN_CLOSE_WRITE | IN_MOVE | IN_CREATE | IN_DELETE),
        );
        $inotify = new Inotify($files);
        $inotify->listen(array('self','listen'));
    }
    public function listen($events) {
        print_r($events);
    }
}

