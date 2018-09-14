<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;
//定义最终的分类模型类，并继承基础模型类
final class CategoryModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "category";

	//获取连表查询的数据
	public function fetchAllWithJoin()
	{
		//构建连表查询的SQL语句
		$sql = "SELECT category.*,count(article.id) AS article_count FROM {$this->table} ";
		$sql .= "LEFT JOIN article ON category.id=article.category_id ";
		$sql .= "GROUP BY category.id";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}

	//获取无限级分类的数据
	public function categoryList($arrs,$level=0,$pid=0)
	{
		//静态的变量数组，来保存结果数据
		static $categorys = array();

		//循环原始的分类数组
		foreach($arrs as $arr)
		{
			//如果原始数组中的$pid和传递过来的id相等
			//就把该符合条件的菜单，添加目标数组中
			if($arr['pid']==$pid)
			{
				$arr['level'] = $level;
				$categorys[] = $arr;
				//递归调用
				$this->categoryList($arrs,$level+1,$arr['id']);
			}
		}
		//返回结果
		return $categorys;
	}
}