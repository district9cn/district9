<?php
	/**
	 * @filename ssh.php 用于实现ssh远程登陆和执行命令
	 * @author 李纲彬
	 * @createTime 2014年11月12日
	 */
    require_once('init.php');
 
	class Ssh {
		protected $conn,
				  $error = false,
				  $results;

		/**
		 * 构造方法 连接ssh服务器
		 */
		public function __construct() {
            $this->log = Plog::factory(__FILE__);
            //$this->connection();
        }

		/**
		 * 使用密码进行认证
		 */
        public function connWithPass() {
            $this->conn = ssh2_connect(Config::get('ssh/host'), Config::get('ssh/port')) or 
                die('connection to '.Config::get('ssh/host').':'.Config::get('ssh/port').' failed');
            $this->auth = ssh2_auth_none($this->conn, Config::get('ssh/username'));
            if (in_array('password', $this->auth)) {
                if (!ssh2_auth_password($this->conn, Config::get('ssh/username'), Config::get('ssh/password'))){
                    $this->error = true;
                }
            }
        }


		/**
		 * 使用key文件进行认证
		 */
        public function connWithKey() {
            $this->conn = ssh2_connect(Config::get('ssh/host'), Config::get('ssh/port'), array('hostkey'=>'ssh-rsa')) or 
                die('connection to '.Config::get('ssh/host').':'.Config::get('ssh/port').' failed');
            if(!ssh2_auth_pubkey_file($this->conn, 
                    Config::get('ssh/username'), 
                    Config::get('ssh/rsa_pub'), 
                    Config::get('ssh/rsa_key'))) {
                $this->error = true;
            }
        }

        /**
         * 连接ssh服务器
         */
        public function connection() {
            $method = Config::get('ssh/method');
            switch($method) {
                case 'password':
                    $this->connWithPass();
                    break;
                case 'key':
                    $this->connWithKey();
                    break;
                default:
                    return false;
            }
        }

		/**
		 * 执行命令方法
		 */
		public function exec($cmd) {
            if(empty($this->conn)) {
                $this->connection();
            }

            $this->error = false;
            $stream = ssh2_exec($this->conn, $cmd);
            stream_set_blocking($stream, true);
     
            if ($stream === FALSE) {
                $this->error = true;
            } else {
                $this->results = stream_get_contents($stream);
            }

            $this->log->debug($cmd);
            return $this;
        }

		/**
		 * 向远程主机发送文件
		 */
		public function sendfile($source, $remote, $mod) {
            if(empty($this->conn)) {
                $this->connection();
            }
            $this->error = false;
            if ($this->conn) {
                $sftp = ssh2_sftp($this->conn);

                if (strrchr($remote, '/')) {
                    $filename = basename($source);
                    $dirname = $remote;
                } else {
                    $filename = basename($remote);
                    $dirname = dirname($remote);
                }

                $path = '';
                foreach(explode('/', $dirname) as $dir) {
                    if($dir) {
                        $path .= '/'.$dir;
                        ssh2_sftp_mkdir($sftp, $path);
                    }
                }

                if (!ssh2_scp_send($this->conn, $source, $dirname.$filename, $mod)) {
                    $this->error = true;
                } 
            } else {
                $this->error = true;
            }

            return $this;
        }

		/**
		 * 得到执行错误信息方法
		 */
		public function getError() {
			return $this->error;
		}
		
		/**
		 * 得到查询结果方法
		 */
		public function getResults() {
			return $this->results;
		}
		
    }
?>
