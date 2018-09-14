<?php
//后端配置信息
return array(
	//数据库配置信息
	'db_type'	=> 'mysql',		//数据库类型
	'db_host'	=> 'localhost',	//主机名
	'db_port'	=> '3306',		//端口号
	'db_user'	=> 'root',		//用户名
	'db_pass'	=> 'root',		//密码
	'db_name'	=> 'blog',		//数据库名
	'charset'	=> 'utf8',		//字符集

	//默认路由参数
	'default_platform'		=> 'Admin',	//平台
	'default_controller'	=> 'Index',	//控制器
	'default_action'		=> 'index',	//动作(方法)
);