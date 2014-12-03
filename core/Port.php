<?php
	/**
	 * @filename Port.php 用于得到ssh映射的端口
	 * @author 李纲彬
	 * @createTime 2014年11月17日
	 */
    require_once('init.php');

	class Port {
        /*
         * 从全局变量中释放端口的占用
         */
		public static function release($port) {
            $app = Application::getInstance()->getValue('port'); 
            if(in_array($port, $app)) {
                unset($app[array_search($port , $app)]);
                Application::getInstance()->setValue('port', $app);
            }
		}
		
        /*
         * 获取可用端口,并存入全局变量中
         */
		public static function get() {
            $app = Application::getInstance()->getValue('port'); 
            if(empty($app)) {
                $app = array();
            }

            for ($port=Config::get('docker/port/min'); $port<=Config::get('docker/port/max'); $port++) {
                if(in_array($port, $app) || in_array($port, Config::get('docker/port/except'))) {
                    continue;
                }

                array_push($app, $port);
                Application::getInstance()->setValue('port', $app);
                return $port;
            }
		}
	}
?>
