/**
 * 
 */
$(function(){
     var lesson_id=$("#classArticle").data("id");
     var lesson,study_last,teacher;//课程详情 最新章节 老师信息
     COURSEDETAIL.getCommentList({lesson_id:lesson_id},function(serverData){//获得评论

         if(serverData.code === 0){
             var course_comment = serverData.data.comment_list;
             $('#cmtList').empty();
             $('#cmtList').html(template('comment-list',{courseComments:course_comment}));
             $('#comment_count').html(course_comment.length);//评论条数

         }else{
             layer.msg(serverData.msg,{
                 icon:5,
                 time:1000
             });
         }
     });
	/*标头选中*/
    $('#navbar-collapse-basic ul .course').addClass('active');
    /*tab 切换*/
    $(".tabNav li").on("click",function(){
        if($(this).hasClass('readonly')){
            layer.msg('请先参加学习！',{
                icon:5,
                time:1000
            });
            return false;
        }
        $(this).addClass("on").siblings().removeClass("on");
        $(".tabCon").eq($(".tabNav li").index($(this))).show().siblings(".tabCon").hide();
    });

    //点击 参加学习
    $('.aHead').delegate('.js-learn','click',function(){
        var $this=$(this);
        COURSEDETAIL.LearnLesson({lesson_id:lesson_id},function(serverData) {
            console.log(serverData);
            if(serverData.code === 0){
                $this.replaceWith('<a href="/course/play/'+serverData.data.lessonListID+'" class="playBtn bc3">继续学习</a>');
                $('.cata-list').removeClass('readonly');//移除目录禁点class
                $(".tabNav li:eq(1)").trigger('click');
            }else if(serverData.code === -1){
                loginFrame();
            }else{
                layer.msg(serverData.msg,{
                    icon:5,
                    time:1000
                });
            }
        });
    });
    //是自己发布的触发事件
    if($('.js-teacher').data('uid') == $("#js-user-name").data('uid')){
        $(".tabNav li:eq(1)").trigger('click');
    };
    /*目录*/
    $('.js-course-section li').off();
    $("#catalog").delegate("li","mouseenter",function(){
        $(this).addClass("on");
    });
    $("#catalog").delegate("li","mouseleave",function(){
        $(this).removeClass("on");
    });
    $('.js-course-section').on('click','li',function(){
        console.log('a');
        if(!$('#js-user-name').size()){
            loginFrame();
            return false;
        }
    });
    /*评论*/
    $('.course-comment').on('click', function () {
        var refUid = 0;
        var refId = 0;
        var content = $('.course-comment-input').val();
        COURSEDETAIL.courseComment(refUid,refId,content);
    });
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
   
    
});
