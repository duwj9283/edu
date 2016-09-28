<?php

namespace Cloud\Frontend\Controllers;
use Cloud\Models\Userfilepush;
use Cloud\Models\Siteconfig;
use Cloud\Models\Subject;
use Cloud\Models\Userfiledownload;
use Cloud\Models\Userfileinfo;

class ResourceController extends ControllerBase
{

    public function indexAction($fatherSubject,$sunSubject,$type)
    {
        $pushFiles = Userfilepush::find(array("file_type>0 and status=1","order"=>"addtime desc,id desc",'limit'=>12,'offset'=>0));
        $fileIds = array();
        $pushFileArr = array();
        foreach($pushFiles as $pushFile)
        {
            array_push($fileIds,$pushFile->user_file_id);
            $userIds = array();
            if(!in_array($pushFile->uid,$userIds))
            {
                array_push($userIds,$pushFile->uid);
            }
            $pushFileArr[$pushFile->user_file_id]['file_id'] = $pushFile->user_file_id;
            $pushFileArr[$pushFile->user_file_id]['push_file_name'] = $pushFile->push_file_name;
            $pushFileArr[$pushFile->user_file_id]['push_date_folder'] = $pushFile->push_date_folder;
            $pushFileArr[$pushFile->user_file_id]['file_type'] = $pushFile->file_type;
            $pushFileArr[$pushFile->user_file_id]['subject_id'] = $pushFile->subject_id;
            $pushFileArr[$pushFile->user_file_id]['addtime'] = $pushFile->addtime;
            $pushFileArr[$pushFile->user_file_id]['userInfo'] = $pushFile->getUserInfo()->toArray();
            $pushFileArr[$pushFile->user_file_id]['like'] = 0;
            $pushFileArr[$pushFile->user_file_id]['download'] = 0;
        }
        if(!empty($fileIds))
        {
            //下载数量统计
            $fileDownloads = Userfiledownload::find(array("user_file_id in(".join(',',$fileIds).")"));
            foreach($fileDownloads as $fileDownload)
            {
                $pushFileArr[$fileDownload->user_file_id]['download']++;
            }
            $userFileInfos = Userfileinfo::find(array("user_file_id in(".join(',',$fileIds).")"));
            foreach($userFileInfos as $userFileInfo)
            {
                $pushFileArr[$userFileInfo->user_file_id]['file_desc'] = $userFileInfo->desc;
            }
        }
        $pushFileCounter = Userfilepush::count(array("file_type>0 and status=1"));
        $subject = Subject::find(array("father_id=0 and visible=1","order"=>"id asc"));
        $this->view->subjects = $subject;
        $this->view->userFiles = $pushFileArr;
        $this->view->allFileCounter = $pushFileCounter;
        $this->view->fatherSubject = $fatherSubject;
        $this->view->sunSubject = $sunSubject;
        $this->view->type = $type;

        //幻灯片
        $siteConfig = Siteconfig::findFirst(array("option_title='site_banner2'"));
        $this->view->images = explode('|',$siteConfig->option_value);
    }

}

