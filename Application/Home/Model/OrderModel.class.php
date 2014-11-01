<?php
namespace Home\Model;
use Think\Model;
class OrderModel extends Model 
{
	public function getTop10($catId)
	{
		// 取出所有子分类的id
		$catModel = D('Category');
		$children = $catModel->getChildrenId($catId);
		$children[] = $catId;
		$children = implode(',', $children);  //1,2,3,4,5
		// 从定单中取出商品的ID，再根据商品ID连接商品表取出商品所在的分类ID
		$sql = "select b.*,SUM(b.goods_number) num
                 from sh_order a
				 left join sh_order_goods as b on a.id = b.order_id 
				  LEFT JOIN sh_goods c ON b.goods_id=c.id
				   where a.post_status ='已到货' AND c.cat_id IN($children)
                    group by b.goods_id
                     order by num DESC
                       LIMIT 10";
		return $this->query($sql);
	}
	protected function _after_insert($data, $option)
	{
		$sn = date('Ymd').$data['id'];
		$this->where('id='.$data['id'])->save(array(
			'order_sn' => $sn,
		));
	}
}




