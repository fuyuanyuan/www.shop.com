$.ajax({
	type : "GET",
	url : "/index.php/Member/Index/ajaxChkLogin",
	dataType : "json",
	success : function(data)
	{
		if(data.id != undefined)
		{
			$("#loginInfo").html("您好, <a href='/index.php/Member/Member/index/id/"+data.id+"'>"+data.username+"</a> <a href='/index.php/Member/Index/logout'>退出</a>");
		}
		else
		{
			$("#loginInfo").html("您好，欢迎来到京西！[<a href='/index.php/Member/Index/login'>登录</a>] [<a href='/index.php/Member/Index/regist'>免费注册</a>] ");
		}
		// 把购物车信息放进去
		$("#cartInfo").html("<a href='/index.php/Home/Cart/lst'>当前购物车中有 <strong>"+data.cartGoodsCount+"</strong> 件商品！</a>");
	}
});