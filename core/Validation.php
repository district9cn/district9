<?php
	/**
	 * @filename Validation.php 用户数据验证类
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

	class Validation {
		
		//成员属性
		protected $errors = array(),
				  $passed = false;
		
        /*
         * 定义判断表单是否提交了的方法
         */
		public static function isSubmit($method) {
			switch ($method) {
				case 'post':
					return !empty($_POST);
					break;
				case 'get':
					return !empty($_GET);
					break;
				default:
					return false;
					break;
			}
		}
		
        /*
         * 得到表单提交数据的方法
         */
		public static function get($name) {
			if (isset($_POST[$name])) {
				return escape($_POST[$name]);
			} else if (isset($_GET[$name])) {
				return escape($_GET[$name]);
			} else {
				return false;
			}
		}

		/**
		 * 验证数据方法
		 */
		public function check($data = array()) {
			if (!empty($data)) {
				foreach ($data as $field=>$rules) {
					$value = Validation::get($field);
					
                    $text = isset($rules['text']) ? $rules['text'] : 'data';
					foreach ($rules as $rule=>$val) {
						switch ($rule) {
							case 'required':
								if ($value == '') {
									$this->errors = $text . '_notnull';
                                    return;
								}
                                break;
							case 'min':
								if ($value != '' && strlen($value) < $val) {
									$this->errors = $text . '_lessthan_' . $val ;
                                    return;
								}
                                break;
							case 'max':
								if ($value != '' && strlen($value) > $val) {
									$this->errors = $text . '_morethan_' . $val;
                                    return;
								}
                                break;
							case 'equal':
								if ($value != '' && $value != Validation::get($val)) {
									$this->errors = $text . '_notequal_' . $data[$val]['text'];
                                    return;
								}
                                break;
							case 'notequal':
								if ($value != '' && $value == Validation::get($val)) {
									$this->errors = $text . '_equal_' . $data[$val]['text'];
                                    return;
								}
                                break;
							case 'matched':
								if ($value != '') {
                                    preg_match($val,$value,$result);
                                    if (count($result) == 0) {
									    $this->errors = $text . '_notmatch';
                                        return;
                                    }
								}
                                break;
							case 'unique':
								if ($value != '') {
									if (DbModle::getInstance()->getRows('users', array('username', '=', $value))->getCount() > 0) {
										$this->errors = $text . '_exist';
                                        return;
									}
								}
                                break;
						}
					}
				}
			}
		}
		
        /*
         * 将错误标识转换为错误信息
         */
		public function toErrors($error) {
            $errmsg = '';
            $arr = explode('_', $error);
            $len = count($arr);

            if($len == 1) {
                $errmsg = Config::get('error/'.$error);
            } else if($len == 2) {
                $errmsg = Config::get('error/'.$arr[0]) . Config::get('error/'.$arr[1]);
            } else if($len == 3) {
                $errmsg = Config::get('error/'.$arr[0]);

                if($arr[1] == 'notequal' || $arr[1] == 'equal') {
                    $errmsg .= sprintf(Config::get('error/'.$arr[1]), Config::get('error/'.$arr[2]));
                } else {
                    $errmsg .= sprintf(Config::get('error/'.$arr[1]), $arr[2]);
                }
            }

            return $errmsg;
		}
		
        /*
         * 得到错误信息方法
         */
		public function getErrors() {
			return $this->errors;
		}
		
        /*
         * 得到用户是否通过的方法
         */
		public function getPassed() {
			if (empty($this->errors)) {
				$this->passed = true;
			}
			return $this->passed;
		}
	}
?>
