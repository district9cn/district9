<?php
	/**
	 * @filename Redirect.php 页面跳转类
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

	class Redirect {
		
		/**
		 * 页面跳转方法
		 */
		public static function go($page = '', $args = array()) {
			if (!empty($page)) {
				if (!empty($args)) {
                    $i = 1;
                    $argstr = '';
                    foreach ($args as $key=>$value) {
                        
                        if ($i < count($args)) {
                            $argstr .= $key . '=' . $value . '&';
                        } else {
                            $argstr .= $key . '=' . $value;
                        }
                        $i++;			
                    }
					header('Location:' . $page . '?' . $argstr);
				} else {
					header('Location:' . $page);
				}
			}
		}
	}
?>
