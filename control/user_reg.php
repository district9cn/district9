<?php
	/**
	 * @filename doreg.php 处理用户注册
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);

    if (Token::check(Validation::get('token'))) {
        $validate = new Validation();
                
        $validate->check(array(
                'username' => array(
                    'required' 	=> true,
                    'min'		=> 3,
                    'max'		=> 20,
                    'unique'	=> true,
                    'text'      => 'username'
                ),
                'password' => array(
                    'required' 	=> true,
                    'min'		=> 6,
                    'max'		=> 20,
                    'text'      => 'pass'
                ),
                'passwordConfirm' => array(
                    'required' 	=> true,
                    'equal' 	=> 'password',
                    'text'      => 'passcfm'
                ),
                'mail' => array(
                    'required' 	=> true,
                    'matched'   => '/^[a-zA-Z0-9][a-zA-Z0-9._-]*\@[a-zA-Z0-9]+\.[a-zA-Z0-9\.]+$/A',
                    'text'      => 'mail'
                ))
            );
        
        if ($validate->getPassed()) {
            $user = new UserModel();
            $salt = Hash::salt();
            
            if ($user->add(array(
                'username'	=> Validation::get('username'),
                'userpass'	=> Hash::create(Validation::get('password'), $salt),
                'salt'		=> $salt,
                'name'		=> Validation::get('username'),
                'mail'      => Validation::get('mail'),
                'joined'	=> date('Y-m-d H:i:s'),
                'groupid'	=> '1'
            ))) {
                $log->info(Validation::get('username') . " register access", __LINE__);
                Session::create(Config::get('session/sucess'), 'regsucess');
                Redirect::go('../');
            }
        } else {
            Session::create(Config::get('session/error'), $validate->getErrors());
            Session::create(Config::get('session/userinfo'), serialize(array(
                'username'	=> Validation::get('username'),
                'name'		=> Validation::get('username'),
                'mail'      => Validation::get('mail'),
            )));
            Redirect::go('../view/register.php');
        }
    } else {
            $log->error("There is no right to submit the form", __LINE__);
            Session::create(Config::get('session/error'), 'formerror');
            Redirect::go('../view/register.php');
    }
?>
