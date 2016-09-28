{% extends "template/basic-v3.volt" %}
{% block pageCss %}
{{ stylesheet_link("/css/frontend/web/zone.css") }}
{% endblock %}
{% block content %}
<div class="content-wrap zone-wrap">
    <div class="container">
        <div class="zone-header clearfix">
            <img src="{% if UserInfo.headpic %}/frontend/source/getFrontImageThumb/header/{{ UserInfo.uid }}/147/147" {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}"   width="147" height="147" class="user-face" alt="">
            <div class="user-name pull-left"  id="js-person-name"
                 data-uid="{{ UserInfo.uid }}">{{ UserInfo.nick_name }}的主页</div>

            {% if follow == 0 %}
              <div class="follow btn pull-right"  id="js-follow-btn" status="{{ follow }}">＋  关注</div>
            {% elseif follow == 1 %}
               <div class="follow btn pull-right un-follow"  id="js-follow-btn" status="{{ follow }}">＋  取消关注</div>
            {% endif %}
            <div class="user-num pull-right"><span>{{ UserInfo.visited_count }}人访问</span><span><span class="js-follow-num">{{ userFollowCounter }}</span>人关注</span></div>

        </div>
        <div class="zone-content">
            <div class="title">
                <ul class="clearfix" id="js-zone-menu">
                    <li class="active" data-name="all">所有</li>
                    <li data-name="file">资源</li>
                    <li data-name="course">课程</li>
                    <li data-name="mico">微课</li>
                    <li data-name="live">直播</li>
                    <li data-name="news">文章</li>
                    <li data-name="dongtai">动态</li>
                </ul>
            </div>
            <div class="main clearfix">
                <div class="content"  style="display: none">
                    <div id="js-content">
                        <div class="text-center loading">加载中 ~</div>
                    </div>
                    <div class="page-wrap"></div>
                </div>
                <div class="content" id="js-all">
                    <h2>资源</h2>
                    <div class="file-list row">
                        {% if !data['fileList'] %}
                        <div class="nothing">没有相关内容</div>
                        {% endif %}
                        {% for list in data['fileList'] %}
                        <div class="col-sm-4">
                            <div class="item file-item">
                                <a href="javascript:;" rel="nofollow" class="js-file-url" data-url="/user/file/{{ list['id']}}" target="_blank">
                                    <div class="item-pic" >
                                        {% if list['file_type'] == 2 %}
                                        <div class="icon-play"></div>
                                        <img src="/api/source/getImageThumb/{{ list['id']}}/215/120" width="215" height="120" alt="">
                                        {% elseif list['file_type'] == 3 %}
                                        <img src="/api/source/getImageThumb/{{ list['id']}}/215/120" width="215" height="120" alt="">
                                        {% elseif list['file_type'] == 4 %}
                                        <div class="icon icon-mp3"></div>
                                        {% elseif list['file_type'] >= 5 %}
                                        <div class="icon icon-{{ list['ext']}}"></div>
                                        {% endif %}
                                    </div>
                                    <div class="item-tit ellipsis">
                                        {{ list['file_name']}}
                                    </div>
                                    <div class="item-b clearfix">
                                        <span class="size pull-right">{{ list['sizeConv']}}</span>
                                        <span class="down">下载：{{ list['download_count']}}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        {%endfor%}
                    </div>
                    <!-- 资源 end -->
                    <h2>课程</h2>
                    <div class="course-list row">
                        {% if !data['lessonList'] %}
                        <div class="nothing">没有相关内容</div>
                        {% endif %}
                        {% for index, list in data['lessonList'] %}
                        <div class="col-sm-4">
                            <div class="item course-item">
                                <a href="/course/detail/{{list['id']}}">
                                    <div  class="item-pic">
                                        <img src="/frontend/source/getFrontImageThumb/lesson/{{ list['id']}}/215/120" width="215" height="120" alt="">
                                    </div>
                                    <div class="item-tit clearfix">
                                        <span class="tit">{{ list['title']}}</span>
                                    </div>
                                    <div class="item-b clearfix">
                                        <span class="pull-right">{{ list['study_count']}}人已学</span>
                                        <span class="">{{ list['subject_name']}}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        {% endfor %}
                    </div>
                    <!-- 课程 end -->
                    <h2>微课</h2>
                    <div class="mico-list row">
                        {% if !data['mlessonList'] %}
                        <div class="nothing">没有相关内容</div>
                        {% endif %}
                        {% for index, list in data['mlessonList'] %}
                        <div class="col-sm-4">
                            <div class="item mico-item">
                                <a href="/mico/detail/{{list['id']}}">
                                    <div  class="item-pic">
                                        <i class="icon icon-play"></i>
                                        <img src="/frontend/source/getFrontImageThumb/mlesson/{{ list['id']}}/215/120" width="215" height="120"  alt="">
                                    </div>
                                    <div class="item-tit clearfix">
                                        <span class="subject pull-right"></span>
                                        <span class="tit">{{ list['title']}}</span>
                                    </div>
                                    <div class="item-b clearfix">
                                        <span class="pull-right">{{ list['studyCount']}}人已学</span>
                                        <span class="author">{{ list['subject_name']}}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        {% endfor %}
                    </div>
                    <!-- 微课 end-->
                    <h2>直播</h2>
                    <div class="live-list row">
                        {% if !data['liveList'] %}
                        <div class="nothing">没有相关内容</div>
                        {% endif %}
                        {% for index, list in data['liveList'] %}
                        <div class="col-sm-4">
                            <div class="item live-item">
                                <a href="/live/index/detail/{{ list['id']}}">
                                    <div  class="item-pic">
                                        {% if list['live_status'] == 0%}
                                        <i class="icon icon-wait"></i>
                                        {% elseif list['live_status'] == 1%}
                                        <i class="icon icon-ing"></i>
                                        {% elseif list['live_status'] == 2%}
                                        <i class="icon icon-end"></i>
                                        {% endif %}
                                        <img src="/frontend/source/getFrontImageThumb/live/{{ list['id']}}/215/120" width="215" height="120" alt="{{ list['name']}}">
                                    </div>
                                    <div class="item-tit ellipsis">
                                        {{ list['name']}}
                                    </div>
                                    <div class="item-b clearfix">
                                        <span class="time pull-right">{{ list['start_time']}} 开始</span>
                                        <span class="author"></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        {% endfor %}
                    </div>
                    <!-- 直播 end-->
                    <h2>文章</h2>
                    <div class="news-list">
                        <ul>
                            {% if !data['newsList'] %}
                            <div class="nothing">没有相关内容</div>
                            {% endif %}
                            {% for index, list in data['newsList'] %}
                            <li class="item news-item">
                                <h2><a href="/user/newsInfo/{{ list['id']}}">{{ list['title']}}({{ list['addtime']}})</a></h2>
                                <p><span>内容：</span>{{ list['content']}}</p>
                            </li>
                            {% endfor %}
                        </ul>
                    </div>
                    <!-- 文章 end-->

                </div>
                <div class="sidebar">
                    <div class="user-info">
                        <div class="top clearfix">
                            <img src="{% if UserInfo.headpic %}/frontend/source/getFrontImageThumb/header/{{ UserInfo.uid }}/95/95" {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}" width="95" height="95" class="pull-left" alt="">
                            <div class="info">
                                <h2>{{ UserInfo.nick_name }}的主页</h2>
                                <p>学科：{{ UserInfo.subject_name }}/{{ UserInfo.class }}</p>
                                <div class="clearfix">
