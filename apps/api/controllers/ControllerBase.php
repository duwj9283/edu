<?php
namespace Cloud\API\Controllers;

use Cloud\Models\User;
use Cloud\Models\Userdynamic;
use Phalcon\Mvc\Controller;
use Phalcon\Validation;

/**
 * @brief 控制器继承类（前台忽略）
 */
class ControllerBase extends Controller
{
    //  请求参数
    protected $params = array();
    //  返回的数据段
    protected $data = array();
    //  成功描述
    protected $desc = '';
    //  错误列表
    protected $errors = array();

    protected $validation;

    protected $uid;

    protected $user_token;

    protected $responseData = array("code" => \Code::OK, "msg" => "", "line" => __LINE__, "data" => array());

    public function beforeExecuteRoute()
    {
        $this->params = $this->request->getPost();
    }

    public function initialize()
    {
        $this->validation = new \Phalcon\Validation();
        $this->uid = (int)$this->session->get('uid');
    }

    protected function curlPost($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    protected function curlGet($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.'?'.$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function output()
    {
        echo json_encode($this->responseData);
    }

    protected function response($data = [])
    {
        $result = [
            'code' => \Code::OK,
            'msg' => '',
        ];
        $result['data'] = $data;
        return json_encode($result);
    }

    protected function error($code = 1, $msg = '', $line = 0)
    {
        $data = compact('code', 'msg', 'line');
        return json_encode($data);
    }

    public function checkUserLogin()
    {
        $user = User::findFirst(array('uid=' . $this->uid));
        if (!$user) {

            $this->responseData = array("code" => -1, "msg" => "请登录", "line" => __LINE__, "data" => array());
            return false;
        }
        $user_token = $this->session->get('user_token');
        if ($user_token != $user->user_token) {
            $this->responseData = array("code" => -1, "msg" => "登陆状态失效", "line" => __LINE__, "data" => array());
            return false;
        }
        return true;
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
     * @brief a compressed zip file  将多个文件压缩成一个zip文件的函数
     * @param files 数组类型  实例array("1.jpg","2.jpg");
     * @param destination  目标文件的路径  如"c:/androidyue.zip"
     * @param overwrite 是否为覆盖与目标文件相同的文件
     */
    public function createZip($files = array(), $destination = '', $overwrite = false)
    {
        //if the zip file already exists and overwrite is false, return false
        //如果zip文件已经存在并且设置为不重写返回false
        if (file_exists($destination) && !$overwrite) {return false;}
        //vars
        $valid_files = array();
        //if files were passed in...
        //获取到真实有效的文件名
        if (is_array($files)) {
            //cycle through each file
            foreach ($files as $file) {
                //make sure the file exists
                if (file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        //如果存在真实有效的文件
        if (count($valid_files)) {
            //create the archive
            $zip = new \ZipArchive();

            //打开文件       如果文件已经存在则覆盖，如果没有则创建
            if ($zip->open($destination, $overwrite ? \ZIPARCHIVE::OVERWRITE : \ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            //向压缩文件中添加文件
            foreach ($valid_files as $file) {
                $file_info_arr = pathinfo($file);
                $filename = $file_info_arr['basename'];
                $zip->addFile($file, iconv('utf-8', 'gb2312', $filename));
            }
            //关闭文件
            $zip->close();
            //check to make sure the file exists
            //检测文件是否存在
            return file_exists($destination);
        } else {
            //如果没有真实有效的文件返回false
            return false;
        }
    }

    /**
     * @brief 文件大小展示计算
     * @param $size
     * @param string $unit
     * @param int $decimals
     * @param string $targetUnit
     * @return string
     */
    public function fileSizeConv($size, $unit = 'B', $decimals = 1, $targetUnit = 'auto')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
        $theUnit = array_search(strtoupper($unit), $units); //初始单位是哪个
        //判断是否自动计算，
        if ($targetUnit != 'auto') {
            $targetUnit = array_search(strtoupper($targetUnit), $units);
        }
        //循环计算
        while ($size >= 1024) {
            $size /= 1024;
            $theUnit++;
            if ($theUnit == $targetUnit) //已符合给定则退出循环吧！
            {
                break;
            }
        }
        return sprintf("%1\$.{$decimals}f", $size) . $units[$theUnit];
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
                        if ($dur < 2569200) {//3天内
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

    /**
     * @brief 动态记录
     * @param type 1、空间可见 2、发布文件 3、分享文件
     * @param content
     * @param content
     */
    public function insertDynamic($uid,$content,$addition,$type)
    {
        $user_dynamic = new Userdynamic();
        $user_dynamic->uid = $uid;
        $user_dynamic->content = $content;
        $user_dynamic->addition = $addition;
        $user_dynamic->type = $type;
        $user_dynamic->addtime = date("Y-m-d H:i:s");
        if(!$user_dynamic->create())
        {
            return 0;
        }
        else
        {
            return 1;
        }
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
