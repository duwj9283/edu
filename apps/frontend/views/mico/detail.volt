{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/article.css") }}
{% endblock %}
{% block content %}
    <div id="wraper">
        <div class="breadcrumbBar">
            <div class="container">
                <div class="breadcrumb"><a href="/">发现</a> > <a href="/mico/publist">精品微课</a> > <span>{{lesson['title']}}</span> </div>
            </div>
        </div>
        <div id="weikeArticle" class="article container">
            <div class="aHead bm clearfix">
                <div class="thumb"><img src="/frontend/source/getFrontImageThumb/mlesson/{{lesson['id']}}/500/320" width="500" height="320" /></div>
                <div class="intr">
                    <div class="tit clearfix">
                        <!--<a href="javascript:;" class="good pull-right"><i class="fa-good"></i>点赞：78</a>-->
                        <h1 class="pull-left">{{lesson['title']}}</h1>
                    </div>
                    <ul>
                        <li>学科：{{father_subject}}</li>
                        <li>专业：{{child_subject}}</li>
                    </ul>
                    <!--<div class="tags clearfix"><span class="label">语言</span><span class="label">语言</span></div>-->
                    <div class="num"><i class="fa-u"></i>{{study_count}}人已看<i class="fa-c"></i>{{comment_count}}评论</div>
                    <div class="teacher">讲师：<a href="/user/zone/{{teacher['uid']}}" target="_blank">{{teacher['realname']}}</a></div>
                    <a href="{% if bSignedIn %}/mico/play/{{lesson['id']}}{%else%}javascript:;{%endif%}" class="playBtn js-play">参加学习</a> </div>
            </div>
            <div class="clearfix aBody">
                <div class="content bm">
                    <div class="tabNav">
                        <ul class="clearfix">
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
                                            {#<a class="reply" data-cmt-uid ={{ comment['uid'] }} data-cmt-id={{ comment['id'] }}>回复</a>#}
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
                    <!-- 相关资料 end -->
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}
<script type="text/html" id="comment-list">
    <% each courseComments as list %>
    <div class="cItem clearfix">
        <% each list as comment i %>
        <% if i === 0 %>
        <div class="faceBar pull-left">
            <img src="/frontend/source/getFrontImageThumb/header/<% comment.uid %>/34/34" width="34" height="34"
                 class="face"/>
        </div>
        <div class="info comment-row">
            <div class="t clearfix">
                <a href="#" class="name"><% comment.userName %></a>
            </div>
            <p class="msg"><% comment.content %></p>

            <div class="b clearfix">
                <p class="time"><% comment.create_time %></p>
            </div>
        </div>
        <% /if %>
        <% if i > 0 %>
        <div class="replyCon clearfix  comment-row"><span class="pull-left"><% comment.refUserName %>回复：</span>

            <div class="info"><a href="#" class="name"><% comment.userName %></a>

                <p class="msg"><% comment.content %></p>

                <p class="time"><% comment.create_time %></p>
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
    {{ javascript_include("js/frontend/mico/detail.js") }}
    {{ javascript_include("js/frontend/mico/detailModel.js") }}
    <script>
        $(function () {
            $(".tabNav li").on("click",function(){
                $(this).addClass("on").siblings().removeClass("on");
                $(".tabCon").eq($(".tabNav li").index($(this))).show().siblings(".tabCon").hide();
            });
        });
    </script>
{% endblock %}