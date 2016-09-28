{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less2/common.css") }}
    {{ stylesheet_link("css/frontend/less2/home.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    {{ stylesheet_link("css/frontend/basic.css")  }}
    {{ stylesheet_link("css/frontend/mico-study.css")  }}

{% endblock %}
{% block content %}
    <div class="inner_body">
        <div class="center_wrap container">
            {% include 'template/col-side.volt' %}
            <div class="body_container">
                <div class="title">
                    <h2>微课学习</h2>
                </div>
                <div class="classList">

                </div>
                <!-- 列表结束  clasList end -->
                <div class="page-wrap"></div>
            </div>
        </div>
    </div>
{% endblock  %}
{% block commonModal %}
<script id="templateList" type="text/html">
    <%each list as item%>
    <div class="item" date-id="<%item.id%>">
        <a href="/mico/detail/<%item.mlesson.id%>" target="_blank" class="pic"> <img src="/frontend/source/getFrontImageThumb/mlesson/<%item.mlesson.id%>/340/190" width="" height="" alt="" /> <i class="fa-play"></i> </a>
        <div class="info">
            <h1><a href="/mico/detail/<%item.mlesson.id%>" target="_blank"><%item.mlesson.title%></a></h1>
            <p class="">讲师：<%item.userinfo.nick_name%></p>
            <p class="desc"><%item.mlesson.desc%></p>
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
                    <a href="javascript:void(0);" class="js-del"><i class="fa-del"></i>删除</a>
                </div>
                <!--<p class="num"> <span class="red">群组发布</span> / <span><em class="red">65</em>点赞</span></p>-->
            </div>
        </div>
    </div>
    <%/each%>

</script>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("3rdpart/template/template.js") }}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/mico/study.js") }}
    {{ javascript_include("js/frontend/mico/studyModal.js") }}
{% endblock %}