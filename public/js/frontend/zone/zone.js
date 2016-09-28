/**
 * Created by 20150112 on 2016/7/28.
 */
var ZONEMODEL = {
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
    /*加载分页*/
    pagination: function  (startPage,keywords) {
        /*
         * allCount:需要分页的总数
         * eCount：每页显示数目
         * startPage:起始页
         * */
        var keywords = keywords ? keywords : '';
        var allCount =  $('[data-total]').data('total');
        var eCount = 12;
        var pageCount = Math.ceil(allCount / eCount);
        var pageHtml =  page(pageCount,eCount,startPage);
        $('.page-wrap').empty();
        if (pageHtml !== ''){
            //pageHtml+='<div class="page-control"><span class="fl">共'+pageCount+'页,</span><span class="fl go-page">到<input type="text" value="1"  />页</span><a class="fl">确认</a></div>';
            $('.page-wrap').html(pageHtml);
            ZONEMODEL.paginationBind(keywords);
        }
    },
    /*分页跳转函数*/
    paginationBind: function  (keywords) {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            // var $nowCol = RESOURCE.getColType($('ul.nav_list li.sel_list_ele')); //获取当前页的共有数据
            ZONEMODEL.getZoneList(startPage,zoneKewords,fatherSubject);  //加载当前页内容
        });
    },
    getZoneList: function (page,keywords,father_subject) {
        fatherSubject =  father_subject;
        var data={url:'/api/user/getZoneList',page:page,keywords:keywords,father_subject:father_subject}
        ZONEMODEL.remote_interface_api(data,function(serverData){
            layer.close(loading);
            if(serverData.code == 0){
                $('[data-total]').data('total',serverData.data.total);
                var userInfoList = serverData.data.userInfoList;
                if(userInfoList == ''){
                    $('.no-zone-user').show();
                    $('#js-user-list').empty().hide();
                }
                else{
                    $('#js-user-list').show();
                    $('.no-zone-user').hide();
                    $('#js-user-list').html(template('template-userlist',{userInfoList:userInfoList}));
                }
                ZONEMODEL.pagination(page,keywords);
            }
        });
    },
}
var fatherSubject = 0;
var zoneKewords = null;
var zonePage = 1;
$(function(){
    ZONEMODEL.getZoneList(1);
    //pagination(1);
    //搜索用户
    $('input[name="zone-user-name"]').on("keypress",function(event){
        if(event.keyCode == "13"){
            zoneKewords = $.trim($(this).val());
            ZONEMODEL.getZoneList(1,zoneKewords,fatherSubject)
        }
    });
    $('.js-search-zone').on('click',function(){
        zoneKewords = $.trim($('input[name="zone-user-name"]').val());
        ZONEMODEL.getZoneList(1,zoneKewords,fatherSubject)
    })
    //通过专业检索用户
    $('.js-menu li').off();
    $('.js-menu li').on('click',function(){
        $(this).addClass('active').siblings().removeClass('active');
        fatherSubject = $(this).data('id');
        ZONEMODEL.getZoneList(zonePage,zoneKewords,fatherSubject);
    })
})
