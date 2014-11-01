<?php
namespace Home\Model;
use Think\Model;
class CategoryModel extends Model 
{
	// 取出三级做为分类
	public function getNavCat()
	{
		$data = $this->where('parent_id=0')->select();
		foreach ($data as $k => $v)
		{
			$data[$k]['children'] = $this->where('parent_id='.$v['id'])->select();
			foreach ($data[$k]['children'] as $k1 => $v1)
			{
				$data[$k]['children'][$k1]['children'] = $this->where('parent_id='.$v1['id'])->select();
			}
		}
		return $data;
	}
	public function getChildrenId($catId)
	{
		$data = $this->select();
		return $this->_findChildrenId($data, $catId);
	}
	
	private function _findChildrenId($data, $parent_id=0, $isClear = TRUE)
	{
		static $ret = array();
		// 如果是第一次访问先清空数组在递归时不清空
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				// 找子级
				$this->_findChildrenId($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}
	/**
	 * 取出一个分类所在大类下所有的三级分类
	 *
	 * @param unknown_type $catId
	 */
	public function getCatList($catId)
	{
		$allCatId = array();
		/*********** 先从数据库中取出所有的分类到内存，在内存中每一个需要的二级分类 *************/
		$allCat = $this->select();
		// 先取出当前分类的基本信息
		foreach ($allCat as $k => $v)
		{
			if($v['id'] == $catId)
			{
				$data = $v;
				break;
			}
		}
		if($data['parent_id'] == 0)
		{
			$allCatId[] = $data['id'];
			// 如果是顶级的那么取出二级分类
			foreach ($allCat as $k => $v)
			{
				// 找二级分类
				if($v['parent_id'] == $data['id'])
				{
					$allCatId[] = $v['id'];
					// 再找这个二级分类的子分类
					foreach ($allCat as $k1 => $v1)
					{
						if($v1['parent_id'] == $v['id'])
						{
							$v['children'][] = $v1;
							$allCatId[] = $v1['id'];
						}
					}
					$data['children'][] = $v;
				}
			}
			$data['allCatId'] = $allCatId;
			return $data;
		}
		else 
		{
			/***************** 先找出最顶级分类的信息 *****************/
			$parent_id = $data['parent_id'];
			// 认为当前分类就是二级的
			$secondCatId = $data['id'];
			//如果不是顶级分类就找上一级的分类
			foreach ($allCat as $k => $v)
			{
				// 找上一级分类
				if($v['id'] == $parent_id)
				{
					// 判断 上一级是否是顶级
					if($v['parent_id'] == 0)
					{
						$allCatId[] = $v['id'];
						// 取出这个顶级分类下所有的子分类	
						// 如果是顶级的那么取出二级分类
						foreach ($allCat as $k1 => $v1)
						{
							// 找二级分类
							if($v1['parent_id'] == $v['id'])
							{
								$allCatId[] = $v1['id'];
								// 再找这个二级分类的子分类
								foreach ($allCat as $k2 => $v2)
								{
									if($v2['parent_id'] == $v1['id'])
									{
										$v1['children'][] = $v2;
										$allCatId[] = $v2['id'];
									}
								}
								$v['children'][] = $v1;
							}
						}
						$v['allCatId'] = $allCatId;
						return $v;
					}
					else 
					{
						// 如果上级不是二级的那么就认为这个上级的分类是二级的
						$secondCatId = $v['id'];
						// 再找上一级分类就是顶级的
						foreach ($allCat as $k1 => $v1)
						{
							// 找顶级
							if($v['parent_id'] == $v1['id'])
							{
								$allCatId[] = $v1['id'];
								// 找所有的子级分类
								// 如果是顶级的那么取出二级分类
								foreach ($allCat as $k2 => $v2)
								{
									// 找二级分类
									if($v2['parent_id'] == $v1['id'])
									{
										$allCatId[] = $v2['id'];
										// 再找这个二级分类的子分类
										foreach ($allCat as $k3 => $v3)
										{
											if($v3['parent_id'] == $v2['id'])
											{
												$v2['children'][] = $v3;
												$allCatId[] = $v3['id'];
											}
										}
										$v1['children'][] = $v2;
									}
								}
								$v1['openId'] = $secondCatId;
								$v1['allCatId'] = $allCatId;
								return $v1;
							}
						}
					}
					break ;
				}
			}
		}
	}
}