<?php
//定义常用的常量
define("DS",DIRECTORY_SEPARATOR);	//动态斜线，windows下为"\"，linux下为"/"
define("ROOT_PATH",getcwd());	//网站根目录
define("APP_PATH",ROOT_PATH.DS."Admin".DS); //前端应用目录
//包含框架的初始类文件
require_once(ROOT_PATH.DS."Frame".DS."Frame.class.php");
//调用框架初始化方法
\Frame\Frame::run();