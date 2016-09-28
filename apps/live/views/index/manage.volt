{% extends "templates/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/home.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/basic.css")  }}
{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="center_wrap container">
            {% include 'templates/col-side.volt' %}
            <div id="content" class="detail_wrapper body_container">
                <div class="title clearfix">
                    <a href="/live/index/edit" class="btn post pull-right"><i class="fa-live-post"></i>发布直播</a>
                    <h2>直播管理</h2>
                </div>
                <div  id="myLive">
                    <div class="classList">


                    </div>
                    <!-- 列表结束  clasList end -->
                    <div class="page-wrap"></div>
                </div>
            </div>
            <!-- 主体内容 content end -->
        </div>
    </div>

    <script id="templateList" type="text/html">

        <%each list as items i%>
        <!-- 发布时间循环-->
        <div class="itemBox"> <i class="line"></i>
            <div class="timeBar"><i class="fa-radius"></i>
                <div class="postTime"><span><%items.created_time%></span></div>
            </div>
            <!--发布时间内部循环-->
            <div class="item" data-id="<%items.id%>">
                <a href="/live/index/detail/<%items.id%>" target="_blank" class="pic">
                    <img src="/frontend/source/getFrontImageThumb/live/<%items.id%>/340/190" width="" height="" alt="" />
                <% if items.live_status == 0 %>
                <i class="fa-yg"></i>
                <% /if %>
                <% if items.live_status == 1 %>
                <i class="fa-play"></i>
                <% /if %>
                <% if items.live_status == 2 %>
                <i class="fa-hk"></i>
                <% /if %>
                </a>
                <div class="info">
                    <h1><a href="/live/index/detail/<%items.id%>" target="_blank"><%items.name%></a></h1>
                    <p class="time">开始时间：<%items.start_time%></p>
                    <p class="time">结束时间：<%items.end_time%></p>
                    <div class="operate cl">
                        <% if items.live_status != 2 %>
                        <a href="<% url %>/play/<%items.id%>" target="_blank"><i class="fa-edit"></i>导播</a>
                        <% /if %>
                        <a href="javascript:void(0)" class="js-del"><i class="fa-del"></i>删除</a>
                        <% if items.live_status != 2 %>
                        <a href="/live/index/edit/<%items.id%>"><i class="fa-edit"></i>编辑</a>
                        <% /if %>
                    </div>
                </div>
            </div>
            <!--发布时间内部循环 end-->

        </div>
        <!-- 发布时间循环 end -->

        <%/each%>
        <!-- 循环体 item end -->

    </script>

{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/live/createModel.js") }}
    {{ javascript_include("3rdpart/template/template.js") }}
    {#弹窗#}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/live/manage.js") }}

{% endblock %}