/**
 * Created by 20150112 on 2016/8/4.
 */

var MICODETAIL = {
    /*����*/
    courseComment: function (ref_uid,ref_id,content) {
        if(content=='' || content.length == 0 ){return false;}
        var lesson_id = $("#classArticle").data("id");
        var data ={url:'/api/mlesson/commentLesson',lesson_id:lesson_id,ref_uid:ref_uid,ref_id:ref_id,content:content};
        console.log(data);
        remote_interface_api(data, function (serverData) {
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
    /*������ۻظ�*/
    clickReply:function($t){
        $('.replay-row-container').remove();
        var $p = $t.parents('.comment-row');
        var commentUId = $t.data('cmtUid');
        var commentId = $t.data('cmtId');
        $p.append(template('replay-row-container',{commentUId:commentUId,commentId:commentId}));
        /*����ظ�*/
        $('.btn-reply-comment').on('click',function(){
            var refUid = $(this).data('cmtUid');
            var refId = $(this).data('cmtId');
            var content = $(this).parent().find('.reply-comment-input').val();
            if(content == '' || content.length == 0){
                layer.tips('���۲���Ϊ��','.replay-row-container .reply-comment-input',{
                    tips:[3,'#2cb07e'],
                });
                return false;
            }
            COURSEDETAIL.courseComment(refUid,refId,content);
            return false;
        });
    },
    /*������������*/
    getCommentTemplate: function (lesson_id) {
        var data ={url:'/api/mlesson/getMlessonDetail',lesson_id:lesson_id};
        console.log(data);
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                //����
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
}
