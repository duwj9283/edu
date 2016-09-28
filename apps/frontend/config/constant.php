<?php
/*
 * 类引用的话，使用\City::$city
 * */
class City{

    static $City=array(
        '安徽'=>array('合肥','安庆','滁州','宣城','芜湖','马鞍山'),
        '江苏'=>array('南京','s苏州','常州','无锡')
        );
}
class Development{
    static $ROOTPATH = 'http://192.168.5.200/api';
    static $ROOTPATHDu = 'http://192.168.5.166/api';
}
/*
 * 想要使用$this->constant这种方式的话，必须返回一个对象，如下：
 * */
return new \Phalcon\Config(array(
    'test'=>'必须是对象'
));

