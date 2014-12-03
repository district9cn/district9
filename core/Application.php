<?php
	/**
	 * @filename Application.php 全局共享变量，用文件模拟application
	 * @author 李纲彬
	 * @createTime 2014年11月17日
	 */

    class Application { 
        protected $save_file = '../etc/Application',
                  $application; 
		protected static $instance;

        /** 
         * 构造函数 
         */ 
		public function __construct() {
            $this->application = array(); 
        } 

		/**
		 * 静态方法来连接数据库并得到类的对象 静态方法只能访问静态的属性
		 */
		public static function getInstance() {
			if (!isset(self::$instance)) {
				self::$instance = new Application();
			}
			return self::$instance;
		}
		
        /** 
         * 设置全局变量 
         */ 
        public function setValue($key,$value) { 
            if (!is_string($key) || empty($key)) 
                return false; 

            $this->readFromFile(); 
            if (!is_array($this->application)) {
                settype($this->application,"array"); 
            }

            $this->application[$key] = $value; 
            $this->writeToFile(); 
        } 

        /** 
         * 取得保存在全局变量里的值 
         */ 
        public function getValue($key) {
            $this->readFromFile(); 
            if(!isset($this->application[$key])) {
                return null;
            }
            return $this->application[$key];
        }

        /** 
         * 从文件中读出数据
         */ 
        public function readFromFile() { 
            if (!is_file($this->save_file)) 
                $this->writeToFile(); 
            $this->application = unserialize(file_get_contents($this->save_file)); 
        } 

        /** 
         * 写序列化后的数据到文件 
         */ 
        protected function writeToFile() { 
            $fp = fopen($this->save_file,"w"); 
            flock($fp , LOCK_EX);
            fwrite($fp, serialize($this->application)); 
            flock($fp , LOCK_UN);  
            fclose($fp); 
        } 
    } 
?>
