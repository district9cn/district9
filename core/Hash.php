<?php
	/**
	 * @filename Hash.php 加密相关操作类
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

	class Hash {
		
		/**
		 * 生成加密方法
		 * 实现方法：加盐加密
		 * 就是将我们用户输入的值后加一些加密码的字串 将整个的加密过程变得理加复杂 md5(123lkdfkfdkjlfdkjldfjlfdjl)
		 * @param string $string 将要加密的字符串
		 * @param $salt 加盐加密的盐
		 */
		public static function create($string, $salt = '') {
			return hash('sha256', $string . $salt);
		}
		
		/**
		 * 生成加盐加密的盐
		 */
		public static function salt() {
			return md5(time() . uniqid());
		}
		
		/**
		 * 生成唯一的一个加密方法
		 */
		public static function unique() {
			return self::create(uniqid());
		}
	}
?>
