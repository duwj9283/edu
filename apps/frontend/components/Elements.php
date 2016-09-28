<?php
/**
 * Created by PhpStorm.
 * User: wangqijun
 * Date: 16/1/28
 * Time: 下午1:20
 */

namespace Cloud\Frontend\Components;
use Phalcon\Mvc\User\Component;

/**
 * @brief 辅助生成html元素类
 */
class Elements extends Component
{
	//	顶部配置
	private $topBar = array('/frontend/index/index'=>'首页',
							'e'=>'资源',
							'/frontend/aa/index'=>'直播',
							'a'=>'课程',
							'b'=>'资讯',
							'c'=>'群组',
							'd'=>'微课');

	/**
	 * @return array
	 * @brief 生成头部导航栏
	 */
	public function getHeaderNav()
	{
		$uri = $this->request->getURI();
		if(strlen($uri) <= strlen('/frontend/') OR substr_count(rtrim($uri, "/"), "/") < 3){
			$uri = "/frontend/index/index";
		}

		$topBar = "";
		foreach($this->topBar as $link => $name){
			if(substr($uri, 0, strlen($link)) === $link){
				$topBar .= "<li class='header_option active'><a href='#'>$name</a></li>";
			}
			else{
				$topBar .= "<li><a href='$link'>$name</a></li>";
			}
			$topBar .=" <li><a>|</a></li>";
		}

		return $topBar;
	}
}