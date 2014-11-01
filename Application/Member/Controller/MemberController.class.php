<?php
namespace Member\Controller;
class MemberController extends \Layout\Controller\LayoutController 
{
	var $id;  // 当前会员的id
	public function __construct()
	{
		parent::__construct();
		/******* 验证是否登录 **************/
		$this->id = session('id');
		if(!$this->id)
		{
			// 把当前页面存到session中，这样在登录成功之后就又跳回来了
			session('returnUrl', $_SERVER['REQUEST_URI']);
			$this->error('必须先登录', U('Index/login'));
		}
	}
	public function order()
	{
		// 设置页面的信息
		$this->assign(array(
			'title' => '我的定单',
			'css' => array('home','order'),
			'js' => array('home'),
			'keyword' => '个人中心',
			'description' => '个人中心-我的定单',
		));
		$orderModel = M('Order');
		$ogModel = M('OrderGoods');
		// 取出总的记录数
		$count = $orderModel->where('member_id='.$this->id)->count();
		$page = new \Think\Page($count,15);
		// 获取翻页的字符串
		$pageStr = $page->show();
		$data = $orderModel->where('member_id='.$this->id)->limit($page->firstRow,$page->listRows)->select();
		require_once("./alipay/alipay.config.php");
		require_once("./alipay/lib/alipay_submit.class.php");
		 //支付类型
		        $payment_type = "1";
		        //必填，不能修改
		        //服务器异步通知页面路径
		        $notify_url = "http://www.shop.com/index.php/Home/Cart/respond";
		        //需http://格式的完整路径，不能加?id=123这类自定义参数
		
		        //页面跳转同步通知页面路径
		        $return_url = "http://www.shop.com/index.php/Home/Cart/order3";
		        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		
		        //卖家支付宝帐户
		        $seller_email = 'fortheday@126.com';
		        //必填
		// 循环每一个定单
		foreach ($data as $k => $v)
		{
			// 取出定单中商品的图片
			$data[$k]['logo'] = $ogModel->field('goods_id,goods_logo')->where('order_id='.$v['id'])->select();
			// 如果定单为未支付状态，并且使用支付宝那么就为这个定单生成支付宝的按钮
			if($v['pay_status'] = '未支付' && $v['pay_method'] == 1)
			{
		        $out_trade_no = $v['id'];
		        $subject = '标题';
		        $total_fee = $v['total_price'];
		        $body = '描述。。。';
		        $show_url = '';
		        $anti_phishing_key = '';
		        $exter_invoke_ip = '';
		        //非局域网的外网IP地址，如：221.0.0.1
		        $parameter = array(
						"service" => "create_direct_pay_by_user",
						"partner" => trim($alipay_config['partner']),
						"payment_type"	=> $payment_type,
						"notify_url"	=> $notify_url,
						"return_url"	=> $return_url,
						"seller_email"	=> $seller_email,
						"out_trade_no"	=> $out_trade_no,
						"subject"	=> $subject,
						"total_fee"	=> $total_fee,
						"body"	=> $body,
						"show_url"	=> $show_url,
						"anti_phishing_key"	=> $anti_phishing_key,
						"exter_invoke_ip"	=> $exter_invoke_ip,
						"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
				);
				$alipaySubmit = new \alipay\AlipaySubmit($alipay_config);
				$data[$k]['btn'] = $alipaySubmit->buildRequestForm($parameter,"get", "立即支付");
			}
		}
		$this->assign('data', $data);
		$this->assign('pageStr', $pageStr);
		$this->display();
	}
	public function cancel($id)
	{
		$model = M('Order');
		$ogModel = M('OrderGoods');
		$proModel = M('Product');
		// 取出定单的基本信息
		$model->field('member_id,pay_status')->find($id);
		if($model->member_id != $this->id)
			$this->error('参数无效！');
		if($model->pay_status != '未支付')
			$this->error('不能删除已支付的支付！');
		/************************* 退还库存量 ***********************/
		// 取出定单中所有的商品
		$goods = $ogModel->field('goods_id,goods_number,goods_attr_id')->where('order_id='.$id)->select();
		// 循环每一个商品退还库存量
		foreach ($goods as $k1 => $v1)
		{
			$proModel->where('goods_id='.$v1['goods_id'].' AND goods_attr_id="'.$v1['goods_attr_id'].'"')->setInc('goods_number', $v1['goods_number']);
		}
		/*********************** 从数据库中删除定单的记录 *******************/
		$model->delete($id);
		$ogModel->where('order_id='.$id)->delete();
		$this->success('操作成功！');
		exit;
	}
	public function received($id)
	{
		$model = M('Order');
		// 取出定单的基本信息
		$model->field('total_price,member_id,post_status')->find($id);
		if($model->member_id != $this->id || $model->post_status != '已发送')
			$this->error('参数无效！');
	
		$model->where('id='.$id)->save(array(
			'post_status' => '已收货',
		));
		
		// 为会员增加经验值和积分
		$model->execute("UPDATE sh_member SET jifen=jifen+{$model->total_price},jyz=jyz+{$model->total_price} WHERE id = {$this->id}");
		$this->success('操作成功！');
		exit;
	}
	public function refund($id)
	{
		$model = M('Order');
		// 取出定单的基本信息
		$model->field('total_price,member_id,post_status')->find($id);
		if($model->member_id != $this->id || $model->post_status != '已发送')
			$this->error('参数无效！');
		
		$model->where('id='.$id)->save(array(
			'post_status' => '退货中',
		));
		
		$this->success("操作成功！");
		exit;
	}
}