<!--                                    <a href="#" class="btn-msg btn">给TA留言</a>-->
                                </div>
                            </div>
                        </div>
                        <dl>
                            <dt>主页介绍：</dt>
                            <dd>{{ UserInfo.desc }}</dd>
                            <dd>TA的资源：{{ data['fileCount']}}</dd>
                            <dd>TA的课程：{{ data['lessonCount']}}</dd>
                            <dd>TA的直播：{{ data['liveCount']}}</dd>
                            <dd>TA的微课：{{ data['mlessonCount']}}</dd>
                            <dd>TA的文章：{{ data['newsCount']}}</dd>
                        </dl>
                    </div>
                    <!-- 个人信息 end -->
                    <div class="dongtai" id="dongtai">
                        <h3>个人动态</h3>
                        {% if !data['dynamicList'] %}
                        <div class="nothing">没有相关内容</div>
                        {% endif %}
                        <ul class="js-dongtai">
                            {% for index,list in data['dynamicList']%}
                            <li class="js-item" data-id="{{ list['dynamic']['id']}}" >
                                <div class="row">
                                    <div class="col-sm-2">
                                        <img src="{% if UserInfo.headpic %}/frontend/source/getFrontImageThumb/header/{{ UserInfo.uid }}/32/32" {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}" class="face img-circle" width="32" height="32" alt="">
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="top">
                                            <span class="date">{{ list['dynamic']['addtime'] }}</span>
                                        </div>
                                        <div class="msg">
                                            {% if list['dynamic']['type'] ==1 or list['dynamic']['type'] ==2 or list['dynamic']['type'] == 3 %}
                                            <a href="/user/file/{{ list['dynamic']['addition'] }}" target="_blank">{{ list['dynamic']['content'] }}</a>
                                            {% endif %}
                                        </div>
                                        {% if bSignedIn %}
                                        <div class="bot text-right">
                                            <a href="javascript:;" rel="nofollow" class="reply js-show-reply-dt" data-uid="0" data-id="0">评论</a>
                                            {% if list['comment'] %}
                                            <a class="open js-open-c collapsed" href="#{{ list['dynamic']['id']}}" data-toggle="collapse"   data-parent="#dongtai" aria-expanded="true">展开 收起</a>
                                            {% endif %}
                                        </div>
                                        {% endif %}
                                        <ul class="collapse" id="{{ list['dynamic']['id']}}">
                                            {% for comments in list['comment'] %}
                                            {% for i,comment in comments %}
                                            {% if i == 0%}
                                            <li  data-id="{{comment['id']}}">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <img src="{% if comment['headpic'] %}/frontend/source/getFrontImageThumb/header/{{comment['uid']}}/32/32" {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}" class="face img-circle" width="32" height="32" alt="">
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="top">{{ comment['userName']}} 评论 {{ UserInfo.nick_name }}</div>
                                                        <div class="msg">
                                                            {{comment['content']}}
                                                        </div>
                                                        {% if bSignedIn %}
                                                        <div class="bot clearfix">
                                                            <a href="javascript:;" rel="nofollow" class="reply pull-right js-show-reply-dt" data-id="{{comment['id']}}" data-uid="{{comment['uid']}}">回复</a>
                                                            <span class="date">{{comment['date']}}</span>
                                                        </div>
                                                        {% endif %}
                                                    </div>
                                                </div>
                                            </li>
                                            {% else %}
                                            <li  data-id="{{comment['id']}}">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <img src="{% if comment['headpic'] %}/frontend/source/getFrontImageThumb/header/{{comment['uid']}}/32/32" {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}" class="face img-circle" width="32" height="32" alt="">
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="top">{{ comment['userName']}} 回复 {{ comment['refUserName']}}</div>
                                                        <div class="msg">
                                                            {{comment['content']}}
                                                        </div>
                                                        {% if bSignedIn %}
                                                        <div class="bot clearfix">
                                                            <a href="javascript:;" rel="nofollow" class="reply pull-right js-show-reply-dt" data-id="{{comment['id']}}" data-uid="{{comment['uid']}}">回复</a>
                                                            <span class="date">{{comment['date']}}</span>
                                                        </div>
                                                        {% endif %}
                                                    </div>
                                                </div>
                                            </li>
                                            {% endif %}
                                            {% endfor %}
                                            {% endfor %}
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            {% endfor %}
                        </ul>
                    </div>
                    <!-- 个人动态 end -->
                </div>
            </div>
        </div>
    </div>
