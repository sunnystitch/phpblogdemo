<?php
//声明命名空间
namespace Frame;
//定义最终的框架初始类
final class Frame
{
	//公共的静态的框架初始化方法
	public static function run()
	{
		self::initCharset();	//字符集设置
		self::initConfig();		//初始化配置文件
		self::initRoute();		//获取路由参数
		self::initConst();		//常量定义
		self::initAutoLoad();	//类的自动加载
		self::initDispatch();	//请求分发
	}

	//私有的静态的字符集设置方法
	private static function initCharset()
	{
		header("content-type:text/html;charset=utf-8");
		session_start();//开启SESSION会话
	}

	//私有的静态的读取配置文件方法
	private static function initConfig()
	{
		$GLOBALS['config'] = require_once(APP_PATH."Conf".DS."Config.php");
	}

	//私有的静态的获取地址栏路由参数方法
	private static function initRoute()
	{
		$p = $GLOBALS['config']['default_platform'];	//平台参数
		$c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller']; //控制器参数
		$a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action']; //用户动作参数
		define("PLAT",$p);
		define("CONTROLLER",$c);
		define("ACTION",$a);
	}

	//私有的静态的常量定义
	private static function initConst()
	{
		define("FRAME_PATH",ROOT_PATH.DS."Frame".DS); //Frame目录
		define("VIEW_PATH",APP_PATH."View".DS); //View目录
	}

	//私有的静态的类的自动加载
	private static function initAutoLoad()
	{
		spl_autoload_register(function($className){
			//当前的类名参数是：\Home\Controller\StudentController
			//真实的类文件路径：./Home/Controller/StudentController.class.php
			//解决办法：将空间类名，转成真实的类文件路径
			$filename = ROOT_PATH.DS.str_replace("\\",DS,$className).".class.php";
			//如果类文件存在，则直接包含
			if(file_exists($filename))	require_once($filename);
		});
	}
	
	//私有的静态的请求分发方法：创建哪个控制器对象，调用哪个控制器对象的方法？
	private static function initDispatch()
	{
		//构建控制器类名称，例如：\Home\Controller\IndexController
		$controllerClassName = "\\".PLAT."\\"."Controller"."\\".CONTROLLER."Controller";
		//创建控制器类的对象
		$controllerObj = new $controllerClassName();
		//构建控制器对象的方法名称
		$actionName = ACTION;
		//调用控制器对象的方法
		$controllerObj->$actionName();
	}
}