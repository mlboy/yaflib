<?php
class Inotify{
	private $handle;
	private $files =array();//array(array('path'=>'','mask'=>''));
	public static function isAbled() {
		$bool = extension_loaded('inotify') |extension_loaded('swoole');;
		return $bool;
	}
    public function __construct($files=null) {
		if (!self::isAbled()) {
			throw new \Exception('Not found inotify PHP module!');
		}
		$this->handle = inotify_init();
        foreach ($files as &$file) {
			if (!isset($file['mask'])) {
				$file['mask'] = IN_MODIFY | IN_CLOSE_WRITE | IN_MOVE | IN_CREATE | IN_DELETE;
			}
            $hash = md5($file['path'].$file['mask']);
            $watch = $this->addWatch($file['path'],$file['mask']);
            $this->files[$hash] = array(
                'path' =>$file['path'],
                'mask' =>$file['mask'],
            );
		}
	}
	public function addWatch($path, $mask = IN_MODIFY) {
		if (!file_exists($path)) {
			throw new \Exception('Path \''. $path. '\' not found.');
		}
		if (is_null($mask)) {
			throw new \Exception('Mask cannot be null.');
		}
		return inotify_add_watch($this->handle, $path, $mask);
	}

	public function queueLen() {
        return inotify_queue_len($this->handle);
    }
    public function listen($callback=null) {
        swoole_event_add($this->handle, function ($handle) use ($callback) {
            $events = inotify_read($handle);
            if ($events) {
                print_R($events);
                call_user_func_array($callback,$events);
            }
        });
    }
	public function rmWatch($watch_descriptor) {
		return inotify_rm_watch($watch_descriptor);
	}
	public function close() {
		fclose($this->handle);
	}
}
