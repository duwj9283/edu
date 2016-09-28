<?php
/**
 * Created by PhpStorm.
 * User: dotboy
 * Date: 16/1/20
 * Time: 上午12:08
 */

require "tbdy/TopSdk.php";

class SMS extends Phalcon\Mvc\User\Component{
    /**
     * @brief 发送短信模板
     * @param phoneList 逗号分割的手机号列表
     * @param authCode 要发送的验证码
     */
    public function send($phoneList, $authCode){
        $client = new TopClient("23301701", "e301353e152369b1f175887852023d63");
        $request = new AlibabaAliqinFcSmsNumSendRequest;
        $request->setSmsType("normal");
        $request->setSmsFreeSignName("风平网");
        $request->setSmsParam("{'code':'$authCode','service':'风平网'}");
        $request->setRecNum($phoneList);
        $request->setSmsTemplateCode("SMS_4726084");
        return $client->execute($request);
    }

    /**
     * @brief 验证短信验证码是否正确
     * @param phone 手机号
     * @param authCode 要验证的验证码
     * @param codeType 验证码类型
     */
    public function checkAuthCode($phone, $authCode, $codeType){
        $cacheKey = RedisConfig::AUTH_CODE_PREFIX."$codeType:$phone";
        $cachedCode = $this->redis->get($cacheKey);

        return $cachedCode=== $authCode;
    }

    public function deleteAuthCode($phone, $authCode, $codeType){
        $cacheKey = RedisConfig::AUTH_CODE_PREFIX."$codeType:$phone";
        return $this->redis->del($cacheKey);
    }

    /**
     * @brief 新用户注册欢迎短信
     * @param $phoneList
     * @param $authCode
     * @return mixed|ResultSet|SimpleXMLElement
     */
    public function welcome($phoneList){
        $client = new TopClient("23301701", "e301353e152369b1f175887852023d63");
        $request = new AlibabaAliqinFcSmsNumSendRequest;
        $request->setSmsType("normal");
        $request->setSmsFreeSignName("风平网");
        $request->setSmsParam("{'wx':'fumpin'}");
        $request->setRecNum($phoneList);
        $request->setSmsTemplateCode("SMS_4776087");
        return $client->execute($request);
    }

    /**
     * @brief 项目到期通知
     * @param $phoneList
     * @param $projectName
     * @param $maturityReal
     * @return mixed|ResultSet|SimpleXMLElement
     */
    public function projectExpire($phoneList, $projectName, $startTime){
        $client = new TopClient("23301701", "e301353e152369b1f175887852023d63");
        $request = new AlibabaAliqinFcSmsNumSendRequest;
        $request->setSmsType("normal");
        $request->setSmsFreeSignName("风平网");
        $request->setSmsParam("{'project':$projectName, 'maturity_real':$startTime}");
        $request->setRecNum($phoneList);
        $request->setSmsTemplateCode("SMS_4746138");
        return $client->execute($request);
    }

    /**
     * @brief 项目参与成功通知
     * @param $phoneList
     * @param $time
     * @param $projectName
     * @param $amount
     * @param $startTime
     * @param $endTime
     * @param $service
     * @return mixed|ResultSet|SimpleXMLElement
     */
    public function addSuccessful($phoneList, $time, $projectName, $amount, $startTime, $endTime, $service){
        $client = new TopClient("23301701", "e301353e152369b1f175887852023d63");
        $request = new AlibabaAliqinFcSmsNumSendRequest;
        $request->setSmsType("normal");
        $request->setSmsFreeSignName("风平网");
        $request->setSmsParam("{'time':$time, 'project':$projectName, 'amount':$amount, 'maturity_begin':$startTime, 'maturity_end':$endTime, 'service':$service}");
        $request->setRecNum($phoneList);
        $request->setSmsTemplateCode("SMS_4766256");
        return $client->execute($request);
    }
}
