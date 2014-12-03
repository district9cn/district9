<?php
	/**
	 * @filename Token.php 令牌相关操作类 （CSRF防御）
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

	class Token {
		
		/**
		 * 生成CSRG防御的加密串 然后将生成的加密串放到session中进行存储
		 */
		public static function create() {
			return Session::create(Config::get('session/token'), Hash::unique());
		}
		
		/**
		 * 验证token是否合法的方法
		 */
		public static function check($token) {
			if (Session::exists(Config::get('session/token')) && $token == Session::get(Config::get('session/token'))) {
				return true;
			}
			return false;
		}
	}
?>
