{% extends 'template/basic-v2.volt' %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/article.css") }}
{% endblock %}
{% block content %}
    <input name="lesson_id" type="hidden" value="{{lesson['id']}}">
    <input name="file_id" type="hidden" value="{{lesson['file']}}">
<input name="path" type="hidden" value="{{path}}">
<input name="ext" type="hidden" value="{{ext}}">
    <div id="wraper">
        <div class="breadcrumbBar">
            <div class="container">
                <div class="breadcrumb"><a href="/">发现</a> > <a href="/mico/publist">精品微课</a> > <a href="/mico/detail/{{lesson['id']}}">{{lesson['title']}}</a></div>
            </div>
        </div>
        <div id="weikeArticle" class="article container">
            <div id="wkVideo">
                <div id="file-video"></div>
            </div>
            <!--<div class="operate clearfix">-->
                <!--<a class="share"><i class="icon icon-share"></i>分享</a>-->
                <!--<a class="down"><i class="icon icon-down"></i>下载</a>-->
                <!--<a class="collect"><i class="icon icon-collect"></i>收藏</a>-->
                <!--<a class="like"><i class="icon icon-like"></i>点赞</a>-->
            <!--</div>-->
            <div class="cl aBody">
                <div class="left">
                    <div class="content bm">
                        <div class="tabNav">
                            <ul class="cl">
                                <li class="on">简介</li>
                                <li>评论</li>
                                <li>关联资料</li>
                            </ul>
                        </div>
                        <div id="about" class="tabCon" style="display:block">
                            {{lesson['desc']}}
                        </div>
                        <!-- 简介 end -->

                        <div id="comments" class="tabCon">
                            <div id="cPost" class="clearfix">
                                <form class="course-comment-form" onsubmit="return false;">
                                    <textarea class="input-wrap course-comment-input" required placeholder="请输入您的评论"></textarea>
                                    <button class="cBtn course-comment">发表评论</button>
                                </form>
                            </div>
                            <div class="cList">
                                {% for comments in comment_list %}
                                <div class="cItem clearfix">
                                    {% for index,comment in comments %}
                                    {% if index === 0 %}
                                    <div class="faceBar pull-left">
                                        <img src="/frontend/source/getFrontImageThumb/header/{{ comment['uid']}}/34/34" width="34" height="34" class="face" />
                                    </div>
                                    <div class="info comment-row">
                                        <div class="t clearfix">
                                            <a href="/user/zone/{{ comment['uid']}}" target="_blank" class="name">{{ comment['userName'] }}</a>
                                        </div>
                                        <p class="msg">{{ comment['content'] }}</p>
                                        <div class="b clearfix">
                                            <p class="time">{{ comment['create_time'] }}</p>
                                            <div class="operate">
                                                <a href="javascript:;" rel="nofollow" class="reply" data-cmt-uid ={{ comment['uid'] }} data-cmt-id={{ comment['id'] }}>回复</a>
                                                <!--<a href="#" class="good"><i class="fa-good"></i>0</a>-->
                                            </div>
                                        </div>

                                    </div>
                                    {% endif %}
                                    {% if index > 0 %}
                                    <div class="replyCon cl comment-row">
                                        <span>{{ comment['userName'] }}</span>
                                        <span>回复：</span>
                                        <span>{{ comment['refUserName'] }}</span>
                                        <div class="info">
                                            <p class="msg">{{ comment['content'] }}</p>
                                            <p class="time">{{ comment['create_time'] }}</p>
                                        </div>
                                    </div>
                                    {% endif %}
                                    {% endfor %}
                                </div>
                                {% endfor %}


                            </div>
                            <!-- 评论列表 end -->
                        </div>
                        <!-- 评论 end -->
                        <div id="file" class="tabCon">
                            <div class="row">
                                {% for file in relation_file_list%}
                                <div class="col-sm-4">
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
                        <!-- 相关资料 end -->
                    </div>
                </div>
                <!-- 左边 end -->
                <div class="sidebar">
                    <div class="teacherBlock block mb20 bm">
                        <div class="hd">
                            <h2>老师介绍</h2>
                        </div>
                        <div class="bd">
                            <div class="teacher">讲师：<img src="{% if teacher['headpic']%}/frontend/source/getFrontImageThumb/header/{{teacher['uid']}}/20/20 {% else%}/img/frontend/camtasiastudio/default-avatar-small.png{%endif%}" width="20" height="20" /><a
                                    href="/user/zone/{{teacher['uid']}}">{{teacher['realname']}}</a></div>
                            <div class="teacherAbout">{{teacher['desc']}}</div>
                        </div>
                    </div>
                    <!-- 讲师 end -->
                    <!--<div class="classBlock block mb20 bm">-->
                        <!--<div class="hd">-->
                            <!--<h2>同类微课</h2>-->
                        <!--</div>-->
                        <!--<div class="bd">-->
                            <!--<div class="item"> <a href="#" class="thumb"><img src="/img/frontend/less2/pic.jpg" width="228" height="180" alt="" />  <span class="fa-play"></span> </a>-->
                                <!--<div class="info">-->
                                    <!--<a href="#" class="tit">和外教一起学英语</a>-->
                                    <!--<p class="cl"><span class="by">BY 在线教育</span><span class="num fr">10000人已学</span></p>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--&lt;!&ndash;循环体 item end &ndash;&gt;-->
                            <!--<div class="item"> <a href="#" class="thumb"><img src="/img/frontend/less2/pic.jpg" width="228" height="180" alt="" /> <span class="fa-play"></span> </a>-->
                                <!--<div class="info">-->
                                    <!--<a href="#" class="tit">和外教一起学英语</a>-->
                                    <!--<p class="cl"><span class="by">BY 在线教育</span><span class="num fr">10000人已学</span></p>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--&lt;!&ndash;循环体 item end &ndash;&gt;-->
                        <!--</div>-->
                    <!--</div>-->
                    <!-- 同类微课 end -->
                </div>
                <!-- 右边 end -->
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}
<script type="text/html" id="replay-row-container">
    <div class="replayForm replay-row-container">
        <textarea class="cText reply-comment-input"></textarea>
        <a href="javascript:;" rel="nofollow" class="btn btn-reply-comment pull-right" data-cmt-uid ='<% commentUId %>' data-cmt-id='<% commentId %>'>回复</a>
    </div>
