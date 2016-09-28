/**
 * Created by 20150112 on 2016/4/7.
 */
$(function () {
    /*标头选中*/
    MANAGE.getMyLessons(1);
    //发布 or 取消发布课程
    $('#classList .list').delegate('.js-push','click',function(){
        var $this=$(this);
        var cur_id=$this.parents('.item').data('id');
        var type=$this.data('type');
        var content=type==1?'确定发布么?':'确定取消发布么?';
        layer.confirm(content, {
            time: 0 //不自动关闭
            ,btn: ['确定', '取消']
            ,yes: function(index){
                MANAGE.pushLesson({lesson_id:cur_id,type:type},function(data){
                    console.log(data);
                    if(data.code==0){//成功
                        var text=type==1?'发布成功!':'取消发布成功!';
                        $this.data('type',type==1?2:1);
                        $this.html(type==1?'<i class="fa-push"></i>取消发布':'<i class="fa-push"></i>发布');
                        $this.parents().siblings('.p3').find('.state span').html(type==1?'已发布':'未发布');
                        layer.msg(text);
                    }else{
                        layer.msg(data.msg);
                    }
                });
            }
        });
    });
    $('#classList').delegate('.js-ask','click',function(){
        $(".js-poplayer-content").empty();
        var lesson_id = $(this).closest('.item').data('id');
        layer.open({
            type: 1,
            title: '学生提问',
            shadeClose: true,
            move: false,
            area: ['600px', "90%"],
            content: $(".popLayer"),
            success:function(){
                MANAGE.lessonManagerAskList(lesson_id);
            }

        });
    });
    //学生学习列表
    $('#classList').delegate('.js-study','click',function(){
        $(".js-poplayer-content").empty();
        var lesson_id = $(this).closest('.item').data('id');
        layer.open({
            type: 1,
            title: '学情监控',
            shadeClose: true,
            move: false,
            area: ['1000px', "90%"],
            content: $(".popLayer"),
            success: function(layero, index){
                MANAGE.getlessonStudentList(lesson_id);
            }
        });
    });
    //提问回复
    $('body').on("click", ".reply", function () {
        $(this).hide();
        $(this).next().show();
    });
    $('body').on('click','.js-replay-ask',function(){
        var content= $.trim($(this).prev('textarea[name="replay-content"]').val()),ref_uid = $(this).data('refuid') ,ref_id =$(this).data('refid');
        if(content == '' || content.length == 0){
            console.log('为空');
            return false;
        }
        MANAGE.refAsk(content,ref_uid,ref_id);
    });
    //


});