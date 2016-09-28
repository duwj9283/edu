
/**
 * Created by 20150112 on 2016/3/15.
 * contextMenu 菜单操作专属JS
 */
$(function () {
    $('#contextMenu').on('click',function(event){
        event.stopPropagation()
    });
    /*重命名*/
    $('#contextMenu .icon_rename_li').on('click', function () {
        /*还原其他正在重命名操作的item状态*/
        $('.inner_body .detail_body_wrapper .item').each(function () {
            $(this).find('.modify_item_name_wrap').remove();
            $(this).find('.item_info_wrap').show();
        });
        var $target = $('.inner_body .detail_body_wrapper .item.contextSelect');
        var itemName = $.trim($target.find('.item_detail .item_info_wrap .item_name').text());
        var lastIndex =itemName.lastIndexOf(".");
        if(lastIndex == -1){
            lastIndex=itemName.length;
        }
        var htmlStr = '<div class="modify_item_name_wrap"><input type="text" value="'+itemName.slice(0,lastIndex)+'" class="folder_rename"><a class="btn_modify_name">保存</a><a class="btn_cancel_modify">取消</a></div>';
        $('.inner_body .detail_body_wrapper .item.contextSelect .item_detail .item_info_wrap').hide();
        $('.inner_body .detail_body_wrapper .item.contextSelect .item_detail').append(htmlStr);
        $(".folder_rename").select();
        //获取菜单停止动画，进行隐藏使用淡出效果
        $("#contextMenu").stop().fadeOut(200);
        //重命名规则
        $(".folder_rename").keydown(function(event){
            var self=$(this);
            console.log(event.keyCode);
            if(event.keyCode==220 || event.keyCode==220&&event.shiftKey || event.keyCode==191 || event.keyCode==191&&event.shiftKey || event.keyCode==186&&event.shiftKey || event.keyCode==56&&event.shiftKey || event.keyCode==222&&event.shiftKey || event.keyCode==188&&event.shiftKey || event.keyCode==190&&event.shiftKey){
                layer.tips('文件名不能包含以下字符：<br> \\ / : * ? " < > |','.folder_rename',{
                    tips:[3,'#ccc'],
                });
                return false;
            }
            else if(event.keyCode==13){
                renameFile();
            }
            else if(event.keyCode==27){
                escRenameFile(self);
            }
        });
        /*取消重命名*/
        $('.btn_cancel_modify').on('click',function(){
            escRenameFile($(this));
        });
        function escRenameFile(self) {
            self.parents('.item').removeClass('contextSelect');
            self.parents('.modify_item_name_wrap').remove();
            $('.inner_body .detail_body_wrapper .item .item_info_wrap').show();
        };
        /*重命名*/
        $('.btn_modify_name').on('click',renameFile );

        function renameFile () {
            var $thisBtn = $(this);
            var rename = $.trim($(this).parents('.modify_item_name_wrap').find('.folder_rename').val()+itemName.slice(lastIndex));
            var folderID = $.trim($(this).parents('.item').attr('folder'));
            var data = {url:'/api/file/renameFile',name:rename,file_id:folderID};

            FILEMODEL.remote_interface_api(data, function (serverData) {
                if(serverData.code === 0){
                    $thisBtn.parents('.item').find('.item_info_wrap .item_name').text(serverData.data.name);
                    $thisBtn.parents('.item').find('.item_info_wrap').show();
                    $thisBtn.parents('.modify_item_name_wrap').remove();
                }else if(serverData.code === 4){
                    /*命名重复的情况下*/
                    layer.confirm(serverData.msg, {
                        btn: ['确定','取消'] //按钮
                    },function (index) {
                        layer.close(index);
                        // $('#modal_pop_wrap .modal-footer .btn_continue').remove(); //去掉确定按钮
                        var data = {url:'/api/file/renameFile',name:rename,file_id:folderID,is_force:1};
                        FILEMODEL.remote_interface_api(data, function (serverData) {
                            if(serverData.code ===0){
                                $thisBtn.parents('.item').find('.item_info_wrap .item_name').text(serverData.data.name);
                                $thisBtn.parents('.item').find('.item_info_wrap').show();
                                $thisBtn.parents('.modify_item_name_wrap').remove();
                                //$('#modal_pop_wrap').modal('hide');
                            }else{
                                layer.msg(serverData.msg);
                                //$('#modal_pop_wrap .pop_content').text(serverData.msg);
                            }
                        });
                    },function(){

                    });
                }
            });
        };
    });
    /*删除*/
    $('#contextMenu .icon_del_li').on('click', function (event) {
        event.stopPropagation();
        $("#contextMenu").stop().fadeOut(200);
        if($(".item.itemSelected").length>=2){
            FILEMODEL.multipleDel();
        }
        else {
            var folderID = $('.detail_body_wrapper .item.contextSelect').attr('folder');
            var data = {url: '/api/file/deleteFile', file_id: folderID};
            layer.confirm('确认要把所选文件放入回收站吗？', {
                btn: ['确定', '取消'] //按钮
            }, function (index) {
                layer.close(index);
                FILEMODEL.remote_interface_api(data, function (serverData) {
                    if (serverData.code === 0) {
                        $('.detail_body_wrapper .item[folder='+folderID+']').remove();
                        layer.msg('删除成功');
                        $(".toolbar_wrap .simple_btn:not('.back_dir')").css("display","none");
                        FILEMODEL.getUserCapacity();
                    } else {
                        layer.msg(serverData.msg);
                    }
                });
            }, function (index) {
                layer.close(index);
            });
        };
    });
    /*从回收站删除*/
    $("#contextMenu .icon_remove_li").on('click',function(){
        event.stopPropagation();
        $("#contextMenu").stop().fadeOut(200);
        var folderID = $('.detail_body_wrapper .item.contextSelect').attr('folder');
        var data = {url: '/api/file/deleteFile', file_id: folderID,trash_type:2};
        layer.confirm('文件删除后将无法恢复，您确认要彻底删除所选文件吗？', {
            btn: ['确定', '取消'] //按钮
        }, function (index) {
            layer.close(index);
            FILEMODEL.remote_interface_api(data, function (serverData) {
                if (serverData.code === 0) {
                    $('.detail_body_wrapper .item[folder='+folderID+']').remove();
                    layer.msg('删除成功');
                    $(".toolbar_wrap .simple_btn:not('.back_dir')").css("display","none");
                } else {
                    layer.msg(serverData.msg);
                }
            });
        }, function (index) {
            layer.close(index);
        });
    });
    /*从回收站恢复*/
    $("#contextMenu .icon_recovery_li").on('click',function(){
        event.stopPropagation();
        $("#contextMenu").stop().fadeOut(200);
        var folderID = $('.detail_body_wrapper .item.contextSelect').attr('folder');
        var data = {url: '/api/file/recoverFile', file_id: folderID};
        layer.confirm('确认还原选中的文件？', {
            btn: ['确定', '取消'] //按钮
        }, function (index) {
            layer.close(index);
            FILEMODEL.remote_interface_api(data, function (serverData) {
                if(serverData.code === 0){
                    $('.detail_body_wrapper .item[folder='+folderID+']').remove();
                    FILEMODEL.getUserCapacity(); //获取容量
                    layer.msg('恢复成功');
                }else if(serverData.code === 4){
                    /*命名重复的情况下*/
                    layer.confirm(serverData.msg, {
                        btn: ['确定','取消'] //按钮
                    },function (index) {
                        layer.close(index);
                        // $('#modal_pop_wrap .modal-footer .btn_continue').remove(); //去掉确定按钮
                        var data = {url:'/api/file/recoverFile',file_id:folderID,is_force:1};
                        FILEMODEL.remote_interface_api(data, function (serverData) {
                            if(serverData.code ===0){
                                $('.detail_body_wrapper .item[folder='+folderID+']').remove();
                                FILEMODEL.getUserCapacity(); //获取容量
                                layer.msg('恢复成功');
                            }else{
                                layer.msg(serverData.msg);
                            }
                        });
                    },function(){

                    });
                }
            });
        }, function (index) {
            layer.close(index);
        });
    });
    /*分享到*/
    $('#contextMenu .icon_share_third').on('click',function(){
        if($("#share-popover").size()>=1){
            $("#share-popover").remove();
        };
        $("#contextMenu").stop().fadeOut(200);
        var $p=$('.detail_body_wrapper .item.contextSelect');
        var fileName=$p.find(".file-name").text();
        $('body').append(template.compile(shareFileHtml));
        layer.open({
            type: 1,
            area: '550px',
            title:'分享文件（夹）：'+fileName,
            content:$("#share-popover"),
            success:function(){}
        });
        // 创建公开连接
        $('#share-popover .js-add-open-share').on('click',function(){
            FILEMODEL.getPublicLink();
        });

    });
    /*发布到*/
    $('#contextMenu .icon_share_li').on('click', function () {
        var $p=$('.detail_body_wrapper .item.contextSelect');
        FILEMODEL.pushFile($p);
        $("#contextMenu").stop().fadeOut(200);
        /*var data={url:'/api/file/pushFileToPublic',file_id:folderID};
        FILEMODEL.remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $('#modal_pop_wrap .pop_content').text('分享成功');
            }else{
                $('#modal_pop_wrap .pop_content').text(serverData.msg);
            }
            $('.detail_wrapper .item.contextSelect').removeClass('contextSelect');
            $("#contextMenu").stop().fadeOut(200);
            $('#modal_pop_wrap').modal('show');
        });*/
    });
    /*收藏文件*/
    $('#contextMenu .icon_collect_li').on('click',function(){
        $('#modal_pop_wrap .pop_content').text('不能收藏自己的文件');
        $('#modal_pop_wrap').modal('show');
        /*var folderID = $('.detail_body_wrapper .item.contextSelect').attr('folder');
        var data={url:'api/file/collectFile',file_id:folderID};
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $('#modal_pop_wrap .pop_content').text('收藏成功');
            }else{
                $('#modal_pop_wrap .pop_content').text(serverData.msg);
            }
            $('.detail_wrapper .item.contextSelect').removeClass('contextSelect');
            $("#contextMenu").stop().fadeOut(200);
            $('#modal_pop_wrap').modal('show');
        });*/
    });
    /*下载*/
    $('#contextMenu .icon_download_li').on('click', function () {
        var folderID =$('.detail_body_wrapper .item.contextSelect').attr('folder');
        var file_ids=[];
        file_ids.push(folderID);
        //file_ids=[10,31,9,6,8];
        var data = {url:'/frontend/file/downloadFiles',file_ids:file_ids};
        FILEMODEL.remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $("#contextMenu").stop().fadeOut(200);
                var dlUrl = serverData.data;
                window.location.href = dlUrl;
            }
        });
    });
    /*打开*/
    $('#contextMenu .icon_open_li').on('click', function () {
        var $p=$('.detail_body_wrapper .item.contextSelect');
        var folderType =$('.detail_body_wrapper .item.contextSelect').data('type');
        $("#contextMenu").stop().fadeOut(200);
        if(folderType==2 || folderType==4){
            FILEMODEL.previewVideo($p.find(".btn-pv-play"));
        }
        if(folderType==3){
            FILEMODEL.previewImg($p.find(".btn-img-preview"));
        }
        if(folderType==5){
            FILEMODEL.previewPDF($p.find(".btn-pv-pdf"));
        }
    });
    /*空间可见*/
    $('#contextMenu .icon_show_li').on('click', function () {
        var $p=$('.detail_body_wrapper .item.contextSelect');
        $("#contextMenu").stop().fadeOut(200);
        var folderID =$('.detail_body_wrapper .item.contextSelect').attr('folder');
        FILEMODEL.getFileVisible($p,folderID);
    });
    /*移动到*/
    $('#contextMenu .icon_move_li').on('click', function () {
        /*先获取文件夹根目录下的第一层子文件夹*/
        var oldPath=$('.detail_body_wrapper .item.contextSelect').attr('level')+'/';
        var fileId=$('.detail_body_wrapper .item.contextSelect').attr('folder');
        $('#dragMove .g-button-move').data('oldPath',oldPath);  //将原路径存储起来，以便后面移动到新路径的时候进行匹配
        $('#dragMove .g-button-move').data('fileId',fileId);  //将id存储起来，以便后面移动到新路径的时候进行匹配

        $("#contextMenu").stop().fadeOut(200);
        $('.detail_wrapper .item.moveSelect').removeClass('moveSelect');  //关闭右键菜单，移除原有的移动选中状态
        $('.detail_wrapper .item.contextSelect').addClass('moveSelect');  //关闭右键菜单，添加移动选中状态
        $('.detail_wrapper .item.contextSelect').removeClass('contextSelect');  //关闭右键菜单，移除选中状态

        $('#dragMove .treeview.treeview-root-content').empty(); //清空原有的目录树
        $('#dragMove .treeview.treeview-root-content').prev().addClass('treeview-node-on'); //设置根目录为选中状态
        var path='/';
        FILEMODEL.getFolderDir(path);
        $('.module-canvas').show();
        $('#dragMove').show();
    });
    /*知识点管理*/
    $('#contextMenu .icon_topic_li').on('click',function(){
        var folderID =$('.detail_body_wrapper .item.contextSelect').attr('folder');
        window.open('/live/index/topic/'+folderID,'_blank');
        $("#contextMenu").stop().fadeOut(200);
    });
    /*关闭drag*/
    $('#dragMove .dialog-icon.dialog-close').on('click', function () {
        $('.module-canvas').hide();
        $('#dragMove').hide();
        $('.detail_wrapper .item.moveSelect').removeClass('moveSelect');  //关闭右键菜单，移除原有的移动选中状态
    });
    $('#dragMove .g-button.g-button-cancel').on('click', function () {
        $('#dragMove .dialog-icon.dialog-close').trigger('click');
    });


});
