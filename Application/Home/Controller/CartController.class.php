<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller 
{
	public function addToCart()
	{
		if(IS_POST)
		{
			$goodsId = (int)$_POST['goods_id'];
			if(!$goodsId)
				$this->error('商品ID不正确！');
			$amount = (int)$_POST['amount'];
			if($amount <= 0)
				$this->error('商品数量不正确！');
			sort($_POST['attrId']);
			$attrId = implode(',', $_POST['attrId']);
			$cartModel = D('Cart');
			$cartModel->addToCart($goodsId, $amount, $attrId);
			$this->success('添加成功！', U('lst'));
			exit;
		}
	}
	public function lst()
	{
		$cartModel = D('Cart');
		$data = $cartModel->getGoods();
		$this->assign('data', $data);
		$this->display();
	}
	
	public function ajaxUpdateNum($goodsId, $attrId = '', $goodsNum = 1)
	{
		$cartModel = D('Cart');
		$cartModel->updateGoodsNumber($goodsId, $attrId, $goodsNum);
	}
	
	public function order()
	{
		// 判断有没有登录
		$member_id = session('id');
		if(!$member_id)
		{
			// 当前当前地址存到session中这样登录完之后就又跳回来
			session('returnUrl', $_SERVER['REQUEST_URI']);
			$this->error('必须先登录', U('Member/Index/login'));
		}
		// 先取出购物车中的所有商品
		$cartModel = D('Cart');
		$goods = $cartModel->getGoods();
		if(!$goods)
			$this->error('购物车中没有商品');
		// 取出当前用户所有收货地址
		$addressModel = M('Address');
		$address = $addressModel->where('member_id='.$member_id)->select();
		$this->assign(array(
			'goods' => $goods,
			'address' => $address,
		));
		$this->display();
	}
	public function order1()
	{
		if(IS_POST)
		{
			/**************** 做基本的数据验证 ********************/
			// 判断有没有登录
			$member_id = session('id');
			if(!$member_id)
			{
				// 当前当前地址存到session中这样登录完之后就又跳回来
				session('returnUrl', $_SERVER['REQUEST_URI']);
				$this->error('必须先登录', U('Member/Index/login'));
			}
			// 判断购物车中是否有商品
			$cartModel = D('Cart');
			$goods = $cartModel->getGoods();
			if(!$goods)
				$this->error('购物车中没有商品');
			// 验证是否添加了收货人地址
			if(!isset($_POST['newaddress']))
				$this->error('必须选择收货人地址');
			$addressModel = D('Address');
			// 添加新的地址
			if($_POST['newaddress'] == 0)
			{
				
				if($addressModel->create())
				{
					$addressModel->member_id = $member_id;
					$addressInfo = array(
						'shr_username' => $addressModel->shr_username,
						'shr_province' => $addressModel->shr_province,
						'shr_city' => $addressModel->shr_city,
						'shr_area' => $addressModel->shr_area,
						'shr_address' => $addressModel->shr_address,
						'shr_phone' => $addressModel->shr_phone,
					);
					if(!$addressModel->add())
						$this->error('收货人添加失败，请重试！');
				}
				else 
					$this->error($addressModel->getError());
			}
			else 
			{
				// 从数据库中取出地址的信息
				$addressInfo = $addressModel->find($_POST['newaddress']);
				if(!$addressInfo)
					$this->error('收货人地址不存在！');
			}
			// 必须选择了支付方式
			if(!isset($_POST['pay']) || !in_array($_POST['pay'], array(1,2)))
				$this->error('支付方式不正确');
			// 循环购物车中的每一件商品判断库存量
			$proModel = M('Product');
			$fp = flock('./Public/order.lock', 'r');
			flock($fp, LOCK_EX);
			foreach ($goods as $k => $v)
			{
				$gn = $proModel->field('goods_number')->where("goods_id={$v['goods_id']} AND goods_attr_id='{$v['goods_attr_id']}'")->find();
				if($gn['goods_number'] < $v['goods_number'])
					$this->error('商品名称为：'.$v['goods_name'].'的商品库存量不够!');
			}
			// 如果是余额支付
			if($_POST['pay'] == 2)
			{
				// 先检查余额够不够
				$member = M('Member');
				$member->field('money')->find($member_id);
				if($member->money < $goods[0]['tp'])
					$this->error('余额不足！');
				$pay_status = '已支付';
			}
			else 
				$pay_status = '未支付';
			/********************* 验证都通过之后开始下定单 ****************/
			//1. 定单的基本信息
			$orderModel = D('Order');
			if($order_id = $orderModel->add(array(
				'order_sn' => '###',
				'addtime' => date('Y-m-d H:i:s'),
				'total_price' => $goods[0]['tp'],
				'shr_username' => $addressInfo['shr_username'],
				'shr_province' => $addressInfo['shr_province'],
				'shr_city' => $addressInfo['shr_city'],
				'shr_area' => $addressInfo['shr_area'],
				'shr_address' => $addressInfo['shr_address'],
				'shr_phone' => $addressInfo['shr_phone'],
				'member_id' => $member_id,
				'pay_method' => $_POST['pay'],
				'pay_status' => $pay_status,
			)))
			{
				// 下单成功减少用户的余额
				$member->where('id='.$member_id)->setDec('money', $goods[0]['tp']);
				// 2. 把购物车中的商品添加到定单商品表中
				$ogModel = M('OrderGoods');
				foreach ($goods as $k => $v)
				{
					$ogModel->add(array(
						'order_id' => $order_id,
						'goods_id' => $v['goods_id'],
						'goods_logo' => $v['logo'],
						'goods_name' => $v['goods_name'],
						'goods_price' => $v['price'],
						'goods_number' => $v['goods_number'],
						'goods_attr_id' => $v['goods_attr_id'],
						'goods_attr_str' => $v['goods_attr_str'],
					));
				}
				// 3. 清空购物车
				$cartModel->clear();
				session('pay_method', $_POST['pay']);
				session('order_id', $order_id);
				session('total_price', $goods[0]['tp']);
				// 4. 减去库存量
				foreach ($goods as $k => $v)
				{
					$proModel->where("goods_id={$v['goods_id']} AND goods_attr_id='{$v['goods_attr_id']}'")->setDec('goods_number', $v['goods_number']);
				}
				flock($fp, LOCK_UN);
				fclose($fp);
				$this->success('下单成功', 'order2');
				exit;
			}
			else 
			{
				flock($fp, LOCK_UN);
				fclose($fp);
				$this->error('下单失败，请重试！');	
			}
				
		}
	}
	public function order2()
	{
		$order_id = session('order_id');
		$pay_method = session('pay_method');
		$total_price = session('total_price');
		// 支付宝支付
		if($pay_method == 1)
		{
			include('./alipay/alipayapi.php');
			$this->assign('btn', $html_text);
		}
		$this->display();
	}
	// 用来接收支付宝传给我们的消息 
	public function respond()
	{
		$orderModel = M('Order');
		include('./alipay/notify_url.php');	
	}
}



















