<?php
namespace Member\Controller;
class IndexController extends \Layout\Controller\LayoutController 
{
	public function login()
	{
		if(IS_POST)
		{
			$model = D('Member');
			// TP判断是添加还的修改的表单依据：
			//1. 如果crate没有传第二个参数如果表单中有一个隐藏域ID那么TP就认为是修改，否则就认为是添加
			if($model->create($_POST, 4))
			{
				$ret = $model->login();
				if($ret === TRUE)
				{
					// 判断有没有上一个要退回的页面
					$ru = session('returnUrl');
					if($ru)
					{
						session('returnUrl', null);
						$this->success('登录成功！', $ru);
					}
					else 
						$this->success('登录成功！', __APP__);
					exit;
				}
				elseif ($ret == 1)
					$this->error('用户名错误！');
				elseif ($ret == 2)
					$this->error('密码错误！');
			}
			else 
				$this->error($model->getError());
		}
		$this->display();
	}
	public function regist()
	{
		if(IS_POST)
		{
			if(isset($_POST['mustClick']))
			{
				$model = D('Member');
				if($model->create())
				{
					$username = $model->username;
					$password = $model->password;
					if($model->add())
					{
						$model->login($username, $password);
						// 判断有没有上一个要退回的页面
						$ru = session('returnUrl');
						if($ru)
						{
							session('returnUrl', null);
							$this->success('注册成功！', $ru);
						}
						else 
							$this->success('注册成功！', __APP__);
						exit;
					}
				}
				else 
					$this->error($model->getError());
			}
			else 
				$this->error('必须同意用户协议！');
		}
		$this->display();
	}
	
	public function ajaxChkName($name)
	{
		$name = trim($name);
		$model = M('Member');
		echo $model->where("username='$name'")->count();
	}
	
	public function ajaxChkEmail($email)
	{
		$email = trim($email);
		$model = M('Member');
		echo $model->where("email='$email'")->count();
	}
	
	public function chkcode($code)
	{
		$Verify = new \Common\Common\ShopVerify();
		if($Verify->check($code, FALSE))
			echo 1;
		else 
			echo 0;
	}
	
	public function getCode()
	{
		$Verify = new \Think\Verify();
		$Verify->entry();
	}
	
	public function ajaxChkLogin()
	{
		// 执行定时任务
		$inte = new \Home\Model\Interval();
		$inte->do_event();
		$cartModel = new \Home\Model\CartModel();
		$cartGoodsCount = (int)$cartModel->getCount();
		if(session('id'))
		{
			echo json_encode(array(
				'id' => session('id'),
				'username' => session('username'),
				'cartGoodsCount' => $cartGoodsCount,
			));
		}
		else 
		{
			// 如果用户没有登录那么就从cookie中取出用户名和密码，如果有就直接替用户登录
			if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
			{
				$model = D('Member');
				$username = trim($model->desDecode($_COOKIE['username']));
				$password = trim($model->desDecode($_COOKIE['password']));
				if($model->login($username, $password) === TRUE)
				{
					echo json_encode(array(
						'id' => session('id'),
						'username' => session('username'),
						'cartGoodsCount' => $cartGoodsCount,
					));
				}
				else 
				{
					// 如果登录失败，说明COOKIE中的用户名密码不正确那么就删除就行了
					$model->logout();
					echo json_encode(array(
						'cartGoodsCount' => $cartGoodsCount,
					));
				}
			}
			else 
				echo json_encode(array(
					'cartGoodsCount' => $cartGoodsCount,
				));
		}
	}
	
	public function logout()
	{
		$model = D('Member');
		$model->logout();
		redirect('/');
	}
}