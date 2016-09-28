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
        'cachesDir'      => __DIR__ . '/../caches/',
        'baseUri'        => '/frontend/'
    ),
    'upload_path' => UPLOAD_PATH,
    //前端上传图片根目录
    'front_image_root'=>'/alidata/upload',
    //图片裁切目录
    'upload_image_thumb' => UPLOAD_PATH.'thumb_image/',
    //视频截图池
    'upload_video_thumb' => UPLOAD_PATH.'video_thumb/'
));
