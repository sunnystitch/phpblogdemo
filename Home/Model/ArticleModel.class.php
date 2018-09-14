<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;
//定义最终的文章模型类，并继承基础模型类
final class ArticleModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "article";

	//获取文章按月份归档数据
	public function fetchAllWithCount()
	{
		//构建查询的SQL语句
		$sql = "SELECT date_format(from_unixtime(addate),'%Y年%m月') AS month,";
		$sql .= "count(id) AS article_count FROM {$this->table} ";
		$sql .= "GROUP BY month ORDER BY month DESC";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}

	//获取文章连表查询的分页数据
	public function fetchAllWithJoin($where='2>1',$startrow=0,$pagesize=10)
	{
		//构建查询的SQL语句
		$sql = "SELECT article.*,user.name,category.classname FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY article.id DESC ";
		$sql .= "LIMIT {$startrow},{$pagesize}";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}

	//获取一行连表查询的数据
	public function fetchOneWithJoin($where="2>1")
	{
		//构建查询的SQL语句
		$sql = "SELECT article.*,user.name,category.classname FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "WHERE {$where}";
		//执行SQL语句，并返回结果(一维数组)
		return $this->pdo->fetchOne($sql);
	}

	//更新记录数
	public function updateRead($id)
	{
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET `read` = `read`+1 WHERE id={$id}";
		//执行SQL语句，并返回结果(布尔值)
		return $this->pdo->exec($sql);
	}

	//更新点赞数
	public function updatePraise($id)
	{
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET `praise` = `praise`+1 WHERE id={$id}";
		//执行SQL语句，并返回结果(布尔值)
		return $this->pdo->exec($sql);
	}

}