<?php
//声明命名空间
namespace Frame\Vendor;
//包含原始的Smarty类文件：./Frame/Vendor/Smarty-3.1.16/libs/Smarty.class.php
require_once(FRAME_PATH."Vendor".DS."Smarty-3.1.16".DS."libs".DS."Smarty.class.php");
//定义最终的Smarty类，并继承原始的Smarty类
final class Smarty extends \Smarty
{
	//自己的Smarty类不写任何内容
}