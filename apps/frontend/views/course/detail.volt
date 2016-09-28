{% extends 'template/basic-v2.volt' %}
{% block pageCss %}
{{ stylesheet_link("css/frontend/less2/article.css") }}
{% endblock %}
{% block content %}
<div id="wraper">
    <div class="breadcrumbBar">
        <div class="container">
            <div class="breadcrumb"><a href="/">发现</a> > <a href="/course">在线课程</a> > <span>{{ lesson.title }}</span>
            </div>
        </div>
    </div>
    <div id="classArticle" class="article container  " data-id="{{ lesson.id }}">
        <div class="aHead bm cl">
            <div class="thumb">
                <img src="/frontend/source/getFrontImageThumb/lesson/{{ lesson.id }}/550/260"
                     width="500" height="320"/></div>
            <div class="intr">
                <div class="tit clearfix">
<!--                    <a href="#" class="good pull-right"><i class="fa-good"></i>点赞：78</a>-->
                    <h1 class="pull-left">{{ lesson.title }}</h1>
<!--                    <span class="price f18 pull-left c_6 ml40">{% if lesson.price==0%}免费{% else %}¥{{ lesson.price }}{% endif %}</span>-->
                </div>
                <p class="time f16">课程时长：{{timeLone}}</p>
                <dl class="desc f16">
                    <dt class="pull-left">课程描述：</dt>
                    <dd>{{ lesson.desc }}</dd>
                </dl>
<!--                <div class="tags clearfix">-->
<!--                    {% for label in lesson.label %}-->
<!--                    <span class="label">{{ label }}</span>-->
<!--                    {% endfor %}-->
<!--                </div>-->
                <div class="num"><i class="fa-u"></i><span id="study_count">{{ lessonStudyCount }}</span>人观看<i
                        class="fa-c"></i><span id="comment_count"></span> 评论
                </div>
                <div class="teacher">
                    讲师：<a href="/user/zone/{{ teacherInfo.uid }}" target="_blank">{{ teacherInfo.nick_name }}</a>
                </div>
                {% if teacherInfo.uid != userInfo.uid %}
                {% if study_status==0 %}
                <a href="javascript:void(0);" class="playBtn bc3 js-learn ">参加学习</a></div>
            {% elseif study_status==1%}
            <a href="/course/play/{{ study_last }}" class="playBtn bc3">继续学习</a></div>
        {% elseif study_status==2%}
        <a href="javascript:void(0);" class="playBtn bc3 disabeld">学习完成</a></div>
    {% endif %}
    {% endif%}
