<?php
	/**
	 * @filename Session.php session相关操作类
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

	class Session {
		
		/**
		 * 判断session是否存在
		 */
		public static function exists($name) {
			if (isset($_SESSION[$name])) {
				return true;
			}
			return false;
		}
		
		/**
		 * 生成session的方法
		 */
		public static function create($name, $value) {
			return $_SESSION[$name] = $value;
		}
		
		/**
		 * 删除session方法
		 */
		public static function delete($name) {
			if (self::exists($name)) {
				unset($_SESSION[$name]);
			}
			return false;
		}
		
		/**
		 * 得到session值的方法
		 */
		public static function get($name) {
			if (self::exists($name)) {
				return $_SESSION[$name];
			}
			return false;
		}
	}
?>
