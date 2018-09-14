<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\UserModel;
//定义最终的用户控制器类，并继承基础控制器类
final class UserController extends BaseController
{
	//显示用户列表
	public function index()
	{
		$this->denyAccess();
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//获取多行数据
		$users = $modelObj->fetchAll();
		//向视图赋值，并显示视图
		$this->smarty->assign("users",$users);
		$this->smarty->display("User/index.html");
	}

	//显示添加的表单
	public function add()
	{
		$this->denyAccess();
		$this->smarty->display("User/add.html");
	}

	//插入表单数据
	public function insert()
	{
		$this->denyAccess();
		//获取表单数据
		$data['username']	= $_POST['username'];
		$data['password']	= md5($_POST['password']);
		$data['name']		= $_POST['name'];
		$data['tel']		= $_POST['tel'];
		$data['status']		= $_POST['status'];
		$data['role']		= $_POST['role'];
		$data['addate']		= time();

		//判断两次密码是否一致
		if($data['password']!=md5($_POST['confirmpwd']))
		{
			$this->jump("两次输入的密码不一致！","?c=User");
		}

		//创建模型类对象
		$modelObj = UserModel::getInstance();

		//判断用户是否存在
		$records = $modelObj->rowCount("username='{$data['username']}'");
		if($records)
		{
			$this->jump("用户名{$data['username']}已经存在了！","?c=User");
		}

		//判断数据是否插入成功
		if($modelObj->insert($data))
		{
			$this->jump("用户注册成功！","?c=User");
		}else
		{
			$this->jump("用户注册失败！","?c=User");
		}
	}

	//显示修改用户的表单
	public function edit()
	{
		$this->denyAccess();
		//获取地址栏id
		$id = $_GET['id'];
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//获取指定id的数据
		$user = $modelObj->fetchOne("id=$id");
		//向视图赋值，并显示视图
		$this->smarty->assign("user",$user);
		$this->smarty->display("User/edit.html");
	}

	//更新用户数据
	public function update()
	{
		$this->denyAccess();
		//获取表单提交值
		$id				= $_POST['id'];
		$data['name']	= $_POST['name'];
		$data['tel']	= $_POST['tel'];
		$data['status']	= $_POST['status'];
		$data['role']	= $_POST['role'];
		//判断密码是否为空
		if(!empty($_POST['password']))
		{
			//判断两次密码是否一致
			if($_POST['password'] == $_POST['confirmpwd'])
			{
				$data['password'] = md5($_POST['password']);
			}else
			{
				$this->jump("两次输入的密码不一致！","?c=User");
			}
		}
		
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//判断数据是否更新成功
		if($modelObj->update($data,$id))
		{
			$this->jump("id={$id}用户更新成功！","?c=User");
		}else
		{
			$this->jump("id={$id}用户更新失败！","?c=User");
		}
	}

	//删除数据
	public function delete()
	{
		$this->denyAccess();
		//获取地址栏id
		$id = $_GET['id'];
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//判断数据是否删除成功
		if($modelObj->delete($id))
		{
			$this->jump("id={$id}的用户删除成功！","?c=User");
		}else
		{
			$this->jump("id={$id}的用户删除失败！","?c=User");
		}
	}

	//用户登录
	public function login()
	{
		$this->smarty->display("User/login.html");
	}

	//用户登录检测
	public function loginCheck()
	{
		//获取表单提交值
		$username	= $_POST['username'];
		$password	= md5($_POST['password']);
		$verify		= $_POST['verify'];
		$data['last_login_ip']		= $_SERVER['REMOTE_ADDR'];
		$data['last_login_time']	= time();

		//判断验证码是否合法
		if(strtolower($verify) != $_SESSION['captcha'])
		{
			$this->jump("两次输入的验证码不一致！","?c=User&a=login");
		}

		//判断用户是否存在
		$user = UserModel::getInstance()->fetchOne("username='$username' and password='$password'");
		if(!$user)
		{
			$this->jump("用户名或密码不正确！","?c=User&a=login");
		}

		//更新用户资料：最后登录的IP、最后登录的时间、登录总次数+1
		$data['login_times'] = $user['login_times']+1;
		UserModel::getInstance()->update($data,$user['id']);

		//将用户信息存储到SESSION中
		$_SESSION['uid']		= $user['id'];
		$_SESSION['username']	= $username;

		//跳转到后台首页
		$this->jump("恭喜您登录成功！正在跳转到后台管理中心...","?c=Index&a=index");
	}

	//用户退出方法
	public function logout()
	{
		unset($_SESSION['username']);
		unset($_SESSION['uid']);
		session_destroy();
		setcookie(session_name(),false);
		$this->jump("用户退出成功！","?c=User&a=login");
	}

	//获取验证码方法
	public function captcha()
	{
		//创建验证码类的对象
		$captchaObj = new \Frame\Vendor\Captcha();
		//获取验证码字符串，并存入SESSION
		$_SESSION['captcha'] = $captchaObj->getCode();
	}
}