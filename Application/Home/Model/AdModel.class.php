<?php
namespace Home\Model;
use Think\Model;
class AdModel extends Model 
{
	public function showAd($posId)
	{
		if($posId == 0)
			return NULL;
		// 取出广告位的信息
		$apModel = M('AdPos');
		$apModel->find($posId);
		// 取出这个位置上启用的广告
		$this->where("is_on='是' AND pos_id=$posId")->find();
		// 判断广告类型返回不同的结果
		if($this->ad_type == 'jq')
		{
			// 为UL的ID做一个序号
			static $_i = 0;
			// 取出JQ广告所有的图片
			$jqModel = M('JqInfo');
			$jqData = $jqModel->where('ad_id='.$this->id)->select();
			$html = "<div style='width:{$apModel->pos_width}px;height:{$apModel->pos_height}px;overflow:hidden;position:relative;'>";
			// 计算JQ图片的数量
			$_count = count($jqData);
			$ulWidth = $_count * $apModel->pos_width;
			$html .= "<ul id='ad_jq_ui_$_i' style='width:{$ulWidth}px;left:0px;position:absolute;'>";
			foreach ($jqData as $k => $v)
			{
				$html .= "<li style='width:{$apModel->pos_width}px;list-style-type:none;float:left'><a href='{$v['ad_link']}'><img src='".IMG_URL . $v['img']."' /></a></li>";
			}
			$html .= '</ul>';
			// 循环出右下角的数字
			$html .= '<ul id="ad_jq_ui_num_'.$_i.'" style="position:absolute;right:20px;bottom:20px;">';
			for ($i=1; $i<=$_count; $i++)
			{
				if($i==1)
					$color = 'c30';
				else 
					$color = '999';
				$html .= '<li style="cursor:pointer;float:left;-webkit-border-radius:12px;background:#'.$color.';width:22px;height:22px;line-height:22px;text-align:center;margin:2px;color:#FFF;">'.$i.'</li>';
			}
			$html .= '</ul>';
			$html .= "</div>";
			$html .=<<<JS
		<script>
		(function(){
		// 广告位的宽
		var width = $apModel->pos_width;
		// 图片的数量
		var imgCount = $_count;
		// 图片切换的间隔
		var seconds = 5000;  // 5秒
		var ul = $("#ad_jq_ui_$_i");
		var li_num = $("ul#ad_jq_ui_num_$_i li");
		var i = 1;
		var ti = setInterval(function(){
			// 实现图片的移动,1000代表这个移动在1秒内完成
			ul.animate({
				left:-(i*width)+"px"
			}, 1000);
			// 让数字也动起来
			li_num.css("backgroundColor","#999");
			li_num.eq(i).css("backgroundColor","#c30");
			if(++i==imgCount)
				i = 0;
		}, seconds);
		// 鼠标放到数字上停止定时器
		li_num.mouseover(function(){
			clearInterval(ti);
			// 取出鼠标放的是第几个
			i = $(this).index();
			// 移动到鼠标所在的数字的对应图片上
			ul.animate({
				left:-(i*width)+"px"
			}, 1000);
			// 让数字也动起来
			li_num.css("backgroundColor","#999");
			li_num.eq(i).css("backgroundColor","#c30");
		});
		// 鼠标离开重新开始定时器
		li_num.mouseout(function(){
			// 从下一个再开始
			if(++i==imgCount)
				i = 0;
			ti = setInterval(function(){
				ul.animate({
					left:-(i*width)+"px"
				}, 1000);
				// 让数字也动起来
				li_num.css("backgroundColor","#999");
				li_num.eq(i).css("backgroundColor","#c30");
				if(++i==imgCount)
					i = 0;
			}, seconds);
		});
		})();
		</script>	
JS;
			$_i++;
			return $html;
		}
		else if($this->ad_type == 'img')
		{
			return "<a href='{$this->ad_link}'><img src='".IMG_URL.$this->ad_img."' /></a>";
		}
		else if ($this->ad_type == 'text')
		{
			return "<a href='{$this->ad_link}'>{$this->ad_text}</a>";
		}
		else 
		{
			// 如果是代码广告那么直接回返代码
			return $this->ad_text;
		}
	}
}