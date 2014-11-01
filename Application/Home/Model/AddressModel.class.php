<?php
namespace Home\Model;
use Think\Model;
class AddressModel extends Model 
{
	protected $_validate = array(
		array('shr_username', 'require', '收货人姓名不能为空！', 1),
		array('shr_province', 'require', '收货人省不能为空！', 1),
		array('shr_city', 'require', '收货人城市不能为空！', 1),
		array('shr_area', 'require', '收货人地区不能为空！', 1),
		array('shr_address', 'require', '收货人详细地址不能为空！', 1),
		array('shr_phone', 'require', '收货人手机不能为空！', 1),
	);
}