</div>
<div class="clearfix aBody">
    <div class="left">
        <div class="content bm">
            <div class="tabNav">
                <ul class="clearfix">
                    <li class="on">课程介绍</li>
                    <li data-name="catalog" class="cata-list">课程章节</li>
                    <li data-name="comments">评论</li>
                    <li data-name="file">关联资料</li>
                </ul>
            </div>
            <div id="about" class="tabCon" style="display:block">{{ lesson.description }}</div>
            <!-- 简介 end -->
            <div id="catalog" class="tabCon">
                <div class="list">
                    {% for list in lessonLists %}
                    <h3>{{ list['lesson']['name'] }}</h3>
                    <ul class="js-course-section">
                        {% for child_list in list['child_list'] %}
                        <li>
                            <div class="box clearfix">
                                <a href="{% if bSignedIn %}/course/play/{{ child_list['id'] }}{%else%}javascript:;{%endif%}">课时{{ child_list['sort'] }}
                                    {{ child_list['name'] }}</a>
                                <div class="type"><i class="fa-a"></i><i class="fa-b"></i><i class="fa-c"></i></div>
                            </div>
                        </li>
                        {% endfor %}
                    </ul>
                    {% endfor %}
                </div>
            </div>
            <!-- 目录 end -->
            <div id="comments" class="tabCon">
                <!-- 评论列表 start -->
                <div class="cList" id="cmtList">
                </div>
                <!-- 评论列表 end -->
            </div>
            <!-- 评论 end -->
            <div id="file" class="tabCon">
                {% for index,list in relationFileList%}
                <div class="panel">
                <h4><a data-toggle="collapse" class="catalog-name collapsed clearfix" data-parent="#file" href="#{{list['index']}}{{list['child_index']}}" aria-expanded="true"><div class="pull-right icon-arrow"></div>第{{list['index']}}章课时{{list['child_index']}}：{{list['father_name']}} - {{list['child_name']}}</a></h4>
                <div class="row collapse" id="{{list['index']}}{{list['child_index']}}">
                    {% for file in list['fileInfo']%}
                    <div class="col-sm-4">
                        <div class="file-item">
                            <a href="/user/file/{{ file['id'] }}" target="_blank" class="file-item-link"  title="{{ file['file_name'] }}">
                                {% if file['file_type'] == 2 %}
                                <div class="file-item-icon file-item-icon-video">
                                    <div class="check js-check" style="display: none;"></div>
                                </div>
                                {% endif %}
                                {% if file['file_type'] == 3 %}
                                <div class="file-item-icon file-item-icon-jpg"><div class="check js-check" style="display: none;"></div></div>
                                {% endif %}
                                {% if file['file_type'] == 4 %}
                                <div class="file-item-icon file-item-icon-mp3"><div class="check js-check" style="display: none;"></div></div>
                                {% endif %}
                                {% if file['file_type'] == 5 %}
                                <div class="file-item-icon file-item-icon-{{ file['ext'] }}"><div class="check js-check" style="display: none;"></div></div>
                                {% endif %}
                                {% if file['file_type'] == 6 %}
                                <div class="file-item-icon file-item-icon-{{ file['ext'] }}"><div class="check js-check" style="display: none;"></div></div>
                                {% endif %}
                                <div class="file-item-name">{{ file['file_name'] }}</div>
                                <div class="file-item-size">文件大小：{{ file['fileSize'] }}</div>
                                <!--<div class="file-item-download"><i class="file-item-icon-download"></i>下载：300</div>-->
                            </a>
                        </div>
                    </div>
                    {% endfor %}
                </div>

                </div>
                {% endfor %}
            </div>
            <!-- 相关资料 end -->
        </div>
    </div>
    <!-- 左边 end -->
    <div class="sidebar">
        <div class="teacherBlock block mb20 bm">
            <div class="hd borc4">
                <h2>老师介绍</h2>
            </div>
            <div class="bd">
                <div class="teacher">讲师：
                    <a href="/user/zone/{{ teacherInfo.uid }}" target="_blank"><img src="{% if teacherInfo.headpic %}/frontend/source/getFrontImageThumb/header/{{ teacherInfo.uid }}/20/20" {% else %}/img/frontend/camtasiastudio/default-avatar.png{% endif %}" class="img-circle"
                                                               width="20" height="20"/>{{ teacherInfo.nick_name }}</a>
                </div>
                <div class="teacherAbout js-teacher" data-uid="{{teacherInfo.uid}}">{{ teacherInfo.desc }}</div>
            </div>
        </div>
        <!-- 讲师 end -->

        <!-- 即将开始课程 end -->
    </div>
    <!-- 右边 end -->
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
                <a href="/user/zone/<% comment.uid %>" target="_blank" class="name"><% comment.userName %></a>
            </div>
            <p class="msg"><% comment.content %></p>

            <div class="b clearfix">
                <p class="time"><% comment.create_time %></p>
            </div>
        </div>
        <% /if %>
        <% if i > 0 %>
        <div class="replyCon clearfix  comment-row"><span class="pull-left"><% comment.refUserName %>回复：</span>

            <div class="info"><span class="name"><% comment.userName %></span>
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
{#artTemplate#}
{{ javascript_include("/3rdpart/template/template.js") }}
{{ javascript_include('js/frontend/less2/layer/layer.js') }}
{{ javascript_include("js/frontend/course/courseDetailModel.js") }}
{{ javascript_include("js/frontend/course/courseDetail.js") }}


{% endblock %}