// 如果传了goods_id那么会把这件存到历史记录中,如果没传就只是获取历史记录中的数据
function getHistory(goods_id)
{
	goods_id = goods_id | 0;
	$.ajax({
		type : "GET",
		url : "/index.php/Home/Index/ajaxHistory/goodsId/"+goods_id,
		dataType : "json",
		success : function(data)
		{
			var html = "";
			$(data).each(function(k,v){
				html += '<dl><dt><a href="/index.php/Home/Index/goods/id/'+v.goods_id+'"><img src="'+v.mid_logo+'" alt="'+v.goods_name+'" /></a></dt><dd><a href="/index.php/Home/Index/goods/id/'+v.goods_id+'">'+v.goods_name+'</a></dd></dl>';
			});
			$("#history").html(html);
		}
	});
}