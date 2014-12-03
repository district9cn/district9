<?php
	/**
	 * @filename doreg.php 处理用户修改密码
	 * @author 李纲彬
	 * @createTime 2014年11月11日
	 */

    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);

    if (Token::check(Validation::get('token'))) {
        $validate = new Validation();
                
        $validate->check(array(
                'password' => array(
                    'required' 	=> true,
                    'text'      => 'oldpass'
                ),
                'newpassword' => array(
                    'required' 	=> true,
                    'min'		=> 6,
                    'max'		=> 20,
                    'notequal'  => 'password',
                    'text'      => 'newpass'
                ),
                'passwordConfirm' => array(
                    'required' 	=> true,
                    'equal' 	=> 'newpassword',
                    'text'      => 'passcfm'
                ))
            );
        
        if ($validate->getPassed()) {
            $user = new UserModel();
            $userinfo = $user->getData();
            $salt = $userinfo['salt'];
            if ($userinfo['userpass'] == Hash::create(Validation::get('password'), $salt)) {
                $newsalt = Hash::salt();
                if ($user->chpass(Hash::create(Validation::get('newpassword'), $newsalt), $newsalt)) {
                    $log->info($userinfo['username'] . " change password access", __LINE__);
                    Session::create(Config::get('session/sucess'), 'chpasssucess');
                    Redirect::go('../view/');
                } else {
                    Session::create(Config::get('session/error'), 'chpassfailed');
                    Redirect::go('../view/chpass.php');
                }
            } else {
                Session::create(Config::get('session/error'), 'passerror');
                Redirect::go('../view/chpass.php');
            }
            
        } else {
            Session::create(Config::get('session/error'), $validate->getErrors());
            Redirect::go('../view/chpass.php');
        }
    } else {
            $log->error("There is no right to submit the form", __LINE__);
            Session::create(Config::get('session/error'), 'formerror');
            Redirect::go('../view/chpass.php');
    }
?>