</div>

<script id="zone-files" type="text/html">
    <div class="file-list row">
    <%each list as item%>
        <div class="col-sm-4">
            <div class="item file-item">
                <a href="javascript:;"  class="js-file-url" data-url="/user/file/<% item.id %>">
                    <div class="item-pic" >
                        <% if item.file_type === '2' %>
                        <img src="/api/source/getImageThumb/<% item.id %>/215/120" width="215" height="120">
                        <div class="icon-play"></div>
                        <% /if %>
                        <% if item.file_type === '3' %>
                        <img src="/api/source/getImageThumb/<% item.id %>/215/120" width="215" height="120">
                        <% /if %>
                        <% if item.file_type === '4' %>
                        <div class="icon icon-mp3"></div>
                        <% /if %>
                        <% if item.file_type === '5' %>
                        <div class="icon icon-<% item.file_name | setType:"other" %>"></div>
                        <% /if %>
                        <% if item.file_type === '6' %>
                        <div class="icon icon-<% item.file_name | setType:"other" %>"></div>
                        <% /if %>
                    </div>
                    <div class="item-tit ellipsis">
                        <% item.file_name %>
                    </div>
                    <div class="item-b clearfix">
                        <span class="size pull-right"><% item.sizeConv%></span>
                        <span class="down">下载：<% item.download_count%></span>
                    </div>
                </a>
            </div>
        </div>
    <%/each%>
    </div>
