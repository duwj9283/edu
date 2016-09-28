{% extends 'template/basic-v2.volt' %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/article.css") }}
{% endblock %}
{% block content %}
    <div id="wraper">
        <div class="breadcrumbBar">
            <div class="container">
                <div class="breadcrumb"> 首页 > <a href="/course/">课程</a> >
                    <a  href="/course/detail/{{ lesson['id'] }}">{{ lesson['title'] }}</a></div>
            </div>
        </div>
        <div id="classArticle" class="article container" data-id="{{ lesson_list_id }}" data-lessonid="{{ lesson['id'] }}">
            <div id="classVideo" class="video-wraper clearfix">
                <!--目录-->
                <div class="videoMenu pull-right">
                    <!--<div class="videoMenu-btn"></div>-->
                    <a href="javascript:;" class="menu open js-menu-btn">目录</a> <a href="javascript:void(0);" class="next next-video" >切换到下一节</a>

                    <div class="videoMenu-box">
                        <div class="tit"><h2>目录</h2></div>
                        <div class="videoMenu-list">
                            <div class="list">
                                {% for list in lesson_list %}

                                <h2>{{ list['lesson']['name'] }}</h2>
                                <ul class="videoMenu-list-ul">
                                    {% for child_list in list['child_list'] %}
                                    <li class="clearfix {% if child_list['id']==lesson_list_id %} current {% endif %}" data-id="{{ child_list['id'] }}"><span class="num pull-left">{{ child_list['sort'] }}</span><a href="#">{{ child_list['name'] }}</a>
                                    </li>
                                    {% endfor %}
                                </ul>
                                {% endfor %}

                            </div>
                        </div>
                    </div>

                </div>

                <!--目录-->
                <div class="video">
                    <div id="video-container"></div>
                </div>

                <div class="operate">
                    <a href="javascript:;" rel="nofollow" data-layer='{"type":"bj"}'  class="bj">笔<br/>记</a>
                    <a href="javascript:;" rel="nofollow" data-layer='{"type":"tw"}' class="tw">提<br/>问</a>
                    <a href="javascript:;" rel="nofollow" data-layer='{"type":"xt"}' class="xt">习<br/> 题</a>
                </div>


            </div>
            <div class="clearfix aBody">
                <div class="left">
                    <div class="content bm">
                        <div class="tabNav">
                            <ul class="clearfix">
                                <li class="on">简介</li>
                                <li data-name="comments">评论</li>
                                <li>问答</li>
                                <li>笔记</li>
                                <li data-name="file">关联资料</li>
                            </ul>
                        </div>
                        <div id="about" class="tabCon" style="display:block">{{ lesson['description'] }}</div>
                        <!-- 简介 end -->
                        <div id="comments" class="tabCon">
                            <div id="cPost" class="clearfix">
                                <form class="course-comment-form" onsubmit="return false;">
                                    <textarea class="input-wrap course-comment-input" required placeholder="请输入您的评论"></textarea>

                                    <button class="cBtn course-comment">发表评论</button>
                                </form>
                            </div>
                            <!-- 评论列表 start -->
                            <div class="cList" id="cmtList">
                                 {% for id,list in comment_list %}

                                <div class="cItem clearfix" data-id="{{ id }}">
                                    {% for i,comment in list  %}
                                        {% if comment['ref_id']==0 %}

                                            <div class="faceBar pull-left">
                                                <img src="/frontend/source/getFrontImageThumb/header/{{ comment['uid'] }}/34/34" width="34" height="34"
                                                     class="face"/>
                                            </div>
                                            <div class="info comment-row">
                                                <div class="t clearfix">
                                                    <a href="#" class="name">{{ comment['userName'] }}</a>
                                                </div>
                                                <div class="msg">{{ comment['content'] }}</div>

                                                <div class="b clearfix">
                                                    <div class="time">{{ comment['create_time'] }}</div>

                                                    <div class="operate">
                                                        <a href="javascript:;" rel="nofollow" class="reply" data-cmt-uid='{{ comment['uid'] }}' data-cmt-id='{{ comment['id'] }}'>回复</a>
                                                        {#<a href="javascript:;" rel="nofollow" class="good"><i class="fa-good"></i>0</a>#}
                                                    </div>
                                                </div>
                                            </div>
                                        {% elseif comment['ref_id']>0 %}
                                            <div class="replyCon cl  comment-row"><span class="pull-left">{{ comment['refUserName'] }}回复：</span>

                                                <div class="info"><a href="#" class="name">{{ comment['userName'] }}</a>

                                                    <div class="msg">{{ comment['content'] }}</div>

                                                    <div class="time">{{ comment['create_time'] }}</div>
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
                        <div id="question" class="tabCon">
                            {% for list in ask_list %}

                                <div class="item clearfix" data-uid="{{ list['ask']['uid'] }}" data-id="{{ list['ask']['id'] }}">
                                    <div class="faceBar pull-left">
                                        <img src="/frontend/source/getFrontImageThumb/header/{{ list['ask']['uid'] }}/34/34" width="34" height="34"  class="face mCS_img_loaded">
                                    </div>
                                    <div class="info">
                                        <div class="t clearfix"><a href="#" class="name">{{ list['user_info']['nick_name'] }}</a>{{ list['ask']['time_point'] }}</div>
                                        <p class="msg">{{ list['ask']['content'] }}</p>

                                        <p class="time">时间：{{ list['ask']['create_time'] }}</p>
                                        <!--回复信息-->
                                        {% if list['child'] %}
                                            {% for child in list['child'] %}
                                                <div class="replyCon clearfix"><span class="pull-left">回复：</span>
                                                    <div class="info"><a href="#" class="name">{{ child['user_info']['nick_name'] }}</a>
                                                        <p class="msg">{{ child['content'] }}</p>
                                                        <p class="time">时间：{{ child['create_time'] }}</p>
                                                    </div>
                                                </div>
                                            {% endfor %}
                                        {% endif %}
                                        <!--回复信息-->
                                    </div>
                                    <a href="javascript:;" class="reply btn bc3">回复</a>

                                    <div class="replayForm">
                                        <textarea placeholder="请输入您的回复内容" name="content"></textarea>
                                        <input class="btn bc3 ask-replay" value="回复">

                                    </div>
                                </div>
                            {% endfor %}

                        </div>
                        <!-- 问答 end -->
                        <div id="notes" class="tabCon">
                            {% for index,list in note_list %}
                            <div class="item clearfix">
                                <div class="row">
                                    <div class="num col-sm-1">{{index}}</div>
                                    <div class="col-sm-6">
                                        <p>{{ list['content'] }}</p>

                                        <p class="time c_5">时间：{{ list['addtime'] }}</p>
                                    </div>
                                    <div class="col-sm-5">第{{ list['lesson_list_info']['sort'] }}章 {{ list['lesson_list_info']['name'] }}</div>
                                </div>
                            </div>
                            {% endfor %}
                        </div>
                        <!-- 笔记 end-->
                        <div id="file" class="tabCon">
                            <ul class="clearfix">

                            </ul>
                        </div>
                        <!-- 相关资料 end -->
                    </div>
                </div>
                <!-- 左边 end -->
                <div class="sidebar">
                    <div class="userBlock block mb20 bm" id="study-list">
                        <div class="hd cl borc4"><span class="pull-right f18"><span class="num" >{{ study_count }}</span>学生</span>

                            <h2>正在学习</h2>
                        </div>
                        <div class="bd" >
                            <ul class="clearfix">
                                {% for list in study_users %}
                                <li class="user">
                                    <a href="/user/zone/{{ list.uid}}" target="_blank" class="face">
                                        <img src="/frontend/source/getFrontImageThumb/header/{{ list.uid }}/60/60"  class="img-circle" width="60" height="60"/>
                                    </a>
                                    <a href="/user/zone/{{ list.uid}}" target="_blank" >{{ list.nick_name}}</a>
                                </li>

                                {% endfor %}
                            </ul>
                        </div>
                    </div>
                    <!-- 学生 end -->

                </div>
                <!-- 右边 end -->
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}

    <script type="text/html" id="comment-detail">
        <% if comment.ref_id >0 %>

            <div class="replyCon cl  comment-row"><span class="pull-left"><% user.nick_name %>回复：</span>

                <div class="info"><a href="#" class="name"><% user.nick_name %></a>

                    <p class="msg"><% comment.content %></p>

                    <p class="time"><% comment.create_time %></p>
                </div>
            </div>

        <% else %>
            <div class="cItem clearfix" data-id="<%comment.id%>">
                <div class="faceBar pull-left">
                    <img src="/frontend/source/getFrontImageThumb/header/<% comment.uid %>/34/34" width="34" height="34"
                         class="face"/>
                </div>
                <div class="info comment-row">
                    <div class="t clearfix">
                        <a href="/user/zone/<% user.uid %>" class="name"><% user.nick_name %></a>
                    </div>
                    <p class="msg"><% comment.content %></p>

                    <div class="b clearfix">
                        <p class="time"><% comment.create_time %></p>

                        <div class="operate">
                            <a class="reply" data-cmt-uid='<% comment.uid %>' data-cmt-id='<% comment.id %>'>回复</a>
                            <a href="javascript:;" rel="nofollow" class="good"><i class="fa-good"></i>0</a>
                        </div>
                    </div>
                </div>

            </div>
        <% /if %>
    </script>


    <script type="text/html" id="ask-detail">
        <% if content.ref_id >0 %>
            <div class="replyCon clearfix"><span class="pull-left">回复：</span>
                <div class="info"><a href="#" class="name"><% user.nick_name %></a>
                    <p class="msg"><% content.content %></p>
                    <p class="time">时间：<% content.create_time %></p>
                </div>
            </div>
        <% else %>
        <div class="item clearfix"  data-id="<%content.id%>">
            <div class="faceBar pull-left">
                <img src="/frontend/source/getFrontImageThumb/header/<%content.uid%>/34/34" width="34" height="34"  class="face mCS_img_loaded">
            </div>
            <div class="info">
                <div class="t clearfix"><a href="#" class="name"><% user.nick_name %></a></div>
                <p class="msg"><%content.content%></p>

                <p class="time">时间：<%content.create_time%></p>

            </div>
            <a href="javascript:;" class="reply btn bc3">回复</a>

            <div class="replayForm">
                <textarea placeholder="请输入您的回复内容" name="content"></textarea>
                <input class="btn bc3 ask-replay" value="回复">
            </div>
        </div>

        <% /if %>
    </script>
    <script type="text/html" id="note-detail">
        <div class="item clearfix">
            <div class="row">
                <div class="num col-sm-1">1</div>
                <div class="col-sm-6">
                    <p><%content.content%></p>
                    <p class="time c_5">时间：<%content.addtime%></p>
                </div>
                <div class="col-sm-5">第<%info.sort%>章 <%info.name%></div>
            </div>
        </div>
    </script>
    <script type="text/html" id="file-list">
        <div class="row">
        <%each file_list as list %>
        <div class="col-sm-4">
            <div class="file-item">
                <a href="/user/file/<%list.id%>" target="_blank" class="file-item-link" title="<%list.file_name%>">
                    <% if list.file_type == 2 %>
                    <div class="file-item-icon file-item-icon-video"></div>
                    <% /if %>
                    <% if list.file_type == 3 %>
                    <div class="file-item-icon file-item-icon-jpg"></div>
                    <% /if %>
                    <% if list.file_type == 4 %>
                    <div class="file-item-icon file-item-icon-mp3"></div>
                    <% /if %>
                    <% if list.file_type == 5%>
                    <div class="file-item-icon file-item-icon-<% list.file_name | setType:'other' %>"></div>
                    <%/if%>
                    <div class="file-item-name"><%list.file_name%></div>
                    <div class="file-item-size">文件大小：<%list.file_size%></div>
                    <!--<div class="file-item-download"><i class="file-item-icon-download"></i>下载：300</div>-->
                </a>
            </div>
        </div>
        <%/each%>
        </div>
    </script>

    <script type="text/html" id="replayRowTem">
        <div class="replayForm replay-row-container clearfix">
            <textarea class="cText reply-comment-input" placeholder="请输入您的回复"></textarea>
            <a href="javascript:;" class="btn bc3 btn-reply-comment" data-cmt-uid='<% commentUId %>'
               data-cmt-id='<% commentId %>'>回复</a>
        </div>
    </script>

    <script id="exam-detail" type="text/html">
        <div class="con">
            <%if exam.type==1%>
                <div class="type typeJudge">判断题</div>
            <%else if exam.type==2%>
                <div class="type typeRadio">单选题</div>
            <%else if exam.type==3%>
                <div class="type typeCheck">多选题</div>
            <%/if%>

            <div class="tit clearfix"><span class="time pull-right"><%exam.addtime%></span>

                <h1 class="c_1"><%exam.title%></h1>
            </div>
            <p class="desc">
                <%exam.con%>
                <% if exam.img %>
                <div class="list-img">
                    <%each exam.img as item %>
                    <img src="<%item%>" width="500" height="">
                    <% /each %>
                </div>
                <% /if %>
            </p>

            <div class="clearfix">
                <div class="operate pull-right">
                    <input type="button" class="btn exam-btn" value="确认" <%if answer.myAnswer%> disabled <%/if%>>
                </div>
                <%if exam.type==1%>
                <!--判断题-->

                    <div class="ans clearfix">
                        <div class="labelBox judgeBox">
                            <%each exam.item as item%>
                            <label <%if answer.myAnswer && answer.myAnswer==item.title %> class="checked" <%/if%>><i class="fa-select"></i>

                                <input type="radio" name="RadioGroup5" value="<%item.title%>" >
                                <%item.title%></label>
                            <%/each%>

                        </div>
                    </div>

                <%else if exam.type==2%>
                <!--单选题-->
                <div class="ans clearfix">
                    <div class="labelBox selectBox">
                        <%each exam.item as item%>
                        <label <%if answer.myAnswer && answer.myAnswer==item.title %> class="checked" <%/if%>> <i class="fa-select"></i>
                            <input type="radio" name="CheckboxGroup1" value="<%item.title%>" checked="checked" id="CheckboxGroup1_0">
                            <%item.title%></label>

                        <%/each%>
                    </div>
                </div>
                <%else if exam.type==3%>
                <!--多项选择题-->

                <div class="ans pull-left">
                    <div class="labelBox judgeBox">
                        <%each exam.item as item%>
                        <label <%if answer.myAnswer && (item.title).indexOf(answer.myAnswer) > -1 %> class="checked" <%/if%>><i class="fa-judge"></i>
                            <input type="checkbox" name="RadioGroup5" value="<%item.title%>" id="RadioGroup5_0">
                            <%item.title%></label>
                        <%/each%>

                    </div>
                </div>
                <%/if%>
            </div>
        </div>
        <div class="msg clearfix" >
            <div class="next-part" <%if !answer.myAnswer%> style="visibility: hidden;" <%/if%>>
                <p class="answer"><%if answer.status==1%>恭喜你，答对啦<%else%>答错了,正确答案是：<%answer.myAnswer%><%/if%></p>
                <dl class="clearfix next-part" >
                    <dt>题目解析：</dt>
                    <dd><%answer.analysis%></dd>
                </dl>
            </div>

            <div class="operate pull-right " >
                <input  type="button" class="btn exam-next-btn " data-type="next" value="下一题" <%if !answer.myAnswer%> disabled <%/if%>>
            </div>

            <div class="operate pull-right before-part" style="visibility: hidden;">
                <input  type="button" class="btn exam-next-btn " data-type="before" value="上一题" >
            </div>
        </div>
    </script>
    <div id="ask" data-id="ask">
        <div class="content" style="padding:30px 20px 30px 30px;">
            <div class="hd clearfix"><a class="layui-layer-close close pull-right" href="javascript:;">x</a>

                <h2>提问</h2></div>
            <div class="bd">
                <form onsubmit="return false" name="lesson-form">
                    <input type="hidden" value="ask" name="type">
                    <textarea placeholder="请输入您的问题" required name="content" rows="7" class="textarea"></textarea>
                    <div class="clearfix">
                        <input type="submit" class="btn pull-right" value="发送">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="ask" data-id="note">
        <div class="content" style="padding:30px 20px 30px 30px;">
            <div class="hd clearfix">
                <a class="layui-layer-close close pull-right" href="javascript:;">x</a>
                <h2>笔记</h2>
            </div>
            <div class="bd">
                <form onsubmit="return false" name="lesson-form">
                    <input type="hidden" value="note" name="type">
                    <textarea placeholder="请输入笔记内容" required name="note-content" rows="7" class="textarea" id="note-content"></textarea>
                    <div class="row clearfix">
                        <input type="submit" class="btn pull-right" value="发送">

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="taskList">
        <div class="content" style="padding:30px 20px 30px 30px;">
            <div class="item bm">

            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include('js/frontend/less2/layer/layer.js') }}
    {#滚动条#}
    {{ javascript_include("3rdpart/slimscroll/jquery.slimscroll.min.js") }}
    {{ javascript_include("js/frontend/course/playModel.js") }}
    {{ javascript_include("js/frontend/course/play.js") }}
    {#滚动条#}
    {{ javascript_include("js/frontend/jquery.nicescroll.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {#视频播放插件#}
    {{ javascript_include("3rdpart/jwplay/jwplayer.js") }}
    {#百度编辑器#}
    {{ javascript_include("3rdpart/ueditor/ueditor.config.js") }}
    {{ javascript_include("3rdpart/ueditor/ueditor.all.js") }}
{% endblock %}