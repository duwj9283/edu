<?php

namespace Cloud\Frontend\Controllers;
use Cloud\Models\Live;
use Cloud\Models\Newsinfo;
use Cloud\Models\Subject;
use Cloud\Models\Userfile;
use Cloud\Models\Userfilepush;
use Cloud\Models\Lesson;
use Cloud\Models\Mlesson;
use Cloud\Models\Userfollow;
use Cloud\Models\Userinfo;
use Cloud\Models\Mlessonstudy;
use Cloud\Models\Lessonstudy;
use Cloud\Models\Siteconfig;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class IndexController extends ControllerBase
{
	public function indexAction()
	{
		//幻灯片
		$siteConfig = Siteconfig::findFirst(array("option_title='site_banner1'"));
		$this->view->images = explode('|',$siteConfig->option_value);
		$data = array();	//数据容器
		//公告
		$news = Newsinfo::find(array("class_id=10011001 and status=1","order"=>"updated_at desc",'limit'=>8));
		$data['newsList'] = $news->toArray();
		//直播课堂
		$where_1 = "publish_type=1 and NOW()>start_time and end_time>NOW()";//直播中
		$where_2 = "publish_type=1 and start_time > NOW()";//即将直播
		$where_3 = "publish_type=1 and end_time < NOW()";//已结束
		$live_limit = 4;
		$live_1 = Live::find(array($where_1,"order"=>"start_time desc",'limit'=>$live_limit));//直播中总数
		$live_2 = Live::find(array($where_2,"order"=>"start_time asc",'limit'=>$live_limit)); //即将直播总数
		$live_3 = Live::find(array($where_3,"order"=>"end_time desc",'limit'=>$live_limit)); //已结束总数
		$liveArr = array();
		foreach ($live_1 as $live) {
			$liveArr[$live->id] = $live->toArray();
			$liveArr[$live->id]['live_status'] = 1;
			$liveArr[$live->id]['userInfo'] = $live->getUserinfo()->toArray();
		}
		foreach ($live_2 as $live) {
			if(count($liveArr)<4)
			{
				$liveArr[$live->id] = $live->toArray();
				$liveArr[$live->id]['live_status'] = 0;
				$liveArr[$live->id]['userInfo'] = $live->getUserinfo()->toArray();
			}
		}
		foreach ($live_3 as $live) {
			if(count($liveArr)<4)
			{
				$liveArr[$live->id] = $live->toArray();
				$liveArr[$live->id]['live_status'] = 2;
				$liveArr[$live->id]['userInfo'] = $live->getUserinfo()->toArray();
			}
		}
		$data['liveList'] = array_values($liveArr);
		//资源中心
		$pushFiles = Userfilepush::find(array("status=1","order"=>"addtime desc,id desc",'limit'=>8));
		$pushFileArr = array();
		$subjectIds = array();
		foreach($pushFiles as $pushFile)
		{
			if(!in_array($pushFile->subject_id,$subjectIds))
			{
				array_push($subjectIds,$pushFile->subject_id);
			}
			$pushFileArr[$pushFile->user_file_id]['file_id'] = $pushFile->user_file_id;
			$pushFileArr[$pushFile->user_file_id]['push_file_name'] = $pushFile->push_file_name;
			$dataSuffix = strrchr($pushFile->push_file_name, '.');
			$type = 'other';
			if($dataSuffix===".pptx" || $dataSuffix===".ppt"){
				$type='ppt';
			}
			if($dataSuffix===".docx" || $dataSuffix===".doc"){
				$type='word';
			}
			if($dataSuffix===".xlsx" || $dataSuffix===".xls"){
				$type='xls';
			}
			if($dataSuffix===".txt"){
				$type='txt';
			}
			if($dataSuffix===".pdf"){
				$type='pdf';
			}
			if($dataSuffix===".rar"){
				$type='rar';
			}
			if($dataSuffix===".zip"){
				$type='zip';
			}
			if($dataSuffix===".xml"){
				$type='xml';
			}
			$pushFileArr[$pushFile->user_file_id]['ext'] = $type;
			$pushFileArr[$pushFile->user_file_id]['push_date_folder'] = $pushFile->push_date_folder;
			$pushFileArr[$pushFile->user_file_id]['file_type'] = $pushFile->file_type;
			$pushFileArr[$pushFile->user_file_id]['addtime'] = $pushFile->addtime;
			$pushFileArr[$pushFile->user_file_id]['userInfo'] = $pushFile->getUserInfo()->toArray();
		}
		if(!empty($subjectIds))
		{
			$subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
			$subjectArr = array();
			foreach($subjects as $subject)
			{
				$subjectArr[$subject->id] = $subject->subject_name;
			}
			foreach($pushFiles as $pushFile)
			{
				$pushFileArr[$pushFile->user_file_id]['subject_name'] = $subjectArr[$pushFile->subject_id];
			}
		}
		$data['fileList'] = array_values($pushFileArr);

		//精品微课
		$mLessons = Mlesson::find(array("status=1","order"=>"addtime desc","limit"=>4));
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
		$data['mlessonList'] = array_values($mLessonArr);

		//在线课程
		$lessons = Lesson::find(array("status=1","order"=>"push_time desc",'limit'=>4));
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
			$lesson_arr[$lesson->id]['userInfo'] = $lesson->getUserInfo()->toArray();
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
		if(!empty($lessonIds))
		{
			//课程学习人数统计
			$sql = "select lesson_id,count(DISTINCT uid) as c from edu_lesson_study where lesson_id in (".join(',',$lessonIds).")";
			$lessonStudy = new Lessonstudy();
			$lessonStudy = new Resultset(null, $lessonStudy, $lessonStudy->getReadConnection()->query($sql));
			foreach($lessonStudy as $study)
			{
				if(!empty($study->lesson_id))
				{
					$lesson_arr[$study->lesson_id]['study_count'] += $study->c;
				}
			}
		}
		$data['lessonList'] = array_values($lesson_arr);

		//名师空间
		$userInfos = UserInfo::find(array("role_id=2","order"=>"follow_count desc","limit"=>4));
		$userInfoArr = array();
		$subjectIds = array();
		foreach($userInfos as $k=>$userInfo)
		{
			if(!in_array($userInfo->subject,$subjectIds))
			{
				array_push($subjectIds,$userInfo->subject);
			}
			$userInfoArr[$k]['userInfo'] = $userInfo->toArray();
		}
		if(!empty($subjectIds))
		{
			$subjects = Subject::find(array('id in ('.join(',',$subjectIds).')'));
			$subjectArr = array();
			foreach($subjects as $subject)
			{
				$subjectArr[$subject->id] = $subject->subject_name;
			}
			foreach($userInfos as $k=>$userInfo)
			{
				$userInfoArr[$k]['subject_name'] = $subjectArr[$userInfo->subject];
			}
		}
		$data['userZoneList'] = array_values($userInfoArr);

		//资源贡献榜
		$maxPushusers = UserInfo::find(array("role_id=2","order"=>"push_file_count desc","limit"=>8));
		$data['maxPushUserList'] = $maxPushusers->toArray();

		//热门资源
		$allPushFiles = Userfilepush::find(array("status=1"));
		$pushFileIds = array();
		$maxDownloadFileNames = array();
		$files = array();
		foreach($allPushFiles as $allPushFile)
		{
			array_push($pushFileIds,$allPushFile->user_file_id);
			$maxDownloadFileNames[$allPushFile->user_file_id] = $allPushFile->push_file_name;
		}
		if(!empty($pushFileIds))
		{
			$userFiles = Userfile::find(array("id in (".join(',',$pushFileIds).")","order"=>"download_count desc","limit"=>8));
			foreach($userFiles as $userFile)
			{
				$files[$userFile->id]['file_id'] = $userFile->id;
				$files[$userFile->id]['file_name'] = $maxDownloadFileNames[$userFile->id];
				$files[$userFile->id]['download_count'] = $userFile->download_count;
			}
		}
		$data['maxDownloadFiles'] = array_values($files);
		$this->view->data=$data;
	}
}

