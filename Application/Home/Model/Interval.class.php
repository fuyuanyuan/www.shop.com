<?php
namespace Home\Model;
class Interval
{
	public function do_event()
	{
		if(rand(1,10) == 5)
			$this->_chk_order();
	}
	// 检查超过30分钟没有支付的定单就删除并退还库存量
	// 检查超过15天没收货的定单更新为已收货
	private function _chk_order()
	{
		$order = M('Order');
		$ogModel = M('OrderGoods');
		$proModel = M('Product');
		/*************** 先取出30分钟没支付的定单的id **********************/
		$data = $order->field('id')->where('pay_status="未支付" AND ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(addtime)) > 1800)')->select();
		$oid = array();  // 所有定单的ID
		/********************** 退还库存量 ***************************/
		// 循环每一个定单取出定单中所有的商品并退还库存量
		foreach ($data as $k => $v)
		{
			$oid[] = $v['id'];
			// 取出定单中的商品
			$goods = $ogModel->field('goods_id,goods_number,goods_attr_id')->where('order_id='.$v['id'])->select();
			// 循环每一个商品退还库存量
			foreach ($goods as $k1 => $v1)
			{
				$proModel->where('goods_id='.$v1['goods_id'].' AND goods_attr_id="'.$v1['goods_attr_id'].'"')->setInc('goods_number', $v1['goods_number']);
			}
		}
		$oid = implode(',', $oid);
		/********************* 从数据库中把定单和定单商品记录删除 *****************************/
		// 把定单和定单商品删除掉
		$order->delete($oid);
		$ogModel->where("order_id IN($oid)")->delete();
		/************************************ 15天没收货的定单自动更新为已收货并给会员增加经验值和积分 **************************************/
		$time = 15 * 24 * 3600;
		// 1. 先取出这些定单的总价格和会员ID
		$mdata = $order->field('total_price,member_id')->where("post_status='已发送' AND (UNIX_TIMESTAMP() - post_time > $time)")->select();
		// 循环每一个定单为会员增加经验值和积分
		foreach ($mdata as $k => $v)
		{
			$order->execute("UPDATE sh_member SET jifen=jifen+{$v['total_price']},jyz=jyz+{$v['total_price']} WHERE id = {$v['member_id']}");
		}
		$order->where("post_status='已发送' AND (UNIX_TIMESTAMP() - post_time > $time)")->save(array(
			'post_status' => '已收货',
		));
	}
}