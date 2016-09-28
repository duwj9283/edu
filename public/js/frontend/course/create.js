/**
 * Created by 20150112 on 2016/3/23.
 */
var remote_interface_api = function (data,cb) {
    $.ajax({
        url:data.url,
        type:'post',
        data:data,
        dataType:'json'
    }).done(cb);
};
$(function () {
    /*初始化上传webuploader*/
    webUploader.init({
        label:'点击更换封面',
        pick:'#filePicker',
        thumbSize:{
            width:340,
            height:189,
        }
    });
    /*初始化ui编辑器*/
    var ue = UE.getEditor('ue-container',{
        initialFrameWidth:600,
        initialFrameHeight:400,
        elementPathEnabled:false,
        enableAutoSave:false,
        toolbars: [[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist','|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify','|',
            'simpleupload', 'insertimage', 'emotion',
            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'print', 'preview', 'searchreplace', 'help'
        ]]
    });



    /*标头*/
    $('#navbar-collapse-basic ul li').removeClass('active');
    $('#navbar-collapse-basic ul .course').addClass('active');
    /*管理页面的绑定事件函数*/
    jqueryBindFunc();
    /*侧边栏*/
    $('.col_side_wrap.body_side .col_side_li_2_wrap.my_course_wrap').show();
    $('.col_side_wrap.body_side .col_side_2_title.my_course').addClass('gly-up');
    $('.col_side_wrap.body_side .col_side_li_2_wrap.my_course_wrap .btn_icon_create_course').addClass('selected');

    /*显示添加标签页*/
    $('.label_list_wrap .icon_add_label').on('click',function(){
        var $list = $(this).parent().find('ul.course_label_list');
        $list.find('li').each(function () {
            var $sText = $(this).find('span').text();
            $('#label-popover-container .pop-content ul li').each(function () {
                var $text = $(this).text();
                if($sText === $text){
                    $(this).addClass('selected');
                }
            });
        });
        $('#label-popover-container').fadeIn();
    });
    /*添加标签*/
    $('#label-popover-container .btn_add_label').on('click', function () {
        $('ul.course_label_list').empty();
        $('#label-popover-container ul li.selected').each(function () {
            var label = $(this).text();
            var $liHtmlStr = $('<li><span>'+label+'</span><a>✕</a></li>')
            $('ul.course_label_list').append($liHtmlStr);
        });
        //绑定删除已添加的标签按钮
        $('ul.course_label_list li a').unbind();

        //隐藏标签页
        $('#label-popover-container').fadeOut(function () {
            $('#label-popover li.selected').removeClass('selected');
        });

    });
    $('ul.course_label_list li a').on('click', function () {
        $(this).parent().remove();
    });

    /*
    * 创建课程的右部导航栏
    * 必须先填写基本信息
    * 暂时先不用，等整个流程走通了再使用
    * */
    $('ul.create_nav_tabs li').on('click', function () {
        if($(this).hasClass('forbidden')){
            $('#modal_pop_wrap .pop_content').text('请按照流程创建课程');
            $('#modal_pop_wrap').modal('show');
            return false;
        }
    });

    /*点击上传课件按钮*/
    $('#course-choose-icons .choose-label.for-kj').on('click', function () {
        $('#file-select-popover ul').empty();
        $('#file-select-popover').removeClass('multiple');
        $('#file-select-popover').attr('choose-type','kj');
        $('#file-select-popover .choose-file input').attr('accept','.mp4,.avi,.rmvb');
        $('#file-select-popover .content-r .popover-content-header .title').text('最多选1个');
        $('#file-select-popover').data('type','kj');
        $('body').addClass('forbidden-scroll');
        /*获取原有的课件*/
        var oldFiles = $('#sort-chapter ul.lesson_area li.selected').data('files_kj');
        if(oldFiles){
            for(var i=0;i<oldFiles.length;i++){
                var file = oldFiles[i];
                var $htmlStr = $('<li class="ellipsis" file="'+file.id+'"><sapn>'+file.file_name+'</sapn><i></i></li>');
                $('#file-select-popover ul.file_r_list').append($htmlStr);
                $htmlStr.data('file',file);
            }
        }
        /*获取用户云盘的视频文件作为课件选项*/
        var data= {'url':'/api/file/getFiles','path':'/','page':1,'type':2};
        getChooseFile(data);

        $('#file-select-popover').fadeIn();
    });
    /*点击上传资料按钮*/
    $('#course-choose-icons .choose-label.for-zl').on('click', function () {
        $('#file-select-popover ul').empty();
        $('#file-select-popover').addClass('multiple');
        $('#file-select-popover').attr('choose-type','zl');
        $('#file-select-popover .choose-file input').attr('accept',null);
        $('#file-select-popover .content-r .popover-content-header .title').text('已选择');
        $('#file-select-popover').data('type','zl');
        $('body').addClass('forbidden-scroll');
        /*获取原有的课件*/
        var oldFiles = $('ul#sort-chapter .lesson_area li.selected').data('files_zl');
        if(oldFiles){
            for(var i=0;i<oldFiles.length;i++){
                var file = oldFiles[i];
                var $htmlStr = $('<li class="ellipsis" file="'+file.id+'"><sapn>'+file.file_name+'</sapn><i></i></li>');
                $('#file-select-popover ul.file_r_list').append($htmlStr);
                $htmlStr.data('file',file);
            }
        }
        /*获取云盘文件列表*/
        var data = {url:'/api/file/getFiles','path':'/','page':1,'type':0};
        remote_interface_api(data, function (serverData) {
            var files = serverData.data.userFiles;
            for(var i=0;i<files.length;i++){
                var file = files[i];
                if(file.file_type == 1){
                    var $fileHtml = $("<li class='ellipsis folder' file='"+file.id+"'><div class='treeview-node'><dfn class='b-in-blk treeview-ic'></dfn><span>"+file.file_name+"</span></div><ul folder='"+'/'+file.file_name+"'></ul></li>") ;
                }else{
                    var $fileHtml = $("<li class='ellipsis' file='"+file.id+"'>"+file.file_name+"</li>") ;
                }
                $('#file-select-popover ul.file_list').append($fileHtml);
                $fileHtml.data('file',file);
            }
            fileSelBind();
        });

        $('#file-select-popover').fadeIn();
    });
    /*添加选择的文件到右侧列表*/
    $('#file-select-popover .move-wrapper .move-r').on('click', function () {
        /*选择课件时，替换*/
        $('#file-select-popover ul.file_list li.selected').each(function () {
            if($('#file-select-popover').attr('choose-type') === 'kj'){
                $('#file-select-popover ul.file_r_list').empty();
            }
            var $this = $(this);
            var $thisData = $(this).data('file');
            var $addStr = $('<li class="ellipsis" file="'+$thisData.id+'"><sapn>'+$thisData.file_name+'</sapn><i></i></li>');
            $('#file-select-popover ul.file_r_list').append($addStr);
            $addStr.data('file',$thisData);
        });
        fileSelBind();
    });
    /*保存选中的文件*/
    $('#file-select-popover .save-file').on('click',function(){
        var type = $('#file-select-popover').data('type');
        var files = [];
        $('#file-select-popover ul.file_r_list li').each(function(){
            var $this = $(this);
            var data = $this.data('file');
            files.push(data);
        });
        var $lesson = $('#sort-chapter ul.lesson_area li.selected');
        if(type == 'kj'){
            $lesson.data('files_kj',files);
        }else if(type == 'zl'){
            $lesson.data('files_zl',files);
        }
        $('#file-select-popover b').trigger('click');
    });


    /*监听付费radio*/
    $(':input[name="radioOption"]').on('click',function () {
        $(':input[name="radioOption"]').parent().removeClass('has-select');
        $(':input[name="radioOption"]').each(function () {
            if($(this).is(':checked')){
                //选中状态的按钮
                $(this).parent().addClass('has-select');
                //判断是否需要付费
                if($(this).val() == 1){
                    $('#cost-input').show();
                }else{
                    $('#cost-input').hide();
                }

            }
        });
    });
    /*监听费用输入框*/
    $('input.course_cost').on('input',function(){
        var $this = $(this).val();
        var reg = /[0-9]*[1-9][0-9]*/;
        if('' != $this.replace(reg,'')){
            $this = $this.match(reg) == null ? '' :$this.match(reg);
        }
        $(this).val($this);
    });
    /*监听课时状态radio*/
    $('input[name="statusRadioOption"]').on('click', function () {
        $('input[name="statusRadioOption"]').parent().removeClass('has-select');
        $('input[name="statusRadioOption"]').each(function () {
            if($(this).is(":checked")){
                $(this).parent().addClass('has-select');
            }
        });
    });
    /*删除课时*/
    $('#course-choose-icons .btn_del').on('click', function () {

        $('#modal_pop_wrap .modal-header .header-title').text('确认删除');
        $('#modal_pop_wrap .pop_content').text('确认删除吗？');
        var btnHtmlStr = '<a class="btn left btn_agree" data-dismiss="modal">确定</a>';
        $('#modal_pop_wrap .modal-footer').append(btnHtmlStr);
        $('#modal_pop_wrap').modal('show');

        /*点击取消隐藏弹窗的时候，回调函数*/
        $('#modal_pop_wrap').on('hidden.bs.modal', function () {
            $('#modal_pop_wrap .modal-header .header-title').text('');
            $('#modal_pop_wrap .modal-footer .btn_agree').remove();
        });
        //单击确定删除，删除操作
        $('#modal_pop_wrap .modal-footer .btn_agree').on('click', function () {
            $('#modal_pop_wrap').modal('hide');
            $('#modal_pop_wrap .modal-header .header-title').text('');
            $(this).remove();
            $('#sort-chapter .lesson_wrap.selected').remove();
        });
    });
    /*新建课程章节html*/
    $('.add_chapter').on('click', function () {
        var chapterCount = $('#sort-chapter .chapter_wrapper').length;
        var sort = parseInt(chapterCount)+1;
        var addHtmlStr = $("<li class='chapter_wrapper chapter_add_wrap' chapter='0'><div class='chapter_header'><div class='title_wrap drag-chapter'><span class='chapter_sort'>第"+sort+"章</span><input class='form-control chapter_name' placeholder='输入章节名'/></div><label class='orange add_lesson'><span class='glyphicon glyphicon-plus'></span>课时</label></div><ul class='lesson_area sort-lesson'></ul></li>");
        $('#sort-chapter').append(addHtmlStr);
        jqueryBindFunc();
    });
    function jqueryBindFunc(){
        /*添加课时*/
        $('#sort-chapter .add_lesson').unbind();
        $('#sort-chapter .add_lesson').on('click', function () {
            var $_this = $(this);
            var $parents = $_this.parents('.chapter_wrapper');
            var lessonCount = $parents.find('.lesson_area').children('.lesson_wrap').length;
            var lesSort =  parseInt(lessonCount+1);
            var addLesStr = $("<li class='lesson_wrap title_wrap w_l drag-lesson' lesson='0' kj_file='' zl_files=''><i class='c_l'></i><span class='lesson_sort'>课时"+lesSort+"</span><input class='form-control lesson_name'/></li>");
            $parents.find('.lesson_area').append(addLesStr);
            jqueryBindFunc();
        });

        /*编辑章节名或者课时名称*/
        $('#sort-chapter input').unbind();
        $('#sort-chapter :input(".input-no-editor")').on('click', function () {
            $(this).attr('readonly',false);
            $(this).removeClass('input-no-editor');
            $(this).addClass('form-control');
        });
        /*输入框失去焦点事件*/
        $('#sort-chapter input').on('blur', function () {
            $(this).removeClass('form-control');
            $(this).addClass('input-no-editor');
            $(this).attr('readonly',false);
        });

        /*课时操作按钮悬浮事件*/
        $('#sort-chapter .lesson_wrap').unbind();
        $('#sort-chapter .lesson_wrap').hover(function () {
            var top = $(this).offset().top;
            var left = $(this).offset().left;
            var width= $(this).width();
            left = left+width-270;
            $('#course-choose-icons').css('top',top);
            $('#course-choose-icons').css('left',left);
            $('#course-choose-icons').show();
            $('#sort-chapter .lesson_wrap.selected').removeClass('selected');
            $(this).addClass('selected');
        },function(){
            $('#course-choose-icons').hide();
        });
        /*隐藏课时操作按钮悬浮事件*/
        $('#course-choose-icons').unbind();
        $('#course-choose-icons').hover(function(){
            $('#course-choose-icons').show();
        }, function () {
            $('#course-choose-icons').hide();
        });

        /*章节拖放排行*/
        $('#sort-chapter').sortable({
            handle:".drag-chapter",
            containment: "parent",
            stop: function (e,ui) {
                $('#sort-chapter .chapter_wrapper').each(function () {
                    var $sort = $(this).index()+1;
                    $(this).find('.drag-chapter .chapter_sort').text('第'+$sort+'章');
                });
            }
        }).disableSelection();
        /*课时拖放排行*/
        $('.sort-lesson').sortable({
            handle:".lesson_sort",
            //handle:".c_l",
            containment: "parent",
            stop: function (e,ui) {
                var $this = $(e.target);
                $this.find('li').each(function () {
                    var $lSort = $(this).index()+1;
                    $(this).find('.lesson_sort').text('课时'+$lSort);
                });
            }
        }).disableSelection();
    }


    /*新建课程*/
    $('.btn_new_course').on('click',function(){

        var name =  $('.course_detail_container .course_name').val();
        if(name == '' || name.length == 0 || name.length>40){
            $('.course_name').parents('.form-group').addClass('has-error');
            return false;
        }
        var title =  $('.course_detail_container .course_title').val();
        if( title.length > 500 ){
            $('.course_title').parents('.form-group').addClass('has-error');
            return false;
        }
        var pic = $('#fileList').data('picSrc');
        if(!pic){
            $('#modal_pop_wrap .pop_content').text('请先上传封面');
            $('#modal_pop_wrap').modal('show');
            return false;
        }

        var desc =$('.course_detail_container .desc').val();
        if(desc.length > 500){
            $('.course_detail_container .desc').parents('.form-group').addClass('has-error');
            return false;
        }
        //课程费用
        var ifCost = $('input[name="radioOption"]:checked').val();
        if(ifCost == 0){
            var price=0;
        }else if(ifCost == 1){
            var price = $('input.course_cost').val();
        }
        //标签
        var labels=new Array();
        $('.label_list_wrap ul.course_label_list li').each(function () {
            var label =$(this).find('span').text();
            labels.push(label);
        });
        //课程状态
        var status = $('input[name="statusRadioOption"]:checked').val();
        var data = {url:'/api/lesson/addLesson',title:name,subtitle:title,pic:pic,label:labels,desc:desc,price:price,type:status,description:''};
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $('#modal_pop_wrap .pop_content').text('创建成功');
                $('#modal_pop_wrap').modal('show');
                /*创建课程成功后的动作*/
                $('ul.create_nav_tabs li').removeClass('forbidden');
                data.lesson_id = serverData.data.lesson_id;
                $('.btn_new_course').data('courseData',data);
                /*情况信息*/
                $('#course-container .form-group input,textarea').val('');
                $('#course-container .form-group #fileList').empty();
                $('#course-container ul.course_label_list').empty();
            }else{
                $('#modal_pop_wrap .pop_content').text(serverData.msg);
                $('#modal_pop_wrap').modal('show');
            }
        });
    });
    /*编辑课程介绍*/
    $('.btn_edit_course_desc').on('click', function () {
        var description = encodeURIComponent(ue.getContent());
        /*
        * 解码的函数
        * decodeURIComponent(description);
        * */
        var data = $('.btn_new_course').data('courseData');
        if( jQuery.isEmptyObject(data) || !data.lesson_id || typeof(data) !='object'){
            $('#modal_pop_wrap .pop_content').text('请先创建课程的基本信息并保存');
            $('#modal_pop_wrap').modal('show');
            return false;
        }
        data.description = description;
        data.url = "/api/lesson/editLesson";
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){
                $('#modal_pop_wrap .pop_content').text('编辑课程介绍成功');
                $('#modal_pop_wrap').modal('show');
            }else{
                $('#modal_pop_wrap .pop_content').text(serverData.msg);
                $('#modal_pop_wrap').modal('show');
            }
        })

    });
    /*保存章节信息*/
    $('.btn_create_lesson ').on('click', function () {
        var js_false = true;
        var courseInfo = $('.btn_new_course').data('courseData');
        if( jQuery.isEmptyObject(courseInfo) || !courseInfo.lesson_id || typeof(courseInfo) !='object'){
            $('#modal_pop_wrap .pop_content').text('请先创建课程的基本信息并保存');
            $('#modal_pop_wrap').modal('show');
            return false;
        }
        var lessonId = courseInfo.lesson_id;
        var data = {url:'/api/lesson/addLessonList','lesson_id':lessonId,lesson_list:[]};
        $('#sort-chapter .chapter_wrapper').each(function () {
            //第一层遍历，章节信息
            var $pThis = $(this);
            var pId=$pThis.attr('chapter');
            var pSort = $pThis.index()+1;
            var pPath = '/';
            var pName = $.trim($pThis.find('.chapter_header .chapter_name').val());
            if(pName.length ==0 || pName == ''){
                $('#modal_pop_wrap .pop_content').text('章节名称不能为空');
                $('#modal_pop_wrap').modal('show');
                js_false = false;
                return false;
            }
            var chapterInfo = {name:pName,path:pPath,sort:pSort,id:pId,child_list:[]};

            $pThis.find('.lesson_area .lesson_wrap').each(function () {
                //第二层遍历，课时信息
                var cThis = $(this);
                var cSort = cThis.index()+1;
                var cid = cThis.attr('lesson');
                var cPath = '/'+pName+'/';
                var cName = $.trim(cThis.find('.lesson_name').val());
                if(cName.length ==0 || cName==''){
                    $('#modal_pop_wrap .pop_content').text('课时名称不能为空');
                    $('#modal_pop_wrap').modal('show');
                    js_false = false;
                    return false;
                }
                /*课件*/
                var kjId = '';
                var files_kj = cThis.data('files_kj');
                if(!files_kj){
                    $('#modal_pop_wrap .pop_content').text('每个课时的课件都不能为空');
                    $('#modal_pop_wrap').modal('show');
                    js_false = false;
                    return false;
                }
                kjId = files_kj[0].id;

                /*资料*/
                var zlFiles=[];
                var files_zl = cThis.data('files_zl');
                if(files_zl){
                    for(var i=0;i<files_zl.length;i++){
                        zlFiles.push(files_zl[i].id);
                    }
                }

                var lessonInfo = {name:cName,path:cPath,sort:cSort,id:cid,file_ids:zlFiles,file:kjId};
                chapterInfo.child_list.push(lessonInfo);
            });
            data.lesson_list.push(chapterInfo);
        });
        if(!js_false){
            return false;
        }
        remote_interface_api(data,function(serverData){
            if(serverData.code === 0){
                $('#modal_pop_wrap').on('hidden.bs.modal', function () {
                    window.location.reload();
                });
                $('#modal_pop_wrap .pop_content').text('保存成功');
                $('#modal_pop_wrap').modal('show');
            }else{
                $('#modal_pop_wrap .pop_content').text(serverData.msg);
                $('#modal_pop_wrap').modal('show');
            }
        });
    });
    $('.btn_cancel_create_lesson').on('click',function(){
        $('#sort-chapter').empty();
    });
    /*获取选择课件或资料的列表*/
    function getChooseFile(data){
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0 ){
                var filesList = serverData.data.userFiles;
                for(var i=0;i<filesList.length;i++){
                    var file = filesList[i];
                    var $fileHtml = $("<li class='ellipsis' file='"+file.id+"'>"+file.file_name+"</li>") ;
                    $('#file-select-popover ul.file_list').append($fileHtml);
                    $fileHtml.data('file',file);
                }
                fileSelBind();
            }
        });
    }
    /*选择课件绑定事件*/
    function fileSelBind(){
        /*隐藏选择课程课件或资料弹窗*/
        $('#file-select-popover b').on('click', function () {
            $(this).parent().fadeOut();
            $('body').removeClass('forbidden-scroll');
        });
        /*点击选择左边的文件区*/
        $('#file-select-popover ul.file_list li').unbind();
        $('#file-select-popover ul.file_list li').on('click', function () {
            /*
            * 先判断是否是目录，是目录则展开，不是则选中
            * */
            if(!$(this).hasClass('folder')){
                $('#file-select-popover ul.file_list li.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        /*删除右边文件*/
        $('#file-select-popover ul.file_r_list li i').unbind();
        $('#file-select-popover ul.file_r_list li i').on('click',function(){
            $(this).parent().remove();
        });
        /*点击打开文件夹*/
        $('#file-select-popover ul.file_list li .treeview-node').unbind();
        $('#file-select-popover ul.file_list li .treeview-node').on('click',function(){
            var path = $(this).next().attr('folder')+'/';
            var $_this = $(this);
            var data = {url:'/api/file/getFiles','path':path,'page':1,'type':0};
            remote_interface_api(data, function (serverData) {
                $_this.next().empty();
                var files = serverData.data.userFiles;
                for(var i=0;i<files.length;i++){
                    var file = files[i];
                    if(file.file_type == 1){
                        var $fileHtml = $("<li class='ellipsis folder' file='"+file.id+"'><div class='treeview-node'><dfn class='b-in-blk treeview-ic'></dfn><span>"+file.file_name+"</span></div><ul folder='"+path+file.file_name+"'></ul></li>") ;
                    }else{
                        var $fileHtml = $("<li class='ellipsis' file='"+file.id+"'>"+file.file_name+"</li>") ;
                    }
                    $_this.next().append($fileHtml);
                    $fileHtml.data('file',file);
                }
                fileSelBind();
            });
        });
    }
});
