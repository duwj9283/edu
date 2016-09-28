/**
 * Created by sherry on 2016/5/21.
 */
$(function () {
    /*标头选中*/
    MYMICO.headActive($('#navbar-collapse-basic ul li.active'),$('#navbar-collapse-basic ul .camtasiastudio'));
    /**/

    var m_lesson_id=$('input[name="m_lesson_id"]').val();//微课id
    var m_lesson;//微课详情
    var type=1;//微课发布类型
    var kjArr=[];//原有的课件array
    var zlArr=[];//资料array
    if(m_lesson_id>0){
        MYMICO.getDetail({m_lesson_id:m_lesson_id},function(data){
            m_lesson=data.data.m_lesson;
            kjArr=dealArrToiNT(m_lesson.file.split(','));
            zlArr=dealArrToiNT(m_lesson.file_ids.split(','));
            m_lesson.label=m_lesson.label?(m_lesson.label.split(',')):'';
            $('.editCon').html(template('templateEdit',m_lesson));
            init();
            //初始化学科
            subObject.init({
                pt:$('input[name="subjectFatherName"]').val(),
                ct:$('input[name="subjectName"]').val()
            });
            getFiles('kj',function(kjList){
                var files = kjList.data.userFiles;
                var kjHtml='';
                for(var i=0;i<files.length;i++){
                    if(m_lesson.file && $.inArray(parseInt(files[i].id),kjArr) >-1){
                        kjHtml+=files[i].file_name+',';
                    }
                }
                $('input[name="kj"]').val(kjHtml.substr(0,kjHtml.length-1));
            });
            getFiles('zl',function(zlList){
                var files = zlList.data.userFiles;
                var zlHtml='';
                for(var i=0;i<files.length;i++){
                    if($.inArray(parseInt(files[i].id),zlArr) >-1){
                        zlHtml+=files[i].file_name+',';
                    }
                }
                $('input[name="zl"]').val(zlHtml.substr(0,zlHtml.length-1));
            });

        });
    }else{
        $('.editCon').html(template('templateEdit'))
        init();
        //初始化学科
        subObject.init({
            //pt:34,
            //ct:35
        });
    }
    //获取课件
    function getFiles(type,fun){
        MYMICO.getFilesList({url:'/api/file/getFiles','path':'/','page':1,'type':(type=='kj'?2:0)},fun);
    }
    //使数组中每个元素都是int型
    function dealArrToiNT(array){
        if(array){
            for( i in array){
                array[i]=parseInt(array[i]);
            }
            return array;
        }
    }
    function init(){
        /*初始化上传webuploader*/
        webUploader.init({
            label:'点击更换封面',
            pick:'#filePicker',
            thumbSize:{
                width:340,
                height:189
            }
        });
        //群组list
        var groupResult=function(data){
            if(data.data.groups.length>0){
                var groups=data.data.groups,html;
                for(var i in groups){
                    if(type==2&&$.inArray(groups[i].gid,group_ids)>-1){
                        //编辑微课 如果活动发布类型是群组 且 此id包含在群组id里
                        html+='<option value="'+groups[i].gid+'" selected>'+groups[i].name
                    }else{
                        html+='<option value="'+groups[i].gid+'">'+groups[i].name
                    }

                }
                $('select[name="group_id"]').html(html);
                $('select[name="group_id"]').prev('input').removeAttr('disabled');//如果有群组 则可选择
            }
        };
        MYMICO.getGroupList({},groupResult);
        /*radio*/
        $(".edit-body label").click(function(){

            if($(this).find("input[type='radio']").is(":checked")){

                $(this).parent().parent().find("label").removeClass("active");
                $(this).addClass("active");
                //如果是选中发布范围 且为私有
                if($(this).find("input[type='radio']").prop("name")=='type'){
                    if($(this).find("input[type='radio']").val()==3 ){
                        $('input[name="password"]').show();

                    }else{
                        $('input[name="password"]').hide();
                    }
                }
            }
        });
        /*显示添加标签页*/
        $('#addTag').on('click',function(){
            var $list = $(this).parent().find('ul.tags_list');
            LABELPOP.show($list);
        });
        /*添加标签*/
        $('#label-popover-container .btn_add_label').on('click', function () {
            var $list = $('ul.tags_list');
            LABELPOP.addLabel($list);
        });
        //创建微课
        var $form=$('form[name="mlesson-form"]');
        $form.submit(function(){
            if($form.valid()){
                var pic = $('#fileList').data('picSrc');
                if(!pic&&(m_lesson_id <=0 )){//新增的时候必须传群logo
                    layer.msg('请先上传微课封面!');return false;
                }
                if($form.find('input[name="type"]:checked').val()==3 && !$('input[name="password"]').val()){
                    //如果发布范围是私有 必须要有私有密码
                    layer.msg('请先填写私有密码!');return false;
                }
                $form.find(':submit').prop('disabled',true);
                var form_data=$form.serialize();
                if(pic){
                    form_data=form_data+"&pic="+pic;
                }
                //$('ul.tags_list li').each(function (index) {
                //    var label =$(this).find('span').text();
                //    form_data=form_data+"&label["+index+"]="+label;//标签
                //
                //});
                var zl=$('input[name="zl"]').data('file');
                var file_ids=[];
                for(i in zlArr){
                    form_data=form_data+"&file_ids["+i+"]="+zlArr[i];
                }
                //form_data=form_data+"&file="+($('input[name="kj"]').data('file'))+"&file_ids="+($('input[name="zl"]').data('file'));
                form_data=form_data+"&file="+kjArr;
                if(m_lesson_id>0){//编辑
                    MYMICO.editDetail(form_data+"&m_lesson_id="+m_lesson_id,dealResult);
                    return false;
                }
                //新增
                MYMICO.addDetail(form_data,dealResult);
            }
        });
        //提交后处理
        var dealResult=function(data){
            //return  false;
            $form.find(':submit').prop('disabled',false);
            if(data.code==0){//成功
                console.log("成功");
                window.location.href='/mico';
            }else{
                layer.msg(data.msg);
            }
        };
    }
    //移除已选择标签
    $('.editCon').delegate('.tags_list li a','click', function () {
        $(this).parent('li').remove();
    })
    /*****************添加关联课件or 关联资料-S*******************/
    //添加关联课件/关联资料弹窗
    $('.editCon').delegate('.add-btn','click', function () {
        var type=$(this).data('type');
        var oldFiles=(type=='kj')?kjArr:zlArr;//获取原有的课件
        $('#file-select-popover').data('type',type);

        if(type=='zl'){
            $('#file-select-popover .content-r .popover-content-header .title').text('已选择');
            $('#file-select-popover .choose-file input').attr('accept',null);


        }else{
            $('#file-select-popover .content-r .popover-content-header .title').text('最多选1个');
            $('#file-select-popover .choose-file input').attr('accept','.mp4,.avi,.rmvb');
            $("#file-select-popover").data('type','kj');
        }
        $('#file-select-popover ul').empty();
        $('body').addClass('forbidden-scroll');
        /*获取云盘文件列表*/
        MYMICO.getFilesList({url:'/api/file/getFiles','path':'/','page':1,'type':(type=='kj'?2:0)},function(serverData){
            var files = serverData.data.userFiles;
            var fileHtml='';
            for(var i=0;i<files.length;i++){
                var file = files[i];
                if(file.file_type == 1){
                    fileHtml+= "<li class='ellipsis folder' data-file='"+file.id+"'><div class='treeview-node'><dfn class='b-in-blk treeview-ic'></dfn><span>"+file.file_name+"</span></div><ul folder='"+'/'+file.id+"'></ul></li>" ;
                }else{
                    fileHtml+= "<li class='ellipsis' data-file='"+file.id+"'>"+file.file_name+"</li>" ;
                }
                if(oldFiles){
                    if($.inArray(parseInt(file.id),oldFiles) >-1){

                        var $htmlStr = $('<li class="ellipsis" data-file="'+file.id+'"><span>'+file.file_name+'</span><i></i></li>');
                        $('#file-select-popover ul.file_r_list').append($htmlStr);
                    }
                }
            }
            $('#file-select-popover ul.file_list').html(fileHtml);
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
    $('#file-select-popover').delegate('li','click', function () {
        /** 先判断是否是目录，是目录则展开，不是则选中* */
        var bSHIFT = event.shiftKey;
        var bCTRL = event.ctrlKey;
        var type = $("#file-select-popover").data('type');
        console.log(type);
        if(!$(this).hasClass('folder') && type !='kj'){
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
        else if(type == 'kj'){
            $('.file-select-popover li.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    /*弹窗左边操作----点击左边打开文件夹*/
    $('#file-select-popover').delegate('ul.file_list li .treeview-node','click',function(){
        var path = $(this).next().attr('folder')+'/';
        var $_this = $(this);
        MYMICO.getFilesList({'path':path,'page':1,'type':0},function(serverData){
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
        });
    });

    /*添加选择的文件到右侧列表*/
    $('#file-select-popover').delegate('.move-wrapper .move-r','click', function () {
        /*先判断是不是课件，课件只允许有一个关联课件*/
        var type = $("#file-select-popover").data('type');
        if(type=='kj' && $('.file_r_list li').size() == '1'){
            layer.msg('最多选1个，请删除右侧');
            return false;
        }
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
        if(type=='kj'){
            kjArr=files;
        }else{
            zlArr=files;
        }
        $('#file-select-popover b').trigger('click');
    });


    /*****************添加关联课件or 关联资料-E*******************/
});