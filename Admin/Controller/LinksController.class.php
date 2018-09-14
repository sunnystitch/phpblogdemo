<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\LinksModel;
//定义最终的友情链接控制器，并继承基础控制器
final class LinksController extends BaseController
{
	//显示列表
	public function index()
	{
		//获取多行数据
		$links = LinksModel::getInstance()->fetchAll();
		//向视图赋值，并显示视图
		$this->smarty->assign("links",$links);
		$this->smarty->display("Links/index.html");
	}

	//显示添加的表单
	public function add()
	{
		$this->smarty->display("Links/add.html");
	}

	//插入表单数据
	public function insert()
	{
		//获取表单提交值
		$data['domain']		= $_POST['domain'];
		$data['url']		= $_POST['url'];
		$data['orderby']	= $_POST['orderby'];
		//判断数据是否写入
		if(LinksModel::getInstance()->insert($data))
		{
			$this->jump("友情链接添加成功！","?c=Links");
		}else
		{
			$this->jump("友情链接添加失败！","?c=Links");
		}
	}
}