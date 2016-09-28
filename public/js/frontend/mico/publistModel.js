/**
 * Created by 20150112 on 2016/8/4.
 */

var PUBLISTMODEL = {
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
    getMlessonList: function(page,subjectID,keywords){
        var data ={url:'/api/mlesson/getMlessonList',page:page,subjectID:subjectID,keywords:keywords}
        PUBLISTMODEL.remote_interface_api(data,function(serverData){
            layer.close(loading);
            if(serverData.code == 0){
                var list=serverData.data.mLessons,allCount=serverData.data.total;
                $('#js-list').html(template('templateList',{list:list}));
                if(list == ''){
                    $('#js-list').html('<p class="nothing">暂无相关微课</p>')
                }
                PUBLISTMODEL.pagination(allCount,page);
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    getChildSubject:function(page,keywords){
        var data ={url:'/api/subject/getChildSubject',page:page,keywords:keywords}
        PUBLISTMODEL.remote_interface_api(data,function(serverData){
            layer.close(loading);
            if(serverData.code == 0){
                console.log(serverData);
                var list=serverData.data.subjects;
                if(page == 1){
                    $('#js-subject-list').empty();
                }
                $('#js-subject-list').append(template('template-subject',{list:list}));
                if(list == ''){
                    layer.msg('没有更多了');
                    $('.js-more').text('加载完成');
                    $('#js-subject-wrap .js-more').off('click');
                }
            }
            else{
                layer.msg(serverData.msg);
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
            PUBLISTMODEL.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            // var $nowCol = RESOURCE.getColType($('ul.nav_list li.sel_list_ele')); //获取当前页的共有数据
            PUBLISTMODEL.getMlessonList(startPage,subjectID,keywords);
        });
    },
}
