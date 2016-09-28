$(function () {
    //动态tab切换
    //zoneModel.getUserZoneFile({page: 1, uid: uid}, zoneFileList);
    $("#js-zone-menu").off('click','li');
    $("#js-zone-menu").on("click",'li:not(".active")',function () {
        var name=$(this).data('name');
        $("#js-all").hide();
        $("#js-content").parent().show();
        $(".dongtai").show();
        if(name=='all'){
            $("#js-all").show();
            $("#js-content").parent().hide();
        }
        else if(name=='file'){ //加载资源列表
            templateName = 'files';
            ZONEMODEL.getUserZoneFile(1,templateName);
            //zoneModel.getUserZoneFile({page: 1, uid: uid}, function (serverData) {
            //    var list = serverData.data.visibleFiles;
            //    getTabData(serverData,24,1,templateName,list);
            //});
        }
        else if(name=='news'){ //加载新闻
            templateName = 'news';
            ZONEMODEL.getMyNewsList(1,templateName);
        }
        else if(name=='course'){ //获取课程
            templateName = 'course';
            ZONEMODEL.getUserZoneLesson(1,templateName);
        }
        else if(name=='mico'){ //获取微课
            templateName = 'mico';
            ZONEMODEL.getUserZoneMlesson(1,templateName);
        }
        else if(name=='live'){ //获取直播
            templateName = 'live';
            ZONEMODEL.getUserZoneLive(1,templateName);
        }
        else if(name=='dongtai'){ //获取动态
            templateName = 'dongtai';
            ZONEMODEL.getDynamicList(1,templateName);
            $(".dongtai").hide();
        }
        $(this).addClass('active').siblings().removeClass('active');
    });
    $("#js-zone-menu a.dynamic").trigger('click'); //默认加载的选项
    //关注用户
    $("#js-follow-btn").on('click',function(){
        $this = $(this) ;
        if($this.attr('status') == 1){
            ZONEMODEL.unFollowUser($this)
        }
        else{
            ZONEMODEL.followUser($this)
        }

    });
});

var templateName=null;
var uid = $("#js-person-name").data('uid'); //获取uid
//资源跳转
$("body").off('click','.js-file-url');
$("body").on('click','.js-file-url',function(event){
    if($("#js-user-name").size()>=1){
        window.open($(this).data('url'),'_blank ');
    }
    else{
        loginFrame();
    }
});
//评论动态
$("body").off('click','.js-show-reply-dt');
$("body").on('click','.js-show-reply-dt',function(event){
    var _this = $(this);
    var _box =$('.js-replay-box');
    var dynamic_id = _this.closest('.js-item').data('id'),
        ref_uid = _this.data('uid'),
        ref_id = _this.data('id');
    $('.js-show-reply-dt:hidden').show();
    _this.hide();
    _box.remove();
    _this.parent().after(template('replay-dongtai', {id:dynamic_id}));
    event.stopPropagation(); //阻止事件冒泡
    $(".js-dongtai").off('click','.js-replay-dt');
    $(".js-dongtai").on('click','.js-replay-dt',function(){
        var _this= $(this);
        console.log($(".js-dongtai .js-replay-dt").size());
        var content = $.trim($('textarea[name="dt-msg"]').val());
        ZONEMODEL.commentDynamic(dynamic_id,ref_uid,ref_id,content);
    });
});
$('body').on('click','.js-replay-box',function(event){
    event.stopPropagation(); //阻止事件冒泡

});
$(document).off('show-dt');
$(document).on('click.show-dt',function(){
    var box =$('.js-replay-box');
    if($.trim(box.find('textarea').val()) != ''){
        $(document).off('show-dt');
        return false;
    }
    box.remove();
    $('.js-show-reply-dt:hidden').show();
});
/*设置文档类型*/
template.helper('setType', function (data, type) {
    var dataSuffix=data.slice(data.lastIndexOf("."));
    if(dataSuffix===".pptx" || dataSuffix===".ppt"){
        type='ppt';
    }
    if(dataSuffix===".docx" || dataSuffix===".doc"){
        type='word';
    }
    if(dataSuffix===".xlsx" || dataSuffix===".xls"){
        type='xls';
    }
    if(dataSuffix===".txt"){
        type='txt';
    }
    if(dataSuffix===".pdf"){
        type='pdf';
    }
    if(dataSuffix===".rar"){
        type='rar';
    }
    if(dataSuffix===".zip"){
        type='zip';
    }
    if(dataSuffix===".xml"){
        type='xml';
    }
    return type;
});