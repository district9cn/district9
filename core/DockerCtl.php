<?php
	/**
	 * @filename DockerCtl.php 用于docker命令操作实现docker控制
	 * @author 李纲彬
	 * @createTime 2014年11月14日
	 */
    require_once('init.php');
 
	class DockerCtl implements DockerCtlIf {
        protected $log,
                  $ssh,
                  $error = false,
                  $dirname;
		protected static $instance;

		/**
		 * 构造方法 连接数据库
		 */
		protected function __construct() {
            $this->dirname = dirname(__FILE__);
            $this->log = Plog::factory(__FILE__);
            $this->ssh = new Ssh();
        }

		/**
		 * 静态方法来连接数据库并得到类的对象 静态方法只能访问静态的属性
		 */
		public static function getInstance() {
			if (!isset(self::$instance)) {
				self::$instance = new DockerCtl();
			}
			return self::$instance;
		}

		/**
		 * 根据配置文件启动一个docker
		 */
        public function createDocker($dockername, $port) {
            #docker run -d -p 1112:22 --name 29bd3fe6f4bb2c1484d477fe189618bd 350f7e75798a
            $this->ssh->exec('docker run -d -p ' . $port . ':22 --name ' . $dockername . ' ' . Config::get('dockerctl/image'));

            $this->log->debug($this->ssh->getResults());

            return Config::get('dockerctl/server');
        }

		/**
		 * 停止一个docker
		 */
        public function stopDocker($dockername) {
            $this->ssh->exec('docker stop ' . $dockername);

            $this->log->debug($this->ssh->getResults());
        }

		/**
		 * 启动一个docker
		 */
        public function startDocker($dockername) {
            $this->ssh->exec('docker start ' . $dockername);

            $this->log->debug($this->ssh->getResults());
        }

		/**
		 * 删除一个docker
		 */
        public function removeDocker($dockername) {
            $this->ssh->exec('docker rm ' . $dockername);
        }

		/**
		 * 根据dockername获取docker所在的服务器ip
		 */
        public function getServer($dockername) {
            return Config::get('dockerctl/server');
        }

		/**
		 * 根据dockername获取docker所在的服务器映射的端口
		 */
        public function getPort($dockername) {
            #docker ps -a list | grep 29bd3fe6f4bb2c1484d477fe189618bd
            $this->ssh->exec('docker ps -a list | grep '.$dockername);

            preg_match('/\d+\.\d+\.\d+\.\d+\:(\d+)->\d+/', $this->ssh->getResults(), $result);
            if (empty($result)) {
                return null;
            }
            $port = $result[1];

            return $port;
        }

		/**
		 * 得到执行错误信息方法
		 */
		public function getError() {
			return $this->error;
		}
    }
?>
