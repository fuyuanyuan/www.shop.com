<?php
namespace Home\Model;
use Think\Model;
class CartModel extends Model 
{
	public function addToCart($goodsId, $goodsNumber = 1, $goodsAttrId = '')
	{
		/************* 构造 属性的字符串 **************/
		$goodsAttrStr = '';
		if($goodsAttrId)
		{
			$_goodsAttrId = explode(',', $goodsAttrId);
					// 循环每一个属性的ID转化成属性名称+属性值
			foreach ($_goodsAttrId as $k => $v)
			{
						$sql = 'SELECT b.attr_name,a.attr_value
								 FROM sh_goods_attr a
								  LEFT JOIN sh_attribute b ON a.attr_id=b.id
								   WHERE a.id='.$v;
				$_attr = $this->query($sql);
				$goodsAttrStr .= $_attr[0]['attr_name'].':'.$_attr[0]['attr_value'].'<br />';
			}
		}
		$member_id = session('id');
		if($member_id)
		{
			// 判断如果数据库中已经存在这件商品就在原数量上加上这次购买的数量
			$count = $this->where('member_id='.$member_id.' AND goods_id='.$goodsId.' AND goods_attr_id="'.$goodsAttrId.'"')->count();
			if($count >= 1)
				$this->where('member_id='.$member_id.' AND goods_id='.$goodsId.' AND goods_attr_id="'.$goodsAttrId.'"')->setInc('goods_number', $goodsNumber);
			else
				$this->add(array(
					'member_id' => $member_id,
					'goods_id' => $goodsId,
					'goods_number' => $goodsNumber,
					'goods_attr_id' => (string)$goodsAttrId,
					'goods_attr_str' => $goodsAttrStr,
				));
		}
		else 
		{
			/******************* 如果没有登录就操作cookie ************************/
			$cart = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : array();
			// 如果购物车不为空就把序列化
			if($cart)
				$cart = unserialize($cart);
			// 先判断购物车中有没有这件商品
			$_has =  FALSE;
			foreach ($cart as $k => $v)
			{
				if(($v['goods_id'] == $goodsId) && ($v['goods_attr_id'] == $goodsAttrId))
				{
					$_has =  TRUE;
					// 如果这件商品已经存在就在商品数量上加上这次购买的数量
					$cart[$k]['goods_number'] += $goodsNumber;
					break ;
				}
			}
			// 如果购物车中没有这件商品就新加上这件商品
			if(!$_has)
			{
				$cart[] = array(
					'goods_id' => $goodsId,
					'goods_number' => $goodsNumber,
					'goods_attr_id' => $goodsAttrId,
					'goods_attr_str' => $goodsAttrStr,
				);
			}
			// 把数组存回到cookie中
			setcookie('cart', serialize($cart), time() + 3600 * 24 * 7, '/', '.shop.com');
		}
	}
	
	public function getGoods()
	{
		$member_id = session('id');
		/************ 如果登录了就从数据库中，如果没有 登录就从cookie中取 *********************/
		if($member_id)
		{
			$data = $this->where('member_id='.$member_id)->select();
		}
		else 
		{
			$data = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
		}
		$goodsModel = M('Goods');
		// 取出商品的详情信息（LOGO,名称、会员价格）
		// 取出当前会员的级别ID和折扣率
		$level_id = (int)session('level_id');
		$rate = session('?rate') ? session('rate') : 1;
		// 总价
		$tp = 0;
		foreach ($data as $k => $v)
		{
			$sql = 'SELECT a.mid_logo,a.goods_name,IFNULL(b.price,a.shop_price*'.$rate.') price
					 FROM sh_goods a
					  LEFT JOIN sh_level_price b ON(a.id=b.goods_id AND b.level_id=.'.$level_id.')
					   WHERE id='.$v['goods_id'];
			$info = $this->query($sql);
			$data[$k]['logo'] = $info[0]['mid_logo'];
			$data[$k]['goods_name'] = $info[0]['goods_name'];
			$data[$k]['price'] = round($info[0]['price'], 2);
			$tp += $data[$k]['price'] * $v['goods_number'];
		}
		// 如果$data为真执行后面的语句
		$data && $data[0]['tp'] = $tp;
		return $data;
	}
	
	public function getCount()
	{
		$member_id = session('id');
		/************ 如果登录了就从数据库中，如果没有 登录就从cookie中取 *********************/
		if($member_id)
		{
			$info = $this->field('SUM(goods_number) gn')->where('member_id='.$member_id)->find();
			return $info['gn'];
		}
		else 
		{
			$data = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			if($data)
			{
				$sum = 0;
				foreach ($data as $k => $v)
				{
					$sum += $v['goods_number'];
				}
				return $sum;
			}
			else 
				return 0;
		}
	}
	
	public function clear()
	{
		$member_id = session('id');
		if($member_id)
			$this->where('member_id='.$member_id)->delete();
		else 
			setcookie('cart', '', 1, '/', '.shop.com');
	}
	
	public function updateGoodsNumber($goodsId, $attrId, $goodsNum)
	{
		$member_id = session('id');
		/************ 如果登录了就从数据库中，如果没有 登录就从cookie中取 *********************/
		if($member_id)
		{

			if($goodsNum == 0)
				$this->where('member_id='.$member_id.' AND goods_id='.$goodsId.' AND goods_attr_id="'.$attrId.'"')->delete();
			else 
				$this->where('member_id='.$member_id.' AND goods_id='.$goodsId.' AND goods_attr_id="'.$attrId.'"')->save(array(
					'goods_number' => $goodsNum,
				));
		}
		else 
		{ 
			// 先从cookie取出数组
			$data = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			// 从数组中找出要修改的记录并修改
			foreach ($data as $k => $v)
			{
				if(($v['goods_id'] == $goodsId) && ($v['goods_attr_id'] == $attrId))
				{
					if($goodsNum == 0)
						unset($data[$k]);
					else 
						$data[$k]['goods_number'] = $goodsNum;
					break ;
				}
			}
			// 把数组存回到cookie中
			setcookie('cart', serialize($data), time() + 3600 * 24 * 7, '/', '.shop.com');
		}
	}
	
	public function dropOne()
	{
		
	}
}