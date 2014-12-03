<?php
	/**
	 * @filename docker_del.php 已登陆的用户删除docker
	 * @author 李纲彬
	 * @createTime 2014年11月17日
	 */
    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);

    $user = new UserModel();
    $fleetctl = FleetCtl::getInstance();

    if($user->getLog()) {
        $docker = new DockerModel();
        $dockername = isset($_GET['id']) ? $_GET['id'] : '';
        if(!empty($dockername)) {
            $fleetctl->stopDocker($dockername);
            $fleetctl->removeDocker($dockername);
            $docker->del($dockername);

            Session::create(Config::get('session/sucess'), 'deletesucess');
            Redirect::go('../view/');
        } else {
            Session::create(Config::get('session/error'), 'deletefailed');
            Redirect::go('../view/');
        }
    }
?>
