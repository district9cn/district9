<?php
	/**
	 * @filename DockerModel.php Docker数据库操作类 实现对Docker表进行相关操作
	 * @author 李纲彬
	 * @createTime 2014年11月17日
	 */

	class DockerModel {
		
		//成员属性
		protected $db,
                  $table = 'dockers',
                  $data; 
                    /*对应表名dockers 字段包括id, userid, name, created
                        id:自动增长，非空，主键
                        userid:关联users表id
                        name:docker的名字，使用创建时sessionid
                        created:创建时间
                    */
		
		/**
		 * 构造方法
		 */
		public function __construct() {
			$this->db = DbModle::getInstance();
		}
		
		/**
		 * 添加Docker的方法
		 */
		public function add($data = array()) {
			if ($this->db->insert($this->table, $data)) {
				return true;
			}
			return false;
		}
        
		/**
		 * 删除Docker的方法
		 */
		public function del($value) {
			if ($this->db->delete($this->table, array('id', '=', $value))) {
				return true;
			}
			return false;
		}
        
		
		/**
		 * 查询Docker数据的方法
		 */
		public function find($value) {
			$vol = is_numeric($value) ? 'userid' : 'id';
			if ($this->db->getRows($this->table, array($vol, '=', $value))->getCount() > 0) {
                $re = $this->db->getResults();
				$this->data = $re;
				return true;
			}
			return false;
		}
		
		/**
		 * 更新Docker数据的方法
		 */
		public function update($fields = array(), $where = array()) {
            return $this->db->update($this->table, $fields, $where);
		}
		
		/**
		 * 得到Docker信息的方法
		 */
		public function getData() {
			return $this->data;
		}
	}
?>
