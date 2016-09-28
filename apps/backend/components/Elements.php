<?php
/**
 * Created by PhpStorm.
 * User: wangqijun
 * Date: 16/1/28
 * Time: 下午1:20
 */

namespace Cloud\Backend\Components;
use Phalcon\Mvc\User\Component;

/**
 * @brief 辅助生成html元素类
 */
class Elements extends Component
{
	//	侧边栏配置
	private $leftBarConfig = array(
		array('itemName'=>'直播管理','uri'=>'#', 'class'=>'fa fa-user', 'children'=>array(
			array('itemName'=>"一级类型管理", 'uri'=>'/backend/aa/level1type'),
			array('itemName'=>"二级类型管理", 'uri'=>'/backend/aa/level2type')
		)),
//		array('itemName'=>'例子','uri'=>'/backend/index/study', 'class'=>'fa fa-bar-chart-o', 'children'=>array()),
	);

	/**
	 * @return array
	 * @brief 生成左边导航栏,基于当前的uri,确定当前侧边栏的高亮情形
	 */
	public function getLeftBar()
	{
		$uri = $this->request->getURI();
		if(strlen($uri) <= strlen('/backend/') OR substr_count(rtrim($uri, "/"), "/") < 3){
			$uri = "/backend/index/index";
		}

		//	遍历查找应该高亮的元素及其父元素ID
		$firstLevelKey = null;
		$secondLevelKey = null;
		foreach($this->leftBarConfig as $level1Key => $item){
			$itemUri = $item['uri'];
			if($item['uri'] === '#'){
				foreach($item['children'] as $level2Key => $child){
					$childUri = $child['uri'];
					//	查看是否匹配
					if(substr($uri, 0, strlen($childUri)) === $childUri){
						$secondLevelKey = $level2Key;
						break;
					}
				}
			}
			else if(substr($uri, 0, strlen($itemUri)) === $itemUri){
				$firstLevelKey = $level1Key;
				break;
			}

			if(null !== $secondLevelKey){
				$firstLevelKey = $level1Key;
				break;
			}
		}

		$leftBar = "";
		foreach($this->leftBarConfig as $level1Key => $item){
			$itemName = $item['itemName'];
			$itemUri = $item['uri'];
			$itemClass = $item['class'];
			if($level1Key === $firstLevelKey){
				$leftBar .= "<li class='active'>";
			}
			else{
				$leftBar .= "<li>";
			}

			if( isset($item['children']) AND count($item['children']) > 0){
				//	带子菜单
				$leftBar .= "<a href='$itemUri'><i class='$itemClass'></i><span class='nav-label'>$itemName</span><span class='fa arrow'></span></a>";
				$leftBar .= "<ul class='nav nav-second-level collapse'>";
				foreach($item['children'] as $level2Key => $child){
					$childName = $child['itemName'];
					$childUri = $child['uri'];

					if($firstLevelKey === $level1Key AND $secondLevelKey === $level2Key){
						$leftBar .= "<li class='active'><a href='$childUri'>$childName</a></li>";
					}
					else{
						$leftBar .= "<li><a href='$childUri'>$childName</a></li>";
					}
				}
				$leftBar .= "</ul>";
				$leftBar .= "</li>";
			}
			else{
				$leftBar .= "<a href='$itemUri'><i class='$itemClass'></i><span class='nav-label'>$itemName</span></a>";
				$leftBar .= "</li>";
			}

		}
		return $leftBar;
	}
}