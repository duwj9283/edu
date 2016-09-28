{% extends "template/basic-v3.volt" %}
{% block pageCss %}
{{ stylesheet_link("/css/frontend/web/list.css") }}
{% endblock %}
{% block content %}
{% include "template/banner.volt" %}
<div class="content-wrap mico-wrap">
    <div class="content-top">
        <div class="container">
            <div class="search pull-right">
                <div class="s-btn" id="js-s-mico-btn"></div>
                <input type="text" name="mico-kewords" class="keyword" placeholder="模糊查询">
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="container clearfix">
            <div class="sidebar pull-left">
                <div class="menu subject">

                    <ul class="js-subject-list">
                        <li class="clearfix active" data-id="0"><a href="javascript:;"><span class="pull-right"></span>全部</a></li>
                        {% for list in subjects%}
                        <li class="clearfix" data-id="{{list['id']}}"><a href="javascript:;"><span class="pull-right">{{list['mlessonCount']}}</span>{{list['subject_name']}}</a></li>
                        {% endfor %}
                    </ul>
                    <div class="subject-more btn" id="js-subject-more">选择其他<span class="icon-arrow"></span>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="title mico-title clearfix">
                    <h2 class="mico-title">微课列表</h2>
                </div>
                <div class="list-wrap ">
                    <div class="mico-list" id="js-list">

                    </div>
                    <!-- 课程列表 end -->
                    <div class="page-wrap"></div>
                    <!-- 分页 -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="subject-wrap" id="js-subject-wrap" style="display: none;">
    <div class="subject-title clearfix">
        <h2 class="pull-left">专业列表（按微课数目排序）</h2>
        <div class="search-subject pull-left form-inline">
            <div class="form-group">
                <label class="sr-only" >专业</label>
                <input type="text" class="form-control" name="subject-name" placeholder="搜索专业">
            </div>
            <button type="submit" class="btn btn-subject js-s-btn">查询</button>
        </div>
    </div>
    <div class="subject-content">
        <ul class="clearfix js-subject-list" id="js-subject-list">
        </ul>
        <div class="text-center"><div class="btn js-more">加载更多</div></div>
    </div>
</div>
<script id="templateList" type="text/html">
    <%each list as item%>
    <div class="media">
        <div class="media-left">
            <a href="/mico/detail/<%item.id%>">
                <img class="media-object" src="/frontend/source/getFrontImageThumb/mlesson/<%item.id%>/455/255" width="455" height="255" alt="<%item.title%>">
            </a>
        </div>
        <div class="media-body">
            <div class="media-top clearfix">
                <span class="pull-right"><%item.subject_name%></span>
                <h4 class="media-heading"><%item.title%></h4>
            </div>
            <div class="media-info">
                <dl class="clearfix">
                    <dt class="pull-left">简介：</dt>
                    <dd><%item.desc%></dd>
                </dl>
                <dl class="clearfix">
                    <dt class="pull-left">时长：</dt>
                    <dd class="time"><%item.timeLone%></dd>
                </dl>
            </div>
            <div class="media-bottom text-right">
                <span>BY <%item.userinfo.nick_name%></span>
                <span><%item.count%>人已学</span>
            </div>
        </div>
    </div>
    <%/each%>
</script>
<script type="text/html" id="template-subject">
    <%each list as item%>
    <li data-id="<%item.id%>"><a href="javascript:;" rel="nofollow"><%item.subject_name%>(<span><%item.mlessonCount%></span>)</a></li>
    <%/each%>
</script>
{% endblock %}
{% block pageJs%}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("/js/frontend/subject.js") }}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {{ javascript_include("/js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("/js/frontend/mico/publistModel.js") }}
    {{ javascript_include("/js/frontend/mico/publist.js") }}
    <script type="text/javascript">

    </script>
{% endblock %}