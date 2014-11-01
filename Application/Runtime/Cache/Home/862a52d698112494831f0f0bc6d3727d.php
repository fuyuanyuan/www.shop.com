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
	
	
	
<!-- 商品页面主体 start -->
	<div class="main w1210 mt10 bc">
		<!-- 面包屑导航 start -->
		<div class="breadcrumb">
			<h2>当前位置：<a href="">首页</a> > <a href="">电脑、办公</a> > <a href="">笔记本</a> > <?php echo $info['goods_name']; ?></h2>
		</div>
		<!-- 面包屑导航 end -->
		
		<!-- 主体页面左侧内容 start -->
		<div class="goods_left fl">
			<!-- 相关分类 start -->
			<div class="related_cat leftbar mt10">
				<h2><strong>相关分类</strong></h2>
				<div class="leftbar_wrap">
					<ul>
						<li><a href="">笔记本</a></li>
						<li><a href="">超极本</a></li>
						<li><a href="">平板电脑</a></li>
					</ul>
				</div>
			</div>
			<!-- 相关分类 end -->

			<!-- 相关品牌 start -->
			<div class="related_cat	leftbar mt10">
				<h2><strong>同类品牌</strong></h2>
				<div class="leftbar_wrap">
					<ul>
						<li><a href="">D-Link</a></li>
						<li><a href="">戴尔</a></li>
						<li><a href="">惠普</a></li>
						<li><a href="">苹果</a></li>
						<li><a href="">华硕</a></li>
						<li><a href="">宏基</a></li>
						<li><a href="">神舟</a></li>
					</ul>
				</div>
			</div>
			<!-- 相关品牌 end -->

			<!-- 热销排行 start -->
			<div class="hotgoods leftbar mt10">
				<h2><strong>热销排行榜</strong></h2>
				<div class="leftbar_wrap">
					<ul>
						<li>
						<?php foreach ($xl as $k => $v): ?>
							<dl>
								<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v['goods_id']; ?>"><img src="<?php echo IMG_URL . $v['goods_logo']; ?>" alt="<?php echo $v['goods_name']; ?>" /></a></dt>
								<dd><a href="/index.php/Home/Index/goods/id/<?php echo $v['goods_id']; ?>"><?php echo $v['goods_name']; ?></a></dd>
								<dd><strong>￥<?php echo $v['shop_price']; ?>元</strong></dd>
							</dl>
						<?php endforeach; ?>
						</li>
					</ul>
				</div>
			</div>
			<!-- 热销排行 end -->


			<!-- 浏览过该商品的人还浏览了  start 注：因为和list页面newgoods样式相同，故加入了该class -->
			<div class="related_view newgoods leftbar mt10">
				<h2><strong>浏览了该商品的用户还浏览了</strong></h2>
				<div class="leftbar_wrap">
					<ul>
						<?php foreach ($elseGoods as $k => $v): ?>
						<li>
							<dl>
								<dt><a href="/index.php/Home/Index/goods/id/<?php echo $v['id']; ?>"><img src="<?php echo IMG_URL . $v['mid_logo']; ?>" alt="<?php echo $v['goods_name']; ?>" /></a></dt>
								<dd><a href="/index.php/Home/Index/goods/id/<?php echo $v['id']; ?>"><?php echo $v['goods_name']; ?></a></dd>
								<dd><strong>￥<?php echo $v['shop_price']; ?>元</strong></dd>
							</dl>
						</li>
						<?php endforeach; ?>				
					</ul>
				</div>
			</div>
			<!-- 浏览过该商品的人还浏览了  end -->

			<!-- 最近浏览 start -->
			<div class="viewd leftbar mt10">
				<h2><a href="">清空</a><strong>最近浏览过的商品</strong></h2>
				<div class="leftbar_wrap" id="history"></div>
			</div>
			<!-- 最近浏览 end -->

		</div>
		<!-- 主体页面左侧内容 end -->
		
		<!-- 商品信息内容 start -->
		<div class="goods_content fl mt10 ml10">
			<!-- 商品概要信息 start -->
			<div class="summary">
				<h3><strong><?php echo $info['goods_name']; ?></strong></h3>
				
				<!-- 图片预览区域 start -->
				<div class="preview fl">
					<div class="midpic">
						<a href="<?php echo IMG_URL . $info['logo']; ?>" class="jqzoom" rel="gal1">   <!-- 第一幅图片的大图 class 和 rel属性不能更改 -->
							<img src="<?php echo IMG_URL . $info['big_logo']; ?>" alt="" />               <!-- 第一幅图片的中图 -->
						</a>
					</div>
	
					<!--使用说明：此处的预览图效果有三种类型的图片，大图，中图，和小图，取得图片之后，分配到模板的时候，把第一幅图片分配到 上面的midpic 中，其中大图分配到 a 标签的href属性，中图分配到 img 的src上。 下面的smallpic 则表示小图区域，格式固定，在 a 标签的 rel属性中，分别指定了中图（smallimage）和大图（largeimage），img标签则显示小图，按此格式循环生成即可，但在第一个li上，要加上cur类，同时在第一个li 的a标签中，添加类 zoomThumbActive  -->

					<div class="smallpic">
						<a href="javascript:;" id="backward" class="off"></a>
						<a href="javascript:;" id="forward" class="on"></a>
						<div class="smallpic_wrap">
							<ul>
								<li class="cur">
									<a class="zoomThumbActive" href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: '<?php echo IMG_URL . $info['big_logo']; ?>',largeimage: '<?php echo IMG_URL . $info['logo']; ?>'}"><img src="<?php echo IMG_URL . $info['sm_logo']; ?>"></a>
								</li>
								<?php foreach ($gpData as $k => $v): ?>
								<li>
									<a href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: '<?php echo IMG_URL . $v['big_logo']; ?>',largeimage: '<?php echo IMG_URL . $v['logo']; ?>'}"><img src="<?php echo IMG_URL . $v['sm_logo']; ?>"></a>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						
					</div>
				</div>
				<!-- 图片预览区域 end -->

				<!-- 商品基本信息区域 start -->
				<div class="goodsinfo fl ml10">
					<ul>
						<li><span>商品编号： </span><?php echo $info['goods_sn']; ?></li>
						<li class="market_price"><span>定价：</span><em>￥<?php echo $info['market_price']; ?>元</em></li>
						<li><span>本店价：</span> <strong>￥<?php echo $info['shop_price']; ?>元</strong> <a href="">(降价通知)</a></li>
						<li><span>上架时间：</span><?php echo $info['addtime']; ?></li>
						<li class="star star<?php echo $avg_star; ?>"><span>商品评分：</span> <strong></strong><a href="#remark">(已有<?php echo $total; ?>人评价)</a></li> <!-- 此处的星级切换css即可 默认为5星 star4 表示4星 star3 表示3星 star2表示2星 star1表示1星 -->
						<li class="shop_price"><span>会员价格：</span><strong>￥<font id="memberprice"><img src="/Public/images/loading.gif" /></font>元</strong></li>
					</ul>
					<form action="/index.php/Home/Cart/addToCart" method="post" class="choose">
						<input type="hidden" name="goods_id" value="<?php echo $info['id']; ?>" />
						<ul>
							<?php foreach ($gaData2 as $k => $v): ?>
							<li class="product">
								<dl>
									<dt><?php echo $k; ?>：</dt>
									<dd>
									<?php foreach ($v as $k1 => $v1): ?>
										<a <?php if($k1 == 0) echo 'class="selected"'; ?> href="javascript:;"><?php echo $v1['attr_value']; ?>
							<input type="radio" name="attrId[<?php echo $v1['attr_id']; ?>]" value="<?php echo $v1['id']; ?>" <?php if($k1 == 0) echo 'checked="checked"'; ?> />
										</a>
									<?php endforeach; ?>
									</dd>
								</dl>
							</li>
							<?php endforeach; ?>
							<li>
								<dl>
									<dt>购买数量：</dt>
									<dd>
										<a href="javascript:;" id="reduce_num"></a>
										<input type="text" name="amount" value="1" class="amount"/>
										<a href="javascript:;" id="add_num"></a>
									</dd>
								</dl>
							</li>

							<li>
								<dl>
									<dt>&nbsp;</dt>
									<dd>
										<input type="submit" value="" class="add_btn" />
									</dd>
								</dl>
							</li>

						</ul>
					</form>
				</div>
				<!-- 商品基本信息区域 end -->
			</div>
			<!-- 商品概要信息 end -->
			
			<div style="clear:both;"></div>

			<!-- 商品详情 start -->
			<a name="remark"></a>
			<div class="detail">
				<div class="detail_hd">
					<ul>
						<li class="first"><span>商品介绍</span></li>
						<li class="on"><span>商品评价</span></li>
						<li><span>售后保障</span></li>
					</ul>
				</div>
				<div class="detail_bd">
					<!-- 商品介绍 start -->
					<div class="introduce detail_div none">
						<div class="attr mt15">
							<ul>
								<?php foreach ($gaData1 as $k => $v): ?>
								<li><span><?php echo $v['attr_name']; ?>：</span><?php echo $v['attr_value']; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>

						<div class="desc mt10">
							<!-- 此处的内容 一般是通过在线编辑器添加保存到数据库，然后直接从数据库中读出 -->
							<?php echo $info['goods_desc']; ?>
						</div>
					</div>
					<!-- 商品介绍 end -->
					
					<!-- 商品评论 start -->
					<div class="comment detail_div mt10">
						<div class="comment_summary">
							<div class="rate fl">
								<strong><em><?php echo $hao; ?></em>%</strong> <br />
								<span>好评度</span>
							</div>
							<div class="percent fl">
								<dl>
									<dt>好评（<?php echo $hao; ?>%）</dt>
									<dd><div style="width:<?php echo $hao; ?>px;"></div></dd>
								</dl>
								<dl>
									<dt>中评（<?php echo $zhong; ?>%）</dt>
									<dd><div style="width:<?php echo $zhong; ?>px;"></div></dd>
								</dl>
								<dl>
									<dt>差评（<?php echo $cha; ?>%）</dt>
									<dd><div style="width:<?php echo $cha; ?>px;" ></div></dd>
								</dl>
							</div>
							<div class="buyer fl">
								<dl>
									<dt>买家印象：</dt>
									<?php foreach ($impData as $k => $v): ?>
									<dd><span><?php echo $v['title']; ?></span><em>(<?php echo $v['num']; ?>)</em></dd>
									<?php endforeach; ?>
								</dl>
							</div>
						</div>

						<div id="remark_container"></div>

						<!-- 分页信息 start -->
						<div class="page mt20" id="page"></div>
						<!-- 分页信息 end -->

						<!--  评论表单 start-->
						<div class="comment_form mt20">
							<form action="" id="comment_form">
								<input type="hidden" name="goods_id" value="<?php echo $info['id']; ?>" />
								<ul>
									<li>
										<label for=""> 评分：</label>
										<input checked="checked" type="radio"" value="5" name="star" /> <strong class="star star5"></strong>
										<input type="radio"" value="4" name="star"/> <strong class="star star4"></strong>
										<input type="radio"" value="3" name="star"/> <strong class="star star3"></strong>
										<input type="radio"" value="2" name="star"/> <strong class="star star2"></strong>
										<input type="radio"" value="1" name="star"/> <strong class="star star1"></strong>
									</li>

									<li>
										<label for="">评价内容：</label>
										<textarea name="content" id="" cols="" rows=""></textarea>
									</li>
									<li>
										<label for="">印象：</label>
										<input type="text" name="title" />
									</li>
									<li>
										<label for="">验证码：</label>
										<input type="text" name="checkcode" /><br />
										<img id="chkcode" style="cursor:pointer;" onclick="this.src='/index.php/Member/Index/getCode/'+Math.random();" src="/index.php/Member/Index/getCode" alt="" />
									</li>
									<li>
										<label for="">&nbsp;</label>
										<input type="button" id="btn_comment" value="提交评论"  class="comment_btn"/>										
									</li>
								</ul>
							</form>
						</div>
						<!--  评论表单 end-->
						
					</div>
					<!-- 商品评论 end -->

					<!-- 售后保障 start -->
					<div class="after_sale mt15 none detail_div">
						<div>
							<p>本产品全国联保，享受三包服务，质保期为：一年质保 <br />如因质量问题或故障，凭厂商维修中心或特约维修点的质量检测证明，享受7日内退货，15日内换货，15日以上在质保期内享受免费保修等三包服务！</p>
							<p>售后服务电话：800-898-9006 <br />品牌官方网站：http://www.lenovo.com.cn/</p>

						</div>

						<div>
							<h3>服务承诺：</h3>
							<p>本商城向您保证所售商品均为正品行货，京东自营商品自带机打发票，与商品一起寄送。凭质保证书及京东商城发票，可享受全国联保服务（奢侈品、钟表除外；奢侈品、钟表由本商城联系保修，享受法定三包售后服务），与您亲临商场选购的商品享受相同的质量保证。本商城还为您提供具有竞争力的商品价格和运费政策，请您放心购买！</p> 
							
							<p>注：因厂家会在没有任何提前通知的情况下更改产品包装、产地或者一些附件，本司不能确保客户收到的货物与商城图片、产地、附件说明完全一致。只能确保为原厂正货！并且保证与当时市场上同样主流新品一致。若本商城没有及时更新，请大家谅解！</p>

						</div>
						
						<div>
							<h3>权利声明：</h3>
							<p>本商城上的所有商品信息、客户评价、商品咨询、网友讨论等内容，是京东商城重要的经营资源，未经许可，禁止非法转载使用。</p>
							<p>注：本站商品信息均来自于厂商，其真实性、准确性和合法性由信息拥有者（厂商）负责。本站不提供任何保证，并不承担任何法律责任。</p>

						</div>
					</div>
					<!-- 售后保障 end -->

				</div>
			</div>
			<!-- 商品详情 end -->

			
		</div>
		<!-- 商品信息内容 end -->
		

	</div>
	<!-- 商品页面主体 end -->
	
