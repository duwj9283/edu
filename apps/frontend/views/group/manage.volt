{% extends "template/basic-v2.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css")  }}
    <style>
        .w1120{
            width: 60%;
            min-width: 1150px;
            margin: 0 auto;
        }
        .widthauto.grounp-block{
            box-sizing: content-box;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="inner_body content">
        <div class="person-con-infor">
            <div class="container clearfix">
                {% include 'template/col-side.volt' %}
                <div class="body_container">
                    
					<div class="per-info-tit per-info-tit03 margin-top-40">
                        <span>群组管理</span>
                    </di
                    <div class="line"></div>
                    <div class=" person-index-block margin-top-30 person-gounp-block">
                       
                        <ul class="grounp-list  " id="infor-overflow" >
                            
                            {#<li class="clearfix">
                                <a class="dt-list-img fl">
                                    <b></b>
                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="41" height="41">
                                </a>
                                <a class="grounp-list-text col col-2 fl" href="">啊啊啊啊啊啊啊啊</a>
								<span class="col col-1 fl"><em class="job job-1">群主</em></span>
								<span class="num   col col-1 fl">人数：34</span>
								<span class="type  col col-2 fl">类别：班级组</span>
								<span class="subject col col-3 fl">教学事物</span>
                            </li>
							<li class="clearfix">
                                <a class="dt-list-img fl">
                                    <b></b>
                                    <img src="/img/frontend/camtasiastudio/small_pic.png" width="41" height="41">
                                </a>
                                <a class="grounp-list-text col col-2 fl" href="">啊啊啊啊啊啊啊啊</a>
								<span class="col col-1 fl"><em class="job">成员</em></span>
								<span class="num   col col-1 fl">人数：34</span>
								<span class="type  col col-2 fl">类别：班级组</span>
								<span class="subject col col-3 fl">教学事物</span>
                            </li>#}




                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script id="templateList" type="text/html">
        <%each list as item%>
        <li class="clearfix">
            <a class="dt-list-img fl" href="/group/zone">
                <b></b>
                <img src="/frontend/source/getFrontImageThumb/group/<%item.gid%>/41/41" width="41" height="41">
            </a>
            <a class="grounp-list-text col col-2 fl" href=""><%item.name%></a>
            <span class="col col-1 fl"><em class="job">成员</em></span>
            <span class="num   col col-1 fl">人数：34</span>
            <span class="type  col col-2 fl">类别：<%teams[item.type]%></span>
            <span class="subject col col-3 fl">教学事物</span>
        </li>

        <%/each%>

    </script>
{% endblock %}
{% block commonModal %}
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/jquery.nicescroll.js") }}
    {#模版#}
    {{ javascript_include("3rdpart/template/template.js") }}
    {#弹窗#}
    {{ javascript_include("js/frontend/less2/layer/layer.js") }}
    {{ javascript_include("js/frontend/group/manage.js") }}
    {{ javascript_include("js/frontend/group/manageModel.js") }}

{% endblock %}