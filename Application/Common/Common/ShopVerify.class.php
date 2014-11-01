<?php
namespace Common\Common;
class ShopVerify extends \Think\Verify
{
	public function check($code, $isDelete = TRUE, $id = '')
	{
        $key = $this->authcode($this->seKey).$id;
        // 验证码不能为空
        $secode = session($key);
        if(empty($code) || empty($secode)) {
            return false;
        }
        // session 过期
        if(NOW_TIME - $secode['verify_time'] > $this->expire) {
            session($key, null);
            return false;
        }

        if($this->authcode(strtoupper($code)) == $secode['verify_code']) {
        	if($isDelete)
           	 session($key, null);
            return true;
        }

        return false;
    }
    /* 加密验证码 */
    private function authcode($str){
        $key = substr(md5($this->seKey), 5, 8);
        $str = substr(md5($str), 8, 10);
        return md5($key . $str);
    }
}