<script type="text/javascript">
$(function(){
	$('.jqzoom').jqzoom({
	            zoomType: 'standard',
	            lens:true,
	            preloadImages: false,
	            alwaysOn:false,
	            title:false,
	            zoomWidth:400,
	            zoomHeight:400
	      });
})
// ajax获取会员价格
$("#memberprice").load('/index.php/Home/Index/ajaxGetMemberPrice/id/<?php echo $info['id']; ?>');
// AJAX提交评论
$("#btn_comment").click(function(){
	// 获取表单中所有的数据
	var data = $("#comment_form").serialize();
	if($.trim($("textarea[name=content]").val()) == "")
	{
		alert("内容不能为空！");
		return false;
	}
	if($.trim($("input[name=checkcode]").val()) == "")
	{
		alert("验证码不能为空！");
		return false;
	}
	$.ajax({
		type : "POST",
		url : "/index.php/Home/Index/ajaxComment",
		data : data,  // 把表单中的数据提交上去
		dataType : "json",
		success : function(data)
		{
			if(data.status == -1)
			{
				alert(data.message);
			}
			else
			{
				alert('评论成功！');
				document.getElementById("comment_form").reset();
				// 换一个验证码
				$("#chkcode").attr('src','/index.php/Member/Index/getCode/'+Math.random());
			}
		}
	});
});
// 制作 AJAX翻页获取评论的数据
function getRemark(page)
{
	$.ajax({
		type : "GET",
		url : "/index.php/Home/Index/ajaxGetRemark/p/"+page+"/goodsId/<?php echo $info['id']; ?>",
		dataType : "json",
		success : function(data)
		{
			var html = '';
			$(data.data).each(function(k,v){
				html += '<div class="comment_items mt10"><div class="user_pic"><dl><dt><a href="/index.php/Member/Member/index/id/'+v.member_id+'"><img src="<?php echo IMG_URL; ?>'+v.sm_logo+'" alt="'+v.username+'" /></a></dt><dd><a href="/index.php/Member/Member/index/id/'+v.member_id+'">'+v.username+'</a></dd></dl></div><div class="item"><div class="title"><span>'+v.addtime+'</span><strong class="star star'+v.star+'"></strong> <!-- star5表示5星级 start4表示4星级，以此类推 --></div><div class="comment_content">'+v.content+'</div></div><div class="cornor"></div></div>';
			});
			$("#remark_container").html(html);
			$("#page").html(data.page);
		}
	});
}
getRemark(1);
// 获取浏览历史并把当前商品存到浏览历史中
getHistory(<?php echo $info['id']; ?>);
</script>






















	
	
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