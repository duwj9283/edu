$(function () {
    $('.body_side li.btn_my_live').addClass('selected');

    $('#navbar-collapse-basic ul li').removeClass('active');
    $('#navbar-collapse-basic ul .live').addClass('active');
    /*时间插件*/
    $('#startDate').datetimepicker({
        lang:"ch",
        formatDate:'Y/m/d'
    });
    $('#endDate').datetimepicker({
        lang:"ch",
        formatDate:'Y/m/d'
    });
    /*ui编辑器*/
    /*初始化ui编辑器*/
    var ue = UE.getEditor('about-content',{
        initialFrameHeight:200,
        elementPathEnabled:false,
        enableAutoSave:false,
        toolbars: [
            ['fullscreen', 'source', 'undo', 'redo'],
            ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
        ]
    });
    /*初始化上传webuploader*/
    webUploader.init({
        label:'点击更换封面',
        pick:'#filePicker',
        thumbSize:{
            width:340,
            height:189
        }
    });
    /************添加资料****************/
    //转换类型
    function dealArrToiNT(array){
        if(array){
            for( i in array){
                array[i]=parseInt(array[i]);
            }
            return array;
        }
    }
    //var files = $('#addFiles').data('files').toString();
    var zlArr=dealArrToiNT($('#addFiles').data('files').toString().split(','));//资料array
    $('.editCon').delegate('#addFiles','click', function () {
        var oldFiles = zlArr;
        var type=$(this).data('type');
        $('#file-select-popover').data('type',type);

        $('#file-select-popover .content-r .popover-content-header .title').text('已选择');
        $('#file-select-popover .choose-file input').attr('accept',null);
        $('#file-select-popover ul').empty();
        $('body').addClass('forbidden-scroll');

        /*获取云盘文件列表*/

        createModel.getFilesList({url:'/api/file/getFiles','path':'/','page':1,'type':0},function(serverData){
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
        });
        //获取右边文件
        createModel.getFileNameByIds({ids:zlArr.join(',')},function(serverData){
            var list = serverData.data.userFileList;
            for(var i=0;i<list.length;i++){
                var htmlStr = $('<li class="ellipsis" data-file="'+list[i].id+'"><span>'+list[i].file_name+'</span><i></i></li>');
                $('#file-select-popover ul.file_r_list').append(htmlStr);
            }
        });

        $('#file-select-popover').fadeIn();
    });
    /*隐藏选择课程课件或资料弹窗*/
    $('#file-select-popover').delegate('b','click', function () {
        $(this).parent().fadeOut();
        $('body').removeClass('forbidden-scroll');
    });
    $('#file-select-popover').on('click','.close-btn',function(){
        $('#file-select-popover').fadeOut();
        $('body').removeClass('forbidden-scroll');
    })
    /*弹窗左边操作----点击选择左边的文件区*/
    var beginIndex=null; //记录开始位置
    $('#file-select-popover').delegate('ul.file_list li','click', function () {
        /** 先判断是否是目录，是目录则展开，不是则选中* */
        var bSHIFT = event.shiftKey;
        var bCTRL = event.ctrlKey;
        if(!$(this).hasClass('folder')){
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
    });
    /*弹窗左边操作----点击左边打开文件夹*/
    $('#file-select-popover').on('click','ul.file_list li .treeview-node',function(){
        var path = $(this).next().attr('folder')+'/';
        var $_this = $(this);

        createModel.getFilesList({'path':path,'page':1,'type':0},function(serverData){
            $_this.next().empty();
            var files = serverData.data.userFiles;
            for(var i=0;i<files.length;i++){
                var file = files[i];
                if(file.file_type == 1){
                    var $fileHtml = $("<li class='ellipsis folder' data-file='"+file.id+"'><div class='treeview-node'><dfn class='b-in-blk treeview-ic'></dfn><span>"+file.file_name+"</span></div><ul folder='"+path+file.file_name+"'></ul></li>");
                }else{
                    var $fileHtml = $("<li class='ellipsis' data-file='"+file.id+"' data-path='"+path+"'>"+file.file_name+"</li>") ;
                }
                $_this.next().append($fileHtml);
               // $fileHtml.data('fileinfo',file);
            }
        });
    });
    /*添加选择的文件到右侧列表*/
    $('#file-select-popover').delegate('.move-wrapper .move-r','click', function () {
        /*选择课件时，替换*/
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
    /*弹窗左边操作----删除右边文件*/
    $('#file-select-popover').delegate('ul.file_r_list li i','click',function(){
        $(this).parent().remove();
    });
    /*保存选中的文件*/
    $('#file-select-popover').delegate('.save-file','click',function(){
        var type = $('#file-select-popover').data('type');
        var files = [];
        var files_names = [];//文件名称
        $('#file-select-popover ul.file_r_list li').each(function(){
            var $this = $(this);
            files.push(parseFloat($this.data('file')));
            files_names.push($this.find('span').html());
        });
        $('input[name="'+type+'"]').val(files_names.join(','));
        zlArr=files;
        console.log(zlArr);
        $('#file-select-popover b').trigger('click');
    });
    /************添加资料 end****************/
    /*显示添加标签页*/
    $('#addTag').on('click',function(){
        var $list = $(this).parent().find('ul.tags_list');
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
        $('ul.tags_list').empty();
        $('#label-popover-container ul li.selected').each(function () {
            var label = $(this).text();
            var $liHtmlStr = $('<li class="form-control"><span>'+label+'</span><a>✕</a></li>')
            $('ul.tags_list').append($liHtmlStr);
        });
        //隐藏标签页
        $('#label-popover-container').fadeOut(function(){
            $('#label-popover li.selected').removeClass('selected');
        });

    });
    //绑定删除已添加的标签按钮
    $('ul.tags_list li a').undelegate();
        $('ul.tags_list').delegate('li a','click',function () {
        $(this).parent().remove();
    });
    /*radio*/
    $(".radioBox label").click(function(){

        if($(this).find("input[type='radio']").is(":checked")){

            $(this).parent().parent().find("label").removeClass("active");
            $(this).addClass("active");

        }
    });
    /* 发布范围 publish_type */
    $('input[name="publish_type"]').click(function(){
        if($(this).val() == 3){
            $('input[name="visit_pass"]').show();
        }else{
            $('input[name="visit_pass"]').hide();
        }


    });
    /* 收费价格 is_charge */
    $(".is_charge").click(function(){

        if($(this).parents('.radioBox').index()==2 ){
            $('input[name="visit_pass"]').show();
        }else{
            $('input[name="visit_pass"]').hide();
        }


    });
    var type=1;//发布范围

    //群组list
    var groupResult=function(data){
        if(data.data.groups.length>0){
            var groups=data.data.groups,html;
            for(var i in groups){
                if(type==2&&$.inArray(groups[i].gid,group_ids)>-1){
                    //编辑活动 如果活动发布类型是群组 且 此id包含在群组id里
                    html+='<option value="'+groups[i].gid+'" selected>'+groups[i].name
                }else{
                    html+='<option value="'+groups[i].gid+'">'+groups[i].name
                }

            }
            $('select[name="group_id"]').html(html);
            $('select[name="group_id"]').prev('input').removeAttr('disabled');//如果有群组 则可选择
        }
    };
    createModel.getGroupList({},groupResult);



    //创建直播

    if($('input[name="clasRoom"]').val() != ''){
        $('select[name="class_room_id"] option[value='+ $('input[name="clasRoom"]').val() +']').attr("selected","selected");
    }
    var $form=$('form[name="live-form"]');
    var id=$('input[name="id"]').val();//直播id
    if(id>0){
        /*初始化学科*/
        subObject.init({
            pt:$('input[name="subjectFatherName"]').val(),
            ct:$('input[name="subjectName"]').val()
        });
        //初始化资料
        createModel.getFileNameByIds({ids:zlArr.join(',')},function(serverData){
            var list = serverData.data.userFileList;
            var htmlStr = '';
            for(var i=0;i<list.length;i++){
                htmlStr += list[i].file_name+',';
            }
            $('input[name="zl"]').val(htmlStr.substr(0,htmlStr.length-1));
        });

    }
    else{
        /*初始化学科*/
        subObject.init({});
    }
    $form.submit(function(){
        if($form.valid()){
            var pic = $('#fileList').data('picSrc');
            if(!pic&&!id){//新增的时候必须传群logo
                layer.alert('请先上传直播封面!');return false;
            }
            if($form.find('input[name="publish_type"]:checked').val()==3 && !$('input[name="visit_pass"]').val()){//如果发布范围是私有 必须要有私有密码
                layer.alert('请先填写私有密码!');return false;
            }
            $form.find(':submit').prop('disabled',true);
            var form_data=$form.serialize();
            if(pic){
                form_data=form_data+"&cover_pic="+pic;
            }
            $('ul.tags_list li').each(function (index) {
                var label =$(this).find('span').text();
                form_data=form_data+"&tags["+index+"]="+label;//标签

            });
            var file_ids=[];
            form_data=form_data+"&file_ids="+zlArr.join(',');
            if(id){//编辑
                console.log(form_data+"&id="+id);
                createModel.updateDetail(form_data+"&id="+id,dealResult);
                return false;
            }
            //新增
            console.log(form_data);
            createModel.createDetail(form_data,dealResult);
        }
    });
    //提交后处理
    var dealResult=function(data){
        $form.find(':submit').prop('disabled',false);
        if(data.code==0){//成功
            layer.msg("成功")
            window.location.href='/live/index/manage';
        }else{
            layer.msg(data.msg);
        }
    };
});