/* 展开我的消息内容 */
var newsModel={
    /*获取文章*/
    getMyNewsList:function(data,cb){
        $.post('/api/news/getMyNewsList',data,cb,'json');
    },
    /*加载分页*/
    pagination: function  (startPage) {
        /*
         * allCount:需要分页的总数
         * eCount：每页显示数目
         * startPage:起始页
         * */
        var allCount = $('#js-news').data('total');
        var eCount = 24;
        var pageCount = Math.ceil(allCount / eCount);
        var pageHtml =  page(pageCount,eCount,startPage);
        $('.pager-wrap').empty();
        if (pageHtml !== ''){
            $('.pager-wrap').html(pageHtml);
            newsModel.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pager-wrap ul li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            newsModel.newsList(startPage);
        });
    },
    newsList: function(startPage){
        newsModel.getMyNewsList({page: startPage,type:1},function(serverData){
            if(serverData.code === 0){
                var list=serverData.data.newsList;
                $("#js-news").html(template('templateNewsList',{list:list}));
                newsModel.pagination(startPage);
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
}
$(function(){
    newsModel.newsList(1);
});

