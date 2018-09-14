<?php
//声明命名空间
namespace Admin\Model;
use \Frame\Libs\BaseModel;
//定义最终的分类模型类，并继承基础模型类
final class CategoryModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "category";

	//获取无限级分类的数据
	public function categoryList($arrs,$level=0,$pid=0)
	{
		/*
			方法参数说明：
				a)$arrs原始的分类数据
				b)$level代表菜单等级
				c)$pid代表上次递归传递过来的id参数
		*/
		//静态的变量数组，来保存结果数据
		//静态变量只需要初始化一次
		//函数或方法执行完毕，静态变量不会消失
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