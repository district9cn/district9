<?php
	/**
	 * @filename DockerCtlIns.php 用于得到DockerCtl实例 
	 * @author 李纲彬
	 * @createTime 2014年12月4日
	 */
    	require_once('init.php');

	class DockerCtlIns {
		
        /*
         * 定义一个静态的得到配置信息的方法
         */
	public static function get() {
            $dockerctl = null;
            switch (Config::get('docker/dockerctl')) {
                case 'fleetctl':
                    $dockerctl = FleetCtl::getInstance();
                    break;
                case 'dockerctl':
                    $dockerctl = DockerCtl::getInstance();
                    break;
            default:
                    break;
            }
            return $dockerctl;
		}
	}
?>
