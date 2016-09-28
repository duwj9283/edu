{% extends "template/basic-v3.volt" %}
{% block pageCss %}
{{ stylesheet_link("/css/frontend/web/list.css") }}
{% endblock %}
{% block content %}
{% include "template/banner.volt" %}
<div class="content-wrap course-wrap">
    <div class="content-top">
        <div class="container">
            <div class="select-wrap">
                <ul class="js-type">
                    <li class="active"  data-type="0"><a href="javascript:;" rel="nofollow">全部</a></li>
                    <li data-type="1"><a href="javascript:;" rel="nofollow">非连载课程</a></li>
                    <li  data-type="2"><a href="javascript:;" rel="nofollow">连载中</a></li>
                    <li  data-type="3"><a href="javascript:;" rel="nofollow">完结课程</a></li>
                </ul>

                <ul>
                    <li class="label">排序：</li>
                    <li role="presentation" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="js-sort-name">发布时间</span> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu js-sort">
                            <li data-sort="0"><a href="javascript:;" rel="nofollow">发布时间</a></li>
                            <li data-sort="1"><a href="javascript:;" rel="nofollow">课程热度</a></li>
                        </ul>
                    </li>
                </ul>
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
                <div class="title course-title clearfix">
                    <div class="search pull-right">
                        <div class="s-btn js-search-course"></div>
                        <input type="text" class="keyword" name="course-keyword" placeholder="模糊查询">
                    </div>
                    <h2 class="course-title">课程列表</h2>
                </div>
                <div class="list-wrap ">
                    <div class="course-list js-course-list">

                    </div>
                    <!-- 课程列表 end -->
                    <div class="page-wrap"></div>
                    <!-- 分页 -->
                </div>
            </div>
        </div>
    </div>
</div>
 <script id="templateList" type="text/html">
  <%each list as item%>
  <div class="media" data-id="<% item.id %>">
      <div class="media-left">
          <a href="/course/detail/<% item.id %>">
              <img class="media-object" src="/frontend/source/getFrontImageThumb/lesson/<% item.id %>/455/255" width="455" height="255" alt="<% item.title %>">
          </a>
      </div>
      <div class="media-body">
          <div class="media-top clearfix">
              <span class="pull-right"><% item.subject_name %></span>
              <h4 class="media-heading"><% item.title %></h4>
          </div>
          <div class="media-info">
              <dl class="clearfix" style="height: 100px; overflow: hidden;">
                  <dt class="pull-left">简介：</dt>
                  <dd><% item.desc %></dd>
              </dl>
              <dl class="clearfix">
                  <dt class="pull-left">时长：</dt>
                  <dd class="time"><% item.timeLone %></dd>
              </dl>
          </div>
          <div class="media-bottom text-right">
              <span>BY <% item.userInfo.nick_name %></span>
              <span><% item.study_count %>人已学</span>
          </div>
      </div>
  </div>
  <%/each%>
 </script>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/basic.js") }}
    {{ javascript_include('js/frontend/less2/layer/layer.js') }}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {{ javascript_include("js/frontend/course/courseModel.js") }}
    {{ javascript_include("js/frontend/course/course.js") }}

{% endblock %}