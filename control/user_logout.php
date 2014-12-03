<?php
	/**
	 * @filename doreg.php 处理用户登出
	 * @author 李纲彬
	 * @createTime 2014年11月11日
	 */

    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);

    $user = new UserModel();

    $user->logOut();
    $userData = $user->getData();
    $log->info($userData['username'] . " logout access", __LINE__);
    session_destroy();
    session_start();
    Session::create(Config::get('session/sucess'), 'logoutsucess');
    Redirect::go('../view/');
?>
