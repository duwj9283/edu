<?php
class Code
{
    const OK = 0;
    const ERROR = 1;
    const ERROR_PARAMS = 2;
    const ERROR_DB = 3;
    const ERROR_DB_WRITE = 4;
}

class FileType
{
    const FOLDER = 1;//文件夹
    const VIDEO = 2;//视频
    const IMAGE = 3;//图片
    const AUDIO = 4;//音频
    const DOC = 5;//文档
    const ZIP = 6;//压缩包
    const OTHER = 7;//其他文件
}

class FileStatus
{
    const NORMAL = 0;//正常
    const RECOVER = 1;//回收站
    const DELETE = 2;//已删除
}
class FileFrom
{
    const PC = 0;//电脑
    const ANDROID = 1;//Android客户端
    const IPHONE = 2;//iPhone客户端
    const IPAD = 3;//ipad客户端
}

class FileLanguage
{
    static $Language = array(
        array('id'=>0,'name'=>'中文','type'=>'zh'),
        array('id'=>1,'name'=>'英文','type'=>'eng'),
        array('id'=>2,'name'=>'双语','type'=>'both'),
        array('id'=>3,'name'=>'其他','type'=>'other')
    );
}

class FileApplicationType
{
    static $ApplicationType = array(
        array('id'=>1,'name'=>'试卷'),
        array('id'=>2,'name'=>'工具'),
        array('id'=>3,'name'=>'教学教材'),
        array('id'=>4,'name'=>'实验'),
        array('id'=>5,'name'=>'课标解读'),
        array('id'=>6,'name'=>'教材教法解析'),
        array('id'=>7,'name'=>'习题')
    );
}


class LessonStatus
{
    const NOPUB = 0;//未发布
    const PUBING = 1;//已发布
    const CLOSE = 2;//已关闭
}

class FileSortType
{
    const DOWNTIME = 1;//按时间降序
    const UPTIME = 2;//按时间升序
    const DOWNNAME = 3;//按文件名升序
    const UPNAME = 4;//按文件名降序
    const DOWNSIZE = 5;//按文件大小降序
    const UPSIZE = 6;//按文件大小升序
    static $TYPES =
         array(
            self::DOWNTIME=>"addtime desc",
            self::UPTIME=>"addtime asc",
            self::DOWNNAME=>"file_name desc",
            self::UPNAME=>"file_name asc",
            self::DOWNSIZE=>"file_size desc",
            self::UPSIZE=>"file_size asc",
        );
}

class GroupType{
	const CLASSGROUP = 1;	//班级群组
	const COMMUNICATIONGROUP = 2; //交流群组
	const WORKGROUP = 3; //工作群组

	static $TYPES =
		array(
			self::CLASSGROUP => "班级群",
			self::COMMUNICATIONGROUP => "交流群",
			self::WORKGROUP => "工作群",
		);

	static $TYPES_R =
		array(
			"班级群"=> self::CLASSGROUP ,
			"交流群"=> self::COMMUNICATIONGROUP ,
			"工作群"=> self::WORKGROUP ,
		);
}

class GroupValidateType{
	const NONEED = 1;
	const NEED = 2;
	const NOTALLOWED = 3;

	static $TYPES =
		array(
			self::NONEED=> "需要认证",
			self::NEED=> "不需要认证",
			self::NOTALLOWED=> "不允许加入",
		);

	static $TYPES_R =
		array(
			"需要认证"=> self::NONEED,
			"不需要认证"=> self::NEED,
			"不允许加入"=> self::NOTALLOWED,
		);
}

class ShareType
{
    const FILE = 1;//文件
    const LESSON = 2;//课程
    const MIN_LESSON = 3;//微课
    const LIVE = 4;//直播
    const ACTIVITY = 5;//活动
}

class LikeType
{
    const FILE = 1;//文件
    const LESSON = 2;//课程
    const MIN_LESSON = 3;//微课
    const LIVE = 4;//直播
    const ACTIVITY = 5;//活动
}

class GroupUserAuthority{
	const NORMAL = 0;	//普通用户
	const CREATER = 1;	//创建者
	const MANAGER = 2;	//管理员
}

class GroupApplyStatus{
	const APPLY = 1;	//申请状态
	const ACCEPT = 2;	//同意
	const REJECT = 3;	//拒绝
}

class CapacityApplyStatus{
	const APPLY = 1;	//申请状态
	const ACCEPT = 2;	//同意
	const REJECT = 3;	//拒绝
}

//学段
class Period{
    const K12 = 1;	//K12(中小学)
    const College = 2;	//大学
}

class DynamicType{
    const FileZone = 1;	//空间可见
    const FilePush = 2;	//发布文件
    const FileShare = 3;	//分享文件
}

class DynamicContent{
    const FileZone = "设置文件[%s]为空间可见";	//空间可见动态内容
    const FilePush = "发布文件[%s]";	//发布文件动态内容
    const FileShare = "分享文件[%s]";	//分享文件动态内容
}

/**
 * @brief （数据）无需参数接口
 */
class NoNeedParams
{
    static $JOGGLES = array(

    );
}


