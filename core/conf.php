<?php
	/**
	 * @filename conf.php 用于配置数据库、COOKIE和SESSION的超全局数组
	 * @author 李纲彬
	 * @createTime 2014年11月10日
	 */

	$GLOBALS['config'] = array(
		'mysql' => array(
			'host' 		=> 'localhost',
			'username'	=> 'root',
			'password' 	=> 'mdsmds',
			'dbname'	=> 'district9'
		),

		'cookie' => array(
			'hash' => 'hash',
			'expire' => 7*24*60*60
		),

		'session' => array(
			'userid'=> 'userid',
			'userinfo'=> 'userinfo',
			'token'	=> 'token',
            'error' => 'error',
            'sucess'=> 'sucess',
            'server' => 'server',
            'docker' => 'docker',
            'newdocker' => 'newdocker',
            'port' => 'port'
		),

		'ssh' => array(
			'host' 		=> '192.168.1.42',
            'method'    => 'password',   #password/key
			'username'	=> 'root',
			'password' 	=> 'mdsmds123',
			'port'  	=> '22',
			'rsa_key' 		=> '/home/bin117/workspace/docker/etc/ssh/id_rsa',
			'rsa_pub' 		=> '/home/bin117/workspace/docker/etc/ssh/id_rsa.pub'
		),

		'fleetctl' => array(
            'image' => 'ubuntussh',        #owncloud / ubuntussh
            'owncloud' => array (
                'postgres_example' 		=> 'postgres_example',
                'postgres_name'	=> 'postgres_name',
                'owncloud_example'	=> 'owncloud_example',
                'owncloud_name'	=> 'owncloud_name',
                'owncloud_port'	=> 'owncloud_port'
            ),
            'ubuntussh' => array(
                'ubuntussh_example'	=> 'ubuntussh_example',
                'ubuntussh_name'	=> 'ubuntussh_name',
                'ubuntussh_port'	=> 'ubuntussh_port'
            )
		),

        'dockerctl' => array(
            'image' => '350f7e75798a',
            'server' => '192.168.1.42'
        ),

        'docker' => array(
            'port' => array(
                'min' 	=> 1024,
                'max'   => 65535,
                'except'  	=> array(4001, 7001)
            ),
            'username' => 'root',
            'password' => '123456',
            'dockerctl' => 'dockerctl'         #dockerctl/fleetctl/kubernetes
        ),

        'sucess' => array(
            'loginsucess' => '登陆成功!',
            'deletesucess' => '删除成功!',
            'chpasssucess' => '更新密码成功！',
            'logoutsucess' => '登出成功！',
            'regsucess' => '用户注册成功！',
        ),

        'error' => array(
            'namenotnull' => '名称不能为空!',
            'createfailed' => '创建失败!',
            'deletefailed' => '删除失败!',
            'lunchfailed' => '启动失败！',
            'chpassfailed' => '更新密码失败！',
            'passerror' => '原密码不正确！',
            'formerror' => '请正确提交表单！',
            'loginfailed' => '登陆失败，请检查用户名或密码！',
            'username' => '用户名',
            'pass'      => '密码',
            'oldpass'   => '原密码',
            'newpass'   => '新密码',
            'passcfm'   => '确认密码',
            'mail'      => '邮箱',
            'notmatch' => '不符合要求!',
            'exist' => '已经被注册!',
            'notnull'   => '不能为空！',
            'notequal'   => '与%s不相同！',
            'equal'   => '与%s相同！',
            'lessthan'  => '长度小于%s位！',
            'morethan'  => '长度大于%s位！',
        )
	);
?>
