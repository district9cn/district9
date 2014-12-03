<?php
	/**
	 * @filename CookieModel.php Cookie数据库操作类 实现对cookie表进行相关操作
	 * @author 李纲彬
	 * @createTime 2014年11月17日
	 */

	class CookieModel {
		
		//成员属性
		protected $db,
                  $table = 'cookies',
				  $data;
                    /*对应表名cookies, 字段包括id, userid, hash, created
                        id:自动增长，非空，主键
                        userid:关联users表id
                        hash:cookie的hash值
                        created:创建时间
                    */
		
		/**
		 * 构造方法
		 */
		public function __construct() {
			$this->db = DbModle::getInstance();
		}
		
		/**
		 * 添加Cookie的方法
		 */
		public function add($data = array()) {
			if ($this->db->insert($this->table, $data)) {
				return true;
			}
			return false;
		}
        
		/**
		 * 删除Cookie的方法
		 */
		public function del($value) {
			$vol = is_numeric($value) ? 'userid' : 'hash';
			if ($this->db->delete($this->table, array($vol, '=', $value))) {
				return true;
			}
			return false;
		}
        
		
		/**
		 * 查询Cookie数据的方法
		 */
		public function find($value) {
			$vol = is_numeric($value) ? 'userid' : 'hash';
			if ($this->db->getRows($this->table, array($vol, '=', $value))->getCount() > 0) {
                $re = $this->db->getResults();
				$this->data = $re[0];
				return true;
			}
			return false;
		}
		
		/**
		 * 得到Cookie信息的方法
		 */
		public function getData() {
			return $this->data;
		}
	}
?>
