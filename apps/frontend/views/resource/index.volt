{% extends 'template/basic-v3.volt' %}
{% block pageCss %}
{{ stylesheet_link("/css/frontend/web/list.css") }}
{% endblock %}
{% block content %}
{% include "template/banner.volt" %}
<div class="content-wrap file-wrap">
    <div class="content-top">
        {% include "template/subject.volt" %}
    </div>
    <div class="content-body">
        <div class="container clearfix">
            <div class="sidebar pull-left">
                <div class="menu">
                    <ul class="js-menu">
                        <li class="active clearfix" data-type="0"><a href="javascript:;" rel="nofollow"><i class="icon icon-all"></i>全部格式</a></li>
                        <li class="clearfix"  data-type="2"><a href="javascript:;" rel="nofollow"><i class="icon icon-video"></i>视频</a></li>
                        <li class="clearfix"  data-type="4"><a href="javascript:;" rel="nofollow"><i class="icon icon-audio"></i>音频</a></li>
                        <li class="clearfix"  data-type="5"><a href="javascript:;" rel="nofollow"><i class="icon icon-doc"></i>文档</a></li>
                        <li class="clearfix"  data-type="3"><a href="javascript:;" rel="nofollow"><i class="icon icon-img"></i>图片</a></li>
                        <li class="clearfix"  data-type="6"><a href="javascript:;" rel="nofollow"><i class="icon icon-other"></i>其他</a></li>
                    </ul>
                </div>
            </div>
            <div class="content">
                <div class="title file-title clearfix">
                    <h2>资源列表</h2>
                </div>
                <div class="list-wrap clearfix">
                    <div class="file-list ">
                        <div class="row js-list" data-allcount={{ allFileCounter }}>

                        </div>
                    </div>
                    <!-- 直播列表 end -->
                    <div class="page-wrap"></div>
                    <!-- 分页 -->
                </div>
            </div>
        </div>
    </div>

</div>
{% endblock %}
{% block commonModal %}
<script type="text/html" id="template-resource">
    <%each userFiles as userFile  %>
    <div class="col-sm-4">
        <div class="item file-item">
            <a href="/file/detail/<%userFile['file_id']%>">
                <div class="item-pic">
                    <% if userFile['file_type'] === '2' %>
                    <div class="icon-play"></div>
                    <img alt="not authorized" src="/api/source/getImageThumb/<%userFile['file_id']%>/320/180" width="320" height="180"  />
                    <% /if %>
                    <% if userFile['file_type'] === '3' %>
                    <img alt="not authorized" src="/api/source/getImageThumb/<%userFile['file_id']%>/320/180" width="320" height="180"  />
                    <% /if %>
                    <% if userFile['file_type'] === '4' %>
                    <div class="icon icon-mp3"></div>
                    <% /if %>
                    <% if userFile['file_type'] === '5' %>
                    <div class="icon icon-<% userFile['push_file_name'] | setType:"other" %>"></div>
                    <% /if %>
                    <% if userFile['file_type'] === '6' %>
                    <div class="icon icon-<% userFile['push_file_name'] | setType:"other" %>"></div>
                    <% /if %>
                </div>
                <div class="item-tit ellipsis"><%userFile['push_file_name']%></div>
                <div class="item-b clearfix">
                    <span class="time pull-right"><%userFile['subject_name']%></span>
                    <span class="author">BY <%userFile['userInfo']['nick_name']%></span>
                </div>
                <div class="item-detail">
                    <div class="item-desc"><%userFile['file_desc']%></div>
                    <div class="item-num"><span>点赞：<i class="icon icon-like"></i><%userFile['like']%></span><span>下载：<i class="icon icon-down"></i><%userFile['download']%></span></div>
                </div>
            </a>
        </div>
    </div>
    <%/each%>
</script>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("/js/frontend/subject.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {#layer#}
    {{ javascript_include("/js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("/js/frontend/resourceModel.js") }}
    {{ javascript_include("/js/frontend/resource.js") }}
{% endblock %}

