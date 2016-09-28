$(function(){


    var lesson_list_id=$('#classArticle').data('id');
    var lesson_id=$('#classArticle').data('lessonid');
    var lesson;//当前播放课时
    var playerInstance;//播放player
    var questionCur;//当前习题
    var questionCounter;//总共多少习题
    var time_point='00:00',layerDiv;//当时视频播放时间 提问与笔记弹出框

    var dealLessonList=function(serverData){
        if(serverData.code === 0) {
            lesson = serverData.data;
            $('.next-video').data('id', lesson.next_lesson_list_id);
            /***************************初始化视频容器-S***************************/
            //var fUrl = 'rtmp://123.59.146.75/vod/mp4:9/85后公交女司机劝阻男乘客抽烟被暴打5分钟_高清.mp4';
            jwplayer.key = "O4G/7OoH6r9ioOg0VZQ1Ptmr+rAfP9BNQQzQYQ==";
            playerInstance = jwplayer('video-container').setup({
                flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                file: lesson.path,
                //file:'http://static.zqgame.com/html/playvideo.html?name=http://lom.zqgame.com/v1/video/LOM_Promo~2.flv',
                // image: coverSrc,
                width: '100%',
                height: 500,
                dock: false,
                autostart: true,
                //autostart:false,
                skin: {
                    name: "vapor"
                }
            });
            //视频播放到最后
            playerInstance.on('complete',function(){
                playerInstance.pause(true);//暂停视频
                time_point=timeFormat(playerInstance.getPosition());//获取当前视频播放时间
                showQustion({lesson_list_id:lesson_list_id,type:1},'end');

            })

            $('#file ul').html(template('file-list',{file_list:lesson.relation_file_list}));//关联文件
        }else{
            layer.msg(serverData.msg,{
                icon:5,
                time:1000
            });
        }

        /***************************初始化视频容器-D***************************/
    };
    PlayModel.getLessonFilePath({lesson_list_id:lesson_list_id},dealLessonList);//获取章节视频
    //点击右侧切换章节 or 点击下一章节
    $('.videoMenu-list ul li,.next-video').click(function(){
        var id=$(this).data('id');
        if(id>0){
            lesson_list_id=id;
            $('.videoMenu-list ul li').removeClass('current');
            $('.videoMenu-list ul li[data-id="'+lesson_list_id+'"]').addClass('current');
            PlayModel.getLessonFilePath({lesson_list_id:lesson_list_id},dealLessonList);
            return false;
        }
        layer.msg('这已经是最后一节!',{
            icon:5,
            time:1000
        });

    })

    /*tab 切换*/
    $(".tabNav li").on("click",function(){
        $(this).addClass("on").siblings().removeClass("on");
        $(".tabCon").eq($(".tabNav li").index($(this))).show().siblings(".tabCon").hide();
    });
    /*******************************评论-S*************************************/
    //处理提交评论后
    var dealCommentResult=function(serverData){
        if(serverData.code === 0){
            layer.msg('评论成功!',{
                icon:1,
                time:1000
            });
            var comment=serverData.data.content;
            var user=serverData.data.userInfo;
            comment.ref_id=parseInt(comment.ref_id);
            if(comment.ref_id > 0){//回复
                var $thisItem=$('#cmtList .cItem[data-id="'+comment.ref_id+'"]');
                $thisItem.find('.reply-comment-input').val('');
                $thisItem.find('.replayForm').remove();
                $thisItem.append(template('comment-detail',{comment:comment,user:user}));

            }else{
                $('.course-comment-input').val('');
                $('#cmtList').append(template('comment-detail',{comment:comment,user:user}));
            }

        }else{
            layer.msg(serverData.msg,{
                icon:5,
                time:1000
            });
        }
    }
    /*提交评论*/
    $('.content').delegate('.course-comment','click', function () {
        var ref_uid = 0;
        var ref_id = 0;
        var content = $('.course-comment-input').val();
        if(content=='' || content.length == 0 ){
            layer.tips('评论内容不能为空','.course-comment-input',{
                tips:[3,'#2cb07e'],
            });
            return false;}
        PlayModel.courseComment({lesson_id:lesson_id,ref_uid:ref_uid,ref_id:ref_id,content:content},dealCommentResult);
    });
    //回复
    $('.content').delegate('#cmtList .reply','click', function () {
        $t=$(this);
        $('.replay-row-container').remove();
        var $p = $t.parents('.comment-row');
        var commentUId = $t.data('cmt-uid');
        var commentId = $t.data('cmt-id');
        $p.append(template('replayRowTem',{commentUId:commentUId,commentId:commentId}));
        return false;
    });
    //提交回复
    $('.content').delegate('.btn-reply-comment','click', function () {
        var ref_uid = $(this).data('cmtUid');
        var ref_id = $(this).data('cmtId');
        var content = $(this).parent().find('.reply-comment-input').val();
        if(content == '' || content.length == 0){
            layer.tips('评论不能为空','.replay-row-container .reply-comment-input',{
                tips:[3,'#2cb07e'],
            });
            return false;
        }
        PlayModel.courseComment({lesson_id:lesson_id,ref_uid:ref_uid,ref_id:ref_id,content:content},dealCommentResult);


    });
    /*取消回复*/
    $('body').on('click', function(event) {
        var ifRm =true;
        var evt = event.srcElement ? event.srcElement : event.target;             // IE支持 event.srcElement ， FF支持 event.target
        if($(evt).hasClass('replay-row-container') || $(evt).hasClass('reply-comment-input') || $(evt).hasClass('btn-reply-comment')){
            ifRm=false;
        }
        if(ifRm){
            $('.replay-row-container').remove();
        }
    });

    /*******************************评论-D*************************************/
    /*******************************笔记/提问-S*************************************/
    //由秒=>时:分:秒
    var timeFormat=function(s){
        var t='';
        if(s > -1){
            hour = Math.floor(s/3600);
            min = Math.floor(s/60) % 60;
            sec = Math.floor(s % 60);
            day = parseInt(hour / 24);
            if(hour < 10){hour += "0";}
            if (day > 0) {
                hour = hour - 24 * day;
                t = day + "day " + hour + ":";
            }else{
                //t = hour + ":";
            }

            if(min < 10){t += "0";}
            t += min + ":";
            if(sec < 10){t += "0";}
            t += sec;
        }
        return t;
    }
    function showQustion(param,state){
        PlayModel.lessonQuestion(param,function(serverData){
            if(serverData.code==0){
                questionCur=serverData.data.question[0];
                questionCounter=serverData.data.questionCounter;
                //处理content html标签跟img
                var content = questionCur.content;
                var img =[];
                content['con']= htmldecode(content);
                content.replace(/<img [^>]*src=['"]([^'"]+)[^>]*>/gi, function (match, capture) {
                    img.push(capture);
                    questionCur['img'] = img;
                });
                //
                $('#taskList .item').html(template('exam-detail',{exam:questionCur,answer:serverData.data.myAnswer}));
                //弹出层
                layerDiv=layer.open({
                    type: 1,
                    title: '习题',
                    closeBtn: 1,
                    shadeClose: true,
                    move: false,
                    area:['980px','auto'],
                    content: $('#taskList'),
                    end:function(){
                        if(state == 'end'){
                            layer.msg('准备播放下一章节');
                            $('.videoMenu-list ul li,.next-video').trigger('click');
                        }
                        else{
                            playerInstance.pause(false);//继续播放
                        }
                    }
                });
                //弹出层 end
                if(questionCur.index>0){//如果不是第一道题  显示上一题按钮

                    $('#taskList .msg .before-part').css('visibility','visible');
                }
                if(questionCur.index+1>=questionCounter){//如果是最后一道题  下一题显示完成

                    $('#taskList .msg .exam-next-btn[data-type="next"]').val('完成');
                    $('#taskList .msg .exam-next-btn[data-type="next"]').data('type','done');
                }


            }else{
                layer.msg(serverData.msg,{
                    icon:1,
                    time:1000
                });
                layer.close(layerDiv);

            }



        });//获取最新习题
    }
    //笔记 提问 dialog
    $('[data-layer]').on("click",function(){
        playerInstance.pause(true);//暂停视频
        time_point=timeFormat(playerInstance.getPosition());//获取当前视频播放时间
        var iType=$(this).data("layer").type, oTit=null, oCon=null, iWidth=['980px','90%'],closeBtnNum=1;
        /*习题*/
        if(iType=='xt'){
            showQustion({lesson_list_id:lesson_list_id});


        }
        /*习题 end */
        /*笔记*/
        if(iType=='bj'){
            oTit= false;
            closeBtnNum=0;
            iWidth=['980px',"auto"];
            oCon=$('#ask[data-id="note"]');
            layerDiv=layer.open({
                type: 1,
                title: oTit,
                closeBtn: closeBtnNum,
                shadeClose: true,
                move: false,
                area:iWidth,
                content: oCon,
                cancel:function(){
                    playerInstance.pause(false);//继续播放

                }
            });
        }
        /*笔记 end */
        /*提问*/
        if(iType=='tw'){
            oTit= false;
            closeBtnNum=0;
            iWidth=['980px',"auto"];
            oCon=$('#ask[data-id="ask"]');
            layerDiv=layer.open({
                type: 1,
                title: oTit,
                closeBtn: closeBtnNum,
                shadeClose: true,
                move: false,
                area:iWidth,
                content: oCon,
                cancel:function(){
                    playerInstance.pause(false);//继续播放

                }
            });
        }
        /*提问 end */


    });
    /*初始化笔记ui编辑器*/
    //var ue = UE.getEditor('note-content',{
    //    initialFrameHeight:400,
    //    initialFrameWidth:900,
    //    elementPathEnabled:false,
    //    enableAutoSave:false,
    //    toolbars: [[
    //        'fullscreen', 'source', '|', 'undo', 'redo', '|',
    //        'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist','|',
    //        'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
    //        'directionalityltr', 'directionalityrtl', 'indent', '|',
    //        'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify','|',
    //        'simpleupload', 'insertimage', 'emotion',
    //        'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
    //        'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
    //        'print', 'preview', 'searchreplace', 'help'
    //    ]]
    //});
    //处理提交 提问/笔记后
    var dealLayerAskResult=function(serverData){
        if(serverData.code === 0){
            layer.msg('提问成功!',{
                icon:1,
                time:1000
            });
            layer.close(layerDiv);
            playerInstance.pause(false);//继续播放
            $('#question').prepend(template('ask-detail',{content:serverData.data.content,user:serverData.data.userInfo}));

        }else{
            layer.msg(serverData.msg,{
                icon:5,
                time:1000
            });
        }
    }
    var dealLayerNoteResult=function(serverData){
        if(serverData.code === 0){
            layer.msg('笔记提交成功!',{
                icon:1,
                time:1000
            });
            layer.close(layerDiv);
            $('#notes').prepend(template('note-detail',{content:serverData.data.content,info:serverData.data.lesson_list_info}));
            $(".tabNav li:eq(3)").trigger('click');
            playerInstance.pause(false);//继续播放
            ue.setContent('');//设置编辑器内容为空

        }else{
            layer.msg(serverData.msg,{
                icon:5,
                time:1000
            });
        }
    }
    //提交
    $('form[name="lesson-form"]').submit(function(){
        $this=$(this);
        var type=$this.find('input[name="type"]').val();
        if(type=='ask'){
            var content=$this.find('textarea[name="content"]').val();
            if(!content){
                $this.find('textarea[name="content"]').focus();return false;
            }
            PlayModel.askLesson({lesson_list_id:lesson_list_id,time_point:time_point,content:content,ref_uid:0,ref_id:0},dealLayerAskResult);

        }else if(type="note"){
            //var content = ue.getContent();
            var content = $('textarea[name="note-content"]').val();
            if(!content){
                layer.msg('请输入您的笔记内容!');return false;
            }
            PlayModel.noteLesson({lesson_list_id:lesson_list_id,time_point:time_point,content:content},dealLayerNoteResult);

        }
    });
    /*提问回复按钮*/
    $('#question').delegate("reply","click",function(){
        $(this).hide();
        $(this).next().show();
    });
    var dealAskReplayResult=function(serverData){
        $('.ask-replay').prop('disabled',false);

        if(serverData.code === 0){
            serverData.data.content.ref_id=parseInt(serverData.data.content.ref_id);
            layer.msg('回复成功!',{
                icon:1,
                time:1000
            });
            var $thisItem=$('#question .item[data-id="'+serverData.data.content.ref_id+'"]');
            $thisItem.find('.replayForm textarea').val('');
            $thisItem.find('.replayForm').hide();
            $thisItem.find('.reply').show();
            $thisItem.find('.replyCon:eq(0)').before(template('ask-detail',{content:serverData.data.content,user:serverData.data.userInfo}));



        }else{
            layer.msg(serverData.msg,{
                icon:5,
                time:1000
            });
        }

    }
    //提问回复提交
    $('#question').delegate('.ask-replay','click', function () {
        var $this = $(this);
        $this.prop('disabled',true);
        var ref_uid = $this.parents('.item').data('uid');//回答某个人的ID
        var ref_id = $this.parents('.item').data('id');//回答的提问ID
        var content = $this.siblings('textarea').val();
        if(content == '' || content.length == 0){
            layer.tips('回复不能为空',$this.siblings('textarea'),{
                tips:[3,'#2cb07e']
            });
            return false;
        }
        PlayModel.askLesson({lesson_list_id:lesson_list_id,time_point:time_point,content:content,ref_uid:ref_uid,ref_id:ref_id},dealAskReplayResult);
    });

    /*做习题*/
    $("#taskList").delegate('.labelBox label',"click",function(){

        _this=$(this).parent().find("input");
        //alert(_this.find("input").is(":checked"));
        _this.each(function(index, element) {
            if($(this).is(":checked")){

                $(this).parent().addClass("checked");
            }
            else{
                $(this).parent().removeClass("checked");
            }
        });
    });
    //习题点击确认  确认返回后处理
    var dealAnswer=function(serverData){

        if(serverData.code === 0) {
            var answer=serverData.data.question;
            $('#taskList .msg .next-part').css('visibility','visible');

            $('#taskList .msg .answer').html(answer.status==0?('答错了,正确答案是：'+answer.answer):'恭喜你，答对啦');
            $('#taskList .msg dd').html(answer.analysis);//答案解析

            $('#taskList .msg .operate input[data-type="next"]').prop('disabled',false);

        }else{
            layer.msg(serverData.msg,{
                icon:5,
                time:1000
            });
        }

    }
    //习题点击确认
    $('#taskList').delegate('.exam-btn','click',function(){
        var $this=$(this);
        $this.prop('disabled',true);
        var if_choice=$('#taskList .labelBox label.checked').length;
        if(if_choice){
            var answer;//答案
            if($.inArray(parseInt(questionCur.type),[1,2])>=0){
                //判断题//单选题
                answer=$('#taskList .labelBox label.checked input').val();
            }else if(parseInt(questionCur.type)==3){
                //多选题
                var str=[];
                $('#taskList .labelBox label.checked input').each(function(){
                    str.push($(this).val());
                })
                answer=str.join(',');
            }
            PlayModel.answer({question_id:questionCur.id,answer:answer,lesson_list_id:lesson_list_id},dealAnswer);//提交答案


        }else{
            layer.tips('请先勾选您的答案!','#taskList .ans',{
                tips:[3,'#2cb07e']
            });
            $this.prop('disabled',false);


        }
    })
    //习题  上一题 下一题
    $('#taskList').delegate('.exam-next-btn','click',function(){
        var type=$(this).data('type');
        if(type=='before'){
            //上一题
            if(questionCur.index>0){
                showQustion({lesson_list_id:lesson_list_id,index:questionCur.index-1});

            }else{
                layer.msg('已经是第一道题',{
                    icon:5,
                    time:1000
                });
                $('#taskList .msg .before-part').prop('visibility','hidden');

            }
        }else if(type=='next'){
            //上一题
            if(questionCur.index+1<questionCounter){
                showQustion({lesson_list_id:lesson_list_id,index:questionCur.index+1});


            }else{
                layer.msg('已经是最后道题',{
                    icon:5,
                    time:1000
                });
                $('#taskList .msg .operate input[data-type="next"]').prop('disabled',true);

            }

        }else if(type=='done'){
            //最后一题 完成
            layer.close(layerDiv);

        }


    })
    /*******************************笔记/提问-D*************************************/

    $(".videoMenu-box").niceScroll();
    /*播放菜单*/
    //$(".videoMenu-btn,.js-menu-btn").on("click",function(){
    //    if($(this).hasClass("videoMenu-btn-open")){
    //        $(this).removeClass("videoMenu-btn-open");
    //        $(".videoMenu-box").hide();
    //        $(".videoMenu").width(0)
    //    }
    //    else{
    //        $(this).addClass("videoMenu-btn-open");
    //        $(".videoMenu-box").show();
    //        $(".videoMenu").width("200px")
    //    }
    //});
    $(".js-menu-btn").on("click",function(){
        if($(this).hasClass("open")){
            $(this).removeClass("open");
            $('.videoMenu').stop().animate({'marginRight':'0'});
        }
        else{
            $(this).addClass("open");
            $('.videoMenu').stop().animate({'marginRight':'-200px'});
        }
    });
    $(".video-wraper").hover(function(){
            $(".video-wraper .operate,.video-wraper .menu,.video-wraper .next").fadeIn()},function(){
            $(".video-wraper .operate,.video-wraper .menu,.video-wraper .next").fadeOut()}
    );


    /*推荐*/
    $(".recommend li").mouseenter(function(){
        $(this).addClass("on").siblings().removeClass("on");
    });


    //关联文件下载
    $('#file').delegate('li','click',function(){
        var file_ids=[];
        file_ids.push($(this).data('id'));
        //PlayModel.download({file_ids:file_ids});

    });

    $(".videoMenu-list .list").slimscroll({
        height: '100%',
        size:'5px',
        color:'#e7ebee',
        distance:'5px',
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
})

function htmldecode(str) {
    //str = str.replace(/<img(?:.|\s)*?>/g,"");
    //console.log(str);
    str = str.replace(/<[^>]+>/g,"");
    str = str.replace(/&nbsp;/gi, ' ');
    str = str.replace(/&quot;/gi, '"');
    str = str.replace(/&#39;/g, "'");
    str = str.replace(/&lt;/gi, '<');
    str = str.replace(/&gt;/gi, '>');
    str = str.replace(/<br[^>]*>(?:(rn)|r|n)?/gi, 'n');
    console.log(str);
    return str;
}