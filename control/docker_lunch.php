<?php
	/**
	 * @filename docker_lunch.php 运行一个docker.如果是未登陆用户，则在session获取dockerid，获取不到则新建
	 * @author 李纲彬
	 * @createTime 2014年11月13日
	 */
    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);
    $user = new UserModel();
    $dockerctl = DockerCtlIns::get();
    $server = '';
    $port;

    if($user->getLog()) {
        $dockername = isset($_GET['id']) ? $_GET['id'] : '';
        if(!empty($dockername)) {
            $server = $dockerctl->getServer($dockername);
            $port = $dockerctl->getPort($dockername);
        } else {
            Session::create(Config::get('session/error'), 'lunchfailed');
            Redirect::go('../view/');
        }
    } else {
        $dockername = Session::get(Config::get('session/newdocker'));
        $server = Session::get(Config::get('session/server'));
        $port = Session::get(Config::get('session/port'));

        if(empty($dockername)) {
            $dockername = Hash::salt();
            $port = Port::get();

            $server = $dockerctl->createDocker($dockername, $port);
            sleep(2); // waiting for docker lunch

            if(empty($server)) {
                Port::release($port);
            }
        }
    }

    if(empty($server)) {
        Session::create(Config::get('session/error'), 'lunchfailed');
        Redirect::go('../view/');
    } else {
        if($user->getLog()) {
            Session::create(Config::get('session/docker'), $dockername);
        } else {
            Session::create(Config::get('session/newdocker'), $dockername);
        }
        Session::create(Config::get('session/port'), $port);
        Session::create(Config::get('session/server'), $server);
        Redirect::go('../view/ssh.php');
    }
?>
