<?php
namespace Home\Model;
use Think\Model;
class HistoryModel extends Model 
{
	public function getElseGoods($goodsId, $limit = 5)
	{
		$sql = 'SELECT b.id,b.goods_name,b.mid_logo,b.shop_price 
		   FROM sh_history a
  		  LEFT JOIN sh_goods b ON a.goods_id=b.id
		 WHERE member_id IN(
		SELECT member_id FROM sh_history WHERE goods_id='.$goodsId.') AND a.goods_id <> '.$goodsId.' ORDER BY a.addtime DESC LIMIT '.$limit;
		return $this->query($sql);
	}
}