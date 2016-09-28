/**
 * Created by 20150112 on 2016/4/7.
 */
var MANAGE={
    headActive: function ($del,$tar) {
        $del.removeClass('active');
        $tar.addClass('active');
    },
    showColSide: function ($t) {
        $t.show();
        $('.col_side_wrap.body_side .col_side_2_title.my_course').addClass('gly-up');
        $t.find('ul li.btn_manege_course').addClass('selected');
    },
    //获取list
    getMyLessons: function (page) {
        var data={url:'/api/lesson/getMyLessons',page:page}
        remote_interface_api(data,function(serverData){
            var list =serverData.data.lessons,count=serverData.data.total;
            for(var item in list){
                list[item].addtime=TimeStamp(list[item].addtime).Format("yyyy年M月d日");
            }
            $('#classList .list').html(template('templateList',{list:list}));
            MANAGE.pagination(count,page);
        })
    },
    getLessonDetail: function (data,cb) {
        /**{'lesson_id':1}*/
        $.post('/api/lesson/getLessonDetail',data,cb,'json');
    },
    pushLesson: function (data,cb) {
        /**{'lesson_id':1,'type':1}*/
        $.post('/api/lesson/pushLesson',data,cb,'json');
    },
    //获取提问
    lessonManagerAskList:function(lesson_id){
        var data ={url:'/api/lesson/lessonManagerAskList',lesson_id:lesson_id}
        remote_interface_api(data,function(serverData){
            if(serverData.code == 0){
                var list =serverData.data.ask_list;
                console.log(list);
                //for(var item in list){
                //    list[item].create_time=TimeStamp(list[item].create_time).Format("yyyy年M月d日");
                //}
                $('.js-poplayer-content').html(template('templateQuestion',{list:list}));
            }
            else{
                console.log(serverData.msg);
            }
        })
    },
    //回复提问
    refAsk:function(content,ref_uid,ref_id){
        var data ={url:'/api/lesson/refAsk',content:content,ref_uid:ref_uid,ref_id:ref_id}
        remote_interface_api(data,function(serverData){
            var content = serverData.data.content.content;
            var id = serverData.data.content.ref_id;
            $('#js-reply-'+id).show();
            $('#js-reply-'+id).append('<p>'+content+'</p>');
            $('#js-'+ref_id).find('.replayForm').remove();
        })
    },
    //获取学习会员
    getlessonStudentList :function(lesson_id){
        var data ={url:'/api/lesson/getlessonStudentList',lesson_id:lesson_id}
        remote_interface_api(data,function(serverData){
            console.log(serverData);
            var list =serverData.data.user_list;
            //for(var item in list){
            //    list[item].create_time=TimeStamp(list[item].create_time).Format("yyyy年M月d日");
            //}
            console.log(list);
            $('.js-poplayer-content').html(template('templateStudent',{list:list}));
        })
    },
    /*加载分页*/
    pagination: function  (allCount,startPage) {
        /*
         * allCount:需要分页的总数
         * eCount：每页显示数目
         * startPage:起始页
         * */
        var eCount = 12;
        var pageCount = Math.ceil(allCount / eCount);
        var pageHtml =  page(pageCount,eCount,startPage);
        $('.page-wrap').empty();
        if (pageHtml !== ''){
            $('.page-wrap').html(pageHtml);
            MANAGE.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            MANAGE.getMyLessons(startPage);
        });
    },
};