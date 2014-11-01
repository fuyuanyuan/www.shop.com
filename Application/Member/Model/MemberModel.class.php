<?php
namespace Member\Model;
use Think\Model;
class MemberModel extends Model 
{
	private $_des_key = '123456';
	
	protected $_validate = array(
		// 用户名密码所有的情况都验证
		array('username', 'require', '用户名不能为空！', 1),
		array('email', 'email', 'email格式不正确！', 1, 'regex', 1),
		array('email', 'email', 'email格式不正确！', 1, 'regex', 2),
		array('password', 'require', '密码不能为空！', 1),
		// 确认密码只有在注册和修改验证
		array('rpassword', 'password', '两次密码不一致！', 1, 'confirm', 1),
		array('rpassword', 'password', '两次密码不一致！', 1, 'confirm', 2),
		// 验证码所有的情况都要验证
		array('checkcode', 'require', '验证码不能为空！', 1, 'regex'),
		array('checkcode', 'chk_code', '验证码输入错误！', 1, 'callback'),
		// 用户名唯一只有在注册和修改验证
		array('username', '', '用户名已经存在！', 1, 'unique', 1),
		array('username', '', '用户名已经存在！', 1, 'unique', 2),
		array('email', '', 'email已经存在！', 1, 'unique', 1),
		array('email', '', 'email已经存在！', 1, 'unique', 2),
	);
	
	protected function chk_code($code)
	{
		$verify = new \Think\Verify();
		return $verify->check($code);
	}
	
	protected function _before_insert(&$data, $option)
	{
		$data['salt'] = substr(uniqid(), -6);
		$data['password'] = md5(md5($data['password']) . $data['salt']);
		$data['reg_time'] = date('Y-m-d H:i:s');
	}
	// 把COOKIE中的购物车移动到数据库中
	private function _moveCartToDb($member_id)
	{
		$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
		// 如果购物车中有商品
		if($cart)
		{
			$cartModel = M('Cart');
			foreach ($cart as $k => $v)
			{
				$cartModel->add(array(
					'member_id' => $member_id,
					'goods_id' => $v['goods_id'],
					'goods_attr_id' => $v['goods_attr_id'],
					'goods_attr_str' => $v['goods_attr_str'],
					'goods_number' => $v['goods_number'],
				));
			}
			// 清空cookie中的购物车
			setcookie('cart', '', 1, '/', '.shop.com');
		}
	}
	
	public function login($username = '', $password = '')
	{
		if(!$username)
			$username = $this->username;
		if(!$password)
			$password = $this->password;
		$info = $this->where("username='$username'")->find();
		// 判断用户名是否存在
		if($info)
		{
			if($info['password'] == md5(md5($password) . $info['salt']))
			{
				// 登录成功之后把用户的id和用户名存到session中
				session('id', $info['id']);
				session('username', $info['username']);
				// 把购物车中的商品从COOKIE中移动到数据库中
				$this->_moveCartToDb($info['id']);
				// 把会员的级别和折扣率存到SESSION中
				$mlModel = M('MemberLevel');
				$mlData = $mlModel->where("{$info['jyz']} BETWEEN num_bottom AND num_top")->find();
				if($mlData)
				{
					session('level_id', $mlModel->id);
					session('rate', $mlModel->rate / 100);
				}
				else 
				{
					session('level_id', 0);
					session('rate', 1);
				}
				// 判断如果点击的保存登录状态那么就把用户名和密码存到COOKIE中
				if(isset($_POST['remember']))
				{
					$time = time() + 24 * 3600 * 30;
					// 第四个参数：COOKIE可用的目录
					// /a/b.php    setcookie('username', $info['username'], $time);
					// /a/d/e.php   echo $_COOKIE['username'];   -->  可以读到
					// /c.php      echo $_COOKIE['username'];    --> 无法读到
					// 现在c.php无法读取那个cookie变量：原因：cookie默认只有定义它的文件的同目录以及子目录中的文件可以访问
					// 解决办法：第四个参数设置为/这样这个COOKIE在整个网站无论哪个目录中都可以使用
					// 第五个参数：有时一个网站可能有多个二级域名,但cookie默认只能在它定义的那个域名中访问，解决的办法就是设置第五个参数
					// 第六个参数：如果https协议的需要设置true
					$_username = \Think\Crypt\Driver\Des::encrypt($info['username'], $this->_des_key);
					$_password = \Think\Crypt\Driver\Des::encrypt($password, $this->_des_key);
					setcookie('username', $_username, $time, '/', '.shop.com');
					setcookie('password', $_password, $time, '/', '.shop.com');
				}
				return TRUE;
			}
			else 
				return 2;
		}
		else 
			return 1;
	}
	
	public function desDecode($str)
	{
		return \Think\Crypt\Driver\Des::decrypt($str, $this->_des_key);
	}
	
	public function logout()
	{
		session(null);
		setcookie('username', $info['username'], 1, '/', '.shop.com');
		setcookie('password', $password, 1, '/', '.shop.com');
	}
}