</script>
<script id="zone-dongtai" type="text/html">
    <div class="dongtai">
        <ul class="js-dongtai">
            <%each list as item index %>
            <li class="js-item" data-id="<% item.dynamic.id %>" >
                <div class="row">
                    <div class="col-sm-1">
                        <img src="<% if item.dynamic.uid %>/frontend/source/getFrontImageThumb/header/<%item.dynamic.uid%>/32/32" <% else %>/img/frontend/camtasiastudio/default-avatar.png<% /if %>" class="face img-circle" width="32" height="32" alt="">
                    </div>
                    <div class="col-sm-11">
                        <div class="top">
                            <span class="date"><%item.dynamic.addtime%></span>
                        </div>
                        <div class="msg">
                            <% if item.dynamic.type ==1 || item.dynamic.type ==2 || item.dynamic.type == 3 %>
                            <a href="/user/file/<% item.dynamic.addition %>" target="_blank"><% item.dynamic.content %></a>
                            <% /if %>
                        </div>
                        <div class="bot text-right">
                            <a href="javascript:;" rel="nofollow" class="reply js-show-reply-dt" data-uid="0" data-id="0">评论</a>
                        </div>
                        <ul>
                            <%each item.comment as comments%>
                            <%each comments as comment%>
                            <li  data-id="<%comment['id']%>">
                                <div class="row">
                                    <div class="col-sm-1">
                                        <img src="<% if comment.headpic %>/frontend/source/getFrontImageThumb/header/<%comment.uid%>/32/32" <% else %>/img/frontend/camtasiastudio/default-avatar.png<% /if %>" class="face img-circle" width="32" height="32" alt="">
                                    </div>
                                    <div class="col-sm-11">
                                        <div class="top"><% comment.userName%> 回复 <% comment.refUserName %></div>
                                        <div class="msg">
                                            <%comment.content%>
                                        </div>
                                        <div class="bot clearfix">
                                            <a href="javascript:;" rel="nofollow" class="reply pull-right js-show-reply-dt" data-id="<%comment.id%>" data-uid="<%comment.uid%>">回复</a>
                                            <span class="date"><% comment.date %></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <% /each %>
                            <% /each %>
                        </ul>
                    </div>
                </div>
            </li>
            <%/each%>
        </ul>
    </div>
</script>
<!--文章-->
<script id="zone-news" type="text/html">
    <div class="news-list">
        <ul>
            <%each list as item %>
            <% if item.status === '0' %>
            <li class="item news-item">
                <h2><a href="/user/newsInfo/<% item.id %>"><% item.title %>(<% item.addtime %>)</a></h2>
                <p><span>内容：</span><% item.content %></p>
            </li>
            <% /if %>
            <%/each%>
        </ul>
    </div>
