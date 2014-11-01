<?php
namespace Layout\Controller;
use Think\Controller;
class LayoutController extends Controller 
{
	protected $catModel;  
	public function __construct()
	{
		parent::__construct();
		/******** 取页头和页脚的数据 **************/
		//1 取出导航条上的分类
		$this->catModel = new \Home\Model\CategoryModel();
		$navCatData = $this->catModel->getNavCat();
		$this->assign('navCatData', $navCatData);
		//2. 取出页脚上的帮助
		$newsModel = new \Home\Model\NewsModel();
		$help = $newsModel->getNavHelp();
		$this->assign('help', $help);
		//3. 按钮
		$btnModel = M('Button');
		$_btnData = $btnModel->select();
		$btnData = array();
		// 重新处理按钮根据位置分类
		foreach ($_btnData as $v) {
			$btnData[$v['btn_pos']][] = $v;
		}
		$this->assign('btnData', $btnData);
	}
}