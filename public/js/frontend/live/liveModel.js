/**
 * Created by 20150112 on 2016/8/2.
 */

var LIVEMODEL={
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
    //获取直播列表
    getPubList: function(page,status,keywords){
        var data={url:'/api/live/getPubList',page:page,status:status,keywords:keywords};
        //var data=$.extend({
        //    url:'/api/live/getPubList',
        //    page:1,
        //},options);
        LIVEMODEL.remote_interface_api(data, function (serverData) {
            layer.close(loading);
            if(serverData.code == 0){
                var total = serverData.data.total;
                var publicLiveList = serverData.data.publicLiveList;
                LIVEMODEL.pagination(total,page);
                if(publicLiveList == ''){
                    $('#js-list').html('<p class="nothing">暂无相关直播</p>');
                    return false;
                }
                $('#js-list').html(template('template-live',{publicLiveList:publicLiveList}));
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
            LIVEMODEL.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            // var $nowCol = RESOURCE.getColType($('ul.nav_list li.sel_list_ele')); //获取当前页的共有数据
            LIVEMODEL.getPubList(startPage,iStatus,oKeywords);  //加载当前页内容
        });
    },
}