</script>
<!--课程-->
<script id="zone-course" type="text/html">
    <div class="course-list row">
        <%each list as item %>
        <div class="col-sm-4">
            <div class="item course-item">
                <a href="/course/detail/<%item.id%>">
                    <div  class="item-pic">
                        <i class="icon icon-play"></i>
                        <img src="/frontend/source/getFrontImageThumb/lesson/<%item.id%>/215/120" width="215" height="120" alt="">
                    </div>
                    <div class="item-tit clearfix">
                        <span class="tit"><%item.title%></span>
                    </div>
                    <div class="item-b clearfix">
                        <span class="pull-right"><%item.study_count%>人已学</span>
                        <span class=""><%item.subject_name%></span>
                    </div>
                </a>
            </div>
        </div>
        <%/each%>
    </div>
</script>
<!--微课-->
<script id="zone-mico" type="text/html">
    <div class="mico-list row">
        <%each list as item %>
        <div class="col-sm-4">
            <div class="item mico-item">
                <a href="/mico/detail/<%item.id%>">
                    <div  class="item-pic">
                        <img src="/frontend/source/getFrontImageThumb/mlesson/<%item.id%>/215/120" width="215" height="120"  alt="">
                    </div>
                    <div class="item-tit clearfix">
                        <span class="subject pull-right"></span>
                        <span class="tit"><%item.title%></span>
                    </div>
                    <div class="item-b clearfix">
                        <span class="pull-right"><%item.study_count%>人已学</span>
                        <span class="author"><%item.subject_name%></span>
                    </div>
                </a>
            </div>
        </div>
        <%/each%>
    </div>
</script>
<script id="zone-live" type="text/html">
    <div class="live-list row">
        <%each list as item %>
        <div class="col-sm-4">
            <div class="item live-item">
                <a href="/live/index/detail/<%item.id%>">
                    <div  class="item-pic">
                        <% if item.live_status == 0 %>
                        <i class="icon icon-wait"></i>
                        <% /if %>
                        <% if item.live_status == 1 %>
                        <i class="icon icon-ing"></i>
                        <% /if %>
                        <% if item.live_status == 2 %>
                        <i class="icon icon-end"></i>
                        <% /if %>
                        <img src="/frontend/source/getFrontImageThumb/live/<%item.id%>/215/120" width="215" height="120" alt="<%item.name%>">
                    </div>
                    <div class="item-tit ellipsis">
                        <%item.name%>
                    </div>
                    <div class="item-b clearfix">
                        <span class="time pull-right"><% if item.live_status == 2 %> <del><%item.start_time%></del><% else %><%item.start_time%><% /if %>开始</span>
                        <span class="author"></span>
                    </div>
                </a>
            </div>
        </div>
        <%/each%>
    </div>
</script>
<script id="replay-dongtai" type="text/html">
    <div class="replay-box js-replay-box" id="js-replay-box-<% id %>">
        <textarea name="dt-msg"  class="form-control"  rows="2"></textarea>
        <a href="javascript:;" class="btn js-replay-dt">评论</a>
    </div>
</script>
<script id="comment-template" type="text/html">
    <li data-id="<%content.id%>">
        <div class="row">
            <div class="col-sm-2">
                <img src="<% if userInfo.headpic %>/frontend/source/getFrontImageThumb/header/<%userInfo.uid%>/32/32<% else %>/img/frontend/camtasiastudio/default-avatar.png<% /if %>" class="face img-circle" width="32" height="32" alt="">
            </div>
            <div class="col-sm-10">
                <div class="top"><% userInfo.nick_name %> 评论 <% refUserInfo.nick_name %></div>
                <div class="msg">
                    <%content.content%>
                </div>
                <div class="bot clearfix">
                    <a href="javascript:;" rel="nofollow" class="reply pull-right js-show-reply-dt" data-id="<% content.id%>" data-uid="<%userInfo.uid%>">回复</a>
                    <span class="date"><%content.create_time%></span>
                </div>
            </div>
        </div>
    </li>
</script>
{% endblock %}
{% block pageJs %}
    {#提示插件#}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("/js/frontend/basic.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {{ javascript_include("js/frontend/user/zoneModel.js") }}
    {{ javascript_include("js/frontend/user/zone.js") }}
{% endblock %}