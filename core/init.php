<?php
	/**
	 * @filename init.php 用于实现系统的初始配置
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */
    require_once('conf.php');

    //日志系统
    require_once('../plog/classes/plog.php');
    Plog::set_config(include '../plog/config.php');

	//定义一个加载类文件函数
	function __autoload($class) { 
        if (in_array($class, array('Config', 'Cookie',  'Hash', 'Redirect', 'Session',
                                    'Token', 'Validation', 'Ssh', 'FleetCtl', 'Port', 'Application'))) {
		    require_once $class . '.php';
        }

        if (in_array($class, array('DbModle', 'UserModel', 'DockerModel', 'SessionModel'))) {
		    require_once '../model/' . $class . '.php';
        }
	}
	
    //开启session
    /*
    if(!isset($_SESSION)) {
        session_start();
    }
     */
    $session = SessionModel::getInstance();

    //设置编码
    header('Content-Type:text/html; charset=utf-8');

    //设置时区
    if (function_exists('date_default_timezone_set')) {
        date_default_timezone_set('PRC');
    }

    // 防止一些低级的XSS
    if($_SERVER['REQUEST_URI']) {
        $temp = urldecode($_SERVER['REQUEST_URI']);
        if(strpos($temp, '<') !== false || strpos($temp, '>') !== false || strpos($temp, '(') !== false || strpos($temp, '"') !== false) {
            exit('Request Bad url');
        }
    }

    //数据过滤函数
  	function escape($string = '') {
        $db = DbModle::getInstance();
		if (!empty($string)) {
			return mysql_real_escape_string(htmlentities($string, ENT_QUOTES, 'utf-8'));
		}
		return false;
	}

?>
