<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <title></title>
    <link href="/css/wap/video-js.css" rel="stylesheet">
    <style>
        *{ -webkit-box-sizing:border-box; -moz-box-sizing:border-box; box-sizing:border-box; }
        html, body { height: 100%; }
        body{ font:1.4rem/1.6 "Microsoft YaHei";}
        html { font-size: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100% }
        body, h1, h2, h3, h4, h5, h6, hr, p, blockquote, dl, dt, dd, ul, ol, li, pre, form, fieldset, legend, button, input, textarea, th, td { margin: 0; padding: 0 }
        ul, ol { list-style: none }
        .clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }
        .clearfix { zoom: 1; }
        .top-wraper{ position: absolute; top: 0; left: 0; right: 0; }
        .top-wraper .title{ height: 5rem; background: #f7f7f7; line-height: 5rem;  text-align: center; font-size: 1.4rem; color: #444; }
        .content-wraper{ position: absolute; left: 0; right: 0; bottom: 4rem; overflow-y: auto;}
        .tab-tit{ height: 4.2rem; line-height: 4.2rem;}
        .tab-tit li{ float: left; width: 50%; text-align: center; border-bottom: 1px solid #ddd;}
        .tab-tit li.active{ margin-top: -1px; border-bottom: 2px solid #db2033; color: #db2033; }
        .tab-con .box{ display: none;}
        /*聊天列表*/
        .msg-list{ padding:1rem; }
        .msg-list li{ padding-bottom:0.5rem; color: #444; }
        .msg-list .user-name{ color: #38f; }
        .msg-list .self .user-name{ color: #f00;}
        /*视频播放按钮*/
        .vjs-default-skin .vjs-big-play-button{ left: 50%; top: 50%; margin: -1.3em 0 0 -2em;}
        /* end */
        /*主播界面*/
        .user-content{ padding:1rem;}
        .user-content .user-img{ float: left; width: 4rem; height: 4rem; border-radius: 50%;}
        .user-content .user-info{ overflow: hidden; padding-left:1rem;}
        .user-content .user-name{ font-size: 1.6rem;}
        .user-content .user-about{color: #666;}
            /*底部聊天对话框*/
        .footer-wrap{ position: absolute; z-index: 9999; left: 0; bottom: 0; right: 0; height: 5rem; padding:0.5rem 6rem 0.5rem 0.5rem; background: #fff; border-top: 1px solid #ddd;}
        .footer-wrap input{width: 100%; height: 100%; padding:0.3rem; border:1px solid #eee; font-size: 1.4rem;}
        .footer-wrap .btn{ position: absolute; display: block; width: 6rem; right:0.5rem; top: 0.5rem; bottom: 0.5rem; background: #126472; text-align: center; color: #fff; line-height: 4rem; text-decoration: none;}
        /*end*/
        .pager{ position: relative; width: 100%; height: 100%; min-width: 320px; max-width: 640px; margin: 0 auto; overflow: hidden;}
        @media only screen and (max-width: 600px) {
            .pager { max-width: 600px; }
            html { font-size: 100%; }
        }

        @media only screen and (max-width: 480px) {
            .pager { max-width: 480px; }
            html { font-size: 75%; }
        }

        @media only screen and (max-width: 360px) {
            .pager { max-width: 360px; }
            html { font-size: 62.5%; }
        }
    </style>
</head>

<body>
<div class="pager">
    <div class="top-wraper">
        <h2 class="title">{{liveInfo.name }}</h2>
        <div class="content">
            <video id=example-video  class="video-js vjs-default-skin"  controls webkit-playsinline>
                <source src="http://{{live_config.host}}/hls/{{stream_name}}.m3u8"
                        type="application/x-mpegURL">
            </video>
            <div class="tab-tit">
                <ul class="clearfix">
                    <li class="active">互动聊天</li>
                    <li>主播信息</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="content-wraper">
        <div class="tab-con">
            <div class="box" id="scroll-msg" style="display: block;">
                <ul class="msg-list" id='msg-list'>

                </ul>
            </div>
            <div class="box">
                <div class="user-content clearfix">
                    <img src="{% if liveUserInfo.headpic %}/frontend/source/getFrontImageThumb/header/{{liveUserInfo.uid}}/40/40 {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}"  class="user-img">
                    <div class="user-info">
                        <h2 class="user-name">{{liveUserInfo.nick_name}}</h2>
                        <div class="user-about">{{liveUserInfo.desc }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-wrap">
        <input type="text" id='msg-text'>
        <a href="javascript:;" class="btn" id="btnSend">发送</a>
    </div>
    {% if bSignedIn %}
    <input type="hidden" value="{% if userInfo.uid %}{{userInfo.uid}}{% endif%}" name="user_uid" id="">
    <input type="hidden" value="{% if userInfo.nick_name %}{{userInfo.nick_name}}{% endif%}" name="user_name">
    {%endif%}
</div>
<script id='msg-template' type="text/template">
    <li class="<%= (record.uid=='self'?'self':'') %>" msg-content='<%=(record.content)%>'><span class="user-name"><%=(record.fromName)%>：</span><%=(record.content|| '&nbsp;&nbsp;') %></li>
</script>


    {{ javascript_include("3rdpart/jquery/jquery.min.js") }}
    {{ javascript_include("/js/backend/socket.io.js") }}
    {{ javascript_include("/js/wap/arttmpl.js") }}
    {{ javascript_include("/js/wap/core.js") }}
<script src="/js/wap/video.js"></script>
    <script src="/js/wap/videojs-media-sources.js"></script>
    <script src="/js/wap/videojs.hls.min.js"></script>
<script>
    var player = videojs('example-video');
    var client= new Client();
    var user_uid = $('input[name="user_name"]').size() ? $('input[name="user_uid"]').val():'';
    var user_name = $('input[name="user_name"]').size() ?$('input[name="user_name"]').val():'游客';
    console.log(user_name);
    /**
     *获取消息
     */
    function getMessage(data){
        data.uid=data.uid==socket.id?"self":data.uid;
        var html =template('msg-template', {
            "record": data
        });
        var areaMsgList= $("#msg-list");
        areaMsgList.append(html);
        //areaMsgList[0].scrollTop = areaMsgList[0].scrollHeight + areaMsgList[0].offsetHeight;
        var iHeight = 0;
        areaMsgList.find('li').each(function(){
            var h=$(this).outerHeight(true);
            iHeight+=h;

        });
        $('.content-wraper').scrollTop(iHeight);
    }
    $(function () {
        template.config('escape', false);
        console.log(client);

        //var args = Url.getArgs();
        var result = client.user.init(user_uid,user_name,{{liveInfo.id }});
        if(!result){
            return;
        }
        client.init(getMessage);
        $("#btnSend").click(function(){
            var textarea = $("#msg-text");
            if($.trim(textarea.val()) == ''){
                return false;
            }
            client.sendChatMsg($.trim(textarea.val()));
            textarea.val("");
        });
//
    setVideoSize();
    function setVideoSize(){
        var videoWidth=$('.pager').width();
        var videoHeight=videoWidth*9/16;
        $('.video-js').css({'width':videoWidth,'height':videoHeight});
        $('.content-wraper').css({'top':$('.top-wraper').outerHeight()})
        $('.tab-tit li').on('click',function(){
            $(this).addClass('active').siblings().removeClass('active');
            $('.tab-con .box').eq($(this).index()).show().siblings().hide();
        });
    };
    $(window).resize(function(){
        setVideoSize();
    });
    });
</script>
</body>

</html>