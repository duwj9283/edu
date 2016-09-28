<?php
namespace Cloud\API\Controllers;
use Cloud\Models\Lesson;
use Cloud\Models\Lessonstudy;
use Cloud\Models\Live;
use Cloud\Models\Mlesson;
use Cloud\Models\Mlessonstudy;
use Cloud\Models\Subject;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

use Cloud\Models\Userfile;
/**
 * @brief 个人空间/群组空间数据接口
 */
class ZoneController extends ControllerBase
{
	/**
	 * @brief 【个人空间】发布动态
	 * @param content
     * @param file_ids (一维数组)
	 */
	public function userPushActioin(){
		do{
			$this->validation->add('content', new PresenceOf(array('message'=>'参数缺失:content')));
            $this->validation->add('file_ids', new PresenceOf(array('message'=>'参数缺失:file_ids')));
			$messages = $this->validation->validate($this->params);
			if (count($messages)) {
				foreach ($messages as $message) {
					array_push($this->errors, strval($message));
				}
				$this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>join(';', $this->errors),"line"=>__LINE__,"data"=>array());
				break;
			}
			$uid = $this->uid;
			$content = $this->params['content'];
            $file_ids = $this->params['file_ids'];

            if(!empty($file_ids)&&!is_array($file_ids))
            {
                $this->responseData = array("code"=>\Code::ERROR_PARAMS,"msg"=>'file_ids必须为数组格式',"line"=>__LINE__,"data"=>array());
                break;
            }
			$userFollow = new Userfollow();

			if(!$userFollow->create()){
				$this->responseData = array("code"=>\Code::ERROR_DB,"msg"=>"关注失败","line"=>__LINE__,"data"=>array());
			}
		}while(false);
		$this->output();
	}


	/**
	 * @brief 个人空间资源列表
	 * @param (选填) uid
	 * @param（选填）page 当前页 默认是：1
	 */
	public function getUserZoneFileAction()
	{
		do{
			$uid = isset($this->params['uid'])&&(int)$this->params['uid']>0?$this->params['uid']:0;
			if($uid==0) {
				if ($this->checkUserLogin()) {
					$uid = $this->uid;
				}
				else
				{
					break;
				}
			}
			$page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
			$limit = 24;
			$offset = ($page - 1) * $limit;
			$files = Userfile::find(array("visible=1 and uid=$uid and file_status=".\FileStatus::NORMAL,"limit"=>$limit,"offset"=>$offset,"order"=>"addtime desc"));
			$data = $files->toArray();
			foreach($data as $k=>$v)
			{
				$data[$k] = $v;
				$data[$k]['sizeConv'] = $this->fileSizeConv($v['file_size']);
			}
			$this->responseData['data']['visibleFiles'] = $data;
			$fileCounter = Userfile::count(array("visible=1 and uid=$uid and file_status=".\FileStatus::NORMAL));
			$this->responseData['data']['total'] = $fileCounter;
		}while(false);
		$this->output();
	}

	/**
	 * @brief 个人空间课程列表
	 * @param (选填) uid
	 * @param（选填）page 当前页 默认是：1
	 */
	public function getUserZoneLessonAction()
	{
		do{
			$uid = isset($this->params['uid'])&&(int)$this->params['uid']>0?$this->params['uid']:0;
			if($uid==0) {
				if ($this->checkUserLogin()) {
					$uid = $this->uid;
				}
				else
				{
					break;
				}
			}
			$page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
			$limit = 24;
			$offset = ($page - 1) * $limit;
			$lessons = Lesson::find(array("uid=$uid","order"=>"addtime desc","offset"=>$offset,"limit"=>$limit));
			$lessonIds = array();
			$lesson_arr = array();
			$subjectIds = array();
			foreach($lessons as $lesson)
			{
				if(!in_array($lesson->subject_id,$subjectIds))
				{
					array_push($subjectIds,$lesson->subject_id);
				}
				array_push($lessonIds,$lesson->id);
				$lesson_arr[$lesson->id] = $lesson->toArray();
				$lesson_arr[$lesson->id]['study_count'] = 0;
			}
			if(!empty($subjectIds))
			{
				$subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
				$subjectArr = array();
				foreach($subjects as $subject)
				{
					$subjectArr[$subject->id] = $subject->subject_name;
				}
				foreach($lessons as $lesson)
				{
					$lesson_arr[$lesson->id]['subject_name'] = $subjectArr[$lesson->subject_id];
				}
			}
			if(!empty($lessonIds)) {
				//课程学习人数统计
				$sql = "select lesson_id,count(DISTINCT uid) as c from edu_lesson_study where lesson_id in (" . join(',', $lessonIds) . ")";
				$lessonStudy = new Lessonstudy();
				$lessonStudy = new Resultset(null, $lessonStudy, $lessonStudy->getReadConnection()->query($sql));
				foreach ($lessonStudy as $study) {
					if (!empty($study->lesson_id)) {
						$lesson_arr[$study->lesson_id]['study_count'] += $study->c;
					}
				}
			}
			$this->responseData['data']['lessonList'] = $lesson_arr;
		}while(false);
		$this->output();
	}

	/**
	 * @brief 个人空间微课列表
	 * @param (选填) uid
	 * @param（选填）page 当前页 默认是：1
	 */
	public function getUserZoneMlessonAction()
	{
		do{
			$uid = isset($this->params['uid'])&&(int)$this->params['uid']>0?$this->params['uid']:0;
			if($uid==0) {
				if ($this->checkUserLogin()) {
					$uid = $this->uid;
				}
				else
				{
					break;
				}
			}
			$page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
			$limit = 24;
			$offset = ($page - 1) * $limit;
			$mLessons = Mlesson::find(array("status=1 and uid=$uid","order"=>"addtime desc","offset"=>$offset,"limit"=>$limit));
			$mLessonArr = array();
			$subjectIds = array();
			foreach($mLessons as $mLesson)
			{
				if(!in_array($mLesson->subject_id,$subjectIds))
				{
					array_push($subjectIds,$mLesson->subject_id);
				}
				$studyCount = Mlessonstudy::count(array("m_lesson_id=".$mLesson->id));
				$mLessonArr[$mLesson->id] = $mLesson->toArray();
				$mLessonArr[$mLesson->id]['userInfo'] = $mLesson->getUserinfo()->toArray();
				$mLessonArr[$mLesson->id]['studyCount'] = $studyCount;
			}
			if(!empty($subjectIds))
			{
				$subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
				$subjectArr = array();
				foreach($subjects as $subject)
				{
					$subjectArr[$subject->id] = $subject->subject_name;
				}
				foreach($mLessons as $mLesson)
				{
					$mLessonArr[$mLesson->id]['subject_name'] = $subjectArr[$mLesson->subject_id];
				}
			}
			$this->responseData['data']['mlessonList'] = array_values($mLessonArr);
		}while(false);
		$this->output();
	}

	/**
	 * @brief 个人空间直播列表
	 * @param (选填) uid
	 * @param（选填）page 当前页 默认是：1
	 */
	public function getUserZoneLiveAction()
	{
		do{
			$uid = isset($this->params['uid'])&&(int)$this->params['uid']>0?$this->params['uid']:0;
			if($uid==0) {
				if ($this->checkUserLogin()) {
					$uid = $this->uid;
				}
				else
				{
					break;
				}
			}
			$page = isset($this->params['page'])&&(int)$this->params['page']>0?(int)$this->params['page']:1;
			$limit = 24;
			$offset = ($page - 1) * $limit;
			$lives = Live::find(array("publish_type=1 and uid=$uid","order"=>"start_time asc","offset"=>$offset,"limit"=>$limit));
			$liveArr = array();
			foreach ($lives as $live) {
				$liveArr[$live->id] = $live->toArray();
				if($live->start_time>date('Y-m-d H:i:s'))
				{
					$liveArr[$live->id]['live_status'] = 0;
				}
				else if($live->start_time<date('Y-m-d H:i:s')&&$live->end_time>date('Y-m-d H:i:s'))
				{
					$liveArr[$live->id]['live_status'] = 1;
				}
				else
				{
					$liveArr[$live->id]['live_status'] = 2;
				}
				$liveArr[$live->id]['userInfo'] = $live->getUserinfo()->toArray();
			}
			$this->responseData['data']['liveList'] = array_values($liveArr);
		}while(false);
		$this->output();
	}
}