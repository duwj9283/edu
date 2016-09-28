/**
 * 课程js
 */
var COURSEMODEL={
    remote_interface_api : function (data,cb,be) {
        $.ajax({
            url:data.url,
            type:'post',
            data:data,
            dataType:'json',
            beforeSend:function () {
                loading=layer.msg('加载中', {area:'100px',icon: 16});
            },
            //fail:function(){
            //    layer.msg('加载失败请刷新重试', {icon: 5});
            //}
        }).done(cb);
    },
    getLessons: function (page,subjectId,type,keywords,sort) {
        $('.live-list').empty();
        var data={url:'/api/Lesson/getLessons',page:page,subjectId:subjectId,type:type,keywords:keywords,sort:sort};
        COURSEMODEL.remote_interface_api(data, function (serverData) {
            layer.close(loading);
            if(serverData.code === 0){
                var list = serverData.data.lessons,count=serverData.data.lessonCounter;
                $('.js-course-list').html(template('templateList',{list:list}));
                if(list == ''){
                    $('.js-course-list').html('<p class="nothing">暂无相关课程</p>')
                }
                COURSEMODEL.pagination(count,page);
            }else{
                layer.msg(serverData.msg,{
                    icon:5,
                    time:1000,
                });
            }
        });
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
            COURSEMODEL.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            // var $nowCol = RESOURCE.getColType($('ul.nav_list li.sel_list_ele')); //获取当前页的共有数据
            COURSEMODEL.getLessons(startPage,subjectID);
        });
    },

};






