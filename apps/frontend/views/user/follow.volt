{% extends "template/basic-v3.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    <style type="text/css">
        .body_container .title{ border-bottom: 0;}
        .tab-content .item{ border-bottom: 1px solid #eee; padding: 5px 5px 8px; line-height: 32px;}
        .tab-content .item span{ color: #999;}
    </style>
{% endblock %}
{% block content %}
    <div class="inner_body content">
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="title clearfix">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#myfollow"  data-toggle="tab">我的关注</a></li>
                            <li role="presentation"><a href="#myfans"  data-toggle="tab">我的粉丝</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="myfollow"></div>
                        <div role="tabpanel" class="tab-pane" id="myfans"></div>
                        <div class="page-wrap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script id="myfollow-template" type="text/html">
    <%each list as item %>
    <div class="item">
        <div class="row">
            <div class="col-sm-1"><a href="/user/zone/<% item.uid %>" target="_blank"><img src="<% if item.headpic %>/frontend/source/getFrontImageThumb/header/<%item.uid%>/32/32<% else %>/img/frontend/camtasiastudio/default-avatar.png<% /if %>" class="img-circle" width="32" height="32"></a></div>
            <div class="col-sm-3"><a href="/user/zone/<% item.uid %>" target="_blank"><% item.nick_name %></a></div>
            <div class="col-sm-3"><span><% item.job %></span></div>
            <div class="col-sm-2"><span><% item.sex %></span></div>
            <div class="col-sm-3"><span><% item.class %></span></div>
        </div>
    </div>
    <% /each %>
</script>
<script id="myfans-template" type="text/html">
    <%each list as item %>
    <div class="item">
        <div class="row">
            <div class="col-sm-1"><a href="/user/zone/<% item.uid %>" target="_blank"><img src="<% if item.headpic %>/frontend/source/getFrontImageThumb/header/<%item.uid%>/32/32<% else %>/img/frontend/camtasiastudio/default-avatar.png<% /if %>" class="img-circle" width="32" height="32"></a></div>
            <div class="col-sm-3"><a href="/user/zone/<% item.uid %>" target="_blank"><% item.nick_name %></a></div>
            <div class="col-sm-3"><span><% item.job %></span></div>
            <div class="col-sm-2"><span><% item.sex %></span></div>
            <div class="col-sm-3"><span><% item.class %></span></div>
        </div>
    </div>
    <% /each %>
</script>
{% endblock %}
{% block pageJs %}
{{ javascript_include("js/frontend/col-side.js") }}
{{ javascript_include("/js/frontend/basic.js") }}
{#artTemplate#}
{{ javascript_include("/3rdpart/template/template.js") }}
{{ javascript_include("js/frontend/user/follow.js") }}
{% endblock %}