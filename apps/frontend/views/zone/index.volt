{% extends "template/basic-v3.volt" %}
{% block pageCss %}
{{ stylesheet_link("/css/frontend/web/list.css") }}
{% endblock %}

{% block content %}
{% include "template/banner.volt" %}
<div class="content-wrap zone-wrap">
    <div class="content-top">
        <div class="container clearfix">
            <div class="search pull-right">
                <div class="s-btn js-search-zone"></div>
                <input type="text" class="keyword"  name="zone-user-name" placeholder="用户名">
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="container clearfix">
            <div class="sidebar pull-left">
                <div class="menu">
                    <ul class="js-menu">
                        <li class="clearfix active" data-id="0"><a href="javascript:;" rel="nofollow">全部</a></li>
                        {% for list in subjects %}
                        <li class="clearfix" data-id="{{list['id']}}"><a href="javascript:;" rel="nofollow">{{list['subject_name']}}</a></li>
                        {% endfor %}
                    </ul>
                </div>
            </div>
            <div class="content">
                <div class="title live-title clearfix">
                    <h2>全部讲师</h2>
                </div>
                <div class="list-wrap">
                    <div class="zone-list" id="js-user-list" data-total="{{ total }}">
                        {% for userInfo in userInfoList %}
                        <div class="media">
                            <div class="media-left">
                                <a href="/user/zone/{{ userInfo['userInfo']['uid'] }}">
                                    <img class="media-object" src="{% if  userInfo['userInfo']['headpic']  %}/frontend/source/getFrontImageThumb/header/{{userInfo['userInfo']['uid'] }}/280/280{% else %} /img/frontend/camtasiastudio/default-avatar.png  {% endif %}" width="280" height="280" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="media-top">
                                    <div class="clearfix">
                                        <span class="job pull-right">{{ userInfo['userInfo']['job'] }}</span>
                                        <h4 class="media-heading">{{ userInfo['userInfo']['nick_name'] }}</h4>
                                    </div>
                                    <p class="class">主讲课程：</p>
                                </div>
                                <div class="media-info">
                                    <dl class="clearfix">
                                        <dt class="pull-left">讲师介绍：</dt>
                                        <dd>{{ userInfo['userInfo']['desc'] }}</dd>
                                    </dl>
                                </div>
                                <div class="media-bottom text-right">
                                    <span class="follow">{{ userInfo['userInfo']['followCount'] }}人已关注</span>
                                </div>
                            </div>
                        </div>
                        {% endfor %}
                    </div>
                    <!-- 名师列表 end -->
                    <div class="page-wrap"></div>
                    <!-- 分页 -->
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/html" id="template-userlist">
    <%each userInfoList as userInfo  %>
    <div class="media">
        <div class="media-left">
            <a href="/user/zone/<% userInfo['userInfo']['uid'] %>">
                <img class="media-object" src="<% if  userInfo['userInfo']['headpic']   %>/frontend/source/getFrontImageThumb/header/<%userInfo['userInfo']['uid']  %>/280/280<% else %> /img/frontend/camtasiastudio/default-avatar.png  <% /if %>" width="280" height="280" alt="...">
            </a>
        </div>
        <div class="media-body">
            <div class="media-top">
                <div class="clearfix">
                    <span class="job pull-right"><% userInfo['userInfo']['job'] %></span>
                    <h4 class="media-heading"><% userInfo['userInfo']['nick_name'] %></h4>
                </div>
                <p class="class">主讲课程：<% userInfo['subject_name'] %></p>
            </div>
            <div class="media-info">
                <dl class="clearfix">
                    <dt class="pull-left">讲师介绍：</dt>
                    <dd><% userInfo['userInfo']['desc'] %></dd>
                </dl>
            </div>
            <div class="media-bottom text-right">
                <span class="follow"><% userInfo['userInfo']['followCount'] %>人已关注</span>
            </div>
        </div>
    </div>
    <% /each %>
</script>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs%}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include('js/frontend/less2/layer/layer.js') }}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {{ javascript_include("/js/frontend/zone/zone.js") }}
{% endblock %}