<?php
namespace Home\Model;
use Think\Model;
class NewsModel extends Model 
{
	public function getNavHelp()
	{
		$newCatModel = M('NewsCat');
		$data = $newCatModel->where("is_help='是'")->select();
		foreach ($data as $k => $v)
		{
			$data[$k]['articles'] = $this->where('cat_id='.$v['id'].' AND isshow="是"')->select();
		}
		return $data;
	}
}