var commeon = require('./commeon.js');
var Files = {};//文件数组
var FilePathRoot = commeon.FilePathRoot;
var FilePoolRoot = commeon.FilePoolRoot;
var VideoThumbImageRoot = commeon.VideoThumbImageRoot;
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var fs = require('fs');
var crypto = require('crypto');
var mysql = require('mysql');
var exec = require('child_process').exec;
var async = require('async');
var pool = mysql.createPool(commeon.MysqlConfig);
if(!fs.existsSync(FilePoolRoot))
{
    fs.mkdirSync(FilePoolRoot,0755);
}
if(!fs.existsSync(VideoThumbImageRoot))
{
    fs.mkdirSync(VideoThumbImageRoot,0755);
}

function getLastFileName(uid,path,name,start,cb,params) {
    async.waterfall([
        function(callback){
            pool.getConnection(function (error, conn)
            {
                if (error) console.log(error);
                var sql = "select count(*) as count from edu_user_file where path='"+path+"' and uid="+uid;
                commeon.RunSql(conn,sql,callback,null);
                conn.release();
            });
        },
        function(result,param,callback){
            pool.getConnection(function (error, conn)
            {
                if (error) console.log(error);
                if(result[0])
                {
                    if(result[0].count>0)
                    {
                        //文件名（不含后缀）
                        var fname='';
                        var ext='';
                        if(name.indexOf(".")>0)
                        {
                            ext = name.substr(name.lastIndexOf(".")+1);
                            fname = name.substr(0,name.lastIndexOf("."));
                        }
                        var pattern = /^.*\(([0-9]*)\)$/;
                        var sort = fname.match(pattern);
                        var num = 0;//最后的序号
                        if(sort!=null)
                        {
                            num = sort[1];
                        }
                        var new_name = fname+'('+(num+start)+').'+ext;
                        var sql = "select count(*) as count from edu_user_file where path='"+path+"' and file_name='"+new_name+"' and uid="+uid+" limit 1";
                        commeon.RunSql(conn,sql,callback,new_name);
                    }
                }
                conn.release();
            });
        },
        function(result,param,callback)
        {
            if(result[0])
            {
                if(result[0].count>0)
                {
                    getLastFileName(uid,path,name,start+1,cb,params);
                }
                else
                {
                    callback(null, param);
                }
            }
        }
    ], function (error,result) {
        cb(null,result,params);
    });
}
io.sockets.on('connection', function (socket) {
    socket.on('start', function (data) {
        var uid = data.uid;
        var user_token = data.user_token;
        var FileMd5 = data.FileMd5;
        var folder = data.folder;
        var file_from = parseInt(data.file_from);
        var name = data.Name;
        var size = data.Size;
        if(!uid||!user_token||!FileMd5||!folder||!name||!size)
        {
            socket.emit('err', { 'code': '0','msg':'参数缺失'});
            console.log('参数缺失');
            return;
        }
        if(!fs.existsSync(FilePathRoot+uid))
        {
            fs.mkdirSync(FilePathRoot+uid,0755);
        }
        //判断文件类型
        var file_type = commeon.GetFileType(name);
        var filePath = FilePathRoot+uid+folder+name;
        pool.getConnection(function (error, conn)
        {
            if (error)
            {
                socket.emit('err', { 'code': '0','msg':'数据库连接池连接失败'});
                console.log('数据库连接池连接失败'+error);
            }
            async.waterfall([
                function(callback){
                    var sql = "select count(*) as count from edu_user where user_token='"+user_token+"' and uid="+uid;
                    commeon.RunSql(conn,sql,callback,null);
                },
                //查看用户剩余空间
                function(result,param,callback){
                    if(result[0]&&result[0].count>0){
                        var sql = "select * from edu_user_capacity where uid="+uid+" limit 1";
                        commeon.RunSql(conn,sql,callback,null);
                    }
                    else
                    {
                        socket.emit('err', { 'code': '0','msg':'用户不存在'});
                        console.log('用户不存在');
                    }
                },
                //查找数据库文件池是否有此文件
                function(result,param,callback){
                    if(result[0]&&(result[0].capacity_used+size)<=result[0].capacity_all){
                        var sql = "select * from edu_files where md5='"+FileMd5+"' limit 1";
                        commeon.RunSql(conn,sql,callback,null);
                    }
                    else
                    {
                        socket.emit('err', { 'code': '1','msg':'上传文件已超出文件容量','FileMd5':FileMd5});
                        console.log('上传文件已超出文件容量');
                    }
                },
                function(result,param,callback){
                    var sql = "select * from edu_user_file where path='"+folder+"' and file_name='"+name+"' and uid="+uid+" and file_status=0 limit 1";
                    commeon.RunSql(conn,sql,callback,result);
                },
                function(result,param,callback){
                    var params = {};
                    if(param[0])    //如果文件池已经有该文件
                    {
                        var thumb_name = '';
                        if(file_type==2)
                        {
                            thumb_name = param[0].path+'/'+param[0].video_thumb;
                        }
                        if(result[0])   //如果已经有文件记录
                        {
                            if(result[0].percent==100)  //如果已有重名并且上传完成的文件，重命名
                            {
                                params[0] = 'insert';
                                params[1] = param;
                                params[2] = thumb_name;
                                getLastFileName(uid,folder,name,1,callback,params);
                            }
                            else    //删除未完成用户文件，秒传
                            {
                                params[0] = 'update';
                                params[1] = param;
                                params[2] = thumb_name;
                                params[3] = result[0];
                                callback(null,name,params);
                            }
                        }
                        else       //秒传
                        {
                            params[0] = 'insert';
                            params[1] = param;
                            params[2] = thumb_name;
                            callback(null,name,params);
                        }
                    }
                    else    //如果文件池没有该文件
                    {
                        if(result[0])    //如果用户云盘有记录，但文件池无记录
                        {
                            if(result[0].file_md5==FileMd5) //如果文件已经存在，续传
                            {
                                params[0] = 'update-two';
                                params[1] = result[0].id;
                                params[2] = result[0].percent;
                                callback(null,name,params);
                            }
                            else    //如果是新文件，只是文件名相同
                            {
                                params[0] = 'insert-first';
                                params[1] = param;
                                getLastFileName(uid,folder,name,1,callback,params);
                            }
                        }
                        else    //如果没有记录，先插入一条用户文件记录，从0开始
                        {
                            params[0] = 'insert-first';
                            callback(null,name,params);
                        }
                    }
                },
                function(result,param,callback){
                    var sql;
                    if(param[0]=='insert-first')
                    {
                        param[1]=result;
                        sql = "insert into edu_user_file(uid,file_name,path,file_from,file_size,file_md5,file_type,addtime) values("+uid+",'"+result+"','"+folder+"',"+file_from+","+size+",'"+FileMd5+"',"+file_type+","+Date.parse(new Date())/1000+")";
                        commeon.RunSql(conn,sql,callback,param);
                    }
                    else if(param[0]=='insert')
                    {
                        param[3] = result;
                        sql = "insert into edu_user_file(uid,file_name,path,file_from,file_type,percent,file_id,file_size,file_md5,video_thumb,addtime) values("+uid+",'"+result+"','"+folder+"',"+file_from+","+file_type+",100,"+param[1][0].id+","+size+",'"+FileMd5+"','"+param[2]+"',"+Date.parse(new Date())/1000+")";
                        commeon.RunSql(conn,sql,callback,param);
                    }
                    else if(param[0]=='update')
                    {
                        sql = "update edu_user_file set video_thumb='"+param[2]+"',percent=100,file_id="+param[1][0].id+" where id="+param[3].id;
                        commeon.RunSql(conn,sql,callback,param);
                    }
                    else if(param[0]=='update-two')
                    {
                        var downloaded = 0;
                        if(fs.existsSync(filePath))
                        {
                            var file = fs.statSync(filePath);
                            downloaded = file.size;
                        }
                        Files[name] = {
                            fileSize:size,
                            fileId:param[1],
                            uid:uid,
                            file_type:file_type,
                            file_from:file_from,
                            FileMd5:FileMd5,
                            data:'',
                            downloaded:downloaded,
                            handler:null,
                            filePath:filePath
                        };
                        Files[name].getPercent = function () {
                            return parseInt((this.downloaded / this.fileSize) * 100);
                        };
                        Files[name].getPosition = function () {
                            return this.downloaded;
                        };
                        fs.open(Files[name].filePath, 'a', 0755, function (err, fd) {
                            if (err){
                                socket.emit('err', { 'code': '1','msg':name+'上传失败','FileMd5':FileMd5});
                                console.log('[start] file open error: ' + err.toString());
                            }
                            else {
                                Files[name].handler = fd;
                                socket.emit('moreData', { 'name':name,'position':downloaded,'percent': param[2]});
                            }
                        });
                    }
                },
                function(result,param,callback){
                    var sql,poolpath;
                    if(param[0]=='update'&&result.affectedRows>0)
                    {
                        poolpath = FilePoolRoot+param[1][0].path;
                        if(!fs.existsSync(poolpath))
                        {
                            fs.mkdirSync(poolpath,0755);
                        }
                        sql = "update edu_files set file_count=file_count+1 where md5='"+FileMd5+"'";
                        param[2] = result.id;
                        commeon.RunSql(conn,sql,callback,param);
                    }
                    else if(param[0]=='insert'&&result.insertId>0)
                    {
                        var fpath = FilePathRoot+uid+folder+param[3];
                        poolpath = FilePoolRoot+param[1][0].path;
                        //创建文件超链接
                        if(!fs.existsSync(poolpath))
                        {
                            fs.mkdirSync(poolpath,0755);
                        }
                        var linkCommand = 'ln -s '+poolpath+'/'+param[1][0].md5+' "'+fpath+'"';
                        exec(linkCommand, function callback(error, stdout, stderr) {
                            if(error){
                                socket.emit('err', { 'code': '1','msg':name+'创建文件超链接失败','FileMd5':FileMd5});
                                console.log("创建文件超链接失败=>"+error);
                            }
                        });
                        sql = "update edu_files set file_count=file_count+1 where md5='"+FileMd5+"'";
                        param[2] = result.insertId;
                        commeon.RunSql(conn,sql,callback,param);
                    }
                    else if(param[0]=='insert-first'&&result.insertId>0)
                    {
                        var fPath = FilePathRoot+uid+folder+param[1];
                        Files[name] = {
                            fileSize:size,
                            fileId:result.insertId,
                            uid:uid,
                            file_type:file_type,
                            file_from:file_from,
                            FileMd5:FileMd5,
                            data:'',
                            downloaded:0,
                            handler:null,
                            filePath:fPath
                        };
                        Files[name].getPercent = function () {
                            return parseInt((this.downloaded / this.fileSize) * 100);
                        };
                        Files[name].getPosition = function () {
                            return this.downloaded;
                        };

                        fs.open(Files[name].filePath, 'a', 0755, function (err, fd) {
                            if (err){
                                socket.emit('err', { 'code': '1','msg':name+'服务器异常','FileMd5':FileMd5});
                                console.log('[start] file open error: ' + err.toString());
                            }
                            else {
                                Files[name].handler = fd;
                                socket.emit('moreData', { 'name':name,'position': 0, 'percent': 0 });
                            }
                        });
                    }
                },
                function(result,param,callback){
                    if(result.affectedRows>0)
                    {
                        if(param[0]=='insert')
                        {
                            socket.emit('done', { 'name': name,'file_id':param[2]});
                        }
                        else
                        {
                            //删除已上传文件
                            if(fs.existsSync(filePath))
                            {
                                var delUploadedFileCommand = 'rm -rf "'+filePath+'"';
                                exec(delUploadedFileCommand, function callback(error, stdout, stderr) {
                                    if(error){
                                        socket.emit('err', { 'code': '1','msg':name+'服务器异常','FileMd5':FileMd5});
                                        console.log("start delUploadedFileCommand=>"+error);
                                    }
                                });
                            }
                            var poolpath = FilePoolRoot+param[1][0].path;
                            callback(null,poolpath,param);
                        }
                    }
                },
                function(result,param,callback){
                    //创建文件超链接
                    var linkCommand = 'ln -s '+result+'/'+param[1][0].md5+' "'+filePath+'"';
                    exec(linkCommand, function callback(error, stdout, stderr) {
                        if(error) {
                            socket.emit('err', { 'code': '1','msg':name+'服务器异常','FileMd5':FileMd5});
                            console.log("start linkCommand=>"+error);
                        }
                        else
                        {
                            socket.emit('done', { 'name': name,'file_id':param[2]});
                        }
                    });
                }
            ], function (error,result) {
                console.log(error);
                console.log(result);
            });
            conn.release();
        });
    });
    socket.on('upload', function (data) {
        var name = data.Name;
        var segment = data.Segment;
        Files[name].downloaded += segment.length;
        if(Files[name].file_from==1)
        {
            Files[name].data = segment;
        }
        else
        {
            Files[name].data += segment;
        }
        pool.getConnection(function (error, conn) {
            if (error) console.log("POOL-start ==> " + error);
            var updateSql = "update edu_user_file set percent="+Files[name].getPercent()+" where id="+Files[name].fileId;
            conn.query(updateSql, function (err, result) {
                if (err) console.log(err);
                if(result.affectedRows>0)
                {
                    if (Files[name].downloaded === Files[name].fileSize)
                    {
                        if(Files[name].file_from==1)
                        {
                            fs.write(Files[name].handler, Files[name].data, 0, Files[name].data.length,null,
                                function (err, written) {
                                    fs.readFile(Files[name].filePath, function(err, data)
                                    {
                                        if (err) console.log(err);
                                        var hash = crypto.createHash('md5');
                                        hash.update(data);
                                        var md5_end = hash.digest('hex');
                                        //上传完毕
                                        if(md5_end==Files[name].FileMd5)
                                        {
                                            var date = new Date();
                                            var date_folder = date.getFullYear()+'-'+parseInt(date.getMonth()+1)+'-'+date.getDate();
                                            var poolpath = FilePoolRoot+date_folder;
                                            if(!fs.existsSync(poolpath))
                                            {
                                                fs.mkdirSync(poolpath,0755);
                                            }
                                            async.waterfall([
                                                function(callbak){
                                                    //移动&修改文件名称
                                                    var moveCommand = 'mv "'+Files[name].filePath+'" '+poolpath+'/'+md5_end;
                                                    exec(moveCommand, function callback(error, stdout, stderr) {
                                                        if(error)
                                                        {
                                                            console.log("moveCommand=>"+error);
                                                            callbak(null,'error');
                                                        }
                                                        else
                                                        {
                                                            callbak(null,'success');
                                                        }
                                                    });
                                                },
                                                function(result_code,callbak){
                                                    if(result_code=='success')
                                                    {
                                                        //创建文件超链接
                                                        var linkCommand = 'ln -s '+poolpath+'/'+md5_end+' "'+Files[name].filePath+'"';
                                                        exec(linkCommand, function callback(error, stdout, stderr) {
                                                            if(error)
                                                            {
                                                                console.log("linkCommand=>"+error);
                                                                callbak(null,'error');
                                                            }
                                                            else
                                                            {
                                                                callbak(null,'success');
                                                            }
                                                        });
                                                    }
                                                    else
                                                    {
                                                        console.log('移动文件到文件池失败');
                                                    }
                                                },
                                                function(result_code,callbak)
                                                {
                                                    if(result_code=='success')
                                                    {
                                                        var thumb_name = '';
                                                        if(Files[name].file_type==2)
                                                        {
                                                            var video_thumb_path =VideoThumbImageRoot+date_folder;
                                                            if(!fs.existsSync(video_thumb_path))
                                                            {
                                                                fs.mkdirSync(video_thumb_path,0755);
                                                            }
                                                            thumb_name = md5_end+".png";
                                                            //截图
                                                            var thumbCommand = 'ffmpeg -i "'+Files[name].filePath+'" -y -f image2 -ss 1 -t 0.001 -s 600*450 '+video_thumb_path+'/'+thumb_name;
                                                            exec(thumbCommand, function callback(error, stdout, stderr) {
                                                                if(error) console.log("thumbCommand=>"+error);
                                                            });
                                                        }
                                                        callbak(null,'success',thumb_name);
                                                    }
                                                    else
                                                    {
                                                        console.log('创建文件超链接失败');
                                                    }
                                                }
                                            ], function (error,result_code,thumb_name) {
                                                if(result_code=='success')
                                                {
                                                    //插入数据库
                                                    var insert_files = "insert into edu_files(size,md5,path,video_thumb,addtime) values("+Files[name].fileSize+",'"+md5_end+"','"+date_folder+"','"+thumb_name+"',"+Date.parse(new Date())/1000+")";
                                                    conn.query(insert_files,function(err_2,result_2){
                                                        if(result_2.insertId>0)
                                                        {
                                                            var update_thumb_sql = '';
                                                            if(thumb_name)
                                                            {
                                                                update_thumb_sql = "video_thumb='"+date_folder+"/"+thumb_name+"',";
                                                            }
                                                            var update_user_file = "update edu_user_file set "+update_thumb_sql+"file_id="+result_2.insertId+" where id="+Files[name].fileId;
                                                            conn.query(update_user_file,function(err_3,result_3){
                                                                if(result_3.affectedRows>0)
                                                                {
                                                                    var update_file_count = "update edu_files set file_count=file_count+1 where id="+result_2.insertId;
                                                                    conn.query(update_file_count,function(err_4,result_4)
                                                                    {
                                                                        if(result_4.affectedRows>0)
                                                                        {
                                                                            socket.emit('done', { 'name': name,'file_id':Files[name].fileId});
                                                                            delete Files[name];
                                                                        }
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                        else
                                        {
                                            console.log('文件上传错误');
                                        }
                                    });
                                }
                            );
                        }
                        else
                        {
                            fs.write(Files[name].handler, Files[name].data, null, 'Binary',
                                function (err, written) {
                                    fs.readFile(Files[name].filePath, function(err, data){
                                        if (err) console.log(err);
                                        var hash = crypto.createHash('md5');
                                        hash.update(data);
                                        var md5_end = hash.digest('hex');
                                        //上传完毕
                                        if(md5_end==Files[name].FileMd5)
                                        {
                                            var date = new Date();
                                            var date_folder = date.getFullYear()+'-'+parseInt(date.getMonth()+1)+'-'+date.getDate();
                                            var poolpath = FilePoolRoot+date_folder;
                                            if(!fs.existsSync(poolpath))
                                            {
                                                fs.mkdirSync(poolpath,0755);
                                            }
                                            async.waterfall([
                                                function(callbak){
                                                    //移动&修改文件名称
                                                    var moveCommand = 'mv "'+Files[name].filePath+'" '+poolpath+'/'+md5_end;
                                                    exec(moveCommand, function callback(error, stdout, stderr) {
                                                        if(error)
                                                        {
                                                            console.log("moveCommand=>"+error);
                                                            callbak(null,'error');
                                                        }
                                                        else
                                                        {
                                                            callbak(null,'success');
                                                        }
                                                    });
                                                },
                                                function(result_code,callbak){
                                                    if(result_code=='success')
                                                    {
                                                        //创建文件超链接
                                                        var linkCommand = 'ln -s '+poolpath+'/'+md5_end+' "'+Files[name].filePath+'"';
                                                        exec(linkCommand, function callback(error, stdout, stderr) {
                                                            if(error)
                                                            {
                                                                console.log("linkCommand=>"+error);
                                                                callbak(null,'error');
                                                            }
                                                            else
                                                            {
                                                                callbak(null,'success');
                                                            }
                                                        });
                                                    }
                                                    else
                                                    {
                                                        console.log('移动文件到文件池失败');
                                                    }
                                                },
                                                function(result_code,callbak)
                                                {
                                                    if(result_code=='success')
                                                    {
                                                        var thumb_name = '';
                                                        if(Files[name].file_type==2)
                                                        {
                                                            var video_thumb_path =VideoThumbImageRoot+date_folder;
                                                            if(!fs.existsSync(video_thumb_path))
                                                            {
                                                                fs.mkdirSync(video_thumb_path,0755);
                                                            }
                                                            thumb_name = md5_end+".png";
                                                            //截图
                                                            var thumbCommand = 'ffmpeg -i "'+Files[name].filePath+'" -y -f image2 -ss 1 -t 0.001 -s 600*450 '+video_thumb_path+'/'+thumb_name;
                                                            exec(thumbCommand, function callback(error, stdout, stderr) {
                                                                if(error) console.log("thumbCommand=>"+error);
                                                            });
                                                        }
                                                        callbak(null,'success',thumb_name);
                                                    }
                                                    else
                                                    {
                                                        console.log('创建文件超链接失败');
                                                    }
                                                }
                                            ], function (error,result_code,thumb_name) {
                                                if(result_code=='success')
                                                {
                                                    //插入数据库
                                                    var insert_files = "insert into edu_files(size,md5,path,video_thumb,addtime) values("+Files[name].fileSize+",'"+md5_end+"','"+date_folder+"','"+thumb_name+"',"+Date.parse(new Date())/1000+")";
                                                    conn.query(insert_files,function(err_2,result_2){
                                                        if(result_2.insertId>0)
                                                        {
                                                            var update_thumb_sql = '';
                                                            if(thumb_name)
                                                            {
                                                                update_thumb_sql = "video_thumb='"+date_folder+"/"+thumb_name+"',";
                                                            }
                                                            var update_user_file = "update edu_user_file set "+update_thumb_sql+"file_id="+result_2.insertId+" where id="+Files[name].fileId;
                                                            conn.query(update_user_file,function(err_3,result_3){
                                                                if(result_3.affectedRows>0)
                                                                {
                                                                    var update_file_count = "update edu_files set file_count=file_count+1 where id="+result_2.insertId;
                                                                    conn.query(update_file_count,function(err_4,result_4)
                                                                    {
                                                                        if(result_4.affectedRows>0)
                                                                        {
                                                                            socket.emit('done', { 'name': name,'file_id':Files[name].fileId});
                                                                            delete Files[name];
                                                                        }
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                        else
                                        {
                                            console.log('文件上传错误');
                                        }
                                    });
                                }
                            );
                        }
                    }
                    else if (segment.length > 0)
                    {
                        if(Files[name].file_from==1)
                        {
                            fs.write(Files[name].handler, Files[name].data, 0, Files[name].data.length,null,
                                function (err, Writen) {
                                    Files[name].data = '';
                                    socket.emit('moreData', {
                                        'name':name,
                                        'position': Files[name].getPosition(),
                                        'percent': Files[name].getPercent()
                                    });
                                }
                            );
                        }
                        else
                        {
                            fs.write(Files[name].handler, Files[name].data, null, 'Binary',
                                function (err, Writen) {
                                    Files[name].data = '';
                                    socket.emit('moreData', {
                                        'name':name,
                                        'position': Files[name].getPosition(),
                                        'percent': Files[name].getPercent()
                                    });
                                }
                            );
                        }
                    }
                    else
                    {
                        socket.emit('moreData', {
                            'name':name,
                            'position': Files[name].getPosition(),
                            'percent': Files[name].getPercent()
                        });
                    }
                }
            });
            conn.release();
        });
    });
});

http.listen(3000, function(){
    console.log('listening on *:3000');
});

