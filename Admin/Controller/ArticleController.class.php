<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\ArticleModel;
use \Admin\Model\CategoryModel;
//定义最终的文章控制器，并继承基础控制器
final class ArticleController extends BaseController
{
	//显示文章列表
	public function index()
	{
		//(1)获取无限级文章分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAll()
		);

		//(2)构建搜索条件
		$where = "2>1";
		if(!empty($_REQUEST['category_id'])) $where .= " AND category_id=".$_REQUEST['category_id'];
		if(!empty($_REQUEST['keyword'])) $where .= " AND title like '%".$_REQUEST['keyword']."%'";

		//(3)构建分页参数
		$pagesize	= 5;	//每页显示条数
		$page		= isset($_GET['page']) ? $_GET['page'] : 1; //当前页
		$startrow	= ($page-1)*$pagesize; //开始行号
		$records	= ArticleModel::getInstance()->rowCount($where);
		$params		= array('c'=>CONTROLLER,'a'=>ACTION);
		if(!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id'];
		if(!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];

		//(4)获取连表查询的分页数据
		$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);

		//(5)获取分页字符串
		$pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
		$pageStr = $pageObj->showPage();

		//(6)向视图赋值，调用视图显示
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->assign("articles",$articles);
		$this->smarty->assign("pageStr",$pageStr);
		$this->smarty->display("Article/index.html");
	}

	//显示添加的表单
	public function add()
	{
		//获取无限级分类的数据
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAll()
		);
		//向视图赋值，并显示视图
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("Article/add.html");
	}

	//插入表单数据
	public function insert()
	{
		//获取表单提交值
		$data['category_id']	= $_POST['category_id'];
		$data['user_id']		= $_SESSION['uid'];
		$data['title']			= $_POST['title'];
		$data['orderby']		= $_POST['orderby'];
		$data['top']			= isset($_POST['top']) ? 1 : 0;
		$data['content']		= $_POST['content'];
		$data['addate']			= time();
		
		//判断数据是否写入
		if(ArticleModel::getInstance()->insert($data))
		{
			$this->jump("文章添加成功！","?c=Article");
		}else
		{
			$this->jump("文章添加失败！","?c=Article");
		}
	}
}