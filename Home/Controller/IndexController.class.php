<?php
//声明命名空间
namespace Home\Controller;
use \Frame\Libs\BaseController;
use \Home\Model\LinksModel;
use \Home\Model\CategoryModel;
use \Home\Model\ArticleModel;
//定义最终的首页控制器，并继承基础控制器
final class IndexController extends BaseController
{
	//首页显示方法
	public function index()
	{
		//(1)获取友情链接数据
		$links = LinksModel::getInstance()->fetchAll();

		//(2)获取无限级分类数据(连表查询)
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAllWithJoin()
		);

		//(3)获取文章按月份归档数据
		$months = ArticleModel::getInstance()->fetchAllWithCount();

		//(4)构建搜索条件
		$where = "2>1";
		if(!empty($_REQUEST['title'])) $where .= " AND title LIKE '%".$_REQUEST['title']."%'";
		if(!empty($_GET['category_id'])) $where .= " AND category_id=".$_GET['category_id'];

		//(5)构建分页的参数
		$pagesize	= 5;	//每页显示的条数
		$page		= isset($_GET['page']) ? $_GET['page'] : 1; //当前页码
		$startrow	= ($page-1)*$pagesize; //开始行号
		$records	= ArticleModel::getInstance()->rowCount($where); //记录数
		$params		= array('c'=>CONTROLLER,'a'=>ACTION);
		if(!empty($_REQUEST['title'])) $params['title'] = $_REQUEST['title'];
		if(!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
		

		//(6)获取文章连表查询的分页数据
		$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);

		//(7)创建分页类对象，并获取分页字符串
		$pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
		$pagestr = $pageObj->showPage();

		//(8)向视图赋值，并显示视图
		$this->smarty->assign(array(
			'links'		=> $links,
			'categorys'	=> $categorys,
			'months'	=> $months,
			'articles'	=> $articles,
			'pagestr'	=> $pagestr,
		));
		$this->smarty->display("Index/index.html");
	}

	//显示文章列表
	public function showList()
	{
		//(1)获取友情链接数据
		$links = LinksModel::getInstance()->fetchAll();

		//(2)获取无限级分类数据(连表查询)
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAllWithJoin()
		);

		//(3)获取文章按月份归档数据
		$months = ArticleModel::getInstance()->fetchAllWithCount();

		//(4)构建搜索条件
		$where = "2>1";
		if(!empty($_REQUEST['title'])) $where .= " AND title LIKE '%".$_REQUEST['title']."%'";
		if(!empty($_GET['category_id'])) $where .= " AND category_id=".$_GET['category_id'];

		//(5)构建分页的参数
		$pagesize	= 30;	//每页显示的条数
		$page		= isset($_GET['page']) ? $_GET['page'] : 1; //当前页码
		$startrow	= ($page-1)*$pagesize; //开始行号
		$records	= ArticleModel::getInstance()->rowCount($where); //记录数
		$params		= array('c'=>CONTROLLER,'a'=>ACTION);
		if(!empty($_REQUEST['title'])) $params['title'] = $_REQUEST['title'];
		if(!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
		
		//(6)获取文章连表查询的分页数据
		$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);

		//(7)创建分页类对象，并获取分页字符串
		$pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
		$pagestr = $pageObj->showPage();

		//(8)向视图赋值，并显示视图
		$this->smarty->assign(array(
			'links'		=> $links,
			'categorys'	=> $categorys,
			'months'	=> $months,
			'articles'	=> $articles,
			'pagestr'	=> $pagestr,
		));
		$this->smarty->display("Index/list.html");
	}

	//显示文章内容
	public function content()
	{
		//(1)获取友情链接数据
		$links = LinksModel::getInstance()->fetchAll();

		//(2)获取无限级分类数据(连表查询)
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAllWithJoin()
		);

		//(3)获取文章按月份归档数据
		$months = ArticleModel::getInstance()->fetchAllWithCount();
		
		//(4)更新文章阅读数
		$id = $_GET['id'];
		ArticleModel::getInstance()->updateRead($id);

		//(5)根据id读取文章连表查询的数据
		$article = ArticleModel::getInstance()->fetchOneWithJoin("article.id={$id}");

		//(6)获取前一篇和后一篇数据
		$prevNext[] = ArticleModel::getInstance()->fetchOne("id<$id","id desc"); //前一篇
		$prevNext[] = ArticleModel::getInstance()->fetchOne("id>$id","id asc"); //后一篇
		
		//(7)向视图赋赋值，并显示视图
		$this->smarty->assign(array(
			'links'		=> $links,
			'categorys'	=> $categorys,
			'months'	=> $months,
			'article'	=> $article,
			'prevNext'	=> $prevNext,
		));
		$this->smarty->display("Index/content.html");
	}

	//文章点赞
	public function praise()
	{
		//获取当前文章的ID
		$id = $_GET['id'];
		//判断当前用户是否存在
		if(isset($_SESSION['username']))
		{
			//判断当前文章是否点赞过
			if(empty($_SESSION['praise'][$id]))
			{
				ArticleModel::getInstance()->updatePraise($id);
				$_SESSION['praise'][$id] = true;
				$this->jump("id={$id}文章点赞完成！","?c=Index&a=content&id=$id");
			}else
			{
				$this->jump("id={$id}文章已经点赞过了，不能重复点赞！","?c=Index&a=content&id=$id");
			}
		}else
		{
			$this->jump("只有登录用户才可以点赞！","admin.php?c=User&a=login");
		}
	}
}