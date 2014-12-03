<?php  
	/**
	 * @filename docker_add.php 用户在登陆之前启动过新的docker,登陆后提示是否加入docker.否则删除docker
	 * @author 李纲彬
	 * @createTime 2014年11月19日
	 */
    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);

    $id = $_POST['id'];
    $isAdd = $_POST['isAdd'];

    $user = new UserModel();
    if($user->getLog() && !empty($id)) {
        $dockername = Session::get(Config::get('session/newdocker'));
        $port = Session::get(Config::get('session/port'));
        $userinfo = $user->getData();

        if($dockername == $id) {
            if($isAdd == 'true') {
                $docker = new DockerModel();
                $docker->add(array('userid' => $userinfo['id'],
                            'id' => $dockername,
                            'name' => $dockername,
                            'port' => $port,
                            'created'	=> date('Y-m-d H:i:s')));
            } else {
                $fleetctl = FleetCtl::getInstance();
                $fleetctl->removeDocker($id);
            }

            Session::delete(Config::get('session/newdocker'));
        }
    }
?>  
