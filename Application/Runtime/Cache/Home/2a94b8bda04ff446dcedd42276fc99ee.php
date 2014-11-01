<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title><?php echo $title; ?></title>
	<meta name="keywords" content="<?php echo $keyword; ?>">
	<meta name="description" content="<?php echo $description; ?>">
	<link rel="stylesheet" href="/Public/style/base.css" type="text/css">
	<link rel="stylesheet" href="/Public/style/global.css" type="text/css">
	<link rel="stylesheet" href="/Public/style/header.css" type="text/css">
	<?php foreach ($css as $v): ?>
	<link rel="stylesheet" href="/Public/style/<?php echo $v; ?>.css" type="text/css">
	<?php endforeach; ?>
	<link rel="stylesheet" href="/Public/style/bottomnav.css" type="text/css">
	<link rel="stylesheet" href="/Public/style/footer.css" type="text/css">

	<script type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/Public/js/login.js"></script>
	<script type="text/javascript" src="/Public/js/header.js"></script>
	<?php foreach ($js as $v): ?>
	<script type="text/javascript" src="/Public/js/<?php echo $v; ?>.js"></script>
	<?php endforeach; ?>
</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w1210 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
					<li id="loginInfo"></li>
					<li class="line">|</li>
					<?php  $_count = count($btnData['top']); foreach ($btnData['top'] as $k => $v): ?>
					<li><a href="<?php echo $v['btn_link']; ?>"><?php echo $v['btn_name']; ?></a></li>
					<?php if($k+1 < $_count): ?>
					<li class="line">|</li>
					<?php endif; ?>
					<?php endforeach; ?>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>

	<!-- 头部 start -->
	<div class="header w1210 bc mt15">
		<!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
		<div class="logo w1210">
			<h1 class="fl"><a href="index.html"><img src="/Public/images/logo.png" alt="京西商城"></a></h1>
			<!-- 头部搜索 start -->
			<div class="search fl">
				<div class="search_form">
					<div class="form_left fl"></div>
					<form action="" name="serarch" method="get" class="fl">
						<input type="text" class="txt" value="请输入商品关键字" /><input type="submit" class="btn" value="搜索" />
					</form>
					<div class="form_right fl"></div>
				</div>
				
				<div style="clear:both;"></div>

				<div class="hot_search">
					<strong>热门搜索:</strong>
					<a href="">D-Link无线路由</a>
					<a href="">休闲男鞋</a>
					<a href="">TCL空调</a>
					<a href="">耐克篮球鞋</a>
				</div>
			</div>
			<!-- 头部搜索 end -->

			<!-- 用户中心 start-->
			<div class="user fl">
				<dl>
					<dt>
						<em></em>
						<a href="">用户中心</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt">
							您好，请<a href="">登录</a>
						</div>
						<div class="uclist mt10">
							<ul class="list1 fl">
								<li><a href="">用户信息></a></li>
								<li><a href="">我的订单></a></li>
								<li><a href="">收货地址></a></li>
								<li><a href="">我的收藏></a></li>
							</ul>

							<ul class="fl">
								<li><a href="">我的留言></a></li>
								<li><a href="">我的红包></a></li>
								<li><a href="">我的评论></a></li>
								<li><a href="">资金管理></a></li>
							</ul>

						</div>
						<div style="clear:both;"></div>
						<div class="viewlist mt10">
							<h3>最近浏览的商品：</h3>
							<ul>
								<li><a href=""><img src="/Public/images/view_list1.jpg" alt="" /></a></li>
								<li><a href=""><img src="/Public/images/view_list2.jpg" alt="" /></a></li>
								<li><a href=""><img src="/Public/images/view_list3.jpg" alt="" /></a></li>
							</ul>
						</div>
					</dd>
				</dl>
			</div>
			<!-- 用户中心 end-->

			<!-- 购物车 start -->
			<div class="cart fl">
				<dl>
					<dt>
						<a href="">去购物车结算</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt" id="cartInfo"></div>
					</dd>
				</dl>
			</div>
			<!-- 购物车 end -->
		</div>
		<!-- 头部上半部分 end -->
		
		<div style="clear:both;"></div>

		<!-- 导航条部分 start -->
		<div class="nav w1210 bc mt10">
			<!--  商品分类部分 start-->
			<div class="category fl <?php if(!isset($show_nav)) echo ' cat1'; ?>"> <!-- 非首页，需要添加cat1类 -->
				<div class="cat_hd <?php if(!isset($show_nav)) echo ' off'; ?>">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
					<h2>全部商品分类</h2>
					<em></em>
				</div>
				<div class="cat_bd <?php if(!isset($show_nav)) echo ' none'; ?>">
					<?php foreach ($navCatData as $k => $v): ?>
					<div class="cat <?php if($k==0) echo 'item1'; ?>">
						<h3><a href="/index.php/Home/Index/lst/catId/<?php echo $v['id']; ?>"><?php echo $v['cat_name']; ?></a> <b></b></h3>
						<div class="cat_detail">
							<?php foreach ($v['children'] as $k1 => $v1): ?>
							<dl <?php if($k1 == 0) echo 'class="dl_1st"'; ?>>
								<dt><a href="/index.php/Home/Index/lst/catId/<?php echo $v1['id']; ?>"><?php echo $v1['cat_name']; ?></a></dt>
								<dd>
								<?php foreach ($v1['children'] as $k2 => $v2): ?>
									<a href="/index.php/Home/Index/lst/catId/<?php echo $v2['id']; ?>"><?php echo $v2['cat_name']; ?></a>
								<?php endforeach; ?>
								</dd>
							</dl>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div>

			</div>
			<!--  商品分类部分 end--> 

			<div class="navitems fl">
				<ul class="fl">
					<li class="current"><a href="/index.php">首页</a></li>
					<?php foreach ($btnData['mid'] as $k => $v): ?>
					<li><a href="<?php echo $v['btn_link']; ?>"><?php echo $v['btn_name']; ?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="right_corner fl"></div>
			</div>
		</div>
		<!-- 导航条部分 end -->
	</div>
	<!-- 头部 end-->
	
	
	
