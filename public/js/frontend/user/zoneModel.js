var ZONEMODEL={
    //资源列表
    getUserZoneFile: function (startPage,templateName) {
        var data={url:'/api/zone/getUserZoneFile',page: startPage, uid: uid};
        remote_interface_api(data,function(serverData){

            if (serverData.code === 0) {
                var allFileCounter = serverData.data.total;
                var list = serverData.data.visibleFiles;
                $('#js-content').html(template('zone-'+templateName, {list: list,total:allFileCounter}));
                ZONEMODEL.operateFile(); //绑定资源操作事件
                ZONEMODEL.pagination(allFileCounter,24,startPage);
            }
            else {
                layer.msg(serverData.msg);
            }
        });
        //$.post('/api/zone/getUserZoneFile',data,cb,'json');
    },
    //课程列表
    getUserZoneLesson: function (startPage,templateName) {
        var data={url:'/api/zone/getUserZoneLesson',page: startPage, uid: uid};
        remote_interface_api(data,function(serverData){

            if (serverData.code === 0) {
                var list = serverData.data.lessonList;
                $('#js-content').html(template('zone-'+templateName, {list: list}));
                ZONEMODEL.pagination(11,24,startPage);
            }
            else {
                layer.msg(serverData.msg);
            }
        });
    },
    //微课列表
    getUserZoneMlesson: function (startPage,templateName) {
        var data={url:'/api/zone/getUserZoneMlesson',page: startPage, uid: uid};
        remote_interface_api(data,function(serverData){

            if (serverData.code === 0) {
                var list = serverData.data.mlessonList;
                $('#js-content').html(template('zone-'+templateName, {list: list}));
                ZONEMODEL.pagination(11,24,startPage);
            }
            else {
                layer.msg(serverData.msg);
            }
        });
    },
    //直播列表
    getUserZoneLive: function (startPage,templateName) {
        var data={url:'/api/zone/getUserZoneLive',page: startPage, uid: uid};
        remote_interface_api(data,function(serverData){

            if (serverData.code === 0) {
                var list = serverData.data.liveList;
                $('#js-content').html(template('zone-'+templateName, {list: list}));
                ZONEMODEL.pagination(11,24,startPage);
            }
            else {
                layer.msg(serverData.msg);
            }
        });
    },
    //资源下载
    downloadFiles: function () {
        var ifDown = false;
        var file_ids=[];
        $("#js-zone-resource-list").children('.file-item.active').each(function () {
            ifDown = true;
            var fileId = $.trim($(this).data('id'));
            file_ids.push(fileId);
        });
        if(ifDown){
            var data={url:'/frontend/file/downloadFiles',file_ids: file_ids};
            remote_interface_api(data,function(serverData){
                if(serverData.code === 0){
                    var dlUrl = serverData.data;
                    window.location.href = dlUrl;
                }
                else{
                    layer.msg('serverData.msg')
                }
            });
        }else{
            layer.msg("请先选择需要下载的文件");
        }
    },
    //文章列表
    getMyNewsList: function (startPage,templateName) {
        var data={url:'/api/news/getMyNewsList',page: startPage, uid: uid};
        remote_interface_api(data,function(serverData){
            console.log(serverData);
            if (serverData.code === 0) {
                var list = serverData.data.newsList;
                var allFileCounter = serverData.data.total;
                $('#js-content').html(template('zone-'+templateName, {list: list,total:allFileCounter}));
                ZONEMODEL.operateFile(); //绑定资源操作事件
                ZONEMODEL.pagination(allFileCounter,24,startPage);
            }
            else {
                layer.msg(serverData.msg);
            }
        });
    },
    //动态列表
    getDynamicList: function (startPage,templateName) {
        var data={url:'/api/user/getDynamicList',page: startPage, uid: uid};
        remote_interface_api(data,function(serverData){
            if (serverData.code === 0) {
                var list = serverData.data.dynamics;
                var allFileCounter = serverData.data.total;
                $('#js-content').html(template('zone-'+templateName, {list: list,total:allFileCounter}));
                ZONEMODEL.pagination(allFileCounter,24,startPage);
            }
            else if(serverData.code === -1){
                $('#js-content').html('<p class="nothing text-center">登录后可查看</p>')
                loginFrame();
                return false;
            }
            else {
                layer.msg(serverData.msg);
            }
        });
    },
    //关注用户
    followUser: function($this) {
        var data={url:'/api/user/follow',tuid:uid};
        remote_interface_api(data,function(serverData){
            if(serverData.code === 0){
                $this.attr('status','1');
                $this.text('取消关注');
                $this.addClass('un-follow');
                layer.msg('关注成功');
                var num = parseInt($('.js-follow-num').text())
                $('.js-follow-num').text(num+1);
            }
            else if(serverData.code === -1){
                loginFrame();
                return false;
            }
            else{
                layer.msg(serverData.msg);
            }
        })
    },
    //取消关注
    unFollowUser: function($this) {
        var data={url:'/api/user/unFollow',tuid:uid};
        remote_interface_api(data,function(serverData){
            if(serverData.code === 0){
                $this.attr('status','0');
                $this.text('关注');
                $this.removeClass('un-follow');
                layer.msg('成功取消关注');
                var num = parseInt($('.js-follow-num').text())
                $('.js-follow-num').text(num-1);
            }
            else{
                layer.msg(serverData.msg);
            }
        })
    },
    /*评论动态*/
    commentDynamic: function(dynamic_id,ref_uid,ref_id,content){
        var data ={url:'/api/user/commentDynamic',dynamic_id:dynamic_id,ref_uid:ref_uid,ref_id:ref_id,content:content};
        remote_interface_api(data,function(serverData){
            if(serverData.code === 0){
                layer.msg('评论成功');
                $('.collapse').collapse('show');
                var refUserInfo = serverData.data.refUserInfo;
                var userInfo = serverData.data.userInfo;
                var content = serverData.data.content;
                if(ref_id==0){
                    $('#js-replay-box-'+dynamic_id).closest('.js-item').find('ul').prepend(template('comment-template', {userInfo: userInfo,refUserInfo:refUserInfo,content:content}));
                }
                else{
                    $('#js-replay-box-'+dynamic_id).closest('li').after(template('comment-template', {userInfo: userInfo,refUserInfo:refUserInfo,content:content}));
                }
                $('#js-replay-box-'+dynamic_id).find('textarea').val('');
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    /*加载分页*/
    pagination: function(allCount,eCount,startPage) {
        /*
         * allCount:需要分页的总数
         * eCount：每页显示数目
         * startPage:起始页
         * */
        var pageCount = Math.ceil(allCount / eCount);
        var pageHtml =  page(pageCount,eCount,startPage);
        $('.page-wrap').empty();
        if (pageHtml !== ''){
            //pageHtml+='<div class="page-control"><span class="fl">共'+pageCount+'页,</span><span class="fl go-page">到<input type="text" value="1"  />页</span><a class="fl">确认</a></div>';
            $('.page-wrap ').html(pageHtml);
            ZONEMODEL.paginationBind();
        }
    },
    /*分页跳转函数*/
    paginationBind: function() {
        $('.page-wrap  li a').on('click', function () {
            var startPage = parseInt($(this).attr('rel'));
            if(templateName=='file'){ //加载资源列表
                ZONEMODEL.getUserZoneFile(startPage,templateName);
            }
            else if(templateName=='news'){ //加载新闻
                ZONEMODEL.getMyNewsList(startPage,templateName);
            }
            else if(templateName=='course'){ //获取课程
                ZONEMODEL.getUserZoneLesson(startPage,templateName);
            }
            else if(templateName=='mico'){ //获取微课
                ZONEMODEL.getUserZoneMlesson(startPage,templateName);
            }
            else if(templateName=='live'){ //获取直播
                ZONEMODEL.getUserZoneLive(startPage,templateName);
            }
            else if(templateName=='dongtai'){ //获取动态
                ZONEMODEL.getDynamicList(startPage,templateName);
            }
        });
    },
    //资源操作
    operateFile: function(){
        $("#js-zone-resource-list .file-item").undelegate();
        // 显示隐藏点击操作组件
        $("#js-zone-resource-list").delegate('.file-item:not(".active")','mouseenter',function(){
            $(this).find('.js-check').fadeIn();
        });
        $("#js-zone-resource-list").delegate('.file-item:not(".active")','mouseleave',function(){
            $(this).find('.js-check').fadeOut();
        });
        $(".js-check").off('click');
        $(".js-check").on('click',function(event){
            $(this).parents(".file-item").hasClass("active") ? $(this).parents(".file-item").removeClass("active") : $(this).parents(".file-item").addClass("active");
            event.stopPropagation();
        });
        //下载
        $("#js-down-btn").off();
        $("#js-down-btn").on("click",function(){
            ZONEMODEL.downloadFiles();
        });
    },
};