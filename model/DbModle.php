<?php
	/**
	 * @filename DbModel.php 数据库操作类文件
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

    require_once('../core/init.php');

	class DbModle {
		
		protected $link,
				  $error = false,
				  $count,
				  $results = array();
		protected static $instance;
		
		/**
		 * 构造方法 连接数据库
		 */
		protected function __construct() {
            $this->log = Plog::factory(__FILE__);
			$this->link = mysql_connect(Config::get('mysql/host'),
						  Config::get('mysql/username'),
						  Config::get('mysql/password'))
						  or die('连接数据库失败！');
			
			$select = mysql_select_db(Config::get('mysql/dbname')) or die('选择数据库失败！');
			
			mysql_query('SET NAMES UTF8');
		}
		
		/**
		 * 静态方法来连接数据库并得到类的对象 静态方法只能访问静态的属性
		 */
		public static function getInstance() {
			if (!isset(self::$instance)) {
				self::$instance = new DbModle();
			}
			return self::$instance;
		}
		
		/**
		 * 原生的数据库查询方法
		 */
		public function query($sql) {
            $this->error = false;
			if (!empty($sql)) {
                $this->log->debug($sql, __LINE__);
				$re = mysql_query($sql);
				if ($re) {
					if ($re !== true) {
						$this->count = mysql_num_rows($re);

                        $results = null;
						while ($row = mysql_fetch_assoc($re)) {
							$results[] = $row;					
						}
						$this->results = $results;
					}
					return $this;
				}
			}
			$this->error = true;
			return $this;
		}
		
		/**
		 * 定义一个执行具体的操作的方法（查询和删除）
		 */
		public function action($action, $table, $where = array()) {
			
			if (count($where) == 3) {
				$operators = array('>', '<', '>=', '<=', '=', '!=', '<>', 'like');
				
				$field = $where[0];
				$operator = $where[1];
				$value = $where[2];
				
				if (in_array($operator, $operators)) {
					
					$sql = "{$action} FROM {$table} WHERE {$field} {$operator} '{$value}'";
					
					return $this->query($sql);
				}
			}
		}
		
		/**
		 * 查询表中某些得数据的方法
		 */
		public function getRows($table, $where) {
            return $this->action('SELECT *', $table, $where);
		}
		
		/**
		 * 删除数据方法
		 */
		public function delete($table, $where = array()) {
			if ($this->action('DELETE', $table, $where)->getError()) {
				return false;
			} else {
				return true;
			}
		}
		
		/**
		 * 数据插入方法
		 */
		public function insert($table, $fields = array()) {
			$i = 1;
            $keyStr = '';
            $valueStr = '';

			foreach ($fields as $keys=>$values) {
				
				if ($i < count($fields)) {
					$keyStr .= $keys . ',';
					$valueStr .= "'" . $values . "',";
				} else {
					$keyStr .= $keys;
					$valueStr .= "'" . $values . "'";
				}
				$i++;			
			}
			
			$sql = "INSERT INTO {$table}({$keyStr}) VALUES({$valueStr})";
            $this->log->debug($sql, __LINE__);
			
			if ($this->query($sql)->getError()) {
				return false;
			} 
			return true;
		}
		
		/**
		 * 更新数据方法
		 */
		public function update($table, $fields = array(), $where = array()) {
			$i = 1;
            $set = '';

			foreach ($fields as $keys=>$values) {
				if ($i < count($fields)) {
					$set .= $keys . "='" . $values . "',";
				} else {
					$set .= $keys . "='" . $values . "'";
				}
				$i++;
			}
				
			$sql = "UPDATE {$table} SET {$set} WHERE {$where[0]} {$where[1]} '{$where[2]}'";
            $this->log->debug($sql, __LINE__);
				
			if ($this->query($sql)->getError()) {
				return false;
			}
			return true;
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
		
		/**
		 * 得到查询行数
		 */
		public function getCount() {
			return $this->count;
		}
	}
?>
