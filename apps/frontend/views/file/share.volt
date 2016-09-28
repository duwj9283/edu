{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    <style>
        #wraper{ padding:30px 0; background-color: #f1f1f1;}
        .share-wrap{  padding:30px; background: #fff; border:1px solid #ddd; box-shadow: 0 3px 3px #eee;}
        .share-file-name{ font-size:16px; color:#000; line-height: 32px;}
        .share-file-time{ padding-top:5px; color: #999; font-size: 12px;}
        .share-btns .btn{ float:right;border:1px solid #a7a7a7; background: #f3f3f3;background: -webkit-linear-gradient(top, #fefefe 0%,#f3f3f3 100%);background: linear-gradient(to bottom, #fefefe 0%,#f3f3f3 100%); color: #666; font-size:12px;}
        .share-btns .share-down{padding-left:35px; background: url(/img/frontend/home/icon_download.png) no-repeat 12px center;}
        .share-content{ padding: 20px;}
        .share-content .file-info{ overflow: hidden;border:1px solid #eee; text-align: center;}
        .share-content .icon-other{ margin: 50px auto; width: 72px; height: 81px; background: url(/img/frontend/camtasiastudio/other.png) no-repeat center;}
        .share-file-size{ padding-top: 5px; color: #999; font-size: 12px;}
        .file-video{ width:100%; height: 600px;}
        .file-pdf{ height:800px;}
        #iframe-pdf{ width:100%; height: 100%;}
        .no-file-msg{ color: #999;}
        /*文档转换*/
        .ppt-wrap{ border:1px solid #ddd;}
        .ppt-wrap .ppt-con{ position: relative; background-color: #fff;}
        .ppt-wrap .ppt-con .pre,.ppt-wrap .ppt-con .next{ position: absolute; width:50%; top: 0; bottom:0;}
        .ppt-wrap .ppt-con .pre{ left: 0;}
        .ppt-wrap .ppt-con .next{ right:0;}
        .ppt-wrap .ppt-con .icon{display: none; position: absolute; width:20px; height: 15px; top: 50%; margin-top: -8px;}
        .ppt-wrap .ppt-con .pre .icon{left: 20px; background-position: -30px 0; }
        .ppt-wrap .ppt-con .next .icon{right:20px; background-position: -30px -30px;}
        .ppt-wrap .ppt-con .img{display: none; max-width: 100%; height: auto; margin:0 auto;}
        .ppt-wrap .ppt-bar{ height:38px; padding-top: 5px; background-color: #eee; border-top:1px solid #ddd; text-align: center;}
        .ppt-wrap .ppt-bar span{ display: inline-block; vertical-align: middle;}
        .ppt-wrap .ppt-bar em{ font-style: normal;}
        .ppt-wrap .ppt-bar input{ width: 38px; border:1px solid #ddd; text-align: center;}
        .ppt-wrap .icon{background-image: url(/img/frontend/web/ppticon.png); background-position: 0 0; background-repeat: no-repeat; }
        .ppt-wrap .ppt-bar .pre,.ppt-wrap .ppt-bar .next{display:inline-block; vertical-align:middle;width:24px; height:24px;background-image: url(/img/frontend/web/ppticon.png); background-position: 0 0; background-repeat: no-repeat;  border:1px solid #eee;}
        .ppt-wrap .ppt-bar .pre{margin-right:10px; background-position: 0 4px;}
        .ppt-wrap .ppt-bar .pre:hover{ border-color: #999 #999 #c8c8c8 #c8c8c8; background-position: -30px 4px;}
        .ppt-wrap .ppt-bar .next{margin-left: 10px; background-position: 0 -26px;}
        .ppt-wrap .ppt-bar .next:hover{ border-color: #999 #999 #c8c8c8 #c8c8c8;background-position: -30px -26px;}
        .ppt-wrap .loading{width:100%; height: 300px; background:url(/js/frontend/less2/layer/skin/default/loading-2.gif) no-repeat center;}
        .ppt-wrap .to-left{cursor:url('/img/frontend/web/toleft.ico'),auto; }
        .ppt-wrap .to-right{cursor:url('/img/frontend/web/toright.ico'),auto; }
    </style>
{% endblock %}
{% block content %}
    <div id="wraper">
        <div class="container">
            <div class="share-wrap">
                <div class="share-title row">
                    <div class="col-sm-8">
                        <h2 class="share-file-name" data-id="{{ fileInfo.id }}">{{ fileInfo.file_name }}</h2>
                        <p class="share-file-time" >分享时间：{{ share_time }}</p>
                    </div>
                    <div class="col-sm-4">
                        {% if( status == 1) %}
                        <div class="share-btns clearfix">
                            <div class="btn share-down" id="js-down-file" data-id="{{ fileInfo.id }}">下载文件</div>
                        </div>
                        <div class="share-file-size text-right">文件大小：{{ fileInfo.file_size }}</div>
                        {% endif %}
                    </div>
                </div>
                <div class="share-content row">
                    {% if( status == 1) %}
                    {% if fileInfo.file_type == 2 %}
                    <div class="file-video">
                        <div id="file-video"></div>
                    </div>
                    {% elseif fileInfo.file_type == 3 %}
                    <div class="file-info file-img">
                        <img src="/api/source/getImageThumb/{{ fileInfo.id }}/1096/1000">
                    </div>
                    {% elseif fileInfo.file_type == 4 %}
                    <div class="file-video">
                        <div id="file-video"></div>
                    </div>
                    {% elseif fileInfo.file_type == 5 %}
<!--                    <div id="file-pdf" class="file-pdf"></div>-->
                    <div class="ppt-wrap js-ppt" id="file-pdf">
                        <div class="ppt-con">
                            <div class="loading"></div>
                        </div>
                        <div class="ppt-bar">
                            <a href="javascript:;" class="pre"></a>
                            <span><input id="txtPictureIndex" type="text" value="1"> / <em class="js-total"></em></span>
                            <a href="javascript:;" class="next"></a>
                        </div>
                    </div>
                    {% elseif fileInfo.file_type == 6 %}
                    <div class="file-info"><div class="icon-other"></div></div>
                    {% endif %}
                    {% elseif( status == 0) %}
                    <div class="file-info">
                        <p clas="no-file-msg">分享的文件已经删除</p>
                    </div>
                    {% endif %}

                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}

{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {#视频播放插件#}
    {{ javascript_include("3rdpart/jwplay/jwplayer.js") }}
<script type="text/javascript">
    /*下载分享文件*/
    var SHAREMODEL={
        downloadFiles:function(data,cb) {
            $.post('/frontend/file/downloadFiles', data, cb, 'json');
        },
    }
    //预览图片
    $("#js-down-file").on('click', function () {
        var file_ids=[];
        var fileId = $(this).data('id');
        file_ids.push(fileId);
        SHAREMODEL.downloadFiles({file_ids:file_ids},function(serverData){
            if(serverData.code === 0){
                var dlUrl = serverData.data;
                window.location.href = dlUrl;
            }
            else{
                layer.msg(serverData.msg);
            }
        });
    });
    /*下载分享文件*/
    if($("#file-video").length>0){
        jwplayer.key="O4G/7OoH6r9ioOg0VZQ1Ptmr+rAfP9BNQQzQYQ==";
        var fileName= $('.share-file-name').text();
        var fileId = $('.share-file-name').data('id');
        var fileNAmeArr = fileName.split('.');
        var fileFormat = fileNAmeArr[fileNAmeArr.length - 1].toLowerCase();
        /* 判断是不是收藏夹里面的 */
        var fUrl = config.rtmp + fileFormat.toLowerCase() + ':' + {{ fileInfo.uid }}+'/'+fileName;
        var coverSrc = '/api/source/getImageThumb/'+fileId+'/1000/800'
        var index=null;
        /*初始化视频播放*/
        if (fileFormat == 'mp3'){
                     $.post('/api/file/fileMp3Preview',{file_id:fileId},function(serverData){
                         fUrl = serverData.data.path;
                         var index=null;
                         playerInstance = jwplayer('file-video').setup({
                             flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                             file:fUrl,
                             image: '/img/frontend/tem_material/audio.png?v=1',
                             //file:'rtmp://123.59.146.75/vod/1.mp4',
                             width: '100%',
                             height:'100%',
                             //aspectratio:"4:3",
                             dock: false,
                             skin: {
                                 name: "vapor"
                             }
                         });
                     },'json');
                }
                else{
                playerInstance = jwplayer('file-video').setup({
                            flashplayer: '/3rdpart/jwplay/jwplayer.flash.swf',
                            file:fUrl,
                            image:coverSrc,
                            //file:'rtmp://123.59.146.75/vod/1.mp4',
                            width: '100%',
                            height:'100%',
                            //aspectratio:"4:3",
                            dock: false,
                            skin: {
                                name: "vapor"
                            }
                        });
                }
    };
    if($("#file-pdf").length>0){
       // var pdfUrl = config.url+"/3rdpart/generic/web/viewer.html?file=/api/file/filePreview/"+{{ fileInfo.id }};
        $.post('/api/file/filePreview/{{ fileInfo.id }}',{},function(data){
            if(data.code==0){
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
            }
            else{
                $('.js-ppt').html('<div class="text-center">'+data.msg+'</div>');
            }
        },'json');
        //$("#file-pdf").html("<iframe id='iframe-pdf' src="+pdfUrl+"></iframe>");
    }
</script>
{% endblock %}