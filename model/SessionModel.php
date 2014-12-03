<?php
	/**
	 * @filename SessionModel.php 将session记录到数据库，统计在线人数和自定义session方法。
	 * @author 李纲彬
	 * @createTime 2014年11月18日
	 */

    class SessionModel { 
        protected $db, 
                  $fleetctl,
                  $timeout,
                  $docker,
                  $table = 'sessions';
                    /*对应表名sessions, 字段包括id, data, created
                        id:sessionid非空，主键
                        data:session 数据
                        created:创建时间
                    */
		protected static $instance;

		/**
		 * 构造方法
		 */
		public function __construct() {
		    $this->db = DbModle::getInstance();
            $this->fleetctl = FleetCtl::getInstance();
            $this->docker = new DockerModel();
            $this->timeout = 30*60;

            session_module_name('user');
            session_set_save_handler( 
                array(&$this, 'open'),
                array(&$this, 'close'),
                array(&$this, 'read'),
                array(&$this, 'write'),
                array(&$this, 'destroy'),
                array(&$this, 'gc')
            ); 
            session_start();
        } 
       
		/**
		 * 静态方法来连接数据库并得到类的对象 静态方法只能访问静态的属性
		 */
		public static function getInstance() {
			if (!isset(self::$instance)) {
				self::$instance = new SessionModel();
			}
			return self::$instance;
		}
		
        /*
         * 在运行session_start()时执行
         */
        public function open($path, $name) { 
            return true; 
        } 

        /*
         * 在脚本执行完成或调用session_write_close() 或 session_destroy()时被执行,即在所有session操作完后被执行
         */
        public function close() { 
            chdir(dirname(__FILE__));
            $this->gc($this->timeout);
            return true; 
        } 

        /*
         * 在运行session_start()时执行,因为在session_start时,会去read当前session数据
         */
        public function read($id){
            chdir(dirname(__FILE__));
			if ($this->db->getRows($this->table, array('id', '=', $id))->getCount() > 0) {
                $re = $this->db->getResults();
				return $re[0]['data'];
			}

            return '';
        } 

        /*
         * 此方法在脚本结束和使用session_write_close()强制提交SESSION数据时执行
         */
        public function write($id,$data) { 
            chdir(dirname(__FILE__));
            $time = date('Y-m-d H:i:s');
            $sql = "replace into `$this->table` values('$id', '$data', '$time')";
            $this->db->query($sql);
            return true;
        } 

        /*
         * 在运行session_destroy()时执行
         */
        public function destroy($id) { 
            chdir(dirname(__FILE__));

            $dockername = Session::get(Config::get('session/newdocker'));
            if(!empty($dockername)) {
                $this->fleetctl->removeDocker($dockername);
            }

            $this->db->delete($this->table, array('id', '=', $id));

            return true; 
        } 

        /*
         * 执行概率由session.gc_probability 和 session.gc_divisor的值决定,时机是在open,read之后,session_start会相继执行open,read和gc
         */
        public function gc($lifetime) {
            chdir(dirname(__FILE__));
            $expire = date('Y-m-d H:i:s', time() - $lifetime);

			if ($this->db->getRows($this->table, array('created', '<', $expire))->getCount() > 0) {
                $re = $this->db->getResults();
                foreach($re as $record) {
                    $dockername = Session::get(Config::get('session/newdocker'));
                    if(!empty($dockername)) {
                        $this->fleetctl->removeDocker($dockername);
                    }
                }
			}

            $this->db->delete($this->table, array('created', '<', $expire));
            return true;
        } 
    } 
?>
