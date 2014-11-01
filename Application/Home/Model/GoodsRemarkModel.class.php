<?php
namespace Home\Model;
use Think\Model;
class GoodsRemarkModel extends Model 
{
	protected $_validate = array(
		array('content', 'require', '内容不能为空！', 1),
		array('goods_id', 'require', '商品ID不能为空！', 1),
		array('star', 'require', '必须指定是几星！', 1),
		// 验证码所有的情况都要验证
		array('checkcode', 'require', '验证码不能为空！', 1, 'regex'),
		array('checkcode', 'chk_code', '验证码输入错误！', 1, 'callback'),
	);
	
	protected function chk_code($code)
	{
		$verify = new \Think\Verify();
		return $verify->check($code);
	}
	
	protected function _before_insert(&$data, $option)
	{
		$data['addtime'] = date('Y-m-d H:i:s');
		// 处理印象的数据
		if(isset($_POST['title']) && ($title = trim($_POST['title'])))
		{
			$giModel = M('GoodsImpression');
			$title = I('post.title');
			$title = str_replace('，', ',', $title);
			$title = explode(',', $title);
			
			foreach ($title as $v)
			{
				$v = trim($v);
				if(!$v)
					continue ;
				$_c = $giModel->where("goods_id={$data['goods_id']} AND title='$v'")->count();
				if($_c >= 1)
					$giModel->where("goods_id={$data['goods_id']} AND title='$v'")->setInc('num');
				else 
					$giModel->add(array(
						'title' => $v,
						'goods_id' => $data['goods_id'],
					));
			}
		}
	}
}














