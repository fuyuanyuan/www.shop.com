	var xml=null;
	$(document).ready(function(){
		$.ajax({
			url:'/Public/ChinaArea.xml',
			type:'get',
			dataTytpe:'xml',
			success:function(msg){
				xml=msg;
				var sheng=$(xml).find('province ');
				$('#sheng').append("<option value=0>请选择</option>");
				$(sheng).each(function(){
					var name=$(this).attr('province');
					var id=$(this).attr('provinceID');
					$('#sheng').append("<option aid="+id+" value="+name+">"+name+"</option>");
				})
			}
		});
	});
	function pca_city()
	{
		var parent_id=$('#sheng').find("option:selected").attr("aid");
		parent_id=parent_id.substr(0,2);
		//alert(parent_id);
		var city=$(xml).find('City[CityID^='+parent_id+']');
		//console.log(city);
		$('#city').empty();
		$('#city').append("<option value=0>请选择</option>");
		$(city).each(function(){
			var name=$(this).attr('City');
			var id=$(this).attr('CityID');
			$('#city').append("<option aid="+id+" value="+name+">"+name+"</option>");
		});
	}
	function pca_area(){
		var parent_id=$('#city').find("option:selected").attr("aid");
		parent_id=parent_id.substr(0,4);
		//alert(parent_id);
		var area=$(xml).find('Piecearea[PieceareaID^='+parent_id+']');
		//console.log(area);
		$('#area').empty();
		$('#area').append("<option value=0>请选择</option>");
		$(area).each(function(){
			var name=$(this).attr('Piecearea');
			var id=$(this).attr('PieceareaID');
			$('#area').append("<option aid="+id+" value="+name+">"+name+"</option>");
		})
	}