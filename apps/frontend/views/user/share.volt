{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    <style type="text/css">
        .body_container .title{ border-bottom: 0;}
        .tab-content .item{ border-bottom: 1px solid #eee; padding: 5px 5px 8px;}
        .tab-content .item .date{ color: #999;}
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
                            <li role="presentation" class="active"><a href="#mypush"  data-toggle="tab">我的发布</a></li>
                            <li role="presentation"><a href="#myshare"  data-toggle="tab">个人分享</a></li>
                            <li role="presentation"><a href="#myshow"  data-toggle="tab">空间可见</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="mypush"></div>
                        <div role="tabpanel" class="tab-pane" id="myshare"></div>
                        <div role="tabpanel" class="tab-pane" id="myshow"></div>
                        <div class="page-wrap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script id="mypush-template" type="text/html">
    <%each list as item %>
    <div class="item">
        <div class="row">
            <div class="col-sm-9"><a href="/user/file/<% item.user_file_id %>" target="_blank"><% item.push_file_name %></a></div>
            <div class="col-sm-3 date">发布时间：<% item.push_date_folder %></div>
        </div>
    </div>
    <% /each %>
</script>
<script id="myshare-template" type="text/html">
    <%each list as item %>
    <div class="item">
        <div class="row">
            <div class="col-sm-9"><a href="/user/file/<% item.user_file_id %>" target="_blank"><% item.file_name %></a></div>
            <div class="col-sm-3 date">分享时间：<% item.addtime %></div>
        </div>
    </div>
    <% /each %>
</script>
<script id="myshow-template" type="text/html">
    <%each list as item %>
    <div class="item">
        <div class="row">
            <div class="col-sm-9"><a href="/user/file/<% item.id %>" target="_blank"><% item.file_name %></a></div>
            <div class="col-sm-3 date"></div>
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
{{ javascript_include("js/frontend/user/share.js") }}
{% endblock %}