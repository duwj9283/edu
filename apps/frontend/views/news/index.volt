{% extends "template/basic-v3.volt" %}
{% block pageCss %}
{{ stylesheet_link("/css/frontend/web/list.css") }}
{% endblock %}

{% block content %}
{% include "template/banner.volt" %}
<div class="content-wrap" style="padding:0;background-color: #f4f4f4">
    <div class="container">
<!--    <div class="content-top">-->
<!---->
<!--    </div>-->
    <div class="notice-wrap">
        <div class="search-wrap clearfix">
            <div class="search pull-right">
                <div class="s-btn js-search-news"></div>
                <input type="text" class="keyword" name="news-kewords" placeholder="模糊查询">
            </div>
        </div>
        <div class="notice-list js-list" data-count="{{count}}">
            {% for index,list in news %}
            <article class="post" id="{{list['id']}}" name="{{list['id']}}">
                <h1 class="post-title"><a href="javascript:;">{{list['title']}}</a></h1>
                <div class="post-date">公告时间：{{list['updated_at']}}</div>
                <div class="post-by">公告来源：系统</div>
                <div class="post-content">
                    <span>内容：</span>{{list['content']}}
                </div>
                <div class="text-right">
                    <span  class="post-more js-post-more">详情</span>
                </div>
            </article>
            {%endfor%}
        </div>
        <div class="page-wrap">
        </div>
    </div>
        </div>
</div>
{% endblock %}
{% block pageJs%}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("/js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("/js/frontend/news/news.js") }}
{% endblock %}