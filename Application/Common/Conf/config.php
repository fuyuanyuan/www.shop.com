<?php
return array(
	'DEFAULT_MODULE'=>'Home',
	// 配置数据库
	'DB_TYPE' => 'mysql',
	'DB_HOST'=>'127.0.0.1',
	'DB_NAME'=>'shop2',
	'DB_USER'=>'root',
	'DB_PWD'=>'123456',
	'DB_PREFIX'=>'sh_',
	/*** 缓存相关配置 *****/
	'HTML_CACHE_ON' => FALSE,
	'HTML_CACHE_TIME' => 3600,
	'HTML_FILE_SUFFIX' => '.html',
	'HTML_CACHE_RULES' => array(
		'index:index' => 'index',
		'index:goods' => 'goods/{id|goods_dir}',
	),
);
function goods_dir($id)
{
	// 每100件商品放到一个目录中
	$dir = ceil($id / 100);
	return $dir.'/'.$id;
}