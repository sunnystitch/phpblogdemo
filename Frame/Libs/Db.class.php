<?php
//声明命名空间
namespace Frame\Libs;
//定义最终的单例的数据库操作类
final class Db
{
	//私有的静态的保存对象的属性
	private static $obj = NULL;

	//私有的数据库配置信息
	private $db_host;	//主机名
	private $db_user;	//用户名
	private $db_pass;	//密码
	private $db_name;	//数据库名
	private $charset;	//字符集

	//私有的构造方法，阻止类外new对象
	private function __construct()
	{
		$this->db_host	= $GLOBALS['config']['db_host'];
		$this->db_user	= $GLOBALS['config']['db_user'];
		$this->db_pass	= $GLOBALS['config']['db_pass'];
		$this->db_name	= $GLOBALS['config']['db_name'];
		$this->charset	= $GLOBALS['config']['charset'];
		$this->connecDb();	//连接数据库
		$this->selectDb();	//选择数据库
		$this->setCharset();//设置字符集
	}

	//私有的克隆方法，阻止类外clone对象
	private function __clone(){}

	//公共的静态的创建对象的方法
	public static function getInstance()
	{
		//判断对象是否存在
		if(!self::$obj instanceof self)
		{
			//如果对象不存在，则创建它
			self::$obj = new self();
		}
		//返回对象
		return self::$obj;
	}

	//私有的连接数据库的方法
	private function connecDb()
	{
		if(!@mysql_connect($this->db_host,$this->db_user,$this->db_pass))
			die("PHP连接MySQL失败！");
	}

	//私有的选择数据库的方法 
	private function selectDb()
	{
		if(!mysql_select_db($this->db_name))
			die("选择数据库{$this->db_name}失败了！");
	}

	//私有的设置字符集
	private function setCharset()
	{
		$this->exec("set names {$this->charset}");
	}

	//公共的执行SQL语句的方法：insert、update、delete、set、drop、create等
	//它们的返回是布尔值
	public function exec($sql)
	{
		//将SQL语句转成全小写
		//"select * from student"
		$sql = strtolower($sql);
		//提取SQL语句的前6个字母，比对是不是SELECT语句
		if(substr($sql,0,6)=="select")
		{
			die("该方法只能执行非查询的SQL语句！");
		}
		//返回执行结果(布尔值)
		return mysql_query($sql);
	}

	//私有的执行SQL语句的方法：select
	//返回值是结果集
	private function query($sql)
	{
		//将SQL语句转成全小写
		//"select * from student"
		$sql = strtolower($sql);
		//提取SQL语句的前6个字母，比对是不是SELECT语句
		if(substr($sql,0,6)!="select")
		{
			die("该方法只能执行查询的SQL语句！");
		}
		//返回执行结果(结果集)
		return mysql_query($sql);
	}

	//公共的获取单行数据
	public function fetchOne($sql,$type=3)
	{
		//返回数据的类型数组
		$types = array(
			1	=> MYSQL_NUM,
			2	=> MYSQL_BOTH,
			3	=> MYSQL_ASSOC,
		);

		//执行SQL语句，并返回结果集
		$result = $this->query($sql);

		//返回一维数组
		return mysql_fetch_array($result,$types[$type]);
	}

	//公共的获取多行数据
	public function fetchAll($sql,$type=3)
	{
		//返回数据的类型数组
		$types = array(
			1	=> MYSQL_NUM,
			2	=> MYSQL_BOTH,
			3	=> MYSQL_ASSOC,
		);

		//执行SQL语句，并返回结果集
		$result = $this->query($sql);
		
		//构建二维数组的结果
		while($row=mysql_fetch_array($result,$types[$type]))
		{
			$arrs[] = $row;
		}

		//返回二维数组
		return $arrs;
	}

	//公共的获取记录数
	public function rowCount($sql)
	{
		//执行SQL语句，并返回结果集
		$result = $this->query($sql);
		//返回记录数
		return mysql_num_rows($result);
	}
}
