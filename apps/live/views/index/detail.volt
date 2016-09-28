{% extends 'templates/basic-v2.volt' %}
{% block pageCss %}
{{ stylesheet_link("css/frontend/less2/article.css") }}
{% endblock %}
{% block content %}
<div id="wraper">
    <div class="breadcrumbBar">
        <div class="container">
            <div class="breadcrumb"><a href="/">发现</a> > <a href="/live">直播课堂</a> > <span>{{liveInfo.name }}</span></div>
        </div>
    </div>
    <div id="liveArticle" class="article container">
        <div class="aHead bm clearfix">
            <div class="thumb">
                {% if liveInfo.status == 2 %}
                <i class="icon icon-end"></i>
                {% endif %}
                <img src="/frontend/source/getFrontImageThumb/live/{{liveInfo.id}}/500/320" width="500"
                                    height="320"/></div>
            <div class="intr">
                <div class="tit">
                    <h1 data-id="{{liveInfo.id }}" id="js-live-name">{{liveInfo.name }}</h1>
                    <!--<a href="#" class="collect fl"><i class="fa-collect"></i>收藏</a>-->
                </div>
                <!--<div class="star">-->
                <!--<div class="star-on star" style="width:20px;"></div>-->
                <!--</div>-->
                <!--<div class="tags cl"><span class="label">语言</span><span class="label">语言</span></div>-->
                <ul>
                    <li>学科：{{subject_father }}</li>
                    <li>专业：{{subject_child}}</li>
                    <li>在线人数：{{counter}}人</li>
                </ul>
                <!--<div class="num"><i class="fa-u"></i>19078正在观看</div>-->
                <div class="teacher" {{liveInfo.status}}>
                    <p>讲师：<a href="/user/zone/{{liveUser.uid}}" target="_blank">{{liveUser.nick_name}}</a></p>
                </div>
                {% if liveInfo.status == 0 %}
                <a href="javascript:;" class="playBtn" style="background: red;" id="js-play-live">等待直播</a>
                {% endif %}
                {% if liveInfo.status == 1 %}
                <a href="{% if bSignedIn %}/live/index/play/{{liveInfo.id}}{%else%}javascript:;{%endif%}" class="playBtn" id="js-play-live">进入直播</a>
                {% endif %}
                {% if liveInfo.status == 2 %}
                    {% if liveInfo.video_path !='' %}
                    <a href="{% if bSignedIn %}/live/index/lookback/{{liveInfo.id}}{%else%}javascript:;{%endif%}" class="playBtn" style="" id="js-play-live">播放录像</a>
                    {%else%}
                    <a href="javascript:;" class="playBtn" style="background: #ccc;" >直播结束</a>
                    {%endif%}
                {% endif %}
            </div>
        </div>
        <div class="clearfix aBody">
            <div class="content bm">
                <div class="tabNav">
                    <ul class="clearfix">
                        <li class="on">简介</li>
                        <li>主讲介绍</li>
                        <li>关联资料</li>
                    </ul>
                </div>
                <div id="about" class="tabCon" style="display:block">{{liveInfo.content }}</div>
                <div class="tabCon">{{liveUser.desc }}</div>
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
                <!--<div id="comments" class="tabCon">-->
                <!--<div class="cList">-->
                <!--&lt;!&ndash; 评论循环 &ndash;&gt;-->
                <!--<div class="cItem clearfix">-->
                <!--<div class="faceBar pull-left"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>-->
                <!--<div class="info">-->
                <!--<a href="#" class="name">用户姓名</a>-->
                <!--<p class="msg">用户评论信息</p>-->
                <!--<p class="time">时间：两天前</p>-->
                <!--<div class="operate"><a href="#" class="good"><i class="fa-good"></i>10</a></div>-->
                <!--</div>-->
                <!--</div>-->
                <!--&lt;!&ndash; 评论循环 end &ndash;&gt;-->
                <!--&lt;!&ndash; 评论循环 &ndash;&gt;-->
                <!--<div class="cItem clearfix">-->
                <!--<div class="faceBar pull-left"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>-->
                <!--<div class="info">-->
                <!--<a href="#" class="name">用户姓名</a>-->
                <!--<p class="msg">用户评论信息</p>-->
                <!--<p class="time">时间：两天前</p>-->
                <!--<div class="operate"><a href="#" class="good"><i class="fa-good"></i>10</a></div>-->
                <!--</div>-->
                <!--</div>-->
                <!--&lt;!&ndash; 评论循环 end &ndash;&gt;-->
                <!--&lt;!&ndash; 评论循环 &ndash;&gt;-->
                <!--<div class="cItem clearfix">-->
                <!--<div class="faceBar pull-left"><img src="/img/frontend/less2/user.png" width="34" height="34" class="face" /></div>-->
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
                <!--<div id="file" class="tabCon">-->
                <!--<ul>-->
                <!--<li><a href="#">文件名称.doc</a></li>-->
                <!--<li><a href="#">文件名称.doc</a></li>-->
                <!--<li><a href="#">文件名称.doc</a></li>-->
                <!--<li><a href="#">文件名称.doc</a></li>-->
                <!--<li><a href="#">文件名称.doc</a></li>-->
                <!--<li><a href="#">文件名称.doc</a></li>-->
                <!--</ul>-->
                <!--</div>-->
                <!-- 相关资料 end -->
            </div>
            <!-- 左边 end -->
            <!--<div class="sidebar">-->
            <!--<div class="teacherBlock block mb20 bm">-->
            <!--<div class="hd">-->
            <!--<h2>老师介绍</h2>-->
            <!--</div>-->
            <!--<div class="bd">-->
            <!--<div class="teacher">讲师：<img src="/img/frontend/less2/user.png" width="20" height="20" />冰雪奇观大PK</div>-->
            <!--<div class="teacherAbout">中学在职老师，致力于推广优质教育资源，帮助更多没有...</div>-->
            <!--</div>-->
            <!--</div>-->
            <!--&lt;!&ndash; 讲师 end &ndash;&gt;-->
            <!--&lt;!&ndash;<div class="classBlock block mb20 bm">&ndash;&gt;-->
            <!--&lt;!&ndash;<div class="hd">&ndash;&gt;-->
            <!--&lt;!&ndash;<h2>即将开始课程</h2>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash;<div class="bd">&ndash;&gt;-->
            <!--&lt;!&ndash;<div class="item"> <a href="#" class="thumb"><img src="/img/frontend/less2/pic.jpg" width="228" height="180" alt="" /> <i class="wait"></i> </a>&ndash;&gt;-->
            <!--&lt;!&ndash;<div class="info">&ndash;&gt;-->
            <!--&lt;!&ndash;<a href="#" class="tit">和外教一起学英语</a>&ndash;&gt;-->
            <!--&lt;!&ndash;<p class="cl"><span class="by">BY 在线教育</span><span class="num fr">10000人已学</span></p>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash;&lt;!&ndash;循环体 item end &ndash;&gt;&ndash;&gt;-->
            <!--&lt;!&ndash;<div class="item"> <a href="#" class="thumb"><img src="/img/frontend/less2/pic.jpg" width="228" height="180" alt="" /> <i class="wait"></i> </a>&ndash;&gt;-->
            <!--&lt;!&ndash;<div class="info">&ndash;&gt;-->
            <!--&lt;!&ndash;<a href="#" class="tit">和外教一起学英语</a>&ndash;&gt;-->
            <!--&lt;!&ndash;<p class="cl"><span class="by">BY 在线教育</span><span class="num fr">10000人已学</span></p>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash;&lt;!&ndash;循环体 item end &ndash;&gt;&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash;</div>&ndash;&gt;-->
            <!--&lt;!&ndash; 即将开始课程 end &ndash;&gt;-->
            <!--</div>-->
            <!-- 右边 end -->
        </div>
    </div>
</div>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs%}
{{ javascript_include("/js/frontend/basic.js") }}
{{ javascript_include("/3rdpart/template/template.js") }}
{{ javascript_include("/js/frontend/live/liveDetail.js") }}
<script>
    $('#navbar-collapse-basic ul .live').addClass('active');
    $(".tabNav li").on("click", function () {
        $(this).addClass("on").siblings().removeClass("on");
        $(".tabCon").eq($(".tabNav li").index($(this))).show().siblings(".tabCon").hide();
    });
</script>
{% endblock %}