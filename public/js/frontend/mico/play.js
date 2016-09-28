/**
 * Created by 20150112 on 2016/8/4.
 */
var m_lesson_id = $('input[name="lesson_id"]').val();
$(function(){
    /*提交评论*/
    $('.content').delegate('.course-comment','click', function () {
        var ref_uid = 0;
        var ref_id = 0;
        var content = $('.course-comment-input').val();
        if(content == '' || content.length == 0){
            layer.tips('评论不能为空','.course-comment-input',{
                tips:[3,'#2cb07e'],
            });
            return false;
        }
        MICOPLAY.courseComment(m_lesson_id,ref_uid,ref_id,content);
    });
    //创建回复文本框
    $('.content').delegate('#cmtList .reply','click', function () {
        $t=$(this);
        $('.replay-row-container').remove();
        var $p = $t.parents('.comment-row');
        var commentUId = $t.data('cmt-uid');
        var commentId = $t.data('cmt-id');
        $p.append(template('replayRowTem',{commentUId:commentUId,commentId:commentId}));
        return false;
    });
    //提交回复
    //$('.content').delegate('.btn-reply-comment','click', function () {
    //    var ref_uid = $(this).data('cmtUid');
    //    var ref_id = $(this).data('cmtId');
    //    var content = $(this).parent().find('.reply-comment-input').val();
    //    if(content == '' || content.length == 0){
    //        layer.tips('评论不能为空','.replay-row-container .reply-comment-input',{
    //            tips:[3,'#2cb07e'],
    //        });
    //        return false;
    //    }
    //    MICOPLAY.courseComment(m_lesson_id,ref_uid,ref_id,content);
    //
    //
    //});
    MICOPLAY.byBind();
    //播放视频
    jwplayer.key="O4G/7OoH6r9ioOg0VZQ1Ptmr+rAfP9BNQQzQYQ==";
    var fileName= $('.share-file-name').text();
    var fileId = $('input[name="file_id"]').val();
    var fileNAmeArr = fileName.split('.');
    var filePath = $('input[name="path"]').val();
    var fileFormat = $('input[name="ext"]').val();
    var fUrl = config.rtmp + fileFormat.toLowerCase() + ':' +filePath;
    console.log(fUrl);
    var coverSrc = '/api/source/getImageThumb/'+fileId+'/1000/800'
    var index=null;
    /*初始化视频播放*/
    if (fileFormat == 'mp3'){
        $.post('/api/file/fileMp3Preview',{file_id:fileId},function(serverData){
            fUrl = serverData.data.path;
            var index=null;
            playerInstance = jwplayer('file-video').setup({
                flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                file:fUrl,
                image: '/img/frontend/tem_material/audio.png?v=1',
                //file:'rtmp://123.59.146.75/vod/1.mp4',
                width: '100%',
                height:'100%',
                //aspectratio:"4:3",
                dock: false,
                skin: {
                    name: "vapor"
                }
            });
        },'json');
    }
    else{
        playerInstance = jwplayer('file-video').setup({
            flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
            file:fUrl,
            image:coverSrc,
            //file:'rtmp://123.59.146.75/vod/1.mp4',
            width: '100%',
            height:'100%',
            //aspectratio:"4:3",
            dock: false,
            skin: {
                name: "vapor"
            }
        });
    }
    /*取消回复*/
    $('body').on('click', function(event) {
        var ifRm =true;
        var evt = event.srcElement ? event.srcElement : event.target;             // IE支持 event.srcElement ， FF支持 event.target
        if($(evt).hasClass('replay-row-container') || $(evt).hasClass('reply-comment-input') || $(evt).hasClass('btn-reply-comment')){
            ifRm=false;
        }
        if(ifRm){
            $('.replay-row-container').remove();
        }
    });
    //观看人数
    $.post('/api/mlesson/inMlesson',{m_lesson_id:m_lesson_id},function(){},'json');
})