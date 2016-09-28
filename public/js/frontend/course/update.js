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
        initialFrameWidth:'100%',
        initialFrameHeight:400,
        elementPathEnabled:false,
        enableAutoSave:false,
        toolbars: [
          ['fullscreen', 'source', 'undo', 'redo', 'bold']
        ]
    });


    var lesson_id=$('input[name="lesson_id"]').val();//课程id

    /*标头*/
    $('#navbar-collapse-basic ul li').removeClass('active');
    $('#navbar-collapse-basic ul .course').addClass('active');

    /*管理页面的绑定事件函数*/
    jqueryBindFunc();
    /*侧边栏*/
    $('ul.body_side li.my_course_wrap').show();
    $('.col_side_wrap.body_side .col_side_2_title.my_course').addClass('gly-up');
    if(lesson_id){
        $('ul.body_side li.my_course_wrap').find('ul li.btn_manege_course').addClass('selected');

    }else{
        $('.col_side_wrap.body_side .col_side_li_2_wrap.my_course_wrap .btn_icon_create_course').addClass('selected');

    }


    //$('.col_side_wrap.body_side .col_side_li_2_wrap.my_course_wrap').show();
    //$('.col_side_wrap.body_side .col_side_2_title.my_course').addClass('gly-up');



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
    if(lesson_id){
        $('ul.create_nav_tabs li').removeClass('forbidden');
    };
    $('ul.create_nav_tabs li').on('click.course_tab', function () {
        var _this = $(this);
        if(_this.hasClass('forbidden')){
            layer.msg('请按照流程创建课程', {icon: 5});
            return false;
        }
        else{
            _this.addClass('active').siblings().removeClass('active');
            $("#"+_this.data('toggle')).show().siblings().hide();
        }
    });
    $('.choose-label').undelegate();
    $('#sort-chapter').delegate('.choose-label','mouseenter',function(){
        layer.tips($(this).attr('title'), $(this), {
            tips: [1, '#0FA6D8'], //还可配置颜色
        });
    });
    $('#sort-chapter').delegate('.choose-label','mouseleave',function(){
        layer.closeAll('tips');
    });
    /*点击上传课件按钮*/
    $('#sort-chapter').delegate('.choose-label.for-kj','click',function () {
        $('#file-select-popover ul').empty();
        $('#file-select-popover').removeClass('multiple');
        $('#file-select-popover').attr('choose-type','kj');
        $('#file-select-popover .choose-file input').attr('accept','.mp4,.avi,.rmvb');
        $('#file-select-popover .content-r .popover-content-header .title').text('最多选1个');
        $('#file-select-popover').data('type','kj');
        $('body').addClass('forbidden-scroll');
        /*获取原有的课件*/
        //var oldFiles = $('#sort-chapter ul.lesson_area li.selected').data('files_kj');
        var oldFiles = $(this).closest('li.lesson_wrap').data('files_kj')==''? 0 : $(this).closest('li.lesson_wrap').data('files_kj').toString();
        console.log(oldFiles);
        /*获取用户云盘的视频文件作为课件选项*/
        var data= {'url':'/api/file/getFiles','path':'/','page':1,'type':2};
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0 ){
                var filesList = serverData.data.userFiles;

                for(var i=0;i<filesList.length;i++){
                    var file = filesList[i];
                    var $fileHtml = $("<li class='ellipsis' data-file='"+file.id+"'>"+file.file_name+"</li>") ;
                    $('#file-select-popover ul.file_list').append($fileHtml);
                }
                fileSelBind('kj');
            }
        });
        remote_interface_api({url:'/api/file/getFileNameByIds','ids':oldFiles},function(serverData){
            var list = serverData.data.userFileList;
            for(var i=0;i<list.length;i++){
                var htmlStr = $('<li class="ellipsis" data-file="'+list[i].id+'"><span>'+list[i].file_name+'</span><i></i></li>');
                $('#file-select-popover ul.file_r_list').append(htmlStr);
            }
        });
        $('#file-select-popover').fadeIn();
        $('.close-btn').on("click",function(){
            $('#file-select-popover').hide();
        });
    });
    /*点击上传资料按钮*/
    $('#sort-chapter').delegate('.choose-label.for-zl','click',function () {
        $('#file-select-popover ul').empty();
        $('#file-select-popover').addClass('multiple');
        $('#file-select-popover').attr('choose-type','zl');
        $('#file-select-popover .choose-file input').attr('accept',null);
        $('#file-select-popover .content-r .popover-content-header .title').text('已选择');
        $('#file-select-popover').data('type','zl');
        $('body').addClass('forbidden-scroll');
        /*获取原有的课件*/
        //var oldFiles = $('ul#sort-chapter .lesson_area li.selected').data('files_zl');
        var oldFiles = $(this).closest('li.lesson_wrap').data('files_zl').toString();
        /*获取云盘文件列表*/
        var data = {url:'/api/file/getFiles','path':'/','page':1,'type':0};
        remote_interface_api(data, function (serverData) {
            var files = serverData.data.userFiles;
            var fileHtml='';
            for(var i=0;i<files.length;i++){
                var file = files[i];
                if(file.file_type == 1){
                    fileHtml+="<li class='ellipsis folder' data-file='"+file.id+"'><div class='treeview-node'><dfn class='b-in-blk treeview-ic'></dfn><span>"+file.file_name+"</span></div><ul folder='"+'/'+file.id+"'></ul></li>" ;
                }else{
                    fileHtml +="<li class='ellipsis' data-file='"+file.id+"'>"+file.file_name+"</li>"
                }
            }
            $('#file-select-popover ul.file_list').html(fileHtml);
            fileSelBind();
        });
        //获取右边文件
        remote_interface_api({url:'/api/file/getFileNameByIds','ids':oldFiles},function(serverData){
            var list = serverData.data.userFileList;
            for(var i=0;i<list.length;i++){
                var htmlStr = $('<li class="ellipsis" data-file="'+list[i].id+'"><span>'+list[i].file_name+'</span><i></i></li>');
                $('#file-select-popover ul.file_r_list').append(htmlStr);
            }
        });
        $('#file-select-popover').fadeIn();
        $('.close-btn').on("click",function(){
            $('#file-select-popover').hide();
        });
    });
    /*点击向右箭头,添加选择的文件到右侧列表*/
    var $popover;
    //添加习题到右侧
    $('#exam-select-popover').off('click','.move-wrapper .move-r');
    $('#exam-select-popover').on('click','.move-wrapper .move-r',function(){
        var rightFiles=[];
        $('#exam-select-popover ul.file_r_list li').each(function(){
            rightFiles.push($(this).data('file').toString());
        });
        //判断右侧是否存在,存在跳过
        $('#exam-select-popover li.selected').each(function () {
            var id=$(this).attr('file').toString();
            //判断右侧是否存在,存在跳过
            if($.inArray(id,rightFiles) != -1){
                console.log(id);
                return true;
            }
            var html = '<li class="ellipsis" data-file="'+id+'"><span>'+$(this).text()+'</span><i></i></li>';
            $('#exam-select-popover ul.file_r_list').append(html);
        });
        fileSelBind();
    });
    //添加课件 资料到右侧
    $('#file-select-popover').undelegate('.move-wrapper .move-r','click');
    $('#file-select-popover').delegate('.move-wrapper .move-r','click', function () {
        if($('#file-select-popover ul.file_r_list li').size()>=1 && $('#file-select-popover').attr('choose-type')=='kj'){
            layer.msg('最多选1个，请删除右侧');
            fileSelBind();
            return false;
        }
        var rightFiles=[];
        $('#file-select-popover ul.file_r_list li').each(function(){
            rightFiles.push($(this).data('file'));
        });
        $('#file-select-popover li.selected').each(function () {
            var id=$(this).data('file');
            //判断右侧是否存在,存在跳过
            if($.inArray(id,rightFiles) != -1){
                return true;
            }
            var html = '<li class="ellipsis" data-file="'+id+'"><span>'+$(this).text()+'</span><i></i></li>';
            $('#file-select-popover ul.file_r_list').append(html);
        });

    });
    /*课件/资料/习题弹窗,保存选中的文件 kj课件exam习题*/
    $('.file-select-popover .save-file').on('click',function(){
        $popover=$(this).parents('.file-select-popover');
        var type = $popover.attr('choose-type');
        var files = [];
        $popover.find('ul.file_r_list li').each(function(){
            files.push($(this).data('file'));
        });
        var $lesson = $('#sort-chapter ul.lesson_area li.selected');
        if(type == 'kj'){
            $lesson.data('files_kj',(files.join(',')));
            if(files == '')
            {
                $lesson.find('.btn_file_kj').removeClass('btn_file_kj_active');
            }
            else{
                $lesson.find('.btn_file_kj').addClass('btn_file_kj_active');
            }
        }else if(type == 'zl'){
            $lesson.data('files_zl',(files.join(',')));
            if(files == '')
            {
                $lesson.find('.btn_file_zl').removeClass('btn_file_zl_active');
            }
            else{
                $lesson.find('.btn_file_zl').addClass('btn_file_zl_active');
            }
        }else if(type == 'exam'){
            $lesson.data('exam',(files.join(',')));
            if(files == '')
            {
                $lesson.find('.btn_edit').removeClass('btn_edit_active');
            }
            else{
                $lesson.find('.btn_edit').addClass('btn_edit_active');
            }
        }
        $('.file-select-popover b').trigger('click');
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
    $('#sort-chapter').delegate('.btn_del','click', function () {

        layer.confirm('确认删除吗?', {icon: 3, title:'确认删除'}, function(index){

            $(this).remove();
            $('#sort-chapter .lesson_wrap.selected').remove();
            layer.close(index);
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
        $('#sort-chapter .add_lesson').off('click');
        $('#sort-chapter .add_lesson').on('click', function () {
            var $_this = $(this);
            var $parents = $_this.parents('.chapter_wrapper');
            var lessonCount = $parents.find('.lesson_area').children('.lesson_wrap').length;
            var lesSort =  parseInt(lessonCount+1);
            var addLesStr = $('<li class="lesson_wrap title_wrap w_l drag-lesson clearfix" lesson="0" data-files_kj="" data-files_zl="" data-exam=""><div class="course_ac_icons" ><div class="course-icon-bg"><label class="choose-label" title="删除"><i class="icon btn_del"></i></label><label class="choose-label for-xt" title="上传习题"><i class="icon btn_edit"></i></label><label class="choose-label for-zl" title="上传资料"><i class="icon btn_file_zl"></i></label><label class="choose-label for-kj" title="上传课件"><i class="icon btn_file_kj" ></i></label></div></div><i class="c_l"></i><span class="lesson_sort">课时'+lesSort+'</span><input class="form-control lesson_name"/></li>');
            $parents.find('.lesson_area').append(addLesStr);
            jqueryBindFunc();
        });
        /*添加习题*/
        //$('#sort-chapter .add_exam').off('click');
        //$('#sort-chapter .add_exam').on('click', function () {
        //    var $_this = $(this);
        //    var $parents = $_this.parents('.chapter_wrapper');
        //    var lessonCount = $parents.find('.lesson_area').children('.lesson_wrap').length;
        //    var lesSort =  parseInt(lessonCount+1);
        //    var addLesStr = $('<li class="lesson_wrap title_wrap w_l drag-lesson clearfix" lesson="0" kj_file="" zl_files=""><div class="course_ac_icons" ><div class="course-icon-bg"><label class="choose-label" title="删除"><i class="icon btn_del"></i></label><label class="choose-label" title="上传习题"><i class="icon btn_edit"></i></label></div></div><i class="c_l"></i><span class="lesson_sort">习题'+lesSort+'</span><input class="form-control lesson_name"/></li>');
        //    $parents.find('.lesson_area').append(addLesStr);
        //    jqueryBindFunc();
        //});
        /*编辑章节名或者课时名称*/
        $('#sort-chapter input').off('click');
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
            //var top = $(this).offset().top;
            //var left = $(this).offset().left;
            //var width= $(this).width();
            //left = left+width-270;
            //$('#course-choose-icons').css('top',top);
            //$('#course-choose-icons').css('left',left);
            //$('#course-choose-icons').show();
            $('#sort-chapter .lesson_wrap.selected').removeClass('selected');
            $(this).addClass('selected');
        },function(){
            //$('#course-choose-icons').hide();
        });
        ///*隐藏课时操作按钮悬浮事件*/
        //$('#course-choose-icons').unbind();
        //$('#course-choose-icons').hover(function(){
        //    $('#course-choose-icons').show();
        //}, function () {
        //    $('#course-choose-icons').hide();
        //});

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
    if(lesson_id !='') {
        //初始化学科
        subObject.init({
            pt:$('input[name="subjectFatherName"]').val(),
            ct:$('input[name="subjectName"]').val()
        });
    }
    else{
        //初始化学科
        subObject.init({
            //pt:34,
            //ct:35
        });
    }
    /*新建课程*/
    $('.btn_new_course').on('click',function(){

        var name = $.trim($('input[name="course_name"]').val());
        if(name == '' || name.length == 0 || name.length > 40){
            $('.course_name').focus();
            layer.msg('请正确填写课程名称', {icon: 5});
            return false;
        }
        var title = $.trim($('.course_title').val());
        if( title == '' || title.length > 500 ){
            $('.course_title').focus();
            layer.msg('请正确填写课程副标题', {icon: 5});
            return false;
        }
        var pic = $('#fileList').data('picSrc');
        if(!pic&&!lesson_id){
            layer.msg('请先上传封面', {icon: 5});
            return false;
        }
        var desc = $.trim($('.course_desc').val());
        if( desc == '' || desc.length > 500){
            $('.course_desc').focus();
            layer.msg('请填写课程简介', {icon: 5});
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
        var father_subject_id = $('select[name="father_subject_id"]').val();
        var subject_id = $('select[name="subject_id"]').val();
        var description = ue.getContent();
        //课程状态
        var status = $('input[name="statusRadioOption"]:checked').val();
        var data;

        if(lesson_id){
            if(!pic) {
                pic = $('#fileList').data('picsrc');
            }
            //data = {url:'/api/lesson/editLesson',lesson_id:lesson_id,title:name,subtitle:title,pic:pic,label:labels,desc:desc,price:price,type:status,description:''};
            data = {url:'/api/lesson/editLesson',lesson_id:lesson_id,title:name,subtitle:title,pic:pic,desc:desc,father_subject_id:father_subject_id,subject_id:subject_id,price:0,type:status,description:description};

        }else{
            //data = {url:'/api/lesson/addLesson',title:name,subtitle:title,pic:pic,label:labels,desc:desc,price:price,type:status,description:''};
            data = {url:'/api/lesson/addLesson',title:name,subtitle:title,pic:pic,desc:desc,father_subject_id:father_subject_id,subject_id:subject_id,price:0,type:status,description:description};

        }
        remote_interface_api(data, function (serverData) {
            if(serverData.code === 0){


                layer.msg(lesson_id?'修改成功!进入下一步！':'创建成功，进入下一步!', {icon: 1});

                /*创建课程成功后的动作*/
                $('ul.create_nav_tabs li').removeClass('forbidden');
                clearTimeout(next);
                var next = setTimeout(function(){
                    $('ul.create_nav_tabs li[data-toggle="lesson-manage"]').addClass('active').siblings().removeClass('active');
                    $("#lesson-manage").show().siblings().hide();
                },1000)
                lesson_id=data.lesson_id = serverData.data.lesson_id;
                $('.btn_new_course').data('courseData',data);

                /*情况信息*/
                /*$('#course-container .form-group input,textarea').val('');
                 $('#course-container .form-group #fileList').empty();
                 $('#course-container ul.course_label_list').empty();*/
            }else{
                layer.msg(serverData.msg, {icon: 5});

            }
        });
    });
    /*编辑课程介绍*/
    $('.btn_edit_course_desc').on('click', function () {
        //var description = encodeURIComponent(ue.getContent());//解码的函数 decodeURIComponent(description);
        var description = ue.getContent();

        if( !lesson_id){

            layer.msg('请先创建课程的基本信息并保存!', {icon: 5});

            return false;
        }
        if( !description){

            layer.msg('请先添加课程介绍!', {icon: 5});

            return false;
        }
        var data={};


        data.title = $('.course_detail_container .course_title').val();
        data.desc =$('.course_detail_container .desc').val();
        data.type =$('input[name="statusRadioOption"]:checked').val();

        data.price = ($('input[name="radioOption"]:checked').val()==0)?0:($('input.course_cost').val());
        data.lesson_id = lesson_id;
        data.description = description;
        data.url = "/api/lesson/editLesson";
        data.pic = $('#fileList').data('picSrc')?$('#fileList').data('picSrc'):$('#fileList').data('picsrc');
        remote_interface_api(data, function (serverData) {

            if(serverData.code === 0){
                layer.msg('编辑课程介绍成功', {icon: 1});

            }else{
                layer.msg(serverData.msg, {icon: 5});


            }
        })

    });
    /*保存章节信息*/
    $('.btn_create_lesson ').on('click', function () {
        var js_false = true;
        if( !lesson_id){

            layer.msg('请先创建课程的基本信息并保存', {icon: 5});
            return false;
        }
        var data = {url:'/api/lesson/addLessonList','lesson_id':lesson_id,lesson_list:[]};
        $('#sort-chapter .chapter_wrapper').each(function () {
            //第一层遍历，章节信息
            var $pThis = $(this);
            var pId=$pThis.attr('chapter');
            var pSort = $pThis.index()+1;
            var pPath = '/';
            var pName = $.trim($pThis.find('.chapter_header .chapter_name').val());
            if(pName.length ==0 || pName == ''){

                layer.msg('章节名称不能为空', {icon: 5});
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

                    layer.msg('课时/习题名称不能为空', {icon: 5});
                    js_false = false;
                    return false;
                }
                var kjId = cThis.data('files_kj');//课件
                var kj =cThis.find('.btn_file_kj').length;
                if(!kjId && kj){

                    layer.msg('每个课时的课件都不能为空', {icon: 5});

                    js_false = false;
                    return false;
                }
                var zlFiles = cThis.data('files_zl')?((cThis.data('files_zl').toString()).split(',')):'';//资料
                var question_ids = cThis.data('exam')?((cThis.data('exam').toString()).split(',')):'';//题库
                var lessonInfo = {name:cName,path:cPath,sort:cSort,id:cid,file_ids:zlFiles,question_ids:question_ids,file:kjId};
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
                   // window.location.reload();
                });

                layer.msg('保存成功', {icon: 1});
            }else{

                layer.msg(serverData.msg, {icon: 5});

            }
        });
    });
    $('.btn_cancel_create_lesson').on('click',function(){
        $('#sort-chapter').empty();
    });
    /*获取选择课件或资料的列表*/
    function getChooseFile(data){


    }
    /*选择课件绑定事件*/
    function fileSelBind(type){
        /*隐藏选择课程课件或资料弹窗*/
        $('.file-select-popover b').on('click', function () {
            $(this).parent().fadeOut();
            $('body').removeClass('forbidden-scroll');
        });
        /*点击选择左边的文件区*/
        var num =[];
        $('#file-select-popover').undelegate('ul.file_list li','click');
        $('.file-select-popover').delegate('ul.file_list li','click', function (event) {
            /*
             * 先判断是否是目录，是目录则展开，不是则选中
             * */
            var bSHIFT = event.shiftKey;
            var bCTRL = event.ctrlKey;
            if(!$(this).hasClass('folder') && type!='kj'){
                if(!bSHIFT){
                    beginIndex =$(this).parent().children().not(".folder").index($(this));
                }

                if(bSHIFT){
                    var endClick = $(this).parent().children().not(".folder").index($(this));
                    $('.file-select-popover li.selected').removeClass('selected');
                    $(this).parent().children().not(".folder").slice(Math.min(beginIndex,endClick),Math.max(beginIndex,endClick)+1).addClass('selected');
                }
                else if(bCTRL){
                    $(this).addClass('selected');
                }
                else{
                    $('.file-select-popover li.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
            }
            else if(type=='kj'){
                $('.file-select-popover li.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        /*课件/资料/习题弹窗,右边x,删除右边文件*/
        $('.file-select-popover ul.file_r_list').off('click','i');
        $('.file-select-popover ul.file_r_list').on('click','i',function(){
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
                        var $fileHtml = $("<li class='ellipsis folder' data-file='"+file.id+"'><div class='treeview-node'><dfn class='b-in-blk treeview-ic'></dfn><span>"+file.file_name+"</span></div><ul folder='"+path+file.file_name+"'></ul></li>") ;
                    }else{
                        var $fileHtml = $("<li class='ellipsis' data-file='"+file.id+"'>"+file.file_name+"</li>") ;
                    }
                    $_this.next().append($fileHtml);
                }
                fileSelBind();
            });
        });
    }

    /*****************导入题库-S @date 2016.5.16*********************/
    //点击编辑,弹出选择习题弹窗
    $('#sort-chapter .for-xt').off('xt');
    $('#sort-chapter').on('click.xt','.for-xt',function(){
        $('#exam-select-popover ul').empty();
        var oldExam= $('ul#sort-chapter .lesson_area li.selected').data('exam');//获取原有的习题
        oldExam = oldExam?((oldExam.toString()).split(',')):'';

        /*获取习题库列表*/
        remote_interface_api({url:'/api/question/getQuestionList'}, function (examsList) {
            var exams = examsList.data.question_list;
            var fileHtml='';
            for(i in exams){
                fileHtml +='<li class="ellipsis" file="'+exams[i].id+'" data-file=\''+JSON.stringify(exams[i])+'\'>'+exams[i].title+'</li>';
                //$fileHtml.data('file',exams[i]);
                if(oldExam){//原有习题
                    for(var j=0;j<oldExam.length;j++){
                        if(oldExam[j]==exams[i].id){
                            var $htmlStr = $('<li class="ellipsis" file="'+exams[i].id+'"><sapn>'+exams[i].title+'</sapn><i></i></li>');
                            $('#exam-select-popover ul.file_r_list').append($htmlStr);
                            $htmlStr.data('fileIds',exams[i].id);
                        }
                    }
                }
            }
            $('#exam-select-popover ul.file_list').html(fileHtml);
            fileSelBind();
        });
        $('#exam-select-popover').fadeIn();
        $('.close-btn').on("click",function(){
            $('#exam-select-popover').hide();
        });
    });
    var ue_config={
        initialFrameWidth : 442,
        initialFrameHeight: 100,
        //elementPathEnabled:false,
       // enableAutoSave:false,
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
    };
    //新增习题
    $('#exam-select-popover').delegate('.choose-exam','click',function(){
        var question_level=[{id:1,value:'简单'},{id:2,value:'普通'},{id:3,value:'困难'}];//习题难易程度
        var ue=new Array();//編輯器組
        var layer_exam=layer.open({
            type: 1,
            title:'新增习题',
            shadeClose: true,
            move: false,
            area:['580px','90%'],
            content: template('templateExamEdit',{level:question_level}),
            end:function(){
                ue[1].destroy();//销毁实例化的编辑器
                ue[2].destroy();
                ue[3].destroy();
            },
            success:function(layerIndex){
                ue[2] = UE.getEditor('about-content2',ue_config);
                ue[3] = UE.getEditor('about-content3',ue_config);
                ue[1] = UE.getEditor('about-content',ue_config);

                /*新增习题类型切换*/
                $("#taskForm .tabTit label").on("click",function(){
                    $(this).addClass("checked").siblings("label").removeClass("checked");
                    $("#taskForm .tabCon").eq($("#taskForm .tabTit label").index($(this))).show().siblings(".tabCon").hide();
                });
                /*选项*/
                $("#taskForm .tabCon .labelBox label").on("click",function(){
                    _this=$(this).parent().find("input");
                    _this.each(function(index, element) {
                        if($(this).is(":checked")){

                            $(this).parent().addClass("checked");
                        }
                        else{
                            $(this).parent().removeClass("checked");
                        }
                    });

                });
                /*************************选项操作-S by sherry********************************/
                var letter=['A','B','C','D','E','F'];//选项内容
                /*添加选项*/
                $(".add-select").on("click",function(){
                    var $labelBox=$(this).prev('.labelBox');
                    var $label=$labelBox.children('label:last').clone(true);
                    var size=$labelBox.find('label').size();
                    $label.removeClass('checked');//移除选中class
                    $label.find('span').html(letter[size]);//替换选项值
                    $label=replay_letter_name(size,$label);//替换item 中 name
                    if($label.find('.delete-select').length>0){//如果复制的label中含有删除按钮,则删除之前都删除按钮
                        $labelBox.find('.delete-select').remove();//如果之前的选项有-号，则删除
                    }else{//如果复制的label中不含有删除按钮,则在复制都label中添加删除按钮
                        $label.append('<a href="javascript:;" class="delete-select" >-</a>');//后面加上删除选项按钮
                    }

                    $labelBox.append('<br>');
                    $labelBox.append($label);
                    if(size==5){//最多限制6个选项
                        $(this).hide();
                    }
                });
                //复制选项后，替换选项中item隐藏中的input
                var replay_letter_name=function(size,$label){
                    $label.find('input[type="hidden"]').each(function(){
                        $(this).prop('name','items['+size+']['+$(this).data('name')+']');//替换item 中 name
                    });
                    $label.find('.title').val(letter[size]);//替换item 中 title的value
                    $label.find('input[name="answer_arr[]"]').val(letter[size]);//替换item 中 answer的value
                    $label.find('input[name="answer_arr[]"]').removeAttr('checked');//移除选中属性
                    return $label;
                };
                /*删除选项*/
                $(".tabCon").delegate('.delete-select','click',function(){
                    var size=$(this).parents('.labelBox').children('label').size();
                    if(size<=6){//最多限制6个选项
                        $(this).parents('.labelBox').siblings('.add-select').show();
                    }
                    if(size>3){//包括本选项，最少保持三个选项,如果大于两个选择，则在this label上一个label上加上-删除
                        $(this).parents('.labelBox').find('label:eq('+(size-2)+')').append('<a href="javascript:;" class="delete-select" >-</a>');
                    }
                    $(this).parent('label').prev().remove();//删除前面的<br>
                    $(this).parent('label').remove();

                });
                /*************************选项操作-E********************************************/
                //习题提交
                var $submit=$('.submit');
                $submit.click(function(){
                    $submit.prop('disabled',true);
                    var $form;
                    var examType=($('.editCon .tabTit .checked').index())+1;//习题类型
                    $form=$('.exam-form-'+examType);
                    var answer=[];
                    $form.find('input[name="answer_arr[]"]:checked').each(function(){
                        answer.push($(this).val());
                    });
                    var data=$form.serialize()+'&'+$.param({'answer':(answer.join(',')),'type':examType,'content':(ue[examType].getContent())});
                    $.post('/api/question/addQuestion',data,function(data){

                        //习题提交添加完成后结果处理
                        $submit.prop('disabled',false);
                        if(data.code==0){
                            layer.close(layer_exam);
                            var exam=data.data.question;
                            $('#exam-select-popover ul.file_list').append('<li class="ellipsis" file="'+exam.id+'" data-file=\''+JSON.stringify(exam)+'\'>'+exam.title+'</li>');
                            return false;
                        }

                        layer.alert(data.msg);
                    },'json');
                });

            }
        });
    });

    /*****************导入题库-E @date 2016.5.16*********************/
});
