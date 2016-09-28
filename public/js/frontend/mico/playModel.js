/**
 * Created by 20150112 on 2016/8/4.
 */

var MICOPLAY = {
    /*评论*/
    courseComment: function (m_lesson_id,ref_uid,ref_id,content) {
        if(content==''){return false;}
        var data ={url:'/api/mlesson/commentLesson',m_lesson_id:m_lesson_id,ref_uid:ref_uid,ref_id:ref_id,content:content};
        console.log(data);
        remote_interface_api(data, function (serverData) {
            console.log(serverData);
            if(serverData.code === 0){
                $('.course-comment-input').val('');
                layer.msg('评论成功')
                //COURSEDETAIL.getCommentTemplate();
                MICOPLAY.getCommentTemplate();
            }else{
                layer.msg(serverData.msg,{
                    icon:5,
                    time:1000
                });
            }
        });
    },
    /*回复评论*/
    clickReply:function($t){
        $('.replay-row-container').remove();
        var $p = $t.parents('.comment-row');
        var commentUId = $t.data('cmtUid');
        var commentId = $t.data('cmtId');
        $p.append(template('replay-row-container',{commentUId:commentUId,commentId:commentId}));
        /*发表回复*/
        $('.btn-reply-comment').on('click',function(){
            var ref_uid = $(this).data('cmtUid');
            var ref_id = $(this).data('cmtId');
            var content = $(this).parent().find('.reply-comment-input').val();
            if(content == '' || content.length == 0){
                layer.tips('评论不能为空','.replay-row-container .reply-comment-input',{
                    tips:[3,'#2cb07e'],
                });
                return false;
            }
            MICOPLAY.courseComment(m_lesson_id,ref_uid,ref_id,content);
            return false;
        });
    },
    /*动态绑定事件*/
    byBind: function () {
        /*回复评论*/
        $('#comments .reply').unbind();
        $('#comments .reply').on('click', function () {
            MICOPLAY.clickReply($(this));
            event.stopPropagation();
        });
    },
    /*重新生成评论*/
    getCommentTemplate: function () {
        var data = {url:'/api/mlesson/getMlessonDetail',m_lesson_id:m_lesson_id};
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                var file_comment = serverData.data.comment_list;
                $('.cList').empty();
                $('.cList').html(template('comment-list',{fileComments:file_comment}));
                MICOPLAY.byBind();
            }else{
                layer.alert(serverData.msg,{
                    icon:5,
                });
            }
        });
    },

}
