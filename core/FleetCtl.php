<?php
	/**
	 * @filename fleetctl.php 用于实现修改fleetctl配置文件
	 * @author 李纲彬
	 * @createTime 2014年11月14日
	 */
    require_once('init.php');
 
	class FleetCtl implements DockerCtlIf {
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
				self::$instance = new FleetCtl();
			}
			return self::$instance;
		}

		/**
		 * 配置owncloud配置文件，暂未使用
		 */
        public function getOwncloudConfig($username) {
            $this->error = false;
            try {
                $file = file_get_contents($this->dirname . '/../etc/fleetctl/' . Config::get('fleetctl/owncloud/postgres_example'));
                $newfile = preg_replace('/'.Config::get('fleetctl/owncloud/postgres_name').'/', 'postgres_' . $username, $file);
                file_put_contents($this->dirname . '/../temp/postgres_'.$username, $newfile);

                $file = file_get_contents($this->dirname . '/../etc/fleetctl/' . Config::get('fleetctl/owncloud/owncloud_example'));
                $newfile = preg_replace('/'.Config::get('fleetctl/owncloud/postgres_name').'/', 'postgres_' . $username, $file);
                $newfile = preg_replace('/'.Config::get('fleetctl/owncloud/owncloud_name').'/', 'owncloud_' . $username, $newfile);
                $newfile = preg_replace('/'.Config::get('fleetctl/owncloud/owncloud_port').'/', '8080', $newfile);
                file_put_contents($this->dirname . '/../temp/owncloud_'.$username, $newfile);
            } catch (Exception $e) { 
                $this->error = true;
            }

            return $this;
        }

		/**
		 * 配置ubuntussh配置文件
		 */
        public function getUbuntusshConfig($session_id, $port) {
            $this->error = false;
            try {
                $file = file_get_contents($this->dirname . '/../etc/fleetctl/' . Config::get('fleetctl/ubuntussh/ubuntussh_example'));
                $newfile = preg_replace('/'.Config::get('fleetctl/ubuntussh/ubuntussh_name').'/', $session_id, $file);
                $newfile = preg_replace('/'.Config::get('fleetctl/ubuntussh/ubuntussh_port').'/', $port, $newfile);
                file_put_contents($this->dirname . '/../temp/'.$session_id, $newfile);
            } catch (Exception $e) { 
                $this->error = true;
            }

            return $this;
        }

		/**
		 * 根据配置文件启动一个docker
		 */
        public function createDocker($dockername, $port) {
            $this->getUbuntusshConfig($dockername, $port);

            $ubuntussh = $this->dirname . '/../temp/' . $dockername;

            $this->ssh->sendfile($ubuntussh, '/root/fleetctl/', 0777);
            $this->ssh->exec('fleetctl start /root/fleetctl/' . $dockername);

            $this->log->debug($this->ssh->getResults());
            preg_match('/Unit.*launched on.*\/(.*)/', $this->ssh->getResults(), $result);
            if (empty($result)) {
                return null;
            }
            $server = $result[1];

            return $server;
        }

		/**
		 * 停止一个docker
		 */
        public function stopDocker($dockername) {
            $this->ssh->exec('fleetctl stop ' . $dockername);

            $this->log->debug($this->ssh->getResults());
        }

		/**
		 * 启动一个docker
		 */
        public function startDocker($dockername) {
            $this->ssh->exec('fleetctl start ' . $dockername);

            $this->log->debug($this->ssh->getResults());
        }

		/**
		 * 删除一个docker
		 */
        public function removeDocker($dockername) {
            $server = $this->getServer($dockername);
            $this->ssh->exec('fleetctl stop ' . $dockername);
            $this->ssh->exec('fleetctl destroy ' . $dockername);
            $this->ssh->exec('ssh '.$server.' docker rm ' . $dockername);
        }

		/**
		 * 根据dockername获取docker所在的服务器ip
		 */
        public function getServer($dockername) {
            $this->ssh->exec('fleetctl list-units | grep ' . $dockername);

            preg_match('/' . $dockername . '\.service.*\/(\S+)\s+\S+\s+(\S+)/', $this->ssh->getResults(), $result);
            if (empty($result)) {
                return null;
            }

            $server = $result[1];

            return $server;
        }

		/**
		 * 根据dockername获取docker所在的服务器映射的端口
		 */
        public function getPort($dockername) {
            $this->ssh->exec('cat fleetctl/'.$dockername.' | grep run');

            preg_match('/run -p (\d+):\d+ --name/', $this->ssh->getResults(), $result);
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
