<?php
namespace Home\Controller;
class IndexController extends \Layout\Controller\LayoutController 
{
	public function index()
	{
		// 设置页面的信息
		$this->assign(array(
			'title' => '首页',
			'css' => array('index'),
			'js' => array('index'),
			'keyword' => '商城',
			'description' => '商城',
			'show_nav' => 1,
		));
		// 取出首页中的广告
		$adModel = D('Ad');
		$ad1 = $adModel->showAd(1);
		$ad2 = $adModel->showAd(2);
		$this->assign(array(
			'adModel' => $adModel,
			'ad1' => $ad1,
			'ad2' => $ad2,
		));
		// 取出八篇非帮助分类下的新闻
		$news = $adModel->query('SELECT id,title FROM sh_news WHERE cat_id IN(SELECT id FROM sh_news_cat WHERE is_help="否") AND isshow="是" LIMIT 8');
		$this->assign('news', $news);
		// 取出推荐的商品
		$goodsModel = D('Goods');
		$this->assign(array(
			'goods1' => $goodsModel->getRecGoods('疯狂抢购'),
			'goods2' => $goodsModel->getRecGoods('热卖商品'),
			'goods3' => $goodsModel->getRecGoods('推荐商品'),
			'goods4' => $goodsModel->getRecGoods('新品上架'),
			'goods5' => $goodsModel->getRecGoods('猜您喜欢'),
		));
		/********* 取出中间大类的数据 *************/
		// 先取出商品大类这个推荐位上所有的商品的ID
		$recModel = M('Recommend');
		$recGoodsId = $recModel->field('goods_id')->where("rec_name='首页中间大类推荐'")->find();
		$recGoodsId = $recGoodsId['goods_id'];
		$goodsModel = M('Goods');
		// 1. 取出所有被推荐的顶级分类
		$catData = $this->catModel->where('parent_id=0 AND is_rec="是"')->select();
		// 2. 循环每一个大类，取出大类下的其他数据
		foreach ($catData as $k => $v)
		{
			// 1. 取出没有推荐的二级分类
			$normalSubCat = $this->catModel->field('id,cat_name')->where('parent_id='.$v['id'].' AND is_rec="否"')->select();
			// 2. 取出四个被援藏的二级分类
			$recSubCat = $this->catModel->field('id,cat_name')->where('parent_id='.$v['id'].' AND is_rec="是"')->limit(4)->select();
			// 3. 循环每一个推荐的二级分类，取出分类下以及子分类被推荐了的8件商品
			// 这个数组中保存的是所有推荐的分类以及子分类的ID
			$_allSubCatId = array();
			foreach ($recSubCat as $k1 => $v1)
			{
				// 1. 先取出这个分类所有子分类的id
				$subId = $this->catModel->getChildrenId($v1['id']);
				// 把子分类的ID和当前分类ID都放到数组中
				$subId[] = $v1['id'];
				// 把这个推荐的分类的所有分类ID合并到主数组中
				$_allSubCatId = array_merge($_allSubCatId, $subId);
				$subId = implode(',', $subId);
				// 取出分类下的商品
				$recSubCat[$k1]['goods'] = $goodsModel->field('id,goods_name,mid_logo,shop_price')->where("cat_id IN($subId) AND is_on_sale='是' AND id IN($recGoodsId)")->limit(8)->select();
			}
			// 4. 取出推荐商品中的数据：条件：1.	这个大类下的 2.不在上面推荐的四个分类以及子分类中 3.推荐了的  4.上架的
			// 取出这个大类下所有的子分类的id
			$allSubId = $this->catModel->getChildrenId($v['id']);
			$allSubId[] = $v['id'];
			$_allSubId = implode(',', $allSubId);
			// 先取出这个大类下商品的id，格式为：1,2,3,4,5,6,7,8,9,10
			$goodsIds = $goodsModel->field('GROUP_CONCAT(id) gid')->where("cat_id IN($_allSubId)")->find();
			$goodsIds = $goodsIds['gid'];
			// 取出这个大类下所有商品对应的品牌信息
			$catData[$k]['brands'] = $goodsModel->alias('a')->field('b.*')->join('LEFT JOIN sh_brand b ON a.brand_id=b.id')->where("a.id IN($goodsIds) AND b.logo IS NOT NULL")->group('a.brand_id')->limit(9)->select();
			// 取出这个大类下所有商品相关的文章
			$goodsNewsModel = M('GoodsNews');
			$catData[$k]['news'] = $goodsNewsModel->alias('a')->field('b.id,b.title')->join('LEFT JOIN sh_news b ON a.news_id=b.id')->where("a.goods_id IN($goodsIds) AND b.isshow='是'")->limit(5)->group('a.news_id')->select();
			// 从所有的分类ID中去掉推荐的分类以及子分类的ID
			$allSubId = array_diff($allSubId, $_allSubCatId);
			$allSubId = implode(',', $allSubId);
			$catData[$k]['goods'] = $goodsModel->field('id,goods_name,mid_logo,shop_price')->where("cat_id IN($allSubId) AND is_on_sale='是' AND id IN($recGoodsId)")->limit(8)->select();
			$catData[$k]['nsc'] = $normalSubCat;
			$catData[$k]['rsc'] = $recSubCat;
		}
		$this->assign('catData', $catData);
		$this->display();
	}
	public function goods($id)
	{
		// 设置页面的信息
		$this->assign(array(
			'title' => '商品页',
			'css' => array('goods','common','jqzoom'),
			'js' => array('goods','jqzoom-core','history'),
		));
		/************** 取商品的详细信息 ************/
		//1. 商品的基本信息
		$goodsModel = M('Goods');
		$info = $goodsModel->find($id);
		// 2. 取商品相册
		$gpModel = M('GoodsPics');
		$gpData = $gpModel->where('goods_id='.$id)->select();
		// 3. 取商品的属性
		$gaModel = M('GoodsAttr');
		$_gaData = $gaModel->field('a.*,b.attr_name,b.attr_type')->alias('a')->join('LEFT JOIN sh_attribute b ON a.attr_id=b.id')->where('a.goods_id='.$id)->select();
		// 唯一的数组
		$gaData1 = array();
		// 单选的数组
		$gaData2 = array();
		// 重新处理商品属性：1.把单选和唯一的分开， 2。把单选属性根据属性名称分组放到一起
		foreach ($_gaData as $k => $v)
		{
			if($v['attr_type'] == '唯一')
			{
				$gaData1[] = $v;
			}
			else 
			{
				$gaData2[$v['attr_name']][] = $v;
			}
		}
		$this->assign(array(
			'info' => $info,
			'gpData' => $gpData,
			'gaData1' => $gaData1,
			'gaData2' => $gaData2,
		));
		/*** 取出商品的评论数和分值和印象 ****/
		//1. 取出商品印象
		$gi = M('GoodsImpression');
		$impData = $gi->where('goods_id='.$id)->select();
		// 2. 取商品评论
		$gr = M('GoodsRemark');
		$remark = $gr->field('star')->where('goods_id='.$id)->select();
		$hao = 0;
		$zhong = 0;
		$cha = 0;
		$sum_star = 0;
		foreach ($remark as $k => $v)
		{
			if($v['star'] >= 4)
				$hao++;
			elseif ($v['star'] == 3)
				$zhong++;
			else
				$cha++;
			$sum_star += $v['star'];
		}
		$total = $hao + $zhong + $cha;
		$hao = $hao / $total * 100;
		$zhong = $zhong / $total * 100;
		$cha = $cha / $total * 100;
		$avg_star = (int)($sum_star / $total);
		$this->assign(array(
			'impData' => $impData,
			'total' => $total,
			'hao' => round($hao, 1),
			'cha' => round($cha, 1),
			'zhong' => round($zhong, 1),
			'avg_star' => $avg_star,
		));
		// 取出销量最高的10件商品
		$order = D('Order');
		$xl = $order->getTop10($info['cat_id']);
		$this->assign('xl', $xl);
		// 取出浏览了这件商品的其他商品
		$hisModel = D('History');
		$elseGoods = $hisModel->getElseGoods($id);
		$this->assign('elseGoods', $elseGoods);
		$this->display();
	}
	public function ajaxGetMemberPrice($id)
	{
		$level_id = (int)session('level_id');
		$rate = session('?rate') ? session('rate') : 1;
		// 算法：如果这件商品有会员价格就用会员价格，如果没有会员价格就用折扣率乘上本店价
		$lpModel = M('LevelPrice');
		$price = $lpModel->field('price')->where('goods_id='.$id.' AND level_id='.$level_id)->find();
		if($price)
			echo $price['price'];
		else 
		{
			$goodsModel = M('Goods');
			$goodsModel->field('shop_price')->find($id);
			echo $rate * $goodsModel->shop_price;
		}
	}
	public function ajaxComment()
	{
		if(IS_POST)
		{
			$model = D('GoodsRemark');
			if($model->create())
			{
				$model->add();
				$ret = array(
					'status' => 1,
					'message' => '',
				);
			}
			else 
				$ret = array(
					'status' => -1,
					'message' => $model->getError(),
				);
			echo json_encode($ret);
		}
	}
	public function ajaxGetRemark($p, $goodsId)
	{
		/**** 先取出这一页的数据 ****/
		$perpage = 10;
		$gr = M('GoodsRemark');
		$offset = ($p-1)*$perpage;
		$data = $gr->alias('a')->field('a.*,IFNULL(b.username,"匿名") username,IFNULL(b.sm_logo,"face.jpg") sm_logo')->join('LEFT JOIN sh_member b ON a.member_id=b.id')->where('a.goods_id='.$goodsId)->limit($offset, $perpage)->order('id DESC')->select();
		/***** 再构造翻页的字符串 *************/
		$count = $gr->where('goods_id='.$goodsId)->count();
		$totalPage = ceil($count / $perpage);
		$pageStr = '';
		for($i=1; $i<=$totalPage; $i++)
		{
			if($i == $p)
				$cur = "class='cur'";
			else 
				$cur = '';
			$pageStr .= "<a $cur href='javascript:void(0);' onclick='getRemark($i);'>$i</a> ";
		}
		echo json_encode(array(
			'data' => $data,
			'page' => $pageStr,
		));
	}
	public function ajaxHistory($goodsId)
	{
		/**
		 * 从cookie中取出浏览历史
		 * 规定：cookie这样保存：直接把商品名称和LOGO存到COOKIE中
		 * $arr = array(
			 * 	array(
			 * 		'id' => 1,
			 * 		'goods_name' => 'xxx',
			 * 		'goods_logo' => 'xxx',
			 * 	)
		 * )
		 * 以二维数组的方式存。
		 * 注意：COOKIE中只能存字符串，所以要把二维数组序列化转化成字符串来存
		 * 扩展知识点：cookie中能存多少个字符？一般一个浏览器中COOKIE最大4K。
		 */
		// 1. 多cookie中取出历史记录中的数组
		$history = isset($_COOKIE['history']) ? $_COOKIE['history'] : array();
		if($history)
			$history = unserialize($history);
		// 如果有商品ID就在数据库中和cookie中
		if($goodsId)
		{
			// 0. 取出商品的信息
			$goodsModel = M('Goods');
			$goodsModel->field('mid_logo,goods_name')->find($goodsId);
			// 判断商品是否已经存在
			$has = FALSE;
			foreach ($history as $k => $v)
			{
				if($v['goods_id'] == $goodsId)
				{
					$has = TRUE;
					break ;
				}
			}
			if(!$has)
				// 1. 把商品放到最前面
				array_unshift($history, array(
					'goods_id' => $goodsId,
					'goods_name' => $goodsModel->goods_name,
					'mid_logo' => IMG_URL . $goodsModel->mid_logo,
				));
			// 2. 只要前七个
			$history = array_splice($history, 0, 7);
			// 3. 存回到cookie
			setcookie('history', serialize($history), time() + 24 * 3600 * 30, '/', '.shop.com');
			/********************** 如果登录了就存数据库 *****************/
			if($member_id = session('id'))
			{
				$hisModel = M('History');
				// 如果这个用户已经浏览过了这件商品那么直接更新浏览时间
				$count = $hisModel->where('goods_id='.$goodsId.' AND member_id='.$member_id)->count();
				if($count >= 1)
					$hisModel->where('goods_id='.$goodsId.' AND member_id='.$member_id)->save(array(
						'addtime' => time(),
					));
				else 
					$hisModel->add(array(
						'member_id' => $member_id,
						'goods_id' => $goodsId,
						'addtime' => time(),
					));
			}
		}
		echo json_encode($history);
	}
	public function lst($catId)
	{
		// 设置页面的信息
		$this->assign(array(
			'title' => '搜索页',
			'css' => array('list','common'),
			'js' => array('list'),
		));
		
		
		// 根据点击的分类的ID取出所在的大类下所有的分类
		$catModel = D('Category');
		$catData = $catModel->getCatList($catId);
		$allCatId = implode(',', $catData['allCatId']);
		// 取出所有分类下商品的品牌
		$brandData = $catModel->query("SELECT b.id,b.brand_name
							 FROM sh_goods a LEFT JOIN sh_brand b ON a.brand_id=b.id
							  WHERE a.cat_id IN($allCatId) AND a.brand_id <> 0 AND a.is_on_sale='是'
							   GROUP BY a.brand_id");
		// 取出价格区别
		$price = $catModel->query("SELECT MIN(shop_price) minprice,MAX(shop_price) maxprice FROM sh_goods WHERE cat_id IN($allCatId) AND is_on_sale='是'");
		// 根据最大和最小的价格来进行区间的划分
		$price_section = array();
		$cha = $price[0]['maxprice'] - $price[0]['minprice'];
		$cha_c = $cha / 4;
		$price_section[] = strval($price[0]['minprice']) .'-'. strval($price[0]['minprice']+$cha_c);
		$price_section[] = strval($price[0]['minprice']+$cha_c) .'-'. strval($price[0]['minprice']+$cha_c*2);
		$price_section[] = strval($price[0]['minprice']+$cha_c*2) .'-'. strval($price[0]['minprice']+$cha_c*3);
		$price_section[] = strval($price[0]['minprice']+$cha_c*3) .'-'. strval($price[0]['maxprice']);
		/********** 取出所有商品的单选属性 ****************/
		$_attrData = $catModel->query("SELECT b.id,b.attr_name,a.attr_value
										 FROM sh_goods_attr a
										  LEFT JOIN sh_attribute b ON a.attr_id=b.id
										   LEFT JOIN sh_goods c ON a.goods_id=c.id
										    WHERE c.cat_id IN($allCatId) AND c.brand_id <> 0 AND c.is_on_sale='是' AND b.attr_type='单选'
										     GROUP BY id,attr_value");
		
		foreach ($_attrData as $k => $v)
		{
			$attrData[$v['attr_name']][] = $v;
		}
		$this->assign(array(
			'catData' => $catData,
			'brandData' => $brandData,
			'price_section' => $price_section,
			'attrData' => $attrData,
		));
		
		/************** 商品的搜索 *****************/
		$where = 'a.is_on_sale="是"';
		if($allCatId)
		{
			$where .= " AND a.cat_id IN($allCatId)";
		}
		if(isset($_GET['brand']) && $_GET['brand'])
		{
			$where .= ' AND a.brand_id='.$_GET['brand'];
		}
		if(isset($_GET['price']) && $_GET['price'])
		{
			$_price = explode('-',$_GET['price']);
			$where .= ' AND a.shop_price BETWEEN '.$_price[0].' AND '.$_price[1];
		}
		// 根据属性搜索引
		if(isset($_GET['attr']) && $_GET['attr'])
		{
			$ga = M('GoodsAttr');
			// 循环每一个属性取出这个属性下的商品
			$_attr = explode('-', $_GET['attr']);
			// 用来标记是没有满足条件的商品还是传的属性值都是'0'
			$hasno = false;
			foreach ($_attr as $k => $v)
			{
				if($v == '0')
					continue;
				$hasno = TRUE;
				// 找出属性的id
				$_k = 0;
				foreach ($attrData as $k1 => $v1)
				{
					if($_k++ == $k)
					{
						$attr_id = $v1[0]['id'];
						break;
					}
				}
				// 取出这个属性值下的商品id
				if(!isset($_attr_goods))
				{
					$_attr_goods_1 = array();
					$_attr_goods = $ga->field('goods_id')->where("attr_value='$v' AND attr_id='$attr_id'")->select();
					foreach ($_attr_goods as $k1 => $v1)
					{
						$_attr_goods_1[] = $v1['goods_id'];
					}
				}
				else 
				{
					// 从第二次开始，每次和上一次的结果取交集
					$_attr_goods_ = $ga->field('goods_id')->where("attr_value='$v' AND attr_id='$attr_id'")->select();
					$_attr_goods__1 = array();
					foreach ($_attr_goods_ as $k1 => $v1)
					{
						$_attr_goods__1[] = $v1['goods_id'];
					}
					$_attr_goods_1 = array_intersect($_attr_goods_1, $_attr_goods__1);
				}
			}
			if($_attr_goods_1)
			{
				$_attr_goods_1 = implode(',', $_attr_goods_1);
				$where .= " AND a.id IN($_attr_goods_1)";
			}
			else 
				if($hasno)
					$where .= ' AND a.id=-1 ';
		}
		/**************** 排序 ********************/
		$_orderby = $orderby = 'shop_price';
		$orderway = 'desc';
		if(isset($_GET['orderway']) && in_array($_GET['orderway'], array('asc','desc')))
			$orderway = $_GET['orderway'];
		$fields = '';
		if(isset($_GET['orderby']) && in_array($_GET['orderby'], array('shop_price','addtime','xl','pl')))
		{
			$_orderby = $orderby = $_GET['orderby'];
			if($_orderby == 'addtime')
				$orderby = 'a.'.$_orderby;
			if($_GET['orderby'] == 'xl')
			{
				$fields = ",(SELECT SUM(c.goods_number) FROM sh_order_goods c LEFT JOIN sh_order d ON c.order_id=d.id WHERE c.goods_id=a.id AND d.pay_status='已支付' AND d.post_status='已收货') xl";
			}
		}
		/******************** 翻页 ***********************/
		// 总的记录数
		$totalRecords = $catModel->query("SELECT COUNT(id) c FROM sh_goods a WHERE $where");
		$totalRecords = $totalRecords[0]['c'];
		$perpage = 3;
		$totalPage = ceil($totalRecords/$perpage);
		$p = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
		$offset = ($p-1)*$perpage;
		// 翻页字符串
		$page = '';
		$curUrl = $_SERVER['REQUEST_URI'];
		$curUrl = preg_replace('/\/p\/\d/', '', $curUrl);
		for($i=1; $i<=$totalPage; $i++)
		{
			if($i == $p)
				$cur = 'class="cur"';
			else 
				$cur = '';
			$page .= "<a $cur href='$curUrl/p/$i#goods'>$i</a> ";
		}
		$sql = "SELECT a.id,a.goods_name,a.shop_price,a.mid_logo,COUNT(b.id) pl$fields
				 FROM sh_goods a 
				  LEFT JOIN sh_goods_remark b ON a.id=b.goods_id 
				   WHERE $where
		 		    GROUP BY a.id
				     ORDER BY $orderby $orderway
				      LIMIT $offset,$perpage";
		$goods = $catModel->query($sql);
		$this->assign(array(
			'goods' => $goods,
			'order' => $_orderby,
			'orderway' => $orderway,
			'catId' => $catId,
			'page' => $page,
			'totalPage' => $totalPage,
		));
		
		$this->display();
	}
}






















