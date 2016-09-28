/**
 * Created by 20150112 on 2016/4/8.
 */
var STUDY={
    headActive: function ($del,$tar) {
        $del.removeClass('active');
        $tar.addClass('active');
    },
    /*微课学习记录*/
    getMlessonStudy :function(page){
        var data ={url:'/api/mlesson/getMlessonStudy',page:page}
        remote_interface_api(data,function(serverData){
            console.log(serverData);
            if(serverData.code == 0){
                var list=serverData.data.mLessons,allCount=serverData.data.count;
                console.log(serverData);
                $('.classList').html(template('templateList',{list:list}));
                STUDY.pagination(allCount,page);
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
            MYMICO.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            MYMICO.getMyMlessons(startPage);
        });
    },
};