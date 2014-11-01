<?php
namespace Home\Model;
use Think\Model;
class GoodsModel extends Model 
{
	public function getRecGoods($recName, $count = 5, $field = 'id,mid_logo,shop_price,goods_name')
	{
		$recModel = M('Recommend');
		$goods_id = $recModel->field('goods_id')->where("rec_name='$recName'")->find();
		$goods_id = explode(',', $goods_id['goods_id']);
		if($goods_id)
		{
			// 把数组打乱
			shuffle($goods_id);
			$goods_id = array_splice($goods_id, 0, $count);
			$goods_id = implode(',', $goods_id);
			return $this->field($field)->where("id IN($goods_id)")->select();
		}
		else 
			return NULL;
	}
}