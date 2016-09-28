/**
 * Created by 20150112 on 2016/5/3.
 */
var RESOURCE={
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
    /*公共方法绑定*/
    bind: function () {
        $('.js-list .item').off();
        $('.js-list .item').on('mouseenter',function(){
            $(this).find('.item-detail').fadeIn();
        })
        $('.js-list .item').on('mouseleave',function(){
            $(this).find('.item-detail').fadeOut();
        })
    },
    /*获取资源*/
    getPushFiles: function (type,subjectID,page,templateName) {
        var data={url:'/api/file/getPushFiles',type:type,subjectID:subjectID,page:page};
        RESOURCE.remote_interface_api(data, function (serverData) {
            layer.close(loading);
            if(serverData.code === 0){
                var userFiles = serverData.data.userFiles;
                var allFileCounter = serverData.data.allFileCounter;
                $('.js-list').html(template(templateName,{userFiles:userFiles,allFileCounter:allFileCounter}));
                if(userFiles == ''){
                    $('.js-list').html('<p class="nothing">暂无相关共享资源</p>')
                }
                RESOURCE.bind();
                RESOURCE.pagination(allFileCounter,page); //重新加载分页
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
            //pageHtml+='<div class="page-control"><span class="fl">共'+pageCount+'页,</span><span class="fl go-page">到<input type="text" value="1"  />页</span><a class="fl">确认</a></div>';
            $('.page-wrap').html(pageHtml);
            RESOURCE.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function  () {
        $('.pagination li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            RESOURCE.getPushFiles(type,subjectID,startPage,templateName);  //加载当前页内容
        });
    },


};