{% extends "template/basic.volt" %}
{% block pageCss %}
    {{ stylesheet_link("css/frontend/less/lessbase.css") }}
    {{ stylesheet_link("css/frontend/col-side.css") }}
    <style>
        .w1120{
            width: 60%;
            min-width: 1150px;
            margin: 0 auto;
        }
        .pri-list>li>label {
            padding-top: 10px;
        }
    </style>
{% endblock %}
{% block content %}
    <div class="content">
        <div class="line"></div>
        <div class="person-con-infor">
            <div class="w1120 clearfix">
                {% include 'template/col-side.volt' %}
                <div class="person-con fr">
                    <div class="per-info-tit per-info-tit02 margin-top-30">
                        <span>个人空间</span>
                    </div>
                    <div class="line margin-top-10"></div>
                    <div class="person-index-block02 person-index-block widthauto margin-top-10">
                        <div class="clearfix space-person-infor">
                            <div class="fl person_pic-box">
                                <img  src="/img/frontend/camtasiastudio/person_pic.jpg" width="235" height="271">
                                <span > 51关注 / 125学生</span>
                            </div>
                            <div class="person-index-text fl">
                                <div class="clearfix per-infor-text">
                                    <div class="person-name fl">田甜甜 <span>教师</span><p>安徽  合肥</p></div>

                                </div>
                                <p>简介：Dream Offer，致力于为在校大学生提供专业的名企求职辅导与职业规划<br>咨询服务</p>
                                <p><span>学科：英语</span><span>三年级二班</span></p>
                                <p>
                                    <span>qq：22343434</span>
                                </p>
                                <p><span>电话：13567854456</span></p>
                                <p><span>邮箱：135678@126.com</span></p>

                            </div>
                        </div>
                        <div class="line margin-top-20"></div>
                        <div class="dt-list dt-list02 dt-center">
                            <a id="space-source" class="active" >
                                <i class="dt-ico dt-resource"> </i>
                                <span>资源</span>
                            </a>
                            <a id="space-dynamic">
                                <i class="dt-ico dt-actiing"></i>
                                <span>动态</span>
                            </a>
                            <a id="space-article" >
                                <i class="dt-ico dt-circle"></i>
                                <span>文章</span>
                            </a>
                            <a id="space-live" >
                                <i class="dt-ico dt-living"></i>
                                <span>直播</span>
                            </a>
                            <a id="space-mico" >
                                <i class="dt-ico dt-mir"></i>
                                <span>微课</span>
                            </a>
                            <a id="space-course" >
                                <i class="dt-ico dt-class"></i>
                                <span>课程</span>
                            </a>
                            <a id="space-active" >
                                <i  class="dt-ico dt-active"></i>
                                <span>活动</span>
                            </a>
                        </div>
                        <div class="text-align-right dt-text pt-right-value">
                            <span><span >34</span><span>群组</span></span>
                            <span><span >34</span><span>资源</span></span>
                            <span><span >34</span><span>点赞</span></span>
                        </div>
                        <div class="text-align-right dt-send-btn  pt-right-value">
                            <span class="send-btn">发布动态/文章</span>
                        </div>
                        <div class="line margin-top-30"></div>
                        <div id="space-content-list">
                            <div class="text-align-right">
                                <span class="load-span">下载</span>
                            </div>
                            <dl class="clearfix space-resource-dl" id="space-resource-dl">
                                <dt>
                                    <a href="" >
                                        <img src="/img/frontend/camtasiastudio/fh_file.png"  />
                                    </a>
                                    <span>返回</span>
                                </dt>
                                <dd>
                                    <a href="javascirpt:void(0)">
                                        <img src="/img/frontend/camtasiastudio/jgp.png"  />
                                        <b></b>

                                    </a>
                                    <span>游种起源.jpg</span>
                                    <span>文件大小：346kb</span>
                                </dd>
                                <dd>
                                    <a class="active" href="javascirpt:void(0)">
                                        <img src="/img/frontend/camtasiastudio/mp3.png"  />
                                        <b></b>
                                    </a>
                                    <span>游种起源.mp3</span>
                                    <span>文件大小：346kb</span>
                                </dd>
                                <dt>
                                    <a href="" >
                                        <img src="/img/frontend/camtasiastudio/file.png"  />
                                    </a>
                                    <span>日语学习</span>
                                </dt>
                                <dd>
                                    <a href="javascirpt:void(0)">
                                        <img src="/img/frontend/camtasiastudio/rar.png"  />
                                        <b></b>

                                    </a>
                                    <span>游种起源.rar</span>
                                    <span>文件大小：346kb</span>
                                </dd>
                                <dd>
                                    <a href="javascirpt:void(0)">
                                        <img src="/img/frontend/camtasiastudio/jgp.png"  />
                                        <b></b>

                                    </a>
                                    <span>游种起源.jpg</span>
                                    <span>文件大小：346kb</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
{% block pageJs %}
    {{ javascript_include("js/frontend/col-side.js") }}
    {{ javascript_include("js/frontend/userspace/userspace.js") }}
    {{ javascript_include("js/frontend/userspace/userspaceModal.js") }}
    <script type="text/javascript">
        $(function () {
            $('.body_side li.btn_person').addClass('selected');
        });
    </script>
{% endblock %}