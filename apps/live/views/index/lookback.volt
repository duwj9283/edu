{% extends "templates/basic-v2.volt" %}
{% block pageCss %}
<style>
    .wrap{ padding-bottom:40px;}

    .video-flash{ height: 670px;}
    .breadcrumbBar{ margin-bottom: 20px; background: #fff; box-shadow: 0 2px 2px 2px #eee;}
    .breadcrumb{ margin-bottom: 0; padding: 20px 0; background: #fff; color: #333;}
    .breadcrumb a{ color: #333;}
</style>
{% endblock %}
{% block content %}
<div class="wrap">
    <div class="breadcrumbBar">
        <div class="container">
            <div class="breadcrumb"><a href="/">发现</a> > <a href="/live">直播课堂</a> > <a href="/live/index/detail/{{id }}/">{{name }}</a> > <span>录像回看</span></div>
        </div>
    </div>
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
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%" id="liveLookback">
                        <param name="movie" value="/js/frontend/live/liveLookback.swf" />
                        <param name="quality" value="high" />
                        <param name="bgcolor" value="#ffffff" />
                        <param name="allowScriptAccess" value="sameDomain" />
                        <param name="allowFullScreen" value="true" />
                        <!--[if !IE]>-->
                        <object type="application/x-shockwave-flash" data="/js/frontend/live/liveLookback.swf" width="100%" height="100%">
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

        </div>
    </div>
</div>
<input type="hidden" value="{{path}}" name="path">

{% endblock %}
{% block commonModal %}

{% endblock %}
{% block pageJs %}
{{ javascript_include("/js/frontend/basic.js") }}
{{ javascript_include("js/frontend/col-side.js") }}
{{ javascript_include("3rdpart/template/template.js") }}
{{ javascript_include("3rdpart/swfobject/swfobject.js") }}
{{ javascript_include("js/frontend/less2/layer/layer.js") }}
<script type="text/javascript">
    function formatSeconds(value) {
        var s = parseInt(value);// 秒
        var m = '00';// 分
        var h = '00';// 小时
        if(s > 60) {
            m = parseInt(s/60);
            s = parseInt(s%60);
            if(m > 60) {
                h = parseInt(m/60);
                m = parseInt(m%60);
                if(h<10){
                    h='0'+ h.toString();
                }
            }
            if(m<10){
                m='0'+ m.toString();
            }
        }
        if(s<10){
            s='0'+ s.toString();
        }
        var result = h+":"+m+":"+s;
        return result;

    }
    function getPlayedTime(time)
    {

    }
    function swfLoadComplete()
    {
        var list=[
        {% for item in knowlegeList %}
            {'id':{{item['id']}},'name':'{{item['name']}}','startTime':formatSeconds({{item['start_time']}}),'type':{{item['type']}}},
        {% endfor %}
        {% for item in qiepianList %}
            {'id':{{item['id']}},'name':'{{item['name']}}','startTime':formatSeconds({{item['start_time']}}),'endTime':formatSeconds({{item['end_time']}}),'type':{{item['type']}}},
        {% endfor %}
    ];
        thisMovie('liveLookback').showKnowledgeList(list);
    }

    function addKnowledgeLabel()
    {
        var knowLedge = {'id':3,'name':'知识点san','startTime':'00:00:20','type':1}
        thisMovie('liveLookback').addKnowledgeLabel(knowLedge);
    }

    function delKnowledgeLabel()
    {
        thisMovie('liveLookback').deleteKnowledgeLabel(1);
    }

    function changeKnowledgeLabel()
    {
        var knowledge = {'id':2,'name':'虚拟切片2test...','startTime':'00:01:08','endTime':'00:01:20','type':2};
        thisMovie('liveLookback').changeKnowledgeLabel(knowledge);
    }

    //获取swf对象
    function thisMovie(movieName)
    {
        if (navigator.appName.indexOf("Microsoft") != -1)
        {
            return window[movieName]
        }
        else
        {
            return document[movieName]
        }
    }
</script>
<script type="text/javascript">
    var path = $('input[name="path"]').val();
    // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection.
    var swfVersionStr = "20.0.0";
    // To use express install, set to playerProductInstall.swf, otherwise the empty string.
    var xiSwfUrlStr = "playerProductInstall.swf";
    var flashvars = {};
    //流地址
    flashvars.streamUrl =config.rtmp;
    //流名称
    flashvars.streamName = path;
    //类型，1-直播，2-录播
    flashvars.type = 2;
    //发送已播放时间间隔,录播才需要,单位-秒
    flashvars.sendTimeInterval = 5;
    var params = {};
    params.quality = "high";
    params.bgcolor = "#ffffff";
    params.allowscriptaccess = "sameDomain";
    params.allowfullscreen = "true";
    var attributes = {};
    attributes.id = "liveLookback";
    attributes.name = "liveLookback";
    attributes.align = "middle";
    swfobject.embedSWF(
        "/js/frontend/live/liveLookback.swf", "flashContent",
        "100%", "100%",
        swfVersionStr, xiSwfUrlStr,
        flashvars, params, attributes);
    // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
    swfobject.createCSS("#flashContent", "display:block;text-align:left;");
</script>
{% endblock %}