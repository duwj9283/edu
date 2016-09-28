/**
 * Created by 20150112 on 2016/5/4.
 */
var FILEDETAIL={
    init: function () {
        $('#navbar-collapse-basic li.active').removeClass('active');
        $('#navbar-collapse-basic li.source').addClass('active');
        /*初始化视频容器*/
        jwplayer.key="O4G/7OoH6r9ioOg0VZQ1Ptmr+rAfP9BNQQzQYQ==";
        var videoUid =$('#video-container').data('uid');
        var videoPath =$('#video-container').data('path');
        var videoFileName =$('#video-container').data('filename');
        var videoFid =$('#video-container').data('fid');
        if( videoUid && videoPath && videoFileName && videoFid){
            var fileNAmeArr = videoFileName.split('.');
            var fileFormat = fileNAmeArr[fileNAmeArr.length-1].toLowerCase();
            var fileDir = videoUid+videoPath+videoFileName;
            var fUrl = config.rtmp+fileFormat+':'+fileDir;
            var coverSrc = "/api/source/getImageThumb/"+videoFid+"/260/260";
            playerInstance = jwplayer('video-container').setup({
                flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                file:fUrl,
                image: coverSrc,
                width: '100%',
                height: 500,
                dock: false,
                autostart:true,
                skin: {
                    name: "vapor"
                }
            }).on('ready',function(){

            });
        }

        /*初始化音频容器*/
        var audioUid =$('#audio-container').data('uid');
        var audioPath =$('#audio-container').data('path');
        var audioFileName =$('#audio-container').data('filename');
        var audioFid =$('#audio-container').data('fid');
        if(audioUid && audioPath && audioFileName && audioFid){
            var fileNAmeArr = audioFileName.split('.');
            var fileFormat = fileNAmeArr[fileNAmeArr.length-1].toLowerCase();
            var fileDir = audioUid+audioPath+audioFileName;
            var data = {url:'/api/file/fileMp3Preview',file_id:audioFid};
            if(fileFormat == 'mp3'){
                FILEDETAIL.remote_interface_api(data, function (serverData) {
                    fUrl = serverData.data.path;
                    var index = null;
                    playerInstance = jwplayer('audio-container').setup({
                        flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                        file:fUrl,
                        image: '/img/frontend/tem_material/audio.png?v=1',
                        width: '100%',
                        height: 500,
                        dock: false,
                        skin: {
                            name: "vapor"
                        }
                    }).on('ready',function(){

                    });
                });
            }
            else{
                var fileNAmeArr = audioFileName.split('.');
                var fileFormat = fileNAmeArr[fileNAmeArr.length-1];
                var fileDir = audioUid+audioPath+audioFileName;
                var fUrl = config.rtmp+fileFormat+':'+fileDir;
                playerInstance = jwplayer('audio-container').setup({
                    flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                    file:fUrl,
                    image: '/img/frontend/tem_material/audio.png?v=1',
                    width: '100%',
                    height: 500,
                    dock: false,
                    autostart:true,
                    skin: {
                        name: "vapor"
                    }
                }).on('ready',function(){

                });
            };
        }
        $('#video-container .jw-icon.jw-icon-inline.jw-button-color.jw-reset.jw-icon-playback').on('click', function () {
            playerInstance.play();
        });

    },
    collectFile: function (fileId) {
        var data={url:'/api/file/collectFile',file_id:fileId};
        FILEDETAIL.remote_interface_api(data, function (serverData) {
            console.log(serverData);
            if(serverData.code === 0){
                layer.msg('收藏成功',{
                    icon:1,
                });
            }else if(serverData.code === -1){
                loginFrame();
            }else{
                layer.msg(serverData.msg,{
                    icon:5,
                });
            }
        });
    },
    download:function(fileId){
        if(!$('#js-user-name').size()){
            loginFrame();
            return false;
        }
        var file_ids=[];
        file_ids.push(fileId);
        var data = {url:'/frontend/file/downloadFiles',file_ids:file_ids};
        FILEDETAIL.remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $("#contextMenu").stop().fadeOut(200);
                var dlUrl = serverData.data;
                window.location.href = dlUrl;
            }else if(serverData.code == -1){
                loginFrame();
            }
        });
    },
    /*评论*/
    fileComment: function (ref_uid,ref_id,content) {
        var file_id = $('#file_info_container').data('id');
        var data ={url:'/api/file/commentUserFile',file_id:file_id,ref_uid:ref_uid,ref_id:ref_id,content:content};
        console.log(data);
        FILEDETAIL.remote_interface_api(data, function (serverData) {
            if(serverData.code === 0 ){
                $('.file-comment-input').val('');
                FILEDETAIL.getCommentTemplate();
            }else{
                layer.msg(serverData.msg,{
                    icon:5,
                    time:1000
                });
            }
        });
    },
    /*点击评论回复*/
    clickReply:function($t){
        $('.replay-row-container').remove();
        var $p = $t.parents('.comment-row');
        var commentUId = $t.data('cmtUid');
        var commentId = $t.data('cmtId');
        $p.append(template('replay-row-container',{commentUId:commentUId,commentId:commentId}));
        /*发表回复*/
        $('.btn-reply-comment').on('click',function(){
            var refUid = $(this).data('cmtUid');
            var refId = $(this).data('cmtId');
            var content = $(this).parent().find('.reply-comment-input').val();
            if(content == '' || content.length == 0){
                layer.tips('评论不能为空','.replay-row-container .reply-comment-input',{
                    tips:[3,'#2cb07e'],
                });
                return false;
            }
            FILEDETAIL.fileComment(refUid,refId,content);
            return false;
        });
    },
    /*重新生成评论*/
    getCommentTemplate: function () {
        var file_id = $('#file_info_container').data('id');
        var data = {url:'/api/file/getFileDetail',file_id:file_id};
        FILEDETAIL.remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                var file_comment = serverData.data.file.file_comment;
                $('#cmtList').empty();
                $('#cmtList').html(template('comment-list',{fileComments:file_comment}));
                FILEDETAIL.byBind();
            }else{
                layer.alert(serverData.msg,{
                    icon:5,
                });
            }
        });
    },
    /*动态绑定事件*/
    byBind: function () {
        /*回复评论*/
        $('#comments .reply').unbind();
        $('#comments .reply').on('click', function () {
            FILEDETAIL.clickReply($(this));
            event.stopPropagation();
        });
    },
    remote_interface_api: function (data,cb) {
        $.ajax({
            url:data.url,
            type:'post',
            data:data,
            dataType:'json'
        }).done(cb);
    },
};