<!-- 综合区域 start 包括幻灯展示，商城快报 -->
	<div class="colligate w1210 bc mt10">
		<!-- 幻灯区域 start -->
		<div class="slide fl">
			<div class="area">
				<?php echo $ad1; ?>
			</div>
		</div>
		<!-- 幻灯区域 end-->
	
		<!-- 快报区域 start-->
		<div class="coll_right fl ml10">
			<div class="ad"><?php echo $ad2; ?></div>
			
			<div class="news mt10">
				<h2><a href="">更多快报&nbsp;></a><strong>网站快报</strong></h2>
				<ul>
					<?php foreach ($news as $k => $v): ?>
					<li <?php if(($k+1)%2 != 0) echo 'class="odd"'; ?>><a href="/index.php/Home/Index/article/id/<?php echo $v['id']; ?>"><?php echo $v['title']; ?></a></li>
					<?php endforeach; ?>
				</ul>

			</div>
			
			<div class="service mt10">
				<h2>
					<span class="title1 on"><a href="">话费</a></span>
					<span><a href="">旅行</a></span>
					<span><a href="">彩票</a></span>
					<span class="title4"><a href="">游戏</a></span>
				</h2>
				<div class="service_wrap">
					<!-- 话费 start -->
					<div class="fare">
						<form action="">
							<ul>
								<li>
									<label for="">手机号：</label>
									<input type="text" name="phone" value="请输入手机号" class="phone" />
									<p class="msg">支持移动、联通、电信</p>
								</li>
								<li>
									<label for="">面值：</label>
									<select name="" id="">
										<option value="">10元</option>
										<option value="">20元</option>
										<option value="">30元</option>
										<option value="">50元</option>
										<option value="" selected>100元</option> 
										<option value="">200元</option>
										<option value="">300元</option>
										<option value="">400元</option>
										<option value="">500元</option>
									</select>
									<strong>98.60-99.60</strong>
								</li>
								<li>
									<label for="">&nbsp;</label>
									<input type="submit" value="点击充值" class="fare_btn" /> <span><a href="">北京青春怒放独家套票</a></span>
								</li>
							</ul>
						</form>
					</div>
					<!-- 话费 start -->
	
					<!-- 旅行 start -->
					<div class="travel none">
						<ul>
							<li>
								<a href=""><img src="/Public/images/holiday.jpg" alt="" /></a>
								<a href="" class="button">度假查询</a>
							</li>
							<li>
								<a href=""><img src="/Public/images/scenic.jpg" alt="" /></a>
								<a href="" class="button">景点查询</a>
							</li>
						</ul>
					</div>
					<!-- 旅行 end -->
						
					<!-- 彩票 start -->
					<div class="lottery none">
						<p><img src="/Public/images/lottery.jpg" alt="" /></p>
					</div>
					<!-- 彩票 end -->

					<!-- 游戏 start -->
					<div class="game none">
						<ul>
							<li><a href=""><img src="/Public/images/sanguo.jpg" alt="" /></a></li>
							<li><a href=""><img src="/Public/images/taohua.jpg" alt="" /></a></li>
							<li><a href=""><img src="/Public/images/wulin.jpg" alt="" /></a></li>
						</ul>
					</div>
					<!-- 游戏 end -->
				</div>
			</div>

		</div>
		<!-- 快报区域 end-->
	</div>
	<!-- -综合区域 end -->
	
	<div style="clear:both;"></div>

	<!-- 导购区域 start -->
	<div class="guide w1210 bc mt15">
		<!-- 导购左边区域 start -->
		<div class="guide_content fl">
			<h2>
				<span class="on">疯狂抢购</span>
				<span>热卖商品</span>
				<span>推荐商品</span>
				<span>新品上架</span>
				<span class="last">猜您喜欢</span>
			</h2>
			
			<div class="guide_wrap">
				<!-- 疯狂抢购 start-->
				<div class="crazy">
					<ul>
					<?php foreach ($goods1 as $k => $v): ?>
						<li>
							<dl>
								<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v['id']; ?>"><img src="<?php echo IMG_URL . $v['mid_logo']; ?>" alt="<?php echo $v['goods_name']; ?>" /></a></dt>
								<dd><a href=""><?php echo $v['goods_name']; ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo $v['shop_price']; ?>元</strong></dd>
							</dl>
						</li>
					<?php endforeach; ?>
					</ul>	
				</div>
				<!-- 疯狂抢购 end-->

				<!-- 热卖商品 start -->
				<div class="hot none">
					<ul>
						<?php foreach ($goods2 as $k => $v): ?>
						<li>
							<dl>
								<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v['id']; ?>"><img src="<?php echo IMG_URL . $v['mid_logo']; ?>" alt="<?php echo $v['goods_name']; ?>" /></a></dt>
								<dd><a href=""><?php echo $v['goods_name']; ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo $v['shop_price']; ?>元</strong></dd>
							</dl>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<!-- 热卖商品 end -->

				<!-- 推荐商品 atart -->
				<div class="recommend none">
					<ul>
						<?php foreach ($goods3 as $k => $v): ?>
						<li>
							<dl>
								<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v['id']; ?>"><img src="<?php echo IMG_URL . $v['mid_logo']; ?>" alt="<?php echo $v['goods_name']; ?>" /></a></dt>
								<dd><a href=""><?php echo $v['goods_name']; ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo $v['shop_price']; ?>元</strong></dd>
							</dl>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<!-- 推荐商品 end -->
			
				<!-- 新品上架 start-->
				<div class="new none">
					<ul>
						<?php foreach ($goods4 as $k => $v): ?>
						<li>
							<dl>
								<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v['id']; ?>"><img src="<?php echo IMG_URL . $v['mid_logo']; ?>" alt="<?php echo $v['goods_name']; ?>" /></a></dt>
								<dd><a href=""><?php echo $v['goods_name']; ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo $v['shop_price']; ?>元</strong></dd>
							</dl>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<!-- 新品上架 end-->

				<!-- 猜您喜欢 start -->
				<div class="guess none">
					<ul>
						<?php foreach ($goods5 as $k => $v): ?>
						<li>
							<dl>
								<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v['id']; ?>"><img src="<?php echo IMG_URL . $v['mid_logo']; ?>" alt="<?php echo $v['goods_name']; ?>" /></a></dt>
								<dd><a href=""><?php echo $v['goods_name']; ?></a></dd>
								<dd><span>售价：</span><strong> ￥<?php echo $v['shop_price']; ?>元</strong></dd>
							</dl>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<!-- 猜您喜欢 end -->

			</div>

		</div>
		<!-- 导购左边区域 end -->
		
		<!-- 侧栏 网站首发 start-->
		<div class="sidebar fl ml10">
			<h2><strong>网站首发</strong></h2>
			<div class="sidebar_wrap">
				<dl class="first">
					<dt class="fl"><a href=""><img src="/Public/images/viewsonic.jpg" alt="" /></a></dt>
					<dd><strong><a href="">ViewSonic优派N710 </a></strong> <em>首发</em></dd>
					<dd>苹果iphone 5免费送！攀高作为全球智能语音血压计领导品牌，新推出的黑金刚高端智能电子血压计，改变传统测量方式让血压测量迈入一体化时代。</dd>
				</dl>

				<dl>
					<dt class="fr"><a href=""><img src="/Public/images/samsung.jpg" alt="" /></a></dt>
					<dd><strong><a href="">Samsung三星Galaxy</a></strong> <em>首发</em></dd>
					<dd>电视百科全书，360°无死角操控，感受智能新体验！双核CPU+双核GPU+MEMC运动防抖，58寸大屏打造全新视听盛宴！</dd>
				</dl>
			</div>
			

		</div>
		<!-- 侧栏 网站首发 end -->
		
	</div>
	<!-- 导购区域 end -->
	
	<div style="clear:both;"></div>

	<!-- 循环顶级大类 -->
	<?php foreach ($catData as $k => $v): ?>
	<!--1F 电脑办公 start -->
	<div class="floor1 floor w1210 bc mt10">
		<!-- 1F 左侧 start -->
		<div class="floor_left fl">
			<!-- 商品分类信息 start-->
			<div class="cate fl">
				<h2><?php echo $v['cat_name']; ?></h2>
				<div class="cate_wrap">
					<ul>
						<?php foreach ($v['nsc'] as $k1 => $v1): ?>
						<li><a href="/index.php/Home/Index/lst/catId/<?php echo $v['id']; ?>"><b>.</b><?php echo $v1['cat_name']; ?></a></li>
						<?php endforeach; ?>
					</ul>
					<p><?php echo $adModel->showAd($v['pos_id1']); ?></p>
				</div>
				

			</div>
			<!-- 商品分类信息 end-->

			<!-- 商品列表信息 start-->
			<div class="goodslist fl">
				<h2>
					<span class="on">推荐商品</span>
					<?php foreach ($v['rsc'] as $k1 => $v1): ?>
						<span><?php echo $v1['cat_name']; ?></span>
					<?php endforeach; ?>
				</h2>
				<div class="goodslist_wrap">
					<div>
						<ul>
							<?php foreach ($v['goods'] as $k2 => $v2): ?>
							<li>
								<dl>
									<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v2['id']; ?>"><img src="<?php echo IMG_URL . $v2['mid_logo']; ?>" alt="<?php echo $v2['goods_name']; ?>" /></a></dt>
									<dd><a href="/index.php/Home/Index/goods/id/<?php echo $v2['id']; ?>"><?php echo $v2['goods_name']; ?></a></dd>
									<dd><span>售价：</span> <strong>￥<?php echo $v2['shop_price']; ?>元</strong></dd>
								</dl>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php  foreach ($v['rsc'] as $k1 => $v1): ?>
					<div class="none">
						<ul>
							<?php foreach ($v1['goods'] as $k2 => $v2): ?>
							<li>
								<dl>
									<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v2['id']; ?>"><img src="<?php echo IMG_URL . $v2['mid_logo']; ?>" alt="<?php echo $v2['goods_name']; ?>" /></a></dt>
									<dd><a href="/index.php/Home/Index/goods/id/<?php echo $v2['id']; ?>"><?php echo $v2['goods_name']; ?></a></dd>
									<dd><span>售价：</span> <strong>￥<?php echo $v2['shop_price']; ?>元</strong></dd>
								</dl>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- 商品列表信息 end-->
		</div>
		<!-- 1F 左侧 end -->
		
		<!-- 右侧 start -->
		<div class="sidebar fl ml10">
			<!-- 品牌旗舰店 start -->
			<div class="brand">
				<h2><a href="">更多品牌&nbsp;></a><strong>品牌旗舰店</strong></h2>
				<div class="sidebar_wrap">
					<ul>
					<?php foreach ($v['brands'] as $k1 => $v1): ?>
						<li><a href="<?php echo $v1['site']; ?>"><img src="<?php echo IMG_URL . $v1['logo']; ?>" alt="<?php echo $v1['brand_name']; ?>" /></a></li>
					<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<!-- 品牌旗舰店 end -->
			
			<!-- 分类资讯 start -->
			<div class="info mt10">
				<h2><strong>分类资讯</strong></h2>
				<div class="sidebar_wrap">
					<ul>
					<?php foreach ($v['news'] as $k1 => $v1): ?>
						<li><a href="/index.php/Home/Index/article/id/<?php echo $v1['id']; ?>"><b>.</b><?php echo $v1['title']; ?></a></li>
					<?php endforeach; ?>
					</ul>
				</div>
				
			</div>
			<!-- 分类资讯 end -->
			
			<!-- 广告 start -->
			<div class="ads mt10"><?php echo $adModel->showAd($v['pos_id2']); ?></div>
			<!-- 广告 end -->
		</div>
		<!-- 右侧 end -->

	</div>
	<!--1F 电脑办公 start -->
	<?php endforeach; ?>
	
	
	<div style="clear:both;"></div>
		<!-- 底部导航 start -->
	<div class="bottomnav w1210 bc mt10">
	<?php foreach ($help as $k => $v): ?>
		<div class="bnav<?php echo $k+1; ?>">
			<h3><b></b> <em><?php echo $v['cat_name']; ?></em></h3>
			<ul>
			<?php foreach ($v['articles'] as $k1 => $v1): ?>
				<li><a href="/index.php/Home/Index/article/id/<?php echo $v['id']; ?>"><?php echo $v1['title']; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>
	</div>
	<!-- 底部导航 end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt10">
		<p class="links">
			<?php  $_count = count($btnData['bottom']); foreach ($btnData['bottom'] as $k => $v): ?>
				<a href="<?php echo $v['btn_link']; ?>"><?php echo $v['btn_name']; ?></a>
				<?php if($k+1 < $_count) echo '|'; ?> 
			<?php endforeach; ?>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="/Public/images/xin.png" alt="" /></a>
			<a href=""><img src="/Public/images/kexin.jpg" alt="" /></a>
			<a href=""><img src="/Public/images/police.jpg" alt="" /></a>
			<a href=""><img src="/Public/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->

</body>
</html>