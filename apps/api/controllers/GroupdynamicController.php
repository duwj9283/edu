<?php

namespace Cloud\API\Controllers;

use Cloud\Models\Groupdynamic;
use Cloud\Models\Groupdynamiccomment;
use Cloud\Models\Groupdynamiclike;

class GroupdynamicController extends ControllerBase
{
	/**
	 * @brief 发布群组动态
	 * @param gid 群组ID
	 * @param content 内容
	 * @param attachments 附件 json格式
	 */
	public function createAction(){
		do{
			$this->validation->add('gid', new PresenceOf(array('message'=>'参数缺失:gid')));
			$this->validation->add('uid', new PresenceOf(array('message'=>'参数缺失:uid')));
			$this->validation->add('content', new PresenceOf(array('message'=>'参数缺失:content')));
			$this->validation->add('attachments', new PresenceOf(array('message'=>'参数缺失:attachments')));
			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			$gid = intval($this->request->getPost('gid'));
			$uid = intval($this->request->getPost('uid'));
			$content = $this->request->getPost('content');
			$attachments = json_decode($this->request->getPost('attachments'));

			$this->db->begin();
			$groupDynamic = new Groupdynamic();
			$groupDynamic->gid = $gid;
			$groupDynamic->uid = $uid;
			$groupDynamic->content = $content;
			$groupDynamic->add_time = time();

			if(!$groupDynamic->create()){
				$this->db->rollback();
				$this->logger->error("创建群动态失败=>". __LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

			$did = $groupDynamic->did;

			//	获取附件
			if(!empty($attachments)){
				$sql_values = array_map(function($attachment)use($did){
					return array($did, $attachment);
				}, $attachments);

				$sql = "INSERT INTO edu_group_dynamic_attachment(did,filename) VALUES(:sql_values)";
				$sth = $this->db->prepare($sql);
				$sth->bindParam(":sql_values", $sql_values);
				$bOK = $sth->execute();
				if(!$bOK){
					$this->db->rollback();
					$this->logger->error("添加附件出错");
					$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
					break;
				}
			}

			$this->db->commit();
		}while(false);
		$this->output();
	}

	/**
	 * @brief 赞群动态评论
	 * @param did 动态ID
	 */
	public function likeAction(){
		do{
			$this->validation->add('did', new PresenceOf(array('message'=>'参数缺失:did')));

			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			$did = intval($this->request->getPost('did'));
			$uid = $this->uid;

			$groupDynamic = Groupdynamic::findFirst(
				array(
					"condidtions"=>"did=$did"
				)
			);

			if(!$groupDynamic){
				$this->logger->error(__FILE__." [动态${did}不存在] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

			$bLike = Groupdynamiclike::findFirst(
				array(
					"conditions"=>"did=$did and uid=$uid"
				)
			);

			if($bLike){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"已经点过赞了","line"=>__LINE__,"data"=>array());
				break;
			}

			$groupDynamicLike = new Groupdynamiclike();
			$groupDynamicLike->did = $did;
			$groupDynamicLike->uid = $uid;

			$this->db->begin();
			if(!$groupDynamicLike->create()){
				$this->db->rollback();
				$this->logger->error(__FILE__." [用户赞出错] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

			$bOK = $this->db->query("UPDATE edu_group_dynamic set likeCounter=likeCounter+1 where did=$did limit 1");
			if(!$bOK){
				$this->db->rollback();
				$this->logger->error(__FILE__." [更新${did}赞总数出错] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}
			$this->db->commit();

		}while(false);
		$this->output();
	}

	/**
	 * @brief 取消赞
	 * @param did 动态ID
	 */
	public function unlikeAction(){
		do{
			$this->validation->add('did', new PresenceOf(array('message'=>'参数缺失:did')));

			$messages = $this->validation->validate($this->params);
			if( count($messages) ){
				foreach($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}

			$did = intval($this->request->getPost('did'));
			$uid = $this->uid;

			$groupDynamic = Groupdynamic::findFirst(
				array(
					"condidtions"=>"did=$did"
				)
			);

			if(!$groupDynamic){
				$this->logger->error(__FILE__." [动态${did}不存在] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

			$bLike = Groupdynamiclike::findFirst(
				array(
					"conditions"=>"did=$did and uid=$uid"
				)
			);

			if(!$bLike){
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"没有点过赞","line"=>__LINE__,"data"=>array());
				break;
			}

			$this->db->begin();
			$bOK = $this->db->query("DELETE from edu_group_dynamic where did=$did and uid=$uid limit 1");
			if(!$bOK){
				$this->db->rollback();
				$this->logger->error(__FILE__." [删除${uid} => ${did}赞记录出错] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

			$bOK = $this->db->query("UPDATE edu_group_dynamic set likeCounter=likeCounter-1 where did=$did limit 1");
			if(!$bOK){
				$this->db->rollback();
				$this->logger->error(__FILE__." [更新${did}赞总数出错] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}
			$this->db->commit();

		}while(false);
		$this->output();
	}

	/**
	 * @brief 对群动态进行评论
	 * @param did [int] 动态ID
	 * @param refcid [int]  回复的评论主题
	 * @param tuid [int] 回复的用户id
	 * @param content [string]  回复内容
	 */
	public function commentAction(){
		do {
			$this->validation->add('did', new PresenceOf(array('message' => '参数缺失:did')));
			$this->validation->add('tuid', new PresenceOf(array('message' => '参数缺失:tuid')));
			$this->validation->add('refcid', new PresenceOf(array('message' => '参数缺失:refcid')));
			$this->validation->add('content', new PresenceOf(array('message' => '参数缺失:content')));

			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code" => \Code::ERROR_PARAMS, "msg" => join(';', $this->errors), "line" => __LINE__, "data" => array());
				break;
			}

			$did = intval($this->request->getPost('did'));
			$tuid = intval($this->request->getPost('tuid'));
			$refcid = intval($this->request->getPost('refcid'));
			$content = $this->request->getPost('content');
			$uid = $this->uid;

			//	检查动态是否存在,被删除则不能评论
			$groupDynamic = Groupdynamic::findFirst(
				array(
					"condidtions"=>"did=$did"
				)
			);

			if(!$groupDynamic){
				$this->logger->error(__FILE__." [动态${did}不存在] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

			$groupDynamicComment = new Groupdynamiccomment();
			$groupDynamicComment->did = $did;
			$groupDynamicComment->tuid = $tuid;
			$groupDynamicComment->uid = $uid;
			$groupDynamicComment->refcid = $refcid;
			$groupDynamicComment->content = $content;
			$groupDynamicComment->add_time = time();

			if(!$groupDynamicComment->create()){
				$this->logger->error(__FILE__." [创建评论${content}出错] ".__LINE__);
				$this->responseData = array("code"=>\Code::ERROR,"msg"=>"数据异常","line"=>__LINE__,"data"=>array());
				break;
			}

		}while(false);
		$this->output();
	}

	/**
	 * @brief 删除评论(接口没做)
	 */
	public function deleteCommentAction(){

	}

}

