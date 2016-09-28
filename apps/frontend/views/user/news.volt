{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
{% endblock %}
{% block content %}
    <div class="inner_body content">
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    <div class="news-wrap">
                        <div class="news-tit title clearfix">
                            <a href="/user/addNews" class="btn add-news pull-right"><i class="fa-post-news"></i>新建文章</a>
                            <h2>文章管理</h2>
                        </div>
                        <ul class="news-list news-content" id="js-news" data-total="{{ total }}">
                        </ul>
                        <div class="pager-wrap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script id="templateNewsList" type="text/html">
        <%each list as news%>
        <li class="news-item clearfix"><span class="time pull-right"><% news.addtime %></span><a
                    href="/user/editNews/<% news.id %>"><% news.title %></a></li>
        <%/each%>
    </script>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/col-side.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {{ javascript_include("/js/frontend/basic.js") }}
    {{ javascript_include("/js/frontend/user/news.js") }}
    <script type="text/javascript">
        $(function () {
            $('.body_side li.btn_person').addClass('selected');
        });
    </script>
{% endblock %}