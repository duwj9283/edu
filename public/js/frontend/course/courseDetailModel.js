/**
 * 课程js
 */
var COURSEDETAIL={

     /*评论*/
    courseComment: function (ref_uid,ref_id,content) {
        if(content=='' || content.length == 0 ){return false;}
        var lesson_id = $("#classArticle").data("id");
        var data ={url:'/api/lesson/commentLesson',lesson_id:lesson_id,ref_uid:ref_uid,ref_id:ref_id,content:content};
        console.log(data);
        COURSEDETAIL.remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $('.course-comment-input').val('');
                COURSEDETAIL.getCommentTemplate();
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
            COURSEDETAIL.courseComment(refUid,refId,content);
            return false;
        });
    },
    /*重新生成评论*/
    getCommentTemplate: function (lesson_id) {
        var data ={url:'/api/Lesson/getLessonDetail',lesson_id:lesson_id};
        console.log(data);
        COURSEDETAIL.remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                //评论
                var course_comment = serverData.data.comment_list;
                $('#cmtList').empty();
                $('#cmtList').html(template('comment-list',{courseComments:course_comment}));
                COURSEDETAIL.byBind();
            }else{
                layer.alert(serverData.msg,{
                    icon:5,
                });
            }
        });
    },

    getLessonDetail: function (lesson_id,callback) {
        var data ={url:'/api/Lesson/getLessonDetail',lesson_id:lesson_id};
        COURSEDETAIL.remote_interface_api(data, callback);
    },
    LearnLesson: function (data,callback) {//参加学习
        $.post('/api/lesson/learn',data,callback,'json');
    },
    getCommentList: function (data,callback) {//评论
        $.post('/api/lesson/lessonCommentList',data,callback,'json');

    },
     /*动态绑定事件*/
    byBind: function () {
        /*回复评论*/
        $('#comments .reply').unbind();
        $('#comments').delegate('.reply','click', function () {
            COURSEDETAIL.clickReply($(this));
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






