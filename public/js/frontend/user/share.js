/**
 * Created by 20150112 on 2016/8/17.
 */

var USERSHARE={
    getMyPush:function(obj,page){
        var data ={url:'/api/user/getMyPush',page:page};
        remote_interface_api(data,function(serverData){
            if(serverData.code === 0){
                var list =serverData.data.myPushList;
                var count =serverData.data.total;
                $("#"+obj).html(template(obj+'-template',{list:list}));
                USERSHARE.pagination(count,page);
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    getMyShare:function(obj,page){
        var data ={url:'/api/user/getMyShare',page:page};
        remote_interface_api(data,function(serverData){
            if(serverData.code === 0){
                var list =serverData.data.myShareList;
                var count =serverData.data.total;
                $("#"+obj).html(template(obj+'-template',{list:list}));
                USERSHARE.pagination(count,page);
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    getMyShow:function(obj,page){
        var data ={url:'/api/user/getMyShow',page:page};
        remote_interface_api(data,function(serverData){
            if(serverData.code === 0){
                var list =serverData.data.myShowList;
                var count =serverData.data.total;
                $("#"+obj).html(template(obj+'-template',{list:list}));
                USERSHARE.pagination(count,page);
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    /*加载分页*/
    pagination: function(allCount,startPage) {
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
            USERSHARE.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function() {
        $('.page-wrap  li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            console.log(objName);
            if(objName=='mypush'){
                USERSHARE.getMyPush(objName,startPage);
            }
            else if(objName=='myshare'){
                USERSHARE.getMyShare(objName,startPage);
            }
            else if(objName=='myshow'){
                USERSHARE.getMyShow(objName,startPage);
            }
        });
    },
}
var objName = null;
$(function(){
    $('.nav-tabs li').off('click');
    $('.nav-tabs li').on('click',function(){
        objName = $(this).find('a').attr("href").split('#')['1'];
        if(objName=='mypush'){
            USERSHARE.getMyPush(objName,1);
        }
        else if(objName=='myshare'){
            USERSHARE.getMyShare(objName,1);
        }
        else if(objName=='myshow'){
            USERSHARE.getMyShow(objName,1);
        }
    })
    $(".nav-tabs li:first").trigger('click');
})
