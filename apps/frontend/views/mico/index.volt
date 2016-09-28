{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/home.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/basic.css")  }}
{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="center_wrap container">
            {% include 'template/col-side.volt' %}
            <div class="body_container">
                <div class="title clearfix">
                    <a href="/mico/edit" class="post btn pull-right"><i class="fa-post"></i><span>添加微课</span></a><h2>我的微课</h2>
                </div>
                <div class="classList" id="myClass">



                </div>
                <!-- 列表结束  clasList end -->
                <div class="page-wrap"></div>
            </div>
            <!-- 主体内容 content end -->
        </div>
    </div>

    <script id="templateList" type="text/html">
        <%each list as item%>
        <div class="item" data-id="<%item.id%>">
            <a href="/mico/detail/<%item.id%>" target="_blank" class="pic"> <img src="/frontend/source/getFrontImageThumb/mlesson/<%item.id%>/340/190" width="" height="" alt="" /> <i class="fa-play"></i> </a>
            <div class="info">
                <h1><a href="/mico/detail/<%item.id%>" target="_blank"><%item.title%></a></h1>
                <p class="desc"><%item.desc%></p>
                <p class="time">时长：<span><%item.timeLone%></span></p>
                <!--<div class="tags cl">-->
                    <!--<%if item.label%>-->
                    <!--<%each item.label.split(',') as label%>-->
                    <!--<span class="label"><%label%></span>-->
                    <!--<%/each%>-->

                    <!--<%/if%>-->
                <!--</div>-->
                <div class="output cl">
                    <div class="fr operate">
                        <a href="javascript:void(0);" class="js-del"><i class="fa-del"></i>删除</a> <a href="/mico/edit/<%item.id%>"><i class="fa-edit"></i>编辑</a>
                    </div>
                    <!--<p class="num"> <span class="red">群组发布</span> / <span><em class="red">65</em>点赞</span></p>-->
                </div>
            </div>
        </div>
        <%/each%>

    </script>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("3rdpart/template/template.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/mico/my-mico.js") }}
    {{ javascript_include("js/frontend/mico/myMicoModal.js") }}
{% endblock %}