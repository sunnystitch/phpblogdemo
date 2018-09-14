<?php
//声明命名空间
namespace Frame\Libs;
use \Frame\Vendor\PDOWrapper;
//定义抽象的基础模型类
abstract class BaseModel
{
	//受保护的PDO对象的属性
	protected $pdo = NULL;

	//公共的构造方法
	public function __construct()
	{
		$this->pdo = new PDOWrapper();
	}

	//私有的静态的存储不同模型类对象的数组
	private static $modelObjArr = array();

	//公共的静态的创建单例的模型类对象
	public static function getInstance()
	{
		//获取静态调用方式下的类名，或者静态延时绑定的类名
		$modelClassName = get_called_class();
		/*
			$modelObjArr['\Home\Model\StudentModel'] = 学生模型类对象
			$modelObjArr['\Home\Model\NewsModel']    = 新闻模型类对象
		*/
		//判断当前模型类对象是否存在
		if(empty(self::$modelObjArr[$modelClassName]))
		{
			//创建当前的模型类对象，并存入数组中
			self::$modelObjArr[$modelClassName] = new $modelClassName();
		}
		//返回当前模型类对象
		return self::$modelObjArr[$modelClassName];
	}

	//获取单行数据
	public function fetchOne($where="2>1",$orderby="id desc")
	{
		//构建查询的SQL语句
		$sql = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderby} LIMIT 1";
		//执行SQL语句，并返回结果(一维数组)
		return $this->pdo->fetchOne($sql);
	}

	//获取多行数据
	public function fetchAll()
	{
		//构建查询的SQL语句
		$sql = "SELECT * FROM {$this->table}";
		//执行SQL语句，并返回结果(二维数组)
		return $this->pdo->fetchAll($sql);
	}

	//插入数据
	public function insert($data)
	{
		//构建字段名列表和字段值列表的字符串
		$fields = "";
		$values = "";
		foreach($data as $key=>$value)
		{
			$fields .= "$key,";
			$values .= "'$value',";
		}
		//去除结尾的逗号
		$fields = rtrim($fields,",");
		$values = rtrim($values,",");

		//构建插入的SQL语句
		$sql = "INSERT INTO {$this->table}($fields) VALUES($values)";
		//执行SQL语句，并返回结果(布尔值)
		return $this->pdo->exec($sql);		
	}

	//更新用户数据
	public function update($data,$id)
	{
		//构建"字段名=字段新值"字符串列表
		$str = "";
		foreach($data as $key=>$value)
		{
			$str .= "$key='$value',";
		}
		//去除结尾的逗号
		$str = rtrim($str,",");

		//构建插入的SQL语句
		$sql = "UPDATE {$this->table} SET {$str} WHERE id={$id}";
		//执行SQL语句，并返回结果(布尔值)
		return $this->pdo->exec($sql);		
	}

	//删除数据
	public function delete($id)
	{
		//构建删除的SQL语句
		$sql = "DELETE FROM {$this->table} WHERE id={$id}";
		//执行SQL语句，并返回结果(布尔值)
		return $this->pdo->exec($sql);
	}

	//获取记录数
	public function rowCount($where="2>1")
	{
		//构建查询的SQL语句
		$sql = "SELECT * FROM {$this->table} WHERE {$where}";
		//执行SQL语句，并返回结果(整数)
		return $this->pdo->rowCount($sql);
	}
}

