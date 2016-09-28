{% extends "templates/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/article.css") }}
{% endblock %}
{% block content %}
    <div id="wraper">
        <div class="breadcrumbBar">
            <div class="container">
                <div class="breadcrumb"><a href="/">发现</a> > <a href="/live">直播课堂</a> > <span>{{liveInfo.name }}</span> </div>
            </div>
        </div>
        <div id="liveArticle" class="article container">
            {% if liveUserInfo['uid'] == userInfo.uid %}
            <div class="top text-right" style="padding:10px 0;"><a href="http://edu.iguangj.com:81/play/{{liveInfo.id}}" class="btn" target="_blank">导播管理</a></div>
            {% endif %}
            <div id="liveVideo" class="clearfix">
                <div class="player-box">
                    <object type="application/x-shockwave-flash" data="/js/frontend/live/previewVideo.swf" width="100%" height="530" id="j-flashArea-player" style="visibility: visible;"><param name="flashvars" value=""></object>
                </div>
                <div class="toolbars">
                    <a href="javascript:;" rel="nofollow" class="open-share js-open-share" ><span class="icon icon-share">生成分享链接</span></a>
                    <a href="javascript:;" rel="nofollow" class="open-chat js-open-chat" ><span class="icon icon-chat">聊天</span><span class="text js-text">展开</span></a>
                </div>
                <div class="videoDialog" id="js-dialog">
                    <div class="chat-discussion" id="js-chat-discussion">
                    </div>
                    <form class="input-wrap cl"  id="formChat" onsubmit="return false">
                        <input type="submit" value="" class="btn enter pull-right">
                        <a href="javascript:;" class="enter">发<br>送</a>
                        <textarea type="text" name="msg" class="msg js-msg" ></textarea>
                    </form>
                </div>
                <!-- 二维码 -->
                <div class="share-content clearfix js-share-content">
                    <a href="javascript:;" rel="nofollow" class="close-share js-close-share">x</a>

                    <img src="/api/source/getImageCode/{{liveInfo.id }}" alt="" class="pull-left img-rounded" width="170" height="170">
                    <p>用微信／微博扫描二维码，在手机上<br>继续观看该视频</p>
                </div>
            </div>
            <div class="cl aBody">
                <div class="content bm">
                    <div class="tabNav" id="js-live" data-id="{{liveInfo.id }}">
                        <ul class="cl">
                            <li class="on">简介</li>
                            <li>主讲介绍</li>
                            <li>关联资料</li>
                            <!--<li >评论</li>-->
                        </ul>
                    </div>
                    <div id="about" class="tabCon" style="display:block">{{liveInfo.content }}</div>
                    <div id="zhubo" class="tabCon">{{liveUserInfo['desc'] }}</div>
                    <div id="file" class="tabCon">
                        <div class="row">
                            {% for file in relation_file_list%}
                            <div class="col-sm-3">

                                <div class="file-item">
                                    <a href="/user/file/{{ file['id'] }}" target="_blank" class="file-item-link">
                                        {% if file['file_type'] == 2 %}
                                        <div class="file-item-icon file-item-icon-video">
                                        </div>
                                        {% endif %}
                                        {% if file['file_type'] == 3 %}
                                        <div class="file-item-icon file-item-icon-jpg"></div>
                                        {% endif %}
                                        {% if file['file_type'] == 4 %}
                                        <div class="file-item-icon file-item-icon-mp3"></div>
                                        {% endif %}
                                        {% if file['file_type'] == 5 %}
                                        <div class="file-item-icon file-item-icon-{{ file['ext'] }}"></div>
                                        {% endif %}
                                        {% if file['file_type'] == 6 %}
                                        <div class="file-item-icon file-item-icon-{{ file['ext'] }}"></div>
                                        {% endif %}
                                        <div class="file-item-name">{{ file['file_name'] }}</div>
                                        <div class="file-item-size">文件大小：{{ file['file_size'] }}</div>
                                        <!--<div class="file-item-download"><i class="file-item-icon-download"></i>下载：300</div>-->
                                    </a>
                                </div>

                            </div>
                            {% endfor %}
                        </div>
                    </div>
                    <!-- 简介 end -->
                    <!--<div id="comments" class="tabCon" style="display:block">-->
                        <!--<div id="cPost" class="cl">-->
                            <!--<form>-->
                                <!--<div class="input-wrap">-->
                                    <!--<textarea class="cText"></textarea>-->
                                <!--</div>-->
                                <!--<button class="cBtn">发表评论</button>-->
                            <!--</form>-->
                        <!--</div>-->
                        <!--<div class="cList">-->
                            <!--&lt;!&ndash; 评论循环 &ndash;&gt;-->
                            <!--<div class="cItem cl">-->
                                <!--<div class="faceBar fl"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>-->
                                <!--<div class="info">-->
                                    <!--<a href="#" class="name">用户姓名</a>-->
                                    <!--<p class="msg">用户评论信息</p>-->
                                    <!--<p class="time">时间：两天前</p>-->
                                    <!--<div class="operate"><a href="#" class="good"><i class="fa-good"></i>10</a></div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--&lt;!&ndash; 评论循环 end &ndash;&gt;-->
                            <!--&lt;!&ndash; 评论循环 &ndash;&gt;-->
                            <!--<div class="cItem cl">-->
                                <!--<div class="faceBar fl"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>-->
                                <!--<div class="info">-->
                                    <!--<a href="#" class="name">用户姓名</a>-->
                                    <!--<p class="msg">用户评论信息</p>-->
                                    <!--<p class="time">时间：两天前</p>-->
                                    <!--<div class="operate"><a href="#" class="good"><i class="fa-good"></i>10</a></div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--&lt;!&ndash; 评论循环 end &ndash;&gt;-->
                            <!--&lt;!&ndash; 评论循环 &ndash;&gt;-->
                            <!--<div class="cItem cl">-->
                                <!--<div class="faceBar fl"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>-->
                                <!--<div class="info">-->
                                    <!--<a href="#" class="name">用户姓名</a>-->
                                    <!--<p class="msg">用户评论信息</p>-->
                                    <!--<p class="time">时间：两天前</p>-->
                                    <!--<div class="operate"><a href="#" class="good"><i class="fa-good"></i>10</a></div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--&lt;!&ndash; 评论循环 end &ndash;&gt;-->
                        <!--</div>-->
                        <!--&lt;!&ndash; 评论列表 end &ndash;&gt;-->
                    <!--</div>-->
                    <!-- 评论 end -->

                </div>
            </div>
        </div>
    </div>
    <script id="tplChatDiscussion" type="text/html">
        <%if user.uid == msg.uid%>
        <div class="chat-message text-right">
            <a class="message-author" href="#"><%msg.fromName%></a>
            <p class="message-content"><%msg.content%></p>
        </div>
        <%else%>
        <div class="chat-message">
            <a class="message-author" href="#"><%msg.fromName%></a>
            <p class="message-content"><%msg.content%></p>
        </div>
        <%/if%>
    </script>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {{ javascript_include("3rdpart/slimscroll/jquery.slimscroll.min.js") }}
    {{ javascript_include("/js/frontend/live/swfobject/swfobject.js") }}
    <!--{{ javascript_include("/js/frontend/live/play.js") }}-->
    {{ javascript_include("/js/backend/socket.io.js") }}
    <script>
        //判断手机跳转
        var sUserAgent = navigator.userAgent.toLowerCase();
        var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
        var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
        var bIsMidp = sUserAgent.match(/midp/i) == "midp";
        var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
        var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
        var bIsAndroid = sUserAgent.match(/android/i) == "android";
        var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
        var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
        if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM ){
            window.location.href=config.url+'/live/wap/index/{{liveInfo.id }}';
        }
        var createStreamPlayer = function(){
            var xiSwfUrlStr = "/js/frontend/live/swfobject/expressInstall.swf";
            var flashvars = {
                "log": "all",
                "url": 'rtmp://{{live_config.host}}:{{live_config.port}}/{{live_config.app_name}}',
                "streamname":'{{stream_name}}',
                "buffer": 0.1
            };
            swfobject.embedSWF("/js/frontend/live/previewVideo.swf", "j-flashArea-player", "100%", "530", "10.1.0", xiSwfUrlStr, flashvars);
            $("#j-flashArea-player param").attr('value','log=all&url='+flashvars.url+'&streamname='+flashvars.streamname+'&buffer='+flashvars.buffer);
        };
        var roomId = '{{liveInfo.id }}';
        $(function () {
            var socket = io("http://edu.iguangj.com:8888");
            var userName = $('#js-user-name').text();
            var userData = {
                uid: {{ userInfo.uid }},                 //通信服务全局ID
                id: {{ userInfo.uid }},                  //用户id
                pname:userName,               //用户名
                role: "web-client",    //角色 tv-app,tv-app-chat,lx,web-client,daobo,board
                room: roomId         //房间号
            };
            console.log(userData);
            socket.emit('usersignin', userData);
            socket.on('userLoginSuccess', function(data){
                userData.uid = socket.id;
                socket.emit('getuserlist', roomId);
            });

            $("#formChat").on("submit", function(){
                if($.trim(this.msg.value)==''){
                    this.msg.focus();
                    return false;
                }
                var msgData = {'type':0, 'content':this.msg.value, 'to':''};
                socket.emit('sendchatmsg', msgData);
                this.reset();
                return false;
            });
            //回车聊天
            $('.js-msg').on('keypress',function(event){
                if(event.keyCode == "13")
                {
                    if($.trim($(this).val())==''){
                        $(this).focus();
                        return false;
                    }
                    var msgData = {'type':0, 'content':$(this).val(), 'to':''};
                    socket.emit('sendchatmsg', msgData);
                    $(this).val('');
                    return false;
                }
            });
            socket.on('getchatmsg', function(data){
                var iHeight = 0;
                $("#js-chat-discussion").append(template("tplChatDiscussion", {"user":userData, "msg":data}));
                $(".chat-message").each(function(){
                    var h=$(this).outerHeight(true);
                    iHeight+=h;
                    $('#js-chat-discussion').scrollTop(iHeight);
                });
            });
            createStreamPlayer();
        });
        //
        $(function () {
            $('#navbar-collapse-basic ul .live').addClass('active');
            $(".tabNav li").on("click",function(){
                $(this).addClass("on").siblings().removeClass("on");
                $(".tabCon").eq($(".tabNav li").index($(this))).show().siblings(".tabCon").hide();
            });
            $.post('/api/live/inLive',{live_id:{{liveInfo.id }}},function(){},'json');
        });
        $('#js-chat-discussion').slimscroll({
            height: '100%',
            size:'5px',
            color:'#ccc',
            distance:'5px',
        });
        $('.js-open-chat').off();
        $('.js-open-chat').on('click',function(){
            var iWidth = $("#js-dialog").outerWidth(true);
            if($(this).hasClass('open')){
                $(this).removeClass('open');
                $(this).find('.js-text').text('展开')
                $(this).parent().stop().animate({'right':'0'});
                $("#js-dialog").stop().animate({'right':-iWidth});

            }
            else{
                $(this).addClass('open');
                $(this).find('.js-text').text('收起')
                $(this).parent().stop().animate({'right':iWidth});
                $("#js-dialog").stop().animate({'right':0});
            }
        });
        $('.js-open-share').on('click',function(){
            $('.js-share-content').fadeIn();
        });
        $('.js-close-share').on('click',function(){
            $('.js-share-content').fadeOut();
        })
    </script>
{% endblock %}