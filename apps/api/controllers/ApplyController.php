<?php

namespace Cloud\API\Controllers;

use Cloud\Models\Capacityapply;
class ApplyController extends ControllerBase
{
	/**
	 * @brief 提升容量申请
	 * @param aid [int] 申请ID号
	 * @param uid [int] 用户ID
	 */
	public function capacityAction(){
		do {
			$this->validation->add('uid', new PresenceOf(array('message' => '参数缺失:uid')));
			$this->validation->add('reason', new PresenceOf(array('message' => '参数缺失:reason')));
			$this->validation->add('apply_capacity', new PresenceOf(array('message' => '参数缺失:apply_capacity')));

			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code" => \Code::ERROR_PARAMS, "msg" => join(';', $this->errors), "line" => __LINE__, "data" => array());
				break;
			}

			$apply_capacity = intval($this->request->getPost('apply_capacity'));
			$reason = $this->request->getPost('reason');
			$uid = $this->uid;

			$applyStatus = \CapacityApplyStatus::APPLY;
			$apply = Capacityapply::findFirst(
				array(
					"conditions"=>"uid=$uid and status=$applyStatus"
				)
			);

			if($apply){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"你的申请正在审核中,请勿重复操作","line"=>__LINE__,"data"=>array());
				break;
			}

			$apply = new Capacityapply();
			$apply->uid = $uid;
			$apply->reason = $reason;
			$apply->add_time = time();
			$apply->apply_capacity = $apply_capacity;
			$apply->proc_time = 0;

			if(!$apply->create()){
				$this->logger->error(__FILE__." [申请提升容量失败] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

		}while(false);
		$this->output();
	}

	/**
	 * @brief 同意提升容量
	 * @param aid [int]  申请ID
	 */
	public function acceptCapacityAction(){

		do {
			$this->validation->add('aid', new PresenceOf(array('message' => '参数缺失:aid')));

			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code" => \Code::ERROR_PARAMS, "msg" => join(';', $this->errors), "line" => __LINE__, "data" => array());
				break;
			}

			$aid = $this->request->getPost('aid');

			$apply = Capacityapply::findFirst(array("conditions"=>"aid=$aid"));
			if(!$apply){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"申请不存在","line"=>__LINE__,"data"=>array());
				break;
			}

			$apply->status = \CapacityApplyStatus::ACCEPT;
			if(!$apply->save()){
				$this->logger->error(__FILE__." [同意提升${aid}容量失败] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}
		}while(false);
	}

	/**
	 * @brief 拒绝提升容量
	 * @param aid [int]  申请ID
	 */
	public function rejectCapacityAction(){

		do {
			$this->validation->add('aid', new PresenceOf(array('message' => '参数缺失:aid')));

			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code" => \Code::ERROR_PARAMS, "msg" => join(';', $this->errors), "line" => __LINE__, "data" => array());
				break;
			}

			$aid = $this->request->getPost('aid');
			$apply = Capacityapply::findFirst(array("conditions"=>"aid=$aid"));
			if(!$apply){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"申请不存在","line"=>__LINE__,"data"=>array());
				break;
			}

			$apply->status = \CapacityApplyStatus::REJECT;
			if(!$apply->save()){
				$this->logger->error(__FILE__." [拒绝提升${aid}容量失败] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}
		}while(false);
		$this->output();
	}
}

