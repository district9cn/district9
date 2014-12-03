<?php
	/**
	 * @filename Cookie.php cookie相关操作类
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

    class Cookie {
        
        /*
         * 判断cookie是否存在的方法
         */
        public static function exists($name) {
            if (isset($_COOKIE[$name])) {
                return true;
            }
            return false;
        }
        
        /*
         * 创建cookie方法
         */
        public static function create($name, $value) {
            setcookie($name, $value, time() + Config::get('cookie/expire'), '/');
        }
        
        /*
         * 定义一个得到cookie值的方法
         */
        public static function get($name) {
            if (self::exists($name)) {
                return $_COOKIE[$name];
            }
            return false;
        }
        
        /*
         * 删除用户cookie值的方法
         */
        public static function delete($name) {
            if (self::exists($name)) {
                setcookie($name, '', time() - 1);
            }
        }
    }

?>
