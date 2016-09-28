{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("3rdpart/bootstrap/css/bootstrap-theme.min.css") }}
    {{ stylesheet_link("css/frontend/less2/preview.css") }}
{% endblock %}
{% block content %}
    <div id="wraper">
        <div class="breadcrumbBar">
            <div class="container">
                <div class="breadcrumb"><a href="/">发现</a> > <a href="/resource">资源中心</a> > <span>{{ file['subject_name'] }}</span> > <span>{{ file['file_name'] }}</span>
                </div>
            </div>
        </div>
        <div class="container" id="article">
            <div class="preview clearfix">
                <div class="previewLeft">
                    <div class="preview-container">
                        {% if( file['file_type'] == 2) %}
                            <div id="video-container" data-path="{{ file['path'] }}" data-uid="{{ file['uid'] }}" data-filename="{{ file['file_name'] }}" data-fid="{{ file['user_file_id'] }}"></div>
                        {% elseif(file['file_type'] == 3) %}
                            <div class="pic-preview  text-center">
                                <img src="/api/source/getImageThumb/{{file['user_file_id']}}/730/500" width="730" height="500">
                            </div>
                        {% elseif(file['file_type'] == 4) %}
                            <div id="audio-container" data-path="{{ file['path'] }}" data-uid="{{ file['uid'] }}" data-filename="{{ file['file_name'] }}" data-fid="{{ file['user_file_id'] }}"></div>
                        {% elseif(file['file_type'] == 5) %}
<!--                            <iframe id='iframe-pdf' src="/3rdpart/generic/web/viewer.html?file=/api/file/filePreview/{{ file['user_file_id'] }}"></iframe>-->

                            <div class="ppt-wrap js-ppt" data-fileid="{{ file['user_file_id'] }}">
                                <div class="ppt-con">
<!--                                    <a href="javascript:;" rel="nofollow" class="pre">-->
<!--                                        <i class="icon icon-pre"></i>-->
<!--                                    </a>-->
<!--                                    <a href="javascript:;" rel="nofollow" class="next"><i class="icon icon-next"></i></a>-->
                                    <div class="loading"></div>

                                </div>
                                <div class="ppt-bar">
                                    <a href="javascript:;" class="pre"></a>
                                    <span><input id="txtPictureIndex" type="text" value="1"> / <em class="js-total"></em></span>
                                    <a href="javascript:;" class="next"></a>
                                </div>
                            </div>
                        {% elseif( file['file_type'] == 6 or file['file_type'] == 7) %}
                            <div class="unknow">无法预览</div>
                        {% endif %}
                    </div>
                    <div class="operate clearfix">
<!--                        <a  class="share"><i class="icon icon-share"></i>分享</a>-->
                        <a href="javascript:;" rel="nofollow"  class="down"><i class="icon icon-down"></i>下载</a>
                        <a href="javascript:;" rel="nofollow"  class="collect"><i class="icon icon-collect"></i>收藏</a>
<!--                        <a  class="like"><i class="icon icon-like"></i>点赞</a>-->
                    </div>
                    <div id="comments" class="bm">
                        {% if bSignedIn %}
                        <div class="title">评论</div>
                        <div id="cPost" class="clearfix">
                            <form class="file-comment-form" onsubmit="return false;">
                                <div class="input-wrap">
                                    <textarea class="cText file-comment-input" required></textarea>
                                </div>
                                <button class="cBtn file-comment">发表评论</button>
                            </form>
                        </div>
                        {%else%}
                        <p class="bg-danger" style="padding:15px 20px; margin-bottom: 40px;">登录后可参与评论</p>
                        {% endif%}
                        <!-- 评论列表 start -->
                        <div class="cList" id="cmtList">
                            {% for fileComment in file['file_comment'] %}
                            <div class="cItem clearfix">
                                {% for index,comment in fileComment %}
                                {% if index === 0 %}
                                <div class="faceBar pull-left">
                                    <img src="/img/frontend/less2/user.png" width="34" height="34" class="face" />
                                </div>
                                <div class="info comment-row">
                                    <div class="t clearfix">
                                        <a href="/user/zone/{{ comment['uid']}}" target="_blank" class="name">{{ comment['userName'] }}</a>
                                    </div>
                                    <p class="msg">{{ comment['content'] }}</p>
                                    <div class="b clearfix">
                                        <p class="time">{{ comment['date'] }}</p>
                                        <div class="operate">
                                            {% if bSignedIn %}
                                            <a href="javascript:;" rel="nofollow"  class="reply" data-cmt-uid ={{ comment['uid'] }} data-cmt-id={{ comment['id'] }}>回复</a>
                                            {% endif %}
                                            {#<a href="#" class="good"><i class="fa-good"></i>0</a>#}
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
                                        <p class="time">{{ comment['date'] }}</p>
                                    </div>
                                </div>
                                {% endif %}
                                {% endfor %}
                            </div>
                            {% endfor %}
                        </div>
                        <!-- 评论列表 end -->
                    </div>
                </div>
                <div class="previewRight" id="file_info_container" data-id={{ file['user_file_id'] }}>
                    <h1 class="file-name">{{ file['file_name'] }}</h1>
                    <ul class="list">
                        <li class="user-name">{{ file['userInfo']['nick_name'] }}</li>
                        <li class="push-time">发布时间：{{ file['pushTime'] }}</li>
                        <li>应用类型：{{ file['application_type_name'] }}</li>
                        <li>学科：{{ file['subject_name'] }}</li>
                        <li>所属知识点：{{ file['knowledge_point'] }}</li>
                        <li>文件大小：{{ file['file_size_conv'] }}</li>
                        <li>语言：{{ file['language_name'] }}</li>
                        <li class="clearfix"><span class="pull-left">简介：</span>
                            <p class="about">{{ file['desc'] }}</p>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
{% endblock %}
{% block commonModal %}
    <script type="text/html" id="replay-row-container">
        <div class="replayForm replay-row-container">
            <textarea class="cText reply-comment-input"></textarea>
            <a class="btn btn-reply-comment pull-right" data-cmt-uid ='<% commentUId %>' data-cmt-id='<% commentId %>'>回复</a>
        </div>
    </script>
    <script type="text/html" id="comment-list">
        <% each fileComments as fileComment %>
            <div class="cItem clearfix">
                <% each fileComment as comment i %>
                    <% if i === 0 %>
                        <div class="faceBar pull-left">
                            <img src="/img/frontend/less2/user.png" width="34" height="34" class="face" />
                        </div>
                        <div class="info comment-row">
                            <div class="t clearfix">
                                <a href="#" class="name"><% comment.userName %></a>
                            </div>
                            <p class="msg"><% comment.content %></p>
                            <div class="b clearfix">
                                <p class="time"><% comment.date %></p>
                                <div class="operate">
                                    <a href="javascript:;" rel="nofollow" class="reply" data-cmt-uid ='<% comment.uid %>' data-cmt-id='<% comment.id %>'>回复</a>
                                    {#<a href="#" class="good"><i class="fa-good"></i>0</a>#}
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
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/fileJs/fileDetail.js") }}
    {{ javascript_include("js/frontend/fileJs/fileDetailModel.js") }}
    {#视频播放插件#}
    {{ javascript_include("3rdpart/jwplay/jwplayer.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
{% endblock %}