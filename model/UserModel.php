<?php
	/**
	 * @filename UserModel.php 用户操作类 实现对用户进行相关操作
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */
    require_once('CookieModel.php');

	class UserModel {
		
		//成员属性
		protected $db,
                  $cookie,
                  $table = 'users',
				  $data, 
                    /*对应表名users, 字段包括
                        id:自动增长，非空，主键
                        username:用户帐号
                        userpass:用户密码
                        salt:密码使用加盐加密，加密的盐
                        name:用户名称未使用
                        mail:邮箱
                        joined:加入时间
                        groupid:组id
                    */
				  $isLogIn = false;
		
		/**
		 * 构造方法
		 */
		public function __construct() {
			$this->db = DbModle::getInstance();
            $this->cookie = new CookieModel();
			
			if (Session::exists(Config::get('session/userid'))) {
				if ($this->find(Session::get(Config::get('session/userid')))) {
					$this->isLogIn = true;
				} else {
					$this->isLogIn = false;
				}
			} else {
				if (Cookie::exists(Config::get('cookie/hash'))) {
					if ($this->cookie->find(Cookie::get(Config::get('cookie/hash')))) {
						$cookies = $this->cookie->getData();
                        if (strtotime($cookies['created']) + Config::get('cookie/expire') > time()) {
						    $this->find($cookies['userid']);
						    Session::create(Config::get('session/userid'), $cookies['userid']);
						    $this->isLogIn = true;
                        } else {
			                $this->cookie->del(Cookie::get(Config::get('cookie/hash')));
						    $this->isLogIn = false;
                        }
					} else {
						$this->isLogIn = false;
					}
				}
			}
		}
		
		/**
		 * 添加用户的方法
		 */
		public function add($data = array()) {
			if ($this->db->insert($this->table, $data)) {
				return true;
			}
			return false;
		}
		
		/**
		 * 用户登录方法
		 */
		public function login($username = '', $userpass = '', $remember = false) {
			if (!empty($username)) {
				if ($this->find($username)) {
					if ($this->data['userpass'] == Hash::create($userpass, $this->data['salt'])) {
						if ($remember) {
							if ($this->cookie->find($this->data['id'])) {
								$cookies = $this->cookie->getData();
								$hash = $cookies['hash'];
							} else {
								$hash = Hash::unique();
                                $this->cookie->add(array(
                                    'userid'=>$this->data['id'], 
                                    'hash'=>$hash,
                                    'created'	=> date('Y-m-d H:i:s')));
							}
							Cookie::create(Config::get('cookie/hash'), $hash);
						}
						
						Session::create(Config::get('session/userid'), $this->data['id']);
						$this->isLogIn = true;

						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		/**
		 * 查询用户数据的方法
		 */
		public function find($value) {
			
			$vol = is_numeric($value) ? 'id' : 'username';
			if ($this->db->action('SELECT *', $this->table, array($vol, '=', $value))) {
                $re = $this->db->getResults();
				$this->data = $re[0];
				return true;
			}
			return false;
		}
		
		/**
		 * 修改用户密码
		 */
		public function chpass($newpass, $salt) {
			
			if ($this->db->update($this->table, array('userpass' => $newpass, 'salt' => $salt), array('id', '=', $this->data['id']))) {
                $this->data['userpass'] = $newpass;
				return true;
			}
			return false;
		}
		
		/**
		 * 得到用户登录状态的方法
		 */
		public function getLog() {
			return $this->isLogIn;
		}
		
		/**
		 * 得到用户信息的方法
		 */
		public function getData() {
			return $this->data;
		}
		
		/**
		 * 实现用户退出的方法
		 */
		public function logOut() {
			Cookie::delete(Config::get('cookie/hash'));
			
			$this->cookie->del((int)Session::get(Config::get('session/userid')));
			
			Session::delete(Config::get('session/userid'));
		}
	}
?>
