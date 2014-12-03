<?php  
	/**
	 * @filename docker_chname.php 已登陆的用户更改docker名称
	 * @author 李纲彬
	 * @createTime 2014年11月18日
	 */
    require_once('../core/init.php');
    $log = Plog::factory(__FILE__);

    $id = $_POST['id'];
    $name = $_POST['name'];

    
    $user = new UserModel();
    if($user->getLog() && !empty($name) && strlen($name) <= 32 && !empty($id)) {
        $docker = new DockerModel();
        if ($docker->update(array('name' => $name), array('id', '=', $id))) {
            echo json_encode(true);
        }
    }
    echo json_encode(false);
?>  
