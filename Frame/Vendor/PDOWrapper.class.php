<?php
//声明命名空间
namespace Frame\Vendor;
use \PDO; //引入PDO类
use \PDOException; //引入PDOException异常类

//定义最终的PDOWrapper类
final class PDOWrapper
{
	//私有的数据库配置属性
	private $db_type;	//数据库类型
	private $db_host;	//主机名
	private $db_port;	//端口号
	private $db_user;	//用户名
	private $db_pass;	//密码
	private $db_name;	//数据库名
	private $charset;	//字符集
	private $pdo = NULL;//PDO对象

	//构造方法：PDO对象初始化
	public function __construct()
	{
		$this->db_type = $GLOBALS['config']['db_type'];
		$this->db_host = $GLOBALS['config']['db_host'];
		$this->db_port = $GLOBALS['config']['db_port'];
		$this->db_user = $GLOBALS['config']['db_user'];
		$this->db_pass = $GLOBALS['config']['db_pass'];
		$this->db_name = $GLOBALS['config']['db_name'];
		$this->charset = $GLOBALS['config']['charset'];
		$this->connectDb(); //连接数据库，并创建PDO对象
		$this->setErrMode(); //设置PDO的报错模式为：异常模式
	}

	//私有的连接数据库的方法，也就是创建PDO类的对象
	private function connectDb()
	{
		try{
			//构建PDO类的三个参数
			$dsn = "{$this->db_type}:host={$this->db_host};port={$this->db_port};";
			$dsn .= "dbname={$this->db_name};charset={$this->charset}";
			$username = $this->db_user;
			$password = $this->db_pass;
			//创建PDO类的对象
			$this->pdo = new PDO($dsn,$username,$password);
		}catch(PDOException $e)
		{
			echo "<h2>创建PDO类对象失败！</h2>";
			echo "错误编号：".$e->getCode();
			echo "<br>错误行号：".$e->getLine();
			echo "<br>错误文件：".$e->getFile();
			echo "<br>错误信息：".$e->getMessage();
		}
	}

	//私有的设置PDO的报错模式
	private function setErrMode()
	{
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}

	//公共的执行SQL语句的方法：insert、delete、update、create、set、drop……
	public function exec($sql)
	{
		try{
			return $this->pdo->exec($sql);
		}catch(PDOException $e)
		{
			$this->showError($e);
		}
	}

	//公共的获取单行数据方法(一维数组)
	public function fetchOne($sql)
	{
		try{
			//执行SQL语句：SELECT语句
			$PDOStatement = $this->pdo->query($sql);
			//从结果集对象中，获取一行数据
			return $PDOStatement->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e)
		{
			$this->showError($e);
		}
	}

	//公共的获取多行数据方法(二维数组)
	public function fetchAll($sql)
	{
		try{
			//执行SQL语句：SELECT语句
			$PDOStatement = $this->pdo->query($sql);
			//从结果集对象中，获取多行数据
			return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e)
		{
			$this->showError($e);
		}
	}
	
	//公共的获取记录数的方法
	public function rowCount($sql)
	{
		try{
			//执行查询的SQL语句，并返回结果集对象
			$PDOStatement = $this->pdo->query($sql);
			//返回记录数
			return $PDOStatement->rowCount();
		}catch(PDOException $e)
		{
			$this->showError($e);
		}
	}

	//私有的错误显示方法
	private function showError($e)
	{
		echo "<h2>SQL语句有错误！</h2>";
		echo "错误编号：".$e->getCode();
		echo "<br>错误行号：".$e->getLine();
		echo "<br>错误文件：".$e->getFile();
		echo "<br>错误信息：".$e->getMessage();	
	}
}