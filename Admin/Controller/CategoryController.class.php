<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CategoryModel;
//定义最终的分类控制器，并继承基础控制器
final class CategoryController extends BaseController
{
	//显示分类列表
	public function index()
	{
		//(1)创建模型类对象
		$modelObj = CategoryModel::getInstance();
		//(2)获取原始的分类数据
		$categorys = $modelObj->fetchAll();
		//(3)获取无限级分类数据：将原始数据加工处理
		$categorys = $modelObj->categoryList($categorys);
		//(4)向视图赋值，并显示视图
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("Category/index.html");
	}

	//显示添加的表单
	public function add()
	{
		//创建型类对象
		$modelObj = CategoryModel::getInstance();
		//获取分类的原始数据
		$categorys = $modelObj->fetchAll();
		//获取分类的无限级数据
		$categorys = $modelObj->categoryList($categorys);
		//向视图赋值，并显示视图
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("Category/add.html");
	}

	//插入表单数据
	public function insert()
	{
		//获取表单提交值
		$data['classname']	= $_POST['classname'];
		$data['orderby']	= $_POST['orderby'];
		$data['pid']		= $_POST['pid'];
		//判断数据是否写入
		if(CategoryModel::getInstance()->insert($data))
		{
			$this->jump("分类数据插入成功！","?c=Category&a=index");
		}else
		{
			$this->jump("分类数据插入失败！","?c=Category&a=index");
		}
	}

	//修改分类数据
	public function edit()
	{
		//获取地址栏传递的id
		$id = $_GET['id'];
		//创建模型类对象
		$modelObj = CategoryModel::getInstance();
		//获取指定ID的分类数据
		$arr = $modelObj->fetchOne("id=$id");
		//获取分类的原始数据
		$categorys = $modelObj->fetchAll();
		//获取分类的无限级数据
		$categorys = $modelObj->categoryList($categorys);
		//向视图赋值，并显示视图
		$this->smarty->assign("arr",$arr);
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("Category/edit.html");
	}

	//更新分类数据
	public function update()
	{
		//获取表单提交值
		$id = $_POST['id'];
		$data['classname']	= $_POST['classname'];
		$data['orderby']	= $_POST['orderby'];
		$data['pid']		= $_POST['pid'];
		//判断数据是否更新成功
		if(CategoryModel::getInstance()->update($data,$id))
		{
			$this->jump("分类数据更新成功！","?c=Category&a=index");
		}else
		{
			$this->jump("分类数据更新失败！","?c=Category&a=index");
		}
	}

}