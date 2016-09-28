var FILEMODEL={
    /*这里直接调用的api接口*/
    remote_interface_api: function (data,cb) {
        $.ajax({
            url:data.url,
            type:'post',
            data:data,
            dataType:'json'
        }).done(cb);
    },
    toggleItemSelect: function ($t,$p) {
        if($p.hasClass('itemSelected')){
            $p.removeClass('itemSelected');
        }
        else{
            $p.addClass('itemSelected');
        }
    },
    /*单个下载*/
    downloadItem:function($t){
        var folderID =$t.attr('folder');
        var file_ids=[];
        file_ids.push(folderID);
        var data = {url:'/frontend/file/downloadFiles',file_ids:file_ids};
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $("#contextMenu").stop().fadeOut(200);
                var dlUrl = serverData.data;
                window.location.href = dlUrl;
            }
        });
    },
    /*单个发布文件*/
    pushFile: function ($t) {
        /*初始化学科*/
        subObject.init({
            pt:'34',
            ct:'35',
        });
        var folderID = $t.attr('folder');
        layer.open({
            type: 1,
            area: '580px',
            title:'发布',
            scrollbar:false,
            content: $('#push-file-popover'), //这里content是一个普通的String
            success: function (layero,index) {
                $('#push-file-popover .btn-push-file').off();
                $('#push-file-popover .btn-push-file').on('click', function () {
                    var subject_id = $('#subject-select .sub-child-type').val();
                    var application_type =$('#push-file-popover .use-type').val();
                    var knowledge_point =$('#push-file-popover input.kld-point').val();
                    var language =$('input[name="lan-radio-option"]:checked').val();
                    var desc= $('#push-file-popover .push-file-intro').val();
                    if(!subject_id || !application_type || !knowledge_point || !language || !desc){
                        layer.msg('请完善信息再发布',{time:1000});
                        return false;
                    };
                    var data = {url:'/api/File/pushFileToPublic',subject_id:subject_id,file_id:folderID,application_type:application_type,knowledge_point:knowledge_point,language:language,desc:desc};
                    remote_interface_api(data, function (serverData) {
                        layer.close(index);
                        if(serverData.code === 0 ){
                            $('.detail_body_wrapper .item[folder='+folderID+'] .icon-status').append('<i class="icon-push-wait"></i>');
                            $('.detail_body_wrapper .item[folder='+folderID+']').removeClass('itemSelected');
                            layer.msg('已提交，待管理员审核！', {
                               icon: 1,
                               time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            });
                       }else{
                            layer.msg(serverData.msg, {
                                icon: 5,
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            });
                        }
                    });
                });
            },
        });
    },
    /*移动到*/
    moveTo: function ($t) {
        var oldPath=$t.attr('level')+'/';
        var fileId=$t.attr('folder');
        $('#dragMove .g-button-move').data('oldPath',oldPath);  //将原路径存储起来，以便后面移动到新路径的时候进行匹配
        $('#dragMove .g-button-move').data('fileId',fileId);  //将id存储起来，以便后面移动到新路径的时候进行匹配

        $('.detail_wrapper .item.moveSelect').removeClass('moveSelect');  //移除其他原有的移动选中状态
        $t.addClass('moveSelect');  //选择元素添加移动选中状态

        $('#dragMove .treeview.treeview-root-content').empty(); //清空原有的目录树
        $('#dragMove .treeview.treeview-root-content').prev().addClass('treeview-node-on'); //设置根目录为选中状态
        var path='/';
        FILEMODEL.getFolderDir(path);
        FILEMODEL.setMulMoveMode(0);   //设置批量移动模式
        $('.module-canvas').show();
        $('#dragMove').show();
    },

    /*批量移动*/
    multipleMove:function(){
        var ifDel = false;
        var file_ids=[];
        var oldPath=[];
        $('.detail_body_wrapper').children('.item.itemSelected').each(function () {
            var fileId = $.trim($(this).attr('folder'));
            file_ids.push(fileId);
            oldPath.push($(this).attr('level')+'/');
            ifDel = true;
        });
        if(ifDel){
            $('#dragMove .g-button-mul-move').data('mul-oldPath',oldPath);  //将原路径存储起来，以便后面移动到新路径的时候进行匹配
            $('#dragMove .g-button-mul-move').data('mul-fileIds',file_ids);  //将id存储起来，以便后面移动到新路径的时候进行匹配
            $('#dragMove .treeview.treeview-root-content').empty(); //清空原有的目录树
            $('#dragMove .treeview.treeview-root-content').prev().addClass('treeview-node-on'); //设置根目录为选中状态
            var path='/';
            FILEMODEL.getFolderDir(path);
            FILEMODEL.setMulMoveMode(1);   //设置批量移动模式
            $('.module-canvas').show();
            $('#dragMove').show();
            /* var data ={url:'/api/file/mvFilesToFolder'};
             remote_interface_api(data, function () {

             });*/
        }else{
            layer.msg("请选择要移动的文件");
            //$('#modal_pop_wrap .pop_content').text('请先选择需要删除的文件');
            //$('#modal_pop_wrap').modal('show');
        }
    },
    /*显示批量移动按钮*/
    setMulMoveMode:function(type){
        if(type == 0){
            $('.g-btn-mode.g-button-mul-move').hide();
            $('.g-btn-mode.g-button-move').show();
        }else{
            $('.g-btn-mode.g-button-move').hide();
            $('.g-btn-mode.g-button-mul-move').show();
        }
    },

    /*获取目录下的子目录*/
    getFolderDir:function(path){
        var data ={url:'/api/file/getFolder',path:path};
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                var userFolder = serverData.data.userFolder;
                var treeStr =  FILEMODEL.treeViewEleStr(userFolder);
                $('#dragMove .treeview-node-on').next().html(treeStr);
                FILEMODEL.bindDragMove();
            }else{
                layer.msg(serverData.msg);
                //$('#modal_pop_wrap .pop_content').text(serverData.msg);
                $('.detail_wrapper .item.contextSelect').removeClass('contextSelect');
                $("#contextMenu").stop().fadeOut(200);
                //$('#modal_pop_wrap').modal('show');
            }
        });
    },
    /*
     * 绑定目录弹框的点击事件
     * */
    bindDragMove:function(){
        $('#dragMove .treeview-node').off('click');
        $('#dragMove .treeview-node').on('click', function () {
            /*
             * 1、判断是否处于 选中且打开状态，是：关闭
             * 2、判断是否处于 选中但关闭状态，是：打开
             * 3、判断是否处于 未选中但打开状态，是：选中
             * 4、判断是否处于 未选中且关闭状态， 是：选中且打开
             * */
            if($(this).hasClass('treeview-node-on') && $(this).hasClass('_minus')){
                //alert('选中且打开状态');
                $(this).removeClass('_minus');
                $(this).next().addClass('treeview-collapse');
                $(this).find('.b-in-blk.plus.icon-operate').removeClass('minus');  //小图标处理
                //$(this).next().empty();
                $(this).next().find('li').remove();
            }else if($(this).hasClass('treeview-node-on') && !$(this).hasClass('_minus')){
                //alert('选中但关闭');
                $(this).addClass('_minus');
                //这里判断是否有子文件夹，有的话打开，没有的话仅仅是选中就行；有的话打开
                if( !$(this).hasClass('treenode-empty')){
                    $(this).addClass('_minus');
                    $(this).next().removeClass('treeview-collapse');
                    $(this).find('.b-in-blk.plus.icon-operate').addClass('minus');  //小图标处理
                    //重新获取子文件夹
                    var path=$(this).find('.treeview-txt').attr('node-path');
                    //var path=$(this).find('.treeview-txt').attr('node-target');
                    if(path !== '/'){
                        path = path+'/';  //非根目录路径的路径后要加一个斜杠
                    }else{
                        path = '/';
                    }
                    FILEMODEL.getFolderDir(path);
                }
            }else if(!$(this).hasClass('treeview-node-on') && $(this).hasClass('_minus')){
                // alert('未选中但打开状态');
                $('#dragMove .treeview-node').removeClass('treeview-node-on');
                $(this).addClass('treeview-node-on');
            }else{
                // alert('未选中且关闭状态');
                $('#dragMove .treeview-node').removeClass('treeview-node-on');
                $(this).addClass('treeview-node-on');
                //这里判断是否有子文件夹，有的话打开，没有的话仅仅是选中就行；有的话打开
                if( !$(this).hasClass('treenode-empty')){
                    $(this).next().removeClass('treeview-collapse');
                    $(this).addClass('_minus');
                    $(this).find('.b-in-blk.plus.icon-operate').addClass('minus');  //小图标处理
                    //重新获取子文件夹
                    var path=$(this).find('.treeview-txt').attr('node-path');
                    //var path=$(this).find('.treeview-txt').attr('node-target');
                    if(path !== '/'){
                        path = path+'/';  //非根目录路径的路径后要加一个斜杠
                    }else{
                        path = '/';
                    }
                    FILEMODEL.getFolderDir(path);
                }
            }
        });
        /*批量移动*/
        $('#dragMove .g-button-mul-move').off('click');
        $('#dragMove .g-button-mul-move').on('click', function () {
            var oldPath = $(this).data('mul-oldPath');
            var fileIds = $(this).data('mul-fileIds');
            var newPath = $('#dragMove .treeview-node.treeview-node-on').find('.treeview-txt').attr('node-path');
            if(newPath !='/'){
                newPath = newPath+'/';
            }else{
                newPath = '/';
            }
            if(newPath == oldPath[0]){
                layer.msg("不能移动到原文件夹");
                //$('#modal_pop_wrap .pop_content').text('不能移动到原文件夹');
                //$('#modal_pop_wrap').modal('show');
                return false;
            }
            var folderId =$('#dragMove .treeview-node.treeview-node-on').find('.treeview-txt').attr('node-target');
            var data = {url:'/api/file/mvFilesToFolder',file_ids:fileIds,folder_id:folderId};
            remote_interface_api(data, function(serverData){
                if(serverData.code === 0){
                    layer.msg('移动成功');
                    $('.detail_body_wrapper .item.itemSelected').remove();
                    $('#dragMove .dialog-icon.dialog-close').trigger('click');
                    $(".toolbar_wrap .simple_btn:not('.back_dir')").css("display","none");
                }else{
                    /*命名重复的情况下*/
                    layer.confirm(serverData.msg, {
                        btn: ['确定','取消'] //按钮
                    },function(index){
                        layer.close(index);
                        remote_interface_api(data, function (serverData) {
                            var data = {url:'/api/file/mvFilesToFolder',file_ids:fileIds,folder_id:folderId,is_force:1};
                            remote_interface_api(data, function (serverData) {
                                if(serverData.code ===0){
                                    layer.msg('移动成功');
                                    $('.detail_body_wrapper .item.itemSelected').remove();
                                    $('#dragMove .dialog-icon.dialog-close').trigger('click');
                                    $(".toolbar_wrap .simple_btn:not('.back_dir')").css("display","none");
                                }else{
                                    layer.msg(serverData.msg);
                                }
                            });
                        });
                    });
                }
            });

        });
        /*点击确定按钮，移动文件夹*/
        $('#dragMove .g-button-move').off('click');
        $('#dragMove .g-button-move').on('click', function () {
            var oldPath = $(this).data('oldPath');
            var newPath = $('#dragMove .treeview-node.treeview-node-on').find('.treeview-txt').attr('node-path');
            //var newPath = $('#dragMove .treeview-node.treeview-node-on').find('.treeview-txt').attr('node-target');
            if(newPath !='/'){
                newPath = newPath+'/';
            }else{
                newPath = '/';
            }
            if(newPath == oldPath){
                layer.msg("不能移动到原文件夹");
                //$('#modal_pop_wrap .pop_content').text('不能移动到原文件夹');
                //$('#modal_pop_wrap').modal('show');
                return false;
            }
            var folderId =$('#dragMove .treeview-node.treeview-node-on').find('.treeview-txt').attr('node-target');

            var fileId = $(this).data('fileId');

            var data = {url:'/api/file/mvFileToFolder',file_id:fileId,folder_id:folderId};
            remote_interface_api(data, function (serverData) {
                if(serverData.code === 0){
                    layer.msg('文件移动成功');
                    $('.detail_body_wrapper .item.moveSelect').remove();
                    $('#dragMove .dialog-icon.dialog-close').trigger('click');
                    $(".toolbar_wrap .simple_btn:not('.back_dir')").css("display","none");
                }else if(serverData.code === 4){
                    /*命名重复的情况下*/
                    //var htmlStr = "<a class='btn btn-primary btn_move_continue' data-backdrop='static'>确定</a>";
                    //$('#modal_pop_wrap .modal-footer').append(htmlStr);
                    //$('#modal_pop_wrap .pop_content').text(serverData.msg);
                    //$('#modal_pop_wrap').modal('show');
                    layer.confirm(serverData.msg, {
                            btn: ['确定','取消'] //按钮
                    },function () {
                        //$('#modal_pop_wrap .modal-footer .btn_move_continue').remove(); //去掉确定按钮
                        var data = {url: '/api/file/mvFileToFolder', file_id: fileId, folder_id: folderId, is_force: 1};
                        remote_interface_api(data, function (serverData) {
                            if (serverData.code === 0) {
                                //$('#modal_pop_wrap .pop_content').text('文件移动成功');
                                layer.msg('文件移动成功');
                                $('.detail_body_wrapper .item.moveSelect').remove();
                                $('#dragMove .dialog-icon.dialog-close').trigger('click');
                                $(".toolbar_wrap .simple_btn:not('.back_dir')").css("display","none");
                            } else {
                                //$('#modal_pop_wrap .pop_content').text();
                                layer.msg(serverData.msg);
                            }
                        });
                    },function(){

                    });
                    /*点击确定继续修改名称*/
                    //$('#modal_pop_wrap .modal-footer .btn_move_continue').on('click',
                    //
                    //});
                }
                //else{
                //    $('#modal_pop_wrap .pop_content').text(serverData.msg);
                //    $('#modal_pop_wrap').modal('show');
                //}
            });
        });
    },
    treeViewEleStr:function(userFolder){
        var htmlstr = '';
        for(var i =0;i<userFolder.length;i++){
            var nodeFolder = userFolder[i];
            //var nodePath =nodeFolder.path+nodeFolder.file_name;
            var nodePath =nodeFolder.path+nodeFolder.id;

            var nodeArray = nodeFolder.path.split('/');
            var nodeCount = nodeArray.length -1;
            var _pl = 15 * parseInt(nodeCount);
            if(nodeFolder.had_folder === 0){
                htmlstr += "<li>";
                htmlstr += "<div class='treeview-node treenode-empty' style='padding-left:"+_pl+"px'>";
                htmlstr += "<span class='treeview-node-handler'>";
                htmlstr += "<em class='b-in-blk plus icon-operate'></em>";
                htmlstr += "<dfn class='b-in-blk treeview-ic'></dfn>";
                htmlstr += " <span class='treeview-txt' node-path='"+nodePath+"' node-target='"+nodeFolder.id+"'>"+nodeFolder.file_name+"</span>";
                htmlstr += "</span>";
                htmlstr += "</div>";
                htmlstr += "<ul class='treeview  treeview-content treeview-collapse'></ul>";
                htmlstr += "</li>";
            }else if(nodeFolder.had_folder === 1){
                htmlstr += "<li>";
                htmlstr += "<div class='treeview-node' style='padding-left:"+_pl+"px'>";
                htmlstr += "<span class='treeview-node-handler'>";
                htmlstr += "<em class='b-in-blk plus icon-operate'></em>";
                htmlstr += "<dfn class='b-in-blk treeview-ic'></dfn>";
                htmlstr += "<span class='treeview-txt' node-path='"+nodePath+"' node-target='"+nodeFolder.id+"'>"+nodeFolder.file_name+"</span>";
                htmlstr += "</span>";
                htmlstr += "</div>";
                htmlstr += "<ul class='treeview  treeview-content treeview-collapse'></ul>";
                htmlstr += "</li>";
            }
        }
        return htmlstr;
    },
    /*批量下载*/
    multipleDownload:function(){
        var ifDown = false;
        var file_ids=[];
        $('.detail_body_wrapper').children('.item.itemSelected').each(function () {
            ifDown = true;
            var fileId = $.trim($(this).attr('folder'));
            file_ids.push(fileId);
        });
        if(ifDown){
            var data = {url:'/frontend/file/downloadFiles',file_ids:file_ids};
            remote_interface_api(data, function (serverData) {
                if(serverData.code === 0){
                    var dlUrl = serverData.data;
                    window.location.href = dlUrl;
                }
            });
        }else{
            layer.msg("请先选择需要下载的文件");
            //$('#modal_pop_wrap .pop_content').text('请先选择需要下载的文件');
            //$('#modal_pop_wrap').modal('show');
        }
    },
    /*批量删除*/
    multipleDel: function () {
        var ifDel = false;
        var file_ids=[];
        $('.detail_body_wrapper').children('.item.itemSelected').each(function () {
            ifDel = true;
            var fileId = $.trim($(this).attr('folder'));
            file_ids.push(fileId);
        });
        if(ifDel){
            var data = {url:'/api/file/deleteBatchFile',file_ids:file_ids};
            layer.confirm('确认要把所选文件放入回收站吗？', {
                btn: ['确定','取消'] //按钮
            },function(index){
                layer.close(index);
                remote_interface_api(data, function (serverData) {
                    if(serverData.code === 0){
                        $('.detail_body_wrapper .item.itemSelected').remove();
                        layer.msg("删除成功");
                        $(".toolbar_wrap .simple_btn:not('.back_dir')").css("display","none");
                        //$('#modal_pop_wrap .pop_content').text('删除成功');
                        //$('#modal_pop_wrap').modal('show');
                        FILEMODEL.getUserCapacity();
                    }else{
                        layer.msg(serverData.msg);
                        //$('#modal_pop_wrap .pop_content').text(serverData.msg);
                        //$('#modal_pop_wrap').modal('show');
                    }
                });
            });
        }else{
            layer.msg("请先选择需要删除的文件");
            //$('#modal_pop_wrap .pop_content').text('请先选择需要删除的文件');
            //$('#modal_pop_wrap').modal('show');
        }
    },
    previewPDF: function ($t) {
        var $p = $t.parents('.item');
        var tid = $.trim($p.attr('folder'));
        //var pSrc = "/3rdpart/generic/web/viewer.html?file=/api/file/filePreview/"+tid;
        $.post('/api/file/filePreview/'+tid,{},function(data){
            if(data.code==0){
                $('#txtPictureIndex').val(1);
                var total=data.data.total;
                var aImages=data.data.images;
                var iPicIndex=$('#txtPictureIndex').val();
                $('.js-ppt .ppt-con img:first').remove();
                $('.js-ppt .ppt-con').append('<img src="'+aImages[0]+'" class="img js-img"  alt="">');
                var oImg=$('.js-ppt .js-img');
                var iHeight=null;
                oImg.load(function(){
                    $(this).show();
                    iHeight=$(this).height();
                    $('.loading').hide();
                });
                //回车跳转
                $('#txtPictureIndex').on("keypress",function(event){
                    if(event.keyCode == "13"){
                        iPicIndex = $.trim($(this).val());
                        showImg();
                    }
                });
                $('.js-ppt .js-total').text(total);
                function showImg(){
                    oImg.attr('src',aImages[iPicIndex-1]);
                    $('.loading').css('height',iHeight).show();
                    oImg.css('display','none');
                    oImg.load(function(){
                        $(this).css('display','block');
                        iHeight=$(this).height();
                        $('.loading').hide();
                    });
                }
                function pptToRight(){
                    if(iPicIndex >= total){
                        return false;
                    }
                    iPicIndex++;
                    $('#txtPictureIndex').val(iPicIndex);
                    showImg();
                }
                function pptToLeft(){
                    if(iPicIndex <= 1){
                        return false;
                    }
                    iPicIndex--;
                    $('#txtPictureIndex').val(iPicIndex);
                    showImg();
                }
                $('.js-ppt .pre,.js-ppt .next').on('click',function(){
                    if($(this).hasClass('pre')){
                        pptToLeft();
                    }
                    else if($(this).hasClass('next')){
                        pptToRight();
                    }
                });
                $(".js-ppt").off('mousemove,click','.js-img');
                $(".js-ppt").on('mousemove','.js-img',function(e) {
                    var positionX = e.pageX - $(this).offset().left || 0;//获取当前鼠标相对img的x坐标
                    if(positionX<=$(this).width()/2){
                        $(this).addClass('to-left').removeClass('to-right');
                    }
                    else{
                        $(this).addClass('to-right').removeClass('to-left')
                    }
                });
                $(".js-ppt").on('click','.js-img',function(e) {
                    var positionX = e.pageX - $(this).offset().left || 0;//获取当前鼠标相对img的x坐标
                    if(positionX<=$(this).width()/2){
                        pptToLeft();
                    }
                    else{
                        pptToRight();
                    }
                });

                layer.open({
                    type: 1,
                    title:false,
                    shade: [1, '#1f1f1f'],
                    closeBtn:0,
                    scrollbar:false,
                    shadeClose:true,
                    area: ["60%","90%"], //宽高
                    content: $(".js-ppt")
                });

            }
            else{
                layer.msg(data.msg);
            }
        },'json');
        //var htmlStr = "<iframe id='iframe-pdf' src="+pSrc+"></iframe>";
        //$('#preview-pdf').append(htmlStr);
       // $('body').addClass('of-hidden');
        //$('#preview-popover').show();
        $(".pv-closed").show();
        $(".pv-closed").click(function(){
            $(this).hide();
            layer.closeAll();
        });
    },
    previewAudio: function($t){
        //$('body').addClass('of-hidden');
        //$('#preview-popover').fadeIn();
        layer.open({
            type: 1,
            title:false,
            closeBtn:0,
            shadeClose:true,
            shade: [1, '#1f1f1f'],
            scrollbar :false,
            area: ['70%','80%'], //宽高
            content: $("#preview-audio"),
        });
    },
    previewVideo: function ($t) {
        var coverSrc = $t.parents('.item_click_area').find('img').attr('src');
        var $p = $t.parents('.item');
        var uid = $('#js-user-name').data('uid');
        var fDir = $p.attr('level');
        var fUrl=null,collectUrl=$p.data("url"),trash=$("#file-list").attr('trash');
        var fileId=$p.attr('folder');
        var fileName = $p.find('.item_info_wrap .item_name .file-name').text();
        if (fDir == '') {
            var fileDir = uid + '/' + fileName;
        } else {
            var fileDir = uid + fDir + '/' + fileName;
        }
        var fileNAmeArr = fileName.split('.');
        var fileFormat = fileNAmeArr[fileNAmeArr.length - 1];
        if (fileFormat == 'mp3'){
            var data = {url:'/api/file/fileMp3Preview',file_id:fileId};
             //$.post('/api/file/fileMp3Preview/'+fileId,{},function(){
             //    console.log(serverData);
             //},'json');
            remote_interface_api(data, function (serverData){
                fUrl = serverData.data.path;
                var index=null;
                playerInstance = jwplayer('preview-video').setup({
                    flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                    file:fUrl,
                    //file:'rtmp://123.59.146.75/vod/1.mp4',
                    image: '/img/frontend/tem_material/audio.png?v=1',
                    width: '100%',
                    height:'100%',
                    //aspectratio:"4:3",
                    dock: false,
                    skin: {
                        name: "vapor"
                    }
                }).on('ready',function(){
                    $(".pv-closed").show();
                    $(".pv-closed").click(function(){
                        $(this).hide();
                        layer.closeAll();
                    });
                    layer.open({
                        type: 1,
                        title:false,
                        closeBtn:0,
                        shadeClose:true,
                        shade: [1, '#1f1f1f'],
                        shift:false,
                        scrollbar :false,
                        area: ['70%','80%'], //宽高
                        content: $("#preview-video"),
                    });
                });
            });
        }
        else{
            /* 判断是不是收藏夹里面的 */
            if (collectUrl) {
                var fUrl = config.rtmp+ fileFormat.toLowerCase() + ':publicpool/' + collectUrl;
            }
            else if(trash){
                var fUrl = config.rtmp + fileFormat.toLowerCase() + ':recoverpool/' + fileDir;
            }
            else {
                var fUrl = config.rtmp + fileFormat.toLowerCase() + ':' + fileDir;
            }
            var index=null;
            /*初始化视频播放*/
            playerInstance = jwplayer('preview-video').setup({
                flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                file:fUrl,
                //file:'rtmp://123.59.146.75/vod/1.mp4',
                image: coverSrc,
                width: '100%',
                height:'100%',
                //aspectratio:"4:3",
                dock: false,
                skin: {
                    name: "vapor"
                }
            }).on('ready',function(){
                $(".pv-closed").show();
                $(".pv-closed").click(function(){
                    $(this).hide();
                    layer.closeAll();
                });
                layer.open({
                    type: 1,
                    title:false,
                    closeBtn:0,
                    shadeClose:true,
                    shade: [1, '#1f1f1f'],
                    shift:false,
                    scrollbar :false,
                    area: ['70%','80%'], //宽高
                    content: $("#preview-video"),
                });
            });
        }

    },
    previewImg: function ($t) {
        var tid = $.trim($t.parents('.item').attr('folder')),width=Math.floor($(window).width()*0.8),height=Math.floor($(window).height()*0.8);
        var imgSrc = '/api/source/getImageThumb/'+tid+'/'+width+'/'+height+'';
        var htmlStr = "<img src="+imgSrc+" id='preview-img'  alt='not authorized' style='display:none;'>";
        $(".pv-closed").show();
        $(".pv-closed").click(function(){
            layer.closeAll();
            $(this).hide();
            $('#preview-img').remove();
        });
        $('#preview-img').remove();
        $('body').append(htmlStr);
        layer.load(1,{
            shade: [1, '#1f1f1f'],
        });
        $("#preview-img").load(function(){
            layer.closeAll('loading');
            layer.open({
                type: 1,
                title:false,
                closeBtn:0,
                shadeClose:true,
                shade: [1, '#1f1f1f'],
                scrollbar :false,
                area: [$('#preview-img').width()+'px',$('#preview-img').height()+'px'], //宽高
                content: $("#preview-img"),
            });
        });
    },
    /*批量发布*/
    multiplePush:function(){
        /*初始化学科*/
        subObject.init({ });
        var file_ids=[];
        var folders = [];
        $('.detail_body_wrapper').children('.item.itemSelected').each(function () {
            var fileId = $.trim($(this).attr('folder'));
           // var path = $.trim($(this).attr('level'));
            file_ids.push(fileId);
           // folders.push(path);
        });
       // var folderID = $t.attr('folder');
        layer.open({
            type: 1,
            area: '580px',
            title:'发布',
            scrollbar:false,
            content: $('#push-file-popover'), //这里content是一个普通的String
            success: function (layero,index) {
                $('#push-file-popover .btn-push-file').off();
                $('#push-file-popover .btn-push-file').on('click', function () {
                    var subject_id = $('#subject-select .sub-child-type').val();
                    var application_type =$('#push-file-popover .use-type').val();
                    var knowledge_point =$.trim($('#push-file-popover input.kld-point').val());
                    var language = $('input[name="lan-radio-option"]:checked').val();
                    var desc= $('#push-file-popover .push-file-intro').val();
                    if(!subject_id || !application_type || !knowledge_point || !language || !desc){
                        layer.msg('请完善信息再发布',{time:1000});
                        return false;
                    };
                    var data = {url:'/api/File/BatchPushFileToPublic',subject_id:subject_id,file_ids :file_ids,application_type:application_type,knowledge_point:knowledge_point,language:language,desc:desc};
                    remote_interface_api(data, function (serverData) {
                        if(serverData.code === 0 ){
                            $('.detail_body_wrapper .item.itemSelected .icon-status').append('<i class="icon-push-wait"></i>');
                            $('.detail_body_wrapper .item.itemSelected').removeClass('itemSelected');
                            layer.msg('已提交，待管理员审核！', {
                                icon: 1,
                                time: 2000, //2秒关闭（如果不配置，默认是3秒）
                            });
                        }else{
                            layer.msg(serverData.msg, {
                                icon: 5,
                                time: 2000, //2秒关闭（如果不配置，默认是3秒）
                            });
                        }
                    });
                    layer.close(index);
                });
            },
        });
    },
    /*搜索文件*/
    searchFile: function (path,keywords,limit) {
        if(_fileType == 'live'){
            var data = {url:'/api/file/getVideoFilesByPage',keywords:keywords};
        }
        else{
            var data = {url:'/api/file/getFilesByPage',path:path,keywords:keywords,limit:12,type:_fileType};
        }
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $('.detail_body_wrapper').empty();
                //$('.detail_body_wrapper').append(template('template-file', {filelist: serverData.data.userFiles}));
                var userFiles = template.compile(temperFile);
                $('.detail_body_wrapper').html(userFiles(serverData.data));
                loadOnBindFun();
                fileLoad = true;
                filePage = 1;
                $(document).on("scroll",function(){
                    _fileType == 'live'?FILEMODEL.getFileData('','',keywords):FILEMODEL.getFileData(path,_fileType,keywords);
                });
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    /*获取文件*/
    getFilesByPage:function(path,type){
        var data = {url:'/api/file/getFilesByPage',path:path,type:type,page:1,limit:12};
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $('#file-list').empty();
                var userFiles = template.compile(temperFile);
                $('#file-list').html(userFiles(serverData.data));
                loadOnBindFun();
                $(document).on("scroll",function(){
                    FILEMODEL.getFileData(getParentDir(),_fileType);
                });
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    /*空间可以见*/
    getFileVisible:function($t,file_id){
        var visibleId=null;
        if($t.find(".icon-eye").length>=1)
        {
            visibleId=0;
            var data = {url:'/api/file/visible',file_id :file_id ,visible :visibleId};
            remote_interface_api(data, function (serverData) {
                if(serverData.code === 0){
                    $t.find(".icon-eye").remove();
                    $t.data("visible",visibleId);
                }
                else
                {
                    layer.msg(serverData.msg)
                }
            });
        }
        else{
            visibleId=1;
            var data = {url:'/api/file/visible',file_id :file_id ,visible :visibleId};
            remote_interface_api(data, function (serverData) {
                if(serverData.code === 0){
                    $t.find(".item_detail .icon-status").append('<i class="icon-eye"></i>');
                    $t.data("visible",visibleId);
                }
                else
                {
                    layer.msg(serverData.msg)
                }
            });
        }
    },
    /*获取直播录像列表*/
    getFileLiveList:function(){
        var data = {url:'/api/file/getVideoFilesByPage'};
        remote_interface_api(data, function (serverData) {
            if(serverData.code===0){
                var userFiles = template.compile(temperFile);
                $('#file-list').html(userFiles(serverData.data));
                $(document).on("scroll",function(){
                    FILEMODEL.getFileData();
                });
                loadOnBindFun();
            }
            else
            {
                layer.msg(serverData.msg)
            }
        });
    },
    /*获取空间可见列表*/
    getFileVisibleList:function(){
        var data = {url:'/api/file/visibleList'};
        remote_interface_api(data, function (serverData) {
            if(serverData.code===0){
                var list = serverData.data.visibleFiles;
                $('.detail_body_wrapper').html(template('visibleList-file',{filelist:list}));
                loadOnBindFun();
            }
            else
            {
                layer.msg(serverData.msg)
            }
        });
    },
    /*获取回收站列表*/
    getTrashFilesByPage:function(path){
        var data = {url:'/api/file/getTrashFilesByPage',page:1};
        remote_interface_api(data, function (serverData) {
            if(serverData.code===0){
                var userFiles = template.compile(temperFile);
                $('.detail_body_wrapper').append(userFiles(serverData.data));
                //$('.detail_body_wrapper').html(template('visibleList-file',{filelist:list}));
                loadOnBindFun();
                $(document).on("scroll",function(){
                    FILEMODEL.getFileData();
                });
            }
            else
            {
                layer.msg(serverData.msg)
            }
        });
    },
    /*取消收藏*/
    delCollectFile:function(){
        $("#contextMenu").stop().fadeOut(200);
        var file_id=$(".item.contextSelect").attr('folder');
        var data ={'url':'/api/file/delCollectFile',file_id :file_id  };
        remote_interface_api(data, function (serverData) {
            if(serverData.code == 0){
                layer.msg("取消收藏成功");
                $(".item.contextSelect").remove();
            }else{
                layer.msg(serverData.msg);
            }
        });
    },
    /*生成分享链接*/
    getPublicLink:function(){
        var share=$('#share-popover');
        var file_id=$(".item.contextSelect").attr('folder');
        var share_input=share.find(".share-link-input");
        var data ={'url':'/api/file/getPublicLink',file_id :file_id};
        remote_interface_api(data, function (serverData) {
            if(serverData.code == 0) {
                share_input.val(config.url+'/file/share/'+ serverData.data.url);
                share_input.select();
                share.find('.button-section').addClass('hide');
                share.find('.share-link').addClass('show');
                //复制分享链接
                $('#share-popover .copy-button-section').zclip({
                    path: "/3rdpart/zclip/ZeroClipboard.swf",
                    copy: function(){
                        return share_input.val();
                    },
                    beforeCopy:function(){
                        share_input.select();
                    },
                    afterCopy:function(){
                        layer.msg('复制成功');
                    },
                });
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    },
    //滚动加载
    getFileData: function (path,type,keywords) {
        var top = $(".detail-add").offset().top;
        if($("#js-file-pages").data('pages')<=1){$(document).off("scroll");return false;} //不够分页取消滚动绑定事件
        if (fileLoad && ($(window).scrollTop() + $(window).height()) > top) {
                fileLoad=false;
                filePage++;
                if($("#file-list").attr('trash')=='true'){ //获取回收站
                  var  data = {url:'/api/file/getTrashFilesByPage',page:filePage};
                }
                else if(_fileType == 'live'){
                   var data = {url:'/api/file/getVideoFilesByPage',page:filePage,keywords:keywords};
                }
                else{
                    var data = {url:'/api/file/getFilesByPage',path: path, page: filePage,type:type,limit: 12,keywords:keywords};
                }
                $('.detail_body_wrapper').append('<div class="loading pull-left text-center col-sm-12">加载中</div>');
                remote_interface_api(data, function (serverData) {
                    if(serverData.code === 0){
                        if(serverData.data.userFiles == ''){
                            $('.detail_body_wrapper .loading').remove();
                            $(document).off("scroll");
                            return false
                        }
                        if(serverData.data.userFiles != ''){
                            var userFiles = template.compile(temperFile);
                            $('.detail_body_wrapper').append(userFiles(serverData.data));
                            loadOnBindFun();
                            $('.detail_body_wrapper .loading').remove();
                            fileLoad=true;
                        }
                    }
                    else{
                        layer.msg(serverData.msg);
                    }
                });
            }
    },
    //显示容量
    getUserCapacity: function (){
        var data ={'url':'/api/user/getUserCapacity'};
        remote_interface_api(data, function (serverData) {
            if(serverData.code ===0 ){
                $(".total_capacity").html(serverData.data.capacity.capacity_all+'GB');
                $(".used_capacity").html(serverData.data.capacity.capacity_used+'GB');
                var percent = (serverData.data.capacity.percent)+'%';
                $(".capacity_progress_bar").animate({"width":percent});
                $(".apply_capacity").attr("apply",serverData.data.capacity.apply_status); //设置提交容量申请后状态
                if($(".apply_capacity").attr("apply")==0){$(".apply_capacity").text('待审核');} //设置提交容量申请后状态
            }
            else{
                layer.msg(serverData.data);
            }
        });
    },
    /*提升容量申请*/
    applyUserCapacity :function(reason){
        var data ={'url':'/api/user/applyUserCapacity',reason:reason };
        remote_interface_api(data, function (serverData) {
            if(serverData.code == 0){
                layer.closeAll();
                layer.msg('申请提交成功！请等待审核！',{icon: 6});
                $(".apply_capacity").attr("apply",0).text('待审核');
            }else{
                layer.closeAll();
                layer.msg(serverData.msg);
            }
        });
    },
};


$(function(){
    /*根据类型获取文件*/
    if( _fileType === 0){
        FILEMODEL.getFilesByPage(getParentDir(),_fileType);
        $('.col_side_wrap .col_side_ul_2 .col_side_list_wrap:first').addClass("selected");
        $(".toolbar_wrap .btn_new").show();
    }
    if( _fileType && typeof _fileType =="number" &&  _fileType!=0){
        FILEMODEL.getFilesByPage(getParentDir(),_fileType);
        $(".toolbar_wrap .btn_new").hide();
        $('.col_side_wrap .col_side_ul_2 .col_side_list_wrap').each(function(){
            if($(this).data("type")==_fileType){
                $(this).addClass("selected").siblings().removeClass("selected");
            }
        });
    }
    if(_fileType && _fileType=='live'){
        FILEMODEL.getFileLiveList()
    }
    /*处理顶部*/
    $('#navbar-collapse-basic ul li.active').removeClass('active');
    $('#navbar-collapse-basic ul li.source').addClass('active');
    /*处理侧边栏*/
    $('.col_side_wrap.body_side .col_side_li_2_wrap.my_file_wrap').show();
    $('.col_side_wrap.body_side .col_side_2_title.my_file').addClass('gly-up');

    /*关闭模态框的默认事件*/
    //$("#modal_pop_wrap").on('hide.bs.modal', function () {
    //    $('#modal_pop_wrap .modal-footer .btn_continue').remove(); //去掉确定按钮
    //    $('#modal_pop_wrap .modal-footer .btn_move_continue').remove(); //去掉确定按钮
    //});
    /*初始化拖拽框*/
    //$('#dragMove').draggable({handle: ".handle_wrap" , scroll: false });

    /*加载一些页面必须绑定的方法*/
    loadOnBindFun();
    /*创建文件夹*/
    $('.btn.btn_new').on('click',function(){
        if($('.detail_body_wrapper .item.crate_folder_wrap').length >0){
            return false;           //如果已经存在，则不建立新的文件夹框
        }
        if($(this).hasClass('disabled')){
            return false;
        }
        var html='<div class="item crate_folder_wrap"><div class="item_detail folder_item"><div class="item_detail_img_wrap"><img class="folder" src="img/frontend/home/folder.png?v=1"></div><div class="item_info_wrap"><div class="item_info_detail"><input type="text" value="新建文件夹" class="folder_name"><a class="btn_create_folder">新建</a><a class="btn_cancel_folder">取消</a></div>' + '</div></div></div>';
        $('.inner_body .detail_wrapper .detail_body_wrapper').prepend(html);
        $(".crate_folder_wrap .folder_name").on("keypress",function(event){
            if(event.keyCode == "13") {
                createFolder();
            }
        });
        $(".crate_folder_wrap .folder_name").trigger("select");
        $('.btn_cancel_folder').on('click', function () {
            $(this).parents('.item').remove();
        });

        $('.btn_create_folder').on('click',createFolder);
        function createFolder(){
            var folder_name = $.trim($('.item .folder_name').val());
            /*判断文件夹名称*/
            if(folder_name.length == 0 || folder_name == '' || !folder_name){
                layer.msg('请先填写文件夹名称');
                return false;
            }
            var folder_id = $('.inner_body .detail_body_wrapper').attr('level');
            var data ={'url':'/api/file/createFolder','path':folder_id+'/','folder_name':folder_name};
            remote_interface_api(data, function (serverData) {
                if(serverData.code == 0){
                    layer.msg('创建成功');
                    /*创建成功后停留在当前页面,并获取当前页面的文件*/
                    FILEMODEL.getFilesByPage(folder_id+'/',$(".detail_body_wrapper").data('type'));
                }else{
                    layer.msg(serverData.msg);
                }
            });
        };
    });
    /*批量下载*/
    $('.toolbar_wrap .btn_multiple_Download').on('click', function () {
        FILEMODEL.multipleDownload();
    });
    /*批量删除*/
    $('.toolbar_wrap .btn_multiple_del').on('click', function () {
        FILEMODEL.multipleDel();
    });
    /*批量发布*/
    $('.toolbar_wrap .btn_multiple_push').on('click', function () {
        FILEMODEL.multiplePush();
    });
    /*批量移动*/
    $('.toolbar_wrap .btn_multiple_move').on('click', function () {
        /*$('#modal_pop_wrap .pop_content').text('目前尚未开通');
        $('#modal_pop_wrap').modal('show');*/
        FILEMODEL.multipleMove();
    });

    /*发布文件-监听radio状态*/
    $('input[name="lan-radio-option"]').on('click',function(){
        $('input[name="lan-radio-option"]').parent().removeClass('has-select');
        if($(this).is(":checked")){
            $(this).parent().addClass('has-select');
        }
    });
    /*批量发布文件-下载配置文件*/
    $('#download-push-config').on('click', function () {
        FILEMODEL.downloadPushConfig();
    });
    /*视频播放器的key*/
    playerInstance = false;
    jwplayer.key="O4G/7OoH6r9ioOg0VZQ1Ptmr+rAfP9BNQQzQYQ==";

    //if($(".detail_body_wrapper").data("type")!=0 && $(".detail_body_wrapper").data("type")){
    //    $(document).on("scroll",function(){
    //        getFileData(getParentDir(),$(".detail_body_wrapper").data("type"));
    //    });
    //}

    /*查看容量*/
    FILEMODEL.getUserCapacity();
    /*提升容量*/
    $(".apply_capacity").on("click",function(){
        if($(".apply_capacity").attr("apply")==0){
         return false;
        }
        if($("#capacity-popover")){
            $("#capacity-popover").remove();
        };
        var capacity = template.compile(capacityPopover);
        $('body').append(capacity);
        layer.open({
            type:"1",
            title:"提升容量申请",
            area:"500px",
            content:$("#capacity-popover"),
        });
        $('form[name="capacity"]').submit(function(){
            var reason= $.trim($('textarea[name="reason"]').val());
            if(!reason){
                return false;
            }
            FILEMODEL.applyUserCapacity(reason);
        });
    });
    /*搜索文件*/
    $('.search-file input[name=file-name]').on('keypress',function(event){
        if(event.keyCode == "13")
        {
            var path = getParentDir(),words = $.trim($('.search-file input[name=file-name]').val()),limit = 12;
            if(path!='/')
            {
                path=path+'/';
            }
            _fileType =='live'?FILEMODEL.searchFile('',words):FILEMODEL.searchFile(path,words,limit);
        }
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
    /*取消收藏*/
    $("#contextMenu .icon_collect_li").off("click");
    $("#contextMenu .icon_collect_li").on("click",function(){
       FILEMODEL.delCollectFile();
    });
});
//设置全局 文件类型
var _fileType=typeof $("#file-list").data('type') == "undefined"?false:$("#file-list").data('type');
var _fileKeyords=null;
var filePage= 1,fileLoad=true;
/*提升容量html*/
var capacityPopover='<div id="capacity-popover">'
    +'<form class="form-horizontal" name="capacity" onsubmit="return false;">'
    +'<div class="form-group">'
    +'<label for="inputEmail3" class="col-sm-3 control-label">增容原因</label>'
    +'<div class="col-sm-8">'
    +'  <textarea class="form-control" rows="5" name="reason"></textarea>'
    +'</div>'
    +'</div>'
    +'<div class="row"><div class="col-sm-8 col-md-offset-3 text-center"><input class="btn" type="submit" value="提交"></div></div>'
    +'</form>'
    +'</div>';
/*获取detail_body_wrapper元素的目录*/
function getParentDir()
{
    var level =  $('.inner_body .detail_body_wrapper').attr('level');
    /*对根目录特殊处理*/
    if(level == ''){
        level='/';
    }
    return level;
};
function loadOnBindFun(){
    /*context menu*/
    $('.detail_body_wrapper .item').off();       //这里涉及到多次绑定的问题
    $('.detail_body_wrapper .item').on('contextmenu',function(event){

        var $_this = $(this);
        var pageX = event.pageX;//鼠标单击的x坐标
        var pageY = event.pageY;//鼠标单击的y坐标
        if(!$(this).hasClass('itemSelected')){
            $('.detail_body_wrapper .item').each(function () {
                $(this).removeClass('contextSelect');
                $(this).removeClass('itemSelected');
                $(this).find(".btn-status").hide();

            });
        }

        /*先删除同类元素中的选中状态,增加选中状态*/

        $_this.addClass('contextSelect');
        $(this).find(".btn-status").show();
        //获取菜单
        var contextMenu = $("#contextMenu");
        var cssObj = {
            top:pageY+"px",//设置菜单离页面上边距离，top等效于y坐标
            left:pageX+"px"//设置菜单离页面左边距离，left等效于x坐标
        };
        //判断横向位置（pageX）+自定义菜单宽度之和，如果超过页面宽度及为溢出，需要特殊处理；
        var menuWidth = contextMenu.width();
        var pageWidth = $(document).width();
        if(pageX+contextMenu.width()>pageWidth){
            cssObj.left = pageWidth-menuWidth-5+"px"; //-5是预留右边一点空隙，距离右边太紧不太美观；
        }
        //判断纵向位置
        var menuHeight = contextMenu.height();
        var pageHeight = $(document).height();
        if(pageY+contextMenu.height()>pageHeight){
            cssObj.top = pageY-menuHeight-5+"px"; //-5是预留右边一点空隙，距离下边太紧不太美观；
        }
        //设置菜单的位置
        $("#contextMenu").css(cssObj).stop().fadeIn(200);
        if($_this.find('.item_detail').hasClass('folder_item')){
            $("#contextMenu .icon_collect_li").addClass('hidden');
            $("#contextMenu .icon_share_li").addClass('hidden');
            $("#contextMenu .icon_move_li").addClass('hidden');
            $("#contextMenu .icon_download_li").addClass('hidden');
            $("#contextMenu .icon_download_li").addClass('hidden');
            $("#contextMenu .icon_share_third").addClass('hidden');
            $("#contextMenu .icon_show_li").addClass('hidden');
        }else{
            $("#contextMenu .icon_download_li").removeClass('hidden');
            $("#contextMenu .icon_collect_li").addClass('hidden');
            $("#contextMenu .icon_share_li").removeClass('hidden');
            $("#contextMenu .icon_move_li").removeClass('hidden');
            $("#contextMenu .icon_share_third").removeClass('hidden');
            $("#contextMenu .icon_show_li").removeClass('hidden');
        }
        /*非全部文件页面不能移动*/

        //var fileType=  $('.inner_body .detail_body_wrapper').data('wrapType');
        if(_fileType !== 0){
            $("#contextMenu .icon_move_li").addClass('hidden');
        }
        /*直播录像列表*/
        if(_fileType === 'live'){
            $('#contextMenu .icon_topic_li').removeClass('hidden');
        }
        /*收藏列表*/
        if($('.col_side_list_wrap.selected').hasClass('btn_icon_collect')){
            $("#contextMenu li").addClass('hidden');
            $("#contextMenu .icon_download_li,#contextMenu  .icon_collect_li ").removeClass('hidden');
            $("#contextMenu  .icon_collect_li .tool_btn_desc").text("取消收藏");
        }
        /*回收站列表*/
        if($('.col_side_list_wrap.selected').hasClass('btn_icon_trash')){
            $("#contextMenu li").addClass('hidden');
            if(!$_this.attr("level")==''){ //判断是不是目录下的
                $('#contextMenu .icon_remove_li').removeClass('hidden');
            }
            else{
                $("#contextMenu .icon_recovery_li,#contextMenu  .icon_remove_li ").removeClass('hidden');
            }
            //$("#contextMenu  .icon_recovery_li .tool_btn_desc").text("恢复");
            //$("#contextMenu  .icon_remove_li .tool_btn_desc").text("移除");
        }
        /*空间可见文字说明*/
        if($_this.find(".icon-eye").length>=1){
            $("#contextMenu .icon_show_li .tool_btn_desc").text("取消空间可见");
        }
        else{
            $("#contextMenu .icon_show_li .tool_btn_desc").text("空间可见")
        }
        /*判断是否显示右键打开*/
        if($_this.data("type")!=2 && $_this.data("type")!=4 && $_this.data("type")!=3 && $_this.data("type")!=5 ){
            $("#contextMenu .icon_open_li").addClass('hidden');
        }
        else{
            $("#contextMenu .icon_open_li").removeClass('hidden');
        };
        return false;
    });
    /*关闭右键菜单*/
    $(document).click(function(event){
    //$('body *:not("#contextMenu")').not('#contextMenu *').mousedown(function(event){
    //$('body *').not('#contextMenu').not('#contextMenu *').mousedown(function(event){
        //button等于0代表左键，button等于1代表中键
        $("#contextMenu").stop().fadeOut(200);//获取菜单停止动画，进行隐藏使用淡出效果
        /*清除所有的右键菜单的选中状态*/
        $('.detail_body_wrapper .item').each(function () {
            $(this).removeClass('contextSelect');
        });
        //event.stopPropagation();  注意：这里不能阻止冒泡事件，否则很多功能会受到影响
    });
    /*进入下一层目录*/
    $('.item .item_click_area.folder_click').off();
    $('.item .item_click_area.folder_click').on('click',function(){
        $(document).off("scroll");
        //var wrapType = $('#file-list').data('type');
        var parentFolder = getParentDir();
        //var folder = '/'+$(this).parent().find('.item_info_wrap .item_name').text();
        var folder = '/'+ $.trim($(this).parents('.item').attr('folder'));
        $(".back_dir").css("display","inline-block");//显示返回上一层
        if(parentFolder == '/'){
            var childFolder = folder;
        }else{
            var childFolder = parentFolder+folder;
        }
        if(_fileType){
            var data ={'url':'/api/file/getFilesByPage','path':childFolder+'/','limit':12,'page':1,'type':_fileType};
        }else{
            var data ={'url':'/api/file/getFilesByPage','path':childFolder+'/','limit':12,'page':1};
        }
        if($("#file-list").attr('trash')=='true'){
            var data ={'url':'/api/file/getTrashFilesByPath','path':childFolder+'/'};
        }
        remote_interface_api(data, function (serverData) {
            if(serverData.code ===0 ){
                //设置当前目录以及父目录
                $('#file-list').attr('level',childFolder);
                $('.toolbar_wrap .back_dir').data('directory',parentFolder);
                /*加载该目录的文件*/
                $('#file-list').empty();
                //var userFiles = serverData.data.userFiles;
                //for(var i=0;i<userFiles.length;i++){
                //    var userFile = userFiles[i];
                //    var htmlstr=getFolderEleStr(userFile);
                //    $('.detail_wrapper .detail_body_wrapper').append(htmlstr);
                //}
                //$('.detail_body_wrapper').append(template('template-file', {filelist: serverData.data.userFiles}));
                var userFiles = template.compile(temperFile);
                $('#file-list').html(userFiles(serverData.data));
                $('#file-list .item').attr('level',childFolder);//设置当前文件所在目录，移动文件时候需要检索
                loadOnBindFun();
                filePage=1;
                fileLoad=true;
                if($("#file-list").attr('trash')=='true'){
                    $(document).on("scroll",function(){
                        FILEMODEL.getFileData(childFolder+'/');
                        $('#file-list .item').attr('level',childFolder);
                    });
                }
                else{
                    $(document).on("scroll",function(){
                        FILEMODEL.getFileData(childFolder+'/',_fileType);
                        $('#file-list .item').attr('level',childFolder);
                    });
                }
            }else{
                layer.msg(serverData.msg);
                //$('#modal_pop_wrap .pop_content').text(serverData.msg);
                //$('#modal_pop_wrap').modal('show');
                //$('#modal_pop_wrap .btn.close_modal_wrap').data('code',serverData.code);
            }
        });
    });
    /*返回上一层目录*/
    $('.toolbar_wrap .back_dir').off();
    $('.toolbar_wrap .back_dir').on('click',function(){
        $(document).off("scroll");
       // var wrapType = $('#file-list').data('type');
        var parentDir = $('.toolbar_wrap .back_dir').data('directory');
        if($('#file-list').data('level')==''){$(".back_dir").hide()}
        if(parentDir){
            /*父级目录处理*/
            if(parentDir == '/'){
                var queryUrl = '';
                $(".back_dir").hide();
            }else{
                var queryUrl = parentDir;
            }
            /*祖父级目录处理*/
            var parentsDir = parentDir.substr(0,parentDir.lastIndexOf("/"));
            if(parentsDir.length ==0){
                parentsDir='/';
            }
            if(_fileType){
                var data ={'url':'/api/file/getFilesByPage','path':queryUrl+'/','page':1,'limit':12,'type':_fileType};
            }else{
                var data ={'url':'/api/file/getFilesByPage','path':queryUrl+'/','limit':12,'page':1};
            }
            if($("#file-list").attr('trash')=='true'){
                var data = parentDir == '/' ? {'url':'/api/file/getTrashFilesByPage','page':1} : {'url':'/api/file/getTrashFilesByPath','path':queryUrl+'/'};
            }
            remote_interface_api(data, function (serverData) {

                if(serverData.code ===0 ){
                    //设置当前目录以及父目录，即：上面设置的父级目录和祖父级目录
                    $('.toolbar_wrap .back_dir').data('directory',parentsDir);
                    $('#file-list').attr('level',queryUrl);
                    /*加载该目录的文件*/
                    $('#file-list').empty();
                    //var userFiles = serverData.data.userFiles;
                    //for(var i=0;i<userFiles.length;i++){
                    //    var userFile = userFiles[i];
                    //    var htmlstr=getFolderEleStr(userFile);
                    //    $('.detail_wrapper .detail_body_wrapper').append(htmlstr);
                    //}
                    //$('.detail_body_wrapper').append(template('template-file', {filelist: serverData.data.userFiles}));
                    var userFiles = template.compile(temperFile);
                    $('#file-list').html(userFiles(serverData.data));
                    loadOnBindFun();
                    filePage=1;
                    fileLoad=true;
                    if($("#file-list").attr('trash')){
                        if(parentDir == '/'){
                            $(document).on("scroll",function(){
                                FILEMODEL.getFileData(parentDir);
                            });
                        }
                    }
                    else{
                        $(document).on("scroll",function(){
                            FILEMODEL.getFileData(parentDir,_fileType);
                        });
                    }

                }else{
                    layer.msg(serverData.msg);
                    //$('#modal_pop_wrap .pop_content').text(serverData.msg);
                    //$('#modal_pop_wrap').modal('show');
                    //$('#modal_pop_wrap .btn.close_modal_wrap').data('code',serverData.code);
                }
            });

        };
    });
    /* 显示操作组件 */
    $('.item .item_click_area').off("mouseenter");
    $('.item .item_click_area').on("mouseenter",function(){
        $(this).find('.btn-status').show();
    });
    $('.item .item_click_area').on("mouseleave",function(){
        if($(this).parents(".item").hasClass("itemSelected") || $(this).parents(".item").hasClass("contextSelect")){}
        else{$(this).find('.btn-status').hide();};
    });
    /*选中、取消文件*/
    $('.item .item_click_area .btn-status').off();

    $('.item .item_click_area .btn-status').on('click', function () {
        var $p = $(this).parents('.item');
        FILEMODEL.toggleItemSelect($(this),$p);
        if($(".itemSelected,.contextSelect").length>0){$(".toolbar_wrap .simple_btn:not('.btn_simple_move')").css("display","inline-block");}
        else{$(".toolbar_wrap .simple_btn:not('.btn_simple_move')").css("display","none");}
    });
    /*item操作选框上移出现事件*/
    //$('.item .item_info_detail').hover(function () {
    //    $(this).parent().find('.item-action-wrapper').fadeIn();
    //});
    /*item操作选框下移出去事件*/
    //$('.item .item-action-wrapper').hover(function () {
    //},function(){
    //    $(this).fadeOut();
    //});
    /*item操作选框——下载*/
    $('.item_info_wrap .btn_simple_download').off();
    $('.item_info_wrap .btn_simple_download').on('click', function () {
        var $p=$(this).parents('.item');
        FILEMODEL.downloadItem($p);
    });
    /*item操作选框——重命名*/
    //$('.item_info_wrap .btn_simple_rename').unbind();
    //$('.item_info_wrap .btn_simple_rename').on('click', function () {
    //    var $t= $(this).parents('.item');
    //    FILEMODEL.renameItem($t);
    //});
    /*item操作选框——发布*/
    $('.item_info_wrap .btn_simple_share ').off();
    $('.item_info_wrap .btn_simple_share ').on('click', function () {
        var $p=$(this).parents('.item');
        FILEMODEL.pushFile($p);
    });
    /*item操作选框——移动到*/
    $('.item_info_wrap .btn_simple_move ').off();
    $('.item_info_wrap .btn_simple_move ').on('click', function () {
        var $p=$(this).parents('.item');
        FILEMODEL.moveTo($p);
    });
    /*预览文档*/
    $('.item_detail .btn-pv-pdf').off();
    $('.item_detail .btn-pv-pdf').on('click', function () {
        FILEMODEL.previewPDF($(this));
    });
    /*预览视频及音频*/
    $('.item_detail .btn-pv-play,.js-mp3').off();
    $('.item_detail .btn-pv-play,.js-mp3').on('click', function () {
        FILEMODEL.previewVideo($(this));
    });
    //$('.js-mp3').unbind();
    //$('.js-mp3').on('click', function (){
    //    FILEMODEL.previewAudio();
    //});
    /*预览图片*/
    $('.item_detail .btn-img-preview img').off();
    $('.item_detail .btn-img-preview img').on('click', function () {
        FILEMODEL.previewImg($(this));
    });
}

/*获取当前目录的文件*/
function getCurrentDir()
{
    //var wrapType = $('.detail_wrapper .detail_body_wrapper').data('type');
    var currentFolder = $('.inner_body .detail_body_wrapper').attr('level');

    if(_fileType){
        var data ={'url':'/api/file/getFilesByPage','path':currentFolder+'/','page':1,limit:12,'type':_fileType};
    }else{
        var data ={'url':'/api/file/getFilesByPage','path':currentFolder+'/','page':1,limit:12};
    }
    FILEMODEL.getFilesByPage(currentFolder+'/',_fileType);
    //remote_interface_api(data, function (serverData) {
    //    if(serverData.code ===0 ){
    //        /*加载该目录的文件*/
    //        $('.detail_wrapper .detail_body_wrapper').empty();
    //        var userFiles = template.compile(temperFile);
    //        $('#file-list').html(userFiles(serverData.data));
    //        loadOnBindFun();
    //        //var userFiles = serverData.data.userFiles;
    //        //for(var i=0;i<userFiles.length;i++){
    //        //    var userFile = userFiles[i];
    //        //    var htmlStr=getFolderEleStr(userFile);
    //        //    $('.detail_wrapper .detail_body_wrapper').append(htmlStr);
    //        //}
    //        //loadOnBindFun();
    //    }else{
    //        layer.msg(serverData.msg);
    //        //$('#modal_pop_wrap .pop_content').text(serverData.msg);
    //        //$('#modal_pop_wrap').modal('show');
    //        //$('#modal_pop_wrap .btn.close_modal_wrap').data('code',serverData.code);
    //    }
    //});
}

/*生成文件夹里面的文件内容*/
//function getFolderEleStr(userflie)
//{
//    var userFile = userflie;
//    var author_name = $.trim($('.user_info_wrap .user_name_wrap .user_name').text());
//    var strHtml ='';
//    var userFilePath = userFile['path'].substr(0,userFile['path'].length-1);
//    var imgSrc = "/api/source/getImageThumb/"+userflie.id+"/260/260";
//    if(userFile['file_type'] ==1){
//        /*文件夹*/
//        strHtml +="<div class='item'  folder="+userFile['id']+" level="+userFilePath+">";
//        strHtml +="<div class='item_detail folder_item'>";
//        strHtml +="<div class='item_detail_img_wrap item_click_area folder_click'>";
//        strHtml +="<img class='folder' src='img/frontend/home/folder.png?v=1'>";
//        strHtml +="</div>";
//        strHtml +="<div class='item_info_wrap'>";
//        strHtml +="<div class='item_info_detail'>";
//        strHtml +="<div class='item_name ellipsis text-center'><div class='file-name'>"+userFile['file_name']+"</div></div>";
//        //strHtml +="<label class='padding-10 fs-b item_name ellipsis'>"+userFile['file_name']+"</label>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//    }else if(userFile['file_type'] ==2){
//        /*视频*/
//        strHtml +="<div class='item' folder="+userFile['id']+" level="+userFilePath+">";
//        strHtml +="<div class='item_detail'>";
//        strHtml +="<div class='item_detail_img_wrap item_click_area'>";
//        strHtml +="<img class='hover_controller' src="+imgSrc+" alt='not authorized'>";
//        strHtml +="<i class='icon-7 btn-status'></i>";
//        strHtml +="<div class='cover_layer_wrap'>";
//        strHtml +="<div class='opacity_layer'></div>";
//        strHtml +="<div class='cover_btn btn-pv-play'></div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="<div class='item_info_wrap'>";
//        strHtml +="<div class='item_info_detail'>";
//        //strHtml +="<label class='item_name '>";
//        //strHtml +="<i class='icon-6 img-icon'></i>";
//        //strHtml +="<label class='file-name ellipsis'>"+userFile['file_name']+"</label>";
//        //strHtml +="</label>";
//        //strHtml +="<label class='pl-40 ellipsis author_name'>";
//        //strHtml +="<span>文件大小：</span><sapn>"+userFile['sizeConv']+"</sapn>";
//        //strHtml +="</label>";
//        strHtml +="<div class='item_name ellipsis text-center'><div class='file-name'>"+userFile['file_name']+"</div></div>";
//        strHtml +="</div>";
//
//        //strHtml +="<div class='download_info item-action-wrapper'>";
//        //strHtml +="<div class='simple_btn btn_simple_share '>";
//        //strHtml +="<i class='tool_icon icon_blue_share'></i>";
//        //strHtml +="<label class='tool_btn_desc'>发布</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_download '>";
//        //strHtml +="<i class='tool_icon icon_blue_download'></i>";
//        //strHtml +="<label class='tool_btn_desc'>下载</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_rename '>";
//        //strHtml +="<i class='tool_icon_1 icon_rename'></i>";
//        //strHtml +="<label class='tool_btn_desc'>重命名</label>";
//        //strHtml +="</div>";
//        //strHtml +="</div>";
//
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//    }else if(userFile['file_type'] ==3){
//        /*图片*/
//        strHtml +="<div class='item' folder="+userFile['id']+" level="+userFilePath+">";
//        strHtml +="<div class='item_detail'>";
//        strHtml +="<div class='item_click_area item_detail_img_wrap btn-img-preview'>";
//        strHtml +="<img class='hover_controller' src="+imgSrc+">";
//        strHtml +="<i class='icon-7 btn-status'></i>";
//        strHtml +="</div>";
//        strHtml +="<div class='item_info_wrap'>";
//        strHtml +="<div class='item_info_detail'>";
//        //strHtml +="<label class='item_name ellipsis'>";
//        //strHtml +="<i class='icon-6 img-icon'></i>";
//        //strHtml +="<label class='file-name ellipsis'>"+userFile['file_name']+"</label>";
//        //strHtml +="</label>";
//        //strHtml +="<label class='pl-40 ellipsis author_name'>";
//        //strHtml +="<span>文件大小：</span><sapn>"+userFile['sizeConv']+"</sapn>";
//        //strHtml +="</label>";
//        strHtml +="<div class='item_name ellipsis text-center'><div class='file-name'>"+userFile['file_name']+"</div></div>";
//        strHtml +="</div>";
//
//        //strHtml +="<div class='download_info item-action-wrapper'>";
//        //strHtml +="<div class='simple_btn btn_simple_share '>";
//        //strHtml +="<i class='tool_icon icon_blue_share'></i>";
//        //strHtml +="<label class='tool_btn_desc'>发布</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_download '>";
//        //strHtml +="<i class='tool_icon icon_blue_download'></i>";
//        //strHtml +="<label class='tool_btn_desc'>下载</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_rename '>";
//        //strHtml +="<i class='tool_icon_1 icon_rename'></i>";
//        //strHtml +="<label class='tool_btn_desc'>重命名</label>";
//        //strHtml +="</div>";
//        //strHtml +="</div>";
//
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//    }else if(userFile['file_type'] ==4){
//        /*音频*/
//        strHtml +="<div class='item' folder="+userFile['id']+" level="+userFilePath+">";
//        strHtml +="<div class='item_detail'>";
//        strHtml +="<div class='item_detail_img_wrap item_click_area'>";
//        strHtml +="<img class='hover_controller' src='img/frontend/tem_material/audio.png?v=1'>";
//        strHtml +="<i class='icon-7 btn-status'></i>";
//        strHtml +="<div class='cover_layer_wrap'>";
//        strHtml +="<div class='opacity_layer'></div>";
//        strHtml +="<div class='cover_btn btn-pv-play'></div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="<div class='item_info_wrap'>";
//        strHtml +="<div class='item_info_wrap'>";
//        strHtml +="<div class='item_info_detail'>";
//        //strHtml +="<label class='item_name'>";
//        //strHtml +="<i class='icon-6 img-icon'></i>";
//        //strHtml +="<label class='file-name ellipsis'>"+userFile['file_name']+"</label>";
//        //strHtml +="</label>";
//        //strHtml +="<label class='pl-40 ellipsis author_name'>";
//        //strHtml +="<span>文件大小：</span><sapn>"+userFile['sizeConv']+"</sapn>";
//        //strHtml +="</label>";
//        strHtml +="<div class='item_name ellipsis text-center'><div class='file-name'>"+userFile['file_name']+"</div></div>";
//        strHtml +="</div>";
//
//        //strHtml +="<div class='download_info item-action-wrapper'>";
//        //strHtml +="<div class='simple_btn btn_simple_share '>";
//        //strHtml +="<i class='tool_icon icon_blue_share'></i>";
//        //strHtml +="<label class='tool_btn_desc'>发布</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_download '>";
//        //strHtml +="<i class='tool_icon icon_blue_download'></i>";
//        //strHtml +="<label class='tool_btn_desc'>下载</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_rename '>";
//        //strHtml +="<i class='tool_icon_1 icon_rename'></i>";
//        //strHtml +="<label class='tool_btn_desc'>重命名</label>";
//        //strHtml +="</div>";
//        //strHtml +="</div>";
//
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//    }else if(userFile['file_type'] ==5){
//        /*文档*/
//        strHtml +="<div class='item' folder="+userFile['id']+" level="+userFilePath+">";
//        strHtml +="<div class='item_detail item_doc'>";
//        strHtml +="<div class='doc_wrap item_click_area'><i class='hover_controller doc_pdf btn-pv-pdf'></i><i class='icon-7 btn-status'></i></div>";
//        strHtml +="<div class='item_info_wrap'>";
//        strHtml +="<div class='item_info_detail'>";
//        //strHtml +="<label class='item_name'>";
//        //strHtml +="<i class='icon-6 pdf-icon'></i>";
//        //strHtml +="<label class='file-name ellipsis'>"+userFile['file_name']+"</label>";
//        //strHtml +="</label>";
//        //strHtml +="<label class='pl-40 ellipsis author_name'>";
//        //strHtml +="<span>文件大小：</span><span>"+userFile['sizeConv']+"</span>";
//        //strHtml +="</label>";
//        strHtml +="<div class='item_name ellipsis text-center'><div class='file-name'>"+userFile['file_name']+"</div></div>";
//        strHtml +="</div>";
//
//        //strHtml +="<div class='download_info item-action-wrapper'>";
//        //strHtml +="<div class='simple_btn btn_simple_share '>";
//        //strHtml +="<i class='tool_icon icon_blue_share'></i>";
//        //strHtml +="<label class='tool_btn_desc'>发布</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_download '>";
//        //strHtml +="<i class='tool_icon icon_blue_download'></i>";
//        //strHtml +="<label class='tool_btn_desc'>下载</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_rename '>";
//        //strHtml +="<i class='tool_icon_1 icon_rename'></i>";
//        //strHtml +="<label class='tool_btn_desc'>重命名</label>";
//        //strHtml +="</div>";
//        //strHtml +="</div>";
//
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//    }else if(userFile['file_type'] >=6){
//
//        /*压缩包*/
//        strHtml +="<div class='item' folder="+userFile['id']+" level="+userFilePath+">";
//        strHtml +="<div class='item_detail item_doc'>";
//        strHtml +="<div class='doc_wrap item_click_area'><i class='hover_controller archive_rar'></i><i class='icon-7 btn-status'></i></div>";
//        strHtml +="<div class='item_info_wrap'>";
//        strHtml +="<div class='item_info_detail'>";
//        //strHtml +="<label class='item_name'>";
//        //strHtml +="<i class='icon-6 ysb-icon'></i>";
//        //strHtml +="<label class='file-name ellipsis'>"+userFile['file_name']+"</label>";
//        //strHtml +="</label>";
//        //strHtml +="<label class='pl-40 ellipsis author_name'>";
//        //strHtml +="<span>文件大小：</span><span>"+userFile['file_size']+"</span>";
//        //strHtml +="</label>";
//        strHtml +="<div class='item_name ellipsis text-center'><div class='file-name'>"+userFile['file_name']+"</div></div>";
//        strHtml +="</div>";
//
//        //strHtml +="<div class='download_info item-action-wrapper'>";
//        //strHtml +="<div class='simple_btn btn_simple_share '>";
//        //strHtml +="<i class='tool_icon icon_blue_share'></i>";
//        //strHtml +="<label class='tool_btn_desc'>发布</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_download '>";
//        //strHtml +="<i class='tool_icon icon_blue_download'></i>";
//        //strHtml +="<label class='tool_btn_desc'>下载</label>";
//        //strHtml +="</div>";
//        //strHtml +="<div class='simple_btn btn_simple_rename '>";
//        //strHtml +="<i class='tool_icon_1 icon_rename'></i>";
//        //strHtml +="<label class='tool_btn_desc'>重命名</label>";
//        //strHtml +="</div>";
//        //strHtml +="</div>";
//
//        strHtml +="</div>";
//        strHtml +="</div>";
//        strHtml +="</div>";
//    }
//    return strHtml;
//
//}
//文件模板
var temperFile='<i style="display:none" id="js-file-pages" data-pages="<% page_count %>"></i><% each userFiles as file %>'
    +'<% if file.file_type === "1" %>'
    +'<div class="item" level="" folder="<% file.id %>"  data-type="<% file.file_type %>">'
    +'<div class="item_detail folder_item">'
    +'<div class="item_detail_img_wrap item_click_area folder_click">'
    +'<img class="folder" src="/img/frontend/home/folder.png?v=1">'
    +'</div>'
    +'<div class="item_info_wrap">'
    +'<div class="item_info_detail">'
    +'<div class="item_name ellipsis text-center"  title="<% file.file_name %>"><% file.file_name %></div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'<% /if %>'
    +'<% if file.file_type === "2" %>'
    +'<div class="item" level="" folder="<% file.id %>" data-type="<% file.file_type %>">'
    +'<div class="item_detail">'
    +' <div class="item_detail_img_wrap item_click_area ">'
    +'   <img src="/api/source/getImageThumb/<% file.id %>/260/260" width="260" height="260">'
    +'   <i class="icon-7 btn-status"></i>'
    +'  <div class="icon-status">'
    +'   <% if file.visible == 1 %>'
    +'   <i class="icon-eye"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 1 %>'
    +'   <i class="icon-push-ok"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 0 %>'
    +'   <i class="icon-push-wait"></i>'
    +'   <% /if %>'
    +'  </div>'
    +'    <div class="cover_layer_wrap">'
    +'   <div class="cover_btn btn-pv-play"></div>'
    +'    </div>'
    +'    </div>'
    +'  <div class="item_info_wrap">'
    +'  <div class="item_info_detail">'
    +'  <div class="item_name ellipsis text-center"><div class="file-name"  title="<% file.file_name %>"><% file.file_name %></div></div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'<% /if %>'
    +'<% if file.file_type === "3" %>'
    +'<div class="item" level="" folder="<% file.id %>" data-type="<% file.file_type %>">'
    +'    <div class="item_detail">'
    +'  <div class="item_detail_img_wrap item_click_area btn-img-preview">'
    +'  <img src="/api/source/getImageThumb/<% file.id %>/260/260" width="260" height="260">'
    +'  <i class="icon-7 btn-status"></i>'
    +'  <div class="icon-status">'
    +'   <% if file.visible == 1 %>'
    +'   <i class="icon-eye"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 1 %>'
    +'   <i class="icon-push-ok"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 0 %>'
    +'   <i class="icon-push-wait"></i>'
    +'   <% /if %>'
    +'  </div>'
    +'  </div>'
    +'  <div class="item_info_wrap">'
    +'  <div class="item_info_detail">'
    +'   <div class="item_name ellipsis text-center"><div class="file-name"  title="<% file.file_name %>"><% file.file_name %></div></div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'<% /if %>'
    +'<% if file.file_type === "4" %>'
    +'<div class="item" level="" folder="<% file.id %>" data-type="<% file.file_type %>">'
    +'    <div class="item_detail item_doc">'
    +'   <div class="doc_wrap item_click_area">'
    +'   <img class="hover_controller"  src="/img/frontend/tem_material/audio.png?v=1" style="display:none">'
    +'  <i class="hover_controller doc_mp3 js-mp3"></i>'
    +'   <i class="icon-7 btn-status"></i>'
    +'  <div class="icon-status">'
    +'   <% if file.visible == 1 %>'
    +'   <i class="icon-eye"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 1 %>'
    +'   <i class="icon-push-ok"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 0 %>'
    +'   <i class="icon-push-wait"></i>'
    +'   <% /if %>'
    +'  </div>'
    +'  </div>'
    +'  <div class="item_info_wrap">'
    +'  <div class="item_info_detail">'
    +'  <div class="item_name ellipsis text-center"><div class="file-name"  title="<% file.file_name %>"><% file.file_name %></div></div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'<% /if %>'
    +'<% if file.file_type === "5" %>'
    +'<div class="item" level="" folder="<% file.id %>" data-type="<% file.file_type %>">'
    +'  <div class="item_detail item_doc">'
    +'  <div class="doc_wrap item_click_area">'
    +'  <i class="hover_controller doc_<% file.file_name | setType:"other" %> btn-pv-pdf"></i>'
    +'  <i class="icon-7 btn-status"></i>'
    +'  <div class="icon-status">'
    +'   <% if file.visible == 1 %>'
    +'   <i class="icon-eye"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 1 %>'
    +'   <i class="icon-push-ok"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 0 %>'
    +'   <i class="icon-push-wait"></i>'
    +'   <% /if %>'
    +'  </div>'
    +'  </div>'
    +'  <div class="item_info_wrap">'
    +'  <div class="item_info_detail">'
    +'  <div class="item_name ellipsis text-center"><div class="file-name" title="<% file.file_name %>"><% file.file_name %></div></div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'<% /if %>'
    +'<% if file.file_type === "6" %>'
    +'<div class="item" level="" folder="<% file.id %>" data-type="<% file.file_type %>" <% file.is_push %>>'
    +' <div class="item_detail item_doc">'
    +'  <div class="doc_wrap item_click_area">'
    +'  <i class="hover_controller doc_<% file.file_name | setType:"other" %>"></i>'
    +'  <i class="icon-7 btn-status"></i>'
    +'  <div class="icon-status">'
    +'   <% if file.visible == 1 %>'
    +'   <i class="icon-eye"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 1 %>'
    +'   <i class="icon-push-ok"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 0 %>'
    +'   <i class="icon-push-wait"></i>'
    +'   <% /if %>'
    +'  </div>'
    +'  </div>'
    +'  <div class="item_info_wrap">'
    +'  <div class="item_info_detail">'
    +'  <div class="item_name ellipsis text-center"><div class="file-name"  title="<% file.file_name %>"><% file.file_name %></div></div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'<% /if %>'
    +'<% if file.file_type >= 7 %>'
    +'<div class="item" level="" folder="<% file.id %>" data-type="<% file.file_type %>">'
    +'  <div class="item_detail item_doc">'
    +'  <div class="doc_wrap item_click_area">'
    +'  <i class="hover_controller doc_<% file.file_name | setType:"other" %> "></i>'
    +'  <i class="icon-7 btn-status"></i>'
    +'  <div class="icon-status">'
    +'   <% if file.visible == 1 %>'
    +'   <i class="icon-eye"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 1 %>'
    +'   <i class="icon-push-ok"></i>'
    +'   <% /if %>'
    +'   <% if file.is_push == 0 %>'
    +'   <i class="icon-push-wait"></i>'
    +'   <% /if %>'
    +'  </div>'
    +'  </div>'
    +'   <div class="item_info_wrap">'
    +'  <div class="item_info_detail">'
    +'   <div class="item_name ellipsis text-center"><div class="file-name"  title="<% file.file_name %>"><% file.file_name %></div></div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'</div>'
    +'<% /if %>'
    +'<% /each %>';
/*分享到弹出html*/
var shareFileHtml='<div id="share-popover">'
    +'<div class="share-content">'
    +'<h3 class="share-tit js-share-tit">公开链接</h3>'
    +'<div class="button-section">'
    +'<div class="button-group"><a class="add-button-section btn js-add-open-share">创建公开连接</a><span>（文件会出现在你的分享主页，其他人都能查看下载）</span></div>'
    +'</div>'
    +'<div class="share-link">'
    +'<div class="link-info clearfix">'
    +'<input class="share-link-input pull-left" value="" type="text" name="share-link-input">'
    +'<div class="copy-button-section btn pull-right">复制分享链接</div>'
    +'</div>'
    +'<div class="share-msg">1、生成文件下载链接<br>2、把链接通过QQ、微博、人人网、QQ空间等方式分享给好友</div>'
    +'</div>'
    +'</div>'
    +'</div>'

