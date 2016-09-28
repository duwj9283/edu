//文件扩展名数组
var Types = [
    ["mp4","flv","avi","rmvb","rm","mov"],
    ["jpg","png","jpeg","gif","bmp"],
    ["mp3","wav"],
    ["txt","doc","docx","xml","pdf","excel","ppt","pptx","xls","xlsx"]
];
//文件上传目录
exports.FilePathRoot = '/alidata/upload/';

//文件池目录
exports.FilePoolRoot = exports.FilePathRoot+'filepool/';

//视频文件截图目录
exports.VideoThumbImageRoot = exports.FilePathRoot+'video_thumb/';

//数据库连接参数配置
exports.MysqlConfig = {
    host: 'localhost',
    user: 'root',
    password: 'duwj9283',
    database:'bbyxy',
    port: 3306
};

//判断文件类型
exports.GetFileType = function (name){
    var nameArray = name.split('.');
    var type = nameArray[nameArray.length-1];
    var matchStr = type.toLowerCase();
    var file_type = 6;
    for(var i=0;i<Types.length;i++)
    {
        for(var j=0;j<Types[i].length;j++)
        {
            if(Types[i][j] == matchStr)
            {
                file_type = i+2;
                return file_type;
            }
        }
    }
    return file_type;
};

//sql文件
exports.RunSql = function (conn,sql,callback,param){
    conn.query(sql,function(error,result) {
        if (error) console.log(error);
        callback(null,result,param);
    });
};







