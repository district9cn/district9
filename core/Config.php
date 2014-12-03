<?php
	/**
	 * @filename Config.php 用于得到系统初始化设置中的配置信息
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

	class Config {
		
        /*
         * 定义一个静态的得到配置信息的方法
         */
		public static function get($path = '') {
			if (!empty($path)) {
				$paths = explode('/', $path);
                $value = $GLOBALS['config'];

                foreach($paths as $path) {
                    $value = $value[$path];
                }
				
				return $value;
			}

			return false;
		}
	}
?>
