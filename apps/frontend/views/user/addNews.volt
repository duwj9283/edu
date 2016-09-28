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
                        <div class="news-tit">
                            <a href="/user/news" class="back-news">返回列表</a>
                        </div>
                        <div class="news-edit news-content">
                            <form name="news" class="form-horizontal" onsubmit="return false;">
                                <input type="hidden" name="id" value="0">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-1 control-label">标题</label>
                                    <div class="col-sm-11">
                                        <input type="text" class="form-control" value="" name="title" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-1 control-label">内容</label>
                                    <div class="col-sm-11">
                                        <script id="news-content" name="content"  ></script>
                                    </div>
                                </div>
                                <div class="form-group form-">
                                    <label for="inputEmail3" class="col-sm-1 control-label">状态</label>
                                    <div class="col-sm-11">
                                        <label class="radio-inline"">
                                        <input type="radio" name="status" checked value="0"> 显示
                                        </label>
                                        <label class="radio-inline"">
                                        <input type="radio" name="status" value="1"> 隐藏
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3 col-sm-offset-1">
                                        <input type="submit" class="pop-btn" value="提交">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("/js/frontend/col-side.js") }}
    {#artTemplate#}
    {{ javascript_include("/3rdpart/template/template.js") }}
    {#page#}
    {{ javascript_include("/js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("/js/frontend/less2/laypage/laypage.js") }}
    {{ javascript_include("/3rdpart/ueditor/ueditor.config.js") }}
    {{ javascript_include("/3rdpart/ueditor/ueditor.all.js") }}
    {{ javascript_include("/js/frontend/user/editNews.js") }}
    <script type="text/javascript">
        $(function () {
           $('.body_side li.btn_person').addClass('selected');
        });
    </script>
{% endblock %}