</script>
<script type="text/html" id="comment-list">
    <% each fileComments as fileComment %>
    <div class="cItem clearfix">
        <% each fileComment as comment i %>
        <% if i === 0 %>
        <div class="faceBar pull-left">
            <img src="/frontend/source/getFrontImageThumb/header/<% comment.uid %>/34/34" width="34" height="34" class="face" />
        </div>
        <div class="info comment-row">
            <div class="t clearfix">
                <a href="#" class="name"><% comment.userName %></a>
            </div>
            <p class="msg"><% comment.content %></p>
            <div class="b clearfix">
                <p class="time"><% comment.date %></p>
                <div class="operate">
                    <a class="reply" data-cmt-uid ='<% comment.uid %>' data-cmt-id='<% comment.id %>'>回复</a>
                    <a href="#" class="good"><i class="fa-good"></i>0</a>
                </div>
            </div>
        </div>
        <% /if %>
        <% if i > 0 %>
        <div class="replyCon clearfix comment-row">
            <span><% comment.userName %></span>
            <span>回复：</span>
            <span><% comment.refUserName %></span>
            <div class="info">
                <p class="msg"><% comment.content %></p>
                <p class="time"><% comment.date %></p>
            </div>
        </div>
        <% /if %>
        <% /each %>
    </div>
    <% /each %>
</script>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("3rdpart/template/template.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("3rdpart/jwplay/jwplayer.js") }}
    {{ javascript_include("js/frontend/mico/play.js") }}
    {{ javascript_include("js/frontend/mico/playModel.js") }}
    <script type="text/javascript">
        $(function () {
            $('#navbar-collapse-basic ul .camtasiastudio').addClass('active');
            $(".tabNav li").on("click",function(){
                $(this).addClass("on").siblings().removeClass("on");
                $(".tabCon").eq($(".tabNav li").index($(this))).show().siblings(".tabCon").hide();
            });
        });
    </script>
{% endblock %}