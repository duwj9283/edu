{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    <style>
        #wraper{ padding:30px 0; background-color: #f1f1f1;}
        .article-wrap{  padding:30px; background: #fff; border:1px solid #eee; box-shadow: 0 3px 3px #eee;}
        .article-title{ border-bottom:1px solid #eee; padding-bottom: 20px;}
        .article-title h1{ font-size:20px; color:#000;}
        .article-title .article-time{ padding-top:15px; color: #999; font-size: 12px;}
        .article-content{ padding: 20px; font-size: 16px; line-height: 1.8;}
        .article-content p{ padding-bottom: 20px; text-indent: 2em;}

    </style>
{% endblock %}
{% block content %}
    <div id="wraper">
        <div class="container">
            <div class="article-wrap ">
                <div class="article-title text-center row">
                    <h1>{{ news['title'] }}</h1>
                    <p class="article-time" >{{ news['addtime'] }}</p>
                </div>
                <div class="article-content row">
                    {{ news['content'] }}
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block commonModal %}

{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("/js/frontend/basic.js") }}
<script type="text/javascript">
    /*下载分享文件*/

</script>
{% endblock %}