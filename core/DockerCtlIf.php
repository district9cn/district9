<?php
	/**
	 * @filename DockerCtlIf.php docker操作接口
	 * @author 李纲彬
	 * @createTime 2014年12月4日
	 */
    require_once('init.php');
 
	interface DockerCtlIf {
		/**
		 * 启动一个docker
		 */
        public function createDocker($dockername, $port);

		/**
		 * 停止一个docker
		 */
        public function stopDocker($dockername);

		/**
		 * 启动一个docker
		 */
        public function startDocker($dockername);

		/**
		 * 删除一个docker
		 */
        public function removeDocker($dockername);

		/**
		 * 根据dockername获取docker所在的服务器ip
		 */
        public function getServer($dockername);

		/**
		 * 根据dockername获取docker所在的服务器映射的端口
		 */
        public function getPort($dockername);

		/**
		 * 得到执行错误信息方法
		 */
		public function getError();
    }
?>
