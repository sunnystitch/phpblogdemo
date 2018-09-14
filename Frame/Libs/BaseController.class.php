<?php
//声明命名空间
namespace Frame\Libs;
//定义抽象的基础控制器类
abstract class BaseController
{
	//受保护的保存smarty对象的属性
	protected $smarty = NULL;

	//构造方法
	public function __construct()
	{
		$this->initSmarty();
	}

	//私有的创建Smarty类的对象
	private function initSmarty()
	{
		//创建Smarty类的对象
		$smarty = new \Frame\Vendor\Smarty();
		//Smarty对象的配置
		$smarty->left_delimiter = "<{";		//左定界符
		$smarty->right_delimiter = "}>";	//右定界符
		$smarty->setTemplateDir(VIEW_PATH); //设置视图工作目录，./Admin/View/
		$smarty->setCompileDir(sys_get_temp_dir().DS."view"); //如：c:/windows/temp/view/
		//向$smarty属性赋值
		$this->smarty = $smarty;
	}

	//跳转方法
	protected function jump($message,$url='?',$time=3)
	{
		$this->smarty->assign("message",$message);
		$this->smarty->assign("url",$url);
		$this->smarty->assign("time",$time);
		$this->smarty->display("Public/jump.html");
		die(); //中止脚本向下执行
	}

	//权限验证的方法
	protected function denyAccess()
	{
		//判断用户是否存在
		if(!isset($_SESSION['username']))
		{
			$this->jump("你必须先登录，才能进行其它操作！","?c=User&a=login");
		}
	}
}