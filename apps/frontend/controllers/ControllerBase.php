<?php

namespace Cloud\Frontend\Controllers;

use Cloud\Models\Messagestatus;
use Cloud\Models\Subject;
use Cloud\Models\User;
use Cloud\Models\Userinfo;
use Cloud\Models\Siteconfig;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public $token_info = false;
    protected $user_info = array();
    protected $responseData = array('code'=>0, 'data'=>array(), 'line'=>__LINE__, 'msg'=>'ok');

	protected $bSignedIn = false;
	protected $msgStatus = 0;
	protected $userInfo = null;

	public function initialize(){

		$this->view->PERIOD = PERIOD;
		//加载头部和尾部信息
		$siteConfig = Siteconfig::find(array());
		foreach($siteConfig as $c)
		{
			if($c->id==1)
			{
				$this->view->title = $c->option_value;
			}
			else if($c->id==2)
			{
				$this->view->footerInfo = $c->option_value;
			}
			else if($c->id==3)
			{
				$this->view->logo = $c->option_value;
			}
		}
	}

    public function set_data($data)
    {
        $this->responseData['data'] = $data;
    }
    public function set_output($responseData)
    {
        $this->responseData = $responseData;
    }
    public function output()
    {
        echo json_encode($this->responseData);
    }

	public function beforeExecuteRoute(){
		$this->checkSignedIn();
	}

	/**
	 * @brief 登录检查(测试中,默认以10号ID登录)
	 */
	protected function checkSignedIn(){
		$this->bSignedIn = false;
		$uid = $this->session->get("uid");
		if ($uid) {
			$user = User::findFirst(array('uid=' . $uid));

			$user_token = $this->session->get('user_token');
			if ($user_token == $user->user_token) {
				$this->bSignedIn = true;
				$this->user =$user;
				$this->userInfo = Userinfo::findFirst("uid=$uid");
				$this->view->userInfo = $this->userInfo;
				$this->view->user = $this->user;
				$this->msgStatus = Messagestatus::count(array("receiver_id=$uid and status=1 and view_status=0"));
				$this->view->msgStatus = $this->msgStatus;
			}
		}
		$this->view->bSignedIn = $this->bSignedIn;

		return $this->bSignedIn;
	}

	private function noNeedLoginUrl()
	{
		return array(
			'/',
			'/resource',
		);
	}
	public function fileSizeConv($size, $unit = 'B', $decimals = 1, $targetUnit = 'auto') {
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
		$theUnit = array_search(strtoupper($unit), $units); //初始单位是哪个
		//判断是否自动计算，
		if ($targetUnit != 'auto')
			$targetUnit = array_search(strtoupper($targetUnit), $units);
		//循环计算
		while ($size >= 1024) {
			$size/=1024;
			$theUnit++;
			if ($theUnit == $targetUnit)//已符合给定则退出循环吧！
				break;
		}

		return sprintf("%1\$.{$decimals}f", $size) . $units[$theUnit];
	}
	public function array_sort($arr,$keys, $order='asc',$rootKey='lesson') {
		if (!is_array($arr)) {
			return false;
		}
		$keysvalue = array();
		foreach($arr as $key => $val) {
			$keysvalue[$key] = $val[$rootKey][$keys];
		}
		if($order == 'asc'){
			asort($keysvalue);
		}else {
			arsort($keysvalue);
		}
		reset($keysvalue);

		foreach($keysvalue as $key => $vals) {
			$keysort[$key] = $key;
		}
		$new_array = array();
		foreach($keysort as $key => $val) {
			$new_array[$key] = $arr[$val];
		}
		return $new_array;
	}
	/**
	 * @brief 计算时间格式
	 * @param date
	 */
	public function dateConv($date)
	{
		$show_time = strtotime($date);
		$dur = time() - $show_time;
		if($dur < 0)
		{
			return $date;
		}
		else
		{
			if ($dur < 60) {
				return $dur . '秒前';
			}
			else
			{
				if ($dur < 3600) {
					return floor($dur / 60) . '分钟前';
				} else {
					if ($dur < 86400) {
						return floor($dur / 3600) . '小时前';
					} else {
						if ($dur < 259200) {//3天内
							return floor($dur / 86400) . '天前';
						} else {
							return $date;
						}
					}
				}
			}
		}
	}
	/**
	 * 数组排序
	 */
	public function arraySort($arr, $keys, $order = 'asc',$rootKey='lesson')
	{
		if (!is_array($arr)) {
			return false;
		}
		$keysvalue = array();
		foreach ($arr as $key => $val) {
			$keysvalue[$key] = $val[$rootKey][$keys];
		}
		if ($order == 'asc') {
			asort($keysvalue);
		} else {
			arsort($keysvalue);
		}
		reset($keysvalue);

		$keysort = array();
		foreach ($keysvalue as $key => $vals) {
			$keysort[$key] = $key;
		}
		$new_array = array();
		foreach ($keysort as $key => $val) {
			$new_array[$key] = $arr[$val];
		}
		return $new_array;
	}

	/**
	 * 返回一字符串，十进制 number 以 radix 进制的表示。
	 * @param dec       需要转换的数字
	 * @param toRadix    输出进制。当不在转换范围内时，此参数会被设定为 2，以便及时发现。
	 * @return    指定输出进制的数字
	 */
	public function dec2Any($dec, $toRadix=20) {

		$MIN_RADIX = 2;
		$MAX_RADIX = 62;
		$num62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if ($toRadix < $MIN_RADIX || $toRadix > $MAX_RADIX) {
			$toRadix = 2;
		}
		if ($toRadix == 10) {
			return $dec;
		}
		// -Long.MIN_VALUE 转换为 2 进制时长度为65
		$buf = array();
		$charPos = 64;
		$isNegative = $dec < 0; //(bccomp($dec, 0) < 0);
		if (!$isNegative) {
			$dec = -$dec; // bcsub(0, $dec);
		}
		while (bccomp($dec, -$toRadix) <= 0) {
			$buf[$charPos--] = $num62[-bcmod($dec, $toRadix)];
			$dec = bcdiv($dec, $toRadix);
		}

		$buf[$charPos] = $num62[-$dec];
		if ($isNegative) {
			$buf[--$charPos] = '-';
		}
		$_any = '';
		for ($i = $charPos; $i < 65; $i++) {
			$_any .= $buf[$i];
		}
		return substr($num62, rand(0,61),2).$_any.substr($num62,rand(0,61),1);
	}

	/**
	 * 返回一字符串，包含 number 以 10 进制的表示。<br />
	 * fromBase 只能在 2 和 62 之间（包括 2 和 62）。
	 * @param number    输入数字
	 * @param fromRadix    输入进制
	 * @return  十进制数字
	 */
	public function any2Dec($number, $fromRadix=20) {
		$number = substr($number,2,strlen($number)-3);
		$num62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$dec = 0;
		$digitValue = 0;
		$len = strlen($number) - 1;
		for ($t = 0; $t <= $len; $t++) {
			$digitValue = strpos($num62, $number[$t]);
			$dec = bcadd(bcmul($dec, $fromRadix), $digitValue);
		}
		return $dec;
	}

	public function timeLong($file){
		if(is_array($file))
		{
			$time = 0;
			foreach($file as $f)
			{
				$time = $time+floatval(exec('ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '.$f));
			}
		}
		else
		{
			$time = floatval(exec('ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '.$file));
		}
		return $time;
	}
}
