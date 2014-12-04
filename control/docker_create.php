<?php
	/**
	 * @filename docker_create.php 已经登陆的用户创建一个docker
	 * @author 李纲彬
	 * @createTime 2014年11月18日
	 */
    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);

    $user = new UserModel();
    $name = $_GET['name'];
    $server = '';

    
    if($user->getLog() && !empty($name)) {
        $userinfo = $user->getData();
        $dockerctl = DockerCtlIns::get();
        $docker = new DockerModel();
        $dockername = Hash::salt();
        $port = Port::get();

        $server = $dockerctl->createDocker($dockername, $port);
        sleep(2);//waiting for docker start

        if(empty($server)) {
            Port::release($port);
        } else {
            $docker->add(array('userid' => $userinfo['id'],
                            'id' => $dockername,
                            'name' => $name,
                            'port' => $port,
                            'created'	=> date('Y-m-d H:i:s')));
        }
    }

    if(empty($name)) {
        Session::create(Config::get('session/error'), 'namenotnull');
        Redirect::go('../view/');
    } else if(empty($server)) {
        Session::create(Config::get('session/error'), 'createfailed');
        Redirect::go('../view/');
    } else {
        Session::create(Config::get('session/port'), $port);
        Session::create(Config::get('session/server'), $server);
        Redirect::go('../view/ssh.php');
    }
?>
