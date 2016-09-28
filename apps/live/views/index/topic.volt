{% extends "templates/basic-v2.volt" %}
{% block pageCss %}
<style>
    .wrap{ padding:40px 0;}
    .main{ border:8px solid #515567; border-radius: 5px; }
    .video-flash{ height: 670px;}
    .video-content{ position: relative; padding:20px 50px 10px; }
    .video-content:before{ display: block; margin-bottom: 20px; content: ""; height: 1px; width: 100%; background: #666666; }
    .video-content{ min-height: 300px; background:#e6e6e6; border-top: 5px solid #515567; }
    .video-content .btn{margin-right:10px; padding:3px 12px; background: #515567;color: #fff;  }
    .video-content .form-btn{ margin-top:30px; }
    .video-content .form-btn .btn{ margin-right: 40px; }
    .video-content .control-label{ color: #515567; font-size: 18px; font-weight: normal;}
    .video-content .form-control{ border-radius: 0; color: #7d7d7d; font-size: 16px; border:0;}
    .video-content .video-form{ width: 45%; float: left; }
    .video-content .video-tab{ width: 600px; float: left; }
    .video-content .tab-content{ background: #fff; }
    .video-content .nav-tabs{ margin-left: 15px; border:none;}
    .video-content .nav-tabs li a{margin-left:-15px; width: 167px; height: 42px; background:url(/img/frontend/web/topic/tab-nav.png) no-repeat 0 0;border:none; border-radius: 0; color: #fff; font-size: 18px;}
    .video-content .nav-tabs>li.active>a, .video-content .nav-tabs>li.active>a:focus,.video-content .nav-tabs>li.active>a:hover{ position: relative; z-index: 3; border:none; background: url(/img/frontend/web/topic/tab-nav-active.png) no-repeat 0 0; color: #515567;}
    .table tr>th,.table tr>td{ padding-left: 20px;vertical-align: middle; font-weight: normal; border-color: #b4b4b4;font-size:16px;text-align: center;}
    .table tr>th{ color: #515567; }
    .table tr>th:last-of-type{ width: 170px; }
    .table tr>td:first-of-type,.table tr>th:first-of-type{ text-align: left; }
    .table tr>td{color: #7d7d7d;}
</style>
{% endblock %}
{% block content %}
<div class="wrap">
    <div class="container">
        <div class="main">
            <div class="video-flash">
                <div id="flashContent">
                    <p>
                        To view this page ensure that Adobe Flash Player version
                        20.0.0 or greater is installed.
                    </p>
                    <script type="text/javascript">
                        var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://");
                        document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='"
                            + pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" );
                    </script>
                </div>

                <noscript>
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%" id="liveTopic">
                        <param name="movie" value="/js/frontend/live/liveTopic.swf" />
                        <param name="quality" value="high" />
                        <param name="bgcolor" value="#ffffff" />
                        <param name="allowScriptAccess" value="sameDomain" />
                        <param name="allowFullScreen" value="true" />
                        <!--[if !IE]>-->
                        <object type="application/x-shockwave-flash" data="/js/frontend/live/liveTopic.swf" width="100%" height="100%">
                            <param name="quality" value="high" />
                            <param name="bgcolor" value="#ffffff" />
                            <param name="allowScriptAccess" value="sameDomain" />
                            <param name="allowFullScreen" value="true" />
                            <!--<![endif]-->
                            <!--[if gte IE 6]>-->
                            <p>
                                Either scripts and active content are not permitted to run or Adobe Flash Player version
                                20.0.0 or greater is not installed.
                            </p>
                            <!--<![endif]-->
                            <a href="http://www.adobe.com/go/getflashplayer">
                                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
                            </a>
                            <!--[if !IE]>-->
                        </object>
                        <!--<![endif]-->
                    </object>
                </noscript>
            </div>
            <div class="video-content clearfix">
                <div class="video-form">
                    <form id="topic-form" class="form-horizontal">
                        <div class="form-group">
                            <label for=""  class="col-sm-4 control-label">开始时间</label>
                            <div class="col-sm-5"><input type="text" value="" name="star-date" class="form-control"></div>
                            <div class="col-sm-1"><input type="checkbox" value="1" name="star"></div>
                        </div>
                        <div class="form-group end-time" style="display: none">
                            <label for=""  class="col-sm-4 control-label">结束时间</label>
                            <div class="col-sm-5"><input type="text" value="" name="end-date" class="form-control"></div>
                            <div class="col-sm-1"><input type="checkbox" value="2" name="end"></div>
                        </div>
                        <div class="form-group">
                            <label for=""  class="col-sm-4 control-label">名     称</label>
                            <div class="col-sm-6"><input type="text" value="" name="name" class="form-control" placeholder="输入名称"></div>
                        </div>
                        <div class="form-group form-btn">
                            <div class="col-sm-6 col-sm-offset-4"><a href="javascript:;" class="btn js-add-topic">创建</a><a href="javascript:;" class="btn js-reset">重置</a></div>
                        </div>
                    </form>
                </div>
                <div class="video-tab">
                    <ul class="nav nav-tabs"  id="js-tabs">
                        <li role="presentation" class="active"><a href="#a" aria-controls="home" role="tab" data-toggle="tab">知识点标签</a></li>
                        <li role="presentation"><a href="#b" aria-controls="profile" role="tab" data-toggle="tab">虚拟切片</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="a">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>名称</th>
                                        <th>开始时间</th>
                                        <th>维护</th>
                                    </tr>
                                </thead>
                                <tbody class="js-a">

                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="b">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>维护</th>
                                </tr>
                                </thead>
                                <tbody class="js-b">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="{{path}}" name="path">
<input type="hidden" value="{{live_id}}" name="id">
{% endblock %}
{% block commonModal %}
<script id="zsd" type="text/html">
    <%each list as item%>
    <tr id="<%item.id%>">
        <td><%item.name%></td>
        <td><%item.start_time%></td>
        <td><a href="javascript:;" class="btn js-edit" data-id="<%item.id%>">修改</a><a href="javascript:;" data-id="<%item.id%>" class="btn js-del">删除</a></td>
    </tr>
    <%/each%>
</script>
<script id="qp" type="text/html">
    <%each list as item%>
    <tr id="<%item.id%>">
        <td><%item.name%></td>
        <td><%item.start_time%></td>
        <td><%item.end_time%></td>
        <td><a href="javascript:;" class="btn js-edit" data-id="<%item.id%>">修改</a><a href="javascript:;" data-id="<%item.id%>" class="btn js-del">删除</a></td>
    </tr>
    <%/each%>
</script>
{% endblock %}
{% block pageJs %}
{{ javascript_include("/js/frontend/basic.js") }}
{{ javascript_include("js/frontend/col-side.js") }}
{{ javascript_include("3rdpart/template/template.js") }}
{{ javascript_include("3rdpart/swfobject/swfobject.js") }}
{{ javascript_include("js/frontend/less2/layer/layer.js") }}
{{ javascript_include("/js/frontend/live/topic.js") }}
{% endblock %}