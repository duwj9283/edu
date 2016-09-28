<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => 'duwj9283',
        'dbname'   => 'bbyxy',
        'charset'  => 'utf8',
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../controllers/',
        'modelsDir'      => __DIR__ . '/../models/',
        'migrationsDir'  => __DIR__ . '/../migrations/',
        'viewsDir'       => __DIR__ . '/../views/',
		'logsDir'        => __DIR__ . '/../logs/',
		'cachesDir'      => __DIR__ . '/../caches/',
        'baseUri'        => '/cloud/'
    ),
    'secret' => array(
        'auth_param'     => 'it is a secret!',
        'password'       => 'it is the password!'
    ),
    'redis' => array(
        'host'        => '127.0.0.1',
        'port'        => '6379'
    ),
    'sms'=> array(
        'uid'=>'BjB7OJZG9auX',
        'password'=>'f22yeqsu',
        'url'=>'http://api.weimi.cc/2/sms/send.html'
    ),
    'vs2_serv' => array(
        'host' => 'edu.iguangj.com',
        'port' => 1935
    ),
    'upload_path' => UPLOAD_PATH,
    //图片裁切目录
    'upload_thumb_image' => UPLOAD_PATH.'thumb_image/',
    //批量下载生成压缩文件临时目录
    'upload_batchfiletmp' => UPLOAD_PATH.'batchfiletmp/',
    //视频截图池
    'upload_video_thumb' => UPLOAD_PATH.'video_thumb/',
    //文件池
    'upload_filepool' => UPLOAD_PATH.'filepool/',
    //回收池
    'upload_recoverpool' => UPLOAD_PATH.'recoverpool/',
    //公开文件池
    'upload_publicpool' => UPLOAD_PATH.'publicpool/',
    //预览池
    'upload_previewpool' => UPLOAD_PATH.'previewpool/',
    //群组文件池
    'upload_grouppool' => UPLOAD_PATH.'grouppool/',
    //个人对个人文件池
    'upload_touserpool' => UPLOAD_PATH.'touserpool/',
    //课程文件
    'lesson' => UPLOAD_PATH.'lesson/'
));
