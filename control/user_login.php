<?php
	/**
	 * @filename doreg.php 处理用户登陆
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);


    if (Token::check(Validation::get('token'))) {
			
			$validate = new Validation();
			$validate->check(array(
				'username' => array(
					'required'	=> true
				),
				'password' => array(
					'required'	=> true
				)
			));
			
			if ($validate->getPassed()) {
				$user = new UserModel();
				if (Validation::get('remember')) {
					$islogin = $user->login(Validation::get('username'), Validation::get('password'), true);
				} else {
					$islogin = $user->login(Validation::get('username'), Validation::get('password'));
				}
				
				if ($islogin) {
                    $log->info(Validation::get('username') . " login access", __LINE__);
                    Session::create(Config::get('session/sucess'), 'loginsucess');
					Redirect::go('../');
				} else {
                    $log->warn(Validation::get('username') . " login fail", __LINE__);
                    Session::create(Config::get('session/error'), 'loginfailed');
                    Session::create(Config::get('session/userinfo'), serialize(array(
                        'username'	=> Validation::get('username'),
                        'name'		=> Validation::get('username'),
                    )));
					Redirect::go('../view/login.php');
				}
				
			} else {
                Session::create(Config::get('session/error'), $validate->getErrors());
                Session::create(Config::get('session/userinfo'), serialize(array(
                    'username'	=> Validation::get('username'),
                    'name'		=> Validation::get('username'),
                )));
                Redirect::go('../view/login.php');
			}
		} else {
            $log->error("There is no right to submit the form", __LINE__);
            Session::create(Config::get('session/error'), 'formerror');
            Redirect::go('../view/login.php');
		}		
?>
