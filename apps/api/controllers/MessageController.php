<?php

namespace Cloud\API\Controllers;

use Cloud\Models\Messagestatus;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class MessageController extends ControllerBase
{
	/**
	 * @brief 获得我的站内信
	 * @param （选填）page 当前页 默认是：1
	 * message 表中的 send_status 作用： 0-已删除 1-垃圾箱 2-草稿箱 3-已发送
	 * message_status 表中的 view_status 作用：0-未查看 1-已查看
	 * message_status 表中的 status 作用：0-垃圾箱 1-收件箱
	 */
	public function getMyMessageAction(){
		do {
			$page = (int)$this->params['page']>0?$this->params['page']:1;
			if(!$this->checkUserLogin())
			{
				break;
			}
			$uid = $this->uid;
			$counter = 12;  //默认单次返回的数据数量
			$start = ($page-1)*$counter;
			$userMessages = Messagestatus::find(array("receiver_id=$uid and status=1","order"=>"created_at desc",'limit'=>$counter,'offset'=>$start));
			$messageArr = array();
			foreach ($userMessages as $k=>$userMessage) {
				$messageInfo = $userMessage->getMessage();
				if($messageInfo->send_status==3)
				{
					$messageArr[$k] = $userMessage->toArray();
					$messageArr[$k]['message_info'] = $messageInfo->toArray();
				}
			}
			$this->responseData['data']['messageList'] = $messageArr;
		} while (false);
		$this->output();
	}

	/**
	 * @brief 点击查看我的消息
	 * @param message_id
	 */
	public function viewMyMessageAction()
	{
		do {
			$this->validation->add('message_id', new PresenceOf(array('message'=>'参数缺失:message_id')));
			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}
			if(!$this->checkUserLogin())
			{
				break;
			}
			$uid = $this->uid;
			$message_id = (int)$this->params['message_id'];

			$userMessage = Messagestatus::findFirst(array("receiver_id=$uid and message_id=$message_id"));
			if($userMessage&&$userMessage->view_status==0)
			{
				$userMessage->view_status=1;
				$userMessage->update();
			}
		} while (false);
		$this->output();